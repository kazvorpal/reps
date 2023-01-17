<?php 
// REGIONS AND PROJECT COUNTS -reg_clsp
$fsyear = date("Y");

if(isset($_POST['fsyear'])) {
	$fsyear = $_POST['fsyear'];
	}

$sql_reg_clsp = "SELECT EPS.ProjectStage.Region, COUNT(EPS.ProjectStage.Region) AS projCount
FROM EPS.ProjectStage
WHERE EPS.ProjectStage.Region != '' and FISCL_PLAN_YR = '$fsyear' and PROJ_STAT = 'Active'
GROUP BY EPS.ProjectStage.Region
ORDER BY EPS.ProjectStage.Region";
$stmt_reg_clsp = sqlsrv_query( $data_conn, $sql_reg_clsp );
		//echo $row_reg_clsp['PROJ_ID']

// TOTAL PROJECTS 
$sql_pcount = "SELECT COUNT(EPS.ProjectStage.ProjectStage_key) AS pcount
FROM EPS.ProjectStage
WHERE EPS.ProjectStage.Region != '' AND FISCL_PLAN_YR = '$fsyear' and PROJ_STAT = 'Active'";
$stmt_pcount = sqlsrv_query( $data_conn, $sql_pcount );
$row_pcount = sqlsrv_fetch_array( $stmt_pcount, SQLSRV_FETCH_ASSOC);
		//echo $row_pcount['PROJ_ID']
?>