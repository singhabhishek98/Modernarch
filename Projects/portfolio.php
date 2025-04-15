<?php
include_once '../db_connect.php';

// Secure Query to Fetch Projects
$sql = "SELECT id, project_name, client_name, category, location, duration, budget, status FROM projects";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$projects = [];
while ($row = $result->fetch_assoc()) {
    $projects[] = $row;
}

// Close Connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modernarch Portfolio</title>
    <link rel="icon" type="image/x-icon" href="../images/logo-modified.png">

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Custom Stylesheets -->
    <link rel="stylesheet" href="portfolio.css">
    <link rel="stylesheet" href="../footer.css">
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="../navbar.css">
    <link rel="stylesheet" href="../popup.css">
</head>

<body>
    <!-- Navbar -->
    <nav style="padding-right: 328px;">

        <a href="../index.html" class="logo">
            <img src="../images/logo.jpg" alt="Modernarch Logo">
        </a>
        <ul>
            <li><a href="../index.html"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="../About_Us/about_us.php"><i class="fa fa-info-circle"></i> About</a></li>
            <li><a href="../Services/services.php"><i class="fa fa-cogs"></i> Services</a></li>
            <li><a href="./portfolio.php"><i class="fa fa-briefcase"></i> Portfolio</a></li>
            <li><a href="../Career_Page/career.php"><i class="fa fa-user-graduate"></i> Career</a></li>
            <li><a href="#" onclick="openPopup()"><i class="fa fa-user-shield"></i> Admin</a></li>
            <li><a href="../Client_Feedback_Ratings/Ratings.php"><i class="fa fa-comments"></i> Feedback</a></li>
        </ul>
        <!-- Popup Container -->
        <div class="popup-container" id="popup">
            <div class="popup-box">
                <button class="close-btn" onclick="closePopup()">&times;</button>
                <h2>Admin Login</h2>

                <!-- Login Form (Submits to login.php) -->
                <form action="../login.php" method="POST">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit" class="login-btn">Login</button>
                </form>

                <p id="error-msg" style="color: red;"></p>
            </div>
        </div>

        <script>
            function openPopup() {
                let popup = document.getElementById("popup");
                popup.style.display = "flex";
                setTimeout(() => popup.classList.add("show"), 10);
            }

            function closePopup() {
                let popup = document.getElementById("popup");
                popup.classList.remove("show");
                setTimeout(() => popup.style.display = "none", 300);
            }

            // Close when clicking outside the box
            window.onclick = function (event) {
                let popup = document.getElementById("popup");
                if (event.target === popup) {
                    closePopup();
                }
            };

            // Show error message if redirected with error
            window.onload = function () {
                const params = new URLSearchParams(window.location.search);
                if (params.has("error")) {
                    document.getElementById("error-msg").innerText = params.get("error");
                    openPopup(); // Open popup if there's an error
                }
            };
        </script>
    </nav>

    <!-- Portfolio Section -->
    <div class="container">
        <div class="filter-buttons">
            <button class="filter-btn" onclick="filterProjects('All')">All</button>
            <button class="filter-btn" onclick="filterProjects('Residential')">Residential</button>
            <button class="filter-btn" onclick="filterProjects('Commercial')">Commercial</button>
        </div>

        <div id="projects-grid" class="projects-grid">
            <!-- Projects will be dynamically inserted here -->
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>Modernarch</h3>
                    <p class="footer-description">Building dreams with precision, integrity, and innovation.</p>
                    <div class="social-icons">
                        <a href="https://www.facebook.com/" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://www.linkedin.com/" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://twitter.com/" target="_blank"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.instagram.com/" target="_blank"><i class="fab fa-instagram"></i></a>
                        <a href="https://wa.me/" target="_blank"><i class="fab fa-whatsapp"></i></a>
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
                        <li><a href="../About_Us/about_us.php">About</a></li>
                        <li><a href="../Services/services.php">Services</a></li>
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
        const projects = <?php echo json_encode($projects, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_HEX_APOS); ?>;

        function displayProjects(filter = 'All') {
            const projectsGrid = document.getElementById('projects-grid');
            projectsGrid.innerHTML = '';

            const filteredProjects = filter === 'All' ? projects : projects.filter(project => project.category === filter);

            if (filteredProjects.length === 0) {
                projectsGrid.innerHTML = `<p>No projects available for the selected filter.</p>`;
                return;
            }

            filteredProjects.forEach(project => {
                const projectCard = document.createElement('div');
                projectCard.classList.add('project-card');

                projectCard.innerHTML = `
                    <img src="fetch_image.php?id=${project.id}" alt="${project.category}" />
                    <div class="project-card-content">
                        <h2>${project.project_name}</h2>
                        <p><strong>Client Name:</strong> ${project.client_name}</p>
                        <p><strong>Location:</strong> ${project.location}</p>
                        <p><strong>Duration:</strong> ${project.duration}</p>                        
                        <p><strong>Status:</strong> ${project.status}</p>
                    </div>
                `;

                projectsGrid.appendChild(projectCard);
            });
        }

        function filterProjects(category) {
            displayProjects(category);
        }

        displayProjects('All');
    </script>
</body>

</html>