<?php 
include ("../includes/functions.php");
include ("../db_conf.php");
include ("../data/emo_data.php");
include ("../sql/MS_Users.php");
include ("../sql/project_by_name.php");

//FIND PROJECT RISK AND ISSUES 1.26.2022
$RiskAndIssue_Key = $_GET['rikey'];
$fscl_year = $_GET['fscl_year'];
$proj_name = $_GET['proj_name']; 
$status = $_GET['status']; //0=closed , 1=open
$popup = $_GET['popup'];
  
$sql_risk_issue = "select * from RI_MGT.fn_GetListOfAllRiskAndIssue ($status)  where RiskAndIssue_Key = $RiskAndIssue_Key"; //NEED TO ADD ESTIMATED DATES TO THIS FUNCTION 2.27.2023
$stmt_risk_issue = sqlsrv_query( $data_conn, $sql_risk_issue );
$row_risk_issue = sqlsrv_fetch_array($stmt_risk_issue, SQLSRV_FETCH_ASSOC);
//echo $sql_risk_issue; //exit();
$ri_name = $row_risk_issue['RI_Nm'];
$riLog_Key = $row_risk_issue['RiskAndIssueLog_Key'];

//GET DRIVERS
$sql_risk_issue_driver = "select * from [RI_MGT].[fn_GetListOfDriversForRILogKey]($status) where RiskAndIssueLog_Key = $riLog_Key";
$stmt_risk_issue_driver = sqlsrv_query( $data_conn, $sql_risk_issue_driver );
$row_risk_issue_driver = sqlsrv_fetch_array($stmt_risk_issue_driver, SQLSRV_FETCH_ASSOC);
//echo $row_risk_issue_driver['Driver_Nm']; 
//echo $sql_risk_issue_driver; exit();

//GET ASSOCIATED PROJECTS
$sql_risk_issue_assoc_proj = "DECLARE @temp VARCHAR(MAX) 
                              SELECT @temp = COALESCE(@temp+'<br>' ,'') + Proj_Nm 
                              FROM RI_MGT.fn_GetListOfAssociatedProjectsForProjectRINm('$ri_name',$status)
                              SELECT @temp AS eps_projects";
$stmt_risk_issue_assoc_proj = sqlsrv_query( $data_conn, $sql_risk_issue_assoc_proj );
$row_risk_issue_assoc_proj = sqlsrv_fetch_array($stmt_risk_issue_assoc_proj, SQLSRV_FETCH_ASSOC);
//echo $sql_risk_issue_assoc_proj;

//COUNT ASSOCIATED PROJECTS
$sql_assoc_proj_cnt = "SELECT COUNT(*) AS AsscPrjCnt FROM RI_MGT.fn_GetListOfAssociatedProjectsForProjectRINm('$ri_name',$status)";
$stmt_assoc_proj_cnt = sqlsrv_query( $data_conn, $sql_assoc_proj_cnt );
$row_assoc_proj_cnt = sqlsrv_fetch_array($stmt_assoc_proj_cnt, SQLSRV_FETCH_ASSOC);
$assPrjCnt = $row_assoc_proj_cnt['AsscPrjCnt'];


// CHECK IF THE USER AND OWNER MATCH
//$authUser = trim($_GET['winuser']);
$alias = "";
if(!empty($row_winuser['CCI_Alias'])){
$alias = trim($row_winuser['CCI_Alias']);
}
//$tempID = uniqid();

$sql_authorize = "SELECT [CCI_Alias], [PROJ_OWNR_NM], [PROJ_NM], [PROJ_ID],[RI_MGT].[RiskandIssues_Users].[Username]
from [RI_MGT].[RiskandIssues_Users]
left join [EPS].[ProjectStage] on [PROJ_OWNR_NM] = [CCI_Alias]
Where [RI_MGT].[RiskandIssues_Users].[Username] = '$windowsUser' and [PROJ_NM] = '$proj_name'";

$stmt_authorize = sqlsrv_query( $data_conn, $sql_authorize );
$row_authorize = sqlsrv_fetch_array( $stmt_authorize, SQLSRV_FETCH_ASSOC);

$authorized = "";
if(!is_null($row_authorize)) {
$authorized = $row_authorize['PROJ_OWNR_NM'];
}
                
