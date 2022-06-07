<?php    
// update time
//$row_uptime['last_update']
$sql_uptime_all = "select * from [dbo].[fn_GetRefreshDatesforWelcomePage]() order by Source";
$stmt_uptime_all = sqlsrv_query( $data_conn, $sql_uptime_all );
$row_uptime_all = sqlsrv_fetch_array( $stmt_uptime_all, SQLSRV_FETCH_ASSOC)
?>