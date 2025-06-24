<?php
session_start();
include 'includes/db.php';

$id = intval($_POST['id']);
$quantity = max(1, intval($_POST['quantity']));

$result = $conn->query("SELECT * FROM products WHERE id = $id");

if ($result->num_rows === 0) {
    die("Product not found.");
}

$product = $result->fetch_assoc();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]['quantity'] += $quantity;
} else {
    $_SESSION['cart'][$id] = [
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => $quantity
    ];
}

header("Location: cart.php");
exit;
