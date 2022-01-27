<?php 



//$fiscal_year = '';
//if (empty($_POST['fiscal_year'])) {
//$fiscal_year = $_POST['fiscal_year'];
//}

// Defaulting to current year.  Need to default current year if on 2020 and default to 2021 when on 2021

//$this_year = date('Y');
 //$fiscal_year = $this_year;

//if (empty($_POST['fiscal_year'])) {
//	 $fiscal_year = $this_year; 
//	$fiscal_year = '0'; 
//} else {
//	$fiscal_year = $_POST['fiscal_year'];
//}


//$sql_por = "Select* from (Select TOP (1000) * From [COX].[EPS].[fn_GetListOfProjectStageWithCriteria]($fiscal_year,'$program_d','$region','$market','$owner', '$subprogram')) as a where PROJ_STAT = '$pStatus' adn PROJ_SIZE =  $subprogram"; // Live Connection
$sql_por = "Select* From [EPS].[fn_GetListOfProjectStageWithCriteria]('$fiscal_year','$pStatus','$program_n','$region','$market','$owner', '$subprogram','$facility') ORDER BY [PRGM], [Sub_Prg]";
$stmt_por = sqlsrv_query( $conn_COXProd, $sql_por ); // Live Connection
//$stmt_por = sqlsrv_query( $conn_COX_QA, $sql_por );  // QA Connection
//$stmt_por = sqlsrv_query( $conn, $sql_por );  // DEV Connection

$sql_por_cnt = "SELECT COUNT(*) AS daCount From [EPS].[fn_GetListOfProjectStageWithCriteria]('$fiscal_year','$pStatus','$program_n','$region','$market','$owner', '$subprogram','$facility')";
$stmt_por_cnt = sqlsrv_query( $conn_COXProd, $sql_por_cnt ); // Live connection
//$stmt_por_cnt = sqlsrv_query( $conn_COX_QA, $sql_por_cnt ); //QA Connection
//$stmt_por_cnt = sqlsrv_query( $conn, $sql_por_cnt ); //DEV Connection

$row_da_count = sqlsrv_fetch_array( $stmt_por_cnt, SQLSRV_FETCH_ASSOC) 
//$row_da_count['daCount']
?>