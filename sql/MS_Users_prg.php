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
//echo $row_winuser_prg['User_UID'];

//DEBUG
//echo $sql_winuser_prg;

//GET MLM USER EMAILS
$sql_mlm = "DECLARE @mlmu VARCHAR(1000) 
					SELECT @mlmu = COALESCE(@mlmu+', ' ,'') + CAST(User_Email AS VARCHAR(1000)) 
					FROM [RI_MGT].[fn_GetListOfOwnersInfoForProgram]($fsclYear,'$program') 
					SELECT @mlmu AS User_Email";
$stmt_mlm  = sqlsrv_query( $data_conn, $sql_mlm);  
$row_mlm  = sqlsrv_fetch_array( $stmt_mlm, SQLSRV_FETCH_ASSOC);


// echo $sql_mlm . '<p>';
$excluded = array_map('strtolower', ["Robert.Plaskon@cox.com", "Andrea.SuiYuan@cox.com", "Kevin.Lam@cox.com"]);
$mlmEmailsArray = array_map('strtolower', array_map('trim', explode(',', $row_mlm['User_Email'])));
$mlmEmails = implode(',', array_diff($mlmEmailsArray, $excluded));

// echo $mlmEmails;
// exit();


?>
