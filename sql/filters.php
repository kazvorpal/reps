<?php 
// DATABASE
//$db_uni = $conn_COX_QA; // QA
$db_uni = $conn_COXProd; // Production 
//$db_uni = $conn; // DEV

// Fiscal Year for Dropdowns - Set to all years to display all dropdown items when cleared button is clicked
$fiscal_year_d = $fiscal_year ;
if($fiscal_year == 0) {
	$fiscal_year_d = '2018|2019|2020|2021|2022';
}

// program Drop
// echo $row_program_n['id'];
$sql_program_n = "SELECT [PRGM]
						FROM [EPS].[fn_GetListOfProjectStageWithCriteria]('$fiscal_year_d','$pStatus','$program_d','$region','$market','$owner','$subprogram','$facility')
						WHERE [PRGM] IS NOT NULL
						Group By [PRGM]
						ORDER BY [PRGM]";
$stmt_program_n = sqlsrv_query( $db_uni, $sql_program_n );

// region_drop
// echo $row_region_drop['region'];

$sql_region_drop = "SELECT [Region]
						FROM [EPS].[fn_GetListOfProjectStageWithCriteria]('$fiscal_year_d','$pStatus','$program_d','$region','$market','$owner','$subprogram','$facility')
						WHERE [Region] IS NOT NULL
						GROUP BY [Region]
						ORDER BY [Region]";
$stmt_region_drop = sqlsrv_query( $db_uni, $sql_region_drop );

// market_drop
// echo $row_market_drop['id'];

$sql_market_drop = "SELECT [Market]
						FROM [EPS].[fn_GetListOfProjectStageWithCriteria]('$fiscal_year_d','$pStatus','$program_d','$region','$market','$owner','$subprogram','$facility')
						WHERE [Market] IS NOT NULL
						GROUP BY [Market]
						ORDER BY [Market]";
$stmt_market_drop = sqlsrv_query( $db_uni, $sql_market_drop );

// owner_drop
// echo $row_owner_drop['id'];

$sql_owner_drop = "SELECT [PROJ_OWNR_NM]
						FROM [EPS].[fn_GetListOfProjectStageWithCriteria]('$fiscal_year_d','$pStatus','$program_d','$region','$market','$owner','$subprogram','$facility')
						WHERE [PROJ_OWNR_NM] IS NOT NULL
						GROUP BY [PROJ_OWNR_NM]
						ORDER BY [PROJ_OWNR_NM]";
$stmt_owner_drop = sqlsrv_query( $db_uni, $sql_owner_drop );

// fiscal_year
// echo $row_fiscal_yr['id'];

$sql_fiscal_year = "SELECT [FISCL_PLAN_YR]
From [EPS].[ProjectStage]
WHERE [FISCL_PLAN_YR] IS NOT NULL
GROUP BY [FISCL_PLAN_YR]
ORDER BY [FISCL_PLAN_YR]";
$stmt_fiscal_year = sqlsrv_query( $db_uni, $sql_fiscal_year );

// subprogram drop - WILL NOT WORK; PENDING FIX FROM AVI/CHRISTOPHE -- COMPLETED
// echo $row_subprog['PROJ_SIZE'];

$sql_subprogram = "SELECT [Sub_Prg]
					FROM (
						Select [Sub_Prg]
						From [EPS].[fn_GetListOfProjectStageWithCriteria]('$fiscal_year_d','$pStatus','$program_d','$region','$market','$owner','$subprogram','$facility')
						Group By [Sub_Prg]
						) AS SPRGMX
					WHERE [Sub_Prg] IS NOT NULL
					ORDER BY [Sub_Prg]";
$stmt_subprogram  = sqlsrv_query( $db_uni, $sql_subprogram );

// Facility
// echo $row_facility_drop['id'];
$sql_facility_drop = "SELECT [Facility]
						FROM [EPS].[fn_GetListOfProjectStageWithCriteria]('$fiscal_year_d','$pStatus','$program_d','$region','$market','$owner','$subprogram','$facility')
						WHERE [Facility] IS NOT NULL
						GROUP BY [Facility]
						ORDER BY [Facility]";
$stmt_facility_drop = sqlsrv_query( $db_uni, $sql_facility_drop );


?>



