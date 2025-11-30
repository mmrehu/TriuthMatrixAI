<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - TruthGuard AI</title>
    <meta name="description" content="Learn about TruthGuard AI's mission to combat misinformation and our team of experts dedicated to protecting truth in the digital age.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
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
        /* Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px hsla(189, 94%, 55%, 0.3); }
            50% { box-shadow: 0 0 40px hsla(189, 94%, 55%, 0.6); }
        }
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
        .pulse {
            animation: pulse-glow 2s ease-in-out infinite;
        }
        /* Section Styles */
        .section {
            padding: 5rem 0;
            position: relative;
        }
        @media (min-width: 1024px) {
            .section {
                padding: 8rem 0;
            }
        }
        .section-header {
            text-align: center;
            max-width: 48rem;
            margin: 0 auto 4rem;
        }
        .section-title {
            font-size: 1.875rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1rem;
        }
        @media (min-width: 768px) {
            .section-title {
                font-size: 2.5rem;
            }
        }
        @media (min-width: 1024px) {
            .section-title {
                font-size: 3rem;
            }
        }
        .section-description {
            font-size: 1.125rem;
            color: var(--muted-foreground);
        }
        /* Badge */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 2rem;
        }
        .badge-icon {
            width: 1rem;
            height: 1rem;
            color: var(--primary);
        }
        /* About Hero Section */
        .about-hero {
            position: relative;
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding-top: 5rem;
            overflow: hidden;
        }
        .about-hero-bg {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom right, var(--background), var(--card));
        }
        .about-hero-bg::before,
        .about-hero-bg::after {
            content: '';
            position: absolute;
            width: 24rem;
            height: 24rem;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.3;
            animation: pulse-glow 3s ease-in-out infinite;
        }
        .about-hero-bg::before {
            top: 25%;
            left: 25%;
            background: var(--primary);
        }
        .about-hero-bg::after {
            bottom: 25%;
            right: 25%;
            background: var(--glow-blue);
            animation-delay: 1s;
        }
        .about-hero-content {
            text-align: center;
            max-width: 64rem;
            margin: 0 auto;
            position: relative;
            z-index: 10;
        }
        .about-hero-title {
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.5rem;
        }
        @media (min-width: 768px) {
            .about-hero-title {
                font-size: 3rem;
            }
        }
        @media (min-width: 1024px) {
            .about-hero-title {
                font-size: 3.75rem;
            }
        }
        .about-hero-description {
            font-size: 1.25rem;
            color: var(--muted-foreground);
            max-width: 48rem;
            margin: 0 auto 2rem;
        }
        /* Mission & Vision Section */
        .mission-section {
            background: linear-gradient(to bottom, var(--background), rgba(255, 255, 255, 0.02), var(--background));
        }
        .mission-grid {
            display: grid;
            gap: 3rem;
            margin-bottom: 4rem;
        }
        @media (min-width: 1024px) {
            .mission-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 4rem;
            }
        }
        .mission-card {
            padding: 2rem;
            border-radius: var(--radius);
            transition: all 0.3s ease;
            position: relative;
        }
        .mission-card:hover {
            transform: scale(1.02);
        }
        .mission-icon-wrapper {
            margin-bottom: 1.5rem;
        }
        .mission-icon-bg {
            width: 5rem;
            height: 5rem;
            background: linear-gradient(135deg, hsla(189, 94%, 55%, 0.2), hsla(217, 91%, 60%, 0.2));
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s ease;
        }
        .mission-card:hover .mission-icon-bg {
            transform: scale(1.1);
        }
        .mission-icon {
            width: 2.5rem;
            height: 2.5rem;
            color: var(--primary);
        }
        .mission-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        .mission-description {
            font-size: 1rem;
            color: var(--muted-foreground);
            line-height: 1.6;
        }
        /* Story Section */
        .story-section {
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.02), var(--background), rgba(255, 255, 255, 0.02));
        }
        .story-timeline {
            position: relative;
            max-width: 64rem;
            margin: 0 auto;
        }
        .story-timeline::before {
            content: '';
            position: absolute;
            left: 50%;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(to bottom, transparent, var(--primary), transparent);
            transform: translateX(-50%);
        }
        @media (max-width: 768px) {
            .story-timeline::before {
                left: 1.5rem;
            }
        }
        .timeline-item {
            display: flex;
            margin-bottom: 4rem;
            position: relative;
        }
        @media (max-width: 768px) {
            .timeline-item {
                flex-direction: column;
            }
        }
        .timeline-item:nth-child(odd) {
            flex-direction: row-reverse;
        }
        @media (max-width: 768px) {
            .timeline-item:nth-child(odd) {
                flex-direction: column;
            text-align: left;
            align-items: flex-start;
            margin-left: 3rem;
            margin-right: 0;
            padding-left: 0;
            padding-right: 0;
            width: auto;
            max-width: none;
            margin-bottom: 3rem;
            position: relative;
            left: auto;
                transform: none;
            }
        }
        .timeline-content {
            flex: 1;
            padding: 2rem;
            border-radius: var(--radius);
            width: 45%;
        }
        @media (max-width: 768px) {
            .timeline-content {
                width: 100%;
                padding: 1.5rem;
                margin-left: 0;
                margin-right: 0;
            }
        }
        .timeline-item:nth-child(odd) .timeline-content {
            margin-left: 5%;
        }
        @media (max-width: 768px) {
            .timeline-item:nth-child(odd) .timeline-content {
                margin-left: 0;
            }
        }
        .timeline-item:nth-child(even) .timeline-content {
            margin-right: 5%;
        }
        @media (max-width: 768px) {
            .timeline-item:nth-child(even) .timeline-content {
                margin-right: 0;
            }
        }
        .timeline-marker {
            position: absolute;
            left: 50%;
            top: 2rem;
            transform: translateX(-50%);
            width: 1.5rem;
            height: 1.5rem;
            background: linear-gradient(135deg, var(--glow-cyan), var(--glow-blue));
            border-radius: 50%;
            border: 4px solid var(--background);
            z-index: 10;
        }
        @media (max-width: 768px) {
            .timeline-marker {
                left: 1.5rem;
            }
        }
        .timeline-year {
            position: absolute;
            left: 50%;
            top: -1.5rem;
            transform: translateX(-50%);
            background: linear-gradient(135deg, var(--glow-cyan), var(--glow-blue));
            color: white;
            font-weight: 700;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            white-space: nowrap;
        }
        @media (max-width: 768px) {
            .timeline-year {
                left: 1.5rem;
                transform: none;
            }
        }
        .timeline-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }
        .timeline-description {
            font-size: 0.875rem;
            color: var(--muted-foreground);
            line-height: 1.5;
        }
        /* Team Section */
        .team-section {
            background: linear-gradient(to bottom, var(--background), rgba(255, 255, 255, 0.02), var(--background));
        }
        .team-grid {
            display: grid;
            gap: 2rem;
            margin-bottom: 4rem;
        }
        @media (min-width: 768px) {
            .team-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (min-width: 1024px) {
            .team-grid {
                grid-template-columns: repeat(3, 1fr);
            gap: 3rem;
            margin-bottom: 4rem;
            }
        }
        @media (min-width: 1280px) {
            .team-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }
        .team-card {
            padding: 2rem;
            border-radius: var(--radius);
            transition: all 0.3s ease;
            position: relative;
            text-align: center;
        }
        .team-card:hover {
            transform: translateY(-10px);
        }
        .team-avatar {
            width: 8rem;
            height: 8rem;
            border-radius: 50%;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, var(--glow-cyan), var(--glow-blue));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 2rem;
            color: white;
            position: relative;
            overflow: hidden;
        }
        .team-avatar::before {
            content: '';
            position: absolute;
            inset: 2px;
            background: var(--background);
            border-radius: 50%;
            z-index: 1;
        }
        .team-avatar span {
            position: relative;
            z-index: 2;
        }
        .team-name {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .team-role {
            font-size: 0.875rem;
            color: var(--primary);
            margin-bottom: 1rem;
            font-weight: 500;
        }
        .team-bio {
            font-size: 0.875rem;
            color: var(--muted-foreground);
            line-height: 1.5;
            margin-bottom: 1.5rem;
        }
        .team-social {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }
        .team-social-link {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .team-social-link:hover {
            background: rgba(255, 255, 255, 0.15);
        }
        .team-social-link svg {
            width: 1.25rem;
            height: 1.25rem;
            color: var(--muted-foreground);
            transition: color 0.3s ease;
        }
        .team-social-link:hover svg {
            color: var(--primary);
        }
        /* Values Section */
        .values-section {
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.02), var(--background), rgba(255, 255, 255, 0.02));
        }
        .values-grid {
            display: grid;
            gap: 2rem;
        }
        @media (min-width: 768px) {
            .values-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (min-width: 1024px) {
            .values-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }
        .value-card {
            padding: 2rem;
            border-radius: var(--radius);
            transition: all 0.3s ease;
            position: relative;
            text-align: center;
        }
        .value-card:hover {
            transform: scale(1.05);
        }
        .value-icon-wrapper {
            margin-bottom: 1.5rem;
        }
        .value-icon-bg {
            width: 5rem;
            height: 5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            background: linear-gradient(135deg, hsla(189, 94%, 55%, 0.2), hsla(217, 91%, 60%, 0.2));
        }
        .value-icon {
            width: 2.5rem;
            height: 2.5rem;
            color: var(--primary);
        }
        .value-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .value-description {
            font-size: 0.875rem;
            color: var(--muted-foreground);
            line-height: 1.5;
        }
        /* Stats Section */
        .stats-section {
            background: linear-gradient(to bottom, var(--background), rgba(255, 255, 255, 0.02), var(--background));
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
        }
        @media (min-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }
        .stat-card {
            padding: 2rem;
            border-radius: var(--radius);
            text-align: center;
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        @media (min-width: 768px) {
            .stat-number {
                font-size: 3rem;
            }
        }
        .stat-text {
            font-size: 0.875rem;
            color: var(--muted-foreground);
        }
        /* CTA Section */
        .cta-section {
            position: relative;
            overflow: hidden;
        }
        .cta-section::before,
        .cta-section::after {
            content: '';
            position: absolute;
            width: 24rem;
            height: 24rem;
            border-radius: 50%;
            filter: blur(120px);
            animation: pulse-glow 3s ease-in-out infinite;
        }
        .cta-section::before {
            top: 25%;
            left: 25%;
            background: hsla(189, 94%, 55%, 0.3);
        }
        .cta-section::after {
            bottom: 25%;
            right: 25%;
            background: hsla(217, 91%, 60%, 0.3);
            animation-delay: 1.5s;
        }
        .cta-card {
            max-width: 64rem;
            margin: 0 auto;
            padding: 4rem 2rem;
            border-radius: 1.5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        @media (min-width: 768px) {
            .cta-card {
                padding: 6rem 3rem;
            }
        }
        @media (min-width: 1024px) {
            .cta-card {
                padding: 8rem 4rem;
            }
        }
        .cta-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to right, hsla(189, 94%, 55%, 0.1), transparent, hsla(217, 91%, 60%, 0.1));
            pointer-events: none;
        }
        .cta-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 5rem;
            height: 5rem;
            background: linear-gradient(135deg, var(--glow-cyan), var(--glow-blue));
            border-radius: 50%;
            margin-bottom: 1.5rem;
        }
        .cta-icon svg {
            width: 2.5rem;
            height: 2.5rem;
            color: white;
        }
        .cta-title {
            font-size: 1.875rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.5rem;
        }
        @media (min-width: 768px) {
            .cta-title {
                font-size: 2.5rem;
            }
        }
        @media (min-width: 1024px) {
            .cta-title {
                font-size: 3rem;
            }
        }
        .cta-description {
            font-size: 1.125rem;
            color: var(--muted-foreground);
            max-width: 42rem;
            margin: 0 auto 2rem;
        }
        .cta-buttons {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            align-items: center;
            justify-content: center;
            margin-bottom: 3rem;
        }
        @media (min-width: 640px) {
            .cta-buttons {
                flex-direction: row;
            }
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
    <!-- Header would be included here -->
    <?php include 'header-landing.php'; ?>
    
    <!-- About Hero Section -->
    <section class="about-hero">
        <div class="about-hero-bg"></div>
        <div class="container">
            <div class="about-hero-content" data-aos="fade-up" data-aos-duration="1000">
                <div class="badge">
                    <svg class="badge-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                    <span>Hackathon Project</span>
                </div>
                <h1 class="about-hero-title">
                    Protecting Truth in the <span class="gradient-text">Digital Age</span>
                </h1>
                <p class="about-hero-description">
                    TruthGuard AI is our hackathon project to combat the growing threat of AI-generated misinformation.
                    Our platform uses cutting-edge artificial intelligence to detect deepfakes, verify news sources,
                    and help restore trust in digital media.
                </p>
                <div class="cta-buttons">
                    <a href="signup.php" class="btn btn-primary btn-lg">
                        Try Our Demo
                        <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    <a href="contact.php" class="btn btn-outline btn-lg">Contact Us</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision Section -->
    <section class="section mission-section">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <h2 class="section-title">
                    Our <span class="gradient-text">Mission & Vision</span>
                </h2>
                <p class="section-description">
                    We're building a future where truth prevails over deception
                </p>
            </div>
            <div class="mission-grid">
                <div class="mission-card glass-strong" data-aos="fade-right" data-aos-delay="200">
                    <div class="mission-icon-wrapper">
                        <div class="mission-icon-bg">
                            <svg class="mission-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="mission-title">Our Mission</h3>
                    <p class="mission-description">
                        To empower individuals and organizations with advanced AI tools that detect and combat misinformation,
                        protecting society from the harmful effects of deepfakes and fake news. We believe in a digital world
                        where truth is accessible, verifiable, and protected.
                    </p>
                </div>
                <div class="mission-card glass-strong" data-aos="fade-left" data-aos-delay="200">
                    <div class="mission-icon-wrapper">
                        <div class="mission-icon-bg">
                            <svg class="mission-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </div>
                    </div>
                    <h3 class="mission-title">Our Vision</h3>
                    <p class="mission-description">
                        We envision a future where AI-generated content is easily identifiable, where digital media
                        can be trusted, and where misinformation no longer threatens democracy, public safety, and
                        personal relationships. Through continuous innovation, we aim to stay ahead of evolving
                        threats to information integrity.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Story Section -->
    <section class="section story-section">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <div class="badge">
                    <svg class="badge-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    <span>Our Journey</span>
                </div>
                <h2 class="section-title">
                    The <span class="gradient-text">TruthGuard Story</span>
                </h2>
                <p class="section-description">
                    From a hackathon idea to a working prototype fighting misinformation
                </p>
            </div>
            <div class="story-timeline">
                <div class="timeline-item" data-aos="fade-up" data-aos-delay="100">
                    <div class="timeline-content glass-strong">
                        <div class="timeline-marker"></div>
                        <div class="timeline-year">Day 1</div>
                        <h3 class="timeline-title">Idea Conception</h3>
                        <p class="timeline-description">
                            Our team came together during the hackathon with a shared concern about AI-generated misinformation.
                            We brainstormed and designed the initial concept for TruthGuard AI.
                        </p>
                    </div>
                </div>
                <div class="timeline-item" data-aos="fade-up" data-aos-delay="200">
                    <div class="timeline-content glass-strong">
                        <div class="timeline-marker"></div>
                        <div class="timeline-year">Day 1</div>
                        <h3 class="timeline-title">Development Sprint</h3>
                        <p class="timeline-description">
                            We built the core platform using cutting-edge technologies, focusing on deepfake detection
                            and source verification. Our team worked tirelessly to implement the AI models and user interface.
                        </p>
                    </div>
                </div>
                <div class="timeline-item" data-aos="fade-up" data-aos-delay="300">
                    <div class="timeline-content glass-strong">
                        <div class="timeline-marker"></div>
                        <div class="timeline-year">Day 2</div>
                        <h3 class="timeline-title">Testing & Refinement</h3>
                        <p class="timeline-description">
                            We rigorously tested our platform with sample data and simulated attacks. We refined our
                            algorithms to improve accuracy and user experience.
                        </p>
                    </div>
                </div>
                <div class="timeline-item" data-aos="fade-up" data-aos-delay="400">
                    <div class="timeline-content glass-strong">
                        <div class="timeline-marker"></div>
                        <div class="timeline-year">Present</div>
                        <h3 class="timeline-title">Hackathon Submission</h3>
                        <p class="timeline-description">
                            We're proud to present TruthGuard AI as our hackathon submission - a working prototype
                            that demonstrates our commitment to fighting misinformation in the digital age.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="section team-section">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <div class="badge">
                    <svg class="badge-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <span>Meet Our Team</span>
                </div>
                <h2 class="section-title">
                    The Minds Behind <span class="gradient-text">TruthGuard</span>
                </h2>
                <p class="section-description">
                    Our team of four developers who built this project during the hackathon
                </p>
            </div>
            <div class="team-grid">
                <div class="team-card glass-strong" data-aos="fade-up" data-aos-delay="100">
                    <div class="team-avatar">
                        <span>VD</span>
                    </div>
                    <h3 class="team-name">Vanshdepp</h3>
                    <div class="team-role">Developer</div>
                    <p class="team-bio">
                        Contributed to the backend infrastructure and API development for TruthGuard AI.
                        Focused on building scalable systems for processing media content.
                    </p>
                    <div class="team-social">
                        <a href="#" class="team-social-link">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path>
                                <rect x="2" y="9" width="4" height="12"></rect>
                                <circle cx="4" cy="4" r="2"></circle>
                            </svg>
                        </a>
                        <a href="#" class="team-social-link">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="team-card glass-strong" data-aos="fade-up" data-aos-delay="200">
                    <div class="team-avatar">
                        <span>AS</span>
                    </div>
                    <h3 class="team-name">Armaan Singh Sandhu</h3>
                    <div class="team-role">Developer</div>
                    <p class="team-bio">
                        Led the frontend development and UI/UX design of TruthGuard AI. Created the intuitive
                        interface that makes our technology accessible to all users.
                    </p>
                    <div class="team-social">
                        <a href="#" class="team-social-link">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path>
                                <rect x="2" y="9" width="4" height="12"></rect>
                                <circle cx="4" cy="4" r="2"></circle>
                            </svg>
                        </a>
                        <a href="#" class="team-social-link">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="team-card glass-strong" data-aos="fade-up" data-aos-delay="300">
                    <div class="team-avatar">
                        <span>RT</span>
                    </div>
                    <h3 class="team-name">Rehu Talwar</h3>
                    <div class="team-role">Developer</div>
                    <p class="team-bio">
                        Worked on the AI/ML components and algorithm development. Specialized in implementing
                        detection models for identifying manipulated media and fake content.
                    </p>
                    <div class="team-social">
                        <a href="#" class="team-social-link">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path>
                                <rect x="2" y="9" width="4" height="12"></rect>
                                <circle cx="4" cy="4" r="2"></circle>
                            </svg>
                        </a>
                        <a href="#" class="team-social-link">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="team-card glass-strong" data-aos="fade-up" data-aos-delay="400">
                    <div class="team-avatar">
                        <span>AS</span>
                    </div>
                    <h3 class="team-name">Arshdeep Singh</h3>
                    <div class="team-role">Developer</div>
                    <p class="team-bio">
                        Focused on full-stack development and integration. Ensured all components work
                        seamlessly together to deliver a smooth user experience.
                    </p>
                    <div class="team-social">
                        <a href="#" class="team-social-link">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path>
                                <rect x="2" y="9" width="4" height="12"></rect>
                                <circle cx="4" cy="4" r="2"></circle>
                            </svg>
                        </a>
                        <a href="#" class="team-social-link">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="section values-section">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <div class="badge">
                    <svg class="badge-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                    <span>Our Values</span>
                </div>
                <h2 class="section-title">
                    What <span class="gradient-text">Drives Us</span>
                </h2>
                <p class="section-description">
                    The principles that guide our work and decision-making
                </p>
            </div>
            <div class="values-grid">
                <div class="value-card glass-strong" data-aos="fade-up" data-aos-delay="100">
                    <div class="value-icon-wrapper">
                        <div class="value-icon-bg">
                            <svg class="value-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="value-title">Integrity</h3>
                    <p class="value-description">
                        We are committed to transparency in our methods and honest in our communications.
                        Our algorithms are designed to ensure fairness and accuracy.
                    </p>
                </div>
                <div class="value-card glass-strong" data-aos="fade-up" data-aos-delay="200">
                    <div class="value-icon-wrapper">
                        <div class="value-icon-bg">
                            <svg class="value-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="value-title">Collaboration</h3>
                    <p class="value-description">
                        We believe in the power of teamwork. Our hackathon project brought together diverse
                        skills and perspectives to create something greater than the sum of its parts.
                    </p>
                </div>
                <div class="value-card glass-strong" data-aos="fade-up" data-aos-delay="300">
                    <div class="value-icon-wrapper">
                        <div class="value-icon-bg">
                            <svg class="value-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                            </svg>
                        </div>
                    </div>
                    <h3 class="value-title">Innovation</h3>
                    <p class="value-description">
                        We continuously push the boundaries of what's possible. Our hackathon project
                        demonstrates creative problem-solving and technical excellence.
                    </p>
                </div>
                <div class="value-card glass-strong" data-aos="fade-up" data-aos-delay="400">
                    <div class="value-icon-wrapper">
                        <div class="value-icon-bg">
                            <svg class="value-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </div>
                    </div>
                    <h3 class="value-title">Privacy</h3>
                    <p class="value-description">
                        We respect user privacy and implement data protection measures.
                        Content analysis is performed with security and minimal data retention.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="section stats-section">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <h2 class="section-title">
                    TruthGuard <span class="gradient-text">By The Numbers</span>
                </h2>
                <p class="section-description">
                    What we achieved during our hackathon development
                </p>
            </div>
            <div class="stats-grid">
                <div class="stat-card glass-strong" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-number gradient-text">4</div>
                    <div class="stat-text">Team Members</div>
                </div>
                <div class="stat-card glass-strong" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-number gradient-text">72+</div>
                    <div class="stat-text">Hours of Development</div>
                </div>
                <div class="stat-card glass-strong" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-number gradient-text">5+</div>
                    <div class="stat-text">Features Implemented</div>
                </div>
                <div class="stat-card glass-strong" data-aos="fade-up" data-aos-delay="400">
                    <div class="stat-number gradient-text">1</div>
                    <div class="stat-text">Working Prototype</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section cta-section">
        <div class="container">
            <div class="cta-card glass-strong" data-aos="fade-up">
                <div class="cta-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                </div>
                <h2 class="cta-title">
                    Try Our <span class="gradient-text">Hackathon Project</span>
                </h2>
                <p class="cta-description">
                    Experience our working prototype developed during the hackathon. Whether you're concerned about
                    fake news or interested in AI detection technology, TruthGuard AI demonstrates our approach
                    to combating misinformation.
                </p>
                <div class="cta-buttons">
                    <a href="signup.php" class="btn btn-primary btn-lg">
                        Try Demo
                        <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    <a href="contact.php" class="btn btn-outline btn-lg">Learn More</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer would be included here -->
    <?php include 'footer-landing.php'; ?>
    
    <!-- AOS Animation Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 100
        });
    </script>
</body>
</html>