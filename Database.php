<?php
session_start();

// Check if user is logged in as admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "software";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all users
$userQuery = "SELECT id, username, email, password FROM users";
$userResult = $conn->query($userQuery);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - User Sales</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: teal;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            max-width: 1000px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
        }

        h3 {
            color: teal;
            text-align: center;
            font-style: italic;
            text-decoration: underline;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #999;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: teal;
            color: white;
        }

        .actions button {
            display: block;
            width: 100%;
            margin-bottom: 5px;
            padding: 5px;
        }

        #userDetailsModal {
            display: none;
            background: white;
            border: 1px solid black;
            padding: 15px;
            margin-top: 20px;
        }

        #userDetailsModal table {
            width: 100%;
            border-collapse: collapse;
        }

        #userDetailsModal th, #userDetailsModal td {
            border: 1px solid #aaa;
            padding: 6px;
        }

        #closeModal {
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h3>User Sales Overview</h3>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Password</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if ($userResult && $userResult->num_rows > 0) {
            while ($user = $userResult->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($user["id"]) . "</td>";
                echo "<td>" . htmlspecialchars($user["username"]) . "</td>";
                echo "<td>" . htmlspecialchars($user["email"]) . "</td>";
                echo "<td>" . htmlspecialchars($user["password"]) . "</td>";
                echo "<td class='actions'>
                        <button class='view-details' data-id='" . $user["id"] . "'>View</button>
                        <button class='delete-user' data-id='" . $user["id"] . "'>Delete</button>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No users found.</td></tr>";
        }
        ?>
        </tbody>
    </table>

    <div id="userDetailsModal">
        <h3>User Sales Details</h3>
        <div id="userDetails"></div>
        <button id="closeModal">Close</button>
    </div>

    <p style="text-align:center;"><a href="logout.php">Logout</a></p>
</div>

<script>
    $(document).ready(function () {
        $(".view-details").click(function () {
            var userId = $(this).data("id");

            $.ajax({
                url: "fetch_sales.php",
                type: "POST",
                data: { user_id: userId },
                success: function (response) {
                    $("#userDetails").html(response);
                    $("#userDetailsModal").show();
                }
            });
        });

        $("#closeModal").click(function () {
            $("#userDetailsModal").hide();
        });

        $(".delete-user").click(function () {
            var userId = $(this).data("id");
            if (confirm("Delete user #" + userId + "?")) {
                alert("User #" + userId + " deleted.");
                $(this).closest("tr").remove();
                // Optional: Implement backend deletion logic here.
            }
        });
    });
</script>
</body>
</html>
