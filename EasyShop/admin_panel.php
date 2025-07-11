<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel - EasyShop</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      background-color: #f4f4f4;
    }

    .admin-header {
      background-color: #ff9900;
      color: white;
      padding: 20px;
      text-align: center;
    }

    .admin-header h1 {
      margin: 0;
      font-size: 28px;
    }

    .admin-actions {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin: 20px 0;
    }

    .admin-actions a {
      text-decoration: none;
      background-color: #333;
      color: white;
      padding: 10px 20px;
      border-radius: 6px;
      font-weight: bold;
      transition: background 0.3s;
    }

    .admin-actions a:hover {
      background-color: #222;
    }

    .product-table {
      width: 95%;
      margin: 0 auto 40px;
      border-collapse: collapse;
      background-color: white;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .product-table th, .product-table td {
      padding: 12px 15px;
      border: 1px solid #ddd;
      text-align: center;
    }

    .product-table th {
      background-color: #f2f2f2;
    }

    .product-table img {
      width: 60px;
      height: auto;
      border-radius: 5px;
    }

    .action-links a {
      color: #007BFF;
      text-decoration: none;
      margin: 0 5px;
      font-weight: bold;
    }

    .action-links a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<?php include('header.php'); ?>

<div class="admin-header">
  <h1>Welcome to EasyShop Admin Panel</h1>
</div>

<div class="admin-actions">
  <a href="admin_page.php"><i class="fas fa-plus"></i> Add Product</a>
  <a href="admin_logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<?php
$conn = new mysqli("localhost", "root", "", "easyshop");
if ($conn->connect_error) die("DB Error: " . $conn->connect_error);

$result = $conn->query("SELECT * FROM products");

echo "<table class='product-table'>";
echo "<tr><th>ID</th><th>Name</th><th>Price</th><th>Category</th><th>Image</th><th>Action</th></tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['id']}</td>
            <td>" . htmlspecialchars($row['name']) . "</td>
            <td>$" . htmlspecialchars($row['price']) . "</td>
            <td>" . htmlspecialchars($row['category']) . "</td>
            <td><img src='{$row['image']}' alt='{$row['name']}'></td>
            <td class='action-links'>
                <a href='edit_product.php?id={$row['id']}'>Edit</a> | 
                <a href='delete_product.php?id={$row['id']}' onclick='return confirm(\"Are you sure you want to delete this product?\");'>Delete</a>
            </td>
          </tr>";
}

echo "</table>";
$conn->close();
?>

</body>
</html>
