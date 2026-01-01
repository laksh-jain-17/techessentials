<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "software";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "
INSERT INTO products (name, description, price, image_url, stock, accessories) VALUES
-- Laptops
('Dell Inspiron Laptop', '15.6-inch FHD display, Intel Core i5, 8GB RAM, 512GB SSD.', 54999.00, 'images/l1.jpeg', 10, 'Laptops'),
('HP Pavilion Laptop', '14-inch screen, Ryzen 5, 16GB RAM, 1TB SSD.', 61999.00, 'images/l2.jpeg', 7, 'Laptops'),
('Lenovo ThinkPad', 'Business-class performance with high durability.', 65999.00, 'images/l3.jpeg', 3, 'Laptops'),
('Acer Aspire 5', 'Entry-level laptop for students and home users.', 39999.00, 'images/l4.jpeg', 5, 'Laptops'),
('Apple MacBook Air', 'M1 chip, 13-inch Retina display, sleek design.', 99999.00, 'images/l5.jpeg', 2, 'Laptops'),
('Asus TUF Gaming Laptop', 'GeForce GTX, Ryzen 7, 16GB RAM.', 72999.00, 'images/l6.jpeg', 3, 'Laptops'),
('Lenovo Ideapad Slim', 'Lightweight laptop with long battery life.', 47999.00, 'images/l7.jpeg', 10, 'Laptops'),
('MSI Stealth Gaming Laptop', 'Thin and powerful, 144Hz screen.', 88999.00, 'images/l8.jpeg', 3, 'Laptops'),
('Asus ZenBook', 'Premium ultrabook with OLED display.', 77999.00, 'images/l9.jpeg', 1, 'Laptops'),
('High-End Developer Laptop', 'Perfect for programming, compiling & multitasking.', 94999.00, 'images/l10.jpeg', 4, 'Laptops'),

-- Keyboards
('Mechanical Gaming Keyboard', 'RGB backlit keys with clicky switches.', 3499.00, 'images/ky1.jpeg', 12, 'Keyboards'),
('Logitech Office Keyboard', 'Quiet and reliable keyboard for office work.', 1199.00, 'images/ky2.jpeg', 8, 'Keyboards'),
('Wireless Keyboard and Mouse Combo', 'Comfortable wireless combo with long battery life.', 1999.00, 'images/ky3.jpeg', 10, 'Keyboards'),
('Compact Mechanical Keyboard', '60% layout with tactile switches.', 2999.00, 'images/ky4.jpeg', 9, 'Keyboards'),
('RGB Gaming Keyboard', 'Full-size with media controls and wrist rest.', 3999.00, 'images/ky5.jpeg', 5, 'Keyboards'),
('Wireless Mechanical Keyboard', 'Bluetooth support with long battery.', 4599.00, 'images/ky6.jpeg', 6, 'Keyboards'),
('Ergonomic Keyboard', 'Split design with cushioned palm rest.', 2899.00, 'images/ky7.jpeg', 7, 'Keyboards'),

-- Computers
('Gaming Desktop Setup', 'High-end custom-built gaming PC.', 84999.00, 'images/c1.jpeg', 4, 'Computers'),
('All-in-One Desktop', 'Compact design with built-in monitor.', 55999.00, 'images/c2.jpeg', 6, 'Computers'),
('Mini PC', 'Small-form-factor desktop with powerful performance.', 38999.00, 'images/c3.jpeg', 7, 'Computers'),
('Refurbished Dell Desktop', 'Core i5, 8GB RAM, Windows 10 Pro.', 27999.00, 'images/c4.jpeg', 6, 'Computers'),
('Dell Precision Workstation', 'For high-performance CAD and 3D work.', 114999.00, 'images/c5.jpeg', 2, 'Computers'),
('HP EliteDesk Mini', 'Compact business desktop with Windows 11.', 36999.00, 'images/c6.jpeg', 5, 'Computers'),
('Full Tower Gaming PC', 'RGB lighting, tempered glass, liquid cooling.', 104999.00, 'images/c7.jpeg', 4, 'Computers'),
('Intel NUC Mini PC', 'Compact powerhouse for home/office use.', 44999.00, 'images/c8.jpeg', 5, 'Computers'),

-- Mouse
('Wireless Mouse', '2.4GHz wireless mouse with ergonomic design.', 799.00, 'images/m1.jpeg', 15, 'Mouse'),
('Gaming Mouse', 'High DPI optical sensor with RGB lighting.', 1499.00, 'images/m2.jpeg', 10, 'Mouse'),
('Bluetooth Mouse', 'Compact and rechargeable with Bluetooth 5.0.', 1299.00, 'images/m3.jpeg', 12, 'Mouse'),
('Silent Click Mouse', 'Noise-free clicks, ideal for office use.', 999.00, 'images/m4.jpeg', 8, 'Mouse'),
('Vertical Ergonomic Mouse', 'Comfortable vertical design for wrist support.', 1799.00, 'images/m5.jpeg', 5, 'Mouse'),
('Wired Optical Mouse', 'Reliable wired mouse with USB interface.', 499.00, 'images/m6.jpeg', 20, 'Mouse'),
('Normal looking Mouse','Looks simple and useful for everyday purpose.',599.00,'images/m7.jpeg',25,'Mouse'),

-- Speakers
('Bluetooth Speaker', 'Portable speaker with deep bass and 10-hour playtime.', 1999.00, 'images/sp1.jpeg', 10, 'Speakers'),
('2.1 Multimedia Speakers', 'Subwoofer with dual satellite speakers.', 2999.00, 'images/sp2.jpeg', 6, 'Speakers'),
('Mini Travel Speaker', 'Compact design, fits in pocket, USB rechargeable.', 999.00, 'images/sp3.jpeg', 14, 'Speakers'),
('Soundbar Speaker', 'Slim soundbar with powerful stereo sound.', 3999.00, 'images/sp4.jpeg', 4, 'Speakers'),
('Waterproof Bluetooth Speaker', 'Perfect for outdoor use, waterproof and rugged.', 2499.00, 'images/sp5.jpeg', 7, 'Speakers'),
('Home Theatre System', 'Surround sound system with HDMI and Bluetooth.', 6999.00, 'images/sp6.jpeg', 3, 'Speakers'),
('USB-Powered Speakers', 'Plug and play stereo sound for laptops.', 799.00, 'images/sp7.jpeg', 11, 'Speakers'),
('Desktop PC Speakers', 'Clear sound with volume control knob.', 1299.00, 'images/sp8.jpeg', 9, 'Speakers');
";

if ($conn->multi_query($sql) === TRUE) {
    echo "39 computer/laptop/keyboard/mouse/speaker products inserted successfully!";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
