<?php
include '../../components/header.php';
include '../../components/navbar.php';
include '../../backend/db_connection.php';

// Fetch bookings from the database
$sql = "SELECT 
            b.booking_id,
            b.date, 
            CONCAT(g.firstname, ' ', g.lastname) AS guest_name, 
            g.phone AS guest_number, 
            g.omang_id AS guest_id, 
            r.room_number, 
            r.price AS amount, 
            b.status, 
            b.check_in, 
            b.check_out 
        FROM bookings b
        LEFT JOIN guests g ON b.guest_id = g.guest_id
        LEFT JOIN rooms r ON b.room_id = r.room_id
        ORDER BY b.date DESC";

$result = $conn->query($sql);
?>


<!-- SUCCESS/ERROR TOAST -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1050;">
    <div id="toastNotification" class="toast align-items-center" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-light">
            <span id="toastIcon" class="me-2"></span>
            <strong id="toastHeading" class="me-auto">Message</strong>
            <small id="toastTime">Just now</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toastMessage">
            <!-- Toast Message -->
        </div>
        <div id="toastProgress" class="progress position-relative bottom-0 start-2 w-100" style="height: 3px;">
            <div class="progress-bar bg-success" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>
</div>

<!-- SEARCH INPUT FIELD -->
<div class="container my-3 mt-5">

    <!-- Button trigger modal -->
    <a href="#" data-bs-toggle="modal" data-bs-target="#createBookingModal">
        <button type="button" class="btn btn-primary text-end">Create Booking</button>
    </a>

    <div class="row d-flex justify-content-end">
        <div class="col-md-4">
            <input
                type="text"
                class="form-control"
                id="searchInput"
                placeholder="Search booking by guest name..."
                onkeyup="searchBooking()" />
        </div>
    </div>
</div>

<!-- BOOKINGS SECTION START -->
<div class="container mt-2">
    <!-- Table Wrapper for Rounded Corners -->
    <div class="rounded border border-secondary shadow-sm" style="overflow: hidden;">
        <table class="table table-hover table-striped mb-0" id="bookingsTable">
            <thead class="table-dark">
                <tr>
                    <th>Date</th>
                    <th>Guest Name</th>
                    <th>Guest Number</th>
                    <th>Guest ID (Omang)</th>
                    <th>Room</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Check-In Date/Time</th>
                    <th>Check-Out Date/Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['date']) ?></td>
                            <td><?= htmlspecialchars($row['guest_name']) ?></td>
                            <td><?= htmlspecialchars($row['guest_number']) ?></td>
                            <td><?= htmlspecialchars($row['guest_id']) ?></td>
                            <td><?= htmlspecialchars($row['room_number']) ?></td>
                            <td><?= htmlspecialchars($row['amount']) ?></td>
                            <td><?= htmlspecialchars($row['status']) ?></td>
                            <td><?= htmlspecialchars($row['check_in']) ?: '-' ?></td>
                            <td><?= htmlspecialchars($row['check_out']) ?: '-' ?></td>
                            <td>
                                <button
                                    type="button"
                                    class="btn btn-warning btn-sm shadow"
                                    onclick="openEditModal(<?= htmlspecialchars(json_encode($row)) ?>)">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <form action="../../backend/delete_booking.php" method="post" style="display: inline;">
                                    <input type="hidden" name="delete" value="<?= $row['booking_id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm shadow">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="text-center text-muted">No bookings found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    /* Add rounded corners and border for table wrapper */
    .rounded {
        border-radius: 10px !important;
        /* Ensure border radius applies */
    }

    /* Optional: Add padding or background styling for the container */
    .rounded {
        background-color: #f8f9fa;
        /* Light grey background */
    }

    /* Optional: Style the table header for consistent design */
    thead.table-dark thead {
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }
</style>


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
                            <label for="addDate" class="form-label">Date Booked</label>
                            <input type="datetime-local" class="form-control" id="addDate" name="datebooked" required>
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

