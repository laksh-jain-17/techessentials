<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $host = "localhost"; // your DB host
    $user = "root";      // your DB user
    $pass = "";          // your DB password
    $dbname = "software"; // your DB name

    $conn = new mysqli($host, $user, $pass, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    // Check if password and confirm password match
    if ($password !== $confirm) {
        echo "<script>alert('Password and Confirm Password do not match');</script>";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Use prepared statement to check if email exists
        $sql = $conn->prepare("SELECT * FROM users WHERE email=?");
        $sql->bind_param("s", $email);
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows > 0) {
            // Email exists, update the password
            $update_sql = $conn->prepare("UPDATE users SET password=? WHERE email=?");
            $update_sql->bind_param("ss", $hashed_password, $email);

            if ($update_sql->execute()) {
                echo "<script>alert('Password updated successfully. Please log in with your new password.'); window.location.href='Login.php';</script>";
            } else {
                echo "<script>alert('Error updating password');</script>";
            }
        } else {
            // Email does not exist in the database
            echo "<script>alert('Email not found');</script>";
        }

        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Forget Password Page</title>
    <style>
        body {
            background: teal;
            font-family: Arial;
        }
        #box {
            background-color: rgba(255,255,255,0.5);
            width: 400px;
            height: 300px;
            color: white;
            position: relative;
            padding: 20px;
            border-radius: 10px;
            margin-left: auto;
            margin-right: auto;
            margin-top: 150px;
        }
        h3 {
            color: teal;
            text-decoration: underline;
            text-align: center;
            font-style: italic;
            font-weight: bold;
            font-family: Verdana;
        }
        #first, #second, #third {
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
            margin-left: 150px;
            margin-top: 10px;
            height: 40px;
            width: 100px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
        }
        label {
            margin-left: 20px;
        }
    </style>
</head>
<body>
    <div id="box">
        <h3>Forget Password</h3>
        <form method="post" action="">
            <table>
                <tr>
                    <td><label for="first">Email: </label></td>
                    <td><input type="text" id="first" name="email" placeholder="Enter email address here"></td>
                </tr>
                <tr>
                    <td><label for="second">New: </label></td>
                    <td><input type="password" id="second" name="password" placeholder="Enter new password"></td>
                </tr>
                <tr>
                    <td><label for="third">Confirm: </label></td>
                    <td><input type="password" id="third" name="confirm" placeholder="Confirm new password"></td>
                </tr>
            </table>
            <div id="message"></div>
            <button type="submit" id="subbtn">Submit</button>
        </form>
    </div>
</body>
</html>
