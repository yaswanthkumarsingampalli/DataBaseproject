<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 800px;
      margin: 20px auto;
      padding: 20px;
      background-color: #fff;
      border-radius: 5px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    h2 {
      color: #333;
    }

    p {
      margin: 10px 0;
      color: #555;
    }

    a {
      text-decoration: none;
      color: #007bff;
      display: inline-block;
      margin-top: 20px;
    }

    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <?php
    session_start();

    // Check if user is logged in
    if (!isset($_SESSION['email'])) {
        // Redirect to login page if not logged in
        header("Location: Loginpage.php");
        exit();
    }

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

        // Display donor details
        echo "<h2>Your Profile</h2>";
        echo "<p>Name: " . $row_donor['first_name'] . " " . $row_donor['last_name'] . "</p>";
        echo "<p>Date of Birth: " . $row_donor['date_of_birth'] . "</p>";
        echo "<p>Address: " . $row_donor['address'] . "</p>";
        echo "<p>Email: " . $row_donor['email_address'] . "</p>";
        echo "<p>Phone Number: " . $row_donor['phone_number'] . "</p>";
        echo "<p>Blood Type: " . $row_donor['abo_group'] . "</p>";
    } else {
        // User is a hospital, fetch hospital details
        $sql_hospital = "SELECT * FROM Hospitals WHERE email_address = '$email'";
        $result_hospital = $conn->query($sql_hospital);
        $row_hospital = $result_hospital->fetch_assoc();

        // Display hospital details
        echo "<h2>Your Profile</h2>";
        echo "<p>Name: " . $row_hospital['name'] . "</p>";
        echo "<p>Address: " . $row_hospital['address'] . "</p>";
        echo "<p>Email: " . $row_hospital['email_address'] . "</p>";
        echo "<p>Phone Number: " . $row_hospital['phone_number'] . "</p>";
    }

    // Close connection
    $conn->close();
    ?>
    <a href='home.html'>Back to Home</a>
  </div>
</body>
</html>
