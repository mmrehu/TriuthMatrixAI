<?php
session_start();
require_once 'database.php';

$currentPage = 'profile';
$pageTitle = 'Profile';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Initialize variables with default values
$user = [];
$scan_count = 0;
$accuracy = 0;
$post_count = 0;
$user_rank = 0;
$username = 'User';
$email = '';
$bio = '';
$location = '';
$website = '';
$language = 'en';
$timezone = 'est';
$theme = 'dark';
$email_notifications = 1;
$push_notifications = 1;
$community_updates = 0;
$recent_activities = [];
$badges = [];
$avatar_initials = 'JD';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['update_profile'])) {
            // Update profile information
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $bio = $_POST['bio'] ?? '';
            $location = $_POST['location'] ?? '';
            $website = $_POST['website'] ?? '';
            
            $stmt = $pdo->prepare("
                UPDATE users 
                SET username = ?, email = ?, bio = ?, location = ?, website = ?, updated_at = CURRENT_TIMESTAMP 
                WHERE id = ?
            ");
            $stmt->execute([$username, $email, $bio, $location, $website, $user_id]);
            
            $_SESSION['success_message'] = "Profile updated successfully!";
            
        } elseif (isset($_POST['update_password'])) {
            // Update password
            $current_password = $_POST['current_password'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            
            // Verify current password
            $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user_data = $stmt->fetch();
            
            if ($user_data && password_verify($current_password, $user_data['password'])) {
                if ($new_password === $confirm_password) {
                    if (strlen($new_password) >= 8) {
                        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                        $stmt = $pdo->prepare("UPDATE users SET password = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
                        $stmt->execute([$hashed_password, $user_id]);
                        $_SESSION['success_message'] = "Password updated successfully!";
                    } else {
                        $_SESSION['error_message'] = "Password must be at least 8 characters long!";
                    }
                } else {
                    $_SESSION['error_message'] = "New passwords don't match!";
                }
            } else {
                $_SESSION['error_message'] = "Current password is incorrect!";
            }
            
        } elseif (isset($_POST['update_preferences'])) {
            // Update preferences
            $language = $_POST['language'] ?? 'en';
            $timezone = $_POST['timezone'] ?? 'est';
            $theme = $_POST['theme'] ?? 'dark';
            $email_notifications = isset($_POST['email_notifications']) ? 1 : 0;
            $push_notifications = isset($_POST['push_notifications']) ? 1 : 0;
            $community_updates = isset($_POST['community_updates']) ? 1 : 0;
            
            $stmt = $pdo->prepare("
                UPDATE users 
                SET language = ?, timezone = ?, theme = ?, 
                    email_notifications = ?, push_notifications = ?, community_updates = ?, 
                    updated_at = CURRENT_TIMESTAMP 
                WHERE id = ?
            ");
            $stmt->execute([$language, $timezone, $theme, $email_notifications, $push_notifications, $community_updates, $user_id]);
            
            $_SESSION['success_message'] = "Preferences updated successfully!";
        }
        
        // Redirect to avoid form resubmission
        header('Location: profile.php');
        exit();
        
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        $_SESSION['error_message'] = "An error occurred while updating your information.";
    }
}

// Handle account deletion
if (isset($_POST['delete_account'])) {
    $confirm_text = $_POST['confirm_delete'] ?? '';
    
    if ($confirm_text === 'DELETE') {
        try {
            // Start transaction
            $pdo->beginTransaction();
            
            // Delete user data from related tables
            $pdo->prepare("DELETE FROM activities WHERE user_id = ?")->execute([$user_id]);
            $pdo->prepare("DELETE FROM badges WHERE user_id = ?")->execute([$user_id]);
            $pdo->prepare("DELETE FROM scans WHERE user_id = ?")->execute([$user_id]);
            
            // Delete user
            $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$user_id]);
            
            $pdo->commit();
            
            session_destroy();
            header('Location: index.php?account_deleted=1');
            exit();
            
        } catch (PDOException $e) {
            $pdo->rollBack();
            $_SESSION['error_message'] = "Error deleting account: " . $e->getMessage();
        }
    } else {
        $_SESSION['error_message'] = "Please type 'DELETE' to confirm account deletion.";
    }
}

