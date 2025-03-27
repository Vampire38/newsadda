<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['loginEmail']);
    $password = $_POST['loginPassword'];

    if (empty($email) || empty($password)) {
        echo "<script>alert('Both fields are required.');</script>";
    } else {
        $sql = "SELECT * FROM users WHERE email='$email'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                echo "<script>alert('Login successful. Welcome, " . $row['name'] . "!');</script>";
                echo "<script>window.location.href = 'index.html';</script>";
                exit();
            } else {
                echo "<script>alert('Invalid password. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('No user found with this email.');</script>";
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
  <title>Login</title>
</head>
<body>
  <div class="container">
    <h2>Login</h2>
    <form method="POST" action="">
      <label for="loginEmail">Email</label>
      <input type="email" id="loginEmail" name="loginEmail" placeholder="Enter your email" required />

      <label for="loginPassword">Password</label>
      <input type="password" id="loginPassword" name="loginPassword" placeholder="Enter your password" required />

      <button type="submit">Login</button>
      <p>Don't have an account? <a href="signup.php">Sign Up here</a></p>
    </form>
  </div>
</body>
</html>
