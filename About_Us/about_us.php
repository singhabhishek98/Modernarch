<?php
include_once '../db_connect.php';
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us Modernarch</title>
  <link rel="icon" type="image/x-icon" href="../images/logo-modified.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="./about.css">
  <link rel="stylesheet" href="../footer.css">
  <link rel="stylesheet" href="../styles.css">
  <link rel="stylesheet" href="../navbar.css">
  <link rel="stylesheet" href="../popup.css">
</head>

<body>

  <!-- Navigation Bar -->

  <div>
    <nav>
      <a href="../index.html" class="logo">
        <img src="../images/logo.jpg" alt="Modernarch Logo">
      </a>
      <ul>
        <li><a href="../index.html"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="./about_us.php"><i class="fa fa-info-circle"></i> About</a></li>
        <li><a href="../Services/services.php"><i class="fa fa-cogs"></i> Services</a></li>
        <li><a href="../Projects/portfolio.php"><i class="fa fa-briefcase"></i> Portfolio</a></li>
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



      <button type="button" onclick="redirectToPage()">Book Now</button>
      <div class="menu-toggle" id="mobile-menu">
        <i class="fas fa-bars"></i>
      </div>
    </nav>
  </div>
  <br><br><br><br><br>
  <h1>About Modernarch</h1>

  <section class="company-overview">
    <h2>Company Overview</h2>
    <p>
      Modernarch is a construction and architectural firm focused on quality, innovation, and sustainability.
      We specialize in residential, commercial, and industrial projects, delivering durable and aesthetically designed
      structures.
      Our goal is to set new standards in modern architecture.
    </p>
  </section>

  <section class="team">
    <h2>Meet Our Team</h2>
    <div class="team-container">
      <?php


      $sql = "SELECT id, name, position, photo FROM employee";
      $result = $conn->query($sql);

      if (!$result) {
        die("<p>Error retrieving team members: " . $conn->error . "</p>");
      }

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          // Convert BLOB to base64 for displaying images
          $photoData = base64_encode($row["photo"]);
          $photoSrc = $photoData ? "data:image/jpeg;base64," . $photoData : "default.jpg"; // Fallback to default image
      
          echo "<div class='team-member'>";
          echo "<img src='$photoSrc' alt='" . htmlspecialchars($row["name"]) . "' width='100'>";
          echo "<h3>" . htmlspecialchars($row["name"]) . "</h3>";
          echo "<p>" . htmlspecialchars($row["position"]) . "</p>";
          echo "</div>";
        }
      } else {
        echo "<p>No team members found.</p>";
      }

      $conn->close(); // Close connection only after all queries
      ?>

    </div>
  </section>

  <section class="achievements">
    <h2>Our Achievements</h2>
    <div class="achievements-list">
      <div class="achievement">
        <i class="fas fa-trophy"></i>
        <h3>Best Architectural Firm 2024</h3>
        <p>Awarded for innovative and sustainable designs.</p>
      </div>
      <div class="achievement">
        <i class="fas fa-certificate"></i>
        <h3>ISO 9001 Certification</h3>
        <p>Recognized for maintaining high-quality construction standards.</p>
      </div>
      <div class="achievement">
        <i class="fas fa-building"></i>
        <h3>500+ Completed Projects</h3>
        <p>Successfully delivered residential, commercial, and industrial projects.</p>
      </div>
    </div>
  </section>

  <footer>
    <div class="footer-container">
      <div class="footer-content">
        <div class="footer-column">
          <h3>Modernarch</h3>
          <p class="footer-description">
            Building dreams with precision, integrity, and innovation. Strong foundations, sustainable solutions, and
            timeless designs for a better tomorrow. 🌍✨
          </p>
          <div class="social-icons">
            <a href="https://www.facebook.com/profile.php?id=100014693254784" target="_blank"><i
                class="fab fa-facebook-f"></i></a>
            <a href="https://www.linkedin.com" target="_blank"><i class="fab fa-linkedin-in"></i></a>
            <a href="https://twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
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
    function redirectToPage() {
      window.location.href = "../contact_with_us/Contact_with_us.html";
    }
  </script>


  <script src="about.js"></script>
  <script src="../script.js"></script>

</body>

</html>