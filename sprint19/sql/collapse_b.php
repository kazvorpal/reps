<?php 
// reg_clsp -- Regions and project counts
$fsyear = date("Y");

if(isset($_POST['fsyear'])) {
	$fsyear = $_POST['fsyear'];
	}

$sql_reg_clsp = "SELECT EPS.ProjectStage.Region, COUNT(EPS.ProjectStage.Region) AS projCount
FROM EPS.ProjectStage
WHERE EPS.ProjectStage.Region != '' and FISCL_PLAN_YR = '$fsyear'
GROUP BY EPS.ProjectStage.Region
ORDER BY EPS.ProjectStage.Region";
$stmt_reg_clsp = sqlsrv_query( $conn_COXProd, $sql_reg_clsp );
//echo $row_reg_clsp['PROJ_ID']
?>

<?php 
// project count -- Total projects 
$sql_pcount = "SELECT COUNT(EPS.ProjectStage.ProjectStage_key) AS pcount
FROM EPS.ProjectStage
WHERE EPS.ProjectStage.Region != '' AND FISCL_PLAN_YR = '$fsyear'";
$stmt_pcount = sqlsrv_query( $conn_COXProd, $sql_pcount );
$row_pcount = sqlsrv_fetch_array( $stmt_pcount, SQLSRV_FETCH_ASSOC) 
//echo $row_pcount['PROJ_ID']
?>





