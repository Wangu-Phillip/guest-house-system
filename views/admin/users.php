<?php
include '../../components/header.php';
include '../../components/navbar.php';
?>

<div class="container mt-5">
    <div class="row d-flex justify-content-end align-items-center mb-3">
        <div class="col-md-4">
            <input id="search" class="form-control me-2 me-auto" type="search" placeholder="Search by name" aria-label="Search">
        </div>    
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
        <tbody id="userTable">
            <!-- Dynamic content will be loaded here -->
        </tbody>
    </table>

    <div class="mt-3 d-flex justify-content-between align-items-center">
        <span id="userCount">Users: 0</span>
        <button class="btn btn-success rounded-circle" style="width: 40px; height: 40px;">
            <i class="bi bi-plus-lg"></i>
        </button>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("search");
    const userTable = document.getElementById("userTable");
    const userCount = document.getElementById("userCount");

    // Fetch and display users dynamically
    function fetchUsers(query = "") {
        fetch(`../../backend/search_users.php?query=${query}`)
            .then(response => response.json())
            .then(data => {
                userTable.innerHTML = "";
                userCount.textContent = `Users: ${data.count}`;
                
                if (data.users.length > 0) {
                    data.users.forEach((user, index) => {
                        const row = `<tr>
                            <td>${index + 1}</td>
                            <td>${user.employee_name}</td>
                            <td>${user.email}</td>
                            <td>${user.phone}</td>
                            <td>${user.role}</td>
                            <td>${user.status}</td>
                            <td>${user.salary}</td>
                            <td>
                                <a href="#" class="text-success"><i class="bi bi-pencil-square"></i></a>
                                <a href="#" class="text-danger ms-2"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>`;
                        userTable.insertAdjacentHTML("beforeend", row);
                    });
                } else {
                    userTable.innerHTML = `<tr>
                        <td colspan="8" class="text-center text-muted">No users found</td>
                    </tr>`;
                }
            })
            .catch(error => console.error("Error fetching users:", error));
    }

    // Initial load
    fetchUsers();

    // Listen for input changes and perform live search
    searchInput.addEventListener("input", () => {
        const query = searchInput.value.trim();
        fetchUsers(query);
    });
});
</script>

<?php include '../../components/footer.php'; ?>
