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
                console.log(opts)
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
                    echo "<li><a href='http://localhost:8888/orders.php?uid={$uid}'>Order History</a></li>"
                    ?>
                    <li>Shopping Cart</li>
                    <li>Checkout</li>
                    <li><a href="signin.html" class="signout-btn">Sign Out</a></li>
                </ul>
            </div>

            
        </div >

        <div style="padding-left: 100px">
            <h1>Create Order</h1>
            <br>
            <br>
            <button class="btn" onclick="addSelects()">Add Item</button>
            <br>
            <br>
            <form action="" id="body">
                <div>
                    <select name="category" oninput="findProds(this)">
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
                    <select name="prod" oninput="findItems(this)">

                    </select>
                    <select name="item">
                        
                    </select>
                    <input type="number">
                    <br>
                    <br>
                </div>

            </form>
            <br>
            <br>
        
            <button class="btn">Create Order</button>
        </div>

       

        
    </body>
</html>