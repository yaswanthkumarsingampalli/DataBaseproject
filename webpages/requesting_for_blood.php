<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

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

$email = $_SESSION['email'];
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $blood_donation_type = $_POST['blood_donation_type'];

    if ($blood_donation_type == 'For Yourself') {
        $medical_condition_category = $_POST['medical_condition_category_yourself'];
        $urgency_level = $_POST['urgency_level_yourself'];
        $additional_info = $_POST['additional_info_yourself'];
        // Query to get donor details based on email
        $sql = "SELECT * FROM Donors WHERE email_address = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Prepare SQL statement for inserting data into Recipients table
            $stmt = $conn->prepare("INSERT INTO Recipients (first_name, last_name, address, phone_number, medical_condition_category, urgency_level, email_address, additional_info, blood_type_id, blood_group) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssss", $row['first_name'], $row['last_name'], $row['address'], $row['phone_number'], $medical_condition_category, $urgency_level, $email, $additional_info, $row['blood_type_id'], $row['abo_group']);

            // Execute SQL statement
            if ($stmt->execute() === TRUE) {
                $message = "Request submitted successfully";
            } else {
                $message = "Error: " . $stmt->error;
            }

            // Close statement
            $stmt->close();
        } else {
            $message = "Donor details not found for the logged-in user.";;
        }
    } else if ($blood_donation_type == 'For Others') {
        // Retrieve form data for recipient
        $recipient_first_name = $_POST['recipient_first_name'];
        $recipient_last_name = $_POST['recipient_last_name'];
        $recipient_blood_group = $_POST['blood_group'];
        $recipient_medical_condition_category = $_POST['medical_condition_category_others'];
        $recipient_urgency_level = $_POST['urgency_level_others'];
        $recipient_email = $_POST['email_address'];
        $recipient_phone_number = $_POST['phone_number'];
        $additional_info = $_POST['additional_info_others'];
        $address = $_POST['address'];

        // Query to get the blood type ID based on the recipient's blood group
        $sql = "SELECT blood_type_id FROM BloodTypes WHERE CONCAT(abo_group, rh_factor) = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $recipient_blood_group);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $blood_type_id = $row['blood_type_id'];

            // Prepare SQL statement for inserting data into Recipients table for others
            $stmt = $conn->prepare("INSERT INTO Recipients (first_name, last_name, address, phone_number, medical_condition_category, urgency_level, email_address, additional_info, blood_group, blood_type_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssssi", $recipient_first_name, $recipient_last_name, $address, $recipient_phone_number, $recipient_medical_condition_category, $recipient_urgency_level, $recipient_email, $additional_info, $recipient_blood_group, $blood_type_id);

            // Execute SQL statement
            if ($stmt->execute() === TRUE) {
                $message = "Your request has been submitted successfully.";;
            } else {
                $message = "Error: " . $stmt->error;;
            }

            // Close statement
            $stmt->close();
        } else {
            $message= "Blood type not found for the specified blood group.";
        }
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
  <title>Submission Result</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-image: url('last_image.png'); /* Replace 'background.jpg' with your image file */
      background-size: cover;
      background-position: center;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .container {
      text-align: center;
      background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent white background */
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    h2 {
      color: #333;
      margin-bottom: 20px;
    }

    .message {
      margin-bottom: 20px;
      font-size: 18px;
      color: #007bff;
    }

    .options {
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .options a {
      margin: 0 10px;
      padding: 12px 24px;
      text-decoration: none;
      color: #fff;
      background-color: #007bff;
      border-radius: 6px;
      transition: background-color 0.3s ease;
      font-size: 16px;
      border: 2px solid #007bff;
    }

    .options a:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Submission Result</h2>
    <div class="message">
      <p><?php echo $message; ?></p>
    </div>
    <div class="options">
      <a href="logout.php">Logout</a>
      <a href="home.html">Back to Home</a>
    </div>
  </div>
</body>
</html>
