<?php 
//INTERNAL
$sql_internal = "select * from [RI_MGT].[fn_GetListOfCurrentTaskPOC] (1) order by POC_Nm";
$stmt_internal = sqlsrv_query( $data_conn, $sql_internal ); 
//$row_internal = sqlsrv_fetch_array( $stmt_internal, SQLSRV_FETCH_ASSOC);
//$row_internal['columnname']

//EXTERNAL
$sql_external = "select * from [RI_MGT].[fn_GetListOfCurrentTaskPOC] (0) order by POC_Nm";
$stmt_external  = sqlsrv_query( $data_conn, $sql_external  ); 
//$row_external  = sqlsrv_fetch_array( $stmt_external , SQLSRV_FETCH_ASSOC);
//$row_external['columnname']

?>