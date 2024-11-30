<?php
require_once('../includes/db_connect.php');

$data = json_decode(file_get_contents('php://input'), true);

try {
    // First check if the room has any bookings
    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE room_id = ?");
    $checkStmt->execute([$data['room_id']]);
    $hasBookings = $checkStmt->fetchColumn() > 0;

    if ($hasBookings) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Не може да изтриете стаята: Има активни резервации'
        ]);
        exit;
    }

    // If no bookings exist, proceed with deletion
    $stmt = $pdo->prepare("DELETE FROM rooms WHERE room_id = ?");
    $stmt->execute([$data['room_id']]);

    if ($stmt->rowCount() > 0) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    } else {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Не е намерена стая'
        ]);
    }
} catch(PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Грешка в базата данни при изтриване на стая: ' . $e->getMessage()
    ]);
}
?>