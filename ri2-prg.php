<?php include ("includes/functions.php");?>
<?php include ("db_conf.php");?>
<?php include ("data/emo_data.php");?>
<?php include ("sql/MS_Users_prg.php");?>
<?php
								//DECLARE
                $uid = $_GET['uid'];
                $ri_region = $_GET['region'];
								$ri_program = $_GET['program'];
								$ri_fscl_yr = $_GET['fscl_year'];
                $ri_proj_nm = $_GET['proj_nm'];
								
								//OPEN PROGRAM RISK AND ISSUES 	
								$sql_risk_issue = "select distinct RI_Nm, ImpactLevel_Nm, Last_Update_Ts, RIDescription_Txt, RiskAndIssue_Key, RIType_Cd
                                    from
                                    (select * from [RI_MGT].[fn_GetListOfRiskAndIssuesForMLMProgram] ($ri_fscl_yr,'$ri_program')
                                    ) a
                                    ORDER BY RiskAndIssue_Key DESC";
								$stmt_risk_issue = sqlsrv_query( $data_conn, $sql_risk_issue );

                //CLOSED PROGRAM RISK AND ISSUES
                $sql_risk_issue_cls = "select distinct RI_Nm, RIType_Cd,RIDescription_Txt, RIClosed_Dt, Last_Update_Ts, RiskAndIssue_Key
                                      from(
                                      select * from RI_MGT.fn_GetListOfAllInactiveRiskAndIssue ('Program') 
                                      where Program_Nm = '$ri_program' and RIOpen_Flg = 0
                                      ) a
                                      order by RiskAndIssue_Key desc";
								$stmt_risk_issue_cls = sqlsrv_query( $data_conn, $sql_risk_issue_cls );
                //echo $sql_risk_issue_cls;
                //exit();

                //USER AUTHORIZATION
                $authUser = strtolower($windowsUser);
                $alias = "";
                  if(!empty($row_winuser_prg['User_UID'])) {
                  $alias = strtolower($row_winuser_prg['User_UID']);
                  }
                $tempID = uniqid();
								//$row_risk_issue = sqlsrv_fetch_array($stmt_risk_issue, SQLSRV_FETCH_ASSOC);
                //echo $sql_risk_issue;

//DEBUG
//echo $sql_risk_issue;
//echo $sql_risk_issue;
//exit;
						
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
<!-- DEBUG USERS -->
<!-- <div align="center" class="alert alert-success" style="font-size:10px;">
Logged in as: <?php echo $authUser; ?><br>
Program Manager is: <?php echo $alias; ?>
</div> -->

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
  <div align="center" class="alert alert-success"><b>OPEN RISK & ISSUES</b></div>
  <table width="98%" border="0" cellpadding="5" class="table table-bordered table-striped table-hover">
    <tbody>
    <tr>
      <th><strong>Program Risk or Issue Name</strong></th>
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
      <td align="center"><a href="risk-and-issues/details-prg.php?rikey=<?php echo $row_risk_issue['RiskAndIssue_Key'];?>&prg_nm=<?php echo $ri_program;?>&fscl_year=<?php echo $ri_fscl_yr;?>&proj_name=<?php echo $ri_proj_nm;?>&uid=<?php echo $uid; ?>&status=1&popup=false"><span class="glyphicon glyphicon-zoom-in" style="font-size:12px;"></span></a></td>
  </tr>
    <?php } ?>
  </tbody>
</table>
<?php } else { ?>
There are no Program Risk or Issues found
<?php }?>
</div>
<div align="center" class="alert alert-success"><b>CLOSED RISK & ISSUES</b></div>
<table width="98%" border="0" class="table table-bordered table-striped table-hover">
  <tbody>
    <tr cellpadding="5px">
      <th width="35%"><strong>Project Risk or Issue Name</strong></th>
      <th><strong>Type</strong></th>
      <th><strong>Description</strong></th>
      <th><strong>Closed Date</strong></th>
      <th><strong>Last Update</strong></th>
      <th align="center"><strong>Details</strong></th>
    </tr>
    <?php while ($row_risk_issue_cls = sqlsrv_fetch_array($stmt_risk_issue_cls, SQLSRV_FETCH_ASSOC)){ ?>
    <tr>
      <td><?php echo $row_risk_issue_cls['RI_Nm']; ?></td>
      <td><?php echo $row_risk_issue_cls['RIType_Cd']; ?></td>
      <td><?php echo $row_risk_issue_cls['RIDescription_Txt']; ?></td>
      <td><?php echo date_format($row_risk_issue_cls['RIClosed_Dt'], 'm-d-Y'); ?></td>
      <td><?php echo date_format($row_risk_issue_cls['Last_Update_Ts'], 'm-d-Y'); ?></td>
      <td align="center"><a href="risk-and-issues/details-prg.php?rikey=<?php echo $row_risk_issue_cls['RiskAndIssue_Key'];?>&prg_nm=<?php echo $ri_program;?>&fscl_year=<?php echo $ri_fscl_yr;?>&proj_name=<?php echo $ri_proj_nm;?>&uid=<?php echo $uid; ?>&status=0&popup=false"><span class="glyphicon glyphicon-zoom-in" style="font-size:12px;"></span></a></td>
    </tr>
    <?php } ?>
  </tbody>
</table>
</div>
</body>
</html>