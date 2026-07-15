<?php
// modules/enrollments/list.php
$page_title = 'Enrollments';
include __DIR__ . '/../../../includes/header.php';
require_login();

// Fetch enrollments
$enrollments = $enrollmentObj->getAll();

// Delete logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['id'] ?? 0;
    if ($enrollmentObj->delete($id)) {
        header("Location: list.php?msg=deleted");
        exit();
    }
}
?>

<div class="card">
    <div class="card-header">
        <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
            <h3>Enrollments List</h3>
        </div>
        <a href="add.php" class="btn btn-primary btn-sm" style="width: auto; margin-left: auto;"><i
                class="fas fa-plus"></i> Enroll Student</a>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Course</th>
                    <th>Semester</th>
                    <th>Academic Year</th>
                    <th>Date Enrolled</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($enrollments as $e): ?>
                    <tr>
                        <td>
                            <a href="../students/view.php?id=<?php echo $e['student_id']; ?>">
                                <?php echo htmlspecialchars($e['first_name'] . ' ' . $e['last_name']); ?>
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($e['course_code'] . ' - ' . $e['course_name']); ?></td>
                        <td><?php echo htmlspecialchars($e['semester']); ?></td>
                        <td><?php echo htmlspecialchars($e['academic_year']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($e['enrollment_date'])); ?></td>
                        <td>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Remove this enrollment?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $e['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($enrollments)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">No enrollments found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../../../includes/footer.php'; ?>
