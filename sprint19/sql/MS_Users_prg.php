<?php 
$windowsUser = preg_replace("/^.+\\\\/", "", $_SERVER["AUTH_USER"]);
$program = $_GET['program'];

//PROGRAM OWNERS
$sql_winuser_prg = "SELECT *
FROM (
	SELECT * 
	FROM [RI_Mgt].[fn_GetListOfOwnersInfoForProgram](2021, '$program')
) a
WHERE User_UID = '$windowsUser'";
//$stmt_por_cnt = sqlsrv_query( $conn_COXProd, $sql_por_cnt ); // Live connection
//$stmt_por_cnt = sqlsrv_query( $conn_COX_QA, $sql_por_cnt ); //QA Connection
$stmt_winuser_prg = sqlsrv_query( $conn, $sql_winuser_prg ); //DEV Connection

$row_winuser_prg = sqlsrv_fetch_array( $stmt_winuser_prg, SQLSRV_FETCH_ASSOC);
//$row_winuser_prg['User_UID']


?>