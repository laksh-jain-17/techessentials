<html>
    <head>
        <title>Log in page</title>
        <style>
            body{
                background:linear-gradient(to bottom,teal,black);
                font-family:arial;
            }
            #box{
                background-color:rgba(255,255,255,0.5);
                width:400px;
                height:300px;
                color:white;
                position:relative;
                padding:20px;
                border-radius:10px;
                margin-left:535px;
                margin-top:180px;
            }
            h3{
                color:teal;
                text-decoration:underline;
                text-align:center;
                font-style:italic;
                font-weight:1700px;
                font-family:verdana;
            }
            #first,#second{
                width: 290px;
                height: 40px;
                background-color: rgba(255, 255, 255, 0.5);
                border: 1px solid #ccc; 
                color: black; 
                padding-left:10px;
                border-radius: 5px;
                margin-left:20px; 
                margin-bottom:5px;
            }
            #subbtn{
                background-color:teal;
                color:white;
                padding:5px;
                margin-left:100px;
                margin-top:10px;
                height:40px;
                width:100px;
                border-radius:10px;
                border:none;
            }
            a{
                color:teal;
                margin-left:20px;
            }
            #linked{
                padding-left:210px;
            }
        </style>
    </head>
    <body>
        <div id="box">
            <h3>Login</h3>
            <form action="Login.php" method="post">
                <table>
                    <tr>
                        <td><label for="" style="margin-left:20px;">Enter: </label></td>
                        <td><input type="text" id="first" name="email" placeholder="Enter email address here"></td>
                    </tr>
                    <tr>
                        <td><label for="" style="margin-left:20px;">Enter: </label></td>
                        <td><input type="password" id="second" name="password" placeholder="Enter password here"></td>
                    </tr>
                </table>
                <div id="message"></div>
                <button id="subbtn">Submit</button>
                <a href="Forget.php">Forget password</a><br><br>
                <a id="linked" href="Signin.php">Not registered?</a>
            </form>
        </div>
        <script src="jquery-3.7.1.js"></script>
        <script>
            $(document).ready(function(){
                $("#subbtn").click(function(){
                    var a = $("#first").val();
                    var b = $("#second").val();
                    if(a.length == 0 || b.length == 0)
                    {
                        //$("#message").html("<strong>&nbsp&nbsp&nbsp&nbspPlease enter details</strong>").show();//
                        alert("Please enter details");
                        event.preventDefault();
                    }
                    else{
                        $("#message").hide()
                    }
                })
            });
        </script>
        <?php 
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "software";
            $conn = mysqli_connect($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed");
            } else {
                session_start();
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $email = $_POST['email'];
                    $password = $_POST['password'];
                    if($email == "admin@gmail.com" && $password == "admin123") {
                        $_SESSION['admin'] = true;
                        header("Location: database.php");
                        exit();
                    }
                    if (empty($email) || empty($password)) {
                        echo("<script>alert('Please fill details properly');</script>");
                    } 
                    else {
                        $query = "SELECT * FROM users WHERE email=?";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("s", $email);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            if (password_verify($password, $row['password'])) {
                                //$_SESSION['user'] = $row['email'];//
                                $_SESSION['user'] = [
                                    'id' => $row['id'], // make sure $row['id'] is correct
                                    'name' => $row['name'],
                                    'email' => $row['email']
                                ];
                                header("Location: Homepage.php");
                                exit();
                            } else {
                                echo("<script>alert('Invalid credentials');</script>");
                            }
                        } else {
                            echo("<script>alert('User not found');</script>");
                        }
                    }
                }
            }
        ?>
    </body>
</html>
