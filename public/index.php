<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Резервация</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <h1 class="text-center mb-4">Резервация на стая</h1>
    
    <!-- Форма за търсене -->
    <div class="card mb-4">
        <div class="card-body">
            <form id="searchForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Дата на настаняване</label>
                        <input type="date" class="form-control" id="startDate" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Дата на напускане</label>
                        <input type="date" class="form-control" id="endDate" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Тип</label>
                        <select class="form-control" id="roomType" required>
                            <option value="SINGLE">Единична стая</option>
                            <option value="DOUBLE">Двойна стая</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Предпочитания</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="hasTerrace">
                            <label class="form-check-label">Тераса</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="hasBathtub">
                            <label class="form-check-label">Вана</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="button" class="btn btn-primary d-block w-100" id="searchFormButton">Търси</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Available Rooms -->
    <div id="availableRooms" style="display: none;">
        <h3>Налични Стаи</h3>
        <div class="row" id="roomsList">
            <!-- Тук ще покажем стаите -->
        </div>
    </div>

    <!-- Форма за резервация -->
    <div id="bookingForm" class="card" style="display: none;">
        <div class="card-body">
            <h3>Завършване на резервация</h3>
            <form id="confirmBookingForm">
                <input type="hidden" id="selectedRoomId">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Три имена</label>
                        <input type="text" class="form-control" id="visitorName" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Телефон за връзка</label>
                        <input type="tel" class="form-control" id="phoneNumber" required>
                    </div>
                    <div class="col-12">
                        <button type="button" class="btn btn-success" id="confirmBookingButton">Резервирай</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('searchFormButton').addEventListener('click', function(e) {
        e.preventDefault();
        searchAvailableRooms();
    });

    document.getElementById('confirmBookingButton').addEventListener('click', function(e) {
        e.preventDefault();
        confirmBooking();
    });
});

function searchAvailableRooms() {
    const searchData = {
        start_date: document.getElementById('startDate').value,
        end_date: document.getElementById('endDate').value,
        room_type: document.getElementById('roomType').value,
        has_terrace: document.getElementById('hasTerrace').checked,
        has_bathtub: document.getElementById('hasBathtub').checked
    };

    fetch('/api/search_rooms.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(searchData)
    })
    .then(response => response.json())
    .then(data => {
        const roomsList = document.getElementById('roomsList');
        roomsList.innerHTML = '';
        
        data.forEach(room => {
            roomsList.innerHTML += `
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Стая ${room.room_number}</h5>
                            <p class="card-text">
                                Тип: ${room.room_type === "SINGLE" ? "Единична" : "Двойна"}<br>
                                С тераса: ${room.has_terrace ? 'Да' : 'Не'}<br>
                                С вана: ${room.has_bathtub ? 'Да' : 'Не'}
                            </p>
                            <button class="btn btn-primary" onclick="selectRoom(${room.room_id})">
                                Избери
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });

        document.getElementById('availableRooms').style.display = 'block';
    });
}

function selectRoom(roomId) {
    document.getElementById('selectedRoomId').value = roomId;
    document.getElementById('bookingForm').style.display = 'block';
    document.getElementById('bookingForm').scrollIntoView({ behavior: 'smooth' });
}

function confirmBooking() {
    const bookingData = {
        room_id: document.getElementById('selectedRoomId').value,
        start_date: document.getElementById('startDate').value,
        end_date: document.getElementById('endDate').value,
        visitor_name: document.getElementById('visitorName').value,
        phone_number: document.getElementById('phoneNumber').value
    };

    fetch('/api/create_booking.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(bookingData)
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            alert('Успешна резервация!');
            window.location.reload();
        } else {
            alert('Грешка при резервация: ' + data.message);
        }
    });
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>