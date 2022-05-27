<?php 
$RI_projID = $_GET['uid'];

//GET MASTER PROJECT
$sql_ri = "SELECT * FROM [EPS].[ProjectStage] WHERE PROJ_ID = '$RI_projID'";
$stmt_ri = sqlsrv_query( $data_conn, $sql_ri ); 
$row_ri = sqlsrv_fetch_array( $stmt_ri, SQLSRV_FETCH_ASSOC) ;
//$row_ri['columnname']

//GET LIST OF PROJECTS FOR USER
$sql_por = "Select* From [EPS].[fn_GetListOfProjectStageWithCriteria]('$fiscal_year','$pStatus','$program_d','$region','$market','$owner', '$subprogram','$facility') WHERE PROJ_ID != '$RI_projID' ORDER BY [PRGM], [Sub_Prg]";
$stmt_por = sqlsrv_query( $data_conn, $sql_por ); 
//$row_por = sqlsrv_fetch_array( $stmt_por, SQLSRV_FETCH_ASSOC) 
//$row_por['columnname']

//COUNT PROJECTS FOUND AFTER FILTERED
$sql_por_cnt = "SELECT COUNT(*) AS daCount From [EPS].[fn_GetListOfProjectStageWithCriteria]('$fiscal_year','$pStatus','$program_n','$region','$market','$owner', '$subprogram','$facility')";
$stmt_por_cnt = sqlsrv_query( $data_conn, $sql_por_cnt ); 

$row_da_count = sqlsrv_fetch_array( $stmt_por_cnt, SQLSRV_FETCH_ASSOC);
//$row_da_count['daCount']



?>