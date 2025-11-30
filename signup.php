<?php
session_start();
require_once 'database.php';

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['fullName']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);
    $terms = isset($_POST['terms']) ? true : false;
    $newsletter = isset($_POST['newsletter']) ? true : false;
    $plan_id = $_POST['plan_id'] ?? 1; // Default to Beginners plan
    
    // Validation
    if (empty($fullName) || empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error = 'Please fill in all required fields';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters long';
    } elseif (!$terms) {
        $error = 'Please agree to the Terms of Service and Privacy Policy';
    } else {
        try {
            // Check if email or username already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
            $stmt->execute([$email, $username]);
            
            if ($stmt->fetch()) {
                $error = 'Email or username already exists';
            } else {
                // Hash password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert new user with plan_id
                $stmt = $pdo->prepare("INSERT INTO users (full_name, username, email, password, plan_id, newsletter_subscription, subscription_status) VALUES (?, ?, ?, ?, ?, ?, 'active')");
                $stmt->execute([$fullName, $username, $email, $hashedPassword, $plan_id, $newsletter]);
                
                $success = 'Account created successfully! Redirecting to login...';
                
                // Redirect to login after 3 seconds
                header("Refresh: 3; url=login.php");
            }
        } catch(PDOException $e) {
            $error = 'Registration failed: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - TruthGuard AI</title>
    <meta name="description" content="Create your TruthGuard AI account to protect yourself from AI-generated misinformation.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
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

/* Enhanced Animations */
@keyframes float {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    33% { transform: translateY(-20px) rotate(2deg); }
    66% { transform: translateY(-10px) rotate(-2deg); }
}

@keyframes pulse-glow {
    0%, 100% { 
        box-shadow: 0 0 20px hsla(189, 94%, 55%, 0.3);
        transform: scale(1);
    }
    50% { 
        box-shadow: 0 0 40px hsla(189, 94%, 55%, 0.6);
        transform: scale(1.02);
    }
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

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

@keyframes bounce-subtle {
    0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
    40% {transform: translateY(-5px);}
    60% {transform: translateY(-3px);}
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

.bounce-subtle {
    animation: bounce-subtle 2s infinite;
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
    transition: transform 0.3s ease;
}

.mobile-menu-btn:hover {
    transform: scale(1.1);
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
    animation: slideUp 0.3s ease-out;
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
    transition: all 0.3s ease;
    border-left: 2px solid transparent;
    padding-left: 1rem;
}

.mobile-nav-link:hover {
    color: var(--primary);
    border-left-color: var(--primary);
    padding-left: 1.5rem;
}

.mobile-menu-actions {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

/* Enhanced Buttons */
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
    position: relative;
    overflow: hidden;
}

.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.btn:hover::before {
    left: 100%;
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
    background: rgba(255, 255, 255, 0.05);
}

.btn-outline {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: var(--foreground);
}

.btn-outline:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: var(--primary);
}

.btn-lg {
    padding: 0.875rem 1.75rem;
    font-size: 1rem;
}

.btn-icon {
    width: 1.25rem;
    height: 1.25rem;
    transition: transform 0.3s ease;
}

.btn-primary .btn-icon {
    transition: transform 0.3s ease;
}

.btn-primary:hover .btn-icon {
    transform: translateX(4px);
}

/* Signup Section */
.signup-section {
    position: relative;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding-top: 5rem;
    overflow: hidden;
}

.signup-bg {
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom right, var(--background), var(--card));
}

.signup-bg::before,
.signup-bg::after {
    content: '';
    position: absolute;
    width: 24rem;
    height: 24rem;
    border-radius: 50%;
    filter: blur(120px);
    opacity: 0.3;
    animation: pulse-glow 3s ease-in-out infinite;
}

.signup-bg::before {
    top: 25%;
    left: 25%;
    background: var(--primary);
}

.signup-bg::after {
    bottom: 25%;
    right: 25%;
    background: var(--glow-blue);
    animation-delay: 1s;
}

.signup-container {
    display: grid;
    grid-template-columns: 1fr;
    gap: 3rem;
    align-items: center;
    position: relative;
    z-index: 10;
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

@media (min-width: 1024px) {
    .signup-container {
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        padding: 2rem 2rem;
    }
}

.signup-content {
    max-width: 28rem;
    margin: 0 auto;
    width: 100%;
}

.signup-card {
    padding: 2rem;
    border-radius: var(--radius);
    box-shadow: 0 20px 60px hsla(189, 94%, 55%, 0.2);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.signup-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 25px 80px hsla(189, 94%, 55%, 0.3);
}

@media (min-width: 640px) {
    .signup-card {
        padding: 3rem;
    }
}

.signup-header {
    text-align: center;
    margin-bottom: 2rem;
}

.signup-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 4rem;
    height: 4rem;
    background: linear-gradient(135deg, var(--glow-cyan), var(--glow-blue));
    border-radius: 50%;
    margin-bottom: 1rem;
    transition: transform 0.3s ease;
}

.signup-icon:hover {
    transform: scale(1.1) rotate(5deg);
}

.signup-icon svg {
    width: 2rem;
    height: 2rem;
    color: white;
}

.signup-title {
    font-size: 1.875rem;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 0.5rem;
}

.signup-subtitle {
    color: var(--muted-foreground);
    font-size: 1rem;
}

.signup-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
}

@media (min-width: 640px) {
    .form-row {
        grid-template-columns: 1fr 1fr;
    }
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-label {
    font-size: 0.875rem;
    font-weight: 500;
}

.form-input {
    padding: 0.75rem 1rem;
    border-radius: var(--radius);
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: var(--foreground);
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.form-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px hsla(189, 94%, 55%, 0.2);
    transform: translateY(-2px);
}

.form-input:hover {
    border-color: rgba(255, 255, 255, 0.2);
}

.password-strength {
    margin-top: 0.5rem;
}

.strength-meter {
    height: 4px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 2px;
    overflow: hidden;
    margin-bottom: 0.25rem;
}

.strength-bar {
    height: 100%;
    width: 0%;
    border-radius: 2px;
    transition: all 0.3s ease;
}

.strength-text {
    font-size: 0.75rem;
    color: var(--muted-foreground);
}

.strength-weak {
    background: #ef4444;
    width: 25%;
}

.strength-fair {
    background: #f59e0b;
    width: 50%;
}

.strength-good {
    background: #eab308;
    width: 75%;
}

.strength-strong {
    background: #10b981;
    width: 100%;
}

.form-options {
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
    font-size: 0.875rem;
}

.form-options input[type="checkbox"] {
    width: 1rem;
    height: 1rem;
    accent-color: var(--primary);
    margin-top: 0.125rem;
    transition: transform 0.2s ease;
}

.form-options input[type="checkbox"]:hover {
    transform: scale(1.1);
}

.form-options a {
    color: var(--primary);
    text-decoration: none;
    transition: all 0.3s ease;
    position: relative;
}

.form-options a::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 0;
    height: 1px;
    background: var(--primary);
    transition: width 0.3s ease;
}

.form-options a:hover::after {
    width: 100%;
}

.form-options a:hover {
    text-decoration: none;
}

.signup-button {
    width: 100%;
    margin-top: 0.5rem;
}

.divider {
    display: flex;
    align-items: center;
    margin: 1.5rem 0;
    color: var(--muted-foreground);
    font-size: 0.875rem;
}

.divider::before,
.divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
}

