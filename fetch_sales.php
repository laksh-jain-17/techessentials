<?php
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

// Get user ID from AJAX request
$userId = intval($_POST['user_id']);

// Query to get sales details for this user
$query = "
    SELECT 
        oi.id AS order_item_id,
        oi.order_id,
        p.name AS product_name,
        oi.quantity,
        oi.price
    FROM order_items oi
    INNER JOIN orders o ON oi.order_id = o.id
    INNER JOIN products p ON oi.product_id = p.id
    WHERE o.user_id = $userId
";

$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    echo "<table>
            <tr>
                <th>Order Item ID</th>
                <th>Order ID</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['order_item_id']}</td>
                <td>{$row['order_id']}</td>
                <td>" . htmlspecialchars($row['product_name']) . "</td>
                <td>{$row['quantity']}</td>
                <td>$" . number_format($row['price'], 2) . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No sales found for this user.</p>";
}

$conn->close();
?>
