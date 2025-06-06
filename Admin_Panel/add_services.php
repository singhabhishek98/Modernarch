<?php
include_once '../db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service_name = trim($_POST["service_name"]);
    $description = trim($_POST["description"]);
    $icon = trim($_POST["icon"]);

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO services (service_name, description, icon) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $service_name, $description, $icon);

    if ($stmt->execute()) {
        echo "<script>
                alert('Service added successfully!');
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
    <title>Add Services</title>
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

        .icon-dropdown {
            position: relative;
            width: 100%;
        }

        .icon-dropdown select {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;
            background: #222;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        .icon-dropdown select:hover {
            background: #333;
            border: 2px solid red;
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
        <h2>Add Services</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label>Service Name:</label>
                <input type="text" name="service_name" required>
            </div>


            <div class="form-group">
                <label>Description:</label>
                <input type="text" name="description" required>
            </div>


            <div class="form-group">
                <label for="category">Select Category:</label>
                <div class="icon-dropdown">
                    <select name="category" id="category" required>
                        <option value="" disabled selected>Select an option</option>
                        <option value="technology">🖥️ Technology</option>
                        <option value="design">🎨 Design</option>
                        <option value="consulting">📊 Consulting</option>
                        <option value="construction">🏗️ Construction</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="icon">Select Construction Work:</label>
                <div class="icon-dropdown">
                    <select name="icon" id="icon" required>
                        <option value="" disabled selected>Select an option</option>
                        <option value="house">🏠 House Construction & Renovation</option>
                        <option value="building">🏢 Commercial Building Small Projects</option>
                        <option value="boundary-wall">🧱 Boundary Wall Construction</option>
                        <option value="roof-repair">🏗️ Roof Repair & Waterproofing</option>
                        <option value="retaining-wall">🧱 Retaining Wall Construction</option>
                        <option value="site-office">🚧 Temporary Site Office Setup</option>
                        <option value="steel-structure">🏭 Steel Structure Fabrication</option>
                        <option value="warehouse">🏭 Warehouse or Shed Construction</option>
                        <option value="fencing">🚧 Fencing & Gate Installation</option>
                        <option value="foundation-repair">🔨 Foundation Repair & Strengthening</option>

                        <!-- Concrete & Masonry Work -->
                        <option value="brickwork">🧱 Brickwork & Block Masonry</option>
                        <option value="plastering">🎨 Plastering & Wall Finishing</option>
                        <option value="paver-block">🏗️ Paver Block & Tile Installation</option>
                        <option value="flooring">🔨 Concrete Flooring & Leveling</option>
                        <option value="beam-column">🏗️ Beam & Column Reinforcement</option>
                        <option value="staircase">🔨 Staircase & Ramp Construction</option>
                        <option value="precast">🧱 Precast Concrete Installation</option>
                        <option value="walkway">🚧 Walkway & Footpath Construction</option>
                        <option value="curbstone">🚧 Sidewalk & Curb Stone Installation</option>
                        <option value="slab-casting">🏗️ Concrete Slab Casting</option>

                        <!-- Utilities & Services -->
                        <option value="electrical">⚡ Electrical Wiring & Fitting</option>
                        <option value="plumbing">🚰 Plumbing & Pipe Fitting</option>
                        <option value="fire-safety">🧯 Fire Safety System Installation</option>
                        <option value="rainwater">♻️ Rainwater Harvesting System</option>
                        <option value="underground-tank">🚰 Underground Water Tank Construction</option>
                        <option value="overhead-tank">🚰 Overhead Water Tank Installation</option>
                        <option value="street-light">⚡ Street Light Pole Installation</option>
                        <option value="solar-panel">⚡ Solar Panel Mounting & Setup</option>
                        <option value="ventilation">🏭 Air Conditioning Duct & Ventilation Setup</option>
                        <option value="borewell">🚰 Borewell & Water Pump Installation</option>

                        <!-- Road & Landscaping -->
                        <option value="road-repair">🛣️ Road Repair & Pothole Filling</option>
                        <option value="access-road">🛣️ Temporary Access Road Construction</option>
                        <option value="parking-lot">🚧 Driveway & Parking Lot Construction</option>
                        <option value="land-leveling">🛒 Land Leveling & Grading</option>
                        <option value="speed-breaker">🛣️ Speed Breaker & Road Marking</option>
                        <option value="drainage">🚰 Roadside Drainage & Gutter Construction</option>
                        <option value="landscaping">🎨 Garden & Landscape Development</option>
                        <option value="recycling">♻️ Recycling & Waste Management Projects</option>
                        <option value="transportation">🚛 Construction Material Transportation</option>
                        <option value="safety">⛑️ Engineer & Worker Safety Gear Implementation</option>
                    </select>
                </div>
            </div>



            <div class="button-container">
                <button type="submit">Add Service</button>
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