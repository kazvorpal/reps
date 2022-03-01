<?php include ("includes/functions.php");?>
<?php include ("db_conf.php");?>
<?php include ("data/emo_data.php");?>
<?php include ("sql/MS_Users.php");?>
<?php include ("sql/project_by_id.php");?>
<?php
								//FIND PROJECT RISK AND ISSUES FUNCTION 1.26.2022
                $uid = $_GET['uid'];
                $proj_name = $_GET['prj_name'];
                $fscl_year = $_GET['fscl_year'];
									
								$sql_risk_issue = "select distinct RI_Nm, ImpactLevel_Nm, Last_Update_Ts, RIDescription_Txt, RiskAndIssue_Key, RIType_Cd
                from
                (select * from [RI_MGT].[fn_GetListOfRiskAndIssuesForProject] ($fscl_year,'$proj_name')
                ) a";
								$stmt_risk_issue = sqlsrv_query( $data_conn, $sql_risk_issue );
								// echo $row_risk_issue['Risk_Issue_Name']; 			
								
                // CHECK IF THE USER AND OWNER MATCH
                $ri_count = $_GET['count'];	//COUNTS ARE CURRENTLY WRONG. THIS WILL BE FIXED WHEN AVI ADDS THE COUNTS TO THE DPR		
                $authUser = trim($_GET['winuser']);
                $alias = trim($row_winuser['CCI_Alias']);
                $tempID = uniqid();
                $projectOwner = $row_projID['PROJ_OWNR_NM'];

                $sql_authorize = "SELECT [CCI_Alias], [PROJ_OWNR_NM], [PROJ_NM], [PROJ_ID],[EPS].[RiskandIssues_Users].[Username]
                from [EPS].[RiskandIssues_Users]
                left join [EPS].[ProjectStage] on [PROJ_OWNR_NM] = [CCI_Alias]
                Where [RiskandIssues_Users].[Username] = '$windowsUser'and [PROJ_ID] = '$projID'";
								$stmt_authorize = sqlsrv_query( $data_conn, $sql_authorize );
                $row_authorize = sqlsrv_fetch_array( $stmt_authorize, SQLSRV_FETCH_ASSOC);

                $authorized = $row_authorize['PROJ_OWNR_NM'];
                
                //PRINT USER SQL TO SCREEN FOR DEBUG
                //echo $sql_authorize;
                
 ?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Project Risk or Issue</title>
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
    <link href='http://fonts.googleapis.com/css?family=Mulish' rel='stylesheet' type='text/css'>
</head>
<body style="font-family:Mulish, serif;">

<h3 align="center">PROJECT RISKS & ISSUES</h3>

<!--<div align="center" class="alert alert-success" style="font-size:10px;">
<h5>FOR DEV ONLY</h5>
CC-Alias from users table <?php echo $alias; //from users table?><br>
User from Querystring: <?php echo $authUser; //from querystring?><br>
Windows Username: <?php echo $windowsUser; //try joining to project?><br>
Project Owner from Project Table: <?php echo $projectOwner; //from Project Table?><br>
ProjectID: <?php echo $projID?>

<?php if($authorized != ''){ 
    echo "<br>Match: True";
  } else { 
    echo "<br>Match: False";}?><br>
</div>
-->
<div align="center">
  
<?php if($authorized != ''){  ?> 
  
  <div style="padding:5px;">
    <a href="risk-and-issues/includes/associated_prj.php?uid=<?php echo $uid?>&ri_level=prj&ri_type=risk&action=new&fiscal_year=<?php echo $_GET['fscl_year']?>&tempid=<?php echo $tempID?>" title="Risk and Issues"><span class="btn btn-primary">CREATE PROJECT RISK</span></a>
    <!--<a href="risk-and-issues/project-risk.php?uid=<?php echo $uid?>&ri_type=risk&action=new&fiscal_year=<?php echo $_GET['fscl_year']?>&tempid=<?php echo $tempID?>" title="Risk and Issues"><span class="btn btn-primary">Create Project Risk</span></a> -->
    <a href="risk-and-issues/includes/associated_prj.php?uid=<?php echo $uid?>&ri_level=prj&ri_type=issue&action=new&fiscal_year=<?php echo $_GET['fscl_year']?>&tempid=<?php echo $tempID?>" title="Risk and Issues"><span class="btn btn-primary">CREATE PROJECT ISSUE</span></a>
  </div>

<?php } else {?>

  <div style="padding:5px;">
    <button class="btn btn-primary" disabled>Create Project Risk</button>
    <button class="btn btn-primary" disabled>Create Project Issue</button>
  </div>
<?php } ?>

<br>
<?php if($_GET['count'] == 0){ //TURNED OFF.  SHOULD BE != 0 ?>
<table width="98%" border="0" class="table table-bordered table-striped table-hover">
  <tbody>
    <tr cellpadding="5px">
      <th><strong>Project Risk or Issue Name</strong></th>
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
      <td align="center"><a href="risk-and-issues/details.php?rikey=<?php echo $row_risk_issue['RiskAndIssue_Key'];?>&fscl_year=<?php echo $fscl_year;?>&proj_name=<?php echo $proj_name;?>"><span class="glyphicon glyphicon-eye-open" style="font-size:12px;"></span></a></td>
    </tr>
    <?php } ?>
  </tbody>
</table>
<?php } else { ?>
There are no Project Risk or Issues found
<?php }?>
</div>
</body>
</html>