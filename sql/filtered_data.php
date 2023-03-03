<?php 
//FILTERED DATA
$sql_por = "Select* From [EPS].[fn_GetListOfProjectStageWithCriteria]('$fiscal_year','$pStatus','$program_n','$region','$market','$owner', '$subprogram','$facility') ORDER BY [PRGM], [Sub_Prg]";
$stmt_por = sqlsrv_query( $data_conn, $sql_por );

//FILTERED DATA COUNT
$sql_por_cnt = "SELECT COUNT(*) AS daCount From [EPS].[fn_GetListOfProjectStageWithCriteria]('$fiscal_year','$pStatus','$program_n','$region','$market','$owner', '$subprogram','$facility')";
$stmt_por_cnt = sqlsrv_query( $data_conn, $sql_por_cnt ); 
$row_da_count = sqlsrv_fetch_array( $stmt_por_cnt, SQLSRV_FETCH_ASSOC);

//$row_da_count['daCount']

//DEBUG
//echo $sql_por;
?>