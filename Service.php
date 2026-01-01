<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: black;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .services-section {
            background: teal;
            padding: 50px 20px;
            text-align: center;
        }
        .services-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        .service-box {
            background: rgba(255, 255, 255, 0.8); /* Transparent effect */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            width: 250px;
            text-align: left;
        }
        .service-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: black;
        }
        .service-description {
            font-size: 16px;
            color: #333;
        }
        .cta-section {
            background: black;
            padding: 20px;
            text-align: center;
        }
        .cta-section button {
            background: teal;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        footer {
            background-color: black;
            color: white;
            padding: 10px;
            text-align: center;
            font-size: 14px;
        }
        h2{
            text-decoration:underline;
        }
    </style>
</head>
<body>

<header>
    <h1>Our Services</h1>
    <p>Your Success in simplified way.</p>
</header>

<section class="services-section">
    <h2>What We Offer</h2>
    <div class="services-container">
        <div class="service-box">
            <div class="service-title">Bulk Order Discounts</div>
            <div class="service-description">Enjoy tailored pricing for bulk purchases, helping you save as you scale.</div>
        </div>
        <div class="service-box">
            <div class="service-title">Custom Orders</div>
            <div class="service-description">Tailored solutions for specific business needs.</div>
        </div>
        <div class="service-box">
            <div class="service-title">After-Sales Service</div>
            <div class="service-description">Warranty claims, returns, or exchanges made easy.</div>
        </div>
        <div class="service-box">
            <div class="service-title">Technical Support</div>
            <div class="service-description">Assistance with product setup and troubleshooting.</div>
        </div>
        <div class="service-box">
            <div class="service-title">Reseller Opportunities</div>
            <div class="service-description">Join our authorized reseller program to boost your business.</div>
        </div>
        <div class="service-box">
            <div class="service-title">Shipping and Delivery</div>
            <div class="service-description">Reliable and timely delivery services for your orders.</div>
        </div>
        <div class="service-box">
            <div class="service-title">Consultation Services</div>
            <div class="service-description">Expert advice to choose the right products for your needs.</div>
        </div>
    </div>
</section>

<section class="cta-section">
    <button>Get Started Today!</button>
</section>

<footer>
    <p>© 2025 TechEssentials. All Rights Reserved.</p>
</footer>
<script src="jquery-3.7.1.js"></script>
<script>
    $(document).ready(function(){
        $("button").mouseenter(function(){
            $(this).css("background-color","white");
            $(this).css("color","black");
        });
        $("button").mouseleave(function(){
            $(this).css("background-color","teal");
            $(this).css("color","white");
        });
    });
</script>
</body>
</html>