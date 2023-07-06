<?php

header("Cache-Control: no-cache");
header("Pragma: no-cache"); 
header("Content-type:application/json");  
include 'connection/connection.php'; 
 
$id = 0;
if (isset($_SESSION['delete_id'])) {
    // URL parameter exists
    $id = $_SESSION['delete_id'];
} 
else{
    echo "{\"error\":2,\"result\":\"invalid id.\"}";
    sqlsrv_close($conn);
    exit();
} 

$level = 0;

$sql = "SELECT * from [rms].[sparksystems].[ratsoft_customers] where id = ".$_SESSION['delete_id'];
$stmt = sqlsrv_query( $conn, $sql );
if( $stmt === false) {
    die( print_r( sqlsrv_errors(), true) ); 
}
while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
    $level = $row['id'];
 
} 
sqlsrv_free_stmt($stmt);

if($level!=0){
    $sql = "DELETE FROM [rms].[sparksystems].[ratsoft_customers] WHERE [id]= '$id'";
    $stmt = sqlsrv_query($conn, $sql);

    if( $stmt === false ) {
        die( print_r( sqlsrv_errors(), true));
    } 
    else{
        echo "[{\"error\":0,\"result\":\"deleted successfully\"}]";
    }

    
}
else{ 
    echo "[{\"error\":1,\"result\":\"Costumer with id = $id does not exist.\"}]";
}
    

 

sqlsrv_close($conn);

?>