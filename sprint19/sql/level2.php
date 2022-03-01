<?php 
// All l2 data 

// Posted UID in url
$pro_uid = $_GET['uid'];

// Posted Levels
$levels = '0, 1';

	if(isset($_POST['levels'])) {
		$levels = $_POST['levels'];
	} else {
		$levels = '0, 1';
	}

$sql_l2 = "SELECT *
			FROM [ODS].[EPS].[TASKS_STG] 
			WHERE PROJ_ID = '$pro_uid'
			AND TASK_OTLN_LVL in ($levels)
			order by [ODS].[EPS].[TASKS_STG].[TASK_WBS],TASK_IDX";
$stmt_l2 = sqlsrv_query( $conn_COXProd, $sql_l2 );

//echo $row_reg_clsp['PROJ_ID']
?>
