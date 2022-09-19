<?php 
include ("../includes/functions.php");
include ("../db_conf.php");
include ("../data/emo_data.php");

//DECLARE
$RiskAndIssues_Key = $_GET['rikey'];

//ACTION PLANS TEMPORARY
$sql_act_plan = "select* from (select * from RI_Mgt.fn_GetListOfAllActionPlanStatusForRiskAndIssueKey($RiskAndIssues_Key)) a order by Created_Ts";
$stmt_act_plan = sqlsrv_query( $data_conn, $sql_act_plan );
// $row_act_plan = sqlsrv_fetch_array($stmt_risk_issue__assoc_proj, SQLSRV_FETCH_ASSOC);
// echo $row_act_plan['ActionPlanStatus]; 
// $sql_act_plan;
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>
	
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"> 
  <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">

<body style="font-family:Mulish, serif;">
<h3 align="center">ACTION PLAN HISTORY</h3>
<div id='dlist'></div> 
	<table class="table table-bordered table-striped table-hover" width="90%">
  <thead>
    <tr>
      <th width="10%">DATE</th>
      <th width="65%">ACTION PLAN</th>
      <!--<th>POC NAME</th>
      <th>POC GROUP</th>-->
    </tr>
</thead>
  <tbody>
  <?php while($row_act_plan = sqlsrv_fetch_array($stmt_act_plan, SQLSRV_FETCH_ASSOC)) { ?>
    <tr>
      <td><?php convtimex($row_act_plan['Min_Last_Update_Ts']); ?></td>
      <td><?php echo $row_act_plan['ActionPlanStatus_Cd']; ?></td>
      <!--
      <td><?php //echo $row_act_plan['POC_Nm']; ?></td>
      <td><?php //echo $row_act_plan['POC_Department']; ?></td>
  -->
    </tr>
  <?php } ?>
  </tbody>
</table>
</body>
</html>