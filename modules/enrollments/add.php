<?php
// modules/enrollments/add.php
$page_title = 'Enroll Student';
include __DIR__ . '/../../includes/header.php';
require_login();

$students = $studentObj->getAll();
$courses = $courseObj->getAll();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = (int) $_POST['student_id'];
    $course_id = (int) $_POST['course_id'];
    $semester = trim($_POST['semester']);
    $academic_year = trim($_POST['academic_year']);

    if (!$student_id || !$course_id || empty($semester) || empty($academic_year)) {
        $error = 'All fields are required.';
    } else {
        if ($enrollmentObj->create($student_id, $course_id, $semester, $academic_year)) {
            header("Location: list.php?msg=enrolled");
            exit();
        } else {
            $error = 'Failed to enroll. Student might already be enrolled in this course for the selected term.';
        }
    }
}
?>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">
        <h3><i class="fas fa-user-graduate"></i> Enroll Student in Course</h3>
        <a href="list.php" class="btn btn-secondary btn-sm" style="width:auto;">Back to List</a>
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
                            <?php echo htmlspecialchars($s['first_name'] . ' ' . $s['last_name'] . ' (' . $s['email'] . ')'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Course *</label>
                <select name="course_id" class="form-control" required>
                    <option value="">-- Select Course --</option>
                    <?php foreach ($courses as $c): ?>
                        <option value="<?php echo $c['id']; ?>">
                            <?php echo htmlspecialchars($c['course_code'] . ' - ' . $c['course_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Semester *</label>
                <select name="semester" class="form-control" required>
                    <option value="Semester 1">Semester 1</option>
                    <option value="Semester 2">Semester 2</option>
                    <option value="Summer">Summer</option>
                </select>
            </div>
            <div class="form-group">
                <label>Academic Year *</label>
                <input type="text" name="academic_year" class="form-control" required placeholder="e.g. 2026/2027" value="<?php echo date('Y') . '/' . (date('Y') + 1); ?>">
            </div>
            <button type="submit" class="btn btn-primary" style="margin-top: 1rem;">Complete Enrollment</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
