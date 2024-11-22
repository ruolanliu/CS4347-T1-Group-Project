<?php 
$uid = $_GET['uid'];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Order History</title>
        <link rel="stylesheet" href="mainstyle.css">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    </head>

    <body>
    <div class="container">
            <div class="menu">
                <ul>
                    <li class="logo"><img src="logo.png"></li>
                    <?php
                    echo "<li><a href='http://localhost:8888/mainpage.php?uid={$uid}'  class='active'>Home</a></li>";
                    
                    echo "<li><a href='http://localhost:8888/inventory.php?uid={$uid}' class='inventory-btn'>Inventory</a></li>";
                    ?>
                    <li>Order Status</li>
                    <?php
                    echo "<li><a href='http://localhost:8888/orders.php?uid={$uid}'>Order History</a></li>";
                    
                    echo "<li><a href='http://localhost:8888/neworder.php?uid={$uid}'>New Order</a></li>";
                    ?>
                    <li>Checkout</li>
                    <li><a href="signin.html" class="signout-btn">Sign Out</a></li>
                </ul>
            </div>
        </div>

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
            $uid = $_GET['uid'];
            $sql1 = "select * from orders where CustomerID = {$uid}";
            $res1 = $conn->query($sql1);
            $sign = '$';

            echo "<h1 style='color: white'>Order History</h1>";

            if($res1){
                echo " 
                    <table>
                        <tr>
                                <td>Order ID</td>
                                <td>Delivered to</td>
                                <td>Order Date</td>
                                <td>Arrival Date</td>
                                <td>Items</td>
                                <td>Total Price</td>
                                <td>Status</td>
                        </tr>
                ";
                while($row1 = $res1->fetch_assoc()){
                    $oid = $row1['OrderID'];
                    $sql2 = "select ItemQuantity, UnitQuantity, PPU, ProductName 
                            from itemized_receipt,item,product where InvoiceID = {$oid} 
                            and itemized_receipt.ItemID = item.ItemID and item.ProductID = product.ProductID";
                    $res2 = $conn->query($sql2);
                    echo <<<tbl1
                        <tr>
                            <td>{$row1['OrderID']}</td>
                            <td>{$row1['DeliveryAddress']}</td>
                            <td>{$row1['OrderedOn']}</td>
                            <td>{$row1['DeliveryEstimate']}</td>
                            <td>
                                <ul>
                    tbl1;
                    $sum = 0.0;

                    while($row2 = $res2->fetch_assoc()){
                        $sum = $sum + ($row2['UnitQuantity']*$row2['ItemQuantity']*$row2['PPU']);
                        echo "<li>{$row2['ProductName']} ({$row2['UnitQuantity']} ct): {$row2['ItemQuantity']}</li>";
                    }
                
                    echo <<<tbl2
                                </ul>
                            </td>
                            <td>{$sign}{$sum}</td>
                            <td>{$row1['OrderStatus']}</td>
                        </tr>
                    tbl2;
                }
                echo "<table>";
            }

           
        }
        $conn->close();
    }

?>

</body>
</html>