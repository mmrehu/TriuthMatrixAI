<?php
session_start();
$currentPage = 'result';
$pageTitle = 'Scan Result - TruthGuard AI';

// Get verdict from URL parameters
$verdict = $_GET['verdict'] ?? 'unknown';
$confidence = $_GET['confidence'] ?? '0.00';
$type = $_GET['type'] ?? 'text';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan Result - TruthGuard AI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* All the CSS from the original file remains the same */
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

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes bounce {
            0%, 20%, 53%, 80%, 100% {
                transform: translate3d(0, 0, 0);
            }
            40%, 43% {
                transform: translate3d(0, -8px, 0);
            }
            70% {
                transform: translate3d(0, -4px, 0);
            }
            90% {
                transform: translate3d(0, -2px, 0);
            }
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

        .slide-in-right {
            animation: slideInRight 0.5s ease-out forwards;
        }

        .float-animation {
            animation: float 6s ease-in-out infinite;
        }

        .pulse {
            animation: pulse-glow 2s ease-in-out infinite;
        }

        .bounce {
            animation: bounce 1s ease infinite;
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

        /* Scan Layout */
        .scan {
            padding-top: 5rem;
            min-height: 100vh;
        }

        @media (min-width: 1024px) {
            .scan {
                padding-top: 6rem;
            }
        }

        .scan-content {
            padding: 2rem 0;
        }

        @media (min-width: 1024px) {
            .scan-content {
                padding: 3rem 0;
            }
        }

        .scan-grid {
            display: grid;
            gap: 1.5rem;
        }

        @media (min-width: 1024px) {
            .scan-grid {
                grid-template-columns: 1fr 350px;
                gap: 2rem;
            }
        }

        .scan-main {
            display: grid;
            gap: 1.5rem;
        }

        .scan-sidebar {
            display: grid;
            gap: 1.5rem;
            align-content: start;
        }

        /* Scan Cards */
        .scan-card {
            padding: 1.5rem;
            border-radius: var(--radius);
            transition: all 0.3s ease;
        }

        .scan-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px hsla(189, 94%, 55%, 0.2);
        }

        .scan-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .scan-card-title {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .scan-card-icon {
            width: 2rem;
            height: 2rem;
            color: var(--primary);
        }

        /* Upload Options */
        .upload-options {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }

        @media (min-width: 768px) {
            .upload-options {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        .upload-option {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 1.5rem 1rem;
            border-radius: var(--radius);
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            text-align: center;
        }

        .upload-option:hover {
            background: rgba(255, 255, 255, 0.05);
            transform: translateY(-5px);
        }

        .upload-option.active {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid var(--primary);
        }

        .upload-option-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--glow-cyan), var(--glow-blue));
        }

        .upload-option-icon svg {
            width: 1.5rem;
            height: 1.5rem;
            color: white;
        }

        .upload-option-text {
            font-size: 0.875rem;
            font-weight: 500;
        }

        /* Upload Areas */
        .upload-area {
            display: none;
            padding: 2rem;
            border-radius: var(--radius);
            text-align: center;
            transition: all 0.3s ease;
        }

        .upload-area.active {
            display: block;
            animation: fadeIn 0.5s ease-out forwards;
        }

        .upload-area-icon {
            width: 4rem;
            height: 4rem;
            margin: 0 auto 1.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--glow-cyan), var(--glow-blue));
        }

        .upload-area-icon svg {
            width: 2rem;
            height: 2rem;
            color: white;
        }

        .upload-area-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .upload-area-description {
            font-size: 0.875rem;
            color: var(--muted-foreground);
            margin-bottom: 1.5rem;
        }

        .upload-area-content {
            margin-top: 1.5rem;
        }

        /* Dropzone */
        .dropzone {
            border: 2px dashed rgba(255, 255, 255, 0.2);
            border-radius: var(--radius);
            padding: 2rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .dropzone:hover, .dropzone.dragover {
            border-color: var(--primary);
            background: rgba(255, 255, 255, 0.05);
        }

        .dropzone-icon {
            width: 3rem;
            height: 3rem;
            margin: 0 auto 1rem;
            color: var(--muted-foreground);
        }

        .dropzone-text {
            font-size: 0.875rem;
            color: var(--muted-foreground);
            margin-bottom: 1rem;
        }

        .dropzone-hint {
            font-size: 0.75rem;
            color: var(--muted-foreground);
        }

        /* Text Input */
        .text-input {
            width: 100%;
            min-height: 150px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--radius);
            padding: 1rem;
            color: var(--foreground);
            font-family: inherit;
            font-size: 0.875rem;
            resize: vertical;
            transition: all 0.3s ease;
        }

        .text-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px hsla(189, 94%, 55%, 0.2);
        }

        /* URL Input */
        .url-input {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--radius);
            padding: 1rem;
            color: var(--foreground);
            font-family: inherit;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .url-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px hsla(189, 94%, 55%, 0.2);
        }

        /* File Preview */
        .file-preview {
            display: none;
            margin-top: 1.5rem;
            padding: 1rem;
            border-radius: var(--radius);
            background: rgba(255, 255, 255, 0.05);
        }

        .file-preview.active {
            display: block;
            animation: slideUp 0.3s ease-out forwards;
        }

        .file-preview-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .file-preview-title {
            font-size: 0.875rem;
            font-weight: 500;
        }

        .file-preview-remove {
            background: none;
            border: none;
            color: var(--muted-foreground);
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .file-preview-remove:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--foreground);
        }

        .file-preview-content {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .file-preview-icon {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.1);
        }

        .file-preview-icon svg {
            width: 1.25rem;
            height: 1.25rem;
            color: var(--primary);
        }

        .file-preview-info {
            flex: 1;
        }

        .file-preview-name {
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .file-preview-size {
            font-size: 0.75rem;
            color: var(--muted-foreground);
        }

        .file-preview-image {
            max-width: 100%;
            max-height: 200px;
            border-radius: var(--radius);
            margin-top: 1rem;
        }

        /* Bulk Upload Preview */
        .bulk-preview {
            display: none;
            margin-top: 1.5rem;
        }

        .bulk-preview.active {
            display: block;
            animation: slideUp 0.3s ease-out forwards;
        }

        .bulk-preview-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .bulk-preview-title {
            font-size: 0.875rem;
            font-weight: 500;
        }

        .bulk-preview-clear {
            background: none;
            border: none;
            color: var(--muted-foreground);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: var(--radius);
            font-size: 0.75rem;
            transition: all 0.3s ease;
        }

        .bulk-preview-clear:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--foreground);
        }

        .bulk-preview-list {
            display: grid;
            gap: 0.75rem;
            max-height: 300px;
            overflow-y: auto;
        }

        .bulk-preview-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            border-radius: var(--radius);
            background: rgba(255, 255, 255, 0.05);
        }

        .bulk-preview-item-icon {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.1);
        }

        .bulk-preview-item-icon svg {
            width: 1rem;
            height: 1rem;
            color: var(--primary);
        }

        .bulk-preview-item-info {
            flex: 1;
        }

        .bulk-preview-item-name {
            font-size: 0.75rem;
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .bulk-preview-item-size {
            font-size: 0.625rem;
            color: var(--muted-foreground);
        }

        .bulk-preview-item-remove {
            background: none;
            border: none;
            color: var(--muted-foreground);
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .bulk-preview-item-remove:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--foreground);
        }

        /* Scan Actions */
        .scan-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        /* Recent Scans */
        .recent-scans {
            display: grid;
            gap: 1rem;
        }

        .recent-scan {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-radius: var(--radius);
            transition: all 0.3s ease;
        }

        .recent-scan:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .recent-scan-icon {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.05);
        }

        .recent-scan-icon svg {
            width: 1.25rem;
            height: 1.25rem;
            color: var(--primary);
        }

        .recent-scan-content {
            flex: 1;
        }

        .recent-scan-title {
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        .recent-scan-time {
            font-size: 0.75rem;
            color: var(--muted-foreground);
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

        /* Stats */
        .stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .stat {
            text-align: center;
            padding: 1rem;
            border-radius: var(--radius);
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        @media (min-width: 768px) {
            .stat-value {
                font-size: 2rem;
            }
        }

        .stat-label {
            font-size: 0.875rem;
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
    
    <section class="scan">
        <div class="container">
            <div class="scan-content">
                <div class="scan-card glass-strong fade-in-up">
                    <div class="result-card">
                        <?php
                        $icon_class = "verdict-{$verdict}-icon";
                        $icon_text = "";
                        
                        if ($verdict === 'real') {
                            $icon_text = "✓";
                        } elseif ($verdict === 'fake') {
                            $icon_text = "✗";
                        } elseif ($verdict === 'misleading') {
                            $icon_text = "!";
                        } else {
                            $icon_text = "?";
                        }
                        ?>
                        
                        <div class="verdict-icon <?php echo $icon_class; ?>">
                            <?php echo $icon_text; ?>
                        </div>
                        
                        <h1 class="scan-card-title" style="font-size: 2rem; margin-bottom: 1rem;">
                            Content is <span class="verdict-<?php echo $verdict; ?>"><?php echo strtoupper($verdict); ?></span>
                        </h1>
                        
                        <p style="margin-bottom: 1.5rem; font-size: 1.125rem;">
                            Our AI analysis determined this content is <strong><?php echo $verdict; ?></strong> with 
                            <strong><?php echo $confidence; ?>%</strong> confidence.
                        </p>
                        
                        <div class="confidence-meter">
                            <div class="confidence-fill" style="width: <?php echo $confidence; ?>%; background: linear-gradient(135deg, var(--glow-cyan), var(--glow-blue));"></div>
                        </div>
                        
                        <p style="color: var(--muted-foreground); margin-bottom: 2rem;">
                            Confidence Level: <?php echo $confidence; ?>%
                        </p>
                        
                        <div class="scan-actions" style="justify-content: center;">
                            <a href="scan.php" class="btn btn-primary">
                                <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="17 8 12 3 7 8"></polyline>
                                    <line x1="12" y1="3" x2="12" y2="15"></line>
                                </svg>
                                Scan Another
                            </a>
                            <a href="community.php" class="btn btn-outline">
                                <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                                View Community
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <?php include 'footer.php'; ?>
</body>
</html>