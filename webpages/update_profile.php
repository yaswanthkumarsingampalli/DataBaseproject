<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Update Profile</title>
<style>
body {
    font-family: Arial, sans-serif;
    background-image: url("update_profile_background.jpg"); /* Background image */
    background-size: cover; /* Cover the entire viewport */
    background-position: center; /* Center the background image */
    /* display: flex; */
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 600px;
    margin: 20px auto;
    padding: 20px;
    background-color: rgba(255, 255, 255, 0.6);
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
    margin-top: 0;
    font-size: 24px;
    color: #333;
}

form {
    margin-top: 20px;
}

label {
    font-weight: bold;
}

input[type="text"],
select {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

button[type="submit"] {
    padding: 10px 20px;
    background-color: #600101;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

button[type="submit"]:hover {
    background-color: #8d0202;
}

a {
    display: block;
    margin-top: 10px;
    text-decoration: none;
    color: #600101;
}

a:hover {
    color: #8d0202;
}
</style>
</head>
<body>
<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect to login page if not logged in
    header("Location: Loginpage.php");
    exit();
}

// Initialize message variable
$message = '';

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

// Retrieve user's email from session
$email = $_SESSION['email'];

// Check if user is a donor
$sql_check_donor = "SELECT * FROM Donors WHERE email_address = '$email'";
$result_check_donor = $conn->query($sql_check_donor);

if ($result_check_donor->num_rows > 0) {
    // User is a donor, fetch donor details
    $sql_donor = "SELECT * FROM Donors WHERE email_address = '$email'";
    $result_donor = $conn->query($sql_donor);
    $row_donor = $result_donor->fetch_assoc();

    // Handle form submission
    if (isset($_POST['submit'])) {
        $address = $_POST['address'];
        $phone_number = $_POST['phone_number'];
        // $abo_group = $_POST['abo_group'];

        // Update donor's address, phone number, and blood type
        $sql_update_donor = "UPDATE Donors SET address='$address', phone_number='$phone_number' WHERE email_address='$email'";
        if ($conn->query($sql_update_donor) === TRUE) {
            $message = "Record updated successfully";
        } else {
            $message = "Error updating record: " . $conn->error;
        }

        // Fetch updated donor details
        $result_donor = $conn->query($sql_donor);
        $row_donor = $result_donor->fetch_assoc();
    }

    // Display donor details
    echo "<div class='container'>";
    echo "<h2>Update Profile</h2>";
    echo "<p>Name: " . $row_donor['first_name'] . " " . $row_donor['last_name'] . "</p>";
    echo "<p>Date of Birth: " . $row_donor['date_of_birth'] . "</p>";
    echo "<p>Blood Group: " . $row_donor['abo_group'] . "</p>";
    echo "<form method='post'>";
    echo "<label for='address'>Address:</label>";
    echo "<input type='text' id='address' name='address' value='" . ($row_donor['address'] ?? '') . "' required><br>";
    echo "<label for='phone_number'>Phone Number:</label>";
    echo "<input type='text' id='phone_number' name='phone_number' value='" . ($row_donor['phone_number'] ?? '') . "' required><br>";
    
    // echo "<select id='abo_group' name='abo_group' required>";
    // $options = ['A+', 'B+', 'AB+', 'O+', 'A-', 'B-', 'AB-', 'O-'];
    // foreach ($options as $option) {
    //     $selected = ($option == ($row_donor['abo_group'] ?? '')) ? 'selected' : '';
    //     echo "<option value='$option' $selected>$option</option>";
    // }
    echo "</select><br>";
    echo "<button type='submit' name='submit'>Update</button>";
    echo "</form>";
    echo "<a href='home.html'>Back to Home</a>";
    echo "<a href='logout.php'>Logout</a>";
    echo "<p>$message</p>"; // Display message here
    echo "</div>";
} else {
    // User is a hospital, fetch hospital details
    $sql_hospital = "SELECT * FROM Hospitals WHERE email_address = '$email'";
    $result_hospital = $conn->query($sql_hospital);
    $row_hospital = $result_hospital->fetch_assoc();

    // Handle form submission
    if (isset($_POST['submit'])) {
        $address = $_POST['address'];
        $phone_number = $_POST['phone_number'];

        // Update hospital's address and phone number
        $sql_update_hospital = "UPDATE Hospitals SET address='$address', phone_number='$phone_number' WHERE email_address='$email'";
        if ($conn->query($sql_update_hospital) === TRUE) {
            $message = "Record updated successfully";
        } else {
            $message = "Error updating record: " . $conn->error;
        }

        // Fetch updated hospital details
        $result_hospital = $conn->query($sql_hospital);
        $row_hospital = $result_hospital->fetch_assoc();
    }

    // Display hospital details
    echo "<div class='container'>";
    echo "<h2>Update Profile</h2>";
    echo "<p>Name: " . $row_hospital['name'] . "</p>";
    echo "<form method='post'>";
    echo "<label for='address'>Address:</label>";
    echo "<input type='text' id='address' name='address' value='" . ($row_hospital['address'] ?? '') . "' required><br>";
    echo "<label for='phone_number'>Phone Number:</label>";
    echo "<input type='text' id='phone_number' name='phone_number' value='" . ($row_hospital['phone_number'] ?? '') . "' required><br>";
    echo "<button type='submit' name='submit'>Update</button>";
    echo "</form>";
    echo "<a href='hospitalhome.html'>Back to Hospital Home</a>";
    echo "<a href='logout.php'>Logout</a>";
    echo "<p>$message</p>"; // Display message here
    echo "</div>";

}

// Close connection
$conn->close();
?>

</body>
</html>
