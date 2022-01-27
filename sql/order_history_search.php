<?php
    if(isset($_POST['ocd'])) {
		$oracleCD_trim = trim($_POST['ocd']); //oracle code from ocd search field trimmed
		$oracleCD_Q1 = str_replace("'", "", $oracleCD_trim);
		$oracleCD_Q2 = str_replace('"', "", $oracleCD_Q1);
		$oracleCD = $oracleCD_Q2;
	} else {
		$oracleCD = 'xxx';
	}
	
	$sql_eqh = "SELECT OrderType, PPM_Num, Order_Num, Line_Num
				, Manufacturer_Nm, [Description], Deducted_RQT_Dt
				, COX_PID, MFG_PART_Num, Ordered_QTY, Ordered_Amt, Shipment_Status
				, Case When Receipt_Num Is Null then 'No' Else 'Yes' End As Received
				, Transaction_Date
				, Project_Num
				, Qty_Received
				, Preparer_Nm
				, Case When WWT_AsOf_Dt Is Not Null then 'Yes' Else 'No' End As WWTOrder
				FROM DBO.fn_GetOrderHistory ()
				Where (Project_Num LIKE '%$oracleCD%' OR Order_Num LIKE '%$oracleCD%' OR PPM_Num LIKE '%$oracleCD%') and YEAR (Deducted_RQT_Dt) = YEAR (Getdate())
				ORDER BY Order_Num, Line_Num";
	$stmt_eqh = sqlsrv_query( $conn_COXProd, $sql_eqh ); //$conn_COXProd is Cox Datebase on SQL Production //$conn_COX_QA is test Datebase 
?>
