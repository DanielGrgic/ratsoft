<?php 

include 'server.php';

// $row_count = sqlsrv_num_rows( $stmt );

$all_invoices = array();

$sql = "SELECT * 
FROM [rms].[sparksystems].[ratsoft_customers]
INNER JOIN [rms].[sparksystems].[ratsoft_invoice] ON [rms].[sparksystems].[ratsoft_customers].id=[rms].[sparksystems].[ratsoft_invoice].customer_id";
$stmt = sqlsrv_query( $conn, $sql);
if( $stmt === false ) {
     die( print_r( sqlsrv_errors(), true));
}

while( $obj = sqlsrv_fetch_object( $stmt)) {
     $all_invoices[] = $obj;
}

// print_r($all_invoices);
  
for ($i=0; $i < count($all_invoices); $i++) { 
    echo "{\"error\":0,\"result\":\"Invoice id: ".$all_invoices[$i]->id."\"} \n";
    echo "{\"error\":0,\"result\":\"Invoice number: ".$all_invoices[$i]->invoicenumber."\"} \n";
    echo "{\"error\":0,\"result\":\"Description: ".$all_invoices[$i]->description."\"} \n";
    echo "{\"error\":0,\"result\":\"Item Name: ".$all_invoices[$i]->itemname."\"} \n";
    echo "{\"error\":0,\"result\":\"Full Name of Costumer: ".$all_invoices[$i]->firstname.' '.$all_invoices[$i]->lastname."\"} \n\n";
} 
sqlsrv_close($conn);



?>