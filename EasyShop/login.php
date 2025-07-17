<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db = "easyshop";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 1) {
  $user = $result->fetch_assoc();
  if (password_verify($password, $user['password'])) {
    // Store user info in session if needed
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['first_name'] = $user['first_name'];

    session_start();
$_SESSION['user_email'] = $email; // Or user ID, name, etc.
header("Location:index.php"); // Or your actual front page file
exit();


    // Redirect to front page
    header("Location: index.php");
    exit();
  } else {
    echo "Incorrect password.";
  }
} else {
  echo "User not found.";
}

$conn->close();
?>