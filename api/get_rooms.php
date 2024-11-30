<?php
require_once('../includes/db_connect.php');

try {
    $stmt = $pdo->query("SELECT * FROM rooms ORDER BY room_number");
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($rooms);
} catch(PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}
?>