//ACCESS 
if($authorized != ''){ 
  $access = "true";
} else { 
  $access = "false";}

//DECLARE
$ri_id = $row_risk_issue['RiskAndIssue_Key'];
//$projectOwner = $row_projID['PROJ_OWNR_NM'];
$ri_owner = $row_risk_issue['LastUpdateBy_Nm'];
$name = trim($row_risk_issue['RI_Nm']);
$RILevel = $row_risk_issue['RILevel_Cd'];
$RIType = $row_risk_issue['RIType_Cd'];
$createdFrom  = "";
$programs = "";
$project_nm = $row_risk_issue['EPSProject_Nm'];
$descriptor  = $row_risk_issue['ScopeDescriptor_Txt'];
$description = $row_risk_issue['RIDescription_Txt'];
$regionx = "";
$Driversx = $row_risk_issue_driver['Driver_Nm'];
$impactArea2 = $row_risk_issue['ImpactArea_Nm'];
$impactLevel2 = $row_risk_issue['ImpactLevel_Nm'];
$riskProbability = $row_risk_issue['RiskProbability_Nm'];
$individual = $row_risk_issue['POC_Nm'];
$internalExternal = $row_risk_issue['POC_Nm'];
$responseStrategy2 = $row_risk_issue['ResponseStrategy_Nm'];
$date = $row_risk_issue['ForecastedResolution_Dt'];
$unknown = ""; // IF DATE IS EMPTY
$transProgMan = $row_risk_issue['TransferredPM_Flg'];
$opportunity = $row_risk_issue['Opportunity_Txt'];
$assocProject = $row_risk_issue_assoc_proj['eps_projects'];
$actionPlan = $row_risk_issue['ActionPlanStatus_Cd'];
$dateClosed = $row_risk_issue['RIClosed_Dt'];
$driver_list = "";
$ri_list = "";
$uaccess = $_GET['au'];
$status = $_GET['status'];
$department = $row_risk_issue['POC_Department'];
$raidLog = $row_risk_issue['RaidLog_Flg'];
$riskRealized_Raw = $row_risk_issue['RiskRealized_Flg'];
$crid = $row_risk_issue['AssociatedCR_Key'];
$changeLogActionVal = $row_risk_issue['RequestAction_Nm'];
$changeLogReason = $row_risk_issue['Reason_Txt'];
$EstActiveDate = $row_risk_issue['PRJI_Estimated_Act_Ts'];
$EstMigrateDate = $row_risk_issue['PRJI_Estimated_Mig_Ts'];
$uid = $_GET['uid'];

if($riskRealized_Raw == 1){
  $riskRealized = "Yes";
} else {
  $riskRealized = "No";
}

//LINK FOR DETAILS BUTTON
$link = urlencode($menu_root . "/risk-and-issues/details.php?au=true&rikey=" . $ri_id ."&fscl_year=" . $fscl_year . "&proj_name=" . $project_nm . "&status=1&popup=true&uid=" . $uid);

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?= $name ?></title>
</head>
	
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"> 
  <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">

<body style="font-family:Mulish, serif;">
<div id='dlist'></div> 
	<div align="center"><h3>PROJECT <?= strtoupper($RIType) ?> DETAILS</h3></div>
	<div align="center"><?= $name ?></div>
	<div style="padding: 10px" class="alert">  </div>

  <form action="confirm-do.php" method="post" name="confirmation" id="confirmation">
	<table class="table table-bordered table-striped table-hover" width="90%">
  <thead>
    <tr>
      <th>Field</th>
      <th>Value</th>
    </tr>
</thead>
  <tbody>  
  <tr>
      <td width="20%">ID</td>
      <td><?= $ri_id; ?></td>
  </tr>
  <tr>
      <td width="20%">Owner Name</td>
      <td><?= $ri_owner; ?></td>
  </tr>
  <tr>
      <td width="20%">Risk/Issue Name</td>
      <td><?= $name; ?></td>
    </tr>
<?php if(!empty($crid)){ ?>
    <tr>
      <td width="20%">Associated CR ID</td>
      <td><?= $crid; ?></td>
    </tr>
<?php } ?>
    <tr>
      <td width="20%">Type</td>
      <td><?= $RILevel . " " . $RIType; ?></td>
    </tr>
