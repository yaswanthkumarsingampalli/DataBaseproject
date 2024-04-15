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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $blood_donation_type = $_POST['blood_donation_type'];
    $medical_condition_category = $_POST['medical_condition_category'];
    $urgency_level = $_POST['urgency_level'];
    $address = $_POST['address'];
    $additional_info = $_POST['additional_info'];
    $email = $_SESSION['email']; // Get email from session

    if ($blood_donation_type == 'For Yourself') {
        // Query to get donor details based on email
        $sql = "SELECT * FROM Donors WHERE email_address = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Prepare SQL statement for inserting data into Recipients table
            $stmt = $conn->prepare("INSERT INTO Recipients (first_name, last_name, address, phone_number, medical_condition_category, urgency_level, email_address, additional_info, blood_donation_type, blood_type_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssissssi", $row['first_name'], $row['last_name'], $address, $row['phone_number'], $medical_condition_category, $urgency_level, $email, $additional_info, $blood_donation_type, $row['blood_type_id']);

            // Execute SQL statement
            if ($stmt->execute() === TRUE) {
                echo "Request submitted successfully";
            } else {
                echo "Error: " . $stmt->error;
            }

            // Close statement
            $stmt->close();
        } else {
            echo "Donor details not found for the logged in user.";
        }
    } else if ($blood_donation_type == 'For Others') {
        // Retrieve form data for recipient
        $recipient_first_name = $_POST['first_name'];
        $recipient_last_name = $_POST['last_name'];
        $recipient_blood_group = $_POST['blood_group'];
        $recipient_medical_condition_category = $_POST['medical_condition_category'];
        $recipient_urgency_level = $_POST['urgency_level'];
        $recipient_email = $_POST['email_address'];
        $recipient_phone_number = $_POST['phone_number'];

        // Prepare SQL statement for inserting data into Recipients table for others
        $stmt = $conn->prepare("INSERT INTO Recipients (first_name, last_name, address, phone_number, medical_condition_category, urgency_level, email_address, additional_info, blood_donation_type, blood_group) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssisssss", $recipient_first_name, $recipient_last_name, $address, $recipient_phone_number, $recipient_medical_condition_category, $recipient_urgency_level, $recipient_email, $additional_info, $blood_donation_type, $recipient_blood_group);

        // Execute SQL statement
        if ($stmt->execute() === TRUE) {
            echo "Request submitted successfully";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    }
}

// Close database connection
$conn->close();
?>
