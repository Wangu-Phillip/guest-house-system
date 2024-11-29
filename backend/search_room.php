
<?php
@include "./db_connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["search"])) {
    $search = mysqli_real_escape_string($conn, $_POST["search"]);

    // Fetch rooms from the database
$sql = "SELECT * FROM rooms WHERE room_Number LIKE '%$search'";
$result = $conn->query($sql);

if (mysqli_num_rows($result) > 0) {
    echo '<table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Room Type</th>
                    <th>Room Number</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>';

    $count = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$count}</td>
                <td>{$row['room_Type']}</td>
                <td>{$row['room_Number']}</td>
                <td>{$row['price']}</td>
                <td>
                    <form action='../../backend/delete_user.php' method='post'>
                        <input type='hidden' name='product_id' value='{$row['room_id']}'>
                        <button type='submit' class='btn btn-danger'>Delete</button>
                    </form>
                </td>
            </tr>";
        $count++;
    }

    echo '</tbody></table>';
} else {
    echo '<p class="text-center">No products found.</p>';
}

mysqli_close($conn);
}
?>