try {
    // Get user data
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    
    if (!$user) {
        header('Location: logout.php');
        exit();
    }
    
    // Get user stats with proper error handling
    $scan_count = $user['scan_count'] ?? 0;
    $accuracy = $user['accuracy'] ?? 0;
    $post_count = $user['post_count'] ?? 0;
    $user_rank = $user['user_rank'] ?? 0;
    $username = $user['username'] ?? 'User';
    $email = $user['email'] ?? '';
    $bio = $user['bio'] ?? 'AI Content Analyst | Fighting misinformation since 2023';
    $location = $user['location'] ?? 'San Francisco, CA';
    $website = $user['website'] ?? 'https://johndoe.com';
    $language = $user['language'] ?? 'en';
    $timezone = $user['timezone'] ?? 'est';
    $theme = $user['theme'] ?? 'dark';
    $email_notifications = $user['email_notifications'] ?? 1;
    $push_notifications = $user['push_notifications'] ?? 1;
    $community_updates = $user['community_updates'] ?? 0;
    
    // Generate avatar initials
    $names = explode(' ', $username);
    $avatar_initials = '';
    if (count($names) >= 2) {
        $avatar_initials = strtoupper(substr($names[0], 0, 1) . substr($names[1], 0, 1));
    } else {
        $avatar_initials = strtoupper(substr($username, 0, 2));
    }
    
    // Get recent activities
    $activities_stmt = $pdo->prepare("
        SELECT * FROM activities 
        WHERE user_id = ? 
        ORDER BY created_at DESC 
        LIMIT 3
    ");
    $activities_stmt->execute([$user_id]);
    $recent_activities = $activities_stmt->fetchAll();
    
    // Get user badges
    $badges_stmt = $pdo->prepare("SELECT * FROM badges WHERE user_id = ?");
    $badges_stmt->execute([$user_id]);
    $badges = $badges_stmt->fetchAll();
    
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    // Values already set to defaults above
}

// Function to get activity icon
function getActivityIcon($activity_type) {
    switch ($activity_type) {
        case 'image_scan': return 'image';
        case 'video_scan': return 'video';
        case 'article_scan': return 'article';
        case 'vote': return 'community';
        case 'badge': return 'badge';
        default: return 'general';
    }
}

// Function to format time ago
function timeAgo($datetime) {
    $time = strtotime($datetime);
    $now = time();
    $diff = $now - $time;
    
    if ($diff < 60) {
        return 'Just now';
    } elseif ($diff < 3600) {
        $mins = floor($diff / 60);
        return $mins . ' minute' . ($mins > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 2592000) {
        $days = floor($diff / 86400);
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    } else {
        return date('M j, Y', $time);
    }
}

// Function to get badge gradient
function getBadgeGradient($badge_color) {
    switch ($badge_color) {
        case 'green': return 'linear-gradient(135deg, #10b981, #059669)';
        case 'orange': return 'linear-gradient(135deg, #f59e0b, #d97706)';
        case 'purple': return 'linear-gradient(135deg, #8b5cf6, #7c3aed)';
        default: return 'linear-gradient(135deg, var(--glow-cyan), var(--glow-blue))';
    }
}

// Function to get badge icon
function getBadgeIcon($badge_name) {
    switch ($badge_name) {
        case 'Fact Checker':
            return '<svg style="width: 1.5rem; height: 1.5rem; color: white;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
            </svg>';
        case 'Accuracy Pro':
            return '<svg style="width: 1.5rem; height: 1.5rem; color: white;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>';
        case 'Rising Star':
            return '<svg style="width: 1.5rem; height: 1.5rem; color: white;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
            </svg>';
        case 'Community':
            return '<svg style="width: 1.5rem; height: 1.5rem; color: white;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>';
        default:
            return '<svg style="width: 1.5rem; height: 1.5rem; color: white;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="8" r="7"></circle>
                <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
            </svg>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - TruthGuard AI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* CSS Variables / Design System */
        :root {
            --background: hsl(220, 28%, 8%);
            --foreground: hsl(210, 40%, 98%);
            --card: hsl(220, 25%, 12%);
            --card-foreground: hsl(210, 40%, 98%);
            --primary: hsl(189, 94%, 55%);
            --primary-foreground: hsl(220, 28%, 8%);
            --secondary: hsl(240, 15%, 20%);
            --secondary-foreground: hsl(210, 40%, 98%);
            --muted: hsl(220, 20%, 18%);
            --muted-foreground: hsl(215, 20%, 65%);
            --border: hsl(220, 20%, 20%);
            --glow-cyan: hsl(189, 94%, 55%);
            --glow-blue: hsl(217, 91%, 60%);
            --radius: 0.75rem;
            --danger: hsl(0, 84%, 60%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: var(--background);
            color: var(--foreground);
            line-height: 1.6;
            overflow-x: hidden;
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        @media (min-width: 1024px) {
            .container {
                padding: 0 2rem;
            }
        }

        /* Glass Effects */
        .glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .glass-strong, .glass-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        /* Gradient Text */
        .gradient-text {
            background: linear-gradient(135deg, var(--glow-cyan), var(--glow-blue));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .glow-text {
            text-shadow: 0 0 30px hsla(189, 94%, 55%, 0.5);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px hsla(189, 94%, 55%, 0.3); }
            50% { box-shadow: 0 0 40px hsla(189, 94%, 55%, 0.6); }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .slide-up {
            animation: slideUp 0.6s ease-out forwards;
        }

        .fade-in {
            animation: fadeIn 0.4s ease-out forwards;
        }

        .pulse {
            animation: pulse-glow 2s ease-in-out infinite;
        }

        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }
        .delay-5 { animation-delay: 0.5s; }

        /* Header */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(30px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 4rem;
        }

        @media (min-width: 1024px) {
            .header-content {
                height: 5rem;
            }
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            transition: transform 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        .logo-icon {
            width: 2rem;
            height: 2rem;
            color: var(--primary);
        }

        .logo-text {
            font-size: 1.25rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--glow-cyan), var(--glow-blue));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        @media (min-width: 1024px) {
            .logo-text {
                font-size: 1.5rem;
            }
        }

        .nav {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .nav-link {
            color: var(--muted-foreground);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            position: relative;
            transition: color 0.3s ease;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -0.25rem;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary);
            transition: width 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary);
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .nav-link.active {
            color: var(--primary);
        }

        .nav-link.active::after {
            width: 100%;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .desktop-nav {
            display: none;
        }

        @media (min-width: 1024px) {
            .desktop-nav {
                display: flex;
            }
        }

        .mobile-menu-btn {
            display: block;
            background: none;
            border: none;
            color: var(--foreground);
            cursor: pointer;
            padding: 0.5rem;
        }

        @media (min-width: 1024px) {
            .mobile-menu-btn {
                display: none;
            }
        }

        .menu-icon {
            width: 1.5rem;
            height: 1.5rem;
        }

        .mobile-menu {
            display: none;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .mobile-menu.active {
            display: block;
        }

        .mobile-nav {
            padding: 1.5rem 1rem;
        }

        .mobile-nav-link {
            display: block;
            color: var(--muted-foreground);
            text-decoration: none;
            padding: 0.75rem 0;
            font-size: 0.875rem;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .mobile-nav-link:hover {
            color: var(--primary);
        }

        .mobile-menu-actions {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            border-radius: var(--radius);
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            white-space: nowrap;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--glow-cyan), var(--glow-blue));
            color: white;
            box-shadow: 0 4px 16px hsla(189, 94%, 55%, 0.5);
        }

        .btn-primary:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px hsla(189, 94%, 55%, 0.6);
        }

        .btn-ghost {
            background: transparent;
            color: var(--foreground);
        }

        .btn-ghost:hover {
            color: var(--primary);
        }

        .btn-outline {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: var(--foreground);
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .btn-danger {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #ef4444;
        }

        .btn-danger:hover {
            background: rgba(239, 68, 68, 0.3);
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
        }

        .btn-lg {
            padding: 0.875rem 1.75rem;
            font-size: 1rem;
        }

        .btn-icon {
            width: 1.25rem;
            height: 1.25rem;
        }

        .btn-primary .btn-icon {
            transition: transform 0.3s ease;
        }

        .btn-primary:hover .btn-icon {
            transform: translateX(4px);
        }

        /* Profile Layout */
        .profile {
            padding-top: 5rem;
            min-height: 100vh;
        }

        @media (min-width: 1024px) {
            .profile {
                padding-top: 6rem;
            }
        }

        .profile-content {
            padding: 2rem 0;
        }

        @media (min-width: 1024px) {
            .profile-content {
                padding: 3rem 0;
            }
        }

        .profile-grid {
            display: grid;
            gap: 1.5rem;
        }

        @media (min-width: 1024px) {
            .profile-grid {
                grid-template-columns: 1fr 350px;
                gap: 2rem;
            }
        }

        .profile-main {
            display: grid;
            gap: 1.5rem;
        }

        .profile-sidebar {
            display: grid;
            gap: 1.5rem;
            align-content: start;
        }

        /* Profile Cards */
        .profile-card {
            padding: 1.5rem;
            border-radius: var(--radius);
            transition: all 0.3s ease;
        }

        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px hsla(189, 94%, 55%, 0.2);
        }

        .profile-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .profile-card-title {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .profile-card-icon {
            width: 2rem;
            height: 2rem;
            color: var(--primary);
        }

        /* Profile Header */
        .profile-header {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .profile-avatar {
            width: 6rem;
            height: 6rem;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--glow-cyan), var(--glow-blue));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
            font-size: 2rem;
            position: relative;
        }

        .profile-avatar-edit {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 2rem;
            height: 2rem;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .profile-avatar-edit svg {
            width: 1rem;
            height: 1rem;
            color: white;
        }

        .profile-info {
            flex: 1;
        }

        .profile-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .profile-bio {
            color: var(--muted-foreground);
            margin-bottom: 1rem;
        }

        .profile-stats {
            display: flex;
            gap: 1.5rem;
        }

        .profile-stat {
            text-align: center;
        }

        .profile-stat-value {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .profile-stat-label {
            font-size: 0.75rem;
            color: var(--muted-foreground);
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--radius);
            padding: 0.75rem 1rem;
            color: var(--foreground);
            font-family: inherit;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px hsla(189, 94%, 55%, 0.2);
        }

        .form-textarea {
            width: 100%;
            min-height: 100px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--radius);
            padding: 0.75rem 1rem;
            color: var(--foreground);
            font-family: inherit;
            font-size: 0.875rem;
            resize: vertical;
            transition: all 0.3s ease;
        }

        .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px hsla(189, 94%, 55%, 0.2);
        }

        .form-select {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--radius);
            padding: 0.75rem 1rem;
            color: var(--foreground);
            font-family: inherit;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1rem;
        }

        .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px hsla(189, 94%, 55%, 0.2);
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        /* Tabs */
        .tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .tab {
            padding: 0.75rem 1.5rem;
            background: none;
            border: none;
            color: var(--muted-foreground);
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            border-bottom: 2px solid transparent;
        }

        .tab:hover {
            color: var(--primary);
        }

        .tab.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
        }

        /* User Stats */
        .user-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .user-stat {
            text-align: center;
            padding: 1rem;
            border-radius: var(--radius);
        }

        .user-stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        @media (min-width: 768px) {
            .user-stat-value {
                font-size: 2rem;
            }
        }

        .user-stat-label {
            font-size: 0.875rem;
            color: var(--muted-foreground);
        }

        /* Recent Activity */
        .activity-list {
            display: grid;
            gap: 1rem;
        }

        .activity-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-radius: var(--radius);
            transition: all 0.3s ease;
        }

        .activity-item:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .activity-icon {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.05);
        }

        .activity-icon svg {
            width: 1.25rem;
            height: 1.25rem;
            color: var(--primary);
        }

        .activity-content {
            flex: 1;
        }

        .activity-text {
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        .activity-time {
            font-size: 0.75rem;
            color: var(--muted-foreground);
        }

        /* Modal */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .modal.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            width: 90%;
            max-width: 500px;
            background: var(--card);
            border-radius: var(--radius);
            padding: 2rem;
            transform: translateY(20px);
            transition: transform 0.3s ease;
        }

        .modal.active .modal-content {
            transform: translateY(0);
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .modal-close {
            background: none;
            border: none;
            color: var(--muted-foreground);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .modal-close:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--foreground);
        }

        .modal-close svg {
            width: 1.25rem;
            height: 1.25rem;
        }

        .modal-body {
            margin-bottom: 1.5rem;
        }

        .modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        /* Toggle Switch */
        .toggle {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
        }

        .toggle input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.1);
            transition: .3s;
            border-radius: 24px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .3s;
            border-radius: 50%;
        }

        input:checked + .toggle-slider {
            background-color: var(--primary);
        }

        input:checked + .toggle-slider:before {
            transform: translateX(20px);
        }

        /* Footer */
        .footer {
            background: rgba(255, 255, 255, 0.03);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 3rem 0;
        }

        @media (min-width: 1024px) {
            .footer {
                padding: 4rem 0;
            }
        }

        .footer-grid {
            display: grid;
            gap: 2rem;
            margin-bottom: 3rem;
        }

        @media (min-width: 768px) {
            .footer-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .footer-grid {
                grid-template-columns: repeat(6, 1fr);
            }
        }

        .footer-brand {
            grid-column: span 2;
        }

        .footer-description {
            font-size: 0.875rem;
            color: var(--muted-foreground);
            margin: 1rem 0 1.5rem;
            max-width: 20rem;
        }

        .social-links {
            display: flex;
            gap: 1rem;
        }

        .social-link {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-link:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .social-link svg {
            width: 1.25rem;
            height: 1.25rem;
            color: var(--muted-foreground);
            transition: color 0.3s ease;
        }

        .social-link:hover svg {
            color: var(--primary);
        }

        .footer-links-title {
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .footer-links ul {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.75rem;
        }

        .footer-links a {
            font-size: 0.875rem;
            color: var(--muted-foreground);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--primary);
        }

        .footer-bottom {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            align-items: center;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        @media (min-width: 768px) {
            .footer-bottom {
                flex-direction: row;
                justify-content: space-between;
            }
        }

        .footer-bottom p {
            font-size: 0.875rem;
            color: var(--muted-foreground);
        }

        .footer-bottom-links {
            display: flex;
            gap: 1.5rem;
        }

        .footer-bottom-links a {
            font-size: 0.875rem;
            color: var(--muted-foreground);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-bottom-links a:hover {
            color: var(--primary);
        }

        /* Message Styles */
        .message {
            padding: 1rem;
            border-radius: var(--radius);
            margin-bottom: 1.5rem;
            animation: fadeIn 0.4s ease-out;
        }

        .message.success {
            background: rgba(16, 185, 129, 0.2);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #10b981;
        }

        .message.error {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #ef4444;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <!-- Profile Section -->
    <section class="profile">
        <div class="container">
            <div class="profile-content">
                <!-- Success/Error Messages -->
                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="message success fade-in-up">
                        <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="message error fade-in-up">
                        <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                    </div>
                <?php endif; ?>

                <div class="profile-grid">
                    <div class="profile-main">
                        <!-- Profile Header -->
                        <div class="profile-card glass-strong fade-in-up">
                            <div class="profile-header">
                                <div class="profile-avatar">
                                    <?php echo $avatar_initials; ?>
                                    <div class="profile-avatar-edit" id="avatarEditBtn">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="profile-info">
                                    <h1 class="profile-name"><?php echo htmlspecialchars($username); ?></h1>
                                    <p class="profile-bio"><?php echo htmlspecialchars($bio); ?></p>
                                    <div class="profile-stats">
                                        <div class="profile-stat">
                                            <div class="profile-stat-value gradient-text"><?php echo $scan_count; ?></div>
                                            <div class="profile-stat-label">Scans</div>
                                        </div>
                                        <div class="profile-stat">
                                            <div class="profile-stat-value gradient-text"><?php echo $accuracy; ?>%</div>
                                            <div class="profile-stat-label">Accuracy</div>
                                        </div>
                                        <div class="profile-stat">
                                            <div class="profile-stat-value gradient-text">#<?php echo $user_rank; ?></div>
                                            <div class="profile-stat-label">Rank</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabs -->
                        <div class="profile-card glass-strong fade-in-up delay-1">
                            <div class="tabs">
                                <button class="tab active" data-tab="profile">Profile</button>
                                <button class="tab" data-tab="security">Security</button>
                                <button class="tab" data-tab="preferences">Preferences</button>
                                <button class="tab" data-tab="danger">Danger Zone</button>
                            </div>

                            <!-- Profile Tab -->
                            <div class="tab-content active" id="profile-tab">
                                <form method="POST" id="profile-form">
                                    <input type="hidden" name="update_profile" value="1">
                                    <div class="form-group">
                                        <label class="form-label" for="username">Username</label>
                                        <input type="text" class="form-input" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label" for="email">Email Address</label>
                                        <input type="email" class="form-input" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label" for="bio">Bio</label>
                                        <textarea class="form-textarea" id="bio" name="bio"><?php echo htmlspecialchars($bio); ?></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label" for="location">Location</label>
                                        <input type="text" class="form-input" id="location" name="location" value="<?php echo htmlspecialchars($location); ?>">
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label" for="website">Website</label>
                                        <input type="url" class="form-input" id="website" name="website" value="<?php echo htmlspecialchars($website); ?>">
                                    </div>

                                    <div class="form-actions">
                                        <button type="button" class="btn btn-outline" id="resetProfile">Reset</button>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>

                            <!-- Security Tab -->
                            <div class="tab-content" id="security-tab" style="display: none;">
                                <form method="POST" id="security-form">
                                    <input type="hidden" name="update_password" value="1">
                                    <div class="form-group">
                                        <label class="form-label" for="current_password">Current Password</label>
                                        <input type="password" class="form-input" id="current_password" name="current_password" required>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label" for="new_password">New Password</label>
                                        <input type="password" class="form-input" id="new_password" name="new_password" required minlength="8">
                                        <small style="color: var(--muted-foreground); font-size: 0.75rem;">Password must be at least 8 characters long</small>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label" for="confirm_password">Confirm New Password</label>
                                        <input type="password" class="form-input" id="confirm_password" name="confirm_password" required>
                                    </div>

                                    <div class="form-actions">
                                        <button type="button" class="btn btn-outline" id="resetSecurity">Reset</button>
                                        <button type="submit" class="btn btn-primary">Update Password</button>
                                    </div>
                                </form>

                                <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid rgba(255, 255, 255, 0.1);">
                                    <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 1rem;">Two-Factor Authentication</h3>
                                    <p style="font-size: 0.875rem; color: var(--muted-foreground); margin-bottom: 1.5rem;">
                                        Add an extra layer of security to your account by enabling two-factor authentication.
                                    </p>
                                    <button class="btn btn-outline">
                                        <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                        </svg>
                                        Enable 2FA
                                    </button>
                                </div>
                            </div>

                            <!-- Preferences Tab -->
                            <div class="tab-content" id="preferences-tab" style="display: none;">
                                <form method="POST" id="preferences-form">
                                    <input type="hidden" name="update_preferences" value="1">
                                    <div class="form-group">
                                        <label class="form-label" for="language">Language</label>
                                        <select class="form-select" id="language" name="language">
                                            <option value="en" <?php echo $language === 'en' ? 'selected' : ''; ?>>English</option>
                                            <option value="es" <?php echo $language === 'es' ? 'selected' : ''; ?>>Spanish</option>
                                            <option value="fr" <?php echo $language === 'fr' ? 'selected' : ''; ?>>French</option>
                                            <option value="de" <?php echo $language === 'de' ? 'selected' : ''; ?>>German</option>
                                            <option value="ja" <?php echo $language === 'ja' ? 'selected' : ''; ?>>Japanese</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label" for="timezone">Timezone</label>
                                        <select class="form-select" id="timezone" name="timezone">
                                            <option value="pst" <?php echo $timezone === 'pst' ? 'selected' : ''; ?>>Pacific Time (PT)</option>
                                            <option value="mst" <?php echo $timezone === 'mst' ? 'selected' : ''; ?>>Mountain Time (MT)</option>
                                            <option value="cst" <?php echo $timezone === 'cst' ? 'selected' : ''; ?>>Central Time (CT)</option>
                                            <option value="est" <?php echo $timezone === 'est' ? 'selected' : ''; ?>>Eastern Time (ET)</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label" for="theme">Theme</label>
                                        <select class="form-select" id="theme" name="theme">
                                            <option value="dark" <?php echo $theme === 'dark' ? 'selected' : ''; ?>>Dark</option>
                                            <option value="light" <?php echo $theme === 'light' ? 'selected' : ''; ?>>Light</option>
                                            <option value="system" <?php echo $theme === 'system' ? 'selected' : ''; ?>>System</option>
                                        </select>
                                    </div>

                                    <div style="margin-bottom: 1.5rem;">
                                        <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 1rem;">Notifications</h3>
                                        
                                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                                            <div>
                                                <div style="font-size: 0.875rem; font-weight: 500;">Email Notifications</div>
                                                <div style="font-size: 0.75rem; color: var(--muted-foreground);">Receive email updates about your activity</div>
                                            </div>
                                            <label class="toggle">
                                                <input type="checkbox" name="email_notifications" <?php echo $email_notifications ? 'checked' : ''; ?>>
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>

                                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                                            <div>
                                                <div style="font-size: 0.875rem; font-weight: 500;">Push Notifications</div>
                                                <div style="font-size: 0.75rem; color: var(--muted-foreground);">Receive push notifications in your browser</div>
                                            </div>
                                            <label class="toggle">
                                                <input type="checkbox" name="push_notifications" <?php echo $push_notifications ? 'checked' : ''; ?>>
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>

                                        <div style="display: flex; align-items: center; justify-content: space-between;">
                                            <div>
                                                <div style="font-size: 0.875rem; font-weight: 500;">Community Updates</div>
                                                <div style="font-size: 0.75rem; color: var(--muted-foreground);">Get notified about community activities</div>
                                            </div>
                                            <label class="toggle">
                                                <input type="checkbox" name="community_updates" <?php echo $community_updates ? 'checked' : ''; ?>>
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-actions">
                                        <button type="button" class="btn btn-outline" id="resetPreferences">Reset</button>
                                        <button type="submit" class="btn btn-primary">Save Preferences</button>
                                    </div>
                                </form>
                            </div>

                            <!-- Danger Zone Tab -->
                            <div class="tab-content" id="danger-tab" style="display: none;">
                                <div style="margin-bottom: 2rem;">
                                    <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem; color: #ef4444;">Export Data</h3>
                                    <p style="font-size: 0.875rem; color: var(--muted-foreground); margin-bottom: 1rem;">
                                        Download all of your data in a JSON format.
                                    </p>
                                    <button class="btn btn-outline" id="exportData">
                                        <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                            <polyline points="7 10 12 15 17 10"></polyline>
                                            <line x1="12" y1="15" x2="12" y2="3"></line>
                                        </svg>
                                        Export Data
                                    </button>
                                </div>

                                <div style="margin-bottom: 2rem; padding-bottom: 2rem; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
                                    <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem; color: #ef4444;">Deactivate Account</h3>
                                    <p style="font-size: 0.875rem; color: var(--muted-foreground); margin-bottom: 1rem;">
                                        Temporarily deactivate your account. You can reactivate it anytime by logging back in.
                                    </p>
                                    <button class="btn btn-outline" id="deactivateAccount">
                                        <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                            <line x1="12" y1="9" x2="12" y2="13"></line>
                                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                        </svg>
                                        Deactivate Account
                                    </button>
                                </div>

                                <div>
                                    <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem; color: #ef4444;">Delete Account</h3>
                                    <p style="font-size: 0.875rem; color: var(--muted-foreground); margin-bottom: 1rem;">
                                        Permanently delete your account and all of your data. This action cannot be undone.
                                    </p>
                                    <button class="btn btn-danger" id="deleteAccount">
                                        <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        </svg>
                                        Delete Account
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="profile-sidebar">
                        <!-- User Stats -->
                        <div class="profile-card glass-strong fade-in-up delay-2">
                            <div class="profile-card-header">
                                <h2 class="profile-card-title">Your Stats</h2>
                                <svg class="profile-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="20" x2="18" y2="10"></line>
                                    <line x1="12" y1="20" x2="12" y2="4"></line>
                                    <line x1="6" y1="20" x2="6" y2="14"></line>
                                </svg>
                            </div>
                            <div class="user-stats">
                                <div class="user-stat glass">
                                    <div class="user-stat-value gradient-text"><?php echo $scan_count; ?></div>
                                    <div class="user-stat-label">Scans</div>
                                </div>
                                <div class="user-stat glass">
                                    <div class="user-stat-value gradient-text"><?php echo $accuracy; ?>%</div>
                                    <div class="user-stat-label">Accuracy</div>
                                </div>
                                <div class="user-stat glass">
                                    <div class="user-stat-value gradient-text"><?php echo $post_count; ?></div>
                                    <div class="user-stat-label">Posts</div>
                                </div>
                                <div class="user-stat glass">
                                    <div class="user-stat-value gradient-text">#<?php echo $user_rank; ?></div>
                                    <div class="user-stat-label">Rank</div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activity -->
                        <div class="profile-card glass-strong fade-in-up delay-3">
                            <div class="profile-card-header">
                                <h2 class="profile-card-title">Recent Activity</h2>
                                <svg class="profile-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                    <polyline points="17 6 23 6 23 12"></polyline>
                                </svg>
                            </div>
                            <div class="activity-list">
                                <?php if (empty($recent_activities)): ?>
                                    <div class="activity-item glass">
                                        <div class="activity-icon">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="activity-content">
                                            <div class="activity-text">No recent activity</div>
                                            <div class="activity-time">Get started by scanning some content!</div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($recent_activities as $activity): ?>
                                        <div class="activity-item glass">
                                            <div class="activity-icon">
                                                <?php
                                                $icon = getActivityIcon($activity['activity_type']);
                                                switch ($icon) {
                                                    case 'image':
                                                        echo '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                                            <polyline points="21 15 16 10 5 21"></polyline>
                                                        </svg>';
                                                        break;
                                                    case 'article':
                                                        echo '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                            <polyline points="14 2 14 8 20 8"></polyline>
                                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                                            <polyline points="10 9 9 9 8 9"></polyline>
                                                        </svg>';
                                                        break;
                                                    case 'community':
                                                        echo '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                                            <circle cx="9" cy="7" r="4"></circle>
                                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                                        </svg>';
                                                        break;
                                                    case 'badge':
                                                        echo '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                                                        </svg>';
                                                        break;
                                                    default:
                                                        echo '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <circle cx="12" cy="12" r="10"></circle>
                                                            <polyline points="12 6 12 12 16 14"></polyline>
                                                        </svg>';
                                                }
                                                ?>
                                            </div>
                                            <div class="activity-content">
                                                <div class="activity-text"><?php echo htmlspecialchars($activity['description']); ?></div>
                                                <div class="activity-time"><?php echo timeAgo($activity['created_at']); ?></div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Badges -->
                        <div class="profile-card glass-strong fade-in-up delay-4">
                            <div class="profile-card-header">
                                <h2 class="profile-card-title">Your Badges</h2>
                                <svg class="profile-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="8" r="7"></circle>
                                    <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                                </svg>
                            </div>
                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                                <?php if (empty($badges)): ?>
                                    <div style="grid-column: span 2; text-align: center; padding: 1rem; color: var(--muted-foreground);">
                                        No badges earned yet. Keep using TruthGuard to earn badges!
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($badges as $badge): ?>
                                        <div style="text-align: center;">
                                            <div style="width: 3rem; height: 3rem; margin: 0 auto 0.5rem; background: <?php echo getBadgeGradient($badge['badge_color']); ?>; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                <?php echo getBadgeIcon($badge['badge_name']); ?>
                                            </div>
                                            <div style="font-size: 0.75rem; font-weight: 500;"><?php echo htmlspecialchars($badge['badge_name']); ?></div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Delete Account Modal -->
    <div class="modal" id="deleteAccountModal">
        <div class="modal-content glass-strong">
            <form method="POST" id="deleteAccountForm">
                <input type="hidden" name="delete_account" value="1">
                <div class="modal-header">
                    <h3 class="modal-title">Delete Account</h3>
                    <button type="button" class="modal-close" id="closeDeleteModal">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <p style="margin-bottom: 1rem;">Are you sure you want to delete your account? This action cannot be undone.</p>
                    <p style="font-size: 0.875rem; color: var(--muted-foreground); margin-bottom: 1.5rem;">
                        All your data, including scans, votes, and profile information will be permanently deleted.
                    </p>
                    <div class="form-group">
                        <label class="form-label" for="confirmDelete">Type "DELETE" to confirm</label>
                        <input type="text" class="form-input" id="confirmDelete" name="confirm_delete" placeholder="DELETE" required>
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-outline" id="cancelDelete">Cancel</button>
                    <button type="submit" class="btn btn-danger" id="confirmDeleteBtn" disabled>Delete Account</button>
                </div>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');

        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('active');
            });

            // Close mobile menu when clicking on a link
            const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');
            mobileNavLinks.forEach(link => {
                link.addEventListener('click', () => {
                    mobileMenu.classList.remove('active');
                });
            });
        }

        // Intersection Observer for fade-in animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            // Observe all fade-in elements
            document.querySelectorAll('.fade-in-up').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';
                el.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
                observer.observe(el);
            });
        });

        // Tab functionality
        const tabs = document.querySelectorAll('.tab');
        const tabContents = document.querySelectorAll('.tab-content');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const target = tab.getAttribute('data-tab');
                
                // Update active tab
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                
                // Show target content, hide others
                tabContents.forEach(content => {
                    if (content.id === `${target}-tab`) {
                        content.style.display = 'block';
                    } else {
                        content.style.display = 'none';
                    }
                });
            });
        });

        // Form handling - Add loading states
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span>Loading...</span>';
                }
            });
        });

        // Reset buttons
        document.getElementById('resetProfile')?.addEventListener('click', function() {
            if (confirm('Are you sure you want to reset all changes?')) {
                document.getElementById('profile-form').reset();
            }
        });

        document.getElementById('resetSecurity')?.addEventListener('click', function() {
            if (confirm('Are you sure you want to reset all changes?')) {
                document.getElementById('security-form').reset();
            }
        });

        document.getElementById('resetPreferences')?.addEventListener('click', function() {
            if (confirm('Are you sure you want to reset all changes?')) {
                document.getElementById('preferences-form').reset();
            }
        });

        // Export data
        document.getElementById('exportData')?.addEventListener('click', function() {
            // In a real app, this would trigger a data export
            alert('Your data export has been started. You will receive an email when it is ready.');
        });

        // Deactivate account
        document.getElementById('deactivateAccount')?.addEventListener('click', function() {
            if (confirm('Are you sure you want to deactivate your account? You can reactivate it by logging in again.')) {
                // In a real app, this would deactivate the account
                alert('Your account has been deactivated.');
            }
        });

        // Delete account modal
        const deleteAccountModal = document.getElementById('deleteAccountModal');
        const deleteAccountBtn = document.getElementById('deleteAccount');
        const closeDeleteModal = document.getElementById('closeDeleteModal');
        const cancelDelete = document.getElementById('cancelDelete');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        const confirmDeleteInput = document.getElementById('confirmDelete');

        deleteAccountBtn?.addEventListener('click', () => {
            deleteAccountModal.classList.add('active');
        });

        closeDeleteModal?.addEventListener('click', () => {
            deleteAccountModal.classList.remove('active');
        });

        cancelDelete?.addEventListener('click', () => {
            deleteAccountModal.classList.remove('active');
        });

        // Enable delete button only when "DELETE" is typed
        confirmDeleteInput?.addEventListener('input', () => {
            confirmDeleteBtn.disabled = confirmDeleteInput.value !== 'DELETE';
        });

        // Avatar edit
        document.getElementById('avatarEditBtn')?.addEventListener('click', () => {
            // In a real app, this would open an avatar upload dialog
            alert('Avatar edit functionality would open here. You could upload a new profile picture.');
        });

        // Password validation
        const newPasswordInput = document.getElementById('new_password');
        const confirmPasswordInput = document.getElementById('confirm_password');
        
        function validatePasswords() {
            if (newPasswordInput && confirmPasswordInput) {
                if (newPasswordInput.value !== confirmPasswordInput.value) {
                    confirmPasswordInput.style.borderColor = '#ef4444';
                } else {
                    confirmPasswordInput.style.borderColor = '';
                }
            }
        }
        
        newPasswordInput?.addEventListener('input', validatePasswords);
        confirmPasswordInput?.addEventListener('input', validatePasswords);

        // Auto-hide messages after 5 seconds
        setTimeout(() => {
            const messages = document.querySelectorAll('.message');
            messages.forEach(message => {
                message.style.opacity = '0';
                message.style.transition = 'opacity 0.5s ease';
                setTimeout(() => message.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>