<?php 
//INTERNAL
$sql_internal = "select * from [RI_MGT].[fn_GetListOfCurrentTaskPOC] (1) order by POC_Nm";
//$stmt_por_cnt = sqlsrv_query( $conn_COXProd, $sql_por_cnt ); // Live connection
//$stmt_por_cnt = sqlsrv_query( $conn_COX_QA, $sql_por_cnt ); //QA Connection
$stmt_internal = sqlsrv_query( $conn, $sql_internal ); //DEV Connection
//$row_internal = sqlsrv_fetch_array( $stmt_internal, SQLSRV_FETCH_ASSOC);
//$row_internal['columnname']

//EXTERNAL
$sql_external = "select * from [RI_MGT].[fn_GetListOfCurrentTaskPOC] (0) order by POC_Nm";
//$stmt_por_cnt = sqlsrv_query( $conn_COXProd, $sql_por_cnt ); // Live connection
//$stmt_por_cnt = sqlsrv_query( $conn_COX_QA, $sql_por_cnt ); //QA Connection
$stmt_external  = sqlsrv_query( $conn, $sql_external  ); //DEV Connection

//$row_external  = sqlsrv_fetch_array( $stmt_external , SQLSRV_FETCH_ASSOC);
//$row_external['columnname']

?>