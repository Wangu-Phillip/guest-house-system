<?php
include '../../components/header.php';
include '../../components/navbar.php';
include '../../backend/db_connection.php';

// Fetch bookings from the database
$sql = "SELECT b.booking_id, DATE(b.date) AS date, CONCAT(g.firstname, ' ', g.lastname) AS guest_name, r.room_number
        FROM bookings b
        LEFT JOIN guests g ON b.guest_id = g.guest_id
        LEFT JOIN rooms r ON b.room_id = r.room_id";
$result = $conn->query($sql);

// Prepare bookings for the calendar
$bookings = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bookings[] = [
            'title' => $row['guest_name'] . ' - Room ' . $row['room_number'],
            'date' => $row['date'], // Date is now formatted as YYYY-MM-DD
        ];
    }
}

?>

<div class="container mt-5">
    <h2 class="mb-4">Bookings Calendar</h2>
    <div id="calendar"></div>
</div>

<style>
    #calendar {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        grid-auto-rows: 100px;
        gap: 1px;
        background: #fff;
        border: #fff 5px solid;
        border-radius: 5px;

    }

    .day {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 5px;
        position: relative;
        /* box-shadow: offset-x offset-y blur-radius spread-radius color; */
        box-shadow: -1px 8px 10px rgba(0, 0, 0, 0.1);

    }

    .day .date {
        font-weight: bold;
        margin-bottom: 5px;
    }

    .event {
        background: #28a745;
        color: #fff;
        padding: 2px 5px;
        margin-top: 5px;
        border-radius: 3px;
        font-size: 12px;
        box-shadow: 5px 8px 15px rgba(0, 0, 0, 0.1);

    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const bookings = <?= json_encode($bookings) ?>; // Booking data from PHP
        const calendarEl = document.getElementById("calendar");
        const today = new Date();
        const year = today.getFullYear();
        const month = today.getMonth(); // 0-indexed for JavaScript (0 = January)

        // Days in the month
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        // Render calendar days
        for (let i = 1; i <= daysInMonth; i++) {
            const dayEl = document.createElement("div");
            dayEl.classList.add("day");
            dayEl.dataset.date = `${year}-${String(month + 1).padStart(2, "0")}-${String(i).padStart(2, "0")}`;

            // Add day number
            const dateEl = document.createElement("div");
            dateEl.classList.add("date");
            dateEl.innerText = i;
            dayEl.appendChild(dateEl);

            // Add events if they exist
            bookings.forEach((booking) => {
                if (booking.date === dayEl.dataset.date) {
                    const eventEl = document.createElement("div");
                    eventEl.classList.add("event");
                    eventEl.innerText = booking.title;
                    dayEl.appendChild(eventEl);
                }
            });

            calendarEl.appendChild(dayEl);
        }
    });
</script>

<?php include '../../components/footer.php'; ?>