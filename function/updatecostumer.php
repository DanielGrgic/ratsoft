<?php

    header("Cache-Control: no-cache");
    header("Pragma: no-cache"); 
    header("Content-type:application/json");  
    include 'connection/connection.php'; 
 
 
$array = $_SESSION['array']; 
if (isset($_SESSION['array']['id'])) {
    // URL parameter exists
    $id = $_SESSION['array']['id'];
  } else {
    // URL parameter does not exist
    echo "{\"error\":501,\"result\":\"ID is missing\"}";
    sqlsrv_close($conn);
    exit();
  } 

  
$level = 0;

$sql = "SELECT * from [rms].[sparksystems].[ratsoft_customers] where id = ".$_SESSION['array']['id'];
$stmt = sqlsrv_query( $conn, $sql );
if( $stmt === false) {
    die( print_r( sqlsrv_errors(), true) ); 
}
while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
  $level = $row['id'];

} 

sqlsrv_free_stmt($stmt);


if($level!=0){
  $sql = "UPDATE [rms].[sparksystems].[ratsoft_customers] SET ";

  if (isset($_SESSION['array']['name'])) $sql = $sql."[name]="."'".$_SESSION['array']['name']."'".", ";

  if (isset($_SESSION['array']['firstname'])) $sql = $sql."[firstname]="."'".$_SESSION['array']['firstname']."'".", ";

  if (isset($_SESSION['array']['lastname'])) $sql = $sql."[lastname]="."'".$_SESSION['array']['lastname']."'".", ";

  if (isset($_SESSION['array']['email'])) $sql = $sql."[email]="."'".$_SESSION['array']['email']."'".", ";

  if (isset($_SESSION['array']['address'])) $sql = $sql."[address]="."'".$_SESSION['array']['address']."'".", ";

  if (isset($_SESSION['array']['phone'])) $sql = $sql."[phone]="."'".$_SESSION['array']['phone']."'".", ";

  if (isset($_SESSION['array']['country'])) $sql = $sql."[country]="."'".$_SESSION['array']['country']."'".", ";

  if (isset($_SESSION['array']['currency'])) $sql = $sql."[currency]="."'".$_SESSION['array']['currency']."'".", ";

  if (isset($_SESSION['array']['amount'])) $sql = $sql."[amount]="."'".$_SESSION['array']['amount']."'".", ";

  if (isset($_SESSION['array']['tax'])) $sql = $sql."[tax]="."'".$_SESSION['array']['tax']."'".", ";

  if (isset($_SESSION['array']['ispaid'])) $sql = $sql."[ispaid]="."'".$_SESSION['array']['ispaid']."'".", ";

  $sql=rtrim($sql, ", ");

  $sql = $sql."WHERE [id]=".$_SESSION['array']['id'];

  $stmt = sqlsrv_query( $conn, $sql);
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