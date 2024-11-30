<?php
session_start();

// Database initialization
function initializeDatabase($pdo) {
    try {
        // Read the SQL file
        $sql = file_get_contents('../includes/database.sql');
        
        // Execute each SQL statement
        $pdo->exec($sql);
        
        return true;
    } catch(PDOException $e) {
        error_log("Database initialization error: " . $e->getMessage());
        return false;
    }
}

// Add database connection and initialization
require_once('../includes/db_connect.php');
initializeDatabase($pdo);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Панел</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <?php include('../includes/sidebar.php'); ?>
        <!-- Основна част -->
        <div class="col-md-9 p-4">
            <h2>Добре дошли в системата за управление на хотели</h2>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>