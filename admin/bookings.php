<?php
require_once('../includes/db_connect.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <?php include('../includes/sidebar.php'); ?>
        <div class="col-md-9 p-4">
            <h2>Управление на резервации</h2>
            
            <!-- Филтри -->
            <div class="card mb-4">
                <div class="card-body">
                    <form id="filterForm" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Начална Дата</label>
                            <input type="date" class="form-control" id="startDate" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Крайна Дата</label>
                            <input type="date" class="form-control" id="endDate" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Стая № (опционално)</label>
                            <input type="text" class="form-control" id="roomNumber">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-primary d-block" id="submitButton">Търси</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Таблица на резервациите -->
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Стая №</th>
                                <th>Тип</th>
                                <th>От Дата</th>
                                <th>До Дата</th>
                                <th>Посетител</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody id="bookingsTableBody">
                            <!-- Тук ще се показват резервацийте -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('submitButton').addEventListener('click', function(e) {
        e.preventDefault();
        loadBookings();
    });
});

function loadBookings() {
    const filters = {
        start_date: document.getElementById('startDate').value,
        end_date: document.getElementById('endDate').value,
        room_number: document.getElementById('roomNumber').value
    };

    fetch('/api/get_bookings.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(filters)
    })
    .then(response => response.json())
    .then(data => {
        const tbody = document.getElementById('bookingsTableBody');
        tbody.innerHTML = '';
        
        data.forEach(booking => {
            tbody.innerHTML += `
                <tr>
                    <td>${booking.room_number}</td>
                    <td>${booking.room_type === "SINGLE" ? "Единична" : "Двойна"}</td>
                    <td>${booking.start_date}</td>
                    <td>${booking.end_date}</td>
                    <td>${booking.visitor_name}</td>
                    <td>
                        <button class="btn btn-sm btn-danger" onclick="deleteBooking(${booking.booking_id})">Прекрати</button>
                    </td>
                </tr>
            `;
        });
    });
}

function deleteBooking(bookingId) {
    if(confirm('Сигурни ли сте, че желаете да отмените тази резервация?')) {
        fetch('/api/delete_booking.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({booking_id: bookingId})
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                loadBookings();
            } else {
                alert('Грешка при отмяна на резервация: ' + data.message);
            }
        });
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>