<?php
session_start();
require_once 'database.php';

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields';
    } else {
        try {
            // Check if user exists
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                // Login successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['logged_in'] = true;
                
                $success = 'Login successful! Redirecting...';
                
                // Redirect to dashboard after 2 seconds
                header("Refresh: 2; url=dashboard");
            } else {
                $error = 'Invalid email or password';
            }
        } catch(PDOException $e) {
            $error = 'Login failed: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TruthGuard AI</title>
    <meta name="description" content="Login to your TruthGuard AI account to protect yourself from AI-generated misinformation.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <style>
        /* All your existing CSS remains the same */
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

/* Login Section */
.login-section {
    position: relative;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding-top: 5rem;
    overflow: hidden;
}

.login-bg {
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom right, var(--background), var(--card));
}

.login-bg::before,
.login-bg::after {
    content: '';
    position: absolute;
    width: 24rem;
    height: 24rem;
    border-radius: 50%;
    filter: blur(120px);
    opacity: 0.3;
    animation: pulse-glow 3s ease-in-out infinite;
}

.login-bg::before {
    top: 25%;
    left: 25%;
    background: var(--primary);
}

.login-bg::after {
    bottom: 25%;
    right: 25%;
    background: var(--glow-blue);
    animation-delay: 1s;
}

.login-container {
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
    .login-container {
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        padding: 2rem 2rem;
    }
}

.login-content {
    max-width: 28rem;
    margin: 0 auto;
    width: 100%;
}

.login-card {
    padding: 2rem;
    border-radius: var(--radius);
    box-shadow: 0 20px 60px hsla(189, 94%, 55%, 0.2);
}

@media (min-width: 640px) {
    .login-card {
        padding: 3rem;
    }
}

.login-header {
    text-align: center;
    margin-bottom: 2rem;
}

.login-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 4rem;
    height: 4rem;
    background: linear-gradient(135deg, var(--glow-cyan), var(--glow-blue));
    border-radius: 50%;
    margin-bottom: 1rem;
}

.login-icon svg {
    width: 2rem;
    height: 2rem;
    color: white;
}

.login-title {
    font-size: 1.875rem;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 0.5rem;
}

.login-subtitle {
    color: var(--muted-foreground);
    font-size: 1rem;
}

.login-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
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
}

.form-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.875rem;
}

.remember-me {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.remember-me input[type="checkbox"] {
    width: 1rem;
    height: 1rem;
    accent-color: var(--primary);
}

.forgot-password {
    color: var(--primary);
    text-decoration: none;
    transition: all 0.3s ease;
}

.forgot-password:hover {
    text-decoration: underline;
}

.login-button {
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
}

.divider span {
    padding: 0 1rem;
}

.social-login {
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
}

.social-button:hover {
    background: rgba(255, 255, 255, 0.1);
}

.social-button svg {
    width: 1.25rem;
    height: 1.25rem;
}

.signup-link {
    text-align: center;
    margin-top: 2rem;
    font-size: 0.875rem;
    color: var(--muted-foreground);
}

.signup-link a {
    color: var(--primary);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.signup-link a:hover {
    text-decoration: underline;
}

.login-features {
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
}

.feature-icon svg {
    width: 1.5rem;
    height: 1.5rem;
    color: var(--primary);
}

.feature-content h3 {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.feature-content p {
    font-size: 0.875rem;
    color: var(--muted-foreground);
    line-height: 1.5;
}

/* Alert Messages */
.alert {
    padding: 0.75rem 1rem;
    border-radius: var(--radius);
    margin-bottom: 1rem;
    font-size: 0.875rem;
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

// Form validation
const loginForm = document.getElementById('loginForm');
if (loginForm) {
    loginForm.addEventListener('submit', (e) => {
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        
        // Basic validation
        if (!email || !password) {
            e.preventDefault();
            alert('Please fill in all fields');
            return;
        }
    });
}

// Add active class to current page nav link
const currentPage = window.location.pathname.split('/').pop() || 'login.php';
document.querySelectorAll('.nav-link, .mobile-nav-link').forEach(link => {
    const href = link.getAttribute('href');
    if (href === currentPage || (currentPage === 'login.php' && href === 'login.php')) {
        link.style.color = 'var(--primary)';
    }
});
    </script>

    <?php include 'header-landing.php'; ?>

    <!-- Login Section -->
    <section class="login-section">
        <div class="login-bg"></div>
        <div class="container">
            <div class="login-container">
                <div class="login-content fade-in-up">
                    <div class="login-card glass-strong">
                        <div class="login-header">
                            <div class="login-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </div>
                            <h1 class="login-title">Welcome Back</h1>
                            <p class="login-subtitle">Sign in to your TruthGuard AI account</p>
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

                        <form class="login-form" id="loginForm" method="POST" action="">
                            <div class="form-group">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" id="email" name="email" class="form-input" placeholder="name@company.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required>
                            </div>

                            <div class="form-options">
                                <label class="remember-me">
                                    <input type="checkbox" id="remember" name="remember">
                                    <span>Remember me</span>
                                </label>
                                <a href="forgot-password.php" class="forgot-password">Forgot password?</a>
                            </div>

                            <button type="submit" class="btn btn-primary login-button">
                                Sign In
                                <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M5 12h14M12 5l7 7-7 7"></path>
                                </svg>
                            </button>

                            <div class="divider">
                                <span>Or continue with</span>
                            </div>

                           

                            <div class="signup-link">
                                Don't have an account? <a href="signup.php">Sign up</a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="login-features fade-in-up delay-2">
                    <h2 class="login-title">Why Join <span class="gradient-text">TruthGuard AI</span>?</h2>
                    <p class="login-subtitle">Access powerful tools to protect yourself and others from misinformation</p>
                    
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
                    </div>
                </div>
            </div>
        </div>
    </section>

   <?php include 'footer-landing.php'; ?>
</body>
</html>