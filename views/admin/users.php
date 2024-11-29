<?php
include '../../components/header.php';
include '../../components/navbar.php';
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

<br><br>

<!-- VIEW USERS -->
<section class="container">
    <div class="">

        <!-- Button trigger modal -->
        <a href="#" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <button type="button" class="btn btn-primary text-end">Add User</button>
        </a>

        <!-- SEARCH USER BY EMAIL -->
        <div class="row mb-3 d-flex justify-content-end ">
            <div class="col-md-4">
                <input
                    type="text"
                    class="form-control"
                    id="searchInput"
                    placeholder="Search user by email..."
                    onkeyup="searchUsers(this.value)" />
            </div>
        </div>

        <!-- USERS TABLE -->
        <div class="row">
            <div class="col">
                <div id="usersTable">
                    <div class="applications-table border rounded-2">
                        <table class="table table-striped">
                            <thead>
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
                            <tbody id="userTableBody">
                                <?php

                                include '../../backend/db_connection.php';

                                // Select data from applications table
                                $sql = "SELECT * FROM users";
                                $result = $conn->query($sql);

                                $count = 1;

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $count++ . "</td>";
                                        echo "<td>" . $row["firstname"] . " " . $row["lastname"] . "</td>";
                                        echo "<td>" . $row["email"] . "</td>";
                                        echo "<td>" . $row["phone"] . "</td>";
                                        echo "<td>" . $row["role"] . "</td>";
                                        echo "<td>" . $row["status"] . "</td>";
                                        echo "<td>" . $row["salary"] . "</td>";
                                        echo "<td>";
                                        echo "<button class='btn btn-warning btn-sm' onclick=\"editUser(
                                                    '{$row['user_id']}', 
                                                    '{$row['firstname']}', 
                                                    '{$row['lastname']}', 
                                                    '{$row['email']}', 
                                                    '{$row['phone']}',
                                                    '{$row['role']}',
                                                    '{$row['salary']}'
                                                )\">Edit</button> ";
                                        echo "<form method='post' action='../../backend/delete_user.php' style='display:inline;'>";
                                        echo "<input type='hidden' name='delete' value='" . $row["email"] . "'>";
                                        echo "<input class='btn btn-danger btn-sm' type='submit' value='Delete'>";
                                        echo "</form>";
                                        echo "</td>";

                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6'>No results found</td></tr>";
                                }

                                $conn->close();
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add new user</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="../../backend/add_user.php" method="post">
                    <input type="hidden" id="addUserId" name="user_id">
                    <div class="mb-3">
                        <label for="addFirstname" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="editFirstname" name="firstname" required>
                    </div>
                    <div class="mb-3">
                        <label for="addLastname" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="addLastname" name="lastname" required>
                    </div>
                    <div class="mb-3">
                        <label for="addEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="addEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="addPhone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="addPhone" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="addRole" class="form-label">Role</label>
                        <select class="form-select" id="addRole" name="role" required>
                            <option value="admin">Admin</option>
                            <option value="employee">Employee</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="addPassword" class="form-label">Password</label>
                        <input type="text" class="form-control" id="addPassword" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="addSalary" class="form-label">Salary</label>
                        <input type="text" class="form-control" id="addSalary" name="salary" required>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="../../backend/update_user.php" method="post">
                    <input type="hidden" id="editUserId" name="user_id">
                    <div class="mb-3">
                        <label for="editFirstname" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="editFirstname" name="firstname" required>
                    </div>
                    <div class="mb-3">
                        <label for="editLastname" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="editLastname" name="lastname" required>
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="editPhone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="editPhone" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="editRole" class="form-label">Role</label>
                        <select class="form-select" id="editRole" name="role" required>
                            <option value="admin">Admin</option>
                            <option value="customer">Customer</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editSalary" class="form-label">Salary</label>
                        <input type="text" class="form-control" id="editSalary" name="salary" required>
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

<!-- SEARCH USER FUNCTION -->
<script>
    // SEARCH USER FUNCTION
    function searchUsers(query) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "../../backend/search_users.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                document.getElementById("userTableBody").innerHTML = this.responseText;
            }
        };

        xhr.send("search=" + query);
    }

    // EDIT USER FUNCTION
    function editUser(id, firstname, lastname, email, phone, role, salary) {
        // Populate the modal fields with the user data
        document.getElementById('editUserId').value = id;
        document.getElementById('editFirstname').value = firstname;
        document.getElementById('editLastname').value = lastname;
        document.getElementById('editEmail').value = email;
        document.getElementById('editPhone').value = phone;
        document.getElementById('editRole').value = role;
        document.getElementById('editSalary').value = salary;


        // Show the modal
        var editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
        editModal.show();
    }

    function addUser() {
        // Show the modal
        var editModal = new bootstrap.Modal(document.getElementById('addUserModal'));
        editModal.show();
    }

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
</script>


</body>

</html>