<?php
// public/index.php
require_once __DIR__ . '/../config/auth.php';
$page_title = 'Welcome';
$plain_layout = true;
$body_class = 'landing-page';
include __DIR__ . '/../includes/header.php';
?>

<section class="hero">
    <div class="hero-content">
        <h1 class="hero-title">Next-Gen <span class="highlight">Student Management</span></h1>
        <p class="hero-subtitle">Automate enrollments, streamline fee collection, and empower your institution with our state-of-the-art platform.</p>
        <div class="hero-btns">
            <?php if (is_logged_in()): ?>
                <a href="<?php echo BASE_URL; ?>/dashboard.php" class="btn btn-primary btn-glow">Enter Dashboard <i class="fas fa-arrow-right"></i></a>
            <?php else: ?>
                <a href="<?php echo BASE_URL; ?>/login.php" class="btn btn-primary btn-glow">Administrator Login <i class="fas fa-lock"></i></a>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="hero-decoration">
        <div class="circle circle-1"></div>
        <div class="circle circle-2"></div>
    </div>
</section>

<section id="features" class="features glass-section">
    <div class="section-header">
        <h2>Powerful Features for Modern Institutions</h2>
        <p>Everything you need to run your school efficiently, in one beautiful interface.</p>
    </div>
    <div class="features-grid">
        <div class="feature-card glass-card">
            <div class="icon-wrapper bg-gradient-1">
                <i class="fas fa-user-graduate"></i>
            </div>
            <h3>Student Records</h3>
            <p>Maintain comprehensive profiles, track statuses, and manage enrollment histories seamlessly.</p>
        </div>
        <div class="feature-card glass-card">
            <div class="icon-wrapper bg-gradient-2">
                <i class="fas fa-book-open"></i>
            </div>
            <h3>Course Management</h3>
            <p>Build and organize your curriculum. Track course credits and assign students with ease.</p>
        </div>
        <div class="feature-card glass-card">
            <div class="icon-wrapper bg-gradient-3">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <h3>Automated Billing</h3>
            <p>Set fee structures per semester, track payments, and generate instant financial reports.</p>
        </div>
    </div>
</section>

<section id="stats" class="stats-banner">
    <div class="stat-item">
        <h2>99%</h2>
        <p>Uptime</p>
    </div>
    <div class="stat-item">
        <h2>Secure</h2>
        <p>Data Encryption</p>
    </div>
    <div class="stat-item">
        <h2>24/7</h2>
        <p>Access Anywhere</p>
    </div>
</section>

<section id="contact" class="contact-section">
    <div class="contact-card glass-card">
        <h2>Ready to upgrade your campus?</h2>
        <p>Contact our deployment team today to schedule a live demonstration.</p>
        <div class="contact-info-pills">
            <div class="pill"><i class="fas fa-envelope"></i> info@jojujomwaluseke.co.tz</div>
            <div class="pill"><i class="fas fa-phone"></i> +255 (0) 766 576 805</div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>