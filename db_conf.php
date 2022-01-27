<?php 
// database credentials
$db_uid = 'Tableau';

//Dev QA
$db_qa = "CATL0QWDB10005\EMOQA";
$db_nm0 = 'Cox_Dev';  
$db_nm1 = 'ODS';  
$db_nm3 = 'COX_QA';  

//Prod EMO
$db_prd = "CATL0DB723\EMO";
$db_nm2 = 'COX';

$message = 'Tab123';
$key = hex2bin('000102030405060708090a0b0c0d0e0f101112131415161718191a1b1c1d1e1f');

$encrypted = UnsafeCrypto::encrypt($message, $key, true);
$decrypted = UnsafeCrypto::decrypt($encrypted, $key, true);
?>