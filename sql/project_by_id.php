<?php 
//DECLARE
$projID = $_GET['uid'];

//GET PROJECT BY ID
$sql_projID = "SELECT * FROM [EPS].[ProjectStage] WHERE PROJ_ID = '$projID'";
$stmt_projID = sqlsrv_query( $data_conn, $sql_projID ); // Live Connection
$row_projID = sqlsrv_fetch_array( $stmt_projID, SQLSRV_FETCH_ASSOC)
//$row_projID['columnName']
?>