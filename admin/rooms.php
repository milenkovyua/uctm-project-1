<?php
require_once('../includes/db_connect.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Стаи</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
    <div class="row">
    <?php include('../includes/sidebar.php'); ?>
            <div class="col-md-9 p-4">
            <h2>Управление на стаите</h2>
            
            <!-- Добавяне на нова стая -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Добавяне на нова стая</h5>
                    <form id="addRoomForm">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" class="form-control" id="roomNumber" placeholder="Стая №" required>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" id="roomType" required>
                                    <option value="SINGLE">Единична</option>
                                    <option value="DOUBLE">Двойна</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="hasTerrace">
                                    <label class="form-check-label">Тераса</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="hasBathtub">
                                    <label class="form-check-label">Вана</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">Добави</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Таблица на стаите -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Налични стаи</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>№</th>
                                <th>Тип</th>
                                <th>С тераса</th>
                                <th>С вана</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody id="roomsTableBody">
                            <!-- Тук ще покажем стаите -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadRooms();
    
    document.getElementById('addRoomForm').addEventListener('submit', function(e) {
        e.preventDefault();
        addRoom();
    });
});

function loadRooms() {
    fetch('/api/get_rooms.php')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('roomsTableBody');
            tbody.innerHTML = '';
            
            data.forEach(room => {
                tbody.innerHTML += `
                    <tr>
                        <td>${room.room_number}</td>
                        <td>${room.room_type === "SINGLE" ? "Единична" : "Двойна"}</td>
                        <td>${room.has_terrace ? 'Да' : 'Да'}</td>
                        <td>${room.has_bathtub ? 'Да' : 'Не'}</td>
                        <td>
                            <button class="btn btn-sm btn-danger" onclick="deleteRoom(${room.room_id})">Изтрий</button>
                        </td>
                    </tr>
                `;
            });
        });
}

function addRoom() {
    const roomData = {
        room_number: document.getElementById('roomNumber').value,
        room_type: document.getElementById('roomType').value,
        has_terrace: document.getElementById('hasTerrace').checked,
        has_bathtub: document.getElementById('hasBathtub').checked
    };

    fetch('/api/add_room.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(roomData)
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            loadRooms();
            document.getElementById('addRoomForm').reset();
        } else {
            alert('Грешка при добавяне на стая: ' + data.message);
        }
    });
}

function deleteRoom(roomId) {
    if(confirm('Сигурни ли сте, че желаете да изтриете тази стая?')) {
        fetch('/api/delete_room.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({room_id: roomId})
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                loadRooms();
            } else {
                alert('Грешка при изтриване на стая: ' + data.message);
            }
        });
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>