<?php
session_start();

// IMPORTANT: Include your database configuration file
// Adjust the path if 'db.php' is not directly in 'includes' relative to this file
require_once 'includes/db.php'; // Assuming db.php is in 'includes'

// Create session cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    // It's better to fetch product details from the database using product_id for security
    // For now, we'll use the passed hidden fields as per your original logic,
    // but note that this is less secure as price/name could be manipulated client-side.
    $product_id = htmlspecialchars($_POST['product_id']); // Use the actual product ID from the DB
    $name = htmlspecialchars($_POST['product_name']);
    $price = floatval($_POST['product_price']);
    $quantity = 1;

    // If already in cart, increase quantity
    if (isset($_SESSION['cart'][$product_id])) { // Use product_id as the key
        $_SESSION['cart'][$product_id]['quantity'] += 1;
    } else {
        $_SESSION['cart'][$product_id] = [
            'id' => $product_id, // Store the actual product ID
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity
        ];
    }

    // Redirect to avoid resubmission
    header("Location: beauty.php");
    exit;
}

// --- Start: Dynamic Product Fetching for Beauty Essentials ---
$beautyProducts = []; // Initialize an empty array to store products fetched from DB
$targetCategory = 'Beauty Essentials'; // Ensure this EXACTLY matches the category name from your admin panel

// Prepare SQL statement to fetch beauty products
$sql = "SELECT id, name, price, description, image FROM products WHERE category = ? ORDER BY id DESC";

if ($stmt = $conn->prepare($sql)) {
    // Bind parameters: 's' for string (category)
    $stmt->bind_param("s", $targetCategory);

    // Execute the statement
    $stmt->execute();

    // Get the result set
    $result = $stmt->get_result();

    // Fetch all products into the $beautyProducts array
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $beautyProducts[] = $row;
        }
    }

    // Close the statement
    $stmt->close();
} else {
    // Handle error if prepare statement fails
    error_log("Failed to prepare statement for beauty category: " . $conn->error);
    // Optionally display a user-friendly error message on the page
    echo "<p>Error: Could not load beauty products. Please try again later.</p>";
    $beautyProducts = []; // Ensure it's empty to prevent display issues
}

// It's good practice to close the connection when no more DB operations are needed
$conn->close();
// --- End: Dynamic Product Fetching ---
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Beauty Products - EasyShop</title>
    <link rel="stylesheet" href="beauty.css">
    <!-- Assuming style.css is for general site-wide styles -->
    <link rel="stylesheet" href="style.css?v=1">
    <style>
        /* Basic styling for product cards (move to beauty.css for better organization) */
        .product-detail-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 20px;
        }
        .product-card {
            border: 1px solid #ddd;
            padding: 15px;
            margin: 10px;
            text-align: center;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            flex: 0 0 calc(25% - 20px); /* Adjust for responsive grid if needed */
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .product-card img {
            max-width: 100%;
            height: 150px; /* Consistent image height */
            object-fit: contain; /* Ensure image fits without cropping */
            margin-bottom: 10px;
        }
        .product-card h2 {
            font-size: 1.2em;
            margin-bottom: 5px;
            color: #333;
        }
        .product-card p {
            font-size: 0.9em;
            color: #666;
            margin-bottom: 5px;
        }
        .product-card p:last-of-type {
            font-weight: bold;
            color: #007bff;
            margin-top: auto; /* Pushes price to the bottom if content varies */
        }
        .product-card form button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }
        .product-card form button:hover {
            background-color: #0056b3;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<h1>Beauty Essentials</h1>

<div class="product-detail-container">
    <?php if (!empty($beautyProducts)): ?>
        <?php foreach ($beautyProducts as $p): ?>
            <div class="product-card">
                <!-- Use htmlspecialchars for all output from database to prevent XSS -->
                <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
                <h2><?= htmlspecialchars($p['name']) ?></h2>
                <!-- Correctly display price from database -->
                <p>$<?= htmlspecialchars(number_format($p['price'], 2)) ?></p>
                <p><?= htmlspecialchars($p['description']) ?></p>
                <form action="add_to_cart.php" method="POST">
                    <!-- Pass actual product ID, name, and price from DB -->
                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($p['id']) ?>">
                    <input type="hidden" name="product_name" value="<?= htmlspecialchars($p['name']) ?>">
                    <input type="hidden" name="product_price" value="<?= htmlspecialchars($p['price']) ?>">
                    <button type="submit" name="add_to_cart">Add to Cart</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No beauty products found in this category yet. Please add some from the admin panel.</p>
    <?php endif; ?>
</div>

</body>
</html>