<?php 
header("Cache-Control: no-cache");
header("Pragma: no-cache"); 
header("Content-type:application/json"); 

include 'server.php';

// $row_count = sqlsrv_num_rows( $stmt );

$all_invoices = array();

if (isset($_REQUEST['invoicenumber'])){
    $invoicenumber=$_REQUEST['invoicenumber'];
}
else{
    sqlsrv_close($conn);
    echo "{\"error\":1,\"result\":\"Invoice number is missing\"}";
    exit();
}



$sql = "SELECT [id], [customer_id], [itemname], [itemprice], [discount], [tax], [createdby], [description]
FROM [rms].[sparksystems].[ratsoft_invoice] WHERE [rms].[sparksystems].[ratsoft_invoice].invoicenumber ='$invoicenumber'"; 
$stmt = sqlsrv_query( $conn, $sql);
if( $stmt === false ) {
     die( print_r( sqlsrv_errors(), true));
}

if(sqlsrv_fetch_object($stmt)==null){
    sqlsrv_close($conn);
    echo "{\"error\":1,\"result\":\"Invoce with invoicenumber $invoicenumber does not exist.\"}";
    exit();
}

sqlsrv_free_stmt($stmt);
$stmt = sqlsrv_query( $conn, $sql);
if( $stmt === false ) {
     die( print_r( sqlsrv_errors(), true));
}

$obj_json = '';
echo "{\n\t".'"invoices":['."\n";  
while( $obj = sqlsrv_fetch_object( $stmt)) {  
    $obj_json = $obj_json."\n\t\t".json_encode($obj).',';
}

$obj_json=rtrim($obj_json, ",");
print_r($obj_json."");
 
echo "\n\n\t]\n}";

sqlsrv_close($conn);



?>