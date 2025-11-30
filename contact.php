<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - TruthGuard AI</title>
    <meta name="description" content="Get in touch with TruthGuard AI. We're here to help you combat misinformation and answer any questions about our deepfake detection technology.">
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

        /* Contact Hero Section */
        .contact-hero {
            position: relative;
            min-height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding-top: 5rem;
            overflow: hidden;
        }

        .contact-hero-bg {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom right, var(--background), var(--card));
        }

        .contact-hero-bg::before,
        .contact-hero-bg::after {
            content: '';
            position: absolute;
            width: 24rem;
            height: 24rem;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.3;
            animation: pulse-glow 3s ease-in-out infinite;
        }

        .contact-hero-bg::before {
            top: 25%;
            left: 25%;
            background: var(--primary);
        }

        .contact-hero-bg::after {
            bottom: 25%;
            right: 25%;
            background: var(--glow-blue);
            animation-delay: 1s;
        }

        .contact-hero-content {
            text-align: center;
            max-width: 64rem;
            margin: 0 auto;
            position: relative;
            z-index: 10;
        }

        .contact-hero-title {
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.5rem;
        }

        @media (min-width: 768px) {
            .contact-hero-title {
                font-size: 3rem;
            }
        }

        @media (min-width: 1024px) {
            .contact-hero-title {
                font-size: 3.75rem;
            }
        }

        .contact-hero-description {
            font-size: 1.25rem;
            color: var(--muted-foreground);
            max-width: 48rem;
            margin: 0 auto 2rem;
        }

        /* Contact Form Section */
        .contact-form-section {
            background: linear-gradient(to bottom, var(--background), rgba(255, 255, 255, 0.02), var(--background));
        }

        .contact-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 4rem;
            max-width: 80rem;
            margin: 0 auto;
        }

        @media (min-width: 1024px) {
            .contact-container {
                grid-template-columns: 1fr 1fr;
                gap: 6rem;
            }
        }

        .contact-form-card {
            padding: 2rem;
            border-radius: var(--radius);
            box-shadow: 0 20px 60px hsla(189, 94%, 55%, 0.2);
        }

        @media (min-width: 640px) {
            .contact-form-card {
                padding: 3rem;
            }
        }

        .contact-form-header {
            margin-bottom: 2rem;
        }

        .contact-form-title {
            font-size: 1.875rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .contact-form-subtitle {
            color: var(--muted-foreground);
            font-size: 1rem;
        }

        .contact-form {
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

        .form-input, .form-textarea {
            padding: 0.75rem 1rem;
            border-radius: var(--radius);
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--foreground);
            font-size: 0.875rem;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-input:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px hsla(189, 94%, 55%, 0.2);
        }

        .form-textarea {
            min-height: 150px;
            resize: vertical;
        }

        .form-submit {
            margin-top: 1rem;
            transition: all 0.3s ease;
        }

        .form-submit:hover {
            transform: translateY(-2px);
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .contact-methods {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .contact-method {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1.5rem;
            border-radius: var(--radius);
            transition: all 0.3s ease;
        }

        .contact-method:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.05);
        }

        .contact-method-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 3rem;
            height: 3rem;
            background: linear-gradient(135deg, hsla(189, 94%, 55%, 0.2), hsla(217, 91%, 60%, 0.2));
            border-radius: var(--radius);
            flex-shrink: 0;
        }

        .contact-method-icon svg {
            width: 1.5rem;
            height: 1.5rem;
            color: var(--primary);
        }

        .contact-method-content h3 {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .contact-method-content p {
            font-size: 0.875rem;
            color: var(--muted-foreground);
            line-height: 1.5;
        }

        .contact-method-content a {
            color: var(--primary);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .contact-method-content a:hover {
            text-decoration: underline;
        }

        .office-hours {
            padding: 1.5rem;
            border-radius: var(--radius);
        }

        .office-hours h3 {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .hours-list {
            list-style: none;
        }

        .hours-list li {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .hours-list li:last-child {
            border-bottom: none;
        }

        .hours-list .day {
            font-weight: 500;
        }

        .hours-list .time {
            color: var(--muted-foreground);
        }

        /* FAQ Section */
        .faq-section {
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.02), var(--background), rgba(255, 255, 255, 0.02));
        }

        .faq-grid {
            display: grid;
            gap: 1.5rem;
            max-width: 64rem;
            margin: 0 auto;
        }

        @media (min-width: 768px) {
            .faq-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .faq-item {
            padding: 1.5rem;
            border-radius: var(--radius);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .faq-item:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.05);
        }

        .faq-question {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
        }

        .faq-question h3 {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .faq-icon {
            width: 1.5rem;
            height: 1.5rem;
            color: var(--primary);
            flex-shrink: 0;
            transition: transform 0.3s ease;
        }

        .faq-item.active .faq-icon {
            transform: rotate(45deg);
        }

        .faq-answer {
            font-size: 0.875rem;
            color: var(--muted-foreground);
            line-height: 1.5;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .faq-item.active .faq-answer {
            max-height: 300px;
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

        .team-card {
            padding: 2rem;
            border-radius: var(--radius);
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            text-align: center;
        }

        .team-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px hsla(189, 94%, 55%, 0.15);
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
            transition: all 0.5s ease;
        }

        .cta-card:hover .cta-icon {
            transform: rotateY(180deg) scale(1.1);
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

    <?php include 'header-landing.php'; ?>

    <!-- Contact Hero Section -->
    <section class="contact-hero">
        <div class="contact-hero-bg"></div>
        <div class="container">
            <div class="contact-hero-content" data-aos="fade-up" data-aos-duration="1000">
                <div class="badge">
                    <svg class="badge-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                    </svg>
                    <span>We're Here to Help</span>
                </div>
                <h1 class="contact-hero-title">
                    Get in <span class="gradient-text">Touch</span>
                </h1>
                <p class="contact-hero-description">
                    Have questions about TruthGuard AI? Need help with deepfake detection? 
                    Our team is ready to assist you in protecting truth in the digital age.
                </p>
                <div class="cta-buttons">
                    <a href="#contact-form" class="btn btn-primary btn-lg">
                        Send a Message
                        <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    <a href="pricing.php" class="btn btn-outline btn-lg">View Pricing</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section id="contact-form" class="section contact-form-section">
        <div class="container">
            <div class="contact-container">
                <div class="contact-form-content" data-aos="fade-right" data-aos-delay="100">
                    <div class="contact-form-card glass-strong">
                        <div class="contact-form-header">
                            <h2 class="contact-form-title">Send Us a Message</h2>
                            <p class="contact-form-subtitle">We typically respond within 24 hours</p>
                        </div>

                        <form class="contact-form" id="contactForm">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" id="name" class="form-input" placeholder="John Doe" required>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" id="email" class="form-input" placeholder="name@company.com" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="subject" class="form-label">Subject</label>
                                <input type="text" id="subject" class="form-input" placeholder="How can we help you?" required>
                            </div>

                            <div class="form-group">
                                <label for="message" class="form-label">Message</label>
                                <textarea id="message" class="form-textarea" placeholder="Tell us about your inquiry..." required></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary form-submit">
                                Send Message
                                <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="22" y1="2" x2="11" y2="13"></line>
                                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="contact-info" data-aos="fade-left" data-aos-delay="200">
                    <div class="contact-methods">
                        <div class="contact-method glass-strong">
                            <div class="contact-method-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                            </div>
                            <div class="contact-method-content">
                                <h3>Email Us</h3>
                                <p><a href="mailto:hello@truthguard.ai">hello@truthguard.ai</a></p>
                                <p>For general inquiries and support</p>
                            </div>
                        </div>

                        <div class="contact-method glass-strong">
                            <div class="contact-method-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                </svg>
                            </div>
                            <div class="contact-method-content">
                                <h3>Call Us</h3>
                                <p><a href="tel:+1-555-123-4567">+1 (555) 123-4567</a></p>
                                <p>Mon-Fri from 9am to 6pm EST</p>
                            </div>
                        </div>

                        <div class="contact-method glass-strong">
                            <div class="contact-method-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                            </div>
                            <div class="contact-method-content">
                                <h3>Visit Us</h3>
                                <p>123 Innovation Drive</p>
                                <p>San Francisco, CA 94107</p>
                            </div>
                        </div>
                    </div>

                    <div class="office-hours glass-strong">
                        <h3>Office Hours</h3>
                        <ul class="hours-list">
                            <li>
                                <span class="day">Monday - Friday</span>
                                <span class="time">9:00 AM - 6:00 PM</span>
                            </li>
                            <li>
                                <span class="day">Saturday</span>
                                <span class="time">10:00 AM - 4:00 PM</span>
                            </li>
                            <li>
                                <span class="day">Sunday</span>
                                <span class="time">Closed</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="section faq-section">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <div class="badge">
                    <svg class="badge-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                    <span>Common Questions</span>
                </div>
                <h2 class="section-title">
                    Frequently Asked <span class="gradient-text">Questions</span>
                </h2>
                <p class="section-description">
                    Quick answers to common questions about TruthGuard AI
                </p>
            </div>

            <div class="faq-grid">
                <div class="faq-item glass-strong" data-aos="fade-up" data-aos-delay="100">
                    <div class="faq-question">
                        <h3>How accurate is your deepfake detection?</h3>
                        <svg class="faq-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </div>
                    <div class="faq-answer">
                        <p>Our AI models achieve 99.7% accuracy in detecting deepfakes across images, videos, and audio. We continuously train our models on new data to maintain this high level of accuracy as deepfake technology evolves.</p>
                    </div>
                </div>

                <div class="faq-item glass-strong" data-aos="fade-up" data-aos-delay="200">
                    <div class="faq-question">
                        <h3>How long does it take to get results?</h3>
                        <svg class="faq-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </div>
                    <div class="faq-answer">
                        <p>Most scans are completed in under 3 seconds for images and short videos. Longer videos may take a few minutes depending on length and complexity. Our Pro and Enterprise plans include priority processing for faster results.</p>
                    </div>
                </div>

                <div class="faq-item glass-strong" data-aos="fade-up" data-aos-delay="300">
                    <div class="faq-question">
                        <h3>Is my data secure and private?</h3>
                        <svg class="faq-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </div>
                    <div class="faq-answer">
                        <p>Yes, we take data privacy seriously. All uploaded content is encrypted in transit and at rest. We never store your original files after processing, and we comply with GDPR, CCPA, and other privacy regulations.</p>
                    </div>
                </div>

                <div class="faq-item glass-strong" data-aos="fade-up" data-aos-delay="400">
                    <div class="faq-question">
                        <h3>Do you offer custom solutions for businesses?</h3>
                        <svg class="faq-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </div>
                    <div class="faq-answer">
                        <p>Absolutely! Our Enterprise plan includes custom AI model training, white-label options, dedicated infrastructure, and API access. Contact our sales team to discuss your specific requirements.</p>
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
                    <span>Our Team</span>
                </div>
                <h2 class="section-title">
                    Meet Our <span class="gradient-text">Support Team</span>
                </h2>
                <p class="section-description">
                    Our dedicated support team is here to help you with any questions or issues
                </p>
            </div>

            <div class="team-grid">
                <div class="team-card glass-strong" data-aos="fade-up" data-aos-delay="100">
                    <div class="team-avatar">
                        <span>AK</span>
                    </div>
                    <h3 class="team-name">Alex Kim</h3>
                    <div class="team-role">Support Lead</div>
                    <p class="team-bio">
                        Alex leads our customer support team with 8+ years of experience in AI technology support. He ensures all customer inquiries are handled promptly and effectively.
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
                        <span>MJ</span>
                    </div>
                    <h3 class="team-name">Maria Johnson</h3>
                    <div class="team-role">Technical Support Specialist</div>
                    <p class="team-bio">
                        Maria specializes in troubleshooting technical issues and API integrations. With a background in computer science, she helps customers implement TruthGuard AI effectively.
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
                        <span>DR</span>
                    </div>
                    <h3 class="team-name">David Rodriguez</h3>
                    <div class="team-role">Customer Success Manager</div>
                    <p class="team-bio">
                        David ensures our Enterprise clients achieve their goals with TruthGuard AI. He provides personalized onboarding, training, and ongoing support for our largest customers.
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
                    Ready to <span class="gradient-text">Protect Truth</span>?
                </h2>

                <p class="cta-description">
                    Join thousands of professionals using TruthGuard AI to detect deepfakes and combat misinformation. 
                    Start with our free plan today or contact our sales team for a custom solution.
                </p>

                <div class="cta-buttons">
                    <a href="signup.php" class="btn btn-primary btn-lg">
                        Get Started Free
                        <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    <a href="pricing.php" class="btn btn-outline btn-lg">View Pricing</a>
                </div>
            </div>
        </div>
    </section>

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

        // FAQ toggle functionality
        document.querySelectorAll('.faq-item').forEach(item => {
            item.addEventListener('click', () => {
                item.classList.toggle('active');
            });
        });

        // Contact form submission
        const contactForm = document.getElementById('contactForm');
        if (contactForm) {
            contactForm.addEventListener('submit', (e) => {
                e.preventDefault();
                
                const name = document.getElementById('name').value;
                const email = document.getElementById('email').value;
                const subject = document.getElementById('subject').value;
                const message = document.getElementById('message').value;
                
                // Basic validation
                if (!name || !email || !subject || !message) {
                    alert('Please fill in all fields');
                    return;
                }
                
                // In a real application, you would send this data to your server
                console.log('Contact form submission:', { name, email, subject, message });
                
                // Simulate successful submission
                alert('Thank you for your message! We will get back to you within 24 hours.');
                contactForm.reset();
            });
        }

        // Add hover effect to contact methods
        document.querySelectorAll('.contact-method').forEach(method => {
            method.addEventListener('mouseenter', () => {
                method.style.transform = 'translateY(-5px)';
            });
            
            method.addEventListener('mouseleave', () => {
                method.style.transform = 'translateY(0)';
            });
        });
    </script>
</body>
</html>