<?php
// modules/courses/edit.php
$page_title = 'Edit Course';
include __DIR__ . '/../../includes/header.php';
require_login();

$id = $_GET['id'] ?? 0;
$course = $courseObj->getById($id);

if (!$course) {
    echo "<div class='alert alert-danger'>Course not found.</div>";
    include __DIR__ . '/../../includes/footer.php';
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'course_code' => trim($_POST['course_code']),
        'course_name' => trim($_POST['course_name']),
        'description' => trim($_POST['description']),
        'credits' => (int) $_POST['credits']
    ];

    if (empty($data['course_code']) || empty($data['course_name'])) {
        $error = 'Course Code and Name are required.';
    } else {
        try {
            if ($courseObj->update($id, $data)) {
                header("Location: list.php?msg=updated");
                exit();
            }
        } catch (Exception $e) {
            $error = 'Failed to update course. Code might already exist.';
        }
    }
}
?>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">
        <h3><i class="fas fa-edit"></i> Edit Course</h3>
        <a href="list.php" class="btn btn-secondary btn-sm" style="width:auto;">Back to List</a>
    </div>
    <div class="card-body">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Course Code *</label>
                <input type="text" name="course_code" class="form-control" value="<?php echo htmlspecialchars($course['course_code']); ?>" required>
            </div>
            <div class="form-group">
                <label>Course Name *</label>
                <input type="text" name="course_name" class="form-control" value="<?php echo htmlspecialchars($course['course_name']); ?>" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="4"><?php echo htmlspecialchars($course['description'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label>Credits</label>
                <input type="number" name="credits" class="form-control" value="<?php echo $course['credits']; ?>" min="0">
            </div>
            <button type="submit" class="btn btn-primary" style="margin-top: 1rem;">Update Course</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
