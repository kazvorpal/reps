<?php 
// reg_clsp -- REGIONS AND PROJECT COUNTS
$fsyear = date("Y");
$PUid = $_GET['uid'];

if(isset($_POST['fsyear'])) {
	$fsyear = $_POST['fsyear'];
	}

$sql_proj_clps = "SELECT * FROM EPS.ProjectStage WHERE EPS.ProjectStage.PROJ_ID = '$PUid'";
$stmt_proj_clps = sqlsrv_query( $conn_COXProd, $sql_proj_clps );
$row_proj_clps = sqlsrv_fetch_array( $stmt_proj_clps, SQLSRV_FETCH_ASSOC);
		//echo $row_reg_clsp['PROJ_ID']

$sql_reg_clsp = "SELECT EPS.ProjectStage.Region, COUNT(EPS.ProjectStage.Region) AS projCount
	FROM EPS.ProjectStage
	WHERE EPS.ProjectStage.Region != '' and FISCL_PLAN_YR = '$fsyear' and PROJ_STAT = 'Active'
	GROUP BY EPS.ProjectStage.Region
	ORDER BY EPS.ProjectStage.Region";
$stmt_reg_clsp = sqlsrv_query( $conn_COXProd, $sql_reg_clsp );
$row_reg_clsp = sqlsrv_fetch_array( $stmt_reg_clsp, SQLSRV_FETCH_ASSOC);

$match_reg = $row_reg_clsp['Region'];

$sql_prog_clsp = "SELECT ROW_NUMBER() OVER(ORDER BY EPS.ProjectStage.PRGM ) AS RWNM , EPS.ProjectStage.PRGM, COUNT(EPS.ProjectStage.PRGM) AS prog_count
	FROM EPS.ProjectStage
	WHERE EPS.ProjectStage.Region = '$match_reg' AND FISCL_PLAN_YR = '$fsyear'
	GROUP BY EPS.ProjectStage.PRGM";
$stmt_prog_clsp = sqlsrv_query($conn_COXProd, $sql_prog_clsp);
$row_prog_clsp = sqlsrv_fetch_array( $stmt_prog_clsp, SQLSRV_FETCH_ASSOC)
?>