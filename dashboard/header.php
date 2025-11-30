

<!-- Header - EXACTLY SAME AS YOUR ORIGINAL -->
<header class="header">
    <div class="container">
        <div class="header-content">
            <a href="index.php" class="logo">
                <svg class="logo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
                <span class="logo-text">TruthGuard AI</span>
            </a>

            <nav class="nav desktop-nav">

                <a href="index.php" class="nav-link <?php echo ($currentPage == 'dashboard') ? 'active' : ''; ?>">Dashboard</a>
                <a href="scan.php" class="nav-link <?php echo ($currentPage == 'scan') ? 'active' : ''; ?>">Scan</a>
                <a href="community.php" class="nav-link <?php echo ($currentPage == 'community') ? 'active' : ''; ?>">Community</a>
                <a href="leaderboard.php" class="nav-link <?php echo ($currentPage == 'leaderboard') ? 'active' : ''; ?>">Leaderboard</a>
                <a href="profile.php" class="nav-link <?php echo ($currentPage == 'profile') ? 'active' : ''; ?>">Profile</a>
            </nav>

            <div class="header-actions desktop-nav">
                <div class="user-menu">
                    <a href="profile.php" class="btn btn-ghost">
                        <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        John Doe
                    </a>
                </div>
                <a href="logout.php" class="btn btn-outline">Log Out</a>
            </div>

            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 12h18M3 6h18M3 18h18"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu - BILKUL WAHI -->
    <div class="mobile-menu" id="mobileMenu">
        <nav class="mobile-nav">
          
            <a href="index.php" class="mobile-nav-link <?php echo ($currentPage == 'dashboard') ? 'active' : ''; ?>">Dashboard</a>
            <a href="scan.php" class="mobile-nav-link <?php echo ($currentPage == 'scan') ? 'active' : ''; ?>">Scan</a>
            <a href="community.php" class="mobile-nav-link <?php echo ($currentPage == 'community') ? 'active' : ''; ?>">Community</a>
            <a href="leaderboard.php" class="mobile-nav-link <?php echo ($currentPage == 'leaderboard') ? 'active' : ''; ?>">Leaderboard</a>
            <a href="profile.php" class="mobile-nav-link <?php echo ($currentPage == 'profile') ? 'active' : ''; ?>">Profile</a>
            
            <div class="mobile-menu-actions">
                <a href="profile.php" class="btn btn-outline">
                    <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    Profile
                </a>
                <a href="logout.php" class="btn btn-primary">Log Out</a>
            </div>
        </nav>
    </div>
</header>