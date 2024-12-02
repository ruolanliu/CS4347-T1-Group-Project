<?php 
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
 
        $env = parse_ini_file('.env');
        $servername = $env["SERVER"];
        $username = $env["USERNAME"];
        $password = $env["PASSWORD"];
        $db = $env["DATABASE"];

        $conn = new mysqli($servername, $username, $password, $db);

        if($conn->connect_error){
            echo "bad connection";
        } else {
            $un = $_GET['username'];
            $pwd = $_GET['password'];

            $sql1 = "select * from customer where Email='$un' and Password='$pwd'";

            $res1 = $conn->query($sql1)->fetch_assoc();

            if($res1){
                $sql2 = "select CustomerID from Customer where Email='$un'";
                $res2 = $conn->query($sql2)->fetch_assoc();
                header("Location: http://localhost:8888/mainpage.php?uid={$res2['CustomerID']}");

            } else {
                echo "Error, username or password is not found.";
                echo "<form action='signin.html'><button>Go Back to Sign In/button></form>";
        

            }


        }
        $conn->close();
    }
?>

