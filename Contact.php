<html>
    <head>
        <title>Contact Us Page</title>
        <style>
            body{
		        background:linear-gradient(to bottom,teal,black);
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
            #boxes{
		        background: rgba(255,255,255,0.5);
		        padding:20px;
		        text-align:center;
		        border-radius:10px;
		        margin-left:130px;
                margin-right:200px;
                margin-top:40px;
		        width:80%;
		        color:white;
	        }
            #names,#org,#mailed,#complaint{
                width: 290px;
                height: 40px;
                background-color: rgba(255, 255, 255, 0.5);
                border: 1px solid #ccc; 
                color: black; 
                padding-left:10px;
                border-radius: 5px;
                margin:10px auto;
                display:block;
            }
            #subbtn{
                background-color:teal;
                color:white;
                padding:5px;
                margin-left:60px;
                margin-top:10px;
                height:40px;
                width:100px;
                border-radius:10px;
                border:none;
            }
            table{
                margin:0px auto;
            }
        </style>
    </head>
    <body>
        <div id="boxes">
            <header>
                <h1>Get in touch</h1>
            </header>
            <form action="Homepage.html" method="post">
                <table>
                    <tr>
                        <td><label for="" style="margin-left:20px;">Enter: </label></td>
                        <td><input type="text" id="names" placeholder="Your Name here" required></td>
                    </tr>
                    <tr>
                        <td><label for="" style="margin-left:20px;">Enter: </label></td>
                        <td><input type="text" id="org" placeholder="Name of the Organization" required></td>
                    </tr>
                    <tr>
                        <td><label for="" style="margin-left:20px;">Enter: </label></td>
                        <td><input type="text" id="mailed" placeholder="Email address" required></td>
                    </tr>
                    <tr>
                        <td><label for="" style="margin-left:20px;">Enter: </label></td>
                        <td><input type="text" id="complaint" placeholder="Write your complaint here" required></td>
                    </tr>
                </table>
                <button id="subbtn">Submit</button>
            </form>
        </div>
    </body>
</html>