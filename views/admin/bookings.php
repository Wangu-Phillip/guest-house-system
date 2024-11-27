<?php
include '../../components/header.php';
include '../../components/navbar.php';
include '../../backend/db_connection.php';

// Fetch bookings from the database
$sql = "SELECT 
            b.date, 
            CONCAT(g.firstname, ' ', g.lastname) AS guest_name, 
            g.phone AS guest_number, 
            g.omang_id AS guest_id, 
            r.room_number, 
            b.price AS amount, 
            b.status, 
            b.check_in_date, 
            b.check_out_date 
        FROM bookings b
        LEFT JOIN guests g ON b.guest_id = g.guest_id
        LEFT JOIN rooms r ON b.room_id = r.room_id
        ORDER BY b.date DESC";

$result = $conn->query($sql);
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <button class="btn btn-outline-success">Day</button>
            <button class="btn btn-outline-success">Week</button>
            <button class="btn btn-outline-success">Month</button>
            <button class="btn btn-outline-success">Year</button>
        </div>
        <div>
            <form class="d-flex">
                <input class="form-control me-2" type="search" placeholder="Search by name" aria-label="Search">
                <button class="btn btn-outline-success" type="submit"><i class="bi bi-search"></i></button>
            </form>
        </div>
    </div>

    <table class="table table-hover table-striped">
        <thead class="table-success">
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
                        <td><?= htmlspecialchars($row['check_in_date']) ?: '-' ?></td>
                        <td><?= htmlspecialchars($row['check_out_date']) ?: '-' ?></td>
                        <td>
                            <a href="#" class="text-success"><i class="bi bi-pencil-square"></i></a>
                            <a href="#" class="text-danger ms-2"><i class="bi bi-trash"></i></a>
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

    <div class="mt-3 d-flex justify-content-between align-items-center">
        <span>Bookings: <?= $result->num_rows ?></span>
        <button class="btn btn-success rounded-circle" style="width: 40px; height: 40px;">
            <i class="bi bi-plus-lg"></i>
        </button>
    </div>
</div>

<?php include '../../components/footer.php'; ?>
