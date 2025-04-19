<?php
include_once '../db_connect.php';
$sql = "SELECT id, name, experience, message, image FROM feedback";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testimonials/Reviews</title>
    <link rel="icon" type="image/x-icon" href="../images/logo-modified.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../footer.css">
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="../navbar.css">
    <link rel="stylesheet" href="../popup.css">
    <link rel="stylesheet" href="ratings.css">
</head>

<body>

    <div>
        <nav>
            <a href="../index.html" class="logo">
                <img src="../images/logo.jpg" alt="Modernarch Logo">
            </a>
            <ul>
                <li><a href="../index.html"><i class="fa fa-home"></i> Home</a></li>
                <li><a href="../About_Us/about_us.php"><i class="fa fa-info-circle"></i> About</a></li>
                <li><a href="../Services/services.php"><i class="fa fa-cogs"></i> Services</a></li>
                <li><a href="../Projects/portfolio.php"><i class="fa fa-briefcase"></i> Portfolio</a></li>
                <li><a href="../Career_Page/career.php"><i class="fa fa-user-graduate"></i> Career</a></li>
                <li><a href="#" onclick="openPopup()"><i class="fa fa-user-shield"></i> Admin</a></li>
                <li><a href="./Ratings.php"><i class="fa fa-comments"></i> Feedback</a></li>
            </ul>

            <!-- Popup Container -->
            <div class="popup-container" id="popup">
                <div class="popup-box">
                    <button class="close-btn" onclick="closePopup()">&times;</button>
                    <h2>Admin Login</h2>
                    <form action="../login.php" method="POST">
                        <input type="text" name="username" placeholder="Username" required>
                        <input type="password" name="password" placeholder="Password" required>
                        <button type="submit" class="login-btn">Login</button>
                    </form>
                    <p id="error-msg" style="color: red;"></p>
                </div>
            </div>

            <button type="button" onclick="redirectToPage()">Book Now</button>
            <div class="menu-toggle" id="mobile-menu">
                <i class="fas fa-bars"></i>
            </div>
        </nav>
    </div>

    <div class="container">
        <div class="header">
            <br><br><br>
            <h1>Read what our customers say</h1>
            <button class="rate-us-btn" onclick="window.open('Client_Feedback.html', '_blank')">Rate Us</button>
        </div>

        <div class="testimonials">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="testimonial">';
                    echo '<img src="' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                    echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                    $stars = str_repeat('⭐', $row['experience']);
                    echo "<p>$stars</p>";
                    echo '<p>' . htmlspecialchars($row['message']) . '</p>';
                    echo '</div>';
                }
            } else {
                echo '<p>No feedback available yet.</p>';
            }
            $conn->close();
            ?>
        </div>
    </div>

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
                        <a href="https://www.facebook.com/profile.php?id=100014693254784" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://www.linkedin.com" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://www.x.com" target="_blank"><i class="fab fa-x-twitter"></i></a>
                        <a href="https://www.instagram.com/saurabhpatel_888/" target="_blank"><i class="fab fa-instagram"></i></a>
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

        window.onclick = function (event) {
            let popup = document.getElementById("popup");
            if (event.target === popup) {
                closePopup();
            }
        };

        window.onload = function () {
            const params = new URLSearchParams(window.location.search);
            if (params.has("error")) {
                document.getElementById("error-msg").innerText = params.get("error");
                openPopup();
            }
        };

        function redirectToPage() {
            window.location.href = "../contact_with_us/Contact_with_us.html";
        }
    </script>

    <script src="../script.js"></script>
</body>
</html>
