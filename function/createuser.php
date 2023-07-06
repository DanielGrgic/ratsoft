<?php

header("Cache-Control: no-cache");
header("Pragma: no-cache"); 
header("Content-type:application/json");  
include 'connection/connection.php'; 
 
$check = 0;
$array = $_SESSION['array']; 

$username="";
$email="";
$password="";

if (isset($_SESSION['array']['username'])) {
    // URL parameter exists
    $username = $_SESSION['array']['username'];
} 
else{
    echo "{\"error\":2,\"result\":\"invalid username.\"}";
    $check = 1;
}  


if (isset($_SESSION['array']['email'])) {
    // URL parameter exists
    $email = $_SESSION['array']['email'];
} 
else{
    echo "{\"error\":2,\"result\":\"invalid email.\"}";
    $check = 1;
} 

if (isset($_SESSION['array']['password'])) {
    // URL parameter exists
    $password = $_SESSION['array']['password'];
} 
else{
    echo "{\"error\":2,\"result\":\"invalid password.\"}";
    $check = 1;
} 

if($check == 1){ // if any of data is missing it will not allow to make user so this if will stop this function 
    sqlsrv_close($conn);
    exit();
}

$sql = "SELECT* FROM [rms].[sparksystems].[ratsoft_users] WHERE [email]= '$email'";
$stmt = sqlsrv_query( $conn, $sql);
if( $stmt === false ) {
    die( print_r( sqlsrv_errors(), true));
} 


$obj = sqlsrv_fetch_object($stmt);
if($obj!=null){
    echo "{\"error\":404,\"result\":\"User with this email does not exist.\"}";
    sqlsrv_close($conn);
    exit();
}
$sql = "INSERT INTO [rms].[sparksystems].[ratsoft_users]  (username,email, password) VALUES   
('$username','$email','$password')";
$stmt = sqlsrv_query($conn,$sql);

if( $stmt === false ) {
    die( print_r( sqlsrv_errors(), true));
} 
else{
    echo "{\"error\":0,\"result\":\"Succes\"}";
}


  
 

sqlsrv_close($conn);

?>