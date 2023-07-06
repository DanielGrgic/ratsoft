<?php
$serverName = "184.154.5.218\MSSQLSERVER2014"; //serverName\instanceName
$connectionInfo = array( "Database"=>"rms", "UID"=>"sparksystems", "PWD"=>"prZ#2e74");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn ) {
     //echo "Connection established.<br />";
}else{
     //echo "Connection could not be established.<br />";
     die( print_r( sqlsrv_errors(), true));
}
?>