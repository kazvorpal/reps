<?php 
$windowsUser = preg_replace("/^.+\\\\/", "", $_SERVER["AUTH_USER"]);

//PROJECT OWNERS
$sql_winuser = "SELECT * FROM [RI_MGT].[RiskandIssues_Users] WHERE Username = '$windowsUser'";
$stmt_winuser = sqlsrv_query( $data_conn, $sql_winuser ); 
$row_winuser = sqlsrv_fetch_array( $stmt_winuser, SQLSRV_FETCH_ASSOC);
//$row_winuser['Username']

//DEBUG
//echo  $sql_winuser;
?>