.divider:hover::before,
.divider:hover::after {
    background: var(--primary);
}

.divider span {
    padding: 0 1rem;
}

.social-signup {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.social-button {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    padding: 0.75rem 1.5rem;
    border-radius: var(--radius);
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: var(--foreground);
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.social-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
    transition: left 0.5s;
}

.social-button:hover::before {
    left: 100%;
}

.social-button:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-2px);
    border-color: var(--primary);
}

.social-button svg {
    width: 1.25rem;
    height: 1.25rem;
    transition: transform 0.3s ease;
}

.social-button:hover svg {
    transform: scale(1.1);
}

.login-link {
    text-align: center;
    margin-top: 2rem;
    font-size: 0.875rem;
    color: var(--muted-foreground);
}

.login-link a {
    color: var(--primary);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    position: relative;
}

.login-link a::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 0;
    height: 1px;
    background: var(--primary);
    transition: width 0.3s ease;
}

.login-link a:hover::after {
    width: 100%;
}

.login-link a:hover {
    text-decoration: none;
}

.signup-features {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.feature-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.feature-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem;
    border-radius: var(--radius);
    transition: all 0.3s ease;
    cursor: pointer;
}

.feature-item:hover {
    background: rgba(255, 255, 255, 0.05);
    transform: translateX(10px);
}

