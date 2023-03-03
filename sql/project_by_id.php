<?php 
//DECLARE
$projID = "";
if(isset($_GET['uid'])) { 
$projID = $_GET['uid'];
}

//GET PROJECT BY ID
$sql_projID = "SELECT * FROM [EPS].[ProjectStage] WHERE PROJ_ID = '$projID'";
$stmt_projID = sqlsrv_query( $data_conn, $sql_projID ); // Live Connection
$row_projID = sqlsrv_fetch_array( $stmt_projID, SQLSRV_FETCH_ASSOC);
//$row_projID['columnName'];
//echo $sql_projID;

//REGION FROM PROJECT ID
$sql_prj_region = "SELECT *  FROM [RI_MGT].[fn_GetListOfRegionForEPSProject]() WHERE PROJ_ID = '$projID'";
$stmt_prj_region = sqlsrv_query( $data_conn, $sql_prj_region  ); // Live Connection
$row_prj_region  = sqlsrv_fetch_array( $stmt_prj_region , SQLSRV_FETCH_ASSOC);
//$row_prj_region ['columnName'];
//echo $sql_prj_region ;
//echo  $row_prj_region['Region']
?>