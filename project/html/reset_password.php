
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // get user info
    $username = $_POST['username'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // check password match or not
    if ($new_password !== $confirm_password) {
        echo "Error: Passwords do not match.";
        echo "<form action='resetpassword.html'><button>Go Back to Reset Password</button></form>";
        exit;
    }

    try {
        // connect to mysql
        $env = parse_ini_file('.env');
        $un = $env["USERNAME"];
        $pwd = $env["PASSWORD"];

        $conn = new PDO('mysql:host=localhost:3306;dbname=b2b', $un, $pwd);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // check user exist
        $stmt = $conn->prepare("SELECT * FROM CUSTOMER WHERE Email = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // update password
            $stmt = $conn->prepare("UPDATE CUSTOMER SET Password = :new_password WHERE Email = :username");
            $stmt->bindParam(':new_password', $new_password); 
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            echo "Password reset successfully.";
            echo "<form action='signin.html'><button>Go to Sign In</button></form>";
        } else {
            echo "Error, user is not found.";
            echo "<form action='resetpassword.html'><button>Go Back to Reset Password</button></form>";
        }

        $conn=null;

    } catch (PDOException $e) {
        echo "Error: " . $e;
    }
}
?>
