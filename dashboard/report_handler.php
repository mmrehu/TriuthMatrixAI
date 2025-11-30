<?php
session_start();
require_once '../database.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$post_id = $input['post_id'] ?? 0;
$report_type = $input['report_type'] ?? '';
$description = $input['description'] ?? '';

if (!$post_id || !$report_type) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO reports (post_id, user_id, report_type, description) VALUES (?, ?, ?, ?)");
    $stmt->execute([$post_id, $_SESSION['user_id'], $report_type, $description]);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>