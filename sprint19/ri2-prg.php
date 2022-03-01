<?php include ("includes/functions.php");?>
<?php include ("db_conf.php");?>
<?php include ("data/emo_data.php");?>
<?php include ("sql/MS_Users_prg.php");?>
<?php
								$uid = $_GET['uid'];
                $ri_region = $_GET['region'];
								$ri_program = $_GET['program'];
								$ri_fscl_yr = $_GET['fscl_year'];
                $ri_proj_nm = $_GET['proj_nm'];
								
								//FIND PROJECT RISK AND ISSUES FUNCTION 1.26.2022				
								$sql_risk_issue = "select distinct RI_Nm, ImpactLevel_Nm, Last_Update_Ts, RIDescription_Txt, RiskAndIssue_Key, RIType_Cd
                from
                (select * from [RI_MGT].[fn_GetListOfRiskAndIssuesForProgram] ($ri_fscl_yr,'$ri_program')
                ) a";
								$stmt_risk_issue = sqlsrv_query( $conn_COX_QA, $sql_risk_issue );
                
                //$sql_risk_issue = "SELECT Program, Created, [Disposition Status], ID, [Risk / Issue Name], [Fiscal Year]
													//FROM [COX_QA].[EPS].[RiskAndIssues]
													//WHERE [Program] LIKE '%$ri_program%' 
													//AND [Risk or Issue Level] = 'Program'
													//AND [Region] LIKE '%$ri_region%' 
													//AND [Fiscal Year] = '$ri_fscl_yr'
													//AND ([Status] = 'New' 
													//OR [Status] = 'Assigned'
													//OR [Status] = 'Under Review') ";
								//$stmt_risk_issue = sqlsrv_query( $conn_COX_QA, $sql_risk_issue );

                $authUser = $windowsUser;
                $alias = $row_winuser_prg['User_UID'];
                $tempID = uniqid();
								//$row_risk_issue = sqlsrv_fetch_array($stmt_risk_issue, SQLSRV_FETCH_ASSOC);
//								echo $sql_risk_issue;
//								exit;
						
 ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
<link href='http://fonts.googleapis.com/css?family=Mulish' rel='stylesheet' type='text/css'>
</head>
<body style="font-family:Mulish, serif;">
<?php // echo $ri_program . '</br>' . $ri_region ?>
<h3 align="center">PROGRAM RISKS & ISSUES</h3>
<!--
<div align="center" class="alert alert-success" style="font-size:10px;">
Logged in as: <?php echo $authUser; ?><br>
Project Owner is: <?php echo $alias; ?>
</div>
-->
<div align="center">
<div align="center">

<?php if($alias == $authUser){ ?> 
<div style="padding:5px;">
  <a href="risk-and-issues/includes/associated_prj.php?uid=<?php echo $uid?>&fiscal_year=<?php echo $ri_fscl_yr?>&ri_type=risk&ri_level=prg&action=new&tempid=<?php echo $tempID?>&proj_name<?php echo $ri_proj_nm;?>&action=new" title="Risk and Issues"><span class="btn btn-primary">CREATE PROGRAM RISK</span></a>
  <a href="risk-and-issues/includes/associated_prj.php?uid=<?php echo $uid?>&fiscal_year=<?php echo $ri_fscl_yr?>&ri_type=issue&ri_level=prg&action=new&tempid=<?php echo $tempID?>&proj_name<?php echo $ri_proj_nm;?>&action=new" title="Risk and Issues"><span class="btn btn-primary">CREATE PROGRAM ISSUE</span></a>
</div>
<?php } else { ?>
  <div style="padding:5px;">
    <button class="btn btn-primary" disabled>CREATE PROGRAM RISK</button>
    <button class="btn btn-primary" disabled>CREATE PROGRAM ISSUE</button>
  </div>
<?php } ?>
<br>
<?php if($_GET['count'] == 0){ //TURNED OFF.  SHOULD BE != 0 ?>
  <table width="98%" border="0" cellpadding="5" class="table table-bordered table-striped table-hover">
    <tbody>
    <tr>
      <th><strong>Name</strong></th>
      <th><strong>Type</strong></th>
      <th><strong>Description</strong></th>
      <th><strong>Impact</strong></th>
      <th><strong>Created On</strong></th>
      <th align="center"><strong>Details</strong></th>
    </tr>
    <?php while ($row_risk_issue = sqlsrv_fetch_array($stmt_risk_issue, SQLSRV_FETCH_ASSOC)){ ?>
      <tr>
      <td><?php echo $row_risk_issue['RI_Nm']; ?></td>
      <td><?php echo $row_risk_issue['RIType_Cd']; ?></td>
      <td><?php echo $row_risk_issue['RIDescription_Txt']; ?></td>
      <td><?php echo $row_risk_issue['ImpactLevel_Nm']; ?></td>
      <td><?php echo date_format($row_risk_issue['Last_Update_Ts'], 'm-d-Y'); ?></td>
      <td align="center"><a href="risk-and-issues/details-prg.php?rikey=<?php echo $row_risk_issue['RiskAndIssue_Key'];?>&prg_nm=<?php echo $ri_program;?>&fscl_year=<?php echo $ri_fscl_yr;?>&proj_name=<?php echo $ri_proj_nm;?>"><span class="glyphicon glyphicon-zoom-in" style="font-size:12px;"></span></a></td>
  </tr>
    <?php } ?>
  </tbody>
</table>
<?php } else { ?>
There are no Program Risk or Issues found
<?php }?>
</div>
<p>&nbsp;</p>
</body>
</html>