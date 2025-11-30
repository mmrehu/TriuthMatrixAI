
   <?php
session_start();
$currentPage = 'leaderboard';  // Important: set this on every page
$pageTitle = 'Leaderboard';

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard - TruthGuard AI</title>
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

        /* Leaderboard Layout */
        .leaderboard {
            padding-top: 5rem;
            min-height: 100vh;
        }

        @media (min-width: 1024px) {
            .leaderboard {
                padding-top: 6rem;
            }
        }

        .leaderboard-content {
            padding: 2rem 0;
        }

        @media (min-width: 1024px) {
            .leaderboard-content {
                padding: 3rem 0;
            }
        }

        .leaderboard-grid {
            display: grid;
            gap: 1.5rem;
        }

        @media (min-width: 1024px) {
            .leaderboard-grid {
                grid-template-columns: 1fr 350px;
                gap: 2rem;
            }
        }

        .leaderboard-main {
            display: grid;
            gap: 1.5rem;
        }

        .leaderboard-sidebar {
            display: grid;
            gap: 1.5rem;
            align-content: start;
        }

        /* Leaderboard Cards */
        .leaderboard-card {
            padding: 1.5rem;
            border-radius: var(--radius);
            transition: all 0.3s ease;
        }

        .leaderboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px hsla(189, 94%, 55%, 0.2);
        }

        .leaderboard-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .leaderboard-card-title {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .leaderboard-card-icon {
            width: 2rem;
            height: 2rem;
            color: var(--primary);
        }

        /* Leaderboard List */
        .leaderboard-list {
            display: grid;
            gap: 1rem;
        }

        .leaderboard-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem;
            border-radius: var(--radius);
            transition: all 0.3s ease;
        }

        .leaderboard-item:hover {
            background: rgba(255, 255, 255, 0.05);
            transform: translateX(5px);
        }

        .leaderboard-rank {
            width: 2.5rem;
            height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--glow-cyan), var(--glow-blue));
            border-radius: 50%;
            font-weight: 700;
            font-size: 0.875rem;
        }

        .rank-1 {
            background: linear-gradient(135deg, #FFD700, #FFA500);
        }

        .rank-2 {
            background: linear-gradient(135deg, #C0C0C0, #A0A0A0);
        }

        .rank-3 {
            background: linear-gradient(135deg, #CD7F32, #A56C28);
        }

        .leaderboard-avatar {
            width: 3.5rem;
            height: 3.5rem;
            background: linear-gradient(135deg, var(--glow-cyan), var(--glow-blue));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
            font-size: 1.125rem;
        }

        .leaderboard-info {
            flex: 1;
        }

        .leaderboard-name {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }

        .leaderboard-badges {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .badge {
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.625rem;
            font-weight: 600;
        }

        .badge-primary {
            background: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
        }

        .badge-success {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
        }

        .badge-warning {
            background: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
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

        .leaderboard-accuracy {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .accuracy-bar {
            width: 100px;
            height: 4px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 2px;
            overflow: hidden;
        }

        .accuracy-fill {
            height: 100%;
            background: linear-gradient(135deg, var(--glow-cyan), var(--glow-blue));
            border-radius: 2px;
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
  <?php include 'header.php'; ?>
    <!-- Leaderboard Section -->
    <section class="leaderboard">
        <div class="container">
            <div class="leaderboard-content">
                <div class="leaderboard-grid">
                    <div class="leaderboard-main">
                        <!-- Welcome Card -->
                        <div class="leaderboard-card glass-strong fade-in-up">
                            <div class="leaderboard-card-header">
                                <h2 class="leaderboard-card-title">Top Contributors</h2>
                                <svg class="leaderboard-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="8.5" cy="7" r="4"></circle>
                                    <path d="M20 8v6M23 11h-6"></path>
                                </svg>
                            </div>
                            <p>Our most accurate contributors who help identify fake content and maintain truth in the community.</p>
                        </div>

                        <!-- Tabs -->
                        <div class="leaderboard-card glass-strong fade-in-up delay-1">
                            <div class="tabs">
                                <button class="tab active" data-tab="all-time">All Time</button>
                                <button class="tab" data-tab="monthly">This Month</button>
                                <button class="tab" data-tab="weekly">This Week</button>
                            </div>

                            <!-- Leaderboard List -->
                            <div class="leaderboard-list" id="all-time">
                                <!-- Top 1 -->
                                <div class="leaderboard-item glass fade-in-up delay-2">
                                    <div class="leaderboard-rank rank-1">1</div>
                                    <div class="leaderboard-avatar">SA</div>
                                    <div class="leaderboard-info">
                                        <div class="leaderboard-name">Sarah Anderson</div>
                                        <div class="leaderboard-badges">
                                            <span class="badge badge-success">Top Voter</span>
                                            <span class="badge badge-primary">Fact Checker</span>
                                        </div>
                                        <div class="leaderboard-stats">
                                            <span class="leaderboard-score">4,892 pts</span>
                                            <span class="leaderboard-accuracy">
                                                <div class="accuracy-bar">
                                                    <div class="accuracy-fill" style="width: 98.2%"></div>
                                                </div>
                                                98.2%
                                            </span>
                                            <span>1,247 votes</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Top 2 -->
                                <div class="leaderboard-item glass fade-in-up delay-3">
                                    <div class="leaderboard-rank rank-2">2</div>
                                    <div class="leaderboard-avatar">MJ</div>
                                    <div class="leaderboard-info">
                                        <div class="leaderboard-name">Mike Johnson</div>
                                        <div class="leaderboard-badges">
                                            <span class="badge badge-warning">Rising Star</span>
                                        </div>
                                        <div class="leaderboard-stats">
                                            <span class="leaderboard-score">4,521 pts</span>
                                            <span class="leaderboard-accuracy">
                                                <div class="accuracy-bar">
                                                    <div class="accuracy-fill" style="width: 97.8%"></div>
                                                </div>
                                                97.8%
                                            </span>
                                            <span>1,103 votes</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Top 3 -->
                                <div class="leaderboard-item glass fade-in-up delay-4">
                                    <div class="leaderboard-rank rank-3">3</div>
                                    <div class="leaderboard-avatar">ER</div>
                                    <div class="leaderboard-info">
                                        <div class="leaderboard-name">Emily Rodriguez</div>
                                        <div class="leaderboard-badges">
                                            <span class="badge badge-primary">Fact Checker</span>
                                        </div>
                                        <div class="leaderboard-stats">
                                            <span class="leaderboard-score">4,210 pts</span>
                                            <span class="leaderboard-accuracy">
                                                <div class="accuracy-bar">
                                                    <div class="accuracy-fill" style="width: 96.5%"></div>
                                                </div>
                                                96.5%
                                            </span>
                                            <span>987 votes</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Rest of the list -->
                                <div class="leaderboard-item glass fade-in-up delay-5">
                                    <div class="leaderboard-rank">4</div>
                                    <div class="leaderboard-avatar">DW</div>
                                    <div class="leaderboard-info">
                                        <div class="leaderboard-name">David Wilson</div>
                                        <div class="leaderboard-stats">
                                            <span class="leaderboard-score">3,987 pts</span>
                                            <span class="leaderboard-accuracy">
                                                <div class="accuracy-bar">
                                                    <div class="accuracy-fill" style="width: 95.7%"></div>
                                                </div>
                                                95.7%
                                            </span>
                                            <span>876 votes</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="leaderboard-item glass fade-in-up delay-5">
                                    <div class="leaderboard-rank">5</div>
                                    <div class="leaderboard-avatar">PS</div>
                                    <div class="leaderboard-info">
                                        <div class="leaderboard-name">Priya Sharma</div>
                                        <div class="leaderboard-stats">
                                            <span class="leaderboard-score">3,745 pts</span>
                                            <span class="leaderboard-accuracy">
                                                <div class="accuracy-bar">
                                                    <div class="accuracy-fill" style="width: 94.9%"></div>
                                                </div>
                                                94.9%
                                            </span>
                                            <span>812 votes</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="leaderboard-item glass fade-in-up delay-5">
                                    <div class="leaderboard-rank">6</div>
                                    <div class="leaderboard-avatar">AR</div>
                                    <div class="leaderboard-info">
                                        <div class="leaderboard-name">Alex Rodriguez</div>
                                        <div class="leaderboard-stats">
                                            <span class="leaderboard-score">3,521 pts</span>
                                            <span class="leaderboard-accuracy">
                                                <div class="accuracy-bar">
                                                    <div class="accuracy-fill" style="width: 94.2%"></div>
                                                </div>
                                                94.2%
                                            </span>
                                            <span>754 votes</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="leaderboard-item glass fade-in-up delay-5">
                                    <div class="leaderboard-rank">7</div>
                                    <div class="leaderboard-avatar">MC</div>
                                    <div class="leaderboard-info">
                                        <div class="leaderboard-name">Maria Chen</div>
                                        <div class="leaderboard-stats">
                                            <span class="leaderboard-score">3,298 pts</span>
                                            <span class="leaderboard-accuracy">
                                                <div class="accuracy-bar">
                                                    <div class="accuracy-fill" style="width: 93.8%"></div>
                                                </div>
                                                93.8%
                                            </span>
                                            <span>698 votes</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="leaderboard-item glass fade-in-up delay-5">
                                    <div class="leaderboard-rank">8</div>
                                    <div class="leaderboard-avatar">JB</div>
                                    <div class="leaderboard-info">
                                        <div class="leaderboard-name">James Brown</div>
                                        <div class="leaderboard-stats">
                                            <span class="leaderboard-score">3,145 pts</span>
                                            <span class="leaderboard-accuracy">
                                                <div class="accuracy-bar">
                                                    <div class="accuracy-fill" style="width: 93.1%"></div>
                                                </div>
                                                93.1%
                                            </span>
                                            <span>642 votes</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="leaderboard-item glass fade-in-up delay-5">
                                    <div class="leaderboard-rank">9</div>
                                    <div class="leaderboard-avatar">LT</div>
                                    <div class="leaderboard-info">
                                        <div class="leaderboard-name">Lisa Thompson</div>
                                        <div class="leaderboard-stats">
                                            <span class="leaderboard-score">2,987 pts</span>
                                            <span class="leaderboard-accuracy">
                                                <div class="accuracy-bar">
                                                    <div class="accuracy-fill" style="width: 92.7%"></div>
                                                </div>
                                                92.7%
                                            </span>
                                            <span>598 votes</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="leaderboard-item glass fade-in-up delay-5">
                                    <div class="leaderboard-rank">10</div>
                                    <div class="leaderboard-avatar">RK</div>
                                    <div class="leaderboard-info">
                                        <div class="leaderboard-name">Robert Kim</div>
                                        <div class="leaderboard-stats">
                                            <span class="leaderboard-score">2,845 pts</span>
                                            <span class="leaderboard-accuracy">
                                                <div class="accuracy-bar">
                                                    <div class="accuracy-fill" style="width: 92.3%"></div>
                                                </div>
                                                92.3%
                                            </span>
                                            <span>567 votes</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Monthly Leaderboard (hidden by default) -->
                            <div class="leaderboard-list" id="monthly" style="display: none;">
                                <!-- Monthly leaderboard content would go here -->
                                <div class="leaderboard-item glass">
                                    <div class="leaderboard-rank rank-1">1</div>
                                    <div class="leaderboard-avatar">MJ</div>
                                    <div class="leaderboard-info">
                                        <div class="leaderboard-name">Mike Johnson</div>
                                        <div class="leaderboard-badges">
                                            <span class="badge badge-success">Top Voter</span>
                                        </div>
                                        <div class="leaderboard-stats">
                                            <span class="leaderboard-score">842 pts</span>
                                            <span class="leaderboard-accuracy">
                                                <div class="accuracy-bar">
                                                    <div class="accuracy-fill" style="width: 98.5%"></div>
                                                </div>
                                                98.5%
                                            </span>
                                            <span>187 votes</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- More monthly entries would follow -->
                            </div>

                            <!-- Weekly Leaderboard (hidden by default) -->
                            <div class="leaderboard-list" id="weekly" style="display: none;">
                                <!-- Weekly leaderboard content would go here -->
                                <div class="leaderboard-item glass">
                                    <div class="leaderboard-rank rank-1">1</div>
                                    <div class="leaderboard-avatar">ER</div>
                                    <div class="leaderboard-info">
                                        <div class="leaderboard-name">Emily Rodriguez</div>
                                        <div class="leaderboard-badges">
                                            <span class="badge badge-success">Top Voter</span>
                                        </div>
                                        <div class="leaderboard-stats">
                                            <span class="leaderboard-score">321 pts</span>
                                            <span class="leaderboard-accuracy">
                                                <div class="accuracy-bar">
                                                    <div class="accuracy-fill" style="width: 99.1%"></div>
                                                </div>
                                                99.1%
                                            </span>
                                            <span>76 votes</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- More weekly entries would follow -->
                            </div>
                        </div>
                    </div>

                    <div class="leaderboard-sidebar">
                        <!-- User Stats -->
                        <div class="leaderboard-card glass-strong fade-in-up delay-2">
                            <div class="leaderboard-card-header">
                                <h2 class="leaderboard-card-title">Your Ranking</h2>
                                <svg class="leaderboard-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </div>
                            <div class="user-stats">
                                <div class="user-stat glass">
                                    <div class="user-stat-value gradient-text">#28</div>
                                    <div class="user-stat-label">Global Rank</div>
                                </div>
                                <div class="user-stat glass">
                                    <div class="user-stat-value gradient-text">89%</div>
                                    <div class="user-stat-label">Accuracy</div>
                                </div>
                                <div class="user-stat glass">
                                    <div class="user-stat-value gradient-text">142</div>
                                    <div class="user-stat-label">Votes</div>
                                </div>
                                <div class="user-stat glass">
                                    <div class="user-stat-value gradient-text">2,145</div>
                                    <div class="user-stat-label">Points</div>
                                </div>
                            </div>
                            <div style="margin-top: 1.5rem;">
                                <a href="community.php" class="btn btn-primary w-full">
                                    <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                                    </svg>
                                    Increase Your Rank
                                </a>
                            </div>
                        </div>

                        <!-- Recent Activity -->
                        <div class="leaderboard-card glass-strong fade-in-up delay-3">
                            <div class="leaderboard-card-header">
                                <h2 class="leaderboard-card-title">Recent Activity</h2>
                                <svg class="leaderboard-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                    <polyline points="17 6 23 6 23 12"></polyline>
                                </svg>
                            </div>
                            <div class="activity-list">
                                <div class="activity-item glass">
                                    <div class="activity-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path>
                                        </svg>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-text">You voted on a community post</div>
                                        <div class="activity-time">2 hours ago</div>
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
                                        <div class="activity-time">Yesterday</div>
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
                                        <div class="activity-text">You moved up 3 positions in the leaderboard</div>
                                        <div class="activity-time">2 days ago</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Badges Info -->
                        <div class="leaderboard-card glass-strong fade-in-up delay-4">
                            <div class="leaderboard-card-header">
                                <h2 class="leaderboard-card-title">Badges</h2>
                                <svg class="leaderboard-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="8" r="7"></circle>
                                    <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                                </svg>
                            </div>
                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                                <div style="text-align: center;">
                                    <div style="width: 3rem; height: 3rem; margin: 0 auto 0.5rem; background: linear-gradient(135deg, var(--glow-cyan), var(--glow-blue)); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <svg style="width: 1.5rem; height: 1.5rem; color: white;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                                        </svg>
                                    </div>
                                    <div style="font-size: 0.75rem; font-weight: 500;">Fact Checker</div>
                                </div>
                                <div style="text-align: center;">
                                    <div style="width: 3rem; height: 3rem; margin: 0 auto 0.5rem; background: linear-gradient(135deg, #FFD700, #FFA500); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <svg style="width: 1.5rem; height: 1.5rem; color: white;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="8" r="7"></circle>
                                            <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                                        </svg>
                                    </div>
                                    <div style="font-size: 0.75rem; font-weight: 500;">Top Voter</div>
                                </div>
                                <div style="text-align: center;">
                                    <div style="width: 3rem; height: 3rem; margin: 0 auto 0.5rem; background: linear-gradient(135deg, #10b981, #059669); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <svg style="width: 1.5rem; height: 1.5rem; color: white;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                        </svg>
                                    </div>
                                    <div style="font-size: 0.75rem; font-weight: 500;">Accuracy Pro</div>
                                </div>
                                <div style="text-align: center;">
                                    <div style="width: 3rem; height: 3rem; margin: 0 auto 0.5rem; background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <svg style="width: 1.5rem; height: 1.5rem; color: white;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                                        </svg>
                                    </div>
                                    <div style="font-size: 0.75rem; font-weight: 500;">Rising Star</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
        const leaderboardLists = document.querySelectorAll('.leaderboard-list');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const target = tab.getAttribute('data-tab');
                
                // Update active tab
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                
                // Show target leaderboard, hide others
                leaderboardLists.forEach(list => {
                    if (list.id === target) {
                        list.style.display = 'grid';
                    } else {
                        list.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>
</html>