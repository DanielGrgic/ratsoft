<?php

header("Cache-Control: no-cache");
header("Pragma: no-cache"); 
header("Content-type:application/json");  
include 'connection/connection.php'; 
 
$check = 0;
$array = $_SESSION['array']; 

$name="";
$firstname="";
$lastname="";
$email="";
$address="";
$phone="";
$country="";
$currency="";

if (isset($_SESSION['array']['firstname'])) {
    // URL parameter exists
    $firstname = $_SESSION['array']['firstname'];
} 
else{
    echo "{\"error\":2,\"result\":\"invalid firstname.\"}\n";
    $check = 1;
}  


if (isset($_SESSION['array']['lastname'])) {
    // URL parameter exists
    $lastname = $_SESSION['array']['lastname'];
} 
else{
    echo "{\"error\":2,\"result\":\"invalid lastname.\"}\n";
    $check = 1;
} 

if (isset($_SESSION['array']['name'])) {
    // URL parameter exists
    $name = $_SESSION['array']['name'];
} 
else{
    echo "{\"error\":2,\"result\":\"invalid name.\"}\n";
    $check = 1;
}  

if (isset($_SESSION['array']['email'])) {
    // URL parameter exists
    $email = $_SESSION['array']['email'];
} 
else{
    echo "{\"error\":2,\"result\":\"invalid email.\"}\n";
    $check = 1;
}  

if (isset($_SESSION['array']['address'])) {
    // URL parameter exists
    $address = $_SESSION['array']['address'];
} 
else{
    echo "{\"error\":2,\"result\":\"invalid address.\"}\n";
    $check = 1;
}  

if (isset($_SESSION['array']['phone'])) {
    // URL parameter exists
    $phone = $_SESSION['array']['phone'];
} 
else{
    echo "{\"error\":2,\"result\":\"invalid phone.\"}\n";
    $check = 1;
}  

if (isset($_SESSION['array']['country'])) {
    // URL parameter exists
    $country = $_SESSION['array']['country'];
} 
else{
    echo "{\"error\":2,\"result\":\"invalid country.\"}\n";
    $check = 1;
}  

if (isset($_SESSION['array']['currency'])) {
    // URL parameter exists
    $currency = $_SESSION['array']['currency'];
} 
else{
    echo "{\"error\":2,\"result\":\"invalid currency.\"}\n";
    $check = 1;
} 

if($check == 1){ // if any of data is missing it will not allow to make user so this if will stop this function 
    sqlsrv_close($conn);
    exit();
}

$logged_userid= $_SESSION['logged_userid'];

$sql = "INSERT INTO [rms].[sparksystems].[ratsoft_customers]  (firstname,lastname, email, createdby, name, address, phone, country, currency) VALUES   
('$firstname','$lastname','$email', '$logged_userid', '$name', '$address', '$phone', '$country', '$currency')";
$stmt = sqlsrv_query($conn,$sql);

if( $stmt === false ) {
    die( print_r( sqlsrv_errors(), true));
} 
else{
    echo "{\"error\":0,\"result\":\"Succes\"}";
} 
 

sqlsrv_close($conn);

?>
