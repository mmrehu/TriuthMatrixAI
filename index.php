<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TruthGuard AI - Deepfake & Fake News Detection</title>
    <meta name="description" content="Protect yourself from AI-generated misinformation. Advanced AI analyzes images, videos, and news articles with 99.7% accuracy.">
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

@keyframes scrollBounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(10px); }
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

/* Hero Section */
.hero {
    position: relative;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding-top: 5rem;
    overflow: hidden;
}

.hero-bg {
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom right, var(--background), var(--card));
}

.hero-bg::before,
.hero-bg::after {
    content: '';
    position: absolute;
    width: 24rem;
    height: 24rem;
    border-radius: 50%;
    filter: blur(120px);
    opacity: 0.3;
    animation: pulse-glow 3s ease-in-out infinite;
}

.hero-bg::before {
    top: 25%;
    left: 25%;
    background: var(--primary);
}

.hero-bg::after {
    bottom: 25%;
    right: 25%;
    background: var(--glow-blue);
    animation-delay: 1s;
}

.hero-grid {
    display: grid;
    gap: 3rem;
    align-items: center;
    position: relative;
    z-index: 10;
}

@media (min-width: 1024px) {
    .hero-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 4rem;
    }
}

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

.hero-title {
    font-size: 2.5rem;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 1.5rem;
}

@media (min-width: 768px) {
    .hero-title {
        font-size: 3rem;
    }
}

@media (min-width: 1024px) {
    .hero-title {
        font-size: 3.75rem;
    }
}

.hero-description {
    font-size: 1.125rem;
    color: var(--muted-foreground);
    margin-bottom: 2rem;
    max-width: 40rem;
}

.hero-stats {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    padding: 0.75rem 1.5rem;
    border-radius: var(--radius);
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary);
}

.stat-label {
    font-size: 0.875rem;
    color: var(--muted-foreground);
}

.hero-cta {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 2rem;
}

@media (min-width: 640px) {
    .hero-cta {
        flex-direction: row;
    }
}

.trust-badges {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 1rem;
    font-size: 0.875rem;
    color: var(--muted-foreground);
}

.trust-icon {
    width: 1.25rem;
    height: 1.25rem;
    color: var(--primary);
}

.hero-image {
    position: relative;
}

.hero-image img {
    width: 100%;
    height: auto;
    border-radius: var(--radius);
}

.image-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, var(--background) 0%, transparent 50%);
    border-radius: var(--radius);
}

.floating-card {
    position: absolute;
    padding: 0.75rem 1rem;
    border-radius: var(--radius);
}

.floating-card.bottom-left {
    bottom: 1.5rem;
    left: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.floating-card.top-right {
    top: 1.5rem;
    right: 1.5rem;
}

.status-indicator {
    width: 0.75rem;
    height: 0.75rem;
    background: #10b981;
    border-radius: 50%;
    animation: pulse 2s ease-in-out infinite;
}

.floating-card-title {
    font-size: 0.875rem;
    font-weight: 600;
}

.floating-card-subtitle {
    font-size: 0.75rem;
    color: var(--muted-foreground);
}

.floating-card-label {
    font-size: 0.75rem;
    color: var(--muted-foreground);
    margin-bottom: 0.25rem;
}

.floating-card-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary);
}

.scroll-indicator {
    position: absolute;
    bottom: 2rem;
    left: 50%;
    transform: translateX(-50%);
}

.scroll-mouse {
    width: 1.5rem;
    height: 2.5rem;
    border: 2px solid hsla(189, 94%, 55%, 0.5);
    border-radius: 9999px;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    padding: 0.5rem;
    animation: scrollBounce 1.5s ease-in-out infinite;
}

