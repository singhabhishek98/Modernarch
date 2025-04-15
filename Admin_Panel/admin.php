<?php
session_start(); // ✅ Start session at the VERY TOP

// Check if user is logged in
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("Location: index.html"); // Redirect if session is not set
    exit();
}

include_once '../db_connect.php';

$user_id = $_SESSION['username']; // ✅ Retrieve session username

// Fetch user data
$sql = "SELECT username, profile_photo FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found in database.");
}

$username = htmlspecialchars($user['username']);
$profile_photo = !empty($user['profile_photo']) ? "uploads/" . htmlspecialchars($user['profile_photo']) : "uploads/default.jpg";


// Fetch users data from employee table
$sql1 = "SELECT id, name, position, mobileNo, email, experience, joining_date, address FROM employee";
$result1 = $conn->query($sql1);

// Fetch services data from services table
$sql2 = "SELECT id, icon, service_name, description, category, construction_work FROM services";
$result2 = $conn->query($sql2);

// Fetch portfolio data from project table
$sql3 = "SELECT id, project_name, category, client_name, mobile_no, duration, budget, location, completion_date, status FROM projects";
$result3 = $conn->query($sql3);

// Fetch inquiry data from inquiry table
$sql4 = "SELECT name, project_type, address, mobile, message, created_at, inquiry_status status FROM inquiry";
$result4 = $conn->query($sql4);


// Fetch invioce data from finance table
$sql5 = "SELECT finance_id, month, year, revenue, expenses, profit, client_name, 
         payment_method, payment_status, invoice_number, tax, material_cost, 
         labor_cost, equipment_cost, miscellaneous_cost, notes, created_at, updated_at  FROM finance";
$result5 = $conn->query($sql5);


// SQL Query to count project status
$sql = "
    SELECT 
        SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) AS completed_projects,
        SUM(CASE WHEN status = 'ongoing' THEN 1 ELSE 0 END) AS ongoing_projects,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending_projects
    FROM projects;
";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $completedCount = $row['completed_projects'];
    $ongoingCount = $row['ongoing_projects'];
    $pendingCount = $row['pending_projects'];
} else {
    $completedCount = 0;
    $ongoingCount = 0;
    $pendingCount = 0;
}


// SQL Query to count inquiry status
$sql = "
    SELECT 
        SUM(CASE WHEN inquiry_status = 'resolved' THEN 1 ELSE 0 END) AS completed_projects,
        SUM(CASE WHEN inquiry_status = 'unresolved' THEN 1 ELSE 0 END) AS ongoing_projects
    FROM inquiry;
";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $resolvedCount = $row['completed_projects'];
    $unresolvedCount = $row['ongoing_projects'];
} else {
    $resolvedCount = 0;
    $unresolvedCount = 0;
}

// SQL Query to fetch revenue and expenses data for 12 months
$sql = "
    SELECT 
        MONTH(created_at) AS month,
        SUM(revenue) AS total_revenue,
        SUM(expenses) AS total_expenses
    FROM finance
    WHERE YEAR(created_at) = YEAR(CURDATE()) 
    GROUP BY MONTH(created_at)
    ORDER BY MONTH(created_at);
";

$result = $conn->query($sql);

// Initialize all months with zero
$revenueData = array_fill(0, 12, 0);
$expensesData = array_fill(0, 12, 0);

// Fill data from database
while ($row = $result->fetch_assoc()) {
    $revenueData[$row['month'] - 1] = $row['total_revenue'];
    $expensesData[$row['month'] - 1] = $row['total_expenses'];
}

// Assign values to PHP variables for each month
list(
    $jan,
    $feb,
    $mar,
    $apr,
    $may,
    $jun,
    $jul,
    $aug,
    $sep,
    $oct,
    $nov,
    $dec
) = $revenueData;

list(
    $jan_exp,
    $feb_exp,
    $mar_exp,
    $apr_exp,
    $may_exp,
    $jun_exp,
    $jul_exp,
    $aug_exp,
    $sep_exp,
    $oct_exp,
    $nov_exp,
    $dec_exp
) = $expensesData;


