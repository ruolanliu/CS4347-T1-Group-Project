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

            echo <<<HTL
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" href="style.css">
                <title>Signup Successful</title>
            </head>
            <body>
                <div class="wrapper">
                    <h1>Signup Successful</h1>
                    <p style="text-align: center; color: dimgray; font-size: 16px; margin-bottom: 20px;">
                        Your account has been created successfully.
                    </p>
                    <form action="signin.html">
                        <button type="submit" class="btn">Go to Sign In</button>
                    </form>
                </div>
            </body>
            </html>
            HTL;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
