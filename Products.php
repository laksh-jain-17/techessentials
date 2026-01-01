<?php
session_start();
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

$category = isset($_GET['category']) ? $_GET['category'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';

$sql = "SELECT * FROM products";
if (!empty($category)) {
    $sql .= " WHERE category = '" . $conn->real_escape_string($category) . "'";
}
if (!empty($sort)) {
    switch($sort) {
        case 'price_low':
            $sql .= " ORDER BY price ASC";
            break;
        case 'price_high':
            $sql .= " ORDER BY price DESC";
            break;
        case 'name':
            $sql .= " ORDER BY name ASC";
            break;
    }
}

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Products - TechEssentials</title>
    <style>
        body {
            background: linear-gradient(to bottom, teal, black);
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        main {
            width: 90%;
            margin: 40px auto;
            background: rgba(255, 255, 255, 0.5);
            padding: 20px;
            border-radius: 10px;
            color: white;
        }
        h2 {
            text-align: center;
            color: teal;
            text-shadow: 1px 1px black;
        }
        .filters {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
            gap: 10px;
        }
        .filters select, .filters button {
            padding: 8px;
            border-radius: 5px;
            border: 1px solid teal;
            background-color: white;
            color: teal;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        .product-card {
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            color: black;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            display: flex;
            flex-direction: column;
        }
        .product-card img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            margin: 0 auto;
        }
        .product-card h3 {
            color: teal;
            margin-top: 10px;
        }
        .product-card .description {
            flex-grow: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }
        .product-card p {
            margin: 5px 0;
        }
        .product-card button {
            padding: 8px 15px;
            background-color: teal;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        .product-card button:hover {
            background-color: white;
            color: teal;
            border: 1px solid teal;
        }
        .stock-low {
            color: #ff5722;
        }
        .stock-out {
            color: #f44336;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <main>
        <h2>Available Products</h2>
        
        <div class="filters">
            <select id="sortOptions" onchange="window.location.href='?sort='+this.value">
                <option value="">Sort By</option>
                <option value="price_low" <?php echo $sort == 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                <option value="price_high" <?php echo $sort == 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                <option value="name" <?php echo $sort == 'name' ? 'selected' : ''; ?>>Name: A to Z</option>
            </select>
        </div>
        
        <div class="product-grid">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $stockClass = '';
                    if ($row['stock'] <= 0) {
                        $stockClass = 'stock-out';
                    } elseif ($row['stock'] < 5) {
                        $stockClass = 'stock-low';
                    }

                    echo '<div class="product-card">';
                    if (!empty($row['image_url']) && file_exists($row['image_url'])) {
                        echo '<img src="' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                    } else {
                        echo '<img src="product_images/placeholder.jpg" alt="No image available">';
                    }
                    echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                    echo '<p class="description">' . htmlspecialchars($row['description']) . '</p>';
                    echo '<p><strong>Price:</strong> ₹' . number_format($row['price'], 2) . '</p>';
                    echo '<p><strong>Stock:</strong> <span class="' . $stockClass . '">' . $row['stock'] . '</span></p>';

                    if ($row['stock'] > 0) {
                        echo '<form method="post" action="Cart.php">';
                        echo '<input type="hidden" name="product_id" value="' . $row['id'] . '">';
                        echo '<button type="submit">Add to Cart</button>';
                        echo '</form>';
                    } else {
                        echo '<button disabled style="background-color: #ccc; cursor: not-allowed;">Out of Stock</button>';
                    }

                    echo '</div>';
                }
            } else {
                echo "<p style='text-align:center; color:black;'>No products found!</p>";
            }
            $conn->close();
            ?>
        </div>
    </main>
</body>
</html>
