<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: Login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Home - Wholesale Computers</title>
    <style>
        body {
            background: linear-gradient(to bottom, teal, black);
            font-family: arial, sans-serif;
            margin: 0;
        }
        header {
            background: rgba(255, 255, 255, 0.5);
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            margin: 20px auto;
            width: 80%;
        }
        header h1 {
            color: teal;
            font-size: 2.5em;
            text-shadow: 2px 2px black;
        }
        header p {
            color: white;
            font-size: 1.2em;
        }
        nav {
            text-align: center;
            margin: 20px auto;
        }
        nav a {
            background-color: teal;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 10px;
            margin: 5px;
            font-size: 16px;
            display: inline-block;
        }
        nav a:hover {
            background-color: white;
            color: teal;
            border: 1px solid teal;
        }
        .hero {
            background-color: rgba(255, 255, 255, 0.5);
            text-align: center;
            padding: 40px;
            border-radius: 10px;
            margin: 20px auto;
            width: 80%;
        }
        .hero h2 {
            color: teal;
            font-size: 2em;
            margin-bottom: 15px;
        }
        .hero p {
            color: white;
            font-size: 1em;
        }
        .product-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
            margin: 20px auto;
            width: 80%;
        }
        .product {
            background-color: rgba(255, 255, 255, 0.5);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        .product img {
            max-width: 100px;
            margin: 10px 0;
        }
        .product h3 {
            color: teal;
            font-size: 1.2em;
            margin: 10px 0;
        }
        .product p {
            color: white;
            font-size: 1em;
        }
        footer {
            text-align: center;
            padding: 10px;
            background-color: rgba(255, 255, 255, 0.3);
            color: white;
            font-size: 0.9em;
            border-radius: 10px;
            margin: 20px auto;
            width: 80%;
        }
    </style>
</head>
<body>
    <header>
        <h1>TechEssentials</h1>
        <p>Your one-stop shop for bulk computers and accessories</p>
    </header>
    <nav>
        <a href="Products.php">Products</a>
        <a href="About.php">About Us</a>
        <a href="Contact.php">Contact</a>
        <a href="Logout.php" style="margin-left:7px;">Logout</a>
    </nav>
    <div class="hero">
        <h2>Get the Best Deals on Bulk Purchases</h2>
        <p>Discover our wide range of laptops, desktops, and accessories, tailored to meet your wholesale needs.</p>
    </div>
    <section class="product-section">
        <div class="product">
            <img src="download.jpg" alt="Laptops">
            <h3>Laptops</h3>
            <p>Starting at ₹30,000 per unit (minimum 10 units)</p>
        </div>
        <div class="product">
            <img src="download (1).jpg" alt="Desktops">
            <h3>Desktops</h3>
            <p>Starting at ₹25,000 per unit (minimum 10 units)</p>
        </div>
        <div class="product">
            <img src="download.png" alt="Accessories">
            <h3>Accessories</h3>
            <p>Bulk discounts available on all peripherals</p>
        </div>
    </section>
    <footer>
        &copy; 2025 TechEssentials Computers. All rights reserved.
    </footer>
    <script src="jquery-3.7.1.js"></script>
    <script>
        $(document).ready(function(){
            $(".hero").mouseenter(function(){
                $(this).animate({
                    width:'85%',
                },"slow");
            })
            $(".hero").mouseleave(function(){
                $(this).animate({width:'80%'},"slow");
            })
            $("header").mouseenter(function(){
                $(this).animate({
                    width:'85%',
                },"slow");
            })
            $("header").mouseleave(function(){
                $(this).animate({width:'80%'},"slow");
            })
        });
    </script>
</body>
</html>
