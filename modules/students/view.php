<?php
// modules/students/view.php
$page_title = 'Student Profile';
include __DIR__ . '/../../includes/header.php';
require_login();

$id = $_GET['id'] ?? 0;
$student = $studentObj->getById($id);

if (!$student) {
    echo "<div class='alert alert-danger'>Student not found.</div>";
    include __DIR__ . '/../../includes/footer.php';
    exit();
}

$enrollments = $enrollmentObj->getByStudent($id);
$payments = $feeObj->getStudentPayments($id);
$balance = $studentObj->getBalance($id);

?>

<div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 1rem;">
    <h2>Student Profile: <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></h2>
    <a href="list.php" class="btn btn-secondary btn-sm" style="margin-left: auto; width:auto;">Back to List</a>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-info">
            <h3>Status</h3>
            <div class="stat-value" style="font-size: 1.2rem;">
                <span class="badge badge-<?php echo $student['status'] === 'active' ? 'success' : ($student['status'] === 'graduated' ? 'primary' : 'warning'); ?>">
                    <?php echo ucfirst($student['status']); ?>
                </span>
            </div>
        </div>
        <div class="stat-icon bg-success-light"><i class="fas fa-info-circle"></i></div>
    </div>
    <div class="stat-card">
        <div class="stat-info">
            <h3>Total Balance Due</h3>
            <div class="stat-value" style="font-size: 1.5rem; color: <?php echo $balance > 0 ? '#dc3545' : '#28a745'; ?>;">
                TZS <?php echo number_format($balance, 2); ?>
            </div>
        </div>
        <div class="stat-icon bg-warning-light"><i class="fas fa-money-bill-wave"></i></div>
    </div>
</div>

<div class="card" style="margin-top: 1rem;">
    <div class="card-header">
        <h3><i class="fas fa-book"></i> Enrolled Courses</h3>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Course Code</th>
                    <th>Course Name</th>
                    <th>Semester</th>
                    <th>Academic Year</th>
                    <th>Date Enrolled</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($enrollments as $e): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($e['course_code']); ?></td>
                        <td><?php echo htmlspecialchars($e['course_name']); ?></td>
                        <td><?php echo htmlspecialchars($e['semester']); ?></td>
                        <td><?php echo htmlspecialchars($e['academic_year']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($e['enrollment_date'])); ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($enrollments)): ?>
                    <tr><td colspan="5" style="text-align: center;">No enrollments found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card" style="margin-top: 1rem;">
    <div class="card-header">
        <h3><i class="fas fa-receipt"></i> Payment History</h3>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Course/Semester</th>
                    <th>Receipt No.</th>
                    <th>Method</th>
                    <th>Amount Paid</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payments as $p): ?>
                    <tr>
                        <td><?php echo date('M d, Y H:i', strtotime($p['payment_date'])); ?></td>
                        <td><?php echo htmlspecialchars($p['course_name'] . ' (' . $p['semester'] . ' ' . $p['academic_year'] . ')'); ?></td>
                        <td><?php echo htmlspecialchars($p['receipt_number']); ?></td>
                        <td><?php echo htmlspecialchars($p['payment_method']); ?></td>
                        <td>TZS <?php echo number_format($p['amount_paid'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($payments)): ?>
                    <tr><td colspan="5" style="text-align: center;">No payments recorded.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