<!-- EDIT BOOKING MODAL -->
<div class="modal fade" id="editBookingModal" tabindex="-1" aria-labelledby="editBookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBookingModalLabel">Edit Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../../backend/update_booking.php" method="post">
                <div class="modal-body">
                    <input type="hidden" name="booking_id" id="editBookingId">

                    <div class="mb-3">
                        <label for="editStatus" class="form-label">Status</label>
                        <select class="form-select" id="editStatus" name="status" required>
                            <option value="Pending">Pending</option>
                            <option value="Checked Out">Checked out</option>
                            <option value="Cancelled">Cancelled</option>
                            <option value="Checked In">Checked In</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="editCheckIn" class="form-label">Check-In</label>
                        <input type="datetime-local" class="form-control" id="editCheckIn" name="check_in">
                    </div>

                    <div class="mb-3">
                        <label for="editCheckOut" class="form-label">Check-Out</label>
                        <input type="datetime-local" class="form-control" id="editCheckOut" name="check_out">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save Changes</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!--Bootstrap JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // MODAL SECTION CONTROLLER
    function showSection(section) {
        for (let i = 1; i <= 3; i++) {
            document.getElementById(`section${i}`).style.display = i === section ? 'block' : 'none';
        }
    }

    // Automatically populate price field when room is selected
    document.getElementById("addRoomNumber").addEventListener("change", function() {
        const selectedOption = this.options[this.selectedIndex];
        const price = selectedOption.getAttribute("data-price");
        document.getElementById("addAmount").value = price;
    });

    // FUNCTION TO SEARCH BOOKING BY GUEST NAME
    // Function to search booking by guest name
    function searchBooking() {
        const query = document.getElementById("searchInput").value.trim();
        const xhr = new XMLHttpRequest();

        xhr.open("POST", "../../backend/search_booking.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                document.querySelector("#bookingsTable tbody").innerHTML = this.responseText;
            }
        };

        xhr.send("search=" + encodeURIComponent(query));
    }


    // FUNCTION TO CREATE BOOKING
    function createBooking() {
        // Show the modal
        var editModal = new bootstrap.Modal(document.getElementById('createBookingModal'));
        editModal.show();
    }

    // FUNCTION TO EDIT BOOKING

    // FUNCTION TO DISPLAY THE TOAST MESSAGE
    function showToast(isSuccess, message, duration = 5000) {
        const toastElement = document.getElementById("toastNotification");
        const toastHeading = document.getElementById("toastHeading");
        const toastMessage = document.getElementById("toastMessage");
        const toastTime = document.getElementById("toastTime");
        const toastIcon = document.getElementById("toastIcon");
        const progressBar = document.querySelector("#toastProgress .progress-bar");

        // Get current time
        const now = new Date();
        const formattedTime = now.toLocaleTimeString([], {
            hour: "2-digit",
            minute: "2-digit",
        });
        toastTime.textContent = formattedTime;

        // Set Toast content and icon
        if (isSuccess) {
            toastHeading.textContent = "Success";
            toastHeading.classList.remove("text-danger");
            toastHeading.classList.add("text-success");
            toastMessage.textContent = message;

            // Green tick icon for success
            toastIcon.innerHTML = `<i class="bi bi-check-circle-fill text-success" style="font-size: 1.2rem;"></i>`;
            progressBar.classList.replace("bg-danger", "bg-success");
        } else {
            toastHeading.textContent = "Error";
            toastHeading.classList.remove("text-success");
            toastHeading.classList.add("text-danger");
            toastMessage.textContent = message;

            // Red X icon for error
            toastIcon.innerHTML = `<i class="bi bi-x-circle-fill text-danger" style="font-size: 1.2rem;"></i>`;
            progressBar.classList.replace("bg-success", "bg-danger");
        }

        // Reset and animate progress bar
        progressBar.style.width = "100%";
        progressBar.style.transition = `width ${duration}ms linear`;
        setTimeout(() => {
            progressBar.style.width = "0%";
        }, 0);

        // Show the Toast
        const toast = new bootstrap.Toast(toastElement, {
            delay: duration,
        });
        toast.show();
    }

    // Trigger Toast if URL contains success or error messages
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has("success")) {
        showToast(true, urlParams.get("success"), 5000);
    } else if (urlParams.has("error")) {
        showToast(false, urlParams.get("error"), 5000);
    }

    // DISPLAY EDIT MODAL
    function openEditModal(row) {
        document.getElementById("editBookingId").value = row.booking_id;
        document.getElementById("editStatus").value = row.status;

        // Format the check-in and check-out values for datetime-local
        document.getElementById("editCheckIn").value = row.check_in;
        document.getElementById("editCheckOut").value = row.check_out;

        // Show the modal
        var editModal = new bootstrap.Modal(document.getElementById("editBookingModal"));
        editModal.show();
    }
</script>

<?php include '../../components/footer.php'; ?>