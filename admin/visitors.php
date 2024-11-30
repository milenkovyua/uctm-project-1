<?php
require_once('../includes/db_connect.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Посетители</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <?php include('../includes/sidebar.php'); ?>
        <div class="col-md-9 p-4">
            <h2>Управление на посетители</h2>
            
            <!-- Филтри -->
            <div class="card mb-4">
                <div class="card-body">
                    <form id="filterForm" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">От Дата</label>
                            <input type="date" class="form-control" id="startDate" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">До Дата</label>
                            <input type="date" class="form-control" id="endDate" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-primary d-block" id="searchButton">Търси</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Таблица на посетители -->
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Три Имена</th>
                                <th>Телефон</th>
                                <th>Стая №</th>
                                <th>От Дата</th>
                                <th>До Дата</th>
                            </tr>
                        </thead>
                        <tbody id="visitorsTableBody">
                            <!-- Тук ще покажем посетителите -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('searchButton').addEventListener('click', function(e) {
        e.preventDefault();
        loadVisitors();
    });
});

function loadVisitors() {
    const filters = {
        start_date: document.getElementById('startDate').value,
        end_date: document.getElementById('endDate').value
    };

    fetch('/api/get_visitors.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(filters)
    })
    .then(response => response.json())
    .then(data => {
        const tbody = document.getElementById('visitorsTableBody');
        tbody.innerHTML = '';
        
        data.forEach(visitor => {
            tbody.innerHTML += `
                <tr>
                    <td>${visitor.full_name}</td>
                    <td>${visitor.phone_number}</td>
                    <td>${visitor.room_number}</td>
                    <td>${visitor.start_date}</td>
                    <td>${visitor.end_date}</td>
                </tr>
            `;
        });
    });
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>