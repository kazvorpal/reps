<?php 
// FUNCTON get trueUp Capex
function  truCPX($trueUp_Cap, $year, $fundingkey) {
	
	include ("../db_conf.php");
	include ("../data/emo_data.php");
	
	 	$crcapexkey = $trueUp_Cap;
		$yearx = $year;
		$fundingkeyx = $fundingkey;
 		$sql_capex = "
					Select top 1 CR_Key, capex
					from dbo.fn_GetCR_All($yearx,$fundingkeyx) 
					UNPIVOT 
					(capex FOR cr_keyx IN 
						(TrueUp_Capex_Jan,
						TrueUp_Capex_Feb,
						TrueUp_Capex_Mar,
						TrueUp_Capex_Apr,
						TrueUp_Capex_Mai,
						TrueUp_Capex_Jun,
						TrueUp_Capex_Jul,
						TrueUp_Capex_Aug,
						TrueUp_Capex_Sep,
						TrueUp_Capex_Oct,
						TrueUp_Capex_Nov,
						TrueUp_Capex_Dec)) a
						where CR_Key = '$crcapexkey' and capex <> 0.00
				 ";
	$stmt_capex = sqlsrv_query( $conn_COXProd, $sql_capex );
	$row_capex = sqlsrv_fetch_array( $stmt_capex, SQLSRV_FETCH_ASSOC);
	
	echo number_format($row_capex['capex'],2);
}


// FUNCTION get trueUp Opex
function  truOPX($trueUp_Op, $year, $fundingkey) {
	
	include ("../db_conf.php");
	include ("../data/emo_data.php");
	
		$cropexkey = $trueUp_Op;
		$yearx = $year;
		$fundingkeyx = $fundingkey;
 		$sql_opex= "
					Select top 1 CR_Key, capex
					from dbo.fn_GetCR_All($year,$fundingkeyx) 
					UNPIVOT 
					(capex FOR cr_keyx IN 
						(TrueUp_Opex_Jan,
						TrueUp_Opex_Feb,
						TrueUp_Opex_Mar,
						TrueUp_Opex_Apr,
						TrueUp_Opex_Mai,
						TrueUp_Opex_Jun,
						TrueUp_Opex_Jul,
						TrueUp_Opex_Aug,
						TrueUp_Opex_Sep,
						TrueUp_Opex_Oct,
						TrueUp_Opex_Nov,
						TrueUp_Opex_Dec)) a
						where CR_Key = '$cropexkey' and capex <> 0.00
				 ";
	$stmt_opex = sqlsrv_query( $conn_COXProd, $sql_opex );
	$row_opex = sqlsrv_fetch_array( $stmt_opex, SQLSRV_FETCH_ASSOC);
	echo number_format($row_opex['capex'],2);
	
	
}
?>