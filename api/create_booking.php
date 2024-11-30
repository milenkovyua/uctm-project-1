<?php
require_once('../includes/db_connect.php');

$data = json_decode(file_get_contents('php://input'), true);

try {
    $pdo->beginTransaction();

    // Create visitor
    $stmt = $pdo->prepare("INSERT INTO visitors (full_name, phone_number) VALUES (?, ?)");
    $stmt->execute([$data['visitor_name'], $data['phone_number']]);
    $visitorId = $pdo->lastInsertId();

    // Create booking
    $stmt = $pdo->prepare("INSERT INTO bookings (room_id, visitor_id, start_date, end_date) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $data['room_id'],
        $visitorId,
        $data['start_date'],
        $data['end_date']
    ]);

    $pdo->commit();
    
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
} catch(PDOException $e) {
    $pdo->rollBack();
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>