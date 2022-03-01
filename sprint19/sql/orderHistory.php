<?php
    if(isset($_POST['ocd'])) {
		$oracleCD_trim = trim($_POST['ocd']); //oracle code from ocd search field trimmed
		$oracleCD_Q1 = str_replace("'", "", $oracleCD_trim);
		$oracleCD_Q2 = str_replace('"', "", $oracleCD_Q1);
		$oracleCD = $oracleCD_Q2;
	} else {
		$oracleCD = 'xxx';
	}
	
	$sql_eqh = "Select * 
				From OrdMgt.fn_GetOrderHistory(2020)
				Where GL_Project_Num LIKE '%$oracleCD%' or Requisition_Num LIKE '%$oracleCD%' or PPM_Num LIKE '%$oracleCD%' or OPTIX_Id LIKE '%$oracleCD%' or HelpDeskTicket_Num LIKE '%$oracleCD%' or Order_Num LIKE '%$oracleCD%'
				ORDER BY OrderLine_Num";
	$stmt_eqh = sqlsrv_query( $conn_COXProd, $sql_eqh ); //$conn_COXProd is Cox Datebase on SQL Production //$conn_COX_QA is test Datebase 
?>