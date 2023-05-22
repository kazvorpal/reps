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
	
	$sql_eqh = "Select o.*, ep.EquipPlan_Id From OrdMgt.fn_GetOrderHistory(2023) o 
				Left Outer Join PORMgt.EquipPlan ep on ep.EPSProject_Key = o.EPS_POR_Project_Key and ep.FiscalYear_Key = o.FiscalYear_Key
				WHERE GL_Project_Num IN (SELECT convert(varchar, value) FROM string_split('$oracleCD', ','))
							  OR Order_Num IN (SELECT convert(varchar, value) FROM string_split('$oracleCD', ','))
							  OR Requisition_Num IN (SELECT convert(varchar, value) FROM string_split('$oracleCD', ','))
							  OR PPM_Num IN (SELECT convert(varchar, value) FROM string_split('$oracleCD', ','))
							  OR OPTIX_Id IN (SELECT convert(varchar, value) FROM string_split('$oracleCD', ','))
							  OR HelpDeskTicket_Num IN (SELECT convert(varchar, value) FROM string_split('$oracleCD', ','))
							  OR WWT_Quote_Num IN (SELECT convert(varchar, value) FROM string_split('$oracleCD', ','))
							  OR EquipPlan_Id IN ('$oracleCD')
							  OR EPS_Project_Nm IN ('$oracleCD')
				";
				echo $sql_eqh; //FIXED 5/16/2023
	$stmt_eqh = sqlsrv_query( $conn, $sql_eqh ); //$conn_COXProd is Cox Datebase on SQL Production //$conn_COX_QA is test Datebase 

// Fetch and print all rows
if( $stmt_eqh === false ) {
	echo "NOOOOOOOOOOOOOOOOOOOOOOOO!,.";
    die( print_r( sqlsrv_errors(), true));
}
echo "<br/>:::::<br/>";
while( $row = sqlsrv_fetch_array( $stmt_eqh, SQLSRV_FETCH_ASSOC) ) {
	echo "<p>PRINTING OUTPUT: '";
    print_r($row);
	echo "'</p>";
}

// Reset pointer to the first row
// sqlsrv_data_seek($stmt_eqh, 0);


	// OR EPS_Project_Nm IN (SELECT convert(varchar, value) FROM string_split('$oracleCD', ','))

	// SPRINT 15 addition - Waiting on endpoint
	// OR Equipment_Id IN (SELECT convert(varchar, value) FROM string_split('$oracleCD', ','))

?>