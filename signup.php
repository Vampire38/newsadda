<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['signupName']);
    $email = mysqli_real_escape_string($conn, $_POST['signupEmail']);
    $password = password_hash($_POST['signupPassword'], PASSWORD_BCRYPT);

    if (empty($name) || empty($email) || empty($_POST['signupPassword'])) {
        echo "<script>alert('All fields are required.');</script>";
    } else {
        $checkEmail = "SELECT * FROM users WHERE email='$email'";
        $result = $conn->query($checkEmail);

        if ($result->num_rows > 0) {
            echo "<script>alert('Email already exists. Please use a different email.');</script>";
        } else {
            $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Signup successful! Redirecting to login page...');</script>";
                echo "<script>window.location.href='login.php';</script>";
                exit();
            } else {
                echo "<script>alert('Error: " . $conn->error . "');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="logg.css" />
  <title>Signup</title>
</head>
<body>
  <div class="container">
    <h2>Sign Up</h2>
    <form method="POST" action="">
      <label for="signupName">Full Name</label>
      <input type="text" id="signupName" name="signupName" placeholder="Enter your full name" required />

      <label for="signupEmail">Email</label>
      <input type="email" id="signupEmail" name="signupEmail" placeholder="Enter your email" required />

      <label for="signupPassword">Password</label>
      <input type="password" id="signupPassword" name="signupPassword" placeholder="Create a password" required />

      <button type="submit">Sign Up</button>
      <p>Already have an account? <a href="login.php">Login here</a></p>
    </form>
  </div>
</body>
</html>
