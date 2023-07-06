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

$sql = "SELECT * from [rms].[sparksystems].[ratsoft_invoice] where id = ".$_SESSION['array']['id'];
$stmt = sqlsrv_query( $conn, $sql );
if( $stmt === false) {
    die( print_r( sqlsrv_errors(), true) ); 
}
while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
    $level = $row['id'];
 
} 
sqlsrv_free_stmt($stmt);


if($level!=0){
  $sql = "UPDATE [rms].[sparksystems].[ratsoft_invoice] SET ";

  if (isset($_SESSION['array']['itemname'])) $sql = $sql."[itemname]="."'".$_SESSION['array']['itemname']."'".", ";

  if (isset($_SESSION['array']['itemprice'])) $sql = $sql."[itemprice]="."'".$_SESSION['array']['itemprice']."'".", ";

  if (isset($_SESSION['array']['discount'])) $sql = $sql."[discount]="."'".$_SESSION['array']['discount']."'".", ";

  if (isset($_SESSION['array']['tax'])) $sql = $sql."[tax]="."'".$_SESSION['array']['tax']."'".", ";

  if (isset($_SESSION['array']['description'])) $sql = $sql."[description]="."'".$_SESSION['array']['description']."'".", ";

  if (isset($_SESSION['array']['createdby'])) $sql = $sql."[createdby]="."'".$_SESSION['array']['createdby']."'".", ";

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
  echo "[{\"error\":1,\"result\":\"Invoice with id = $id does not exist.\"}]";
}
  
sqlsrv_close($conn);

?>