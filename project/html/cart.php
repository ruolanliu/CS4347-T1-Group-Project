<?php 
    $json = file_get_contents('php://input');

    // Converts it into a PHP object
    $data = json_decode($json);
    $uid = $data->uid;
    $items = $data->items;
?>



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
        <h1 style="color: white">Shopping Cart</h1>

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

                if($items){
                    echo <<<th
                        <section class="wrapper-main">
                            <table>
                                <tr>
                                    <th>Item ID</th>
                                    <th>Product Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Delete</th>
                                </tr>
                    th;
                     $sum = 0.0;
                    for($i=0; $i<count($items); $i++){
                        $sql1 = "select unitquantity, ppu, productname from item natural join product
                                where itemid = {$items[$i]->itemID}";
                        $res1 = $conn->query($sql1)->fetch_assoc();
                        $price = $res1['ppu']*$res1['unitquantity']*$items[$i]->qty;
                        $sum = $sum+$price;
                        echo <<<tbl1
                                    <tr>
                                        <td class="item">{$items[$i]->itemID}</td>
                                        <td>{$res1['productname']}</td>
                                        <td class="price">{$sign}{$price}</td>
                                        <td class="qty">{$items[$i]->qty}</td>
                                        <td><button onclick="this.parentElement.parentElement.remove(); editPrice()">Delete</button><td>
                                        <td>
                                    </tr>
                        tbl1;
                    }

                    echo " 
                            </table> 
                        </section>
                        <p style='color: white' id='pr'>Total Price: {$sign}{$sum}</p>"
                        ;

                    echo "<br><br> <button class='btn' style='padding: 10px' onclick='goCheckout({$uid})'>Confirm Order</button>";
                }
            }

        ?>

       

    </body>
