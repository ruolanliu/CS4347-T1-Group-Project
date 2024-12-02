<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user input
    $username = $_POST['username'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($new_password !== $confirm_password) {
        echo "Error: Passwords do not match.";
        echo "<form action='resetpassword.html'><button>Go Back to Reset Password</button></form>";
        exit;
    }

    // Connect to MySQL database
    $env = parse_ini_file('.env');
    $servername = $env["SERVER"];
    $db_username = $env["USERNAME"];
    $db_password = $env["PASSWORD"];
    $dbname = $env["DATABASE"];

    $conn = new mysqli($servername, $db_username, $db_password, $dbname);

    if ($conn->connect_error) {
        echo "Database connection failed.";
    } else {
        // Unsafe UPDATE query vulnerable to SQL injection
        $sql_update = "UPDATE CUSTOMER SET Password='$new_password' WHERE Email='$username'";
        echo "<p>Executed Query (UPDATE): $sql_update</p>"; // Display the query for demonstration

        // Execute the query
        if ($conn->query($sql_update) === TRUE) {
            echo "Password reset successfully.";
            echo "<form action='signin.html'><button>Go to Sign In</button></form>";
        } else {
            echo "Error: Could not reset password.";
            echo "<form action='resetpassword.html'><button>Go Back to Reset Password</button></form>";
        }
    }
    $conn->close();
}

/*
Instructions for Testing (b):

1. Basic Functionality Test:
   - Input a valid username and matching new password in the form.
   - Expected Result: The password is successfully updated for the given username.

2. SQL Injection Test:
   - Input: For the "username" field, enter `' OR '1'='1`.
   - Input: For "new_password" and "confirm_password" fields, enter any matching values.
   - Expected Result: If the code is vulnerable, passwords for all users may be updated, demonstrating an SQL injection vulnerability.

3. Partial Data Test:
   - Input: Provide a partial username (e.g., `'`).
   - Input: For "new_password" and "confirm_password" fields, enter valid matching passwords.
   - Expected Result: If the query executes, unintended or no data should be updated, showing the impact of incomplete input.
*/
?>
