<?php
include_once '../db_connect.php';

// Fetch finance data
$invoice_number = $_GET['invoice_number'];
$finance_query = "SELECT * FROM finance WHERE invoice_number = '$invoice_number'";
$finance_result = $conn->query($finance_query);

if ($finance_result->num_rows > 0) {
    $finance = $finance_result->fetch_assoc();
} else {
    die("Invoice not found.");
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Invoice - Modernarch</title>
    <link rel="icon" type="image/x-icon" href="../images/logo-modified.png">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            padding: 40px;
        }

        .invoice-box {
            background-color: #fff;
            border: 5px solid #4CAF50;
            border-radius: 10px;
            padding: 20px;
            max-width: 750px;
            margin: 0 auto;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 10px;
            border-bottom: 3px solid #4CAF50;
            margin-bottom: 10px;
        }

        .header img {
            width: 75px;
            border-radius: 10px;
        }

        .company-info {
            text-align: right;
        }

        h1 {
            text-align: center;
            color: #4CAF50;
            margin-bottom: 5px;
        }

        .invoice-details p {
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: #fff;
        }

        .total-row {
            background-color: #f0f7f0;
            font-weight: bold;
        }

        .signature {
            margin-top: 60px;
            text-align: right;
        }

        .signature img {
            width: 150px;
        }

        .signature p {
            margin-top: -10px;
            font-weight: bold;
        }

        .notes {
            background-color: #fff3cd;
            border-left: 5px solid #ffc107;
            padding: 7px;
            margin-top: 20px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <div class="header">
            <img src="../images/logo.jpg" alt="Modernarch Logo">
            <div class="company-info">
                <p><strong>Modernarch Construction Services</strong></p>
                📍 Pandeypur, Varanasi 221001 |
                📞 +91 63932 21303 |
                📧 modernarch.vns@gmail.com
            </div>
        </div>

        <h1>INVOICE</h1>

        <div class="invoice-details">
            <p><strong>Invoice Number:</strong> <?php echo $finance['invoice_number']; ?></p>
            <p><strong>Client Name:</strong> <?php echo $finance['client_name']; ?></p>
            <p><strong>Month/Year:</strong> <?php echo $finance['month'] . '/' . $finance['year']; ?></p>
            <p><strong>Payment Method:</strong> <?php echo $finance['payment_method']; ?></p>
            <p><strong>Payment Status:</strong> <?php echo $finance['payment_status']; ?></p>
        </div>

        <table>
            <tr>
                <th>Description</th>
                <th>Amount (INR)</th>
            </tr>
            <tr>
                <td>Material Cost</td>
                <td>₹<?php echo $finance['material_cost']; ?></td>
            </tr>
            <tr>
                <td>Labor Cost</td>
                <td>₹<?php echo $finance['labor_cost']; ?></td>
            </tr>
            <tr>
                <td>Equipment Cost</td>
                <td>₹<?php echo $finance['equipment_cost']; ?></td>
            </tr>
            <tr>
                <td>Miscellaneous Cost</td>
                <td>₹<?php echo $finance['miscellaneous_cost']; ?></td>
            </tr>
            <tr class="total-row">
                <td><strong>Subtotal</strong></td>
                <td>₹<?php echo $finance['revenue']; ?></td>
            </tr>
            <tr>
                <td>Tax (GST)</td>
                <td>₹<?php echo $finance['tax']; ?></td>
            </tr>
            <tr class="total-row">
                <td><strong>Grand Total</strong></td>
                <td>₹<?php echo $finance['revenue'] + $finance['tax']; ?></td>
            </tr>
        </table>

        <div class="signature">

            <p>Saurabh Patel</p>
        </div>

        <div class="notes">
            <p><strong>Notes:</strong> <?php echo $finance['notes']; ?></p>
        </div>

        <div class="footer">
            <p><strong>Payment Instructions:</strong> Please transfer payment to Modernarch Construction Services.<br>
                For inquiries, contact +91 63932 21303.</p>
        </div>
    </div>
</body>

</html>