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

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check for admin in database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND role = 'admin'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_email'] = $email;
            header("Location: admin_panel.php");
            exit();
        } else {
            $error = "Invalid email or password!";
        }
    } else {
        $error = "Invalid email or password!";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - EasyShop</title>
    <link rel="stylesheet" href="style.css"> <!-- Use your site's main style -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            margin: 100px auto;
            padding: 40px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 30px;
            color: #333;
        }

        .login-container input[type="email"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        .login-container button {
            width: 100%;
            padding: 12px;
            background-color: #ff9900;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 10px;
        }

        .login-container button:hover {
            background-color: #e68900;
        }

        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Admin Login</h2>
    <form method="POST">
        <input type="email" name="email" placeholder="Admin Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    </form>

    <p style="margin-top: 15px;">Don't have an account? <a href="admin_signup.php" style="color: #ff9900;">Sign up here</a></p>
</div>

</body>
</html>
