<?php
require_once('../includes/db_connect.php');

$data = json_decode(file_get_contents('php://input'), true);

try {
    $stmt = $pdo->prepare("DELETE FROM bookings WHERE booking_id = ?");
    $stmt->execute([$data['booking_id']]);
    
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
} catch(PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>