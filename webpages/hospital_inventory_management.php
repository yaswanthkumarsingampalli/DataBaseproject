<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Blood Inventory Management</title>
    <style>
        /* Apply styles to the body */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 0;
        }

        /* Style the container */
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Style the heading */
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        /* Style the form */
        .form-section {
            margin-bottom: 20px;
        }

        .form-section label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        select, button {
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: calc(50% - 12px); /* Adjust width here */
            max-width: 200px; /* Limit maximum width */
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Style the table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        /* Style the 'Back to Home' button */
        .btn {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .container {
                width: 90%;
            }

            table {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Blood Inventory Management</h2>
    <div class="form-section">
        <h3>Select ABO Group and Rh Factor</h3>
        <form method="post">
            <label for="abo_group">Select ABO Group:</label>
            <select id="abo_group" name="abo_group">
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="AB">AB</option>
                <option value="O">O</option>
            </select>
            <label for="rh_factor">Select Rh Factor:</label>
            <select id="rh_factor" name="rh_factor">
                <option value="+">+</option>
                <option value="-">-</option>
            </select><br>
            <button type="submit" name="submit">Submit</button>
        </form>
    </div>

    <div class="result-section">
        <h3>Search Results</h3>
        <table>
            <tr>
                <th>Collected Date</th>
                <th>Expiry Date</th>
                <th>Storage Location</th>
                <th>Donor First Name</th>
                <th>Donor Last Name</th>
                <th>Blood Volume (ml)</th>
                <th>Hemoglobin Level</th>
            </tr>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
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

                // Fetch user-selected ABO group and Rh factor
                $abo_group = $_POST['abo_group'];
                $rh_factor = $_POST['rh_factor'];

                // Fetch blood inventory details based on selected ABO group and Rh factor
                $sql = "SELECT bi.collected_date, bi.expiry_date, bi.storage_location, d.first_name, d.last_name, dn.blood_volume, dn.hemoglobin_level
                        FROM BloodInventory bi
                        INNER JOIN Donors d ON bi.donor_id = d.donor_id
                        INNER JOIN Donations dn ON d.donor_id = dn.donor_id
                        WHERE d.blood_type_id = (
                            SELECT blood_type_id FROM BloodTypes WHERE abo_group = ? AND rh_factor = ?
                        )";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $abo_group, $rh_factor);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["collected_date"] . "</td>";
                        echo "<td>" . $row["expiry_date"] . "</td>";
                        echo "<td>" . $row["storage_location"] . "</td>";
                        echo "<td>" . $row["first_name"] . "</td>";
                        echo "<td>" . $row["last_name"] . "</td>";
                        echo "<td>" . $row["blood_volume"] . "</td>";
                        echo "<td>" . $row["hemoglobin_level"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No blood inventory available for the selected ABO group and Rh factor.</td></tr>";
                }

                // Close the database connection
                $conn->close();
            }
            ?>
        </table>
    </div>
    <a class="btn" href="home.html">Back to Home</a>
</div>

</body>
</html>
