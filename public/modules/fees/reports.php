<?php
// modules/fees/reports.php
$page_title = 'Fee Reports & Balances';
include __DIR__ . '/../../../includes/header.php';
require_login();

// Fetch all students to calculate balances
$students = $studentObj->getAll();
$payments = $feeObj->getPayments();
?>

<div class="card">
    <div class="card-header">
        <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
            <h3><i class="fas fa-chart-bar"></i> Student Balances</h3>
        </div>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Balance Due</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $s): 
                    $balance = $studentObj->getBalance($s['id']);
                ?>
                    <tr>
                        <td>
                            <a href="../students/view.php?id=<?php echo $s['id']; ?>">
                                <?php echo htmlspecialchars($s['first_name'] . ' ' . $s['last_name']); ?>
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($s['email']); ?></td>
                        <td><?php echo htmlspecialchars($s['phone']); ?></td>
                        <td style="color: <?php echo $balance > 0 ? '#dc3545' : '#28a745'; ?>; font-weight: bold;">
                            $<?php echo number_format($balance, 2); ?>
                        </td>
                        <td>
                            <a href="payments.php?student_id=<?php echo $s['id']; ?>" class="btn btn-sm btn-primary">Pay</a>
                            <a href="../students/view.php?id=<?php echo $s['id']; ?>" class="btn btn-sm btn-info">Details</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($students)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">No students found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card" style="margin-top: 2rem;">
    <div class="card-header">
        <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
            <h3><i class="fas fa-history"></i> Recent Payments</h3>
        </div>
        <a href="payments.php" class="btn btn-success btn-sm" style="margin-left: auto; width:auto;"><i class="fas fa-plus"></i> New Payment</a>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Student</th>
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
                        <td><?php echo htmlspecialchars($p['first_name'] . ' ' . $p['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($p['course_name'] . ' (' . $p['semester'] . ' ' . $p['academic_year'] . ')'); ?></td>
                        <td><?php echo htmlspecialchars($p['receipt_number']); ?></td>
                        <td><?php echo htmlspecialchars($p['payment_method']); ?></td>
                        <td style="color: #28a745; font-weight: bold;">+$<?php echo number_format($p['amount_paid'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($payments)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">No payments recorded yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../../../includes/footer.php'; ?>
