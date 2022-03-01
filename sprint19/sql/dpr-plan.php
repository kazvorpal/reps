<?php 
$username = '';
$projID = $_GET['uid'];
$fiscal_yr = $_GET['fiscal_yr'];

$sql_plan = "Select * From PORMgt.fn_GetListOfPlanForEPSProject('$username','$fiscal_yr','$projID ')";
$stmt_plan= sqlsrv_query( $conn_COXProd, $sql_plan ); // Live Connection
//$stmt_plan= sqlsrv_query( $conn_COX_QA, $sql_plan );  // QA Connection
//$stmt_plan = sqlsrv_query( $conn, $sql_plan );  // DEV Connection
//$row_plan= sqlsrv_fetch_array( $stmt_$sql_plan, SQLSRV_FETCH_ASSOC)

//$row_plan['columnName']
?>