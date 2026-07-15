<?php
// api/crud.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/auth.php';

header('Content-Type: application/json');
require_login(); // Ensure user is logged in

$action = $_GET['action'] ?? '';
$entity = $_GET['entity'] ?? 'sample_items'; // Default entity

// Security: White-list entities that can be managed via this generic API
$allowed_entities = ['sample_items', 'activity_logs', 'settings'];

// Admins can also manage users and roles
if (has_role('admin')) {
    $allowed_entities[] = 'users';
    $allowed_entities[] = 'roles';
}

if (!in_array($entity, $allowed_entities)) {
    echo json_encode(['success' => false, 'message' => 'Invalid entity or unauthorized access']);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents('php://input'), true) ?? $_POST;

try {
    switch ($action) {
        case 'list':
            $search = $_GET['search'] ?? '';
            if (!empty($search)) {
                $stmt = $pdo->prepare("SELECT * FROM $entity WHERE name LIKE ? OR description LIKE ? ORDER BY id DESC");
                $stmt->execute(["%$search%", "%$search%"]);
            } else {
                $stmt = $pdo->query("SELECT * FROM $entity ORDER BY id DESC");
            }
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
            break;

        case 'get':
            $id = $_GET['id'] ?? 0;
            $stmt = $pdo->prepare("SELECT * FROM $entity WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true, 'data' => $stmt->fetch()]);
            break;

        case 'create':
            if ($entity === 'sample_items') {
                $name = $data['name'] ?? '';
                $desc = $data['description'] ?? '';
                $price = $data['price'] ?? 0;

                $stmt = $pdo->prepare("INSERT INTO sample_items (name, description, price) VALUES (?, ?, ?)");
                $stmt->execute([$name, $desc, $price]);

                // Log activity
                $stmt_log = $pdo->prepare("INSERT INTO activity_logs (user_id, action, details) VALUES (?, ?, ?)");
                $stmt_log->execute([$_SESSION['user_id'], 'Create Item', "Created item: $name"]);

                echo json_encode(['success' => true, 'message' => 'Item created successfully']);
            }
            break;

        case 'update':
            $id = $data['id'] ?? 0;
            if ($entity === 'sample_items') {
                $name = $data['name'] ?? '';
                $desc = $data['description'] ?? '';
                $price = $data['price'] ?? 0;

                $stmt = $pdo->prepare("UPDATE sample_items SET name = ?, description = ?, price = ? WHERE id = ?");
                $stmt->execute([$name, $desc, $price, $id]);

                echo json_encode(['success' => true, 'message' => 'Item updated successfully']);
            }
            break;

        case 'delete':
            $id = $data['id'] ?? $_GET['id'] ?? 0;
            $stmt = $pdo->prepare("DELETE FROM $entity WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true, 'message' => 'Item deleted successfully']);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Action not supported']);
            break;
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
