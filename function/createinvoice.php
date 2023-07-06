<?php

header("Cache-Control: no-cache");
header("Pragma: no-cache"); 
header("Content-type:application/json");  
include 'connection/connection.php'; 
 
$check = 0;
$id = 0; 
 

if (isset($_SESSION['costumer_id_invoice'])) {
    // URL parameter exists
    $id = $_SESSION['costumer_id_invoice'];
} 
else{
    echo "{\"error\":2,\"result\":\"invalid id.\"}";
    $check = 1;
}  

if($check == 1){ // if any of data is missing it will not allow to make user so this if will stop this function 
    sqlsrv_close($conn);
    exit();
}


$logged_userid= $_SESSION['logged_userid'];

$level = 0;

$sql = "SELECT * from [rms].[sparksystems].[ratsoft_customers] where id = ".$id;
$stmt = sqlsrv_query( $conn, $sql );
if( $stmt === false) {
    die( print_r( sqlsrv_errors(), true) ); 
}
while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
    $level = $row['id'];
 
} 
sqlsrv_free_stmt($stmt);
 

if($level!=0){

    $time= date('Y-m-d H:i:s');

    $sql = "INSERT INTO [rms].[sparksystems].[ratsoft_invoice]  (customer_id, createdby, updatedate) VALUES   
    ('$id','$logged_userid', '$time')";
    $stmt = sqlsrv_query($conn,$sql);

    if( $stmt === false ) {
        die( print_r( sqlsrv_errors(), true));
    } 
    else{
        echo "{\"error\":0,\"result\":\"Succes\"}";
    }

}
else{ 
    echo "[{\"error\":1,\"result\":\"Costumer with id = $id does not exist.\"}]";
}
  
 

sqlsrv_close($conn);

?>