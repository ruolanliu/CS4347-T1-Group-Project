<?php

 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
        $env = parse_ini_file('.env');
        $servername = $env["SERVER"];
        $username = $env["USERNAME"];
        $password = $env["PASSWORD"];
        $db = $env["DATABASE"];
		
		
	$conn = new mysqli($servername, $username, $password, $db); 
	if($conn->connect_error){
            echo "bad connection";
			die("Connection error: " .$conn->connect_error);
    }
	
	//$uid = $_POST['CustomerID'];
	//$s_uid = mysqli_real_escape_string($conn, $CustomerID);
	$cart_json = file_get_contents('php://input');
	$cart = json_decode($cart_json);
	$items = $cart->items;

	try {
		//initalize constant values (not itemID/qty)
		$sql = "SELECT MAX(OrderID) FROM ORDERS"; 
		$orderID = $conn->query($sql)->fetch_assoc();
		$orderID = $orderID['MAX(OrderID)'] + 1;

		$sql = "SELECT MAX(InvoiceID) FROM INVOICE"; 
		$invoiceID = $conn->query($sql)->fetch_assoc();
		$invoiceID = $invoiceID['MAX(InvoiceID)'] + 1;
		$customerID = $cart->uid;
		$uid = $cart->uid;

		$sql = "SELECT Address FROM CUSTOMER WHERE CustomerID ='" . $customerID . "'"; //currently not accounting for situations where customer does not have an address registered 
		$address = $conn->query($sql)->fetch_assoc();
		$address = $address['Address'];
		$orderDate = date("Y/m/d");
		$orderStatus = 'Order Placed';

		//create Order record
		$stmt = $conn->prepare("INSERT INTO ORDERS (OrderID, CustomerID,DeliveryAddress, OrderedOn, DeliveryEstimate, OrderStatus)
		VALUES (?, ?, ?, CURRENT_TIMESTAMP(), TIMESTAMPADD(Day, 14, CURRENT_TIMESTAMP()),?)"); //CURRENT_TIMESTAMP() returns the current date+time (same datatype used in ORDERS relation)
		$stmt->bind_param("iiss", $orderID, $customerID, $address, $orderStatus);
		$stmt->execute();
			
		//create related Invoice record
		$stmt = $conn->prepare("INSERT INTO INVOICE (InvoiceID, OrderID)
		VALUES (?, ?)");
		$stmt->bind_param("ii", $invoiceID, $orderID);
		$stmt->execute();
		
		
		for($i = 0; $i < count($items); $i++){
			$itemID = $items[$i]->itemID;
			$qty = $items[$i]->qty;

			//verify that item has AvailableStock >=qty. If not then skip this Item.
			$sql = "SELECT AvailableStock FROM ITEM 
				WHERE ITEM.ItemID = {$itemID}";
			$res = $conn->query($sql)->fetch_assoc();
			if($res < $qty){echo "Error: Selected quantity for '" . $itemID . "' exceeds current available stock."; continue;}
			
			
			 //create related Itemized_Receipt record
			$stmt = $conn->prepare("INSERT INTO ITEMIZED_RECEIPT(InvoiceID, ItemID, ItemQuantity)
			VALUES(?, ?, ?)");
			$stmt->bind_param("iii", $invoiceID, $itemID, $qty);
			$stmt->execute();
			
			 //update item stock to reflect placed Order
			$stmt = $conn->prepare("UPDATE ITEM
			SET AvailableStock = AvailableStock - ? WHERE ItemID = ?");
			$stmt->bind_param("ii", $itemID, $qty);
			$stmt->execute();
		}

		echo <<<markup
		<div class="container">
			<div class="menu">
				<ul>
					<li class="logo"><img src="logo.png"></li>
					<li><a href='http://localhost:8888/mainpage.php?uid={$uid}'  class='active'>Home</a></li>
					<li><a href='http://localhost:8888/inventory.php?uid={$uid}' class='inventory-btn'>Inventory</a></li>
					
					<li>Order Status</li>
					<li><a href='http://localhost:8888/orders.php?uid={$uid}'>Order History</a></li>
					<li><a href='http://localhost:8888/neworder.php?uid={$uid}'>New Order</a></li>
					<li>Checkout</li>
					<li><a href="signin.html" class="signout-btn">Sign Out</a></li>
				</ul>
				</div>
			</div >

			<div style="padding-left: 100px; padding-top: 20px;">
				<h1 style="color: white">Success!</h1>
				<br><br>
				<h3>You have confirmed your order!</h3>
				<br><br>
				<a href="http://localhost:8888/orders.php?uid={$uid}">
					<button class="btn" style="padding: 10px">View Order History</button>
				<a>
		
				
			</div>
		
		markup;

	} catch (Exception $e){
		echo "Error: " . $e;
	}
	

 }
 ?>