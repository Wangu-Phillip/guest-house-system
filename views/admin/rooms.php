<?php
include '../../components/header.php';
include '../../components/navbar.php';
include '../../backend/db_connection.php';

?>

    <!-- SEARCH INPUT FIELD -->
    <div class="container my-3">
        <div class="row">
            <div class="col-md-4">
                <input
                    type="text"
                    class="form-control"
                    id="searchInput"
                    placeholder="Search by room number..."
                    onkeyup="searchProduct()" />
            </div>
        </div>
    </div>

    <!-- ROOMS SECTION START -->
    <section class="container">
        <!-- Button trigger modal -->
        <a href="#" data-bs-toggle="modal" data-bs-target="#addProductModal">
            <button type="button" class="btn btn-primary text-end">Add a room</button>
        </a>
        <br><br>

        <div class="applications-table border rounded">
            <div id="productTable">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Room Type</th>
                            <th>Room Number</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Query to fetch products from the database
                        $query = "SELECT * FROM rooms";
                        $result = mysqli_query($conn, $query);

                        if ($result) {
                            $count = 1;
                            while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                                <tr>
                                    <td><?php echo $count; ?></td>
                                    <td><?php echo $row['room_Type']; ?></td>
                                    <td><?php echo $row['room_Number']; ?></td>
                                    <td><?php echo $row['price']; ?></td>
                                    <td>
                                        <button
                                            class="btn btn-warning btn-sm"
                                            onclick="editProduct(
                                            '<?php echo $row['room_id']; ?>', 
                                            '<?php echo $row['room_Type']; ?>', 
                                            '<?php echo $row['room_Number']; ?>', 
                                            '<?php echo $row['price']; ?>', 
                                        )">
                                            Edit
                                        </button>
                                        <form action="../../backend/delete_room.php" method="post" style="display:inline;">
                                            <input type="hidden" name="room_id" value="<?php echo $row['room_id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                        <?php
                                $count++;
                            }
                            mysqli_free_result($result);
                        } else {
                            echo "<tr><td colspan='8'>No products found</td></tr>";
                        }
                        mysqli_close($conn);
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    
    <!-- ADD/SAVE A ROOM -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md"> <!-- Makes the modal wider -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Add a room</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="../../backend/add_room.php" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="roomType" class="form-label">Room Type</label>
                                    <input type="text" class="form-control" id="roomType" name="roomtype" required>
                                </div>
                                <div class="mb-3">
                                    <label for="roomNumber" class="form-label">Room Number</label>
                                    <input type="text" class="form-control" id="roomNumber" name="roomnumber" required>
                                </div>
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price</label>
                                    <input type="text" class="form-control" id="price" name="price" required>
                                </div>
                            </div>

                            <!-- Right Column -->
                            
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Add New Room</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Edit Room Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="../../backend/update_room.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" id="editRoomId" name="room_id">
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editRoomType" class="form-label">Room Type</label>
                                    <input type="text" class="form-control" id="editRoomType" name="roomtype" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editRoomNumber" class="form-label">Room Number</label>
                                    <input type="text" class="form-control" id="editRoomNumber" name="roomnumber" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editPrice" class="form-label">Price</label>
                                    <input type="text" class="form-control" id="editPrice" name="price" required>
                                </div>
                            </div>
                            <!-- Right Column -->
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <br><br>

    <!-- FOOTER  -->
    <?php include '../../components/footer.php'; ?>

    <!--Bootstrap JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AJAX SEARCH & FILTER PRODUCTS FUNCTIONALITY -->
    <script>

        // FUNCTION TO SEARCH PRODUCTS BY THEIR NAME --> USES AJAX
        function searchProduct() {
            const searchValue = document.getElementById("searchInput").value;

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "../../backend/search_room.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function() {
                if (this.readyState === 4 && this.status === 200) {
                    document.getElementById("productTable").innerHTML = this.responseText;
                }
            };

            xhr.send("search=" + searchValue);
        }
    </script>

    <!-- EDIT FUNCTION -->
    <script>
        // FUNCTION TO EDIT PRODUCTS
        function editProduct(id, type, number, price) {
            // Populate modal fields
            document.getElementById('editRoomId').value = id;
            document.getElementById('editRoomType').value = type;
            document.getElementById('editRoomNumber').value = number;
            document.getElementById('editPrice').value = price;

            // Show the modal
            var editModal = new bootstrap.Modal(document.getElementById('editProductModal'));
            editModal.show();
        }

        
    </script>

