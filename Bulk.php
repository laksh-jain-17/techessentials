<!DOCTYPE html>
<html>
<head>
    <title>Bulk Order</title>
    <style>
        body { font-family: Arial; background-color: #f0f0f0; margin: 0; padding: 0; }
        main { width: 50%; margin: 40px auto; background: white; padding: 20px; border-radius: 10px; }
        input, textarea { width: 100%; padding: 10px; margin-bottom: 15px; }
        input[type="submit"] { background: #28a745; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <main>
        <h2>Bulk Order Form</h2>
        <form method="post" action="bulk_order.php">
            <label>Name:</label>
            <input type="text" name="customer_name" required>

            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Product ID:</label>
            <input type="number" name="product_id" required>

            <label>Quantity:</label>
            <input type="number" name="quantity" required>

            <label>Notes:</label>
            <textarea name="notes" rows="4"></textarea>

            <input type="submit" value="Place Bulk Order">
        </form>
    </main>
    <?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = htmlspecialchars(trim($_POST['customer_name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    $notes = htmlspecialchars(trim($_POST['notes']));
    $errors = [];
    if (empty($customer_name)) {
        $errors[] = "Name is required.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if ($product_id <= 0) {
        $errors[] = "Product ID must be a positive number.";
    }
    if ($quantity <= 0) {
        $errors[] = "Quantity must be a positive number.";
    }
    if (empty($errors)) {
        echo "<h2>Order Confirmation</h2>";
        echo "<p>Thank you, $customer_name! Your order for $quantity of product ID $product_id has been placed successfully.</p>";
        echo "<p>Notes: $notes</p>";
    } else {
        echo "<h2>There were errors with your submission:</h2>";
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
        echo '<a href="javascript:history.back()">Go Back</a>';
    }
} else {
    header("Location: index.html");
    exit();
}
?>
</body>
</html>
