<?php
require_once('../includes/db_connect.php');

$data = json_decode(file_get_contents('php://input'), true);

try {
    $stmt = $pdo->prepare("INSERT INTO rooms (room_number, room_type, has_terrace, has_bathtub) VALUES (?, ?, ?, ?)");
    
    $stmt->execute([
        $data['room_number'],
        $data['room_type'],
        $data['has_terrace'] ? 1 : 0,
        $data['has_bathtub'] ? 1 : 0
    ]);
    
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
} catch(PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>