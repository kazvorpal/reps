<?php 
$projID = $_GET['uid'];

$sql_projID = "SELECT * FROM [COX].[EPS].[ProjectStage] WHERE PROJ_ID = '$projID'";
$stmt_projID = sqlsrv_query( $conn_COXProd, $sql_projID ); // Live Connection
//$stmt_projID = sqlsrv_query( $conn_COX_QA, $sql_projID );  // QA Connection
//$stmt_projID = sqlsrv_query( $conn, $sql_projID );  // DEV Connection
$row_projID = sqlsrv_fetch_array( $stmt_projID, SQLSRV_FETCH_ASSOC)

//$row_projID['columnName']
?>