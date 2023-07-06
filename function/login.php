<?php 
// header('Content-Type: text/xml');
header("Cache-Control: no-cache");
header("Pragma: no-cache"); 
header("Content-type:application/json"); 
header('Content-Type:text/xml');
include 'connection/connection.php'; 
 
 
$username = $_SESSION['username'];
$password = $_SESSION['password'];
 


$sql = "SELECT [id], [username], [email], [password], [active], [updatedate], [lastloginip] FROM [rms].[sparksystems].[ratsoft_users] WHERE [username]= '$username' AND [password]= '$password'";
$stmt = sqlsrv_query( $conn, $sql);
if( $stmt === false ) {
     die( print_r( sqlsrv_errors(), true));
} 


$obj = sqlsrv_fetch_object($stmt);
 
    
    if($obj != null){

        
        
            
        //XML file creating

        $dom = new DOMDocument();

        $dom->encoding = 'utf-8';

        $dom->xmlVersion = '1.0';

        $dom->formatOutput = true;


        $xml_file_name = 'user.xml';

        $root = $dom->createElement('xml');

        $user_node = $dom->createElement('user');

            $child_node_error = $dom->createElement('error', 0);

                $user_node->appendChild($child_node_error);

            $child_node_result = $dom->createElement('result', 'success');

                $user_node->appendChild($child_node_result);

            $child_node_id = $dom->createElement('id', $obj->id);

                $user_node->appendChild($child_node_id);

            $child_node_username = $dom->createElement('username', $obj->username);

                $user_node->appendChild($child_node_username);

            $child_node_email = $dom->createElement('email', $obj->email);

                $user_node->appendChild($child_node_email);

            $child_node_password = $dom->createElement('password', $obj->password);

                $user_node->appendChild($child_node_password);

            $child_node_active = $dom->createElement('active', $obj->active);

                $user_node->appendChild($child_node_active);

            $date = $obj->updatedate; 
            
            $time=$date->format("D, d M Y H:i:s O"); 

            $child_node_updatedate = $dom->createElement('updatedate', $time);

                $user_node->appendChild($child_node_updatedate);
            
            $child_node_lastloginip = $dom->createElement('lastloginip', $obj->lastloginip);

                $user_node->appendChild($child_node_lastloginip);

            $root->appendChild($user_node);

        $dom->appendChild($root);

        $dom->save($xml_file_name); 

        $file = file_get_contents("user.xml");
        if(session_status() == 1) {
            session_start(); 
        }  
        else{
            session_destroy();
            session_start(); 
        }
        
        setcookie("userid", $obj->id, time()+30*24*60*60);
        
        $_SESSION['cookie'] = $_COOKIE;
        $_SESSION['logged_userid'] = $obj->id;
        $_SESSION['logged_in'] = 1;

        echo $file;
    }
    else{
        
        //XML file creating

        $dom = new DOMDocument();

        $dom->encoding = 'utf-8';

        $dom->xmlVersion = '1.0';

        $dom->formatOutput = true;


        $xml_file_name = 'user.xml';

        $root = $dom->createElement('xml');

        $user_node = $dom->createElement('user');

            $child_node_error = $dom->createElement('error', 101);

                $user_node->appendChild($child_node_error);

            $child_node_result = $dom->createElement('result', 'username or password not correct!');

                $user_node->appendChild($child_node_result);

            $root->appendChild($user_node);

        $dom->appendChild($root);

        $dom->save($xml_file_name);

        $file = file_get_contents("user.xml");
        echo $file; 

    }


sqlsrv_close($conn);




?>