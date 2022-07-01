<?php 
//DECLARE
$projNm = $_GET['proj_name'];

//GET PROJECT BY NAME
$sql_projNm = "SELECT * FROM [EPS].[ProjectStage] WHERE PROJ_NM = '$projNm'";
$stmt_projNm = sqlsrv_query( $data_conn, $sql_projNm ); // Live Connection
$row_projNm = sqlsrv_fetch_array( $stmt_projNm, SQLSRV_FETCH_ASSOC);
//$row$projNm['columnName'];
//echo $sql$projNm;
?>