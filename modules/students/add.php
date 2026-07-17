<?php
// modules/students/add.php
$page_title = 'Add Student';
include __DIR__ . '/../../includes/header.php';
require_login();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'first_name' => trim($_POST['first_name']),
        'last_name' => trim($_POST['last_name']),
        'email' => trim($_POST['email']),
        'phone' => trim($_POST['phone']),
        'date_of_birth' => !empty($_POST['date_of_birth']) ? $_POST['date_of_birth'] : null,
        'enrollment_date' => $_POST['enrollment_date'],
        'status' => $_POST['status']
    ];

    if (empty($data['first_name']) || empty($data['last_name']) || empty($data['enrollment_date'])) {
        $error = 'Please fill in all required fields.';
    } else {
        if ($studentObj->create($data)) {
            header("Location: list.php?msg=added");
            exit();
        } else {
            $error = 'Failed to add student. Please check inputs.';
        }
    }
}
?>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">
        <h3><i class="fas fa-user-plus"></i> Add New Student</h3>
        <a href="list.php" class="btn btn-secondary btn-sm" style="width:auto;">Back to List</a>
    </div>
    <div class="card-body">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>First Name *</label>
                <input type="text" name="first_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Last Name *</label>
                <input type="text" name="last_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control">
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" class="form-control">
            </div>
            <div class="form-group">
                <label>Date of Birth</label>
                <input type="date" name="date_of_birth" class="form-control">
            </div>
            <div class="form-group">
                <label>Enrollment Date *</label>
                <input type="date" name="enrollment_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="active">Active</option>
                    <option value="suspended">Suspended</option>
                    <option value="graduated">Graduated</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="margin-top: 1rem;">Save Student</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
