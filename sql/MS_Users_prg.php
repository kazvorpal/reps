<?php 
//REQIREMENTS
	//GET program
	//GET fisl_year
	//$_SERVER["AUTH_USER"] => windows login name

//DECLARE
$windowsUser = preg_replace("/^.+\\\\/", "", $_SERVER["AUTH_USER"]);

if(!empty($_GET['program'])){
		$program = $_GET['program'];
	} else {
		$program = $_POST['program'];
	}

if(!empty($_GET['fscl_year'])){
		$fsclYear = $_GET['fscl_year'];
	} else {
		$fsclYear = $_POST['fiscalYer'];
	}

//PROGRAM OWNERS //Function changed on 4.4.2023
$sql_winuser_prg = "SELECT *
FROM (
	SELECT * 
	FROM [RI_MGT].[fn_GetListOfMLMProgramAccessforUserUID]('$windowsUser',$fsclYear)
) a
WHERE Program_Nm = '$program'";
$stmt_winuser_prg = sqlsrv_query( $data_conn, $sql_winuser_prg ); 
$row_winuser_prg = sqlsrv_fetch_array( $stmt_winuser_prg, SQLSRV_FETCH_ASSOC);
//$row_winuser_prg['User_UID']

//DEBUG
//echo $sql_winuser_prg;
?>