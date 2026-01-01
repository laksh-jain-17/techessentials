<?php
session_start();
echo '<pre>';
print_r($_SESSION['user']);
echo '</pre>';
if (!isset($_SESSION['user'])) {
    header("Location: Login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "software";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = isset($_SESSION['user']['id']) ? intval($_SESSION['user']['id']) : 0;
$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;

// Initialize $available_stock with a default value of 0
$available_stock = 0;

// Fetch the available stock for the product if product_id is set
if ($product_id > 0) {
    $stock_query = $conn->prepare("SELECT stock FROM products WHERE id = ?");
    $stock_query->bind_param("i", $product_id);
    $stock_query->execute();
    $stock_result = $stock_query->get_result();

    if ($stock_result->num_rows > 0) {
        $stock_row = $stock_result->fetch_assoc();
        $available_stock = $stock_row['stock'];
    }
}

// Handle Add to Cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $product_id > 0) {
    if ($quantity > $available_stock) {
        // Prevent adding more than available stock
        echo "<script>alert('Quantity exceeds available stock!');</script>";
    } else {
        $check = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
        $check->bind_param("ii", $user_id, $product_id);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            // Update quantity if product already in cart
            $update = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
            $update->bind_param("iii", $quantity, $user_id, $product_id);
            $update->execute();
        } else {
            // Insert product into cart if not already present
            $insert = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $insert->bind_param("iii", $user_id, $product_id, $quantity);
            $insert->execute();
        }
    }
}

// Fetch Cart Items
$sql = "SELECT p.name, p.price, c.quantity, (p.price * c.quantity) AS total, c.product_id
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <style>
        body { font-family: Arial; background: teal; margin: 0; padding: 0; }
        main { width: 80%; margin: 40px auto; background: white; padding: 20px; border-radius: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; border: 1px solid #ccc; text-align: center; }
        th { background-color: #ddd; }
        input[type="submit"] {
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            margin-top: 20px;
            cursor: pointer;
            border-radius: 5px;
        }
        .quantity-buttons {
            display: inline-flex;
            align-items: center;
        }
        .quantity-buttons button {
            padding: 5px 10px;
            margin: 0 5px;
            cursor: pointer;
        }
        .payment-section {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<main>
    <h2>Your Cart</h2>
    <table>
        <thead>
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price (₹)</th>
            <th>Total (₹)</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $grand_total = 0;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                echo '<td>
                        <form method="post" action="">
                            <input type="hidden" name="product_id" value="' . $row['product_id'] . '">
                            <div class="quantity-buttons">
                                <button type="submit" name="quantity" value="' . max($row['quantity'] - 1, 1) . '" ' . ($row['quantity'] <= 1 ? 'disabled' : '') . '> - </button>
                                <input type="text" value="' . $row['quantity'] . '" readonly style="width: 30px; text-align: center;">
                                <button type="submit" name="quantity" value="' . min($row['quantity'] + 1, $available_stock) . '" ' . ($row['quantity'] >= $available_stock ? 'disabled' : '') . '> + </button>
                            </div>
                        </form>
                      </td>';
                echo '<td>' . number_format($row['price'], 2) . '</td>';
                echo '<td>' . number_format($row['total'], 2) . '</td>';
                echo '<td><form method="post" action="Products.php">
                        <input type="hidden" name="product_id" value="' . $row['product_id'] . '">
                        <input type="submit" value="Remove">
                    </form></td>';
                echo '</tr>';
                $grand_total += $row['total'];
            }
        } else {
            echo '<tr><td colspan="5">Your cart is empty.</td></tr>';
        }
        ?>
        <tr>
            <td colspan="4"><strong>Grand Total:</strong></td>
            <td><strong>₹<?php echo number_format($grand_total, 2); ?></strong></td>
        </tr>
        </tbody>
    </table>

    <?php if ($grand_total > 0): ?>
        <form method="post" action="Checkout.php">
            <input type="submit" value="Proceed to Checkout">
        </form>

        <!-- Payment Section -->
        <!--div class="payment-section">
            <h3>Make Payment</h3>
            <form action="payment_gateway.php" method="POST">
                <input type="hidden" name="total_amount" value="<?php// echo $grand_total; ?>">
                <input type="submit" value="Pay Now">
            </form>
        </div-->
    <?php endif; ?>
</main>
</body>
</html>