.scroll-mouse::before {
    content: '';
    width: 0.25rem;
    height: 0.5rem;
    background: var(--primary);
    border-radius: 9999px;
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

/* Features Section */
.features-section {
    background: linear-gradient(to bottom, var(--background), rgba(255, 255, 255, 0.02), var(--background));
}

.features-grid {
    display: grid;
    gap: 1.5rem;
    margin-bottom: 4rem;
}

@media (min-width: 768px) {
    .features-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 1024px) {
    .features-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (min-width: 1280px) {
    .features-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}

.feature-card {
    padding: 1.5rem;
    border-radius: var(--radius);
    transition: all 0.3s ease;
    position: relative;
}

.feature-card:hover {
    transform: scale(1.05);
    box-shadow: 0 20px 40px hsla(189, 94%, 55%, 0.2);
}

.feature-icon-wrapper {
    position: relative;
    margin-bottom: 1rem;
}

.feature-icon-bg {
    width: 3.5rem;
    height: 3.5rem;
    border-radius: var(--radius);
    padding: 2px;
}

.feature-icon-inner {
    width: 100%;
    height: 100%;
    background: var(--background);
    border-radius: calc(var(--radius) - 2px);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.3s ease;
}

.feature-card:hover .feature-icon-inner {
    background: transparent;
}

.feature-icon {
    width: 1.75rem;
    height: 1.75rem;
    color: var(--foreground);
    transition: color 0.3s ease;
}

.feature-card:hover .feature-icon {
    color: white;
}

.feature-badge {
    position: absolute;
    top: -0.5rem;
    right: -0.5rem;
    background: linear-gradient(135deg, #fbbf24, #f59e0b);
    color: black;
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.25rem 0.5rem;
    border-radius: 9999px;
}

.feature-title {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    transition: color 0.3s ease;
}

.feature-card:hover .feature-title {
    color: var(--primary);
}

.feature-description {
    font-size: 0.875rem;
    color: var(--muted-foreground);
    line-height: 1.5;
}

.features-cta {
    text-align: center;
}

.features-cta p {
    color: var(--muted-foreground);
    margin-bottom: 1.5rem;
}

.cta-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 9999px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.cta-link:hover {
    background: rgba(255, 255, 255, 0.15);
}

.cta-link svg {
    width: 1rem;
    height: 1rem;
    color: var(--primary);
}

/* How It Works Section */
.how-it-works-section::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom, var(--background), rgba(255, 255, 255, 0.03), var(--background));
    pointer-events: none;
}

.how-it-works-section::after {
    content: '';
    position: absolute;
    top: 25%;
    left: 0;
    width: 24rem;
    height: 24rem;
    background: var(--primary);
    opacity: 0.1;
    border-radius: 50%;
    filter: blur(120px);
}

.steps-grid {
    display: grid;
    gap: 2rem;
    margin-bottom: 4rem;
    position: relative;
    z-index: 10;
}

@media (min-width: 768px) {
    .steps-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 1024px) {
    .steps-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}

.step-card {
    padding: 2rem;
    border-radius: var(--radius);
    transition: all 0.3s ease;
    position: relative;
}

.step-card:hover {
    transform: scale(1.05);
}

.step-number {
    position: absolute;
    top: -1rem;
    right: -1rem;
    width: 3rem;
    height: 3rem;
    background: linear-gradient(135deg, var(--glow-cyan), var(--glow-blue));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.125rem;
    box-shadow: 0 4px 16px hsla(189, 94%, 55%, 0.5);
}

.step-icon-wrapper {
    margin-bottom: 1.5rem;
}

.step-icon-bg {
    width: 4rem;
    height: 4rem;
    background: linear-gradient(135deg, hsla(189, 94%, 55%, 0.2), hsla(217, 91%, 60%, 0.2));
    border-radius: var(--radius);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s ease;
}

.step-card:hover .step-icon-bg {
    transform: scale(1.1);
}

.step-icon {
    width: 2rem;
    height: 2rem;
    color: var(--primary);
}

.step-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
    transition: color 0.3s ease;
}

.step-card:hover .step-title {
    color: var(--primary);
}

.step-description {
    font-size: 0.875rem;
    color: var(--muted-foreground);
    line-height: 1.5;
}

.stats-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

@media (min-width: 768px) {
    .stats-row {
        grid-template-columns: repeat(4, 1fr);
    }
}

.stat-box {
    text-align: center;
    padding: 1.5rem;
    border-radius: var(--radius);
}

