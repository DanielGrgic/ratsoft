<?php

header("Cache-Control: no-cache");
header("Pragma: no-cache"); 
header("Content-type:application/json");  
include 'connection/connection.php'; 
$check = 0;
$invoicenumber = 1; 
$itemprice = 0;
$tax = 0;
$discount=0;
$description=0;
$customer_id = 0;
$itemname = 0;

if (isset($_SESSION['invoicenumber'])) {
    // URL parameter exists
    $invoicenumber = $_SESSION['invoicenumber'];
} 

if($invoicenumber==1){
    echo "{\"error\":1,\"result\":\"Please generate first invoice number\"}";
    sqlsrv_close($conn);
    exit();
}


if (isset($_REQUEST['itemprice'])) {
    // URL parameter exists
    $itemprice = $_REQUEST['itemprice'];
} 
else{
    echo "{\"error\":2,\"result\":\"invalid itemprice.\"}";
    $check = 1;
}  


if (isset($_REQUEST['tax'])) {
    // URL parameter exists
    $tax = $_REQUEST['tax'];
} 
else{
    echo "{\"error\":2,\"result\":\"invalid tax.\"}";
    $check = 1;
}  

if (isset($_REQUEST['discount'])) {
    // URL parameter exists
    $discount = $_REQUEST['discount'];
} 
else{
    echo "{\"error\":2,\"result\":\"invalid discount.\"}";
    $check = 1;
} 

if (isset($_REQUEST['customer_id'])) {
    // URL parameter exists
    $customer_id = $_REQUEST['customer_id'];
} 
else{
    echo "{\"error\":2,\"result\":\"invalid customer_id.\"}";
    $check = 1;
} 

if (isset($_REQUEST['description'])) {
    // URL parameter exists
    $description = $_REQUEST['description'];
} 

if (isset($_REQUEST['itemname'])) {
    // URL parameter exists
    $itemname = $_REQUEST['itemname'];
} 
 

if($check==1){
    sqlsrv_close($conn);
    exit();
}


$logged_userid= $_SESSION['logged_userid'];
 
$updatedate = date('Y-m-d H:i:s');

$sql = "INSERT INTO [rms].[sparksystems].[ratsoft_invoice]  (customer_id, createdby, updatedate, itemname, itemprice, discount, tax, invoicenumber, description) VALUES   
('$customer_id','$logged_userid', '$updatedate', '$itemname', '$itemprice', '$discount', '$tax', '$invoicenumber', '$description')";
$stmt = sqlsrv_query($conn,$sql);

if( $stmt === false ) {
    die( print_r( sqlsrv_errors(), true));
} 
else{
    echo "{\"error\":0,\"result\":\"Succes\"}";
}
  
 

sqlsrv_close($conn);

?>