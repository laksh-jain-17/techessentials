<?php
// 1. Database connection settings
$servername = "localhost";
$username   = "root";      // Default XAMPP/WAMP username
$password   = "";          // Default XAMPP/WAMP password
$dbname     = "software";

// 2. Create connection
$conn = new mysqli($servername, $username, $password);

// 3. Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 4. Create Database if it doesn't exist
$sql_db = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql_db) === TRUE) {
    $conn->select_db($dbname);
} else {
    die("Error creating database: " . $conn->error);
}

// 5. SQL to create tables
$tables = [
    "users" => "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL,
        email VARCHAR(100) NOT NULL,
        password VARCHAR(255) NOT NULL
    )",

    "products" => "CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        price DECIMAL(10,2) NOT NULL,
        image_url VARCHAR(255),
        stock INT,
        accessories TEXT
    )",

    "cart" => "CREATE TABLE IF NOT EXISTS cart (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        product_id INT,
        quantity INT,
        added_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )",

    "orders" => "CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        total_amount DECIMAL(10,2),
        status VARCHAR(50),
        ordered_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )",

    "order_items" => "CREATE TABLE IF NOT EXISTS order_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT,
        product_id INT,
        quantity INT,
        price DECIMAL(10,2)
    )",

    "bulk_orders" => "CREATE TABLE IF NOT EXISTS bulk_orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        customer_name VARCHAR(255),
        email VARCHAR(100),
        product_id INT,
        quantity INT,
        notes TEXT,
        ordered_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )",

    "contacts" => "CREATE TABLE IF NOT EXISTS contacts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255),
        email VARCHAR(100),
        subject VARCHAR(255),
        message TEXT
    )",

    "faqs" => "CREATE TABLE IF NOT EXISTS faqs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        question TEXT,
        answer TEXT
    )"
];

// 6. Execute table creation
foreach ($tables as $name => $query) {
    if ($conn->query($query) === FALSE) {
        echo "Error creating table $name: " . $conn->error . "<br>";
    }
}

// Success message (Optional: comment out for production)
// echo "Database and Tables initialized successfully!";
?>