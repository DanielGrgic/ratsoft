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

$sql = "SELECT * from [rms].[sparksystems].[ratsoft_users] where id = ".$_SESSION['array']['id'];
$stmt = sqlsrv_query( $conn, $sql );
if( $stmt === false) {
    die( print_r( sqlsrv_errors(), true) ); 
}
while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
    $level = $row['id'];
 
} 
sqlsrv_free_stmt($stmt);


if($level!=0){
  $sql = "UPDATE [rms].[sparksystems].[ratsoft_users] SET ";

  if (isset($_SESSION['array']['username'])) $sql = $sql."[username]="."'".$_SESSION['array']['username']."'".", ";

  if (isset($_SESSION['array']['email'])) $sql = $sql."[email]="."'".$_SESSION['array']['email']."'".", ";

  if (isset($_SESSION['array']['password'])) $sql = $sql."[password]="."'".$_SESSION['array']['password']."'".", ";

  if (isset($_SESSION['array']['active'])) $sql = $sql."[active]="."'".$_SESSION['array']['active']."'".", ";

  if (isset($_SESSION['array']['lastloginip'])) $sql = $sql."[lastloginip]="."'".$_SESSION['array']['lastloginip']."'".", ";

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
  echo "[{\"error\":1,\"result\":\"User with id = $id does not exist.\"}]";
}
  
sqlsrv_close($conn);

?>