<?php
require_once('../includes/db_connect.php');

$data = json_decode(file_get_contents('php://input'), true);

try {
    $query = "
        SELECT r.*
        FROM rooms r
        WHERE r.room_type = :room_type
        AND NOT EXISTS (
            SELECT 1 
            FROM bookings b 
            WHERE b.room_id = r.room_id
            AND (
                (b.start_date <= :end_date AND b.end_date >= :start_date)
            )
        )
    ";
    
    $params = [
        ':room_type' => $data['room_type'],
        ':start_date' => $data['start_date'],
        ':end_date' => $data['end_date']
    ];

    if ($data['has_terrace']) {
        $query .= " AND r.has_terrace = 1";
    }
    if ($data['has_bathtub']) {
        $query .= " AND r.has_bathtub = 1";
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($rooms);
} catch(PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}
?>