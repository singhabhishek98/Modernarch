<?php
include_once '../db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $position = trim($_POST["position"]);
    $mobileNo = trim($_POST["mobileNo"]);
    $address = trim($_POST["address"]);
    $email = trim($_POST["email"]);
    $experience = trim($_POST["experience"]);
    $joining_date = $_POST["joining_date"];

    // Handle file upload
    $photo = "";
    if (!empty($_FILES["photo"]["name"])) {
        $target_dir = "uploads/";
        $photo = $target_dir . basename($_FILES["photo"]["name"]);
        move_uploaded_file($_FILES["photo"]["tmp_name"], $photo);
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO employee (name, position, mobileNo, address, email, photo, experience, joining_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $name, $position, $mobileNo, $address, $email, $photo, $experience, $joining_date);

    if ($stmt->execute()) {
        echo "<script>
                alert('Employee added successfully!');
                window.location.href = 'admin.php';
              </script>";
    } else {
        echo "Error: " . $stmt->error;
    }


    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee</title>
    <link rel="icon" type="image/x-icon" href="../images/logo-modified.png">
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="../footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, black, red);
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #000;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: red;
        }

        .menu-toggle {
            display: none;
            font-size: 24px;
            cursor: pointer;
        }

        .nav-links {
            display: flex;
            gap: 20px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            transition: 0.3s;
        }

        .nav-links a:hover {
            color: red;
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
                flex-direction: column;
                position: absolute;
                top: 60px;
                left: 0;
                background: black;
                width: 100%;
                padding: 10px 0;
            }

            .nav-links.show {
                display: flex;
            }

            .menu-toggle {
                display: block;
            }
        }

        .form-container {
            background: linear-gradient(135deg, red, black);
            color: white;
            padding: 30px;
            width: 750px;
            border-radius: 15px;
            width: 90%;
            max-width: 750px;
            margin: 50px auto;
            box-shadow: 0 0 15px rgba(255, 0, 0, 0.6);
            transition: transform 0.3s ease-in-out;
        }

        .form-container:hover {
            transform: scale(1.03);
            box-shadow: 0 0 20px rgba(255, 0, 0, 0.8);
        }


        h2 {
            text-align: center;
            margin-bottom: 20px;
            text-transform: uppercase;
            font-size: 22px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background: #222;
            color: white;
            transition: 0.3s;
        }

        input:focus,
        textarea:focus {
            outline: none;
            border: 2px solid red;
            background: #333;
        }

        .form-row {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            /* Add spacing between input fields */
        }

        .form-row .form-group {
            width: 50%;
            /* Adjust width if needed */
        }


        button {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            background: red;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            color: white;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #ff1a1a;
            box-shadow: 0 0 10px red;
            transform: scale(1.05);
        }

        input[type="file"] {
            background: none;
            border: none;
            color: #ccc;
            cursor: pointer;
        }

        input[type="file"]::-webkit-file-upload-button {
            background: red;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease-in-out, transform 0.2s ease-in-out;
        }

        input[type="file"]::-webkit-file-upload-button:hover {
            background: #ff1a1a;
            transform: scale(1.05);
            box-shadow: 0 0 8px rgba(255, 0, 0, 0.6);
        }

        .button-container {
            width: 90%;
            max-width: 300px;
            margin: 10px auto;
            display: flex;
            justify-content: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="menu-toggle" onclick="toggleMenu()">&#9776;</div>
        <nav>
            <div class="nav-links" id="navLinks">
                <a href="../index.html">Home</a>
                <a href="../About_Us/about_us.html">About</a>
                <a href="../Services/services.html">Services</a>
                <a href="#">Projects</a>
            </div>
        </nav>
    </div>

    <script>
        function toggleMenu() {
            document.getElementById("navLinks").classList.toggle("show");
        }
    </script>
    <div class="form-container">
        <h2>Add Employee</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label>Name:</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Position:</label>
                    <input type="text" name="position" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Mobile No:</label>
                    <input type="text" name="mobileNo" required pattern="[0-9]{10,15}"
                        title="Enter a valid mobile number">
                </div>
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Experience:</label>
                    <input type="text" name="experience" required>
                </div>
                <div class="form-group">
                    <label>Joining Date:</label>
                    <input type="date" name="joining_date" required>
                </div>
            </div>

            <div class="form-group">
                <label>Address:</label>
                <textarea name="address" required></textarea>
            </div>

            <div class="form-group">
                <label>Photo:</label>
                <input type="file" name="photo" accept="image/*">
            </div>

            <div class="button-container">
                <button id="s1" type="submit">Add Employee</button>
            </div>

        </form>
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

</body>

</html>