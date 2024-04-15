<?php
session_start();

// Include database connection
// Database connection parameters
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

// Fetch user's donor ID and blood type ID from the database
$stmt = $conn->prepare("SELECT donor_id, blood_type_id FROM Donors WHERE email_address = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$donor_id = $user['donor_id'];
$blood_type_id = $user['blood_type_id'];

// Check if user has previously donated blood and get the last donation date
$last_donation_date = null;
$stmt = $conn->prepare("SELECT MAX(donation_date) AS last_donation_date FROM Donations WHERE donor_id = ?");
$stmt->bind_param("i", $donor_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$last_donation_date = $row['last_donation_date'];

$donation_error = '';

// Process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if 60 days have passed since the last donation
    if ($last_donation_date !== null && strtotime($last_donation_date . " + 60 days") > time()) {
        // If less than 60 days have passed, set an error message
        $donation_error = "You are not allowed to donate blood until 60 days have passed since your last donation.";
    } else {
        // Sanitize and validate form inputs
        $donation_date = date("Y-m-d", strtotime($_POST["appointment_date"]));
        $blood_volume = $_POST["blood_volume"];
        $hemoglobin_level = $_POST["hemoglobin_level"];
        $storage_location = $_POST["blood_drive_location"];

        // Calculate expiry date (42 days from appointment date)
        $expiry_date = date("Y-m-d", strtotime($donation_date . " + 42 days"));

        // Insert data into Donations table
        $stmt = $conn->prepare("INSERT INTO Donations (donor_id, donation_date, blood_volume, hemoglobin_level) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isid", $donor_id, $donation_date, $blood_volume, $hemoglobin_level);
        $stmt->execute();

        // Insert data into BloodInventory table
        $stmt = $conn->prepare("INSERT INTO BloodInventory (donor_id, blood_type_id, collected_date, expiry_date, storage_location) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $donor_id, $blood_type_id, $donation_date, $expiry_date, $storage_location);
        $stmt->execute();

        // Redirect to a success page
        header("Location: donate_blood_success.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Donate Blood</title>
<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f2f2f2;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 600px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
    margin-top: 0;
    font-size: 24px;
    color: #333;
}

.error-message {
    color: red;
    font-size: 18px;
    margin-bottom: 20px;
}

.btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #4CAF50;
    color: #fff;
    text-decoration: none;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.btn:hover {
    background-color: #45a049;
}
</style>
</head>
<body>

<div class="container">
    <?php if (!empty($donation_error)) : ?>
        <p class="error-message"><?php echo $donation_error; ?></p>
    <?php endif; ?>

    <a class="btn" href="home.html">Back to Home</a>
</div>

</body>
</html>

