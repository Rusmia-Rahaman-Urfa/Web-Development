<?php
session_start();

// Check if logged in and is admin
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin_panel.php");
    exit();
} else {
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <title>Access Denied</title>
        <link rel='stylesheet' href='style.css'>
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background-color: #f8f8f8;
                text-align: center;
                padding-top: 100px;
            }
            .warning {
                color: red;
                font-size: 22px;
                margin-bottom: 20px;
            }
            a {
                color: #ff9900;
                text-decoration: none;
                font-size: 18px;
            }
        </style>
    </head>
    <body>
        <div class='warning'>🚫 You need to become a seller to open this section!</div>
        <a href='index.html'>← Back to Home</a>
    </body>
    </html>";
    exit();
}
?>
