<?php
error_reporting(E_ALL);
header("Cache-Control: no-cache");
header("Pragma: no-cache"); 
header("Content-type:application/json"); 

  
 
// session_set_cookie_params(0);
if(session_status() == 1) {
    session_start(); 
}  
else{
    session_destroy();
    session_start(); 
}

check();

$function = "";
if(isset($_REQUEST['cmd'])){   
    $function = $_REQUEST['cmd'];
} 

check_login();


switch ($function) {
    case 'login':
        //how to call this case :  http://localhost/php-test-project/service.php?cmd=login&user=admin&pass=ratsoft@123
        login();

        break;
    case 'userdetails_byid':
        //how to call this case :  http://localhost/php-test-project/service.php?cmd=userdetails_byid&id=1&user_type=user
        userDetails_byId();
        break;
    case 'logout':
        //how to call this case: http://localhost/php-test-project/service.php?cmd=logout
        setcookie("user_id", $_SESSION['logged_userid'], time() - 3600);
        session_destroy();
        
        echo "{\"error\":1,\"result\":\"Logged out.\"}"; 
        break;
    
    case 'updateuser':
        //how to call this case: http://localhost/php-test-project/service.php?cmd=updateuser&username=daniel...
        //Thing here is that you must add new variable to link you want to change
        //You can change only data for logged user, so if you are logged in then only for your self
        
        updateUser();
        break; 

    case 'createuser':
        //how to call this case: http://localhost/php-test-project/service.php?cmd=createuser&username=daniel&email=daniel@gmail.com&password=Daniel123456
        createUser();
        break;

    case 'deleteuser':
        //how to call this case: http://localhost/php-test-project/service.php?cmd=deleteuser&id=2
        deleteUser();
        break; 
        
    case 'createcostumer':
        //how to call this case: http://localhost/php-test-project/service.php?cmd=createcostumer&firstname=danie&lastname=grgic&email=daniel@gmail.com
        createCostumer();
        break;
    
    case 'deletecostumer':
        //how to call this case: http://localhost/php-test-project/service.php?cmd=deletecostumer&id=1
        deleteCostumer();
        break; 

    case 'updatecostumer':
        //how to call this case: http://localhost/php-test-project/service.php?cmd=updatecostumer&username=daniel...
        //Thing here is that you must add new variable to link you want to change
        //You can change only for costumer you choose with id
        
        updateCostumer();
        break;

    // case 'createinvoice':
    //     //how to call this case: http://localhost/php-test-project/service.php?cmd=createinvoice&id=1
    //     //id represent costumer id so please push us costumer id and based on that we will know this invoice is for that costumer
    //     createInvoice();
    //     break;

    case 'getallinvoices':
        //how to call this case: http://localhost/php-test-project/service.php?cmd=getallinvoices
        getAllInvoices();
        break;

    case 'getinvoice_byinvoicenumber':
        //how to call this case: http://localhost/php-test-project/service.php?cmd=getallinvoices
        getInvoiceByInvoiceNumber();
        break;

    case 'generateinvoicenumber':
        //how to call this case: http://localhost/php-test-project/service.php?cmd=generateinvoicenumber
        // generateInvoiceNumber();
        $invoice_number = substr(date("Y"), -3).date("m").date("d").'/'.time();
        echo "{\"error\":0,\"result\":\"Succes\",\"invoice_number\":\"$invoice_number\"}";
        $_SESSION['invoicenumber'] = $invoice_number;
        break;

    case 'getinvoice_byid':
        //how to call this case: http://localhost/php-test-project/service.php?cmd=getinvoice_byid&id=1
        getInvoiceById();
        break;

    case 'deleteinvoice':
        //how to call this case: http://localhost/php-test-project/service.php?cmd=deleteinvoice&id=1
        deleteInvoice();
        break;

    case 'deleteinvoice_byinvoicenumber':
        //how to call this case: http://localhost/php-test-project/service.php?cmd=deleteinvoice_byinvoicenumber&invoicenumber=0230620/1687256538
        deleteInvoice_byInvoiceNumber();
        break;

    case 'updateinvoice':
        //how to call this case: http://localhost/php-test-project/service.php?cmd=updateinvoice&itemname=something...
        //Thing here is that you must add new variable to link you want to change
        //you can change only invoice you choose by id
        updateInvoice();
        break;

    case 'createitem':
        //how to call this case: http://localhost/php-test-project/service.php?cmd=createitem&invoicenumber=0230523/1684867160
        createItem();
        break;

    case 'check':
        // setcookie('Admin', 'Admin', time() + (86400 * 30), "/");    
        print_r($_SESSION); 
        break;

    case 'invoiceprint':
        //how to call this case: http://localhost/php-test-project/service.php?cmd=invoiceprint&invoicenum=0230523/1684867160
        header("Location: function/invoiceprint.php?invoicenum=".$_REQUEST['invoicenum'], true, 301);
        // include 'function/invoiceprint.php';
        break;
    
    default:
        echo "{\"error\":1,\"result\":\"no function is selected\"}";
    
}

