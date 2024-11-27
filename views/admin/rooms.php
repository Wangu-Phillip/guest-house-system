<?php
include '../../components/header.php';
include '../../components/navbar.php';
include '../../backend/db_connection.php';

// Fetch rooms from the database
$sql = "SELECT room_id, room_type, room_number, price, 'Available' AS status FROM rooms";
$result = $conn->query($sql);
?>

<div class="container mt-5">
    <h2 class="mb-4">Rooms</h2>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <form class="d-flex">
            <input class="form-control me-2" type="search" placeholder="Search by room number" aria-label="Search">
            <button class="btn btn-outline-success" type="submit"><i class="bi bi-search"></i></button>
        </form>
    </div>

    <table class="table table-hover table-striped">
        <thead class="table-success">
            <tr>
                <th>#</th>
                <th>Room Type</th>
                <th>Room Number</th>
                <th>Price</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php $count = 1; ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $count++ ?></td>
                        <td><?= htmlspecialchars($row['room_type']) ?></td>
                        <td><?= htmlspecialchars($row['room_number']) ?></td>
                        <td><?= htmlspecialchars($row['price']) ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                        <td>
                            <a href="#" class="text-success"><i class="bi bi-pencil-square"></i></a>
                            <a href="#" class="text-danger ms-2"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center text-muted">No rooms found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="mt-3 d-flex justify-content-between align-items-center">
        <span>Rooms Available: <?= $result ? $result->num_rows : 0 ?></span>
        <button class="btn btn-success rounded-circle" style="width: 40px; height: 40px;">
            <i class="bi bi-plus-lg"></i>
        </button>
    </div>
</div>

<?php include '../../components/footer.php'; ?>
