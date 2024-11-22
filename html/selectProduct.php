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
	$selectedCategory = $_POST['selectedCategory'];
	$sanitized_selectedCategory =  mysqli_real_escape_string($conn, $selectedCategory);
	$sql = "SELECT ProductName 
	FROM PRODUCT as p, CATEGORY as c 
	WHERE c.CategoryName ='" . $sanitized_selectedCategory . "' AND p.CategoryID = c.CategoryID";
	$result = $conn->query($sql);
	if($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			printf("Product: %s <br />", $row["p.ProductName"]);
		}
	}
	mysqli_free_result($result);
	$sql->close();
	$conn->close();
	
	
 }