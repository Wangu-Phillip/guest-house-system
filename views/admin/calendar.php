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

<!-- CREATE BOOKING MODAL -->
<div class="modal fade" id="createBookingModal" tabindex="-1" aria-labelledby="createBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createBookingModalLabel">Create Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../../backend/save_booking.php" method="post">
                <div class="modal-body">
                    <!-- Guest Details -->
                    <div id="section1" class="row" style="display: block;">
                        <h5>Guest Details</h5>
                        <div class="row">
                            <!-- Column 1 -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="addFirstname" class="form-label">First Name</label>
                                    <input type="text" class="form-control form-control-sm" id="addFirstname" name="firstname" required>
                                </div>
                                <div class="mb-3">
                                    <label for="addLastname" class="form-label">Last Name</label>
                                    <input type="text" class="form-control form-control-sm" id="addLastname" name="lastname" required>
                                </div>
                                <div class="mb-3">
                                    <label for="addOmang" class="form-label">Omang ID</label>
                                    <input type="text" class="form-control form-control-sm" id="addOmang" name="omang" required>
                                </div>
                                <div class="mb-3">
                                    <label for="addPhone" class="form-label">Phone</label>
                                    <input type="text" class="form-control form-control-sm" id="addPhone" name="phone" required>
                                </div>
                            </div>

                            <!-- Column 2 -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="addEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control form-control-sm" id="addEmail" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="addAddress" class="form-label">Address</label>
                                    <input type="text" class="form-control form-control-sm" id="addAddress" name="address" required>
                                </div>
                                <div class="mb-3">
                                    <label for="addCitizenship" class="form-label">Citizenship</label>
                                    <input type="text" class="form-control form-control-sm" id="addCitizenship" name="citizenship" required>
                                </div>
                                <div class="mb-3">
                                    <label for="addCountry" class="form-label">Country</label>
                                    <input type="text" class="form-control form-control-sm" id="addCountry" name="country" required>
                                </div>
                            </div>
                        </div>

                        <!-- Single Full-Width Row -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="addCompany" class="form-label">Company</label>
                                    <input type="text" class="form-control form-control-sm" id="addCompany" name="company">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="addCarReg" class="form-label">Car Registration Number</label>
                                    <input type="text" class="form-control form-control-sm" id="addCarReg" name="car_registration_no" required>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary btn-sm" onclick="showSection(2)">Next</button>
                        </div>
                    </div>

                    <!-- Next of Kin Details -->
                    <div id="section2" class="row" style="display: none;">
                        <h5>Next of Kin Details</h5>
                        <div class="mb-3">
                            <label for="kinFirstname" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="kinFirstname" name="kin_firstname" required>
                        </div>
                        <div class="mb-3">
                            <label for="kinLastname" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="kinLastname" name="kin_lastname" required>
                        </div>
                        <div class="mb-3">
                            <label for="kinPhone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="kinPhone" name="kin_phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="kinCell" class="form-label">Cell</label>
                            <input type="text" class="form-control" id="kinCell" name="kin_cell" required>
                        </div>
                        <div class="mb-3">
                            <label for="kinAddress" class="form-label">Address</label>
                            <input type="text" class="form-control" id="kinAddress" name="kin_address" required>
                        </div>
                        <div class="mb-3">
                            <label for="kinEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="kinEmail" name="kin_email" required>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" onclick="showSection(1)">Previous</button>
                            <button type="button" class="btn btn-primary" onclick="showSection(3)">Next</button>
                        </div>
                    </div>

                    <!-- Booking Details -->
                    <div id="section3" class="row" style="display: none;">
                        <h5>Booking Details</h5>
                        <div class="mb-3">
                            <label for="addRoomNumber" class="form-label">Room Number</label>
                            <select class="form-select" id="addRoomNumber" name="roomNo" required>
                                <?php
                                $rooms_query = $conn->query("SELECT room_id, room_Number, price FROM rooms");
                                while ($room = $rooms_query->fetch_assoc()) {
                                    echo "<option value='{$room['room_id']}' data-price='{$room['price']}'>{$room['room_Number']} - Price: {$room['price']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="addAmount" class="form-label"></label>
                            <input type="hidden" class="form-control" id="addAmount" name="amount" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="addDate" class="form-label"></label>
                            <input type="hidden" class="form-control" id="addDate" name="datebooked">
                        </div>
                        <div class="mb-3">
                            <label for="addNumberOfGuests" class="form-label">Number of Guests</label>
                            <input type="number" class="form-control" id="addNumberOfGuests" name="number_of_guests" required>
                        </div>
                        <div class="mb-3">
                            <label for="addNumberOfNights" class="form-label">Number of Nights</label>
                            <input type="number" class="form-control" id="addNumberOfNights" name="number_of_nights" required>
                        </div>
                        <div class="mb-3">
                            <label for="addStatus" class="form-label">Status</label>
                            <select class="form-select" id="addStatus" name="status" required>
                                <option value="" disabled selected>Status</option>
                                <option value="Pending">Pending</option>
                                <option value="Cancelled">Cancelled</option>
                                <option value="Active">Active</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="addPaymentMethod" class="form-label">Payment Method</label>
                            <select class="form-select" id="addPaymentMethod" name="payment_method" required>
                                <option value="" disabled selected>Select a payment method</option>
                                <option value="Cash">Cash</option>
                                <option value="Credit Card">Credit Card</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="addCheckIn" class="form-label">Check-In</label>
                            <input type="datetime-local" class="form-control" id="addCheckIn" name="check_in">
                        </div>
                        <div class="mb-3">
                            <label for="addCheckOut" class="form-label">Check-Out</label>
                            <input type="datetime-local" class="form-control" id="addCheckOut" name="check_out">
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" onclick="showSection(2)">Previous</button>
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<!-- CALENDAR DISPLAY -->
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <button id="prevMonth" class="btn btn-dark">Previous</button>
        <h2 id="currentMonth"></h2>
        <button id="nextMonth" class="btn btn-dark">Next</button>
    </div>
    <div id="calendar"></div>
</div>
<br><br>

<!-- CALENDAR STYLING -->
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
    const createBookingModal = new bootstrap.Modal(document.getElementById("createBookingModal"));
    const addDateInput = document.getElementById("addDate"); // Date input in the modal

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

            // Add click event to open the modal and set the date
            dayEl.addEventListener("click", function () {
                const selectedDate = dayEl.dataset.date; // Get the selected date
                addDateInput.value = selectedDate; // Set the date in the modal's date input
                createBookingModal.show(); // Show the modal
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

// FUNCTION TO CREATE BOOKING
function createBooking() {
        // Show the modal
        var editModal = new bootstrap.Modal(document.getElementById('createBookingModal'));
        editModal.show();
    }

    // MODAL SECTION CONTROLLER
    function showSection(section) {
        for (let i = 1; i <= 3; i++) {
            document.getElementById(`section${i}`).style.display = i === section ? 'block' : 'none';
        }
    }
</script>

<?php include '../../components/footer.php'; ?>