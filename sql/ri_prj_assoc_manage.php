<?php 
$RI_projID = $_GET['uid'];
$increment = $_GET['inc'];
$org_proj = $_GET['proj_name'];

//GET MASTER PROJECT
$sql_ri = "SELECT * FROM [EPS].[ProjectStage] WHERE PROJ_ID = '$RI_projID'";
$stmt_ri = sqlsrv_query( $data_conn, $sql_ri ); 
$row_ri = sqlsrv_fetch_array( $stmt_ri, SQLSRV_FETCH_ASSOC) ;
//$row_ri['columnname']
//echo $sql_ri;

//GET LIST OF OWNER PORJECTS AND COMMA SEPERATE
$sql_avail_prjs = "DECLARE @temp VARCHAR(MAX)
            SELECT @temp = COALESCE(@temp+', ' ,'') + EPSProject_Nm
            FROM RI_MGT.fn_getlistofallriskandissue(1) where RIIncrement_Num = $increment
            SELECT @temp AS eps_projects";
$stmt_avail_prjs   = sqlsrv_query( $data_conn, $sql_avail_prjs ); 
$row_avail_prjs   = sqlsrv_fetch_array( $stmt_avail_prjs  , SQLSRV_FETCH_ASSOC);
$avRaw = $row_avail_prjs ['eps_projects'];
$avCom = str_replace(", ","','",$avRaw);
$pjNames =  "'" . $avCom . "'";
//echo $pjNames;

//GET ASSOCIATED PROJECT OF A RISK/ISSUE
$sql_assoc_prj = "select * 
            from RI_MGT.fn_getlistofallriskandissue(1) 
            where RIIncrement_Num =$increment and EPSProject_Nm != '$org_proj'";
$stmt_assoc_prj  = sqlsrv_query( $data_conn, $sql_assoc_prj ); 
//$row_assoc_prj  = sqlsrv_fetch_array( $stmt_assoc_prj , SQLSRV_FETCH_ASSOC) 
//$row_assoc_prj ['columnname']
//echo $sql_assoc_prj;

//GET LIST OF PROJECTS AVAILABLE TO ADD TO R/I
$sql_por = "Select* 
            From [EPS].[fn_GetListOfProjectStageWithCriteria]('$fiscal_year','Active','$program_d','$region','$market','$owner', '$subprogram','$facility') 
            WHERE PROJ_NM not in ( $pjNames ) ORDER BY [PRGM], [Sub_Prg]";
$stmt_por = sqlsrv_query( $data_conn, $sql_por ); 
//$row_por = sqlsrv_fetch_array( $stmt_por, SQLSRV_FETCH_ASSOC) 
//$row_por['columnname']
echo $sql_por;

//COUNT PROJECTS FOUND AFTER FILTERED
$sql_por_cnt = "SELECT COUNT(*) AS daCount 
            From [EPS].[fn_GetListOfProjectStageWithCriteria]('$fiscal_year','Active','$program_n','$region','$market','$owner', '$subprogram','$facility')";
$stmt_por_cnt = sqlsrv_query( $data_conn, $sql_por_cnt ); 

$row_da_count = sqlsrv_fetch_array( $stmt_por_cnt, SQLSRV_FETCH_ASSOC);
//$row_da_count['daCount']

?>