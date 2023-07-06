<?php 

include 'server.php'; 

$all_costumers = array();

$sql = "SELECT TOP (1000) [id], [name], [firstname], [lastname], [email], [address], [phone], [country], [currency], [amount], [tax], [ispaid], [updatedate], [createdby], [createdate] FROM [rms].[sparksystems].[ratsoft_customers]";
$stmt = sqlsrv_query( $conn, $sql);
if( $stmt === false ) {
     die( print_r( sqlsrv_errors(), true));
}

while( $obj = sqlsrv_fetch_object( $stmt)) {
     $all_costumers[] = $obj;
}

print_r($all_costumers);
sqlsrv_close($conn);
?>
