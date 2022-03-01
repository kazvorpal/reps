<?php 
$windowsUser = preg_replace("/^.+\\\\/", "", $_SERVER["AUTH_USER"]);

//PROJECT OWNERS
$sql_winuser = "SELECT * FROM [COX_Dev].[EPS].[RiskandIssues_Users] WHERE Username = '$windowsUser'";
//$stmt_por_cnt = sqlsrv_query( $conn_COXProd, $sql_por_cnt ); // Live connection
$stmt_winuser = sqlsrv_query( $conn_COX_QA, $sql_winuser ); //QA Connection
//$stmt_winuser = sqlsrv_query( $conn, $sql_winuser ); //DEV Connection

$row_winuser = sqlsrv_fetch_array( $stmt_winuser, SQLSRV_FETCH_ASSOC);
//$row_winuser['columnname']

?>