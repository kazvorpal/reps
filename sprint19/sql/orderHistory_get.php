<?php
    if(isset($_GET['ocd'])) {
		$oracleCD_trim = trim($_GET['ocd']); //oracle code from ocd search field trimmed
		$oracleCD_Q1 = str_replace("'", "", $oracleCD_trim);
		$oracleCD_Q2 = str_replace('"', "", $oracleCD_Q1);
		$oracleCD_Q3 = str_replace(' ' , '', $oracleCD_Q2);
		
		$oracleCD = $oracleCD_Q3;
	} else {
		$oracleCD = 'xxx';
	}
	
	$sql_eqh = "Select * 
				From OrdMgt.fn_GetOrderHistory(2020)
				WHERE  GL_Project_Num IN (SELECT convert(varchar, value) FROM string_split('$oracleCD', ','))
							  OR Order_Num IN (SELECT convert(varchar, value) FROM string_split('$oracleCD', ','))
							  OR Requisition_Num IN (SELECT convert(varchar, value) FROM string_split('$oracleCD', ','))
							  OR PPM_Num IN (SELECT convert(varchar, value) FROM string_split('$oracleCD', ','))
							  OR OPTIX_Id IN (SELECT convert(varchar, value) FROM string_split('$oracleCD', ','))
							  OR HelpDeskTicket_Num IN (SELECT convert(varchar, value) FROM string_split('$oracleCD', ','))
							  OR WWT_Quote_Num IN (SELECT convert(varchar, value) FROM string_split('$oracleCD', ','))
				ORDER BY GL_Project_Num";
	$stmt_eqh = sqlsrv_query( $conn_COXProd, $sql_eqh ); //$conn_COXProd is Cox Datebase on SQL Production //$conn_COX_QA is test Datebase 
?>