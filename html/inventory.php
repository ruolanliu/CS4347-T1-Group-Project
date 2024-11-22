<?php 
$uid = $_GET['uid'];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Inventory</title>
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
            $env = parse_ini_file('.env');
            $servername = $env["SERVER"];
            $username = $env["USERNAME"];
            $password = $env["PASSWORD"];
            $db = $env["DATABASE"];
            $sign = '$';

            $conn = new mysqli($servername, $username, $password, $db);

            if($conn->connect_error){
                echo "bad connection";
            } else {
                $sql1 = "select itemid, unitquantity, ppu, availablestock, productname
                from Item natural join product";
                $res1 = $conn->query($sql1);

                if($res1){
                    echo <<<th
                        <section class="wrapper-main">
                            <table>
                                <tr>
                                    <th>Item ID</th>
                                    <th>Product Name</th>
                                    <th>Price</th>
                                    <th>Stock Quantity</th>
                                </tr>
                    th;

                    while($row1 = $res1->fetch_assoc()){
                        $price = $row1['ppu']*$row1['unitquantity'];
                        echo <<<tbl1
                                    <tr>
                                        <td>{$row1['itemid']}</td>
                                        <td>{$row1['productname']}</td>
                                        <td>{$sign}{$price}</td>
                                        <td>{$row1['availablestock']}</td>
                                        <td>
                                    </tr>
                        tbl1;
                    }

                    echo " 
                            </table> 
                        </section>";
                }
            }

        ?>

    </body>
</html>