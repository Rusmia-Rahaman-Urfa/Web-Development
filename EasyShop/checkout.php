<?php
session_start();
$_SESSION['cart'] = []; // Clear the cart
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Confirmed - EasyShop</title>
  <link rel="stylesheet" href="css/checkout.css">
</head>
<body>
  <nav class="navbar">
    <a href="index.php"><strong>EasyShop</strong></a>
  </nav>

  <main class="thank-you-container">
    <div class="thank-you-box">
      <h1>🎉 Thank You for Your Order!</h1>
      <p>Your items will be shipped soon.</p>
      <a href="index.php" class="home-btn">Back to Home</a>
    </div>
  </main>
</body>
</html>
