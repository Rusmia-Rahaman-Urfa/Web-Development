<?php
// Include the database configuration file
require_once 'includes/db.php'; // Adjust path if necessary

// Check if the form was submitted via POST and product_id is set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    $product_id = filter_var($_POST['product_id'], FILTER_VALIDATE_INT); // Sanitize and validate

    // Only proceed if product_id is a valid integer
    if ($product_id !== false) {
        // Optional: Get image path before deleting product to delete the file from server too
        $image_to_delete = null;
        $stmt_select_image = $conn->prepare("SELECT image FROM products WHERE id = ?");
        if ($stmt_select_image) {
            $stmt_select_image->bind_param("i", $product_id);
            $stmt_select_image->execute();
            $stmt_select_image->bind_result($image_to_delete);
            $stmt_select_image->fetch();
            $stmt_select_image->close();
        }

        // Prepare a DELETE statement
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");

        if ($stmt) {
            // Bind the product ID parameter
            $stmt->bind_param("i", $product_id); // 'i' for integer

            // Execute the statement
            if ($stmt->execute()) {
                // If product deleted from DB, try to delete the image file from the server
                if ($image_to_delete && file_exists($image_to_delete)) {
                    if (unlink($image_to_delete)) {
                        // File deleted successfully
                        // Optionally log this success
                    } else {
                        // Failed to delete file (e.g., permissions issue)
                        error_log("Failed to delete image file: " . $image_to_delete);
                    }
                }
                // Redirect back to admin_page.php with a success message
                header("Location: admin_page.php?status=deleted");
                exit();
            } else {
                // Error executing delete statement
                $error_msg = "Database error: " . $stmt->error;
                error_log($error_msg); // Log the error
                header("Location: admin_page.php?status=error&msg=" . urlencode($error_msg));
                exit();
            }
            $stmt->close();
        } else {
            // Error preparing statement
            $error_msg = "Database error: Could not prepare delete statement.";
            error_log($error_msg); // Log the error
            header("Location: admin_page.php?status=error&msg=" . urlencode($error_msg));
            exit();
        }
    } else {
        // Invalid product ID
        header("Location: admin_page.php?status=error&msg=" . urlencode("Invalid product ID."));
        exit();
    }
} else {
    // Direct access to delete_product.php without POST request or product_id
    header("Location: admin_page.php?status=error&msg=" . urlencode("Invalid request."));
    exit();
}

// Close the connection if it's still open (though exit() should handle it)
if ($conn && $conn->ping()) {
    $conn->close();
}
?>