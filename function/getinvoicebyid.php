<?php 
// header('Content-Type: text/xml');
header("Cache-Control: no-cache");
header("Pragma: no-cache"); 
header("Content-type:application/json");  

include 'connection/connection.php'; 
 
 
$id = $_SESSION['invoice_id'];
 
 
$sql = "SELECT * 
FROM [rms].[sparksystems].[ratsoft_customers]
INNER JOIN [rms].[sparksystems].[ratsoft_invoice] ON [rms].[sparksystems].[ratsoft_customers].id=[rms].[sparksystems].[ratsoft_invoice].customer_id WHERE [rms].[sparksystems].[ratsoft_invoice].[id]= '$id'";
// $sql = "SELECT * FROM [rms].[sparksystems].[ratsoft_invoice] WHERE [id]= '$id'";
// SELECT Customer.Customer, Customer.State, Entry.Entry
//    FROM Customer
//    LEFT JOIN Entry
//    ON Customer.Customer=Entry.Customer
//    WHERE Entry.Category='D'
$stmt = sqlsrv_query( $conn, $sql);
if( $stmt === false ) {
    die( print_r( sqlsrv_errors(), true));
} 


$obj = sqlsrv_fetch_object($stmt);




if($obj == null){
    echo "{\"error\":1,\"result\":\"Invoice with id: $id does not exist!\"}";
}
else{

        $date = $obj->updatedate; 
        
        $time=$date->format("D, d M Y H:i:s O"); 

        $date_2 = $obj->createdate; 
        
        $time_2=$date_2->format("D, d M Y H:i:s O"); 
       
        
echo "{
    \"error\":0,
    \"result\":\"Succes\",
    \"id\":\"'$obj->id'\",
    \"customer_id\":\"$obj->customer_id\",
    \"itemname\":\"$obj->itemname\",
    \"itemprice\":\"$obj->itemprice\",
    \"discount\":\"$obj->discount\",
    \"tax\":\"$obj->tax\",
    \"invoicenumber\":\"$obj->invoicenumber\",
    \"firstname\":\"$obj->firstname\",
    \"lastname\":\"$obj->lastname\",
    \"description\":\"$obj->description\",
    \"createdby\":\"$obj->createdby\",
    \"createdate\":\"$time_2\",
    \"updatedate\":\"$time\"
}";

    }


sqlsrv_close($conn);




?>