function check(){
    
    $server_ip = gethostbyname($_SERVER['SERVER_NAME']);
    
    // if($server_ip!='184.154.5.218'){
    //     echo "{\"error\":1,\"result\":\"error, IP address is not right\"}";
        
    //     exit();
    // }

    $invoice_number = 1;
 

    if($_REQUEST == null){ //this if check if query is empty
        echo "{\"error\":1,\"result\":\"error 501\"}";
         
        exit();
    } 

    $url = $url = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
    $values = parse_url($url);
    $query = explode('&',$values['query']);
    $num = count($query);
    $array = [];
    for ($i=0; $i < $num; $i++) { 
        $split = explode('=',$query[$i]); 
        array_push($array,$split[0]);
    } 
    $possible_query = ['cmd', 'username', 'password', 'email', 'id', 'user_type', 'active', 'lastloginip', 'firstname', 'lastname', 'name', 'address', 'phone', 'country', 'currency', 'amount', 'tax', 'ispaid', 'customer_id', 'itemname', 'itemprice', 'discount', 'invoicenumber', 'description', 'invoicenum', 'createdby'];

    $bFound = (count(array_intersect($array, $possible_query)));
    //This if check if there is some query that does not belongs there
    //for example you can only put cmd, username and password and if you put for example any other then you will get error and not be possible to continue
    if($num!=$bFound){
        echo "{\"error\":1,\"result\":\"Query You sent is not right!\"}";
        
        exit();
    }

    if (isset($_REQUEST['username'])){
        if (strlen($_REQUEST['username'])>50){
            echo "{\"error\":1,\"result\":\"invalid username\"}";
            
            exit();
        }
    }
    if (isset($_REQUEST['password'])){
        if (strlen($_REQUEST['password'])>50){
            echo "{\"error\":1,\"result\":\"invalid password\"}";
            
            exit();
        }
    }
    if (isset($_REQUEST['email'])){
        if (strlen($_REQUEST['email'])>50){
            echo "{\"error\":1,\"result\":\"invalid email\"}";
            
            exit();
        }
    }
    if (isset($_REQUEST['id'])){
        if (strlen((string)$_REQUEST['id'])>8){
            echo "{\"error\":1,\"result\":\"invalid id\"}";
            
            exit();
        }
        elseif ( !is_numeric($_REQUEST['id']) ) {
            $check_id= $_REQUEST['id'];
            echo "{\"error\":1,\"result\":\"$check_id is not a number\"}";
            
            exit();
        }
    }
     
    if (isset($_REQUEST['user_type'])){
        if (strlen($_REQUEST['user_type'])>50){
            echo "{\"error\":1,\"result\":\"invalid user_type\"}";
            
            exit();
        }
    }
    if (isset($_REQUEST['active'])){
        if (strlen($_REQUEST['active'])>50){
            echo "{\"error\":1,\"result\":\"invalid active\"}";
            
            exit();
        }
    }
    if (isset($_REQUEST['lastloginip'])){
        if (strlen($_REQUEST['lastloginip'])>50){
            echo "{\"error\":1,\"result\":\"invalid lastloginip\"}";
            
            exit();
        }
    }
    if (isset($_REQUEST['firstname'])){
        if (strlen($_REQUEST['firstname'])>50){
            echo "{\"error\":1,\"result\":\"invalid firstname\"}";
            
            exit();
        }
    }
    if (isset($_REQUEST['lastname'])){
        if (strlen($_REQUEST['lastname'])>50){
            echo "{\"error\":1,\"result\":\"invalid lastname\"}";
            
            exit();
        }
    }
    if (isset($_REQUEST['name'])){
        if (strlen($_REQUEST['name'])>50){
            echo "{\"error\":1,\"result\":\"invalid name\"}";
            
            exit();
        }
    }
    if (isset($_REQUEST['address'])){
        if (strlen($_REQUEST['address'])>50){
            echo "{\"error\":1,\"result\":\"invalid address\"}";
            
            exit();
        }
    }
    if (isset($_REQUEST['phone'])){
        if (strlen($_REQUEST['phone'])>50){
            echo "{\"error\":1,\"result\":\"invalid phone\"}";
            
            exit();
        }
    }
    if (isset($_REQUEST['country'])){
        if (strlen($_REQUEST['country'])>50){
            echo "{\"error\":1,\"result\":\"invalid country\"}";
            
            exit();
        }
    }
    if (isset($_REQUEST['currency'])){
        if (strlen($_REQUEST['currency'])>50){
            echo "{\"error\":1,\"result\":\"invalid currency\"}";
            
            exit();
        }
    }
    if (isset($_REQUEST['amount'])){
        if (strlen((string)$_REQUEST['amount'])>20){
            echo "{\"error\":1,\"result\":\"invalid amount\"}";
            
            exit();
        } 
        elseif ( !is_numeric($_REQUEST['amount']) ) {
            $check_amount= $_REQUEST['amount'];
            echo "{\"error\":1,\"result\":\"$check_amount is not a number\"}";
            
            exit();
        }
    }
    if (isset($_REQUEST['tax'])){
        if (strlen((string)$_REQUEST['tax'])>20){
            echo "{\"error\":1,\"result\":\"invalid tax\"}";
            
            exit();
        } 
        elseif ( !is_numeric($_REQUEST['tax']) ) {
            $check_tax= $_REQUEST['tax'];
            echo "{\"error\":1,\"result\":\"$check_tax is not a number\"}";
            
            exit();
        }
    }
    if (isset($_REQUEST['ispaid'])){
        if (strlen((string)$_REQUEST['ispaid'])>20){
            echo "{\"error\":1,\"result\":\"invalid ispaid\"}";
            
            exit();
        } 
        elseif ( !is_numeric($_REQUEST['ispaid']) ) {
            $check_ispaid= $_REQUEST['ispaid'];
            echo "{\"error\":1,\"result\":\"$check_ispaid is not a number\"}";
            
            exit();
        }
    }

    if (isset($_REQUEST['customer_id'])){
        if (strlen((string)$_REQUEST['customer_id'])>20){
            echo "{\"error\":1,\"result\":\"invalid customer_id\"}";
            
            exit();
        } 
        elseif ( !is_numeric($_REQUEST['customer_id']) ) {
            $customer_id= $_REQUEST['customer_id'];
            echo "{\"error\":1,\"result\":\"$customer_id is not a number\"}";
            
            exit();
        }
    }
    if (isset($_REQUEST['itemname'])){
        if (strlen($_REQUEST['itemname'])>50){
            echo "{\"error\":1,\"result\":\"invalid itemname\"}";
            
            exit();
        }
    }
    if (isset($_REQUEST['itemprice'])){
        if (strlen((string)$_REQUEST['itemprice'])>20){
            echo "{\"error\":1,\"result\":\"invalid itemprice\"}";
            
            exit();
        } 
        elseif ( !is_numeric($_REQUEST['itemprice']) ) {
            $itemprice= $_REQUEST['itemprice'];
            echo "{\"error\":1,\"result\":\"$itemprice is not a number\"}";
            
            exit();
        }
    }
    if (isset($_REQUEST['discount'])){
        if (strlen((string)$_REQUEST['discount'])>20){
            echo "{\"error\":1,\"result\":\"invalid discount\"}";
            
            exit();
        } 
        elseif ( !is_numeric($_REQUEST['discount']) ) {
            $discount= $_REQUEST['discount'];
            echo "{\"error\":1,\"result\":\"$discount is not a number\"}";
            
            exit();
        }
    }
    if (isset($_REQUEST['invoicenumber'])){
        if (strlen($_REQUEST['invoicenumber'])>50){
            echo "{\"error\":1,\"result\":\"invalid invoicenumber\"}";
            
            exit();
        }
    }
    if (isset($_REQUEST['invoicenum'])){
        if (strlen($_REQUEST['invoicenum'])>50){
            echo "{\"error\":1,\"result\":\"invalid invoicenum\"}";
            
            exit();
        }
    }
    if (isset($_REQUEST['description'])){
        if (strlen($_REQUEST['description'])>50){
            echo "{\"error\":1,\"result\":\"invalid description\"}";
            
            exit();
        }
    }
    if (isset($_REQUEST['createdby'])){
        if (strlen((string)$_REQUEST['createdby'])>20){
            echo "{\"error\":1,\"result\":\"invalid createdby\"}";
            
            exit();
        } 
        elseif ( !is_numeric($_REQUEST['createdby']) ) {
            $createdby= $_REQUEST['createdby'];
            echo "{\"error\":1,\"result\":\"$createdby is not a number\"}";
            
            exit();
        }
    }
    
}


