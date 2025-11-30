<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricing - TruthGuard AI</title>
    <meta name="description" content="Choose the perfect TruthGuard AI plan for your needs. Free, Pro, and Enterprise plans with advanced deepfake detection features.">
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

        /* Pricing Hero Section */
        .pricing-hero {
            position: relative;
            min-height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding-top: 5rem;
            overflow: hidden;
        }

        .pricing-hero-bg {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom right, var(--background), var(--card));
        }

        .pricing-hero-bg::before,
        .pricing-hero-bg::after {
            content: '';
            position: absolute;
            width: 24rem;
            height: 24rem;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.3;
            animation: pulse-glow 3s ease-in-out infinite;
        }

        .pricing-hero-bg::before {
            top: 25%;
            left: 25%;
            background: var(--primary);
        }

        .pricing-hero-bg::after {
            bottom: 25%;
            right: 25%;
            background: var(--glow-blue);
            animation-delay: 1s;
        }

        .pricing-hero-content {
            text-align: center;
            max-width: 64rem;
            margin: 0 auto;
            position: relative;
            z-index: 10;
        }

        .pricing-hero-title {
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.5rem;
        }

        @media (min-width: 768px) {
            .pricing-hero-title {
                font-size: 3rem;
            }
        }

        @media (min-width: 1024px) {
            .pricing-hero-title {
                font-size: 3.75rem;
            }
        }

        .pricing-hero-description {
            font-size: 1.25rem;
            color: var(--muted-foreground);
            max-width: 48rem;
            margin: 0 auto 2rem;
        }

        /* Pricing Plans Section */
        .pricing-plans-section {
            background: linear-gradient(to bottom, var(--background), rgba(255, 255, 255, 0.02), var(--background));
        }

        .pricing-grid {
            display: grid;
            gap: 2rem;
            max-width: 80rem;
            margin: 0 auto 4rem;
        }

        @media (min-width: 768px) {
            .pricing-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .pricing-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .pricing-card {
            padding: 2rem;
            border-radius: var(--radius);
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }

        .pricing-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, var(--glow-cyan), var(--glow-blue));
            transform: scaleX(0);
            transition: transform 0.5s ease;
        }

        .pricing-card:hover::before {
            transform: scaleX(1);
        }

        .pricing-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 25px 50px hsla(189, 94%, 55%, 0.15);
        }

        .pricing-card.popular {
            border: 2px solid var(--primary);
            transform: scale(1.05);
        }

        .pricing-card.popular:hover {
            transform: scale(1.08) translateY(-10px);
        }

        .popular-badge {
            position: absolute;
            top: -1rem;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, var(--glow-cyan), var(--glow-blue));
            color: white;
            font-size: 0.875rem;
            font-weight: 700;
            padding: 0.5rem 1.5rem;
            border-radius: 9999px;
            box-shadow: 0 4px 16px hsla(189, 94%, 55%, 0.5);
        }

        .pricing-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .pricing-icon {
            width: 3rem;
            height: 3rem;
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .pricing-card:hover .pricing-icon {
            transform: rotateY(180deg);
        }

        .pricing-card.popular .pricing-icon {
            background: linear-gradient(135deg, var(--glow-cyan), var(--glow-blue));
        }

        .pricing-card:not(.popular) .pricing-icon {
            background: var(--muted);
        }

        .pricing-icon svg {
            width: 1.5rem;
            height: 1.5rem;
            transition: all 0.3s ease;
        }

        .pricing-card.popular .pricing-icon svg {
            color: white;
        }

        .pricing-card:not(.popular) .pricing-icon svg {
            color: var(--primary);
        }

        .pricing-name {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .pricing-price-wrapper {
            margin-bottom: 1rem;
        }

        .pricing-price {
            font-size: 2.5rem;
            font-weight: 700;
        }

        .pricing-period {
            color: var(--muted-foreground);
            margin-left: 0.5rem;
        }

        .pricing-description {
            font-size: 0.875rem;
            color: var(--muted-foreground);
            margin-bottom: 1.5rem;
        }

        .pricing-cta {
            width: 100%;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .pricing-card:hover .pricing-cta {
            transform: scale(1.05);
        }

        .pricing-features {
            list-style: none;
        }

        .pricing-features li {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
            font-size: 0.875rem;
            color: var(--muted-foreground);
            transition: all 0.3s ease;
        }

        .pricing-card:hover .pricing-features li {
            transform: translateX(5px);
        }

        .pricing-features li:nth-child(odd) {
            transition-delay: 0.05s;
        }

        .pricing-features li:nth-child(even) {
            transition-delay: 0.1s;
        }

        .pricing-features svg {
            width: 1.25rem;
            height: 1.25rem;
            color: var(--primary);
            flex-shrink: 0;
            margin-top: 0.125rem;
            transition: all 0.3s ease;
        }

        .pricing-card:hover .pricing-features svg {
            transform: scale(1.2);
        }

        /* Feature Comparison Section */
        .comparison-section {
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.02), var(--background), rgba(255, 255, 255, 0.02));
        }

        .comparison-table {
            width: 100%;
            border-collapse: collapse;
            border-radius: var(--radius);
            overflow: hidden;
        }

        .comparison-table th,
        .comparison-table td {
            padding: 1.25rem 1.5rem;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .comparison-table th {
            background: rgba(255, 255, 255, 0.05);
            font-weight: 600;
            font-size: 0.875rem;
        }

        .comparison-table tr:last-child td {
            border-bottom: none;
        }

        .comparison-table tr:hover {
            background: rgba(255, 255, 255, 0.03);
        }

        .feature-name {
            font-weight: 500;
        }

        .plan-check {
            text-align: center;
        }

        .check-icon {
            color: var(--primary);
            width: 1.25rem;
            height: 1.25rem;
            margin: 0 auto;
        }

        .check-icon.cross {
            color: var(--muted-foreground);
        }

        /* FAQ Section */
        .faq-section {
            background: linear-gradient(to bottom, var(--background), rgba(255, 255, 255, 0.02), var(--background));
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

        /* Testimonials Section */
        .testimonials-section {
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.02), var(--background), rgba(255, 255, 255, 0.02));
        }

        .testimonials-grid {
            display: grid;
            gap: 1.5rem;
            margin-bottom: 4rem;
        }

        @media (min-width: 768px) {
            .testimonials-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .testimonials-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .testimonial-card {
            padding: 1.5rem;
            border-radius: var(--radius);
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
        }

        .testimonial-card:hover {
            transform: translateY(-10px) rotate(1deg);
            box-shadow: 0 20px 40px hsla(189, 94%, 55%, 0.15);
        }

        .quote-icon {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            width: 2rem;
            height: 2rem;
            color: hsla(189, 94%, 55%, 0.2);
            transition: all 0.3s ease;
        }

        .testimonial-card:hover .quote-icon {
            transform: scale(1.2);
            color: hsla(189, 94%, 55%, 0.4);
        }

        .testimonial-rating {
            display: flex;
            gap: 0.25rem;
            margin-bottom: 1rem;
        }

        .testimonial-rating svg {
            width: 1rem;
            height: 1rem;
            color: var(--primary);
            fill: var(--primary);
            transition: all 0.3s ease;
        }

        .testimonial-card:hover .testimonial-rating svg {
            transform: scale(1.2);
        }

        .testimonial-content {
            font-size: 0.875rem;
            color: var(--muted-foreground);
            line-height: 1.5;
            margin-bottom: 1.5rem;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .testimonial-avatar {
            width: 3rem;
            height: 3rem;
            background: linear-gradient(135deg, var(--glow-cyan), var(--glow-blue));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
            transition: all 0.3s ease;
        }

        .testimonial-card:hover .testimonial-avatar {
            transform: scale(1.1);
        }

        .testimonial-name {
            font-weight: 600;
            font-size: 0.875rem;
        }

        .testimonial-role {
            font-size: 0.75rem;
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

    <?php 
    // Include database connection
    include 'database.php';

    // Fetch pricing plans from database
    try {
        $sql = "SELECT * FROM pricing_plans WHERE is_active = 1 ORDER BY price ASC";
        $stmt = $pdo->query($sql);
        $plans = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        die("Error fetching pricing plans: " . $e->getMessage());
    }
    ?>

    <?php include 'header-landing.php'; ?>

    <!-- Pricing Hero Section -->
    <section class="pricing-hero">
        <div class="pricing-hero-bg"></div>
        <div class="container">
            <div class="pricing-hero-content" data-aos="fade-up" data-aos-duration="1000">
                <div class="badge">
                    <svg class="badge-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                    </svg>
                    <span>Simple, Transparent Pricing</span>
                </div>
                <h1 class="pricing-hero-title">
                    Choose Your <span class="gradient-text">Protection Plan</span>
                </h1>
                <p class="pricing-hero-description">
                    Start with our affordable plans and upgrade as you grow. All plans include our core AI detection features with varying levels of capacity and advanced capabilities.
                </p>
                <div class="cta-buttons">
                    <a href="#pricing-plans" class="btn btn-primary btn-lg">
                        View Plans
                        <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    <a href="contact.php" class="btn btn-outline btn-lg">Contact Sales</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Plans Section -->
    <section id="pricing-plans" class="section pricing-plans-section">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <h2 class="section-title">
                    Simple, <span class="gradient-text">Flexible Pricing</span>
                </h2>
                <p class="section-description">
                    Choose the plan that works best for you. All plans include our core AI detection technology.
                </p>
            </div>

            <div class="pricing-grid">
                <?php 
                $delay = 100;
                foreach ($plans as $plan): 
                    $popularClass = $plan['is_popular'] ? 'popular' : '';
                    $features = json_decode($plan['features'], true);
                ?>
                <div class="pricing-card glass-strong <?php echo $popularClass; ?>" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                    <?php if ($plan['is_popular']): ?>
                    <div class="popular-badge">Most Popular</div>
                    <?php endif; ?>
                    
                    <div class="pricing-header">
                        <div class="pricing-icon <?php echo $plan['is_popular'] ? 'popular' : ''; ?>">
                            <?php if ($plan['id'] == 1): ?>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                            </svg>
                            <?php elseif ($plan['id'] == 2): ?>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                            </svg>
                            <?php else: ?>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                            </svg>
                            <?php endif; ?>
                        </div>
                        <h3 class="pricing-name"><?php echo htmlspecialchars($plan['name']); ?></h3>
                    </div>

                    <div class="pricing-price-wrapper">
                        <span class="pricing-price">₹<?php echo htmlspecialchars($plan['price']); ?></span>
                        <span class="pricing-period">/<?php echo htmlspecialchars($plan['period']); ?></span>
                    </div>

                    <p class="pricing-description"><?php echo htmlspecialchars($plan['description']); ?></p>

                    <a href="signup.php" class="btn <?php echo $plan['is_popular'] ? 'btn-primary' : 'btn-outline'; ?> pricing-cta">
                        <?php echo $plan['is_popular'] ? 'Start Pro Trial' : 'Get Started'; ?>
                    </a>

                    <ul class="pricing-features">
                        <?php foreach ($features as $feature): ?>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            <span><?php echo htmlspecialchars($feature); ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php 
                $delay += 100;
                endforeach; 
                ?>
            </div>

            <div class="pricing-cta" data-aos="fade-up" data-aos-delay="400">
                <p>All plans include a 14-day free trial of Pro features. No credit card required.</p>
            </div>
        </div>
    </section>

    <!-- Feature Comparison Section -->
    <section class="section comparison-section">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <div class="badge">
                    <svg class="badge-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="20" x2="18" y2="10"></line>
                        <line x1="12" y1="20" x2="12" y2="4"></line>
                        <line x1="6" y1="20" x2="6" y2="14"></line>
                    </svg>
                    <span>Compare Features</span>
                </div>
                <h2 class="section-title">
                    Plan <span class="gradient-text">Comparison</span>
                </h2>
                <p class="section-description">
                    Detailed breakdown of what's included in each plan
                </p>
            </div>

            <div class="comparison-table glass-strong" data-aos="fade-up" data-aos-delay="200">
                <table>
                    <thead>
                        <tr>
                            <th class="feature-name">Feature</th>
                            <?php foreach ($plans as $plan): ?>
                            <th class="plan-check"><?php echo htmlspecialchars($plan['name']); ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="feature-name">Daily Scans</td>
                            <?php foreach ($plans as $plan): ?>
                            <td class="plan-check">
                                <?php 
                                $features = json_decode($plan['features'], true);
                                $scanFeature = array_filter($features, function($feature) {
                                    return stripos($feature, 'scan') !== false;
                                });
                                echo !empty($scanFeature) ? reset($scanFeature) : 'Limited';
                                ?>
                            </td>
                            <?php endforeach; ?>
                        </tr>
                        <tr>
                            <td class="feature-name">AI Detection</td>
                            <?php foreach ($plans as $plan): ?>
                            <td class="plan-check">
                                <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                            </td>
                            <?php endforeach; ?>
                        </tr>
                        <tr>
                            <td class="feature-name">Community Access</td>
                            <?php foreach ($plans as $plan): ?>
                            <td class="plan-check">
                                <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                            </td>
                            <?php endforeach; ?>
                        </tr>
                        <tr>
                            <td class="feature-name">Priority Processing</td>
                            <?php foreach ($plans as $plan): ?>
                            <td class="plan-check">
                                <?php if ($plan['id'] == 1): ?>
                                <svg class="check-icon cross" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                                <?php else: ?>
                                <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <?php endif; ?>
                            </td>
                            <?php endforeach; ?>
                        </tr>
                        <tr>
                            <td class="feature-name">Export Reports</td>
                            <?php foreach ($plans as $plan): ?>
                            <td class="plan-check">
                                <?php if ($plan['id'] == 1): ?>
                                <svg class="check-icon cross" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                                <?php else: ?>
                                <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <?php endif; ?>
                            </td>
                            <?php endforeach; ?>
                        </tr>
                        <tr>
                            <td class="feature-name">Multiple Devices</td>
                            <?php foreach ($plans as $plan): ?>
                            <td class="plan-check">
                                <?php 
                                $features = json_decode($plan['features'], true);
                                $deviceFeature = array_filter($features, function($feature) {
                                    return stripos($feature, 'device') !== false;
                                });
                                if (!empty($deviceFeature)) {
                                    echo reset($deviceFeature);
                                } else {
                                    echo $plan['id'] == 3 ? '4 devices' : ($plan['id'] == 2 ? '2 devices' : '1 device');
                                }
                                ?>
                            </td>
                            <?php endforeach; ?>
                        </tr>
                    </tbody>
                </table>
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
                    Everything you need to know about our pricing and plans
                </p>
            </div>

            <div class="faq-grid">
                <div class="faq-item glass-strong" data-aos="fade-up" data-aos-delay="100">
                    <div class="faq-question">
                        <h3>Can I change plans later?</h3>
                        <svg class="faq-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </div>
                    <div class="faq-answer">
                        <p>Yes, you can upgrade or downgrade your plan at any time. When upgrading, you'll get immediate access to the new features. When downgrading, the changes will take effect at the end of your current billing cycle.</p>
                    </div>
                </div>

                <div class="faq-item glass-strong" data-aos="fade-up" data-aos-delay="200">
                    <div class="faq-question">
                        <h3>Do you offer discounts for nonprofits?</h3>
                        <svg class="faq-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </div>
                    <div class="faq-answer">
                        <p>Yes, we offer a 50% discount on all paid plans for registered nonprofit organizations. Contact our sales team with proof of your nonprofit status to get started.</p>
                    </div>
                </div>

                <div class="faq-item glass-strong" data-aos="fade-up" data-aos-delay="300">
                    <div class="faq-question">
                        <h3>What payment methods do you accept?</h3>
                        <svg class="faq-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </div>
                    <div class="faq-answer">
                        <p>We accept all major credit cards (Visa, MasterCard, American Express), PayPal, UPI, and net banking. For Enterprise plans we also accept bank transfers and purchase orders.</p>
                    </div>
                </div>

                <div class="faq-item glass-strong" data-aos="fade-up" data-aos-delay="400">
                    <div class="faq-question">
                        <h3>Is there a free trial for paid plans?</h3>
                        <svg class="faq-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </div>
                    <div class="faq-answer">
                        <p>Yes, we offer a 14-day free trial for our Pro plan. No credit card is required to start the trial. You'll have full access to all Pro features during the trial period.</p>
                    </div>
                </div>

                <div class="faq-item glass-strong" data-aos="fade-up" data-aos-delay="500">
                    <div class="faq-question">
                        <h3>How secure is my data?</h3>
                        <svg class="faq-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </div>
                    <div class="faq-answer">
                        <p>We take data security very seriously. All data is encrypted in transit and at rest. We never store your original files after processing, and we comply with GDPR, CCPA, and other privacy regulations.</p>
                    </div>
                </div>

                <div class="faq-item glass-strong" data-aos="fade-up" data-aos-delay="600">
                    <div class="faq-question">
                        <h3>Can I cancel anytime?</h3>
                        <svg class="faq-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </div>
                    <div class="faq-answer">
                        <p>Absolutely. You can cancel your subscription at any time. If you cancel, you'll retain access to your paid features until the end of your current billing period.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="section testimonials-section">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <div class="badge">
                    <svg class="badge-icon" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                    <span>Trusted by Professionals</span>
                </div>
                <h2 class="section-title">
                    What Our <span class="gradient-text">Customers Say</span>
                </h2>
                <p class="section-description">
                    Join thousands of professionals protecting truth worldwide
                </p>
            </div>

            <div class="testimonials-grid">
                <div class="testimonial-card glass-strong" data-aos="fade-up" data-aos-delay="100">
                    <svg class="quote-icon" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z"></path>
                        <path d="M15 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2h.75c0 2.25.25 4-2.75 4v3c0 1 0 1 1 1z"></path>
                    </svg>
                    
                    <div class="testimonial-rating">
                        <svg viewBox="0 0 24 24">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                        <svg viewBox="0 0 24 24">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                        <svg viewBox="0 0 24 24">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                        <svg viewBox="0 0 24 24">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                        <svg viewBox="0 0 24 24">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                    </div>

                    <p class="testimonial-content">"The Pro plan has been a game-changer for our newsroom. The unlimited scans and priority processing help us verify breaking news stories in minutes, not hours."</p>

                    <div class="testimonial-author">
                        <div class="testimonial-avatar">SJ</div>
                        <div>
                            <div class="testimonial-name">Sarah Johnson</div>
                            <div class="testimonial-role">Journalist, Global News Network</div>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card glass-strong" data-aos="fade-up" data-aos-delay="200">
                    <svg class="quote-icon" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z"></path>
                        <path d="M15 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2h.75c0 2.25.25 4-2.75 4v3c0 1 0 1 1 1z"></path>
                    </svg>
                    
                    <div class="testimonial-rating">
                        <svg viewBox="0 0 24 24">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                        <svg viewBox="0 0 24 24">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                        <svg viewBox="0 0 24 24">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                        <svg viewBox="0 0 24 24">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                        <svg viewBox="0 0 24 24">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                    </div>

                    <p class="testimonial-content">"As a content creator with 500k followers, the Pro plan has saved me from sharing misinformation multiple times. The bulk upload feature is a lifesaver!"</p>

                    <div class="testimonial-author">
                        <div class="testimonial-avatar">JW</div>
                        <div>
                            <div class="testimonial-name">James Wilson</div>
                            <div class="testimonial-role">Content Creator</div>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card glass-strong" data-aos="fade-up" data-aos-delay="300">
                    <svg class="quote-icon" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z"></path>
                        <path d="M15 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2h.75c0 2.25.25 4-2.75 4v3c0 1 0 1 1 1z"></path>
                    </svg>
                    
                    <div class="testimonial-rating">
                        <svg viewBox="0 0 24 24">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                        <svg viewBox="0 0 24 24">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                        <svg viewBox="0 0 24 24">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                        <svg viewBox="0 0 24 24">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                        <svg viewBox="0 0 24 24">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                    </div>

                    <p class="testimonial-content">"The Matrix Special plan's unlimited posts and multiple device access were exactly what our organization needed. The ROI has been incredible."</p>

                    <div class="testimonial-author">
                        <div class="testimonial-avatar">AT</div>
                        <div>
                            <div class="testimonial-name">Alex Thompson</div>
                            <div class="testimonial-role">Enterprise Security Lead</div>
                        </div>
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
                    Ready to Protect <span class="gradient-text">Your Digital World</span>?
                </h2>

                <p class="cta-description">
                    Join over 50,000 professionals who trust TruthGuard AI to detect deepfakes and fake news. 
                    Start with our affordable Beginners plan, no credit card required.
                </p>

                <div class="cta-buttons">
                    <a href="signup.php" class="btn btn-primary btn-lg">
                        Get Started
                        <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    <a href="contact.php" class="btn btn-outline btn-lg">Contact Sales</a>
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

        // Add hover effect to pricing cards
        document.querySelectorAll('.pricing-card').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = card.classList.contains('popular') 
                    ? 'scale(1.08) translateY(-10px)' 
                    : 'translateY(-15px)';
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = card.classList.contains('popular') 
                    ? 'scale(1.05)' 
                    : 'translateY(0)';
            });
        });
    </script>
</body>
</html>