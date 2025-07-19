<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "easyshop";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

session_start();

if (!isset($_SESSION['user_email'])) {
    header("Location: account.php?error=Please login to view your order history.");
    exit();
}

$user_email = $_SESSION['user_email'];

$stmt = $conn->prepare("SELECT * FROM orders WHERE user_email = ? ORDER BY order_time DESC");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order History - EasyShop</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
    }
    h1 {
      text-align: center;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 30px;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 12px;
      text-align: center;
    }
    th {
      background-color: #ff9900;
      color: white;
    }
  </style>
</head>
<body>

<h1>🧾 Order History</h1>

<table>
  <tr>
    <th>Product</th>
    <th>Price (Tk)</th>
    <th>Quantity</th>
    <th>Total</th>
    <th>Time</th>
  </tr>
  <?php while ($row = $result->fetch_assoc()): ?>
  <tr>
    <td><?= htmlspecialchars($row['product_name']) ?></td>
    <td><?= $row['price'] ?></td>
    <td><?= $row['quantity'] ?></td>
    <td><?= $row['total'] ?></td>
    <td><?= $row['order_time'] ?></td>
  </tr>
  <?php endwhile; ?>
</table>

</body>
</html>