function check_login(){
    
    
    if (!isset($_SESSION['logged_in']) && $_REQUEST['cmd']!='login'){
        if($_REQUEST['cmd']=='createuser'){
            $function = $_REQUEST['cmd'];
        }
        else{
            echo "{\"error\":1,\"result\":\"Please login first before start using our service.\"}";
            
            exit();
        }
    } 

}

//Function for login
function login(){
    

    $check_l = 0;
    //check does variable user exist in link 
    if(isset($_REQUEST['username'])) {
        $username = $_REQUEST['username'];
    }
    else{
        $username = "";
        $check_l = 1;
    } 
    if(isset($_REQUEST['password'])) {
        $password = $_REQUEST['password'];
    }
    else{
        $password = "";
        $check_l = 1;
    }  
    //check what is required
    if($username == ""){
        echo "{\"error\":1,\"result\":\"username required.\"} \n";
    }
    if ($password == ""){
        echo "{\"error\":1,\"result\":\"password required.\"}";
    } 

    if($check_l == 1){
        
        exit();
    }

    //if all is oke then he will be able to login
        
        
        
        $_SESSION['username'] = $username; 
        $_SESSION['password'] = $password;  
        include 'function/login.php';
    

    unset($_SESSION['username']);
    unset($_SESSION['password']);  
    

}