.feature-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 3rem;
    height: 3rem;
    background: linear-gradient(135deg, hsla(189, 94%, 55%, 0.2), hsla(217, 91%, 60%, 0.2));
    border-radius: var(--radius);
    flex-shrink: 0;
    transition: all 0.3s ease;
}

.feature-item:hover .feature-icon {
    background: linear-gradient(135deg, hsla(189, 94%, 55%, 0.3), hsla(217, 91%, 60%, 0.3));
    transform: scale(1.1) rotate(5deg);
}

.feature-icon svg {
    width: 1.5rem;
    height: 1.5rem;
    color: var(--primary);
    transition: transform 0.3s ease;
}

.feature-item:hover .feature-icon svg {
    transform: scale(1.1);
}

.feature-content h3 {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    transition: color 0.3s ease;
}

.feature-item:hover .feature-content h3 {
    color: var(--primary);
}

.feature-content p {
    font-size: 0.875rem;
    color: var(--muted-foreground);
    line-height: 1.5;
}

.pricing-highlight {
    background: rgba(255, 255, 255, 0.05);
    border-radius: var(--radius);
    padding: 1.5rem;
    margin-top: 1rem;
    transition: all 0.3s ease;
}

.pricing-highlight:hover {
    background: rgba(255, 255, 255, 0.08);
    transform: translateY(-5px);
}

.pricing-highlight h4 {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
}

.pricing-option {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
    padding: 0.75rem;
    border-radius: var(--radius);
    background: rgba(255, 255, 255, 0.03);
    transition: all 0.3s ease;
    cursor: pointer;
}

.pricing-option:hover {
    background: rgba(255, 255, 255, 0.05);
    transform: translateX(5px);
}

.pricing-option input[type="radio"] {
    width: 1.25rem;
    height: 1.25rem;
    accent-color: var(--primary);
    transition: transform 0.2s ease;
}

.pricing-option:hover input[type="radio"] {
    transform: scale(1.1);
}

.pricing-info {
    flex: 1;
}

.pricing-name {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.pricing-desc {
    font-size: 0.75rem;
    color: var(--muted-foreground);
}

.pricing-price {
    font-weight: 700;
    color: var(--primary);
}

/* Alert Messages */
.alert {
    padding: 0.75rem 1rem;
    border-radius: var(--radius);
    margin-bottom: 1rem;
    font-size: 0.875rem;
    animation: slideUp 0.3s ease-out;
}

.alert-error {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #fca5a5;
}

.alert-success {
    background: rgba(34, 197, 94, 0.1);
    border: 1px solid rgba(34, 197, 94, 0.3);
    color: #86efac;
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
    transform: translateY(-3px);
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
    transition: all 0.3s ease;
    position: relative;
}

.footer-links a::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 0;
    height: 1px;
    background: var(--primary);
    transition: width 0.3s ease;
}

.footer-links a:hover::after {
    width: 100%;
}

