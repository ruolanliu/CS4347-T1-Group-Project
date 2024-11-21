<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // get user info
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // connect to mysql
        $env = parse_ini_file('.env');
        $un = $env["USERNAME"];
        $pwd = $env["PASSWORD"];

        $conn = new PDO('mysql:host=localhost:3306;dbname=b2b', $un, $pwd);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // check email exist
        $stmt = $conn->prepare("SELECT * FROM CUSTOMER WHERE Email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "Error: Email already exists.";
            echo "<form action='signup.html'><button>Go Back to Sign Up</button></form>";
        } else {
            $sql1 = $conn->prepare("SELECT MAX(CustomerID) FROM CUSTOMER");
            $sql1->execute();
            $res1 = $sql1->fetch();
            $id = $res1['MAX(CustomerID)'] + 1;
            // add new user
            $stmt = $conn->prepare("INSERT INTO CUSTOMER (CustomerID, FName, LName, Email, PhoneNumber, Password) VALUES (:id, :fname, :lname, :email, :phone, :password)");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':fname', $fname);
            $stmt->bindParam(':lname', $lname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':password', $password); 
            $stmt->execute();

            echo "Signup successful.";
            echo "<form action='signin.html'><button>Go to Sign In</button></form>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
