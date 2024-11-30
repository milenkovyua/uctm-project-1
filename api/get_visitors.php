<?php
require_once('../includes/db_connect.php');

$data = json_decode(file_get_contents('php://input'), true);

try {
    $query = "
        SELECT 
            v.full_name,
            v.phone_number,
            r.room_number,
            b.start_date,
            b.end_date
        FROM visitors v
        JOIN bookings b ON v.visitor_id = b.visitor_id
        JOIN rooms r ON b.room_id = r.room_id
        WHERE b.start_date >= :start_date 
        AND b.end_date <= :end_date
        ORDER BY b.start_date
    ";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':start_date' => $data['start_date'],
        ':end_date' => $data['end_date']
    ]);
    
    $visitors = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($visitors);
} catch(PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}
?>