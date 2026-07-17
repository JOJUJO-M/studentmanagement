<?php
// modules/fees/payments.php
$page_title = 'Record Payment';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/auth.php';
require_login();

$students = $studentObj->getAll();
$structures = $feeObj->getStructures();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'student_id' => (int) $_POST['student_id'],
        'fee_structure_id' => (int) $_POST['fee_structure_id'],
        'amount_paid' => (float) $_POST['amount_paid'],
        'receipt_number' => trim($_POST['receipt_number']),
        'payment_method' => trim($_POST['payment_method'])
    ];

    if (empty($data['receipt_number']) || $data['amount_paid'] <= 0) {
        $error = 'Receipt number and valid amount are required.';
    } else {
        try {
            if ($feeObj->recordPayment($data)) {
                header("Location: reports.php?msg=payment_recorded");
                exit();
            }
        } catch (Exception $e) {
            $error = 'Failed to record payment. Receipt number might already exist.';
        }
    }
}

include __DIR__ . '/../../includes/header.php';
?>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">
        <h3><i class="fas fa-money-check-alt"></i> Record Fee Payment</h3>
        <a href="reports.php" class="btn btn-secondary btn-sm" style="width:auto;">View Reports</a>
    </div>
    <div class="card-body">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Student *</label>
                <select name="student_id" class="form-control" required>
                    <option value="">-- Select Student --</option>
                    <?php foreach ($students as $s): ?>
                        <option value="<?php echo $s['id']; ?>">
                            <?php echo htmlspecialchars($s['first_name'] . ' ' . $s['last_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Fee Structure (Course/Semester) *</label>
                <select name="fee_structure_id" class="form-control" required>
                    <option value="">-- Select Fee --</option>
                    <?php foreach ($structures as $st): ?>
                        <option value="<?php echo $st['id']; ?>">
                            <?php echo htmlspecialchars($st['course_code'] . ' - ' . $st['semester'] . ' ' . $st['academic_year'] . ' (TZS ' . number_format($st['amount'], 2) . ')'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Amount Paid (TZS) *</label>
                <input type="number" step="0.01" name="amount_paid" class="form-control" required min="0.01">
            </div>
            <div class="form-group">
                <label>Receipt Number *</label>
                <input type="text" name="receipt_number" class="form-control" required placeholder="e.g. REC-12345" value="<?php echo 'REC-' . strtoupper(uniqid()); ?>">
            </div>
            <div class="form-group">
                <label>Payment Method</label>
                <select name="payment_method" class="form-control">
                    <option value="Cash">Cash</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                    <option value="Mobile Money">Mobile Money</option>
                    <option value="Credit Card">Credit Card</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success" style="margin-top: 1rem;"><i class="fas fa-save"></i> Submit Payment</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
