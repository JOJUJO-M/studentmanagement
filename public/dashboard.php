<?php
// public/dashboard.php
$page_title = 'Dashboard';
include __DIR__ . '/../includes/header.php';
require_login();

// Fetch some stats
$stats = [
    'students' => $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn(),
    'courses' => $pdo->query("SELECT COUNT(*) FROM courses")->fetchColumn(),
    'revenue' => $pdo->query("SELECT SUM(amount_paid) FROM fee_payments")->fetchColumn() ?: 0,
];

// Fetch recent enrollments
$recent_enrollments = $pdo->query("SELECT e.*, s.first_name, s.last_name, c.course_name 
                                   FROM enrollments e 
                                   JOIN students s ON e.student_id = s.id 
                                   JOIN courses c ON e.course_id = c.id 
                                   ORDER BY e.enrollment_date DESC LIMIT 5")->fetchAll();
?>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-info">
            <h3>Total Students</h3>
            <div class="stat-value">
                <?php echo $stats['students']; ?>
            </div>
        </div>
        <div class="stat-icon bg-primary-light">
            <i class="fas fa-user-graduate"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <h3>Active Courses</h3>
            <div class="stat-value">
                <?php echo $stats['courses']; ?>
            </div>
        </div>
        <div class="stat-icon bg-success-light">
            <i class="fas fa-book-open"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <h3>Total Revenue Collected</h3>
            <div class="stat-value" style="color: #28a745;">
                $<?php echo number_format($stats['revenue'], 2); ?>
            </div>
        </div>
        <div class="stat-icon bg-warning-light">
            <i class="fas fa-money-bill-wave"></i>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Recent Enrollments</h3>
        <a href="<?php echo BASE_URL; ?>/modules/enrollments/list.php" class="btn btn-secondary btn-sm">View All</a>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Course</th>
                    <th>Term</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent_enrollments as $e): ?>
                    <tr>
                        <td><strong>
                                <?php echo htmlspecialchars($e['first_name'] . ' ' . $e['last_name']); ?>
                            </strong></td>
                        <td>
                            <?php echo htmlspecialchars($e['course_name']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($e['semester'] . ' ' . $e['academic_year']); ?>
                        </td>
                        <td>
                            <?php echo date('M d, Y', strtotime($e['enrollment_date'])); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($recent_enrollments)): ?>
                    <tr>
                        <td colspan="4" style="text-align: center;">No enrollments found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>