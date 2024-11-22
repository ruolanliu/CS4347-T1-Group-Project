<? php

 if ($_SERVER['REQUEST_METHOD'] === 'GET') {
 
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
	$selectedProduct = $_POST['selectedProduct'];
	$sanitized_selectedProduct =  mysqli_real_escape_string($conn, $selectedProduct);
	$sql = "SELECT p.ProductName, p.UnitMeasurement, i.UnitQuantity, i.UnitQuantity * i.PPU, i.PPU
	FROM PRODUCT as p, ITEM as i
	WHERE p.ProductName = '" . $sanitized_selectedProduct . "' AND i.ProductID = p.ProductID";
	$result = $conn->query($sql);
	if($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			printf("Product: %s, Amount: %s %s, Price: %s, PPU: %s  <br />", 
			$row["p.ProductName"],
			$row["i.UnitQuantity"],
			$row["p.UnitMeasurement"],
			$row["i.UnitQuantity * i.PPU"],
			$row["i.PPU"]);
		}
	}
	mysqli_free_result($result);
	
	$selectedItem = $_POST['selectedItem'];
	$sanitized_selectedItem =  mysqli_real_escape_string($conn, $selectedItem);
	$sql = 
	
	
 }