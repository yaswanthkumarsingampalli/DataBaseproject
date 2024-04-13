<?php
session_start();

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

    // Query to check if user exists in Hospitals table
    $sql_hospital = "SELECT * FROM Hospitals WHERE email_address = '$email' AND password = '$password'";
    $result_hospital = $conn->query($sql_hospital);

    if ($result_hospital->num_rows > 0) {
        // User found in Hospitals table, redirect to hospital home page
        $_SESSION['email'] = $email;
        header("Location: hospitalhome.html");
        exit();
    } else {
        // User not found in Hospitals table, check in Donors table
        $sql_donor = "SELECT * FROM Donors WHERE email_address = '$email' AND password = '$password'";
        $result_donor = $conn->query($sql_donor);

        if ($result_donor->num_rows > 0) {
            // User found in Donors table, redirect to home page
            $_SESSION['email'] = $email;
            header("Location: home.html");
            exit();
        } else {
            // User not found in both tables, redirect back to login page with error message
            header("Location: Loginpage.php?error=User not found. Please sign up.");
            exit();
        }
    }
}

$conn->close();
?>
