<?php
// public/login.php
$page_title = 'Login - Boilerplate';
include __DIR__ . '/includes/header.php';

if (is_logged_in()) {
    header('Location: ' . BASE_URL . '/dashboard.php');
    exit();
}
?>

<div class="auth-page">
    <div class="auth-card">
        <div class="brand-logo-container">
            <img src="<?php echo BASE_URL; ?>/assets/images/CBE_Logo2.png" alt="CBE Logo" class="brand-logo">
        </div>
        <h1>Login</h1>
        <div id="alert-container"></div>
        <form id="login-form" class="validate-form">
            <div class="form-group">
                <label for="login">Username or Email</label>
                <input type="text" id="login" name="login" class="form-control" required
                    placeholder="Enter username or email" autocomplete="username" autocapitalize="none" spellcheck="false" inputmode="email">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required
                    placeholder="Enter password" autocomplete="current-password">
            </div>
            <button type="submit" class="btn btn-primary">Sign In</button>
        </form>
        <div class="auth-footer">
            Don't have an account? <a href="<?php echo BASE_URL; ?>/register.php">Register here</a>
        </div>
    </div>
</div>

<script>
    document.getElementById('login-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());

        const response = await apiCall('/api/auth.php?action=login', 'POST', data);

        const alertContainer = document.getElementById('alert-container');
        if (response.success) {
            alertContainer.innerHTML = `<div class="alert alert-success">${response.message}</div>`;
            setTimeout(() => window.location.href = response.redirect, 1000);
        } else {
            alertContainer.innerHTML = `<div class="alert alert-danger">${response.message}</div>`;
        }
    });
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>