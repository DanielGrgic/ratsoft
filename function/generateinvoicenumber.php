<?php

header("Cache-Control: no-cache");
header("Pragma: no-cache"); 
header("Content-type:application/json");  
include 'connection/connection.php'; 
 
$sql = "SELECT TOP (1) * FROM [rms].[sparksystems].[ratsoft_invoice] ORDER BY [id] DESC";
$stmt = sqlsrv_query( $conn, $sql );
if( $stmt === false) {
    die( print_r( sqlsrv_errors(), true) ); 
}
while( $obj = sqlsrv_fetch_object( $stmt)) {
    $all_invoices[] = $obj;
}

sqlsrv_free_stmt($stmt);
$id = 1;
if($all_invoices[0]->id!=null){
    $id= $all_invoices[0]->id;
}

$invoice_number = substr(date("Y"), -3).date("m").date("d").'/'.time();
echo "{\"error\":0,\"result\":\"Succes\",\"invoice_number\":\"$invoice_number\"}";


// if($all_invoices[0]->invoicenumber==NULL){
    
//     $sql = "UPDATE [rms].[sparksystems].[ratsoft_invoice] SET [invoicenumber]="."'".$invoice_number."'"." WHERE [id]=".$id;

//     $stmt = sqlsrv_query( $conn, $sql);
//     if( $stmt === false ) {
//         die( print_r( sqlsrv_errors(), true));
//     } 
//     else{
//         echo "{\"error\":0,\"result\":\"Succes\"}";
//     }
// }

// else{
//     echo "{\"error\":0,\"result\":\"This Invoice already have invoice number, create new invoice first\"}";
// } 

sqlsrv_close($conn);

?>