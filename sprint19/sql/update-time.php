<?php    
// update time
//$row_uptime['last_update']
$sql_uptime = "select top(1) Last_Update_Ts from EPS.ProjectStage";
$stmt_uptime = sqlsrv_query( $conn_COXProd, $sql_uptime );
$row_uptime = sqlsrv_fetch_array( $stmt_uptime, SQLSRV_FETCH_ASSOC)
?>