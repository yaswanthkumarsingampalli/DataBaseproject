<?php
// Database connection parameters
$servername = "localhost:3306";
$username = "root";
$password = "Msdhoni77@";
$dbname = "BloodConnect";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(isset($_POST['login'])) {
    // Retrieve input values
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check if user exists
    $sql = "SELECT * FROM Donors WHERE email_address = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User found, redirect to home page or wherever you want
        header("Location: home.html");
        exit();
    } else {
        // User not found, redirect back to login page with error message
        header("Location: Loginpage.php?error=User not found. Please sign up.");
        exit();
    }
}

$conn->close();
?>
