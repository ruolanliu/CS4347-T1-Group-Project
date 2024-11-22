<?php 
$uid = $_GET['uid'];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>New Order</title>
        <link rel="stylesheet" href="mainstyle.css">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <script>

            function addSelects(){
                const form = document.getElementById("body");
                const opts = form.firstElementChild;
                const clone = opts.cloneNode(true);
                form.appendChild(clone);
            }


            function findProds(elem){
                const prods = elem.nextElementSibling;
                if(prods.children.length > 0){
                    for(let i=0; i<=prods.children.length; i++){
                        prods.removeChild(prods.children[0]);
                    }
                    prods.removeChild(prods.children[0]);

                }
                
                const catID = elem.value;
                const req = new XMLHttpRequest();
                req.onload = function(){
                    const res = JSON.parse(req.responseText);
                    for(let i=0; i<res.length; i++){
                        const name = document.createTextNode(res[i].name);
                        const val = document.createAttribute("value");
                        val.value = res[i].id;
                        const opt = document.createElement('option');
                        opt.setAttributeNode(val);
                        opt.appendChild(name);
                        prods.appendChild(opt);
                    }

                }
                req.open("GET", "http://localhost:8888/getitems.php?type=c&id="+catID,);
                req.send()
                
            }

            function findItems(elem){
                const items = elem.nextElementSibling;
                if(items.children.length > 0){
                    for(let i=0; i<=items.children.length; i++){
                        items.removeChild(items.children[0]);
                    }
                    items.removeChild(items.children[0]);

                }

                const prodID = elem.value;
                const req = new XMLHttpRequest();
                req.onload = function(){
                    const res = JSON.parse(req.responseText);
                    for(let i=0; i<res.length; i++){
                        const name = document.createTextNode(res[i].name);
                        const val = document.createAttribute("value");
                        val.value = res[i].id;
                        const opt = document.createElement('option');
                        opt.setAttributeNode(val);
                        opt.appendChild(name);
                        items.appendChild(opt);
                    }
                    
                }
                req.open("GET", "http://localhost:8888/getitems.php?type=p&id="+prodID);
                req.send()
            }

            function sendOrder(user){
                const items = document.getElementsByClassName("item");
                const qtys = document.getElementsByClassName("qty");
                const list = [];
                for(let i=0; i<items.length; i++){
                    const obj = {};
                    obj.itemID = items[i].value;
                    obj.qty = qtys[i].value;
                    list.push(obj);
                }
                const req = {};
                req.uid = user;
                req.items = list;
                let httpreq = new XMLHttpRequest();
                httpreq.onload = function(){
                    document.getElementsByTagName('body')[0].outerHTML = httpreq.responseText;
                }
                httpreq.open("POST", "http://localhost:8888/cart.php", true);
                httpreq.setRequestHeader('Content-Type', 'application/json');
                httpreq.send(JSON.stringify(req));
            }

            function goCheckout(user){
                const items = document.getElementsByClassName("item");
                const qtys = document.getElementsByClassName("qty");
                const list = [];
                for(let i=0; i<items.length; i++){
                    const obj = {};
                    obj.itemID = items[i].innerText;
                    obj.qty = qtys[i].innerText;
                    list.push(obj);
                }
                const req = {};
                req.uid = user;
                req.items = list;
                
                let httpreq = new XMLHttpRequest();
                httpreq.onload = function(){
                    document.getElementsByTagName('body')[0].innerHTML = httpreq.responseText;
                }
                httpreq.open("POST", "http://localhost:8888/checkout.php", true);
                httpreq.setRequestHeader('Content-Type', 'application/json');
                httpreq.send(JSON.stringify(req)); 
            }
        
        </script>

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

            
        </div >

        <div style="padding-left: 100px; padding-top: 20px;">
            <h1 style="color: white">Create Order</h1>
            <br>
            <br>
            <button class="btn" style="padding: 10px" onclick="addSelects()">Add Item</button>
            <br>
            <br>
            <form action="" id="body">
                <div>
                    <select name="category" style="padding: 10px" oninput="findProds(this)">
                        <?php 
                            $env = parse_ini_file('.env');
                            $servername = $env["SERVER"];
                            $username = $env["USERNAME"];
                            $password = $env["PASSWORD"];
                            $db = $env["DATABASE"];
                
                            $conn = new mysqli($servername, $username, $password, $db);
                
                            if($conn->connect_error){
                                echo "bad connection";
                            } else {
                                $sql1 = "select * from category";
                                $res1 = $conn->query($sql1);
                                while($row1 = $res1->fetch_assoc()){
                                    echo"<option value='{$row1['CategoryID']}'>{$row1['CategoryName']}</option>";
                                }
                            }
                        ?>
                    </select>
                    <select name="prod" style="padding: 10px" oninput="findItems(this)">

                    </select>
                    <select name="item" class="item" style="padding: 10px">
                        
                    </select>
                    <input type="number" name="qty" class="qty" style="padding: 10px">
                    <br>
                    <br>
                </div>

            </form>
            <br>
            <br>
            <?php
            echo "<button class='btn' style='padding: 10px' onclick='sendOrder({$uid})'>Go to Shopping Cart</button>"
            ?>
        </div>

       

        
    </body>
</html>