.stat-number {
    font-size: 1.875rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

@media (min-width: 768px) {
    .stat-number {
        font-size: 2.25rem;
    }
}

.stat-text {
    font-size: 0.875rem;
    color: var(--muted-foreground);
}

/* Pricing Section */
.pricing-section {
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
    transition: all 0.3s ease;
    position: relative;
}

.pricing-card.popular {
    border: 2px solid var(--primary);
    box-shadow: 0 20px 60px hsla(189, 94%, 55%, 0.2);
    transform: scale(1.05);
}

.pricing-card:hover {
    transform: scale(1.05);
}

.pricing-card.popular:hover {
    transform: scale(1.08);
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
}

.pricing-features svg {
    width: 1.25rem;
    height: 1.25rem;
    color: var(--primary);
    flex-shrink: 0;
    margin-top: 0.125rem;
}

.pricing-cta {
    text-align: center;
}

.pricing-cta p {
    color: var(--muted-foreground);
    margin-bottom: 1rem;
}

.text-link {
    color: var(--primary);
    text-decoration: none;
    font-weight: 500;
}

.text-link:hover {
    text-decoration: underline;
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
    transition: all 0.3s ease;
    position: relative;
}

.testimonial-card:hover {
    transform: scale(1.05);
}

.quote-icon {
    position: absolute;
    top: 1.5rem;
    right: 1.5rem;
    width: 2rem;
    height: 2rem;
    color: hsla(189, 94%, 55%, 0.2);
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
}

.testimonial-name {
    font-weight: 600;
    font-size: 0.875rem;
}

.testimonial-role {
    font-size: 0.75rem;
    color: var(--muted-foreground);
}

.testimonial-stats {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 3rem;
}

.testimonial-stat {
    text-align: center;
}

.testimonial-stat-value {
    font-size: 1.875rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.testimonial-stat-label {
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

.cta-features {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
}

@media (min-width: 768px) {
    .cta-features {
        grid-template-columns: repeat(3, 1fr);
    }
}

.cta-feature {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: var(--muted-foreground);
}

.cta-feature svg {
    width: 1rem;
    height: 1rem;
    color: var(--primary);
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

/* Utility Classes */
.w-full {
    width: 100%;
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

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if (href !== '#' && href !== '#demo') {
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }
    });
});

// Features Data
const features = [
    {
        icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
            <circle cx="8.5" cy="8.5" r="1.5"></circle>
            <polyline points="21 15 16 10 5 21"></polyline>
        </svg>`,
        title: "Image fake news Detection",
        description: "Advanced AI algorithms detect manipulated photos and AI-generated images with pixel-level analysis.",
        gradient: "linear-gradient(135deg, #06b6d4, #3b82f6)"
    },
    {
        icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polygon points="23 7 16 12 23 17 23 7"></polygon>
            <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
        </svg>`,
        title: "Video Verification",
        description: "Frame-by-frame analysis identifies deepfake videos, face swaps, and synthetic media in real-time.",
        gradient: "linear-gradient(135deg, #3b82f6, #8b5cf6)"
    },
    {
        icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
            <polyline points="14 2 14 8 20 8"></polyline>
            <line x1="16" y1="13" x2="8" y2="13"></line>
            <line x1="16" y1="17" x2="8" y2="17"></line>
            <polyline points="10 9 9 9 8 9"></polyline>
        </svg>`,
        title: "Fake News Scanner",
        description: "NLP-powered fact-checking cross-references news articles against verified sources instantly.",
        gradient: "linear-gradient(135deg, #8b5cf6, #ec4899)"
    },
    {
        icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
        </svg>`,
        title: "URL Analysis",
        description: "Scan suspicious links and web content for misinformation, phishing, and malicious content.",
        gradient: "linear-gradient(135deg, #ec4899, #ef4444)"
    },
    {
        icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
            <polyline points="17 8 12 3 7 8"></polyline>
            <line x1="12" y1="3" x2="12" y2="15"></line>
        </svg>`,
        title: "Bulk Upload",
        description: "Process hundreds of files simultaneously with our enterprise-grade batch processing system.",
        gradient: "linear-gradient(135deg, #ef4444, #f97316)",
        badge: "Pro"
    },
    {
        icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="18" y1="20" x2="18" y2="10"></line>
            <line x1="12" y1="20" x2="12" y2="4"></line>
            <line x1="6" y1="20" x2="6" y2="14"></line>
        </svg>`,
        title: "Detailed Analytics",
        description: "Comprehensive reports with confidence scores, evidence breakdown, and source verification.",
        gradient: "linear-gradient(135deg, #f97316, #eab308)"
    },
    {
        icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
            <circle cx="9" cy="7" r="4"></circle>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
        </svg>`,
        title: "Community Voting",
        description: "Crowdsourced verification system where users can vote and discuss detected content.",
        gradient: "linear-gradient(135deg, #eab308, #84cc16)"
    },
    {
        icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
            <polyline points="17 6 23 6 23 12"></polyline>
        </svg>`,
        title: "Trending Threats",
        description: "Stay updated with real-time alerts on viral deepfakes and misinformation campaigns.",
        gradient: "linear-gradient(135deg, #84cc16, #06b6d4)"
    },
    {
        icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
        </svg>`,
        title: "Privacy Protected",
        description: "End-to-end encryption ensures your scanned content remains completely confidential.",
        gradient: "linear-gradient(135deg, #06b6d4, #3b82f6)"
    },
    {
        icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
        </svg>`,
        title: "Lightning Fast",
        description: "Get results in under 3 seconds with our optimized AI infrastructure and edge computing.",
        gradient: "linear-gradient(135deg, #3b82f6, #8b5cf6)"
    },
    {
        icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
            <polyline points="22 4 12 14.01 9 11.01"></polyline>
        </svg>`,
        title: "99.7% Accuracy",
        description: "Industry-leading precision backed by continuous model training on millions of samples.",
        gradient: "linear-gradient(135deg, #8b5cf6, #ec4899)"
    },
    {
        icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
        </svg>`,
        title: "API Integration",
        description: "Seamlessly integrate our detection system into your platform with our developer-friendly API.",
        gradient: "linear-gradient(135deg, #ec4899, #06b6d4)"
    }
];

// Steps Data
const steps = [
    {
        icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
            <polyline points="17 8 12 3 7 8"></polyline>
            <line x1="12" y1="3" x2="12" y2="15"></line>
        </svg>`,
        title: "Upload Content",
        description: "Upload images, videos, paste article links, or enter text. Support for bulk uploads and multiple formats.",
        step: "01"
    },
    {
        icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"></circle>
            <path d="M12 6v6l4 2"></path>
        </svg>`,
        title: "AI Analysis",
        description: "Our advanced AI models analyze your content using neural networks trained on millions of verified samples.",
        step: "02"
    },
    {
        icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
            <polyline points="22 4 12 14.01 9 11.01"></polyline>
        </svg>`,
        title: "Get Results",
        description: "Receive instant verdict with detailed confidence scores, evidence breakdown, and source verification.",
        step: "03"
    },
    {
        icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="18" cy="5" r="3"></circle>
            <circle cx="6" cy="12" r="3"></circle>
            <circle cx="18" cy="19" r="3"></circle>
            <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
            <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
        </svg>`,
        title: "Share & Report",
        description: "Generate shareable reports, contribute to community, and help spread awareness about detected misinformation.",
        step: "04"
    }
];

// Pricing Data
const pricingPlans = [
    {
        name: "Free",
        icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
        </svg>`,
        price: "$0",
        period: "forever",
        description: "Perfect for trying out TruthGuard AI",
        features: [
            "10 scans per day",
            "Image & Video detection",
            "Basic news verification",
            "Community access",
            "Standard support",
            "Public scan results"
        ],
        cta: "Start Free",
        ctaLink: "signup.html",
        popular: false
    },
    {
        name: "Pro",
        icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
        </svg>`,
        price: "$29",
        period: "per month",
        description: "For professionals & content creators",
        features: [
            "Unlimited scans",
            "All detection types",
            "Bulk upload (100 files)",
            "Priority AI processing",
            "Detailed analytics",
            "Private scan results",
            "API access (10k calls/mo)",
            "Priority support",
            "Export reports",
            "Advanced filters"
        ],
        cta: "Start Pro Trial",
        ctaLink: "signup.html",
        popular: true
    },
    {
        name: "Enterprise",
        icon: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
        </svg>`,
        price: "Custom",
        period: "contact sales",
        description: "For organizations & large teams",
        features: [
            "Everything in Pro",
            "Unlimited bulk uploads",
            "Custom AI model training",
            "Dedicated infrastructure",
            "White-label options",
            "SLA guarantee (99.9%)",
            "Unlimited API calls",
            "24/7 premium support",
            "Custom integrations",
            "Team management",
            "Advanced security",
            "Compliance reports"
        ],
        cta: "Contact Sales",
        ctaLink: "contact.html",
        popular: false
    }
];

// Testimonials Data
const testimonials = [
    {
        name: "Sarah Johnson",
        role: "Journalist, Global News Network",
        content: "TruthGuard AI has become an essential tool in our newsroom. It helps us verify sources and detect manipulated content before publishing. The accuracy is remarkable.",
        rating: 5,
        avatar: "SJ"
    },
    {
        name: "Marcus Chen",
        role: "Social Media Manager",
        content: "As someone who manages multiple brand accounts, this tool is a lifesaver. It catches fake news and deepfakes that could damage our reputation. Highly recommended!",
        rating: 5,
        avatar: "MC"
    },
    {
        name: "Dr. Emily Rodriguez",
        role: "Cybersecurity Researcher",
        content: "The AI models behind TruthGuard are incredibly sophisticated. I've tested it against various deepfake techniques, and it consistently delivers accurate results.",
        rating: 5,
        avatar: "ER"
    },
    {
        name: "James Wilson",
        role: "Content Creator",
        content: "I use this daily to verify content before sharing with my 500k followers. It's fast, reliable, and has saved me from spreading misinformation multiple times.",
        rating: 5,
        avatar: "JW"
    },
    {
        name: "Priya Sharma",
        role: "Education Administrator",
        content: "We integrated TruthGuard into our digital literacy program. Students learn to verify information critically, and the tool provides excellent teaching moments.",
        rating: 5,
        avatar: "PS"
    },
    {
        name: "Alex Thompson",
        role: "Enterprise Security Lead",
        content: "The enterprise features are exactly what we needed. Bulk scanning, API integration, and detailed reports help us maintain information integrity across our organization.",
        rating: 5,
        avatar: "AT"
    }
];

// Render Features
function renderFeatures() {
    const featuresGrid = document.getElementById('featuresGrid');
    if (!featuresGrid) return;

    featuresGrid.innerHTML = features.map((feature, index) => `
        <div class="feature-card glass-strong fade-in-up" style="animation-delay: ${index * 0.05}s">
            <div class="feature-icon-wrapper">
                <div class="feature-icon-bg" style="background: ${feature.gradient}">
                    <div class="feature-icon-inner">
                        <div class="feature-icon">${feature.icon}</div>
                    </div>
                </div>
                ${feature.badge ? `<span class="feature-badge">${feature.badge}</span>` : ''}
            </div>
            <h3 class="feature-title">${feature.title}</h3>
            <p class="feature-description">${feature.description}</p>
        </div>
    `).join('');
}

// Render Steps
function renderSteps() {
    const stepsGrid = document.getElementById('stepsGrid');
    if (!stepsGrid) return;

    stepsGrid.innerHTML = steps.map((step, index) => `
        <div class="step-card glass-strong fade-in-up" style="animation-delay: ${index * 0.15}s">
            <div class="step-number">${step.step}</div>
            <div class="step-icon-wrapper">
                <div class="step-icon-bg">
                    <div class="step-icon">${step.icon}</div>
                </div>
            </div>
            <h3 class="step-title">${step.title}</h3>
            <p class="step-description">${step.description}</p>
        </div>
    `).join('');
}

// Render Pricing
function renderPricing() {
    const pricingGrid = document.getElementById('pricingGrid');
    if (!pricingGrid) return;

    pricingGrid.innerHTML = pricingPlans.map((plan, index) => `
        <div class="pricing-card glass-strong fade-in-up ${plan.popular ? 'popular' : ''}" style="animation-delay: ${index * 0.15}s">
            ${plan.popular ? '<div class="popular-badge">Most Popular</div>' : ''}
            
            <div class="pricing-header">
                <div class="pricing-icon ${plan.popular ? 'popular' : ''}">
                    ${plan.icon}
                </div>
                <h3 class="pricing-name">${plan.name}</h3>
            </div>

            <div class="pricing-price-wrapper">
                <span class="pricing-price">${plan.price}</span>
                ${plan.period !== "contact sales" ? `<span class="pricing-period">/${plan.period}</span>` : ''}
            </div>

            <p class="pricing-description">${plan.description}</p>

            <a href="${plan.ctaLink}" class="btn ${plan.popular ? 'btn-primary' : 'btn-outline'} pricing-cta">
                ${plan.cta}
            </a>

            <ul class="pricing-features">
                ${plan.features.map(feature => `
                    <li>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span>${feature}</span>
                    </li>
                `).join('')}
            </ul>
        </div>
    `).join('');
}

// Render Testimonials
function renderTestimonials() {
    const testimonialsGrid = document.getElementById('testimonialsGrid');
    if (!testimonialsGrid) return;

    testimonialsGrid.innerHTML = testimonials.map((testimonial, index) => `
        <div class="testimonial-card glass-strong fade-in-up" style="animation-delay: ${index * 0.1}s">
            <svg class="quote-icon" viewBox="0 0 24 24" fill="currentColor">
                <path d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z"></path>
                <path d="M15 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2h.75c0 2.25.25 4-2.75 4v3c0 1 0 1 1 1z"></path>
            </svg>
            
            <div class="testimonial-rating">
                ${Array(testimonial.rating).fill('').map(() => `
                    <svg viewBox="0 0 24 24">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                `).join('')}
            </div>

            <p class="testimonial-content">"${testimonial.content}"</p>

            <div class="testimonial-author">
                <div class="testimonial-avatar">${testimonial.avatar}</div>
                <div>
                    <div class="testimonial-name">${testimonial.name}</div>
                    <div class="testimonial-role">${testimonial.role}</div>
                </div>
            </div>
        </div>
    `).join('');
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
    renderFeatures();
    renderSteps();
    renderPricing();
    renderTestimonials();

    // Observe all fade-in elements
    document.querySelectorAll('.fade-in-up').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
        observer.observe(el);
    });
});

// Add active class to current page nav link
const currentPage = window.location.pathname.split('/').pop() || 'index.html';
document.querySelectorAll('.nav-link, .mobile-nav-link').forEach(link => {
    const href = link.getAttribute('href');
    if (href === currentPage || (currentPage === 'index.html' && href === '/')) {
        link.style.color = 'var(--primary)';
    }
});

    </script>
    <!-- Header -->
  
    <?php include 'header-landing.php'; ?>
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-bg"></div>
        <div class="container">
            <div class="hero-grid">
                <div class="hero-content fade-in-up">
                    <div class="badge">
                        <svg class="badge-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                        </svg>
                        <span>AI-Powered Truth Verification</span>
                    </div>

                    <h1 class="hero-title">
                        Detect <span class="gradient-text glow-text">Deepfakes</span><br>
                        & Fake News<br>
                        Instantly
                    </h1>

                    <p class="hero-description">
                        Protect yourself from AI-generated misinformation. Our advanced AI analyzes images, videos, and news articles with 99.7% accuracy in seconds.
                    </p>

                    <div class="hero-stats">
                        <div class="stat-card glass">
                            <div class="stat-value">99.7%</div>
                            <div class="stat-label">Accuracy</div>
                        </div>
                        <div class="stat-card glass">
                            <div class="stat-value">2M+</div>
                            <div class="stat-label">Scans Daily</div>
                        </div>
                        <div class="stat-card glass">
                            <div class="stat-value">&lt;3s</div>
                            <div class="stat-label">Response Time</div>
                        </div>
                    </div>

                    <div class="hero-cta">
                        <a href="signup.html" class="btn btn-primary btn-lg">
                            Start Free Trial
                            <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14M12 5l7 7-7 7"></path>
                            </svg>
                        </a>
                        <button class="btn btn-outline btn-lg">
                            <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polygon points="5 3 19 12 5 21 5 3"></polygon>
                            </svg>
                            Watch Demo
                        </button>
                    </div>

                    <div class="trust-badges">
                        <svg class="trust-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                        <span>Enterprise-grade security</span>
                        <svg class="trust-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <span>Privacy-first approach</span>
                    </div>
                </div>

                <div class="hero-image fade-in-up delay-2">
                    <div class="glass-card float-animation">
                        <img src="hero-ai-detection.jpg" alt="AI Detection Visualization">
                        <div class="image-overlay"></div>
                        
                        <div class="floating-card glass bottom-left slide-up delay-4">
                            <div class="status-indicator"></div>
                            <div>
                                <div class="floating-card-title">Real Content</div>
                                <div class="floating-card-subtitle">Verified by AI</div>
                            </div>
                        </div>

                        <div class="floating-card glass top-right slide-up delay-5">
                            <div class="floating-card-label">Confidence</div>
                            <div class="floating-card-value">99.7%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="scroll-indicator">
            <div class="scroll-mouse"></div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="section features-section">
        <div class="container">
            <div class="section-header fade-in-up">
                <div class="badge">
                    <svg class="badge-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                    <span>Comprehensive Protection</span>
                </div>
                <h2 class="section-title">
                    Powerful Features for<br>
                    <span class="gradient-text">Complete Truth Verification</span>
                </h2>
                <p class="section-description">
                    Everything you need to combat misinformation and protect yourself from AI-generated deception
                </p>
            </div>

            <div class="features-grid" id="featuresGrid">
                <!-- Features will be dynamically loaded -->
            </div>

            <div class="features-cta fade-in-up">
                <p>Ready to experience the future of truth verification?</p>
                <a href="#demo" class="cta-link glass">
                    <span>Try it now - It's free</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="section how-it-works-section">
        <div class="container">
            <div class="section-header fade-in-up">
                <div class="badge">
                    <svg class="badge-icon pulse" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M12 6v6l4 2"></path>
                    </svg>
                    <span>Simple Process</span>
                </div>
                <h2 class="section-title">
                    How <span class="gradient-text">TruthGuard AI</span> Works
                </h2>
                <p class="section-description">
                    Four simple steps to verify any content in seconds
                </p>
            </div>

            <div class="steps-grid" id="stepsGrid">
                <!-- Steps will be dynamically loaded -->
            </div>

            <div class="stats-row">
                <div class="stat-box glass-card">
                    <div class="stat-number gradient-text">2.5M+</div>
                    <div class="stat-text">Scans Completed</div>
                </div>
                <div class="stat-box glass-card">
                    <div class="stat-number gradient-text">99.7%</div>
                    <div class="stat-text">Accuracy Rate</div>
                </div>
                <div class="stat-box glass-card">
                    <div class="stat-number gradient-text">&lt;3sec</div>
                    <div class="stat-text">Average Time</div>
                </div>
                <div class="stat-box glass-card">
                    <div class="stat-number gradient-text">150+</div>
                    <div class="stat-text">Countries Served</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="section pricing-section">
        <div class="container">
            <div class="section-header fade-in-up">
                <div class="badge">
                    <svg class="badge-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                    </svg>
                    <span>Simple Pricing</span>
                </div>
                <h2 class="section-title">
                    Choose Your <span class="gradient-text">Protection Plan</span>
                </h2>
                <p class="section-description">
                    Start free, upgrade when you need more power
                </p>
            </div>

            <div class="pricing-grid" id="pricingGrid">
                <!-- Pricing cards will be dynamically loaded -->
            </div>

            <div class="pricing-cta fade-in-up">
                <p>Questions about pricing?</p>
                <a href="contact.html" class="text-link">Contact our sales team</a>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="section testimonials-section">
        <div class="container">
            <div class="section-header fade-in-up">
                <div class="badge">
                    <svg class="badge-icon" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                    <span>Trusted by Professionals</span>
                </div>
                <h2 class="section-title">
                    What Our <span class="gradient-text">Users Say</span>
                </h2>
                <p class="section-description">
                    Join thousands of professionals protecting truth worldwide
                </p>
            </div>

            <div class="testimonials-grid" id="testimonialsGrid">
                <!-- Testimonials will be dynamically loaded -->
            </div>

            <div class="testimonial-stats">
                <div class="testimonial-stat">
                    <div class="testimonial-stat-value gradient-text">4.9/5</div>
                    <div class="testimonial-stat-label">Average Rating</div>
                </div>
                <div class="testimonial-stat">
                    <div class="testimonial-stat-value gradient-text">50k+</div>
                    <div class="testimonial-stat-label">Active Users</div>
                </div>
                <div class="testimonial-stat">
                    <div class="testimonial-stat-value gradient-text">98%</div>
                    <div class="testimonial-stat-label">Satisfaction Rate</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section cta-section">
        <div class="container">
            <div class="cta-card glass-strong">
                <div class="cta-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                </div>

                <h2 class="cta-title">
                    Start Protecting Truth<br>
                    <span class="gradient-text">Today</span>
                </h2>

                <p class="cta-description">
                    Join over 50,000 professionals who trust TruthGuard AI to detect image and fake news. Start with 10 free scans daily, no credit card required.
                </p>

                <div class="cta-buttons">
                    <a href="signup.html" class="btn btn-primary btn-lg">
                        Get Started Free
                        <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    <a href="pricing.html" class="btn btn-outline btn-lg">View Pricing</a>
                </div>

                <div class="cta-features">
                    <div class="cta-feature">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                        <span>No credit card required</span>
                    </div>
                    <div class="cta-feature">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                        </svg>
                        <span>10 free scans daily</span>
                    </div>
                    <div class="cta-feature">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                        <span>Cancel anytime</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

   <?php include 'footer-landing.php'; ?>


    <script src="script.js"></script>
</body>
</html>
