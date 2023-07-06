<?php

header("Cache-Control: no-cache");
header("Pragma: no-cache"); 
header("Content-type:application/json");  
include 'connection/connection.php'; 
 
$invoicenumber = 0;
if (!isset($_REQUEST['invoicenumber'])) {
    echo "{\"error\":2,\"result\":\"invalid invoice number.\"}";
    sqlsrv_close($conn);
    exit();
} 
$invoicenumber = $_REQUEST['invoicenumber'];

$level = 0;

$sql = "SELECT * from [rms].[sparksystems].[ratsoft_invoice] where [invoicenumber] = '$invoicenumber'";
$stmt = sqlsrv_query( $conn, $sql );
if( $stmt === false) {
    die( print_r( sqlsrv_errors(), true) ); 
}
while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
    $level = $row['id'];
 
} 
sqlsrv_free_stmt($stmt);

if($level!=0){
    $sql = "DELETE FROM [rms].[sparksystems].[ratsoft_invoice] WHERE [invoicenumber]= '$invoicenumber'";
    $stmt = sqlsrv_query($conn, $sql);

    if( $stmt === false ) {
        die( print_r( sqlsrv_errors(), true));
    } 
    else{
        echo "[{\"error\":0,\"result\":\"deleted successfully\"}]";
    }

    
}
else{ 
    echo "[{\"error\":1,\"result\":\"Invoice with invoice number = $invoicenumber does not exist.\"}]";
}
    

 

sqlsrv_close($conn);

?>