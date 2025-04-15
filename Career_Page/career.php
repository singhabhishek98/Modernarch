<?php
include_once '../db_connect.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $mobile = $conn->real_escape_string($_POST['mobile']);
    $email = $conn->real_escape_string($_POST['email']);
    $cover_letter = $conn->real_escape_string($_POST['coverLetter']);

    // File upload handling
    $pdf_dir = "uploads/";
    if (!is_dir($pdf_dir)) {
        mkdir($pdf_dir, 0777, true);
    }

    $pdf_name = basename($_FILES["resume"]["name"]);
    $pdf_path = $pdf_dir . time() . "_" . $pdf_name;

    if (move_uploaded_file($_FILES["resume"]["tmp_name"], $pdf_path)) {
        // Insert into database
        $sql = "INSERT INTO opening_job (name, mobile, email, pdf, cover_letter) 
                VALUES ('$name', '$mobile', '$email', '$pdf_path', '$cover_letter')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Application submitted successfully!'); window.location.href='career.php';</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "File upload error: " . $_FILES["resume"]["error"];
    }
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Career - Modernarch</title>
    <link rel="icon" type="image/x-icon" href="../images/logo-modified.png">
    <!-- External CSS -->
    <link rel="stylesheet" href="career.css">
    <link rel="stylesheet" href="../footer.css">
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="../navbar.css">
    <link rel="stylesheet" href="../popup.css">

    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>

    <div>
        <nav style="margin-right: 26px; padding-top: 27px; ">

            <a href="../index.html" class="logo">
                <img src="../images/logo.jpg" alt="Modernarch Logo">
            </a>
            <ul>
                <li><a href="../index.html"><i class="fa fa-home"></i> Home</a></li>
                <li><a href="../About_Us/about_us.php"><i class="fa fa-info-circle"></i> About</a></li>
                <li><a href="../Services/services.php"><i class="fa fa-cogs"></i> Services</a></li>
                <li><a href="../Projects/portfolio.php"><i class="fa fa-briefcase"></i> Portfolio</a></li>
                <li><a href="./career.php"><i class="fa fa-user-graduate"></i> Career</a></li>
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


            <button type="button" onclick="redirectToPage()" style="border-radius: 30px;">Book Now</button>
            <div class="menu-toggle" id="mobile-menu">
                <i class="fas fa-bars"></i>
            </div>
        </nav>
    </div>

    <!-- Career Section -->
    <div class="container my-5">
        <br><br><br>
        <h2 class="text-center text-primary mb-4">Join Our Team</h2>

        <div class="row">
            <!-- Job Openings -->
            <div class="col-lg-6 mb-4">
                <h3 class="text-success">Job Openings</h3>

                <div class="job-listing p-3 shadow-sm rounded bg-light mb-3">
                    <h4>Software Developer</h4>
                    <p><strong>Location:</strong> Remote</p>
                    <p><strong>Skills:</strong> JavaScript, React, Node.js</p>
                    <button class="btn btn-sm btn-outline-primary">Apply Now</button>
                </div>

                <div class="job-listing p-3 shadow-sm rounded bg-light">
                    <h4>Backend Engineer</h4>
                    <p><strong>Location:</strong> On-site</p>
                    <p><strong>Skills:</strong> Python, Django, PostgreSQL</p>
                    <button class="btn btn-sm btn-outline-primary">Apply Now</button>
                </div>
            </div>

            <!-- Apply Form -->
            <div class="col-lg-6">
                <h3 class="text-success">Apply Now</h3>
                <form id="jobApplicationForm" class="p-4 bg-white shadow-sm rounded" action="submit.php" method="POST"
                    enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="mobile" class="form-label">Mobile</label>
                        <input type="tel" id="mobile" name="mobile" class="form-control" required pattern="[0-9]{10}"
                            maxlength="10" autocomplete="tel">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="resume" class="form-label">Resume (PDF only)</label>
                        <input type="file" id="resume" name="resume" class="form-control" accept=".pdf" required>
                    </div>
                    <div class="mb-3">
                        <label for="coverLetter" class="form-label">Cover Letter</label>
                        <textarea id="coverLetter" name="coverLetter" class="form-control" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Submit Application</button>
                    <p id="formMsg" class="text-success mt-2 text-center" style="display: none;">Application submitted
                        successfully!</p>
                </form>

            </div>
        </div>
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
                        <li><a href="../About_Us/about-us.php">About</a></li>
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
        document.getElementById('jobApplicationForm').addEventListener('submit', function (event) {
            event.preventDefault();

            // Basic Form Validation
            let name = document.getElementById('name').value.trim();
            let email = document.getElementById('email').value.trim();
            let resume = document.getElementById('resume').value;
            let coverLetter = document.getElementById('coverLetter').value.trim();

            if (!name || !email || !resume || !coverLetter) {
                alert("Please fill all the required fields.");
                return;
            }

            document.getElementById('formMsg').style.display = 'block';
            setTimeout(() => { document.getElementById('formMsg').style.display = 'none'; }, 3000);
        });
    </script>

    <script>
        function redirectToPage() {
            window.location.href = "../contact_with_us/Contact_with_us.html";
        }
    </script>

    <script src="https://kit.fontawesome.com/7b1fb74b2f.js" crossorigin="anonymous"></script>
    <script src="../script.js"></script>
</body>

</html>