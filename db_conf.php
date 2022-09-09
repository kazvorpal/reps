<?php 
// DATABASE CREDENTIALS
$db_uid = 'Tableau';

//DEV QA
$db_qa = "CATL0QWDB10005\EMOQA";
$db_nm0 = 'Cox_Dev';  
$db_nm1 = 'ODS';  
$db_nm3 = 'COX_QA';  
$db_nm4 = 'COX_UAT';


//PROD EMO
$db_prd = "CATL0DB723\EMO";
$db_nm2 = 'COX';

//ENCRYPT PASSWORD
$message = 'Tab123'; //PASSWORD
$key = hex2bin('000102030405060708090a0b0c0d0e0f101112131415161718191a1b1c1d1e1f');

$encrypted = UnsafeCrypto::encrypt($message, $key, true);
$decrypted = UnsafeCrypto::decrypt($encrypted, $key, true);
?>