<?php
// modules/courses/list.php
$page_title = 'Courses';
include __DIR__ . '/../../includes/header.php';
require_login();

// Fetch courses
$courses = $courseObj->getAll();

// Delete logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['id'] ?? 0;
    if ($courseObj->delete($id)) {
        header("Location: list.php?msg=deleted");
        exit();
    }
}
?>

<div class="card">
    <div class="card-header">
        <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
            <h3>Courses List</h3>
        </div>
        <a href="add.php" class="btn btn-primary btn-sm" style="width: auto; margin-left: auto;"><i
                class="fas fa-plus"></i> Add Course</a>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Credits</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($courses as $c): ?>
                    <tr>
                        <td><?php echo $c['id']; ?></td>
                        <td><?php echo htmlspecialchars($c['course_code']); ?></td>
                        <td><?php echo htmlspecialchars($c['course_name']); ?></td>
                        <td><?php echo $c['credits']; ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $c['id']; ?>" class="btn btn-secondary btn-sm"><i class="fas fa-edit"></i></a>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this course?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($courses)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">No courses found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
