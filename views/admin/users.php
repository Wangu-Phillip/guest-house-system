<?php
include '../../components/header.php';
include '../../components/navbar.php';
include '../../backend/db_connection.php';

// Fetch users from the database
$sql = "SELECT user_id, CONCAT(firstname, ' ', lastname) AS employee_name, email, phone, role, status, salary FROM users";
$result = $conn->query($sql);
?>

<div class="container mt-5">
    <h2 class="mb-4">Users</h2>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <form class="d-flex">
            <input class="form-control me-2" type="search" placeholder="Search by name" aria-label="Search">
            <button class="btn btn-outline-success" type="submit"><i class="bi bi-search"></i></button>
        </form>
    </div>

    <table class="table table-hover table-striped">
        <thead class="table-success">
            <tr>
                <th>#</th>
                <th>Employee Name</th>
                <th>Employee Email</th>
                <th>Employee Number</th>
                <th>Role</th>
                <th>Status</th>
                <th>Salary (BWP)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php $count = 1; ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $count++ ?></td>
                        <td><?= htmlspecialchars($row['employee_name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td><?= htmlspecialchars($row['role']) ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                        <td><?= htmlspecialchars($row['salary']) ?></td>
                        <td>
                            <a href="#" class="text-success"><i class="bi bi-pencil-square"></i></a>
                            <a href="#" class="text-danger ms-2"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center text-muted">No users found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="mt-3 d-flex justify-content-between align-items-center">
        <span>Users: <?= $result ? $result->num_rows : 0 ?></span>
        <button class="btn btn-success rounded-circle" style="width: 40px; height: 40px;">
            <i class="bi bi-plus-lg"></i>
        </button>
    </div>
</div>

<?php include '../../components/footer.php'; ?>
