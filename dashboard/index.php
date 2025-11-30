   <?php
session_start();
$currentPage = 'dashboard';  // Important: set this on every page
$pageTitle = 'Dashboard';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - TruthGuard AI</title>
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
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px hsla(189, 94%, 55%, 0.3); }
            50% { box-shadow: 0 0 40px hsla(189, 94%, 55%, 0.6); }
        }

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

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .slide-up {
            animation: slideUp 0.6s ease-out forwards;
        }

        .float-animation {
            animation: float 6s ease-in-out infinite;
        }

        .pulse {
            animation: pulse-glow 2s ease-in-out infinite;
        }

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

        /* Dashboard Layout */
        .dashboard {
            padding-top: 5rem;
            min-height: 100vh;
        }

        @media (min-width: 1024px) {
            .dashboard {
                padding-top: 6rem;
            }
        }

        .dashboard-content {
            padding: 2rem 0;
        }

        @media (min-width: 1024px) {
            .dashboard-content {
                padding: 3rem 0;
            }
        }

        .dashboard-grid {
            display: grid;
            gap: 1.5rem;
        }

        @media (min-width: 1024px) {
            .dashboard-grid {
                grid-template-columns: 1fr 400px;
                gap: 2rem;
            }
        }

        .dashboard-main {
            display: grid;
            gap: 1.5rem;
        }

        .dashboard-sidebar {
            display: grid;
            gap: 1.5rem;
            align-content: start;
        }

        /* Dashboard Cards */
        .dashboard-card {
            padding: 1.5rem;
            border-radius: var(--radius);
            transition: all 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px hsla(189, 94%, 55%, 0.2);
        }

        .dashboard-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .dashboard-card-title {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .dashboard-card-icon {
            width: 2rem;
            height: 2rem;
            color: var(--primary);
        }

        /* User Stats */
        .user-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        @media (min-width: 768px) {
            .user-stats {
                grid-template-columns: repeat(4, 1fr);
            }
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

        /* Trending Content */
        .trending-list {
            display: grid;
            gap: 1rem;
        }

        .trending-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-radius: var(--radius);
            transition: all 0.3s ease;
        }

        .trending-item:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .trending-rank {
            width: 2rem;
            height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--glow-cyan), var(--glow-blue));
            border-radius: 50%;
            font-weight: 700;
            font-size: 0.875rem;
        }

        .trending-content {
            flex: 1;
        }

        .trending-title {
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .trending-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 0.75rem;
            color: var(--muted-foreground);
        }

        .trending-votes {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .trending-votes svg {
            width: 0.875rem;
            height: 0.875rem;
            color: var(--primary);
        }

        .verdict-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .verdict-real {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
        }

        .verdict-fake {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        .verdict-misleading {
            background: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
        }

        /* Leaderboard */
        .leaderboard-list {
            display: grid;
            gap: 1rem;
        }

        .leaderboard-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-radius: var(--radius);
            transition: all 0.3s ease;
        }

        .leaderboard-item:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .leaderboard-rank {
            width: 2rem;
            height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--glow-cyan), var(--glow-blue));
            border-radius: 50%;
            font-weight: 700;
            font-size: 0.875rem;
        }

        .leaderboard-avatar {
            width: 3rem;
            height: 3rem;
            background: linear-gradient(135deg, var(--glow-cyan), var(--glow-blue));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
        }

        .leaderboard-info {
            flex: 1;
        }

        .leaderboard-name {
            font-weight: 600;
            font-size: 0.875rem;
        }

        .leaderboard-stats {
            display: flex;
            gap: 1rem;
            font-size: 0.75rem;
            color: var(--muted-foreground);
        }

        .leaderboard-score {
            font-weight: 700;
            color: var(--primary);
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

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        @media (min-width: 768px) {
            .quick-actions {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        .quick-action {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 1.5rem 1rem;
            border-radius: var(--radius);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .quick-action:hover {
            background: rgba(255, 255, 255, 0.05);
            transform: translateY(-5px);
        }

        .quick-action-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--glow-cyan), var(--glow-blue));
        }

        .quick-action-icon svg {
            width: 1.5rem;
            height: 1.5rem;
            color: white;
        }

        .quick-action-text {
            font-size: 0.875rem;
            font-weight: 500;
            text-align: center;
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
    </style>
</head>
<body>
   <?php
session_start();
$currentPage = 'dashboard';  // Important: set this on every page
$pageTitle = 'Dashboard';
include 'header.php';
?>
    <!-- Dashboard -->
    <section class="dashboard">
        <div class="container">
            <div class="dashboard-content">
                <div class="dashboard-grid">
                    <div class="dashboard-main">
                        <!-- Welcome Card -->
                        <div class="dashboard-card glass-strong fade-in-up">
                            <div class="dashboard-card-header">
                                <h2 class="dashboard-card-title">Welcome back, John!</h2>
                                <svg class="dashboard-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </div>
                            <p>You have <span class="gradient-text">8 scans remaining</span> today. Upgrade to Pro for unlimited scans.</p>
                        </div>

                        <!-- Quick Actions -->
                        <div class="dashboard-card glass-strong fade-in-up delay-2">
                            <div class="dashboard-card-header">
                                <h2 class="dashboard-card-title">Quick Actions</h2>
                                <svg class="dashboard-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                                </svg>
                            </div>
                            <div class="quick-actions">
                                <a href="scan.php" class="quick-action glass">
                                    <div class="quick-action-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                            <polyline points="21 15 16 10 5 21"></polyline>
                                        </svg>
                                    </div>
                                    <span class="quick-action-text">Scan Image</span>
                                </a>
                                <a href="scan.php" class="quick-action glass">
                                    <div class="quick-action-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polygon points="23 7 16 12 23 17 23 7"></polygon>
                                            <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                                        </svg>
                                    </div>
                                    <span class="quick-action-text">Scan Video</span>
                                </a>
                                <a href="scan.php" class="quick-action glass">
                                    <div class="quick-action-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                            <polyline points="10 9 9 9 8 9"></polyline>
                                        </svg>
                                    </div>
                                    <span class="quick-action-text">Scan Article</span>
                                </a>
                                <a href="community.php" class="quick-action glass">
                                    <div class="quick-action-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="9" cy="7" r="4"></circle>
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                        </svg>
                                    </div>
                                    <span class="quick-action-text">Community</span>
                                </a>
                            </div>
                        </div>

                        <!-- Trending Content -->
                        <div class="dashboard-card glass-strong fade-in-up delay-3">
                            <div class="dashboard-card-header">
                                <h2 class="dashboard-card-title">Trending Content</h2>
                                <svg class="dashboard-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="20" x2="18" y2="10"></line>
                                    <line x1="12" y1="20" x2="12" y2="4"></line>
                                    <line x1="6" y1="20" x2="6" y2="14"></line>
                                </svg>
                            </div>
                            <div class="trending-list">
                                <div class="trending-item glass">
                                    <div class="trending-rank">1</div>
                                    <div class="trending-content">
                                        <div class="trending-title">Breaking: Presidential Deepfake Video Goes Viral</div>
                                        <div class="trending-meta">
                                            <div class="trending-votes">
                                                <svg viewBox="0 0 24 24" fill="currentColor">
                                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                                </svg>
                                                4.8k
                                            </div>
                                            <span class="verdict-badge verdict-fake">FAKE</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="trending-item glass">
                                    <div class="trending-rank">2</div>
                                    <div class="trending-content">
                                        <div class="trending-title">Celebrity Photo Manipulation Exposed</div>
                                        <div class="trending-meta">
                                            <div class="trending-votes">
                                                <svg viewBox="0 0 24 24" fill="currentColor">
                                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                                </svg>
                                                3.2k
                                            </div>
                                            <span class="verdict-badge verdict-misleading">MISLEADING</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="trending-item glass">
                                    <div class="trending-rank">3</div>
                                    <div class="trending-content">
                                        <div class="trending-title">Viral Health Claim Debunked by Experts</div>
                                        <div class="trending-meta">
                                            <div class="trending-votes">
                                                <svg viewBox="0 0 24 24" fill="currentColor">
                                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                                </svg>
                                                2.7k
                                            </div>
                                            <span class="verdict-badge verdict-fake">FAKE</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="trending-item glass">
                                    <div class="trending-rank">4</div>
                                    <div class="trending-content">
                                        <div class="trending-title">Verified: This Historical Photo is Authentic</div>
                                        <div class="trending-meta">
                                            <div class="trending-votes">
                                                <svg viewBox="0 0 24 24" fill="currentColor">
                                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                                </svg>
                                                2.1k
                                            </div>
                                            <span class="verdict-badge verdict-real">REAL</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="trending-item glass">
                                    <div class="trending-rank">5</div>
                                    <div class="trending-content">
                                        <div class="trending-title">AI-Generated News Article Spreads False Information</div>
                                        <div class="trending-meta">
                                            <div class="trending-votes">
                                                <svg viewBox="0 0 24 24" fill="currentColor">
                                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                                </svg>
                                                1.8k
                                            </div>
                                            <span class="verdict-badge verdict-fake">FAKE</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activity -->
                        <div class="dashboard-card glass-strong fade-in-up delay-4">
                            <div class="dashboard-card-header">
                                <h2 class="dashboard-card-title">Your Recent Activity</h2>
                                <svg class="dashboard-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                    <polyline points="17 6 23 6 23 12"></polyline>
                                </svg>
                            </div>
                            <div class="activity-list">
                                <div class="activity-item glass">
                                    <div class="activity-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                            <polyline points="21 15 16 10 5 21"></polyline>
                                        </svg>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-text">You scanned an image - <strong>98.7% REAL</strong></div>
                                        <div class="activity-time">2 hours ago</div>
                                    </div>
                                </div>
                                <div class="activity-item glass">
                                    <div class="activity-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                            <polyline points="10 9 9 9 8 9"></polyline>
                                        </svg>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-text">You scanned an article - <strong>87.2% FAKE</strong></div>
                                        <div class="activity-time">5 hours ago</div>
                                    </div>
                                </div>
                                <div class="activity-item glass">
                                    <div class="activity-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="9" cy="7" r="4"></circle>
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                        </svg>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-text">You voted on a community post</div>
                                        <div class="activity-time">Yesterday</div>
                                    </div>
                                </div>
                                <div class="activity-item glass">
                                    <div class="activity-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                                        </svg>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-text">You earned the <strong>Fact Finder</strong> badge</div>
                                        <div class="activity-time">2 days ago</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="dashboard-sidebar">
                        <!-- User Stats -->
                        <div class="dashboard-card glass-strong fade-in-up delay-2">
                            <div class="dashboard-card-header">
                                <h2 class="dashboard-card-title">Your Stats</h2>
                                <svg class="dashboard-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="20" x2="18" y2="10"></line>
                                    <line x1="12" y1="20" x2="12" y2="4"></line>
                                    <line x1="6" y1="20" x2="6" y2="14"></line>
                                </svg>
                            </div>
                            <div class="user-stats">
                                <div class="user-stat glass">
                                    <div class="user-stat-value gradient-text">142</div>
                                    <div class="user-stat-label">Scans</div>
                                </div>
                                <div class="user-stat glass">
                                    <div class="user-stat-value gradient-text">87%</div>
                                    <div class="user-stat-label">Accuracy</div>
                                </div>
                                <div class="user-stat glass">
                                    <div class="user-stat-value gradient-text">42</div>
                                    <div class="user-stat-label">Posts</div>
                                </div>
                                <div class="user-stat glass">
                                    <div class="user-stat-value gradient-text">#28</div>
                                    <div class="user-stat-label">Rank</div>
                                </div>
                            </div>
                        </div>

                        <!-- Leaderboard -->
                        <div class="dashboard-card glass-strong fade-in-up delay-3">
                            <div class="dashboard-card-header">
                                <h2 class="dashboard-card-title">Top Contributors</h2>
                                <svg class="dashboard-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="8.5" cy="7" r="4"></circle>
                                    <path d="M20 8v6M23 11h-6"></path>
                                </svg>
                            </div>
                            <div class="leaderboard-list">
                                <div class="leaderboard-item glass">
                                    <div class="leaderboard-rank">1</div>
                                    <div class="leaderboard-avatar">SA</div>
                                    <div class="leaderboard-info">
                                        <div class="leaderboard-name">Sarah Anderson</div>
                                        <div class="leaderboard-stats">
                                            <span class="leaderboard-score">4,892 pts</span>
                                            <span>98.2% accuracy</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="leaderboard-item glass">
                                    <div class="leaderboard-rank">2</div>
                                    <div class="leaderboard-avatar">MJ</div>
                                    <div class="leaderboard-info">
                                        <div class="leaderboard-name">Mike Johnson</div>
                                        <div class="leaderboard-stats">
                                            <span class="leaderboard-score">4,521 pts</span>
                                            <span>97.8% accuracy</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="leaderboard-item glass">
                                    <div class="leaderboard-rank">3</div>
                                    <div class="leaderboard-avatar">ER</div>
                                    <div class="leaderboard-info">
                                        <div class="leaderboard-name">Emily Rodriguez</div>
                                        <div class="leaderboard-stats">
                                            <span class="leaderboard-score">4,210 pts</span>
                                            <span>96.5% accuracy</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="leaderboard-item glass">
                                    <div class="leaderboard-rank">4</div>
                                    <div class="leaderboard-avatar">DW</div>
                                    <div class="leaderboard-info">
                                        <div class="leaderboard-name">David Wilson</div>
                                        <div class="leaderboard-stats">
                                            <span class="leaderboard-score">3,987 pts</span>
                                            <span>95.7% accuracy</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="leaderboard-item glass">
                                    <div class="leaderboard-rank">5</div>
                                    <div class="leaderboard-avatar">PS</div>
                                    <div class="leaderboard-info">
                                        <div class="leaderboard-name">Priya Sharma</div>
                                        <div class="leaderboard-stats">
                                            <span class="leaderboard-score">3,745 pts</span>
                                            <span>94.9% accuracy</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Upgrade CTA -->
                        <div class="dashboard-card glass-strong fade-in-up delay-4">
                            <div class="dashboard-card-header">
                                <h2 class="dashboard-card-title">Upgrade to Pro</h2>
                                <svg class="dashboard-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                                </svg>
                            </div>
                            <p>Unlock unlimited scans, advanced analytics, and priority processing.</p>
                            <a href="pricing.php" class="btn btn-primary w-full" style="margin-top: 1rem;">
                                Upgrade Now
                                <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M5 12h14M12 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
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
    </script>
</body>
</html>