//Function to show User details
function userDetails_byId(){
    if(isset($_REQUEST['user_type'])) {
        $user_type = $_REQUEST['user_type'];
    }
    else{
        $user_type = "";
        echo "User Type is required!";
        
        exit();
    }
    if(isset($_REQUEST['id'])) {
        $id = $_REQUEST['id'];
    }
    else{
        $id = "";
    } 

    if($id == ""){
        echo "{\"error\":1,\"result\":\"id required.\"}";
    }
    elseif (strlen($id)>10) { //Number of symbols can not be more then 50
        echo "{\"error\":1,\"result\":\"id is to long.\"}";
    }
    else{
        $id = intval($id); //we had first to convert string to integer so we could call data from database by id, because id in database have type integer. if we send number it can cause problem
        $_SESSION['user_id'] = $id;
        $_SESSION['usertype'] = $user_type;
        include 'function/userdetails_byid.php';
    }
     
    unset($_SESSION['user_id']);
    unset($_SESSION['usertype']); 

}

function updateUser(){  
   
    $_SESSION['array'] = $_REQUEST;  

    include 'function/updateuser.php';


    unset($_SESSION['array']);

}

function createUser(){
    $_SESSION['array'] = $_REQUEST;

    include 'function/createuser.php';

    unset($_SESSION['array']);
}


function createCostumer(){
    $_SESSION['array'] = $_REQUEST;

    include 'function/createcostumer.php';

    unset($_SESSION['array']);
}

function deleteUser(){
    $_SESSION['delete_id'] = $_REQUEST['id'];

    include 'function/deleteuser.php';

    unset($_SESSION['delete_id']);
}

function deleteCostumer(){
    $_SESSION['delete_id'] = $_REQUEST['id'];

    include 'function/deletecostumer.php';

    unset($_SESSION['delete_id']);
}


function updateCostumer(){  
   
    $_SESSION['array'] = $_REQUEST;  

    include 'function/updatecostumer.php';


    unset($_SESSION['array']);

}

function createInvoice(){

    $_SESSION['costumer_id_invoice'] = $_REQUEST['id'];  

    include 'function/createinvoice.php';


    unset($_SESSION['costumer_id_invoice']);
}

function generateInvoiceNumber(){


    include 'function/generateinvoicenumber.php';

 
}

function getAllInvoices(){  

    include 'function/getallinvoices.php';

}

function getInvoiceById(){

    $_SESSION['invoice_id'] = $_REQUEST['id'];  

    include 'function/getinvoicebyid.php';


    unset($_SESSION['invoice_id']);
}

function deleteInvoice(){

    $_SESSION['delete_id'] = $_REQUEST['id'];

    include 'function/deleteinvoice.php';

    unset($_SESSION['delete_id']);

}

function updateInvoice(){


    $_SESSION['array'] = $_REQUEST;  

    
    include 'function/updateinvoice.php';


    unset($_SESSION['array']);

}

function createItem(){
 

    include 'function/createitem.php';

    // unset($_SESSION['invoicenumber']);

}

function getInvoiceByInvoiceNumber(){

    include 'function/getinvoice_byinvoicenumber.php';

}

function deleteInvoice_byInvoiceNumber(){

    include 'function/deleteinvoicebyinvoicenumber.php';

}

?>