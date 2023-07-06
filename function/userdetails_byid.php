<?php 
// header('Content-Type: text/xml');
header("Cache-Control: no-cache");
header("Pragma: no-cache"); 
header("Content-type:application/json");  

include 'connection/connection.php'; 
 
 
$id = $_SESSION['user_id'];
$user_type = $_SESSION['usertype'];
 
 

if($user_type == "costumer"){
    $sql = "SELECT [id], [name], [firstname], [lastname], [email], [address], [phone], [country], [currency], [amount], [tax], [ispaid], [updatedate], [createdby], [createdate] FROM [rms].[sparksystems].[ratsoft_customers] WHERE [id]= '$id'";
    $stmt = sqlsrv_query( $conn, $sql);
    if( $stmt === false ) {
        die( print_r( sqlsrv_errors(), true));
    } 


    $obj = sqlsrv_fetch_object($stmt);
}
elseif ($user_type == "user") {
    $sql = "SELECT [id], [username], [email], [password], [active], [updatedate], [lastloginip] FROM [rms].[sparksystems].[ratsoft_users] WHERE [id]= '$id'";
    $stmt = sqlsrv_query( $conn, $sql);
    if( $stmt === false ) {
        die( print_r( sqlsrv_errors(), true));
    } 


    $obj = sqlsrv_fetch_object($stmt);
}
else{
    $obj = NULL;
    echo "{\"error\":1,\"result\":\"wrong user type\"}";
    sqlsrv_close($conn);
    exit();
}



if($obj == null){
    echo "{\"error\":1,\"result\":\"$user_type with id: $id does not exist!\"}";
}
else{
    if($user_type == "user"){
        $date = $obj->updatedate; 
        
        $time=$date->format("D, d M Y H:i:s O"); 

echo "{
    \"error\":0,
    \"result\":\"Succes\",
    \"id\":$obj->id,
    \"userType\":\"$user_type\",
    \"username\":\"$obj->username\",
    \"email\":\"$obj->email\",
    \"password\":\"$obj->password\",
    \"active\":\"$obj->active\",
    \"updatedate\":\"$time\",
    \"lastloginip\":\"$obj->lastloginip\"
}";

            
    }

    if($user_type == "costumer"){
        $date = $obj->updatedate; 
        
        $time=$date->format("D, d M Y H:i:s O"); 

        $date_2 = $obj->createdate; 
        
        $time_2=$date->format("D, d M Y H:i:s O"); 
        
echo "{
    \"error\":0,
    \"result\":\"Succes\",
    \"id\":\"'$obj->id'\",
    \"userType\":\"$user_type\",
    \"name\":\"$obj->name\",
    \"firstname\":\"$obj->firstname\",
    \"lastname\":\"$obj->lastname\",
    \"email\":\"$obj->email\",
    \"address\":\"$obj->address\",
    \"phone\":\"$obj->phone\",
    \"country\":\"$obj->country\",
    \"currency\":\"$obj->currency\",
    \"amount\":\"$obj->amount\",
    \"tax\":\"$obj->tax\",
    \"ispaid\":\"$obj->ispaid\",
    \"updatedate\":\"$time\",
    \"createdby\":\"$obj->createdby\",
    \"createdate\":\"$time_2\"
}";

    }

}


sqlsrv_close($conn);




?>