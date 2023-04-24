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
				From OrdMgt.fn_GetOrderHistoryEquipmentId(2023)
				WHERE  cast(GL_Project_Num as varchar(256)) IN (SELECT convert(varchar, value) FROM string_split('$oracleCD', ','))
							  OR cast(Order_Num as varchar(256)) IN (SELECT convert(varchar, value) FROM string_split('$oracleCD', ','))
							  OR cast(Requisition_Num as varchar(256)) IN (SELECT convert(varchar, value) FROM string_split('$oracleCD', ','))
							  OR cast(PPM_Num as varchar(256)) IN (SELECT convert(varchar, value) FROM string_split('$oracleCD', ','))
							  OR cast(OPTIX_Id as varchar(256)) IN (SELECT convert(varchar, value) FROM string_split('$oracleCD', ','))
							  OR cast(HelpDeskTicket_Num as varchar(256)) IN (SELECT convert(varchar, value) FROM string_split('$oracleCD', ','))
							  OR cast(WWT_Quote_Num as varchar(256)) IN (SELECT convert(varchar, value) FROM string_split('$oracleCD', ','))
							  OR cast(EquipPlan_Id as varchar(256)) IN ('$oracleCD')
							  OR cast(EPS_Project_Nm as varchar(256)) IN ('$oracleCD')
				";
				
				 echo $sql_eqh;
				// die();
	$stmt_eqh = sqlsrv_query( $conn_COXProd, $sql_eqh ); //$conn_COXProd is Cox Datebase on SQL Production //$conn_COX_QA is test Datebase 

	// OR EPS_Project_Nm IN (SELECT convert(varchar, value) FROM string_split('$oracleCD', ','))

	// SPRINT 15 addition - Waiting on endpoint
	// OR Equipment_Id IN (SELECT convert(varchar, value) FROM string_split('$oracleCD', ','))

?>