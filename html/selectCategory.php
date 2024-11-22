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
	$sql = "SELECT CategoryName FROM CATEGORY";
	$result = $conn->query($sql);
	if($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			printf("Category: %s <br />", $row["CategoryName"]);
		}
	}
	mysqli_free_result($result);
	$sql->close();
	$conn->close();
	
 }