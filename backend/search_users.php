
<?php
@include "./db_connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["search"])) {
    $search = mysqli_real_escape_string($conn, $_POST["search"]);

    $sql = "SELECT * FROM users WHERE email LIKE '%$search%'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $count = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $count++ . "</td>";
            echo "<td>" . $row["firstname"] . $row["lastname"] . "</td>";
            echo "<td>" . $row["email"] . "</td>";
            echo "<td>" . $row["phone"] . "</td>";
            echo "<td>" . $row["role"] . "</td>";
            echo "<td>" . $row["status"] . "</td>";
            echo "<td>" . $row["salary"] . "</td>";
            echo "<td>";
            echo "<form method='post' action='../../backend/delete_user.php'>";
            echo "<input type='hidden' name='delete' value='" . $row["email"] . "'>";
            echo "<input class='btn btn-danger' type='submit' value='Delete'>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No results found</td></tr>";
    }

    mysqli_close($conn);
}
?>
