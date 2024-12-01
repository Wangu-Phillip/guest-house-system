<?php
include '../../components/header.php';
include '../../components/navbar.php';
include '../../backend/db_connection.php';

// Fetch bookings from the database
$sql = "SELECT b.booking_id, DATE(b.date) AS date, CONCAT(g.firstname, ' ', g.lastname) AS guest_name, r.room_number
        FROM bookings b
        LEFT JOIN guests g ON b.guest_id = g.guest_id
        LEFT JOIN rooms r ON b.room_id = r.room_id
        WHERE (b.check_in IS NOT NULL OR b.check_in != '0000-00-00 00:00:00')
        AND (b.check_out IS NULL OR b.check_out = '0000-00-00 00:00:00')";
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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <button id="prevMonth" class="btn btn-dark">Previous</button>
        <h2 id="currentMonth"></h2>
        <button id="nextMonth" class="btn btn-dark">Next</button>
    </div>
    <div id="calendar"></div>
</div>
<br><br>

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
        box-shadow: -1px 8px 10px rgba(0, 0, 0, 0.1);
        overflow-y: auto;
        max-height: 100px;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .day::-webkit-scrollbar {
        display: none;
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
    document.addEventListener("DOMContentLoaded", function () {
        const bookings = <?= json_encode($bookings) ?>; // Booking data from PHP
        const calendarEl = document.getElementById("calendar");
        const currentMonthEl = document.getElementById("currentMonth");
        const prevMonthBtn = document.getElementById("prevMonth");
        const nextMonthBtn = document.getElementById("nextMonth");

        let currentDate = new Date(); // Initialize to today's date

        function renderCalendar(date) {
            calendarEl.innerHTML = ""; // Clear the existing calendar
            const year = date.getFullYear();
            const month = date.getMonth(); // 0-indexed for JavaScript (0 = January)
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const firstDay = new Date(year, month, 1).getDay();

            // Set the current month and year in the header
            currentMonthEl.textContent = date.toLocaleDateString("en-US", {
                year: "numeric",
                month: "long",
            });

            // Render blank days for the previous month
            for (let i = 0; i < firstDay; i++) {
                const blankEl = document.createElement("div");
                blankEl.classList.add("day");
                blankEl.style.background = "#f9f9f9"; // Lighter background for blank days
                calendarEl.appendChild(blankEl);
            }

            // Render calendar days
            for (let i = 1; i <= daysInMonth; i++) {
                const dayEl = document.createElement("div");
                dayEl.classList.add("day");
                dayEl.dataset.date = `${year}-${String(month + 1).padStart(2, "0")}-${String(i).padStart(2, "0")}`;

                // Highlight today's date
                if (new Date().toDateString() === new Date(year, month, i).toDateString()) {
                    dayEl.style.background = "lightblue"; // Highlight the current day
                }

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

            // Fill remaining days to complete the week (if needed)
            const totalDays = firstDay + daysInMonth;
            const remainingDays = 7 - (totalDays % 7);
            if (remainingDays < 7) {
                for (let i = 0; i < remainingDays; i++) {
                    const blankEl = document.createElement("div");
                    blankEl.classList.add("day");
                    blankEl.style.background = "#f9f9f9"; // Lighter background for blank days
                    calendarEl.appendChild(blankEl);
                }
            }
        }

        // Event listeners for navigation buttons
        prevMonthBtn.addEventListener("click", function () {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar(currentDate);
        });

        nextMonthBtn.addEventListener("click", function () {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar(currentDate);
        });

        // Initial render
        renderCalendar(currentDate);
    });
</script>

<?php include '../../components/footer.php'; ?>