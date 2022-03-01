<?php 
//DECLARE
$windowsUser = preg_replace("/^.+\\\\/", "", $_SERVER["AUTH_USER"]);
$program = $_GET['program'];
$fsclYear = $_GET['fscl_year'];

//PROGRAM OWNERS
$sql_winuser_prg = "SELECT *
FROM (
	SELECT * 
	FROM [RI_Mgt].[fn_GetListOfOwnersInfoForProgram]($fsclYear, '$program')
) a
WHERE User_UID = '$windowsUser'";
$stmt_winuser_prg = sqlsrv_query( $data_conn, $sql_winuser_prg ); 
$row_winuser_prg = sqlsrv_fetch_array( $stmt_winuser_prg, SQLSRV_FETCH_ASSOC);
//$row_winuser_prg['User_UID']

//DEBUG
//echo $sql_winuser_prg;
?>