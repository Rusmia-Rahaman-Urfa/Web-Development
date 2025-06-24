<?php
session_start();

$id = $_POST['id'];
$quantity = max(1, intval($_POST['quantity']));

if (isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]['quantity'] = $quantity;
}

header("Location: cart.php");
exit;
