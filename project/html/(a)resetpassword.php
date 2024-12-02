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

    // connect to mysql
    $env = parse_ini_file('.env');
    $servername = $env["SERVER"];
    $db_username = $env["USERNAME"];
    $db_password = $env["PASSWORD"];
    $dbname = $env["DATABASE"];

    $conn = new mysqli($servername, $db_username, $db_password, $dbname);

    if ($conn->connect_error) {
        echo "Database connection failed.";
    } else {
        // Step 1: Unsafe SELECT query
        $sql_select = "SELECT * FROM CUSTOMER WHERE Email='$username'";
        echo "<p>Executed Query (SELECT): $sql_select</p>"; // Display the query for demonstration

        $result = $conn->query($sql_select);

        if ($result->num_rows > 0) {
            // Step 2: If user exists, proceed to update password
            $sql_update = "UPDATE CUSTOMER SET Password='$new_password' WHERE Email='$username'";
            echo "<p>Executed Query (UPDATE): $sql_update</p>"; // Display the query for demonstration

            if ($conn->query($sql_update) === TRUE) {
                echo "Password reset successfully.";
                echo "<form action='signin.html'><button>Go to Sign In</button></form>";
            } else {
                echo "Error: Could not reset password.";
                echo "<form action='resetpassword.html'><button>Go Back to Reset Password</button></form>";
            }
        } else {
            echo "Error: User not found.";
            echo "<form action='resetpassword.html'><button>Go Back to Reset Password</button></form>";
        }
    }
    $conn->close();
}

/*
Instructions for Testing (a):

1. Basic Functionality Test:
   - Input a valid username and matching new password in the form.
   - Expected Result: The password is successfully reset, and a confirmation message is displayed.

2. SQL Injection Test:
   - Input: For the "username" field, enter `' OR '1'='1`.
   - Input: For "new_password" and "confirm_password" fields, enter any matching values.
   - Expected Result: If the code is vulnerable, passwords for unintended users might get updated, demonstrating an SQL injection vulnerability.

3. Partial Data Test:
   - Input: Provide only part of a username (e.g., `'`).
   - Input: For "new_password" and "confirm_password" fields, enter valid matching passwords.
   - Expected Result: The query executes but doesn't retrieve or update any user data.
*/

?>
