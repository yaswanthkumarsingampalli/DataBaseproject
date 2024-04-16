/* Reset default browser styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }
  
  /* body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    background-image: url('profile-background-image.jpg'); 
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
  }
   */
  body {
    font-family: Arial, sans-serif;
    background-image: url("profile-background-image.jpg"); /* Background image */
    background-size: cover; /* Cover the entire viewport */
    background-position: center; /* Center the background image */
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
  }

  .container {
    max-width: 500px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    position: absolute; /* or relative */
    top: 15%;
    left: 40%;
  }
  
  h2 {
    color: #333;
    margin-bottom: 20px;
    font-size: 24px;
  }
  
  p {
    margin-bottom: 10px;
    color: #555;
  }
  
  a {
    text-decoration: none;
    color: #b60a0a;
    display: inline-block;
    margin-top: 20px;
    transition: color 0.3s ease;
  }
  
  a:hover {
    color: #7b0303;
  }
  
  /* Style logout button */
  .logout-btn {
    text-decoration: none;
    color: #333; /* Dark gray text */
    background-color: #ffffff; /* White background */
    padding: 10px 20px;
    border-radius: 5px;
    border: 2px solid #333; /* Dark gray border */
    position: absolute;
    top: 20px;
    right: 20px;
    transition: background-color 0.3s ease, color 0.3s ease;
  }
  
  .logout-btn:hover {
    background-color: #333; /* Dark gray background on hover */
    color: #ffffff; /* White text on hover */
  }
  
