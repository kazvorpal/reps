<?php
    $oracleCD = $_GET['sqlex'];
	
	
	$sql_eqh = "$oracleCD";
	$stmt_eqh = sqlsrv_query( $conn_COXProd, $sql_eqh ); //$conn_COXProd is Cox Datebase on SQL Production
?>