<?php if(isset($_POST['CreatedFrom'])) { ?>
    <tr>
      <td>Created From</td>
      <td><?= $createdFrom ; ?></td>
    </tr>
<?php } ?>
<?php if(isset($_POST['CreatedFrom'])) { ?>
    <tr>
      <td>Created From</td>
      <td><?= $createdFrom ; ?></td>
    </tr>
<?php } ?>
    <tr>
      <td>Project</td>
      <td><?= $project_nm ; ?></td>
    </tr>
    <tr>
      <td>Issue Descriptor</td>
      <td><?= $descriptor ; ?></td>
    </tr>
    <tr>
      <td>Description</td>
      <td><?= str_replace(["'", '"'], ['&#39;', '&quot;'], $description); ?></td>
    </tr>
    <!--<tr>
      <td>Region</td>
      <td><?php //echo $regionx; ?></td>
    </tr> -->
    <tr>
      <td>Drivers</td>
      <td>
        <?= $Driversx;?>
      </td>
    </tr>
    <tr>
      <td>Impact Area</td>
      <td><?= $impactArea2; ?></td>
    </tr>
    <tr>
      <td>Impact Level</td>
      <td><?= $impactLevel2; ?></td>
    </tr>
    <?php if(!empty($riskProbability)) {?>
    <tr>
      <td>Risk Probibility</td>
      <td><?= $riskProbability; ?></td>
    </tr>
    <?php } ?>
    <!--
    <tr>
      <td>Individual POC</td>
      <td><?php //echo $individual; ?></td>
    </tr>
    <tr>
      <td>Team POC</td>
      <td><?php //echo $department; ?></td>
    </tr>
    -->
    <tr>
      <td>Response Strategy</td>
      <td><?= $responseStrategy2; ?></td>
    </tr>
    <tr>
      <td>Forecasted Resolution Date</td>
      <td>
        <?php if(!empty($date) || $date != ""){ echo (convtimex($date)); } else { echo "Unknown"; } ?>
      </td>
    </tr>
<?php if(!empty($row_risk_issue['TransferredPM_Flg'])) { ?>
    <tr>
      <td>Tranfer to Program Manager</td>
      <td>
        Yes
    </td>
    </tr>
<?php } ?>

<?php if(!empty($row_risk_issue['Opportunity_Txt'])) { ?>
    <tr>
      <td>Opportunity</td>
      <td><?php $row_risk_issue['Opportunity_Txt']; ?>
    </td>
    </tr>
<?php } ?>
    <tr>
      <td>Associated Projects (<?= $assPrjCnt; ?>)   
        </td>
      <td>
        <?= $assocProject; 
        ?>
      </td>
    </tr>
    <tr>
      <td>Action Plan <a data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><span class="glyphicon glyphicon-calendar"></span></a></td>
      <td><?= str_replace(["'", '"'], ['&#39;', '&quot;'], $actionPlan); ?>

        <div class="collapse" id="collapseExample">
          <div class="well">
          <iframe id="actionPlan" src="action_plan.php?rikey=<?= $RiskAndIssue_Key?>" width="100%" frameBorder="0"></iframe>
          </div>
        </div>

    </td>
    </tr>
<?php if(!empty($changeLogActionVal)) { ?>
    <tr>
      <td>Change Log Action</td>
      <td><?= $changeLogActionVal; ?></td>
    </tr>
    <tr>
      <td>Change Log Reason</td>
      <td><?= $changeLogReason; ?></td>
    </tr>
<?php } ?>
<!-- POR SCHEDULE DATES -->
<?php if($changeLogActionVal == "POR Schedule Update") { ?>
    <tr>
      <td>Est. Activation Date</td>
      <td><?php convtimexNA($EstActiveDate)?></td>
    </tr>
    <tr>
      <td>Est. Migration Date</td>
      <td><?php convtimexNA($EstMigrateDate)?></td>
    </tr>
<?php } ?>
<!-- END -->
<?php if($RILevel == "Program") { ?>
    <tr>
      <td>Notify Porfolio Team</td>
      <td><?php 
      
      if($raidLog == 0){
        echo "No"; 
      } else {
        echo "Yes";
      }?>
    </td>
    </tr>
<?php } ?>
<?php if($RIType == "Risk"){ ?>
    <tr>
      <td width="20%">Risk Realized</td>
      <td><?= $riskRealized ; ?></td>
    </tr>
<?php } ?>
    <tr>
      <td>Date Closed</td>
      <td>
        <?php 
        if($dateClosed == "NULL") {
          echo "Open";
        } else { 
          convtimex($dateClosed);  
        }
        ?>
    </td>
    </tr>   
  </tbody>
