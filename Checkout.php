<?php
session_start();

// Regenerate session ID to prevent session fixation
if (!isset($_SESSION['session_regenerated']) || $_SESSION['session_regenerated'] < time() - 300) {
    session_regenerate_id(true);
    $_SESSION['session_regenerated'] = time();
}

// Redirect if no user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: Login.php");
    exit();
}

// Generate CSRF token if it doesn't exist
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "software";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user']['id'];
// Make sure user_id is an integer to prevent SQL injection
$user_id = (int) $user_id;

$sql = "SELECT p.name, p.price, c.quantity, (p.price * c.quantity) AS total, c.product_id
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$grand_total = 0;
$items = [];
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
    $grand_total += $row['total'];
}

if (empty($items)) {
    echo "<script>alert('Your cart is empty!'); window.location.href = 'index.php';</script>";
    exit();
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token with proper error handling
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        //$errors[] = "Security verification failed. Please try again.";
    } else {
        // Input validation
        $name = trim($_POST['name']);
        $address = trim($_POST['address']);
        $payment_method = $_POST['payment_method'];
        
        // Validate name
        if (empty($name) || strlen($name) > 100) {
            $errors[] = "Name is required and must be less than 100 characters";
        }
        
        // Validate address
        if (empty($address) || strlen($address) > 500) {
            $errors[] = "Address is required and must be less than 500 characters";
        }
        
        // Validate payment method
        $allowed_payment_methods = ['credit_card','paypal','bank_transfer','cash'];
        if (!in_array($payment_method, $allowed_payment_methods)) {
            $errors[] = "Invalid payment method";
        }
        
        if (empty($errors)) {
            // Start transaction
            $conn->begin_transaction();
            
            try {
                // Create order
                $order_sql = "INSERT INTO orders (user_id, total_amount, status, ordered_at) VALUES (?, ?, ?, NOW())";
                $stmt = $conn->prepare($order_sql);
                $status = "pending";
                $stmt->bind_param("ids", $user_id, $grand_total, $status);
                $stmt->execute();
                $order_id = $stmt->insert_id;
                
                // Re-verify the cart items belong to the user
                $verify_sql = "SELECT c.product_id, c.quantity, p.price 
                            FROM cart c 
                            JOIN products p ON c.product_id = p.id
                            WHERE c.user_id = ?";
                $stmt = $conn->prepare($verify_sql);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $verify_result = $stmt->get_result();
                
                $verified_items = [];
                while ($row = $verify_result->fetch_assoc()) {
                    $verified_items[] = $row;
                }
                
                // Add order items
                foreach ($verified_items as $item) {
                    $order_item_sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
                    $stmt = $conn->prepare($order_item_sql);
                    $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
                    $stmt->execute();
                }
                
                // Clear the user's cart
                $clear_cart_sql = "DELETE FROM cart WHERE user_id = ?";
                $stmt = $conn->prepare($clear_cart_sql);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                
                // Commit transaction
                $conn->commit();
                
                // Generate new CSRF token for next form only after successful submission
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                
                // Redirect to confirmation page
                echo "<script>alert('Order placed successfully!'); window.location.href = 'Order_confirm.php?id=" . htmlspecialchars($order_id) . "';</script>";
                exit();
            } catch (Exception $e) {
                // Rollback transaction on error
                $conn->rollback();
                $errors[] = "Error processing order: " . $e->getMessage();
            }
        }
    }
}

// Function to safely output data
function e($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: Arial, sans-serif; background-color: teal; padding: 20px; }
        main { width: 70%; margin: 40px auto; background:#fff; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; border: 1px solid #ccc; text-align: center; }
        th { background-color: #ddd; }
        input[type="text"], textarea, select { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        input[type="submit"] { padding: 12px 20px; background: #007bff; color: white; border: none; cursor: pointer; border-radius: 5px; font-size: 16px; }
        input[type="submit"]:hover { background: #0056b3; }
        .error { color: red; margin-bottom: 15px; }
        @media (max-width: 768px) {
            main { width: 95%; }
        }
    </style>
</head>
<body>
<main>
    <h2>Checkout</h2>
    
    <?php if (!empty($errors)): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <input type="hidden" name="csrf_token" value="<?php echo e($_SESSION['csrf_token']); ?>">
        
        <h3>Shipping Information</h3>
        <label for="name">Full Name:</label>
        <input type="text" name="name" id="name" value="<?php echo isset($_POST['name']) ? e($_POST['name']) : ''; ?>" required>

        <label for="address">Shipping Address:</label>
        <textarea name="address" id="address" rows="4" required><?php echo isset($_POST['address']) ? e($_POST['address']) : ''; ?></textarea>

        <h3>Payment Information</h3>
        <label for="payment_method">Payment Method:</label>
        <select name="payment_method" id="payment_method" required>
            <option value="credit_card" <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] == 'credit_card') ? 'selected' : ''; ?>>Credit Card</option>
            <option value="paypal" <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] == 'paypal') ? 'selected' : ''; ?>>PayPal</option>
            <option value="bank_transfer" <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] == 'bank_transfer') ? 'selected' : ''; ?>>Bank Transfer</option>
            <option value="cash" <?php echo(isset($_POST['payment_method']) && $_POST['payment_method'] == 'cash') ? 'selected' : ''; ?>>Cash</option>
        </select>

        <h3>Your Order</h3>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price (₹)</th>
                    <th>Total (₹)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td><?php echo e($item['name']); ?></td>
                    <td><?php echo (int)$item['quantity']; ?></td>
                    <td><?php echo number_format((float)$item['price'], 2); ?></td>
                    <td><?php echo number_format((float)$item['total'], 2); ?></td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3"><strong>Grand Total:</strong></td>
                    <td><strong>₹<?php echo number_format($grand_total, 2); ?></strong></td>
                </tr>
            </tbody>
        </table>
        
        <input type="submit" value="Place Order">
    </form>
</main>
</body>
</html>
