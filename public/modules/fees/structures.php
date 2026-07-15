<?php
// modules/fees/structures.php
$page_title = 'Fee Structures';
include __DIR__ . '/../../../includes/header.php';
require_login();

$structures = $feeObj->getStructures();
$courses = $courseObj->getAll();

$error = '';
// Handle Add Structure
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $data = [
        'course_id' => (int) $_POST['course_id'],
        'semester' => trim($_POST['semester']),
        'academic_year' => trim($_POST['academic_year']),
        'amount' => (float) $_POST['amount']
    ];

    if ($data['course_id'] && $data['semester'] && $data['academic_year'] && $data['amount'] >= 0) {
        if ($feeObj->createStructure($data)) {
            header("Location: structures.php?msg=added");
            exit();
        } else {
            $error = 'Failed to create fee structure.';
        }
    } else {
        $error = 'Please fill in all valid fields.';
    }
}

// Handle Delete Structure
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['id'] ?? 0;
    if ($feeObj->deleteStructure($id)) {
        header("Location: structures.php?msg=deleted");
        exit();
    }
}
?>

<div style="display:flex; gap: 2rem; flex-wrap: wrap;">
    <div class="card" style="flex: 1; min-width: 300px;">
        <div class="card-header">
            <h3><i class="fas fa-plus"></i> Add Fee Structure</h3>
        </div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label>Course *</label>
                    <select name="course_id" class="form-control" required>
                        <option value="">-- Select Course --</option>
                        <?php foreach ($courses as $c): ?>
                            <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['course_code']); ?></option>
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
                <div class="form-group">
                    <label>Amount ($) *</label>
                    <input type="number" step="0.01" name="amount" class="form-control" required min="0">
                </div>
                <button type="submit" class="btn btn-primary" style="margin-top: 1rem;">Save Structure</button>
            </form>
        </div>
    </div>

    <div class="card" style="flex: 2; min-width: 400px;">
        <div class="card-header">
            <h3>Existing Fee Structures</h3>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Semester</th>
                        <th>Academic Year</th>
                        <th>Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($structures as $st): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($st['course_code']); ?></td>
                            <td><?php echo htmlspecialchars($st['semester']); ?></td>
                            <td><?php echo htmlspecialchars($st['academic_year']); ?></td>
                            <td>$<?php echo number_format($st['amount'], 2); ?></td>
                            <td>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this structure?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $st['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($structures)): ?>
                        <tr><td colspan="5" style="text-align: center;">No structures found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../../includes/footer.php'; ?>
