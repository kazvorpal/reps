<?php 
// portfolio dropdown
$sql_crCat = "SELECT * FROM [dbo].[fn_GetListOfAllFundingCategory] () WHERE Active_Flg = 1 ";
$stmt_crCat = sqlsrv_query( $conn_COXProd, $sql_crCat );
//$row_crCat = sqlsrv_fetch_array( $stmt_crCat, SQLSRV_FETCH_ASSOC);
//echo $row_crCat['PortfolioFundingCat_Key']

// status dropdown
$sql_crDrp = "Select CR_Status_Abb, CR_Status_Key From dbo.fn_GetCR_All(2020,1) group by CR_Status_Abb, CR_Status_Key order by CR_Status_Key";
$stmt_crDrp = sqlsrv_query( $conn_COXProd, $sql_crDrp );
//$row_crDrp = sqlsrv_fetch_array( $stmt_crDrp, SQLSRV_FETCH_ASSOC);
//echo $row_crDrp['']

// fiscal year dropdown
$sql_fyDrp = "SELECT Fiscal_Year FROM dbo.fn_GetFiscalYears () WHERE ACTIVE_Flg=1 ORDER BY Fiscal_Year DESC";
$stmt_fyDrp = sqlsrv_query( $conn_COXProd, $sql_fyDrp );
//$row_fyDrp = sqlsrv_fetch_array( $stmt_fyDrp, SQLSRV_FETCH_ASSOC);
//echo $row_fyDrp['']

// CRS
$fundingKey = 0;
if(isset($_GET['fundingKey'])) {
$fundingKey = $_GET['fundingKey'];
}

$year = date('Y');
if(isset($_GET['year'])) {
$year = $_GET['year'];
}

$statusK = '';
if(isset($_GET['status'])) {
if($_GET['status'] >= 1) {
$pstatus = $_GET['status'];
$statusK = 'WHERE CR_Status_Key =' . $pstatus;
}
}

// Paging 50 per
$startRow =1;
$endRow = 500;

if(isset($_GET['start'])){
	$startRow = $_GET['start'];
	$endRow = $_GET['end'];
	}


		// SHOW 50 AT A TIME
		$sql_crs = "
					SELECT c.*
					FROM (
						Select ROW_NUMBER() OVER(ORDER BY CR_Id Desc) AS RowNumber, * 
						From dbo.fn_GetCR_All($year,$fundingKey) 
						$statusK 
						) AS c
					WHERE c.RowNumber >= $startRow AND c.RowNumber <= $endRow
					ORDER BY c.CR_id Desc
					";
		$stmt_crs = sqlsrv_query( $conn_COX_QA, $sql_crs, array(), array("Scrollable" => 'static') );
		$row_count_crs = sqlsrv_num_rows( $stmt_crs );
		//$row_crs = sqlsrv_fetch_array( $stmt_crs, SQLSRV_FETCH_ASSOC);
		//echo $row_crs['column_name']

		// LETS COUNT THE TOTAL IN THAT RESULTS
		$sql_crs_ct = "Select* From dbo.fn_GetCR_All($year,$fundingKey) $statusK ORDER BY CR_id Desc";
		$stmt_crs_ct = sqlsrv_query( $conn_COX_QA, $sql_crs_ct, array(), array("Scrollable" => 'static') );
		$row_count_crs_ct = sqlsrv_num_rows( $stmt_crs_ct );

			// Set the number of rows to be returned on a page.
			$rowsPerPage = 500;
			
			// Get the total number of rows returned by the query. 
			$row_count_crs_ct = sqlsrv_num_rows( $stmt_crs_ct );
			/* Calculate number of pages. */
			$numOfPages = ceil($row_count_crs_ct/$rowsPerPage);





?>
