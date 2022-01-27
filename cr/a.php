<?php include ("../includes/functions.php");?>
<?php include ("../db_conf.php");?>
<?php include ("../data/emo_data.php");?>
<?php 
// workflow_stage
$bmysql = "select top 2  PROJ_ID From [COX_QA].[EPS].[fn_GetListOfProjectStageWithCriteria](2019,'-1','-1','-1','-1')for xml path";
$sql_por = "$bmysql"; 
$stmt_por = sqlsrv_query( $conn_COX_QA, $sql_por );

//sqlsrv_fetch($stmt_por);
//$row_program_n = sqlsrv_get_field($stmt_por, 0, SQLSRV_PHPTYPE_STRING('UTF-8'));
//
//$doc   =  new DOMDocument();
//$result = $doc->loadXML($row_program_n);
//
//var_dump($result);         
//$doc->save('php://output'); 


while($row_program_n = sqlsrv_fetch_array( $stmt_por, SQLSRV_FETCH_ASSOC)) {

// header("Content-type: application/vnd.ms-excel; name='excel'");
// header("Content-Disposition: attachment; filename=export_detailed_phase_report.xls");
// header("Pragma: no-cache");
// header("Expires: 0");

echo $row_program_n['XML_F52E2B61-18A1-11d1-B105-00805F49916B'] . '--------';
}
?>