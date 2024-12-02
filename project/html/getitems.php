<?php 
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
 
    $env = parse_ini_file('.env');
    $servername = $env["SERVER"];
    $username = $env["USERNAME"];
    $password = $env["PASSWORD"];
    $db = $env["DATABASE"];

    $conn = new mysqli($servername, $username, $password, $db);

    if($conn->connect_error){
        echo "bad connection";
    } else {
        $type = $_GET['type'];
        $id = $_GET['id'];

        if($type == 'c'){
            $sql1 = "select productid, productname from product 
                    where categoryid = {$id}";
            $res1 = $conn->query($sql1);
            $prods = array();
            while($row1 = $res1->fetch_assoc()){
                $obj = new stdClass();
                $obj->id = (int)$row1['productid'];
                $obj->name = $row1['productname'];
                $prods[] = $obj;
            }
            $jsn = json_encode($prods);
            echo $jsn;
        } elseif ($type == 'p'){
            $sql1 = "select itemid, unitquantity from item 
                    where productid = {$id}";
            $res1 = $conn->query($sql1);
            $prods = array();
            while($row1 = $res1->fetch_assoc()){
                $obj = new stdClass();
                $obj->id = $row1['itemid'];
                $obj->name = $row1['unitquantity'];
                $prods[] = $obj;
            }
            $jsn = json_encode($prods);
            echo $jsn;
        }

    }
}

?>