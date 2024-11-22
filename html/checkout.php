<? php

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
	
	$cart_json = file_get_contents('php://PLACEHOLDERPATH');
	$cart = json_decode($cart_json); //decode json and place into variable

	$cartArr = JSON.parse($cart); //parse json into an array (not sure if decode already does this)
	//initalize constant values (not itemID/qty)
	$sql = "SELECT MAX(OrderID) FROM ORDERS"; 
	$orderID = $conn->query($sql);
	$orderID = $orderID + 1;
	$sql = "SELECT MAX(InvoiceID) FROM INVOICE"; 
	$invoiceID = $conn->query($sql);
	$invoiceID = $invoiceID + 1;
	$customerID = $cartArr.uid[0]; //Only one entry in 'uid' portion of json
	$sql = "SELECT Address FROM CUSTOMER WHERE CustomerID ='" . $customerID . "'"; //currently not accounting for situations where customer does not have an address registered 
	$address = $conn->query($sql);
	//$orderDate = date("Y/m/d"); //php date function - If SQL function doesnt work
	$orderStatus = 'Order Placed'; //same as default value but added to avoid any issues with insertion
	
	//create Order record
	$stmt = $conn->prepare("INSERT INTO ORDERS (OrderID, CustomerID,DeliveryAddress, OrderedOn, OrderStatus)
	VALUES (:oid, :uid, :address, CURRENT_TIMESTAMP(), :status)"); //CURRENT_TIMESTAMP() returns the current date+time (same datatype used in ORDERS relation)
	$stmt->bindParam(':oid', $orderID);
	$stmt->bindParam(':uid', $customerID);
	$stmt->bindParam(':address', $address);
	//$stmt->bindParam(':orderedOn', $orderDate); //if SQL function doesnt work
	$stmt->bindParam(':status', $orderStatus);
	$stmt->execute();
	
	//create related Invoice record
	$stmt = $conn->prepare("INSERT INTO INVOICE (InvoiceID, OrderID)
	VALUES (:vid, :oid)");
	$stmt->bindParam(':vid', $invoiceID);
	$stmt->bindParam(':oid', $orderID);
	$stmt->execute();
	
//use each tuple in JSON to create respective Order/Invoice/Itemized_Receipt entries 
 for(int i = 0; i < $cartArr.items.length; i++){ //since items.length = qty.length
	 $itemID = $cartArr.items[i];
	 $qty = $cartArr.qty[i];
	 
	 //verify that item has AvailableStock >=qty. If not then skip this Item.
	 $sql = "SELECT AvailableStock FROM ITEM 
		WHERE ITEM.ItemID = '". $itemID ."'";
	 $res = $conn->query($sql);
	 if($res < $qty){echo "Error: Selected quantity for '" . $itemID . "' exceeds current available stock."; continue;}
	 
	 //create related Itemized_Receipt record
	 $stmt = $conn->prepare("INSERT INTO ITEMIZED_RECEIPT(InvoiceID, ItemID, ItemQuantity)
	 VALUES(:vid, :itemID, :qty)");
	 $stmt->bindParam(':vid', $invoiceID);
	 $stmt->bindParam(':itemID', $itemID);
	 $stmt->bindParam(':qty', $qty);
	 $stmt->execute();
	 
	 //update item stock to reflect placed Order
	 $stmt = $conn->prepare("UPDATE ITEM
	 SET AvailableStock = AvailableStock - :qty WHERE ItemID = :itemID");
	 $stmt->bindParam(':itemID', $itemID);
	 $stmt->bindParam(':qty', $qty);
	 $stmt->execute();
 }
	

 }
 ?>