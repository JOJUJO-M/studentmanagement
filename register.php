<?php
// public/register.php
$page_title = 'Register - Boilerplate';
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
        <h1>Register</h1>
        <div id="alert-container"></div>
        <form id="register-form" class="validate-form">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" required
                    placeholder="Choose a username" autocomplete="username" autocapitalize="none" spellcheck="false">
            </div>
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" id="email" name="email" class="form-control" required
                    placeholder="Enter your email" autocomplete="email" inputmode="email" autocapitalize="none" spellcheck="false">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required
                    placeholder="Create a password" autocomplete="new-password">
            </div>
            <button type="submit" class="btn btn-primary">Sign Up</button>
        </form>
        <div class="auth-footer">
            Already have an account? <a href="<?php echo BASE_URL; ?>/login.php">Login here</a>
        </div>
    </div>
</div>

<script>
    document.getElementById('register-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());

        const response = await apiCall('/api/auth.php?action=register', 'POST', data);

        const alertContainer = document.getElementById('alert-container');
        if (response.success) {
            alertContainer.innerHTML = `<div class="alert alert-success">${response.message} Redirection to login...</div>`;
            setTimeout(() => window.location.href = BASE_URL + '/login.php', 2000);
        } else {
            alertContainer.innerHTML = `<div class="alert alert-danger">${response.message}</div>`;
        }
    });
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>