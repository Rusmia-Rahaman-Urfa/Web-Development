<?php
session_start();

// IMPORTANT: Include your database configuration file
// Adjust the path if 'db.php' is not directly in 'includes' relative to this file
require_once 'includes/db.php'; // Assuming db.php is in 'includes'

$toysProducts = []; // Initialize an empty array to store products fetched from DB
$targetCategory = 'toys'; // Ensure this matches the category name from your admin panel EXACTLY (case-sensitive)
$maxPrice = 25.00; // For "Toys Under $25"

// Prepare SQL statement to fetch toys under $25
// Using prepared statements for security
$sql = "SELECT id, name, price, description, image FROM products WHERE category = ? AND price <= ? ORDER BY id DESC";

if ($stmt = $conn->prepare($sql)) {
    // Bind parameters: 's' for string (category), 'd' for double (price)
    $stmt->bind_param("sd", $targetCategory, $maxPrice);

    // Execute the statement
    $stmt->execute();

    // Get the result set
    $result = $stmt->get_result();

    // Fetch all products into the $toysProducts array
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $toysProducts[] = $row;
        }
    }

    // Close the statement
    $stmt->close();
} else {
    // Handle error if prepare statement fails (e.g., database connection issue, SQL syntax error)
    // In a production environment, you would log this error and show a user-friendly message.
    error_log("Failed to prepare statement for toys category: " . $conn->error);
    $toysProducts = []; // Ensure it's empty so no broken display
}

// It's good practice to close the connection when no more DB operations are needed
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Toys - EasyShop</title>
    <link rel="stylesheet" href="toys.css">
    <link rel="stylesheet" href="style.css?v=1"> <style>
        /* Add some basic styling for the product cards if not in toys.css */
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
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<h1>Toys Under $25</h1>

<div class="product-detail-container">
    <?php if (!empty($toysProducts)): ?>
        <?php foreach ($toysProducts as $p): ?>
            <div class="product-card">
                <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
                <h2><?= htmlspecialchars($p['name']) ?></h2>
                <p>$<?= htmlspecialchars(number_format($p['price'], 2)) ?></p>
                <p><?= htmlspecialchars($p['description']) ?></p>
                <form action="add_to_cart.php" method="POST">
                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($p['id']) ?>">
                    <input type="hidden" name="product_name" value="<?= htmlspecialchars($p['name']) ?>">
                    <input type="hidden" name="product_price" value="<?= htmlspecialchars($p['price']) ?>">
                    <button type="submit">Add to Cart</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No toys found under $25 in this category yet.</p>
    <?php endif; ?>
</div>

</body>
</html>