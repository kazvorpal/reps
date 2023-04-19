<?php
    if(isset($_POST['ocd'])) { // if the field is set

		$a = $_POST['ocd'];

		if(strpos($a, ',') !== false) {  // if string contains a comma  - clean it up
			$oracleCD_trim = trim($_POST['ocd']); //oracle code from ocd search field trimmed
			$oracleCD_Q1 = str_replace("'", "", $oracleCD_trim);
			$oracleCD_Q2 = str_replace('"', "", $oracleCD_Q1);
			$oracleCD_Q3 = str_replace(' ', '', $oracleCD_Q2);

			$oracleCD = $oracleCD_Q3;
		} else {  // if string contains no comma  - move it through
			$oracleCD = $_POST['ocd'];
		}
		
	} else { // default to find nothing
		$oracleCD = 'xxx';
	}
	
	$sql_eqh = "Select * 
				From OrdMgt.fn_GetOrderHistoryEquipmentId(2021)
				WHERE  GL_Project_Num IN (SELECT convert(varchar, value) FROM string_split('$oracleCD', ','))
							  OR Order_Num IN (SELECT convert(varchar, value) FROM string_split('$oracleCD', ','))
							  OR Requisition_Num IN (SELECT convert(varchar, value) FROM string_split('$oracleCD', ','))
							  OR PPM_Num IN (SELECT convert(varchar, value) FROM string_split('$oracleCD', ','))
							  OR OPTIX_Id IN (SELECT convert(varchar, value) FROM string_split('$oracleCD', ','))
							  OR HelpDeskTicket_Num IN (SELECT convert(varchar, value) FROM string_split('$oracleCD', ','))
							  OR WWT_Quote_Num IN (SELECT convert(varchar, value) FROM string_split('$oracleCD', ','))
							  OR EquipPlan_Id IN ('$oracleCD')
							  OR EPS_Project_Nm IN ('$oracleCD')
				";
				
	$stmt_eqh = sqlsrv_query( $conn_COXProd, $sql_eqh ); //$conn_COXProd is Cox Datebase on SQL Production //$conn_COX_QA is test Datebase 

	// OR EPS_Project_Nm IN (SELECT convert(varchar, value) FROM string_split('$oracleCD', ','))

	// SPRINT 15 addition - Waiting on endpoint
	// OR Equipment_Id IN (SELECT convert(varchar, value) FROM string_split('$oracleCD', ','))

?>