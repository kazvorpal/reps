<?php
//DEVELOPMENT --------------------
// Cox_Dev Database
$serverName = "CATL0QWDB10005\EMOQA"; 
$connectionInfo = array("Database"=>"$db_nm0", "UID"=>"$db_uid", "PWD"=>"$decrypted");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

// ODS Database 
$serverName_ODS = "CATL0QWDB10005\EMOQA"; 
$connectionInfo_ODS = array("Database"=>"$db_nm1", "UID"=>"$db_uid", "PWD"=>"$decrypted");
$conn_ODS  = sqlsrv_connect( $serverName_ODS , $connectionInfo_ODS );

// Cox Database PRODUCTION
$serverName_COXProd = "CATL0DB723\EMO"; 
$connectionInfo_COXProd = array("Database"=>"$db_nm2", "UID"=>"$db_uid", "PWD"=>"$decrypted");
$conn_COXProd = sqlsrv_connect( $serverName_COXProd, $connectionInfo_COXProd);

// Cox_QA Database
$serverName_COX_QA = "CATL0QWDB10005\EMOQA"; 
$connectionInfo_COX_QA = array("Database"=>"$db_nm3", "UID"=>"$db_uid", "PWD"=>"$decrypted");
$conn_COX_QA = sqlsrv_connect( $serverName_COX_QA, $connectionInfo_COX_QA);

// GLOBAL DATA CONNECTION
//$conn = Dev Database
//$conn_COXProd= Prodiction Database
//$conn_COX_QA = QA Database

// list of server urls for different data connections
$serverlist = (array) [
    "catl0dwas11209.corp.cox.com" => $conn,
    "catl0pwas10385.corp.cox.com" => $conn_ODS,
    "catl0dwas11208.corp.cox.com" => $conn_COXProd,
    "catl0dwas10222.corp.cox.com" => $conn_COX_QA
];

$data_conn = $serverlist[$_SERVER['HTTP_HOST']]; //<--CHANGE THIS TO SWITCH CONNECTIONS
//Uncomment the below line and change to a specific connection to override.
// $data_conn = $conn; 

?>