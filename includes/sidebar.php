<?php
// includes/sidebar.php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar">
    <div class="sidebar-header">
        <img src="<?php echo BASE_URL; ?>/assets/images/CBE_Logo2.png" alt="Logo" style="width: 30px; height: auto; border-radius: 4px;">
        <span style="font-size: 0.85rem;"><?php echo strtoupper(get_setting('system_name', 'CBE SYSTEM')); ?></span>
    </div>
    <nav class="sidebar-nav">
        <ul>
            <li class="<?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">
                <a href="<?php echo BASE_URL; ?>/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            </li>
            <li class="<?php echo $current_page == 'index.php' ? 'active' : ''; ?>">
                <a href="<?php echo BASE_URL; ?>/index.php"><i class="fas fa-external-link-alt"></i> View Website</a>
            </li>

            <li class="nav-label">Student Management</li>
            <li class="<?php echo strpos($_SERVER['PHP_SELF'], 'modules/students') !== false ? 'active' : ''; ?>">
                <a href="<?php echo BASE_URL; ?>/modules/students/list.php"><i class="fas fa-user-graduate"></i> Students</a>
            </li>
            <li class="<?php echo strpos($_SERVER['PHP_SELF'], 'modules/courses') !== false ? 'active' : ''; ?>">
                <a href="<?php echo BASE_URL; ?>/modules/courses/list.php"><i class="fas fa-book-open"></i> Courses</a>
            </li>
            <li class="<?php echo strpos($_SERVER['PHP_SELF'], 'modules/enrollments') !== false ? 'active' : ''; ?>">
                <a href="<?php echo BASE_URL; ?>/modules/enrollments/list.php"><i class="fas fa-clipboard-list"></i> Enrollments</a>
            </li>
            
            <li class="nav-label">Finance</li>
            <li class="<?php echo strpos($_SERVER['PHP_SELF'], 'modules/fees/structures.php') !== false ? 'active' : ''; ?>">
                <a href="<?php echo BASE_URL; ?>/modules/fees/structures.php"><i class="fas fa-file-invoice-dollar"></i> Fee Structures</a>
            </li>
            <li class="<?php echo strpos($_SERVER['PHP_SELF'], 'modules/fees/payments.php') !== false ? 'active' : ''; ?>">
                <a href="<?php echo BASE_URL; ?>/modules/fees/payments.php"><i class="fas fa-money-check-alt"></i> Record Payment</a>
            </li>
            <li class="<?php echo strpos($_SERVER['PHP_SELF'], 'modules/fees/reports.php') !== false ? 'active' : ''; ?>">
                <a href="<?php echo BASE_URL; ?>/modules/fees/reports.php"><i class="fas fa-chart-line"></i> Fee Reports</a>
            </li>

            <?php if (has_role('admin')): ?>
                <li class="nav-label">Administration</li>
                <li class="<?php echo strpos($_SERVER['PHP_SELF'], 'modules/users') !== false ? 'active' : ''; ?>">
                    <a href="<?php echo BASE_URL; ?>/modules/users/list.php"><i class="fas fa-users"></i> User Management</a>
                </li>
                <li class="<?php echo $current_page == 'settings.php' ? 'active' : ''; ?>">
                    <a href="<?php echo BASE_URL; ?>/settings.php"><i class="fas fa-cog"></i> System Settings</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</aside>