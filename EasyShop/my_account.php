<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: account.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Account - EasyShop</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .account-box {
            max-width: 500px;
            margin: 100px auto;
            padding: 30px;
            border: 1px solid #ccc;
            border-radius: 10px;
            text-align: center;
            background-color: #f9f9f9;
        }
        .account-box h2 {
            margin-bottom: 20px;
        }
        .account-box button {
            padding: 10px 20px;
            margin: 10px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
        }
        .logout-btn {
            background-color: orange;
            color: black;
        }
        .switch-btn {
            background-color: orange;
            color: black;
        }
    </style>
</head>
<body>

<div class="account-box">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['first_name']); ?>!</h2>
    <form action="logout.php" method="post">
        <button type="submit" class="logout-btn">Log Out</button>
    </form>
    <form action="account.php" method="get">
        <button type="submit" class="switch-btn">Login to Another Account</button>
    </form>
</div>

</body>
</html>