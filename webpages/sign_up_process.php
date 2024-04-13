<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // Check the selected account type
    $account_type = $_POST['account_type'];

    if ($account_type === 'donor') {
        // Handle donor registration
        // Prepare SQL statement to check if email already exists
        $check_email_sql = "SELECT email_address FROM Donors WHERE email_address = ?";
        $check_email_stmt = $conn->prepare($check_email_sql);
        $check_email_stmt->bind_param("s", $email);

        // Set parameter values
        $email = $_POST['email'];

        // Execute the statement
        $check_email_stmt->execute();
        $check_email_stmt->store_result();

        // Check if email already exists
        if ($check_email_stmt->num_rows > 0) {
            // Email already exists, redirect to signup page with error message
            header("Location: Loginpage.php?error=Donor Email already exists. Please use a different email.");
            exit(); // Ensure that no other code is executed after the redirect
        }

        // Close statement
        $check_email_stmt->close();

        // Prepare SQL statement to insert data into the Donors table
        $sql = "INSERT INTO Donors (first_name, last_name, date_of_birth, address, phone_number, email_address, password, blood_type_id, abo_group)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Prepare and bind parameters
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssiss", $first_name, $last_name, $date_of_birth, $address, $phone_number, $email, $password, $blood_type_id, $abo_group);

        // Set parameter values
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $date_of_birth = $_POST['date_of_birth'];
        $address = $_POST['address'];
        $phone_number = $_POST['phone_number'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $abo_group = $_POST['blood_type'];

        // Concatenate abo_group and rh_factor
        $abo_rh_concat = $abo_group . '+';
        $abo_rh_concat_neg = $abo_group . '-';

        // Retrieve blood_type_id from BloodTypes table based on abo_group
        $get_blood_type_id_sql = "SELECT blood_type_id FROM BloodTypes WHERE CONCAT(abo_group, rh_factor) = ?";
        $get_blood_type_id_stmt = $conn->prepare($get_blood_type_id_sql);
        $get_blood_type_id_stmt->bind_param("s", $abo_rh_concat);
        $get_blood_type_id_stmt->execute();
        $get_blood_type_id_stmt->bind_result($blood_type_id);

        // Fetch the result
        $get_blood_type_id_stmt->fetch();

        // Close statement
        $get_blood_type_id_stmt->close();

        // Set the retrieved blood_type_id
        $stmt->bind_param("ssssssiss", $first_name, $last_name, $date_of_birth, $address, $phone_number, $email, $password, $blood_type_id, $abo_group);
    } elseif ($account_type === 'hospital') {
        // Handle hospital registration
        // Prepare SQL statement to check if email already exists
        $check_email_sql = "SELECT email_address FROM Hospitals WHERE email_address = ?";
        $check_email_stmt = $conn->prepare($check_email_sql);
        $check_email_stmt->bind_param("s", $email);

        // Set parameter values
        $email = $_POST['hospital_email'];

        // Execute the statement
        $check_email_stmt->execute();
        $check_email_stmt->store_result();

        // Check if email already exists
        if ($check_email_stmt->num_rows > 0) {
            // Email already exists, redirect to signup page with error message
            header("Location: Loginpage.php?error=Hospital Email already exists. Please use a different email.");
            exit(); // Ensure that no other code is executed after the redirect
        }

        // Close statement
        $check_email_stmt->close();

        // Prepare SQL statement to insert data into the Hospitals table
        $sql = "INSERT INTO Hospitals (name, address, phone_number, email_address, password)
                VALUES (?, ?, ?, ?, ?)";

        // Prepare and bind parameters
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $name, $address, $phone_number, $email, $password);

        // Set parameter values
        $name = $_POST['hospital_name']; // Hospital Name for hospital registration
        $address = $_POST['hospital_address']; // Hospital Address for hospital registration
        $phone_number = $_POST['hospital_phone']; // Hospital Phone Number for hospital registration
        $email = $_POST['hospital_email']; // Hospital Email for hospital registration
        $password = $_POST['hospital_password']; // Hospital Password for hospital registration
    }

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to the login page after successful signup
        header("Location: Loginpage.php");
        exit(); // Ensure that no other code is executed after the redirect
    } else {
        // Handle errors
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
