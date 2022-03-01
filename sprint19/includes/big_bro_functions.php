<?php 
// FUNCTON Log user action
function bigBro($uid, $ptitle, $version, $action, $actionCD) {
	
	include ("../db_conf.php");
	include ("../data/emo_data.php");
	
	$a = $uid;
	$b = $ptitle;
	$c = $version;
	$d = $action;
	$e = $actionCD;
	
	$sql_log = "EXECUTE dbo.sp_InsertUserLog '$a','$b','$c','$d','$e' ";
	$stmt_log = sqlsrv_query( $conn_COXProd, $sql_log );
	
}

// EXECUTE Function
$daUser = $_SERVER['AUTH_USER'];
$lPage = $_SERVER['ORIG_PATH_INFO'];
$servPath = $_SERVER['PATH_TRANSLATED'];

bigBro($daUser, $lPage, '2.2.5', 'Page Request', $servPath)
?>