.footer-links a:hover {
    color: var(--primary);
    text-decoration: none;
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

/* Responsive Utilities */
@media (max-width: 767px) {
    .mobile-only {
        display: block !important;
    }
    
    .desktop-only {
        display: none !important;
    }
}

@media (min-width: 768px) {
    .mobile-only {
        display: none !important;
    }
    
    .desktop-only {
        display: block !important;
    }
}
    </style>
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

        // Password strength indicator
        const passwordInput = document.getElementById('password');
        const strengthBar = document.getElementById('strengthBar');
        const strengthText = document.getElementById('strengthText');

        if (passwordInput && strengthBar && strengthText) {
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;
                let text = '';

                // Check password length
                if (password.length >= 8) strength += 25;
                
                // Check for lowercase letters
                if (/[a-z]/.test(password)) strength += 25;
                
                // Check for uppercase letters
                if (/[A-Z]/.test(password)) strength += 25;
                
                // Check for numbers and special characters
                if (/[0-9]/.test(password) || /[^A-Za-z0-9]/.test(password)) strength += 25;

                // Update strength bar and text
                strengthBar.className = 'strength-bar';
                
                if (strength === 0) {
                    text = '';
                } else if (strength <= 25) {
                    strengthBar.classList.add('strength-weak');
                    text = 'Weak';
                } else if (strength <= 50) {
                    strengthBar.classList.add('strength-fair');
                    text = 'Fair';
                } else if (strength <= 75) {
                    strengthBar.classList.add('strength-good');
                    text = 'Good';
                } else {
                    strengthBar.classList.add('strength-strong');
                    text = 'Strong';
                }
                
                strengthText.textContent = text;
            });
        }

        // Form validation
        const signupForm = document.getElementById('signupForm');
        if (signupForm) {
            signupForm.addEventListener('submit', (e) => {
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('confirmPassword').value;
                const terms = document.getElementById('terms').checked;
                
                // Basic validation
                if (password !== confirmPassword) {
                    e.preventDefault();
                    alert('Passwords do not match');
                    return;
                }
                
                if (!terms) {
                    e.preventDefault();
                    alert('Please agree to the Terms of Service and Privacy Policy');
                    return;
                }
            });
        }

        // Add active class to current page nav link
        const currentPage = window.location.pathname.split('/').pop() || 'signup.php';
        document.querySelectorAll('.nav-link, .mobile-nav-link').forEach(link => {
            const href = link.getAttribute('href');
            if (href === currentPage || (currentPage === 'signup.php' && href === 'signup.php')) {
                link.style.color = 'var(--primary)';
            }
        });

        // Add hover effects to pricing options
        document.querySelectorAll('.pricing-option').forEach(option => {
            option.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(5px)';
            });
            
            option.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
            });
        });

        // Add animation to feature items on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animation = 'fadeInUp 0.6s ease-out forwards';
                    entry.target.style.opacity = '1';
                }
            });
        }, observerOptions);

        // Observe feature items
        document.querySelectorAll('.feature-item').forEach(item => {
            item.style.opacity = '0';
            observer.observe(item);
        });
    </script>

    <?php include 'header-landing.php'; ?>

    <!-- Signup Section -->
    <section class="signup-section">
        <div class="signup-bg"></div>
        <div class="container">
            <div class="signup-container">
                <div class="signup-content fade-in-up">
                    <div class="signup-card glass-strong">
                        <div class="signup-header">
                            <div class="signup-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="8.5" cy="7" r="4"></circle>
                                    <path d="M20 8v6M23 11h-6"></path>
                                </svg>
                            </div>
                            <h1 class="signup-title">Create Account</h1>
                            <p class="signup-subtitle">Join TruthGuard AI to protect truth in the digital age</p>
                        </div>

                        <!-- Display error/success messages -->
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-error">
                                <?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success">
                                <?php echo htmlspecialchars($success); ?>
                            </div>
                        <?php endif; ?>

                        <form class="signup-form" id="signupForm" method="POST" action="">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="fullName" class="form-label">Full Name</label>
                                    <input type="text" id="fullName" name="fullName" class="form-input" placeholder="John Doe" value="<?php echo isset($_POST['fullName']) ? htmlspecialchars($_POST['fullName']) : ''; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" id="username" name="username" class="form-input" placeholder="johndoe" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" id="email" name="email" class="form-input" placeholder="name@company.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required>
                                <div class="password-strength">
                                    <div class="strength-meter">
                                        <div class="strength-bar" id="strengthBar"></div>
                                    </div>
                                    <div class="strength-text" id="strengthText"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="confirmPassword" class="form-label">Confirm Password</label>
                                <input type="password" id="confirmPassword" name="confirmPassword" class="form-input" placeholder="••••••••" required>
                            </div>

                            <input type="hidden" name="plan_id" id="planId" value="1">

                            <div class="form-options">
                                <input type="checkbox" id="terms" name="terms" required>
                                <label for="terms">
                                    I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                                </label>
                            </div>

                            <div class="form-options">
                                <input type="checkbox" id="newsletter" name="newsletter" <?php echo isset($_POST['newsletter']) ? 'checked' : ''; ?>>
                                <label for="newsletter">
                                    Send me product updates and security alerts
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary signup-button">
                                Create Account
                                <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M5 12h14M12 5l7 7-7 7"></path>
                                </svg>
                            </button>

                            <div class="divider">
                                <span>Or sign up with</span>
                            </div>

                           

                            <div class="login-link">
                                Already have an account? <a href="login.php">Log in</a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="signup-features fade-in-up delay-2">
                    <h2 class="signup-title">Join <span class="gradient-text">50,000+ Professionals</span> Protecting Truth</h2>
                    <p class="signup-subtitle">Get access to powerful tools to detect and combat misinformation</p>
                    
                    <div class="feature-list">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                </svg>
                            </div>
                            <div class="feature-content">
                                <h3>Advanced Deepfake Detection</h3>
                                <p>Our AI analyzes images and videos with 99.7% accuracy to detect manipulated content.</p>
                            </div>
                        </div>
                        
                        <div class="feature-item">
                            <div class="feature-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10 9 9 9 8 9"></polyline>
                                </svg>
                            </div>
                            <div class="feature-content">
                                <h3>Fake News Scanner</h3>
                                <p>Verify news articles and sources with our NLP-powered fact-checking system.</p>
                            </div>
                        </div>
                        
                        <div class="feature-item">
                            <div class="feature-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                                </svg>
                            </div>
                            <div class="feature-content">
                                <h3>URL Analysis</h3>
                                <p>Scan suspicious links for misinformation, phishing, and malicious content.</p>
                            </div>
                        </div>

                        <div class="feature-item">
                            <div class="feature-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <path d="M12 6v6l4 2"></path>
                                </svg>
                            </div>
                            <div class="feature-content">
                                <h3>Lightning Fast Results</h3>
                                <p>Get verification results in under 3 seconds with our optimized AI infrastructure.</p>
                            </div>
                        </div>
                    </div>

                    <div class="pricing-highlight glass">
                        <h4>Choose Your Plan</h4>
                        <div class="pricing-option" onclick="document.getElementById('planId').value='1'">
                            <input type="radio" id="beginners-plan" name="pricing" checked>
                            <div class="pricing-info">
                                <div class="pricing-name">Beginners Plan</div>
                                <div class="pricing-desc">Perfect for getting started</div>
                            </div>
                            <div class="pricing-price">₹19/mo</div>
                        </div>
                        <div class="pricing-option" onclick="document.getElementById('planId').value='2'">
                            <input type="radio" id="pro-plan" name="pricing">
                            <div class="pricing-info">
                                <div class="pricing-name">Pro Plan</div>
                                <div class="pricing-desc">For professionals & content creators</div>
                            </div>
                            <div class="pricing-price">₹49/mo</div>
                        </div>
                        <p style="font-size: 0.875rem; color: var(--muted-foreground); margin-top: 1rem;">
                            Start with 10 free scans daily. Upgrade anytime.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'footer-landing.php'; ?>
</body>
</html>