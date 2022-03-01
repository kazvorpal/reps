<?php include ("../../includes/functions.php");?>
<?php include ("../../db_conf.php");?>
<?php include ("../../data/emo_data.php");?>
<?php // include ("../sql/collapse.php");?>
<?php //include ("../../sql/project_by_id.php");?>
<?php //include ("../../sql/ri_filter_vars.php");?>
<?php //include ("../../sql/ri_filters.php");?>
<?php //include ("../../sql/ri_filtered_data.php");?>
<?php //include ("../../sql/RI_Internal_External.php");?>

<?php 
//echo $_SERVER['HTTP_REFERER'];
//exit();

$RI_tempID = $_POST['tempID'];
$RI_user = $_POST['user'];

//  REPLACE SPECIAL CHARACTERS IN PLAN
$RI_plan_raw = $_POST['ActionPlan'];
$RI_plan_clean = str_replace("'", "''", $RI_plan_raw); //REPLACES APOSTROPHE
$RI_plan = $RI_plan_clean;

    //echo $RI_plan;
    //echo $RI_tempID;
    //echo$RI_user;
    //exit();

$sql_plans = "INSERT INTO RI_MGT.ActionPlanStatus_Log([Temp_ID],[User],[Update]) VALUES ('$RI_tempID','$RI_user','$RI_plan');";
//$stmt_por = sqlsrv_query( $conn_COXProd, $sql_por ); // Live Connection
//$stmt_plans = sqlsrv_query( $conn_COX_QA, $sql_plans );  // QA Connection
$stmt_plans = sqlsrv_query( $conn, $sql_plans );  // DEV Connection
//$row_plans = sqlsrv_fetch_array( $stmt_plans, SQLSRV_FETCH_ASSOC) 
//$row_plans['columnname']

//$sql_add_plans = "";
//$stmt_por_cnt = sqlsrv_query( $conn_COXProd, $sql_por_cnt ); // Live connection
//$stmt_por_cnt = sqlsrv_query( $conn_COX_QA, $sql_por_cnt ); //QA Connection
//$stmt_por_cnt = sqlsrv_query( $conn, $sql_por_cnt ); //DEV Connection

//$row_da_count = sqlsrv_fetch_array( $stmt_por_cnt, SQLSRV_FETCH_ASSOC) 
//$row_da_count['daCount']

//echo $sql_plans;
//exit();

header("Location: " .  $_SERVER['HTTP_REFERER']);

?>