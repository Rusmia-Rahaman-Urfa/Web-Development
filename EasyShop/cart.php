<?php
session_start();

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$subtotal = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Cart - EasyShop</title>
  <link rel="stylesheet" href="css/cart.css">
</head>
<body>
  <nav class="navbar">
    <a href="index.php"><strong>EasyShop</strong></a>
  </nav>

  <main>
    <h1 class="cart-title">Your Cart</h1>

    <div class="cart-container">
      <table class="cart-table">
        <thead>
          <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($cart as $id => $item): 
              $total = $item['price'] * $item['quantity'];
              $subtotal += $total;
          ?>
          <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td>$<?= $item['price'] ?></td>
            <td>
              <form action="update_cart.php" method="POST" style="display: inline-block;">
                <input type="hidden" name="id" value="<?= $id ?>">
                <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" style="width: 50px;">
                <button type="submit">Update</button>
              </form>
            </td>
            <td>$<?= $total ?></td>
            <td>
              <form action="remove_from_cart.php" method="POST">
                <input type="hidden" name="id" value="<?= $id ?>">
                <button type="submit">×</button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <div class="order-summary">
        <h3>Order Summary</h3>
        <p>Subtotal: $<?= $subtotal ?></p>
        <p>Shipping: Free</p>
        <hr>
        <p><strong>Total: $<?= $subtotal ?></strong></p>
        <form action="checkout.php" method="POST">
          <button class="checkout-btn">Checkout</button>
        </form>
      </div>
    </div>
  </main>
</body>
</html>
