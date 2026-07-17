<?php
// modules/students/list.php
$page_title = 'Students';
include __DIR__ . '/../../includes/header.php';
require_login();

// Fetch students with search
$search = $_GET['search'] ?? '';

if (!empty($search)) {
    $stmt = $pdo->prepare("SELECT * FROM students WHERE first_name LIKE ? OR last_name LIKE ? OR email LIKE ? ORDER BY last_name, first_name");
    $stmt->execute(["%$search%", "%$search%", "%$search%"]);
    $students = $stmt->fetchAll();
} else {
    $students = $studentObj->getAll();
}

// Delete logic if submitted via POST form or API
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['id'] ?? 0;
    if ($studentObj->delete($id)) {
        header("Location: list.php?msg=deleted");
        exit();
    }
}
?>

<div class="card">
    <div class="card-header">
        <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
            <h3>Students List</h3>
            <form action="" method="GET" style="display: flex; gap: 0.5rem; margin-left: auto;">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"
                    placeholder="Search students..." class="form-control" style="width: 200px; padding: 0.4rem;">
                <button type="submit" class="btn btn-secondary btn-sm" style="width: auto;">Search</button>
                <?php if ($search): ?>
                    <a href="list.php" class="btn btn-sm" style="background: #e5e7eb; width: auto;">Clear</a>
                <?php endif; ?>
            </form>
        </div>
        <a href="add.php" class="btn btn-primary btn-sm" style="width: auto; margin-left: 1rem;"><i
                class="fas fa-plus"></i> Add Student</a>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Enrollment Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $s): ?>
                    <tr>
                        <td><?php echo $s['id']; ?></td>
                        <td><?php echo htmlspecialchars($s['first_name'] . ' ' . $s['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($s['email']); ?></td>
                        <td><?php echo htmlspecialchars($s['phone']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($s['enrollment_date'])); ?></td>
                        <td>
                            <span class="badge badge-<?php echo $s['status'] === 'active' ? 'success' : ($s['status'] === 'graduated' ? 'primary' : 'warning'); ?>">
                                <?php echo ucfirst($s['status']); ?>
                            </span>
                        </td>
                        <td>
                            <a href="view.php?id=<?php echo $s['id']; ?>" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                            <a href="edit.php?id=<?php echo $s['id']; ?>" class="btn btn-secondary btn-sm"><i class="fas fa-edit"></i></a>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this student?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $s['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($students)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">No students found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