</table>

<div align="center">
      <?php if($popup =="false"){?>
        <a href="javascript:void(0);" onclick="javascript:history.go(-1)" class="btn btn-primary"><span class="glyphicon glyphicon-step-backward"></span> Back </a>
      <?php } ?>
        <?php if($access=="true"){?>  
          <?php if($status == 1){ ?>
            <a href="includes/associated_prj_update.php?ri_level=prj&fscl_year=<?= $fscl_year?>&name=<?= urlencode($name)?>&proj_name=<?= $project_nm?>&ri_type=<?= $RIType ?>&rikey=<?= $RiskAndIssue_Key?>&status=<?= $status ?>"  class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span> Update </a>

            <?php 
$desc = (strlen($description) > 100) ? substr($description, 0, 100) . "[...]" : $description;
$act = (strlen($actionPlan) > 100) ? substr($actionPlan, 0, 100) . "[...]" : $actionPlan;
?>

            <a href="mailto:?subject=RISKS AND ISSUES - <?= $name;?>
            &body=%0D%0A----------------------------------------RISKS AND ISSUES DETAILS ----------------------------------------
            %0D%0AID: <?= $ri_id;?>
            %0D%0AOwner Name: <?= $ri_owner;?>
            %0D%0AName: <?= $name;?>
            %0D%0AType: <?= $RILevel . " " . $RIType?>
            %0D%0AProject: <?= $project_nm?>
            %0D%0ADescriptor: <?= $descriptor ?>
            %0D%0ADescription: <?= $desc?>
            %0D%0ADriver: <?= $Driversx?>
            %0D%0AImpact Area: <?= $impactArea2?>
            %0D%0AImpact Level: <?= $impactLevel2?>
            %0D%0AResponse Strategy: <?= $responseStrategy2?>
            %0D%0AForecasted Resolution Date: <?php if(!empty($date) || $date != ""){ echo (convtimex($date)); } else { echo "Unknown"; }?>
            %0D%0AAssociated Project(s): <?= str_replace("<br>", ", ", $assocProject)?>
            %0D%0AAction Plan: <?= $act?>
            %0D%0ADate Closed: <?php convtimex($dateClosed)?>
            %0D%0ALink: <?= $link;?>"
            class="btn btn-primary"><span class="glyphicon glyphicon-envelope"></span> Email </a>

            <span style="font-size: 24px;"> | </span> 

          <a href="includes/associated_prj_manage.php?ri_level=prj&fiscal_year=<?= $fscl_year;?>&name=<?= $row_risk_issue['RI_Nm'];?>&proj_name=<?= $proj_name;?>&ri_type=<?= $row_risk_issue['RIType_Cd'];?>&rikey=<?= $row_risk_issue['RiskAndIssue_Key']; ?>&status=1&uid=<?= $uid;?>&action=update&inc=<?= $row_risk_issue['RIIncrement_Num']; ?>" title="Add Project Association"><span class="btn btn-primary">+</span></a>
          <a href="includes/associated_prj_manage_remove.php?ri_level=prj&fiscal_year=<?= $fscl_year;?>&name=<?= $row_risk_issue['RI_Nm'];?>&proj_name=<?= $proj_name;?>&ri_type=<?= $row_risk_issue['RIType_Cd'];?>&rikey=<?= $row_risk_issue['RiskAndIssue_Key']; ?>&status=1&uid=<?= $uid;?>&action=update&inc=<?= $row_risk_issue['RIIncrement_Num']; ?>" title="Remove Project Association"><span class="btn btn-primary">-</span></a>
        <?php } ?>
      <?php } ?>
    </div>
  </form>
</div>
</body>
</html>