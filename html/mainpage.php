<?php 
$uid = $_GET['uid'];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Main Page</title>
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
                    echo "<li><a href='http://localhost:8888/orders.php?uid={$uid}'>Order History</a></li>"
                    ?>
                    <li>Shopping Cart</li>
                    <li>Checkout</li>
                    <li><a href="signin.html" class="signout-btn">Sign Out</a></li>
                </ul>
            </div>
            <div class="banner">
                <div class="app-text">
                    <h1 class="anim">We provide efficient management of paper, stationary, and office supplies for your business.</h1>
                    <p class="anim">Manufactured and shipped with care.</p>
                </div>
                <div class="app-picture">
                    <img src="stationary.png" class="feature-img anim"> <!-- Image from https://www.flaticon.com/free-icon/stationary_5001021?related_id=5001021&origin=search made by BomSymbols -->
                </div>
            </div>
        </div>
    </body>
</html>