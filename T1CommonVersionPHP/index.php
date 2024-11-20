<?php
// Process form data if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize user input
    $orderID = isset($_POST['orderID']) ? intval($_POST['orderID']) : 0;

    if ($orderID <= 0) {
        echo "Invalid Order ID.";
        exit();
    }

    try {
        // Connect to SQLite database
        $db = new PDO('sqlite:database.sqlite');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create table and insert test data if the database is empty
        $db->exec("CREATE TABLE IF NOT EXISTS Orders (
            OrderID INTEGER PRIMARY KEY,
            CustomerID INTEGER,
            Status TEXT
        )");
        $db->exec("INSERT OR IGNORE INTO Orders (OrderID, CustomerID, Status) VALUES (1, 1, 'Pending')");
        $db->exec("INSERT OR IGNORE INTO Orders (OrderID, CustomerID, Status) VALUES (2, 2, 'Shipped')");

        // **Unsafe query for SQL injection testing**
        // Uncomment the following block for testing SQL injection
        /*
        $query = "SELECT * FROM Orders WHERE OrderID = $orderID";
        $result = $db->query($query);
        $data = $result->fetch(PDO::FETCH_ASSOC);
        */

        // **Secure query to prevent SQL injection**
        $stmt = $db->prepare("SELECT * FROM Orders WHERE OrderID = :orderID");
        $stmt->bindParam(':orderID', $orderID, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch result
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Output results
        if ($data) {
            echo "Order ID: " . htmlspecialchars($data['OrderID']) . "<br>";
            echo "Customer ID: " . htmlspecialchars($data['CustomerID']) . "<br>";
            echo "Status: " . htmlspecialchars($data['Status']) . "<br>";
        } else {
            echo "No order found with ID: $orderID";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
