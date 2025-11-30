
    <style>
        :root {
            --bg: hsl(220, 28%, 8%);
            --text: hsl(210, 40%, 98%);
            --primary: hsl(189, 94%, 55%);
            --muted: hsl(215, 20%, 65%);
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Plus Jakarta Sans',sans-serif; background:var(--bg); color:var(--text); }

        .container { max-width:1280px; margin:0 auto; padding:0 1.5rem; }
        .header {
            position:fixed; top:0; left:0; right:0; z-index:1000;
            background:rgba(255,255,255,0.08); backdrop-filter:blur(30px);
            border-bottom:1px solid rgba(255,255,255,0.12);
        }
        .header-content { display:flex; align-items:center; justify-content:space-between; height:70px; }

        .logo { display:flex; align-items:center; gap:10px; text-decoration:none; }
        .logo-icon { width:32px; height:32px; color:var(--primary); }
        .logo-text {
            font-size:1.5rem; font-weight:800;
            background:linear-gradient(135deg,var(--primary),#60a5fa);
            -webkit-background-clip:text; -webkit-text-fill-color:transparent;
        }

        .nav-link, .mobile-nav-link {
            color:var(--muted); text-decoration:none; font-weight:500; position:relative; 
            transition:color .3s; padding:8px 4px;
        }
        .nav-link:hover, .nav-link.active, 
        .mobile-nav-link:hover, .mobile-nav-link.active {
            color:var(--primary) !important;
        }
        .nav-link.active::after, .nav-link:hover::after {
            content:''; position:absolute; bottom:-8px; left:0; width:100%; height:3px;
            background:var(--primary); border-radius:3px;
        }
        .mobile-nav-link.active::before {
            content:''; position:absolute; left:0; top:50%; transform:translateY(-50%);
            width:5px; height:24px; background:var(--primary); border-radius:3px;
        }

        .desktop-nav { display:none; gap:2.5rem; align-items:center; }
        @media(min-width:1024px){ .desktop-nav{display:flex;} }

        .mobile-menu-btn { background:none; border:none; color:white; cursor:pointer; display:block; }
        @media(min-width:1024px){ .mobile-menu-btn{display:none;} }

        .mobile-menu { display:none; background:rgba(255,255,255,0.06); backdrop-filter:blur(20px); padding:1.5rem 0; }
        .mobile-menu.active { display:block; }

       .btn {
    padding:0.7rem 1.4rem; 
    border-radius:10px; 
    font-weight:600; 
    text-decoration:none;
    transition:all .3s; 
    font-size:0.95rem;
    color: white !important;   /* ← Yeh line add kar do */
}

.btn-primary {
    background:linear-gradient(135deg,var(--primary),#60a5fa);
    color:white; 
    box-shadow:0 6px 20px rgba(6,182,212,0.4);
}
        .btn-primary:hover { transform:translateY(-3px); }
    </style>
</head>
<body>

<?php
$current_file = basename($_SERVER['PHP_SELF']);
// PHP sirf file name check karta hai, hash JavaScript handle karega
?>

<header class="header">
    <div class="container">
        <div class="header-content">
            <a href="index.php" class="logo">
                <svg class="logo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
                <span class="logo-text">TruthMatrix AI</span>
            </a>

            <!-- Desktop Nav -->
            <nav class="desktop-nav">
                <a href="index.php" id="nav-home" class="nav-link">Home</a>
                <a href="index.php#features" id="nav-features" class="nav-link">Features</a>
                <a href="index.php#how-it-works" id="nav-how-it-works" class="nav-link">How It Works</a>
                <a href="pricing.php" id="nav-pricing" class="nav-link">Pricing</a>
                <a href="about.php" id="nav-about" class="nav-link">About</a>
                <a href="contact.php" id="nav-contact" class="nav-link">Contact</a>
            </nav>

            <div class="desktop-nav">
                <a href="login.php" class="btn">Log In</a>
                <a href="signup.php" class="btn btn-primary">Start Free Trial</a>
            </div>

            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 12h18M3 6h18M3 18h18"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="container">
            <a href="index.php" id="mobile-home" class="mobile-nav-link">Home</a>
            <a href="index.php#features" id="mobile-features" class="mobile-nav-link">Features</a>
            <a href="index.php#how-it-works" id="mobile-how-it-works" class="mobile-nav-link">How It Works</a>
            <a href="pricing.php" id="mobile-pricing" class="mobile-nav-link">Pricing</a>
            <a href="about.php" id="mobile-about" class="mobile-nav-link">About</a>
            <a href="contact.php" id="mobile-contact" class="mobile-nav-link">Contact</a>
            <div style="margin-top:2rem; display:flex; flex-direction:column; gap:1rem;">
                <a href="login.php" class="btn">Log In</a>
                <a href="signup.php" class="btn btn-primary">Start Free Trial</a>
            </div>
        </div>
    </div>
</header>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Mobile menu functionality
    const btn = document.getElementById('mobileMenuBtn');
    const menu = document.getElementById('mobileMenu');
    btn.onclick = () => menu.classList.toggle('active');
    document.querySelectorAll('.mobile-nav-link').forEach(l => {
        l.onclick = () => menu.classList.remove('active');
    });

    // Smart active navigation based on current URL
    function updateActiveNav() {
        const currentPath = window.location.pathname;
        const currentHash = window.location.hash;
        
        // Remove all active classes first
        document.querySelectorAll('.nav-link, .mobile-nav-link').forEach(link => {
            link.classList.remove('active');
        });

        // Check for current page and hash
        if (currentPath.endsWith('pricing.php')) {
            document.getElementById('nav-pricing')?.classList.add('active');
            document.getElementById('mobile-pricing')?.classList.add('active');
        } else if (currentPath.endsWith('about.php')) {
            document.getElementById('nav-about')?.classList.add('active');
            document.getElementById('mobile-about')?.classList.add('active');
        } else if (currentPath.endsWith('contact.php')) {
            document.getElementById('nav-contact')?.classList.add('active');
            document.getElementById('mobile-contact')?.classList.add('active');
        } else if (currentPath.endsWith('index.php') || currentPath === '/') {
            // For index.php - check hash
            if (currentHash === '#features') {
                document.getElementById('nav-features')?.classList.add('active');
                document.getElementById('mobile-features')?.classList.add('active');
            } else if (currentHash === '#how-it-works') {
                document.getElementById('nav-how-it-works')?.classList.add('active');
                document.getElementById('mobile-how-it-works')?.classList.add('active');
            } else {
                // Default for index.php (no hash)
                document.getElementById('nav-home')?.classList.add('active');
                document.getElementById('mobile-home')?.classList.add('active');
            }
        }
    }

    // Update on page load
    updateActiveNav();

    // Update when hash changes (for single page navigation)
    window.addEventListener('hashchange', updateActiveNav);
});
</script>

<div style="height:80px;"></div> <!-- Header space -->