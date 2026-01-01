<!DOCTYPE html>
<html>
<head>
    <title>Sign in page</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: arial;
        }
        
        .container {
            width: 100%;
            height: 100vh;
            background: linear-gradient(to bottom, teal, black);
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        #box {
            background-color: rgba(255, 255, 255, 0.5);
            width: 400px;
            height: 460px;
            color: white;
            padding: 20px;
            border-radius: 10px;
        }
        
        h3 {
            color: teal;
            text-decoration: underline;
            text-align: center;
            font-style: italic;
            font-weight: bold;
            font-family: verdana;
        }
        
        #first, #second, #third, #fourth, #fifth, #sixth, #seventh, #eight, #ninth {
            width: 290px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.5);
            border: 1px solid #ccc; 
            color: black; 
            padding-left: 10px;
            border-radius: 5px;
            margin-left: 20px; 
            margin-bottom: 5px;
        }
        
        #subbtn {
            background-color: teal;
            color: white;
            padding: 5px;
            margin-left: 100px;
            margin-top: 10px;
            height: 40px;
            width: 100px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
        }
        
        #resbtn {
            background-color: white;
            color: black;
            padding: 5px;
            margin-left: 15px;
            margin-top: 10px;
            height: 40px;
            width: 100px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
        }
        
        #gender {
            width: 290px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.5);
            border: 1px solid #ccc; 
            color: grey;
            padding-left: 10px;
            border-radius: 5px;
            margin-left: 20px; 
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div id="box">
            <h3>Register</h3>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <table>
                    <tr>
                        <td><label for="second" style="margin-left:20px;">Enter: </label></td>
                        <td><input type="text" id="second" name="username" placeholder="Name here" required></td>
                    </tr>
                    <tr>
                        <td><label for="third" style="margin-left:20px;">Enter: </label></td>
                        <td><input type="text" id="third" name="dob" placeholder="DD/MM/YYYY"></td>
                    </tr>
                    <tr>
                        <td><label for="gender" style="margin-left:20px;">Enter: </label></td>
                        <td>
                            <select name="gender" id="gender">
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="fifth" style="margin-left:20px;">Enter: </label></td>
                        <td><input type="email" id="fifth" name="email" placeholder="Email address" required></td>
                    </tr>
                    <tr>
                        <td><label for="sixth" style="margin-left:20px;">Enter: </label></td>
                        <td><input type="text" id="sixth" name="phone" placeholder="Phone number"></td>
                    </tr>
                    <tr>
                        <td><label for="eight" style="margin-left:20px;">Enter: </label></td>
                        <td><input type="password" id="eight" name="password" placeholder="Password" required></td>
                    </tr>
                    <tr>
                        <td><label for="ninth" style="margin-left:20px;">Enter: </label></td>
                        <td><input type="password" id="ninth" name="confirm_password" placeholder="Confirm Password" required></td>
                    </tr>
                </table>
                <button type="submit" id="subbtn">Submit</button>
                <button type="button" id="resbtn">Reset</button>
            </form>
        </div>
    </div>

    <script src="jquery-3.7.1.js"></script>
    <script>
        $(document).ready(function(){
            $("#subbtn").click(function(event){
                var passed = true;

                var email = $("#fifth").val();
                var validEmail = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                if (!validEmail.test(email)) {
                    alert("Please enter a valid email address.");
                    passed = false;
                }

                var phone = $("#sixth").val();
                if (phone && !/^\d{10}$/.test(phone)) {
                    alert("Phone number must be 10 digits.");
                    passed = false;
                }

                var pass = $("#eight").val();
                var confirm = $("#ninth").val();
                if (pass !== confirm) {
                    alert("Passwords do not match.");
                    passed = false;
                }

                if (!passed) {
                    event.preventDefault();
                }
            });

            $("#resbtn").click(function() {
                $("input").val("");
                $("#gender").val("");
            });
        });
    </script>

    <?php
    // Process form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Create database connection
        $conn = mysqli_connect("localhost", "root", "", "software");
        
        // Check connection
        if (!$conn) {
            die("<script>alert('Connection failed: " . mysqli_connect_error() . "');</script>");
        }
        
        // Get form data
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        // Validate required fields
        if (empty($username) || empty($email) || empty($password)) {
            echo "<script>alert('Username, Email, and Password are required');</script>";
            exit();
        }
        
        // Check if email already exists
        $check_query = "SELECT * FROM users WHERE email = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows > 0) {
            echo "<script>alert('Email already exists. Please use a different email.');</script>";
            $check_stmt->close();
            mysqli_close($conn);
            exit();
        }
        $check_stmt->close();
        
        // Insert new user
        $insert_query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("sss", $username, $email, $password);
        
        // Execute query and handle result
        if ($stmt->execute()) {
            echo "<script>
                alert('Registration successful! Redirecting to login page.');
                window.location.href='Login.php';
            </script>";
            $stmt->close();
            mysqli_close($conn);
            exit();
        } else {
            echo "<script>alert('Registration failed: " . $stmt->error . "');</script>";
            $stmt->close();
            mysqli_close($conn);
        }
    }
    ?>
</body>
</html>