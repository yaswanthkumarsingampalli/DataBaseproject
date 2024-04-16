<?php
session_start();

// Include database connection
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "BloodConnect";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Fetch logged-in user's email from session
$email = $_SESSION['email'];

// Fetch user's donor ID from the database
$stmt = $conn->prepare("SELECT donor_id FROM Donors WHERE email_address = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$donor_id = $user['donor_id'];

// Fetch donation history from BloodInventory table
$stmt = $conn->prepare("SELECT collected_date, expiry_date, storage_location FROM BloodInventory WHERE donor_id = ?");
$stmt->bind_param("i", $donor_id);
$stmt->execute();
$result = $stmt->get_result();
$donation_history = $result->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation History</title>
    <style>
        /* body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        } */

        body {
            font-family: Arial, sans-serif;
            background-image: url("donation_history_background.jpg"); /* Background image */
            background-size: cover; /* Cover the entire viewport */
            background-position: center; /* Center the background image */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: rgba(255, 255, 255, 0.6);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            color: #333;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #8d0202;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }

        .btn:hover {
            background-color: #600101;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Donation History</h1>
        <?php if (!empty($donation_history)) : ?>
            <table>
                <thead>
                    <tr>
                        <th>Donation Date</th>
                        <!-- <th>Expiry Date</th> -->
                        <th>Blood Drive Location</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($donation_history as $donation) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($donation['collected_date']); ?></td>
                            <!-- <td><?php echo htmlspecialchars($donation['expiry_date']); ?></td> -->
                            <td><?php echo htmlspecialchars($donation['storage_location']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No donation history available.</p>
        <?php endif; ?>
        <a class="btn" href="home.html">Back to Home</a>
    </div>
</body>
</html>

