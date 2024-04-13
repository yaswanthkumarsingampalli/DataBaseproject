<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <img src="logo.png" alt="Save Lives with blood">
    <h1>Blood Management System</h1>
    <?php
    // Check if error parameter exists
    if(isset($_GET['error'])) {
        // Display error message
        echo "<p style='color: red;'>".$_GET['error']."</p>";
    }
    ?>
    <form action="login.php" method="post">
      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required>
      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required>
      <button type="submit" name="login">Login</button>
      <a href="signup.html">Sign Up</a>
    </form>
  </div>
</body>
</html>
