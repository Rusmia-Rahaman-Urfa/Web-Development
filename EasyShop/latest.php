<?php
session_start();

// IMPORTANT: Include your database configuration file
require_once 'includes/db.php'; // Assuming db.php is in 'includes'

// Handle add to cart (This block must come BEFORE any HTML output)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    // It's better to fetch product details from the database using product_id for security
    // For now, we'll use the passed hidden fields as per your original logic,
    // but note that this is less secure as price/name could be manipulated client-side.
    $product_id = htmlspecialchars($_POST['product_id']);
    $name = htmlspecialchars($_POST['product_name']);
    $price = floatval($_POST['product_price']);
    $quantity = 1;

    // Create session cart if not exists (redundant if done at the very top, but harmless here)
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

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
    header("Location: latest.php");
    exit;
}


// --- Start: Dynamic Product Fetching for Digital Mobile Phones ---
$latestProducts = []; // Initialize an empty array to store products fetched from DB
// IMPORTANT: Ensure this EXACTLY matches the category name from your admin panel
$targetCategory = 'latest device'; 
// If you indeed renamed your categories to just 'mobile' in the DB, then use 'mobile' here.
// But based on your homepage screenshot, 'Digital Mobile Phones' is the category name.


// Prepare SQL statement to fetch mobile products
$sql = "SELECT id, name, price, description, image FROM products WHERE category = ? ORDER BY id DESC";

if ($stmt = $conn->prepare($sql)) {
    // FIX: Changed "sd" to "s" because there's only one placeholder for category (string)
    $stmt->bind_param("s", $targetCategory);

    // Execute the statement
    $stmt->execute();

    // Get the result set
    $result = $stmt->get_result();

    // Fetch all products into the $mobileProducts array
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $latestProducts[] = $row;
        }
    }

    // Close the statement
    $stmt->close();
} else {
    // Handle error if prepare statement fails
    error_log("Failed to prepare statement for mobile category: " . $conn->error);
    // Optionally display a user-friendly error message on the page
    echo "<p>Error: Could not load mobile products. Please try again later.</p>";
    $latestProducts = []; // Ensure it's empty to prevent display issues
}

// It's good practice to close the connection when no more DB operations are needed
$conn->close();
// --- End: Dynamic Product Fetching ---
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Latest device - EasyShop</title>
    <link rel="stylesheet" href="latest.css">
    <link rel="stylesheet" href="style.css?v=1">
    <style>
        /* Basic styling for product cards (move to mobile.css for better organization) */
        .product-card {
            border: 1px solid #ddd;
            padding: 15px;
            margin: 10px;
            text-align: center;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            flex: 0 0 calc(25% - 20px); /* Adjust for responsive grid if needed */
            box-sizing: border-box; /* Include padding and border in element's total width and height */
            display: flex; /* Added for better vertical alignment */
            flex-direction: column; /* Organize content vertically */
            justify-content: space-between; /* Push button to bottom */
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
            margin-top: 10px; /* Space above button */
        }

        .product-card form button:hover {
            background-color: #0056b3;
        }

        .product-detail-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 20px;
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

<h1>Latest Devices</h1>

<div class="product-detail-container">
    <?php if (!empty($latestProducts)): ?>
        <?php foreach ($latestProducts as $p): ?>
            <div class="product-card">
                <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
                <h2><?= htmlspecialchars($p['name']) ?></h2>
                <p>$<?= htmlspecialchars(number_format($p['price'], 2)) ?></p>
                <p><?= htmlspecialchars($p['description']) ?></p>
                <form action="add_to_cart.php" method="POST">
                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($p['id']) ?>">
                    <input type="hidden" name="product_name" value="<?= htmlspecialchars($p['name']) ?>">
                    <input type="hidden" name="product_price" value="<?= htmlspecialchars($p['price']) ?>">
                    <button type="submit" name="add_to_cart">Add to Cart</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No latest device found in this category yet. Please add some from the admin panel.</p>
    <?php endif; ?>
</div>

</body>
</html>