$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="icon" type="image/x-icon" href="../images/logo-modified.png">
    <!-- External CSS -->
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="../footer.css">

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        button {
            margin-top: 5px;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .edit-btn {
            background-color: #47e64d;
            color: white;
        }

        .edit-btn:hover {
            background-color: #45a050;
        }

        .delete-btn {
            background-color: #f7327a;
            color: white;
        }

        .delete-btn:hover {
            background-color: #b5095d;
        }

        .log-out-btn {
            background-color: #ff0000;
            color: white;
        }

        .log-out-btn:hover {
            background-color: #b50909;
        }



        .profile-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .profile-photo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ccc;
        }

        #user_name {
            margin-top: -15px;
            color: #ffffff;
        }
    </style>

</head>

<body>

    <div class="container">
        <button id="menu-toggle" class="menu-toggle">☰</button>

        <aside class="sidebar">
            <h2>Admin Panel</h2>
            <nav>
                <button onclick="showSection('dashboard')"><i class="fas fa-chart-bar"></i> Dashboard</button>
                <button onclick="showSection('users')"><i class="fas fa-users"></i> User Management</button>
                <button onclick="showSection('services')"><i class="fas fa-cogs"></i> Services</button>
                <button onclick="showSection('portfolio')"><i class="fas fa-briefcase"></i> Portfolio
                    Management</button>
                <button onclick="showSection('career')"><i class="fas fa-user-tie"></i> Career Management</button>
                <button onclick="showSection('inquiries')"><i class="fas fa-envelope"></i> Inquiry Management</button>
                <button onclick="showSection('invoice')"><i class="fas fa-file-alt"></i> Invoice Management</button>
            </nav>

        </aside>

        <main class="content">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
                <h2 id="user_name">Welcome: <?php echo $username; ?></h2>
                <button class="log-out-btn" onclick="logOut()">Log Out</button>
            </div>
            <section id="dashboard" class="section active">

                <h2>Analytics Dashboard</h2><br>
                <div class="dashboard-container">
                    <div class="chart-box">
                        <h4> Project Completion</h4>
                        <canvas id="projectChart"></canvas>
                    </div>

                    <div class="chart-box">
                        <h4> Inquiry Status</h4>
                        <canvas id="inquiryChart"></canvas>
                    </div>

                    <div class="chart-box">
                        <h4> Revenue & Expenses</h4>
                        <canvas id="financeChart"></canvas>
                    </div>
                </div>

                <script>
                    function createGradient(ctx, color1, color2) {
                        let gradient = ctx.createLinearGradient(0, 0, 0, 300);
                        gradient.addColorStop(0, color1);
                        gradient.addColorStop(1, color2);
                        return gradient;
                    }

                    // Project Completion Chart (3D Bar)
                    const projectCtx = document.getElementById('projectChart').getContext('2d');
                    new Chart(projectCtx, {
                        type: 'bar',
                        data: {
                            labels: ['Completed', 'Ongoing', 'Pending'],
                            datasets: [{
                                label: 'Projects',
                                data: [
                                    <?php echo $completedCount; ?>,
                                    <?php echo $ongoingCount; ?>,
                                    <?php echo $pendingCount; ?>
                                ],
                                backgroundColor: [
                                    createGradient(projectCtx, '#ff9a9e', '#fad0c4'),
                                    createGradient(projectCtx, '#a1c4fd', '#c2e9fb'),
                                    createGradient(projectCtx, '#ff758c', '#ff7eb3')
                                ],
                                borderRadius: 3,
                                barPercentage: 0.6,
                                categoryPercentage: 0.5
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { beginAtZero: true, ticks: { color: '#fff' } },
                                x: { ticks: { color: '#fff' } }
                            }
                        }
                    });

                    // Inquiry Status Chart (3D Doughnut)
                    new Chart(document.getElementById('inquiryChart').getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: ['Resolved', 'Unresolved'],
                            datasets: [{
                                data: [
                                    <?php echo $resolvedCount; ?>,
                                    <?php echo $unresolvedCount; ?>
                                ],
                                backgroundColor: ['#42e695', '#ff6a88'],
                                hoverOffset: 10,
                                borderWidth: 3,
                                borderColor: '#fff'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { labels: { color: '#fff' } } }
                        }
                    });


                    // Revenue & Expenses Chart (3D Line)
                    new Chart(document.getElementById('financeChart').getContext('2d'), {
                        type: 'line',
                        data: {
                            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                            datasets: [{
                                label: 'Revenue',
                                data: [
                                    <?php echo $jan; ?>, <?php echo $feb; ?>, <?php echo $mar; ?>,
                                    <?php echo $apr; ?>, <?php echo $may; ?>, <?php echo $jun; ?>,
                                    <?php echo $jul; ?>, <?php echo $aug; ?>, <?php echo $sep; ?>,
                                    <?php echo $oct; ?>, <?php echo $nov; ?>, <?php echo $dec; ?>
                                ],


                                borderColor: '#00c6ff',
                                backgroundColor: 'rgba(0, 198, 255, 0.3)',
                                fill: true,
                                tension: 0.4,
                                borderWidth: 3,
                                pointStyle: 'circle',
                                pointRadius: 5,
                                pointBackgroundColor: '#00c6ff',
                                shadowColor: 'rgba(0, 0, 0, 0.3)',
                                shadowBlur: 5
                            }, {
                                label: 'Expenses',
                                data: [
                                    <?php echo $jan_exp; ?>, <?php echo $feb_exp; ?>, <?php echo $mar_exp; ?>,
                                    <?php echo $apr_exp; ?>, <?php echo $may_exp; ?>, <?php echo $jun_exp; ?>,
                                    <?php echo $jul_exp; ?>, <?php echo $aug_exp; ?>, <?php echo $sep_exp; ?>,
                                    <?php echo $oct_exp; ?>, <?php echo $nov_exp; ?>, <?php echo $dec_exp; ?>
                                ],

                                borderColor: '#ff512f',
                                backgroundColor: 'rgba(255, 81, 47, 0.3)',
                                fill: true,
                                tension: 0.4,
                                borderWidth: 3,
                                pointStyle: 'circle',
                                pointRadius: 5,
                                pointBackgroundColor: '#ff512f',
                                shadowColor: 'rgba(0, 0, 0, 0.3)',
                                shadowBlur: 5
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { labels: { color: '#fff' } } },
                            scales: {
                                x: { ticks: { color: '#fff' } },
                                y: { ticks: { color: '#fff' }, beginAtZero: true }
                            }
                        }
                    });
                </script>
            </section>


            <section id="users" class="section">
                <h2>User Management</h2>
                <div class="user-table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Employee ID</th>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Mobile No</th>
                                <th>Email</th>
                                <th>Experience</th>
                                <th>Joining Date</th>
                                <th>Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result1->num_rows > 0) {
                                while ($row = $result1->fetch_assoc()) {
                                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['position']}</td>
                            <td>{$row['mobileNo']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['experience']}</td>
                            <td>{$row['joining_date']}</td>
                            <td>{$row['address']}</td>
                        </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8'>No employees found</td></tr>";
                            }

                            ?>
                        </tbody>
                    </table>
                </div>
                <td><button class="edit-btn" onclick="window.location.href='add_employee.php'">Add new</button></td>
                <td><button class="edit-btn" onclick="editItem()">Edit</button></td>
                <td><button class="delete-btn" onclick="deleteItem()">Delete</button></td>
            </section>


            <section id="services" class="section">
                <h2>Services Management</h2><br>
                <div class="services-table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Service ID</th>
                                <th>icon</th>
                                <th>Service Name</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Construction Work</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result2->num_rows > 0) {
                                while ($row = $result2->fetch_assoc()) {
                                    echo "<tr>                            
                            <td>" . htmlspecialchars($row['id']) . "</td>
                            <td>" . htmlspecialchars($row['icon']) . "</td>
                            <td>" . htmlspecialchars($row['service_name']) . "</td>
                            <td>" . htmlspecialchars($row['category']) . "</td>
                            <td>" . htmlspecialchars($row['description']) . "</td>
                            <td>" . htmlspecialchars($row['construction_work']) . "</td>
                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>No services found</td></tr>";
                            }

                            ?>
                        </tbody>
                    </table>
                </div>
                <td><button class="edit-btn" onclick="window.location.href='add_services.php'">Add new</button></td>
                <td><button class="edit-btn" onclick="editItem()">Edit</button></td>
                <td><button class="delete-btn" onclick="deleteItem()">Delete</button></td>
            </section>


            <section id="portfolio" class="section">
                <h2>Portfolio Management</h2><br>
                <div class="portfolio-table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Project ID</th>
                                <th>Project Name</th>
                                <th>Category</th>
                                <th>Client Name</th>
                                <th>Mobile No</th>
                                <th>Duration</th>
                                <th>Budget</th>
                                <th>Location</th>
                                <th>Completion Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result3->num_rows > 0) {
                                while ($row = $result3->fetch_assoc()) {
                                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['project_name']}</td>
                        <td>{$row['category']}</td>
                        <td>{$row['client_name']}</td>
                        <td>{$row['mobile_no']}</td>
                        <td>{$row['duration']}</td>
                        <td>₹{$row['budget']}</td>
                        <td>{$row['location']}</td>
                        <td>{$row['completion_date']}</td>
                        <td>{$row['status']}</td>
                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='10'>No projects found</td></tr>";
                            }

                            ?>
                        </tbody>
                    </table>
                </div>
                <td><button class="edit-btn" onclick="window.location.href='add_projects.php'">Add new</button></td>
                <td><button class="edit-btn" onclick="editItem()">Edit</button></td>
                <td><button class="delete-btn" onclick="deleteItem()">Delete</button></td>
            </section>


            <section id="career" class="section">
                <h2>Career Management</h2><br>
                <div class="career-table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Job ID</th>
                                <th>Position</th>
                                <th>Department</th>
                                <th>Location</th>
                                <th>Posted Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>401</td>
                                <td>Project Manager</td>
                                <td>Construction</td>
                                <td>Delhi, India</td>
                                <td>05-Feb-2024</td>
                                <td>Open</td>
                            </tr>
                            <tr>
                                <td>402</td>
                                <td>Architect</td>
                                <td>Design</td>
                                <td>Mumbai, India</td>
                                <td>20-Jan-2024</td>
                                <td>Closed</td>
                            </tr>
                            <tr>
                                <td>403</td>
                                <td>Site Engineer</td>
                                <td>Engineering</td>
                                <td>Varanasi, India</td>
                                <td>15-Feb-2024</td>
                                <td>Open</td>
                            </tr>
                            <tr>
                                <td>404</td>
                                <td>Interior Designer</td>
                                <td>Design</td>
                                <td>Bangalore, India</td>
                                <td>01-Mar-2024</td>
                                <td>Open</td>
                            </tr>
                            <tr>
                                <td>405</td>
                                <td>HR Executive</td>
                                <td>Human Resources</td>
                                <td>Kolkata, India</td>
                                <td>10-Feb-2024</td>
                                <td>Closed</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <td><button class="edit-btn" onclick="window.location.href='add_service.php'">Add new</button></td>
                <td><button class="edit-btn" onclick="editItem()">Edit</button></td>
                <td><button class="delete-btn" onclick="deleteItem()">Delete</button></td>
            </section>


            <section id="inquiries" class="section">
                <h2>Inquiry Management</h2><br>
                <div class="inquiry-table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Client Name</th>
                                <th>Address</th>
                                <th>Contact Number</th>
                                <th>Inquiry Type</th>
                                <th>Details</th>
                                <th>Inquiry Date</th>
                                <th>Inquiry Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result4->num_rows > 0) {
                                while ($row = $result4->fetch_assoc()) {
                                    $formattedDate = date('d-M-Y', strtotime($row['created_at'])); // Format the date
                                    echo "<tr>
                        <td>{$row['name']}</td>
                        <td>{$row['address']}</td>
                        <td>{$row['mobile']}</td>
                        <td>{$row['project_type']}</td>
                        <td>{$row['message']}</td>
                        <td>{$formattedDate}</td> <!-- Formatted date -->
                         <td>{$row['status']}</td>

                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='10'>No Inquiry found</td></tr>";
                            }

                            ?>
                        </tbody>
                    </table>
                </div>

                <td><button class="edit-btn" onclick="editItem()">Edit</button></td>
                <td><button class="delete-btn" onclick="deleteItem()">Delete</button></td>
            </section>



            <section id="invoice" class="section">
                <h2>Invoice Management</h2>
                <div class="">
                    <table>
                        <thead>
                            <tr>
                                <th>Invoice_No</th>
                                <th>Client_name</th>
                                <th>Revenue</th>
                                <th>Expenses</th>
                                <th>Profit</th>
                                <th>status</th>
                                <th>Material_cost</th>
                                <th>Labor_cost</th>
                                <th>Equipment_cost</th>
                                <th>Extra_Charges</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result5->num_rows > 0) {
                                while ($row = $result5->fetch_assoc()) {
                                    echo "<tr>
                        <td><a href='invoice.php?invoice_number={$row['invoice_number']}'>{$row['invoice_number']}</a></td>
                        <td>{$row['client_name']}</td>
                        <td>{$row['revenue']}</td>
                        <td>{$row['expenses']}</td>
                        <td>{$row['profit']}</td>                        
                        <td>{$row['payment_status']}</td>                        
                        <td>{$row['material_cost']}</td>
                        <td>{$row['labor_cost']}</td>
                        <td>{$row['equipment_cost']}</td>
                        <td>{$row['miscellaneous_cost']}</td>                        
                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='18'>No records found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <td><button class="edit-btn" onclick="window.location.href='add_employee.php'">Add new</button></td>
                <td><button class="edit-btn" onclick="editItem()">Edit</button></td>
                <td><button class="delete-btn" onclick="deleteItem()">Delete</button></td>
            </section>

        </main>


    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>Modernarch</h3>
                    <p class="footer-description">
                        Building dreams with precision, integrity, and innovation. Strong foundations, sustainable
                        solutions, and timeless designs for a better tomorrow. 🌍✨
                    </p>
                    <div class="social-icons">
                        <a href="https://www.facebook.com/profile.php?id=100014693254784" target="_blank"><i
                                class="fab fa-facebook-f"></i></a>
                        <a href="https://www.linkedin.com" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.instagram.com/saurabhpatel_888/" target="_blank"><i
                                class="fab fa-instagram"></i></a>
                        <a href="https://wa.me/916393221303" target="_blank"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>

                <div class="footer-column">
                    <h3>Address</h3>
                    <p>Pandeypur, Varanasi 221001</p>
                    <p class="footer-email">modernarch.vns@gmail.com</p>
                    <p class="footer-phone">+91 63932 21303</p>
                </div>

                <div class="footer-column">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="../index.html">Home</a></li>
                        <li><a href="../About_Us/about_us.html">About</a></li>
                        <li><a href="../Services/services.html">Services</a></li>
                        <li><a href="#">Projects</a></li>
                    </ul>
                </div>

                <div class="footer-column">
                    <h3>Newsletter</h3>
                    <form>
                        <input type="email" name="email" placeholder="Your email address" required>
                        <button type="submit">Subscribe</button>
                    </form>
                </div>
            </div>

            <div class="footer-bottom">
                <hr>
                <p>&copy; 2025 Modernarch. All rights reserved.</p>
            </div>
        </div>
    </footer>


    <!-- JavaScript -->
    <script>
        // Function to show/hide sections
        function showSection(sectionId) {
            document.querySelectorAll('.section').forEach(section => {
                section.classList.remove('active');
            });
            document.getElementById(sectionId).classList.add('active');
        }

        // Sidebar toggle functionality
        document.addEventListener("DOMContentLoaded", function () {
            const menuToggle = document.getElementById("menu-toggle");
            const sidebar = document.querySelector(".sidebar");

            menuToggle.addEventListener("click", function () {
                sidebar.classList.toggle("active");
            });
        });

        // Form submission handling
        // Removed event listener for non-existent form "submit-to-google-sheet" to prevent error
        // document.forms["submit-to-google-sheet"].addEventListener("submit", function (event) {
        //     event.preventDefault();
        //     const msg = document.getElementById("msg");
        //     msg.innerHTML = "Thank you for subscribing!";
        //     setTimeout(() => msg.innerHTML = "", 3000);
        //     this.reset();
        // });
    </script>

    <script>
        function editItem() {
            alert("Edit button clicked!");
        }

        function deleteItem() {
            alert("Delete button clicked!");
        }
    </script>

    <script>
        function logOut() {
            // Clear session storage or local storage (if applicable)
            sessionStorage.clear();
            localStorage.clear();

            // Redirect to index.html
            window.location.href = '../index.html';
        }
    </script>
</body>

</html>