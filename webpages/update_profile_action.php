<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Include config.php for database connection
include_once 'config.php';

$email = $_SESSION['email'];
$user_type = $_SESSION['user_type'];

// Fetch user information based on user type
if ($user_type == 'donor') {
    $query = "SELECT d.*, bt.abo_group, d.rh_factor 
              FROM Donors d
              LEFT JOIN BloodTypes bt ON d.blood_type_id = bt.blood_type_id
              WHERE email_address='$email'";
} elseif ($user_type == 'hospital') {
    $query = "SELECT h.*, bt.abo_group, h.rh_factor 
              FROM Hospitals h
              LEFT JOIN BloodTypes bt ON h.blood_type_id = bt.blood_type_id
              WHERE email_address='$email'";
} elseif ($user_type == 'recipient') {
    $query = "SELECT r.*, bt.abo_group, r.rh_factor 
              FROM Recipients r
              LEFT JOIN BloodTypes bt ON r.blood_type_id = bt.blood_type_id
              WHERE email_address='$email'";
}

$result = $conn->query($query);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // You can display user information here
    $name = $row['first_name'] . ' ' . $row['last_name'];
    $dob = $row['date_of_birth'];
    $address = $row['address'];
    $phone = $row['phone_number'];
    // Additional fields for specific user types
    if ($user_type == 'donor') {
        $aboGroup = $row['abo_group'];
        $rhFactor = $row['rh_factor'];
    } elseif ($user_type == 'hospital') {
        $hospitalName = $row['name'];
    } elseif ($user_type == 'recipient') {
        $medicalCondition = $row['medical_condition_category'];
    }
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile - Blood Connect</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <div class="update-profile-container">
        <h2>Your Profile</h2>
        <p>
            <strong>Name:</strong> <?php echo $name; ?><br>
            <strong>Email:</strong> <?php echo $email; ?><br>
            <strong>Date of Birth:</strong> <?php echo $dob; ?><br>
            <strong>Address:</strong> <?php echo $address; ?><br>
            <strong>Phone:</strong> <?php echo $phone; ?><br>
            <?php if ($user_type == 'donor') : ?>
                <strong>Blood Type:</strong> <?php echo $aboGroup . ($rhFactor ? $rhFactor : ''); ?><br>
            <?php endif; ?>
        </p>
        <form action="update_profile.php" method="GET">
            <button type="submit">Update Profile</button>
        </form>
        <form action="dashboard.php" method="GET">
            <button type="submit">Go to Dashboard</button>
        </form>
    </div>
</body>

</html>
