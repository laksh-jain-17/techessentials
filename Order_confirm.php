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

// Check for order ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid order ID.";
    exit();
}

$order_id = (int)$_GET['id'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "software";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to safely output data
function e($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

// Fetch order details
$sql = "SELECT o.id, o.user_id, o.total_amount, o.status, o.ordered_at,
            u.username AS user_name, u.email AS user_email
        FROM orders o
        JOIN users u ON o.user_id = u.id
        WHERE o.id = ? ";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_result = $stmt->get_result();

if ($order_result->num_rows === 0) {
    echo "Order not found.";
    exit();
}

$order = $order_result->fetch_assoc();

// Fetch order items
$sql = "SELECT oi.product_id, oi.quantity, oi.price, p.name AS product_name
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        WHERE oi.order_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items_result = $stmt->get_result();

$items = [];
while ($row = $items_result->fetch_assoc()) {
    $items[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body{ 
            font-family: Arial, sans-serif; 
            background: teal;
            padding: 20px;
        }
        main { width: 70%; margin: 40px auto; background:#fff;padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { color: #333; }
        p { line-height: 1.6; color: #555; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; border: 1px solid #ccc; text-align: left; }
        th { background-color: #ddd; }
        @media (max-width: 768px) {
            main { width: 95%; }
        }
    </style>
    <!-- Redirect to homepage after 5 seconds -->
    <meta http-equiv="refresh" content="5;url=Homepage.php">
</head>
<body>
<main>
    <h2>Order Confirmation</h2>

    <p>Thank you for your order! Here are the details:</p>

    <h3>Order Details</h3>
    <p><strong>Order ID:</strong> <?php echo e($order['id']); ?></p>
    <p><strong>Order Date:</strong> <?php echo e($order['ordered_at']); ?></p>
    <p><strong>Status:</strong> <?php echo e($order['status']); ?></p>
    <p><strong>Total Amount:</strong> ₹<?php echo number_format($order['total_amount'], 2); ?></p>

    <h3>Customer Information</h3>
    <p><strong>Username:</strong> <?php echo e($order['user_name']); ?></p>
    <p><strong>Email:</strong> <?php echo e($order['user_email']); ?></p>

    <h3>Order Items</h3>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price (₹)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?php echo e($item['product_name']); ?></td>
                <td><?php echo (int)$item['quantity']; ?></td>
                <td>₹<?php echo number_format((float)$item['price'], 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p>You will receive another email when your order ships.</p>
</main>
</body>
</html>
