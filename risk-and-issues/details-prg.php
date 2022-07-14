<?php 
include ("../includes/functions.php");
include ("../db_conf.php");
include ("../data/emo_data.php");
include ("../sql/MS_Users_prg.php");

//FIND PROJECT RISK AND ISSUES
$RiskAndIssue_Key = $_GET['rikey'];
$fscl_year = $_GET['fscl_year'];
$proj_name = $_GET['proj_name'];
$prog_name = $_GET['program'];

if(isset($_GET['uid'])) {
$uid = $_GET['uid'];
}

$status = $_GET['status']; //0=closed , 1=open
$popup = $_GET['popup'];
$au = $_GET['au'];
  
$sql_risk_issue = "select * from RI_Mgt.fn_GetListOfAllRiskAndIssue($status) where RIlevel_Cd = 'Program' and RiskAndIssue_Key = $RiskAndIssue_Key";
$stmt_risk_issue = sqlsrv_query( $data_conn, $sql_risk_issue );
$row_risk_issue = sqlsrv_fetch_array($stmt_risk_issue, SQLSRV_FETCH_ASSOC);
//echo $row_risk_issue['Risk_Issue_Name']; 	
//echo $sql_risk_issue . "<br><br>";
//exit();		

//GET DISTINCT REGIONS FOR UPDATE
$sql_risk_issue_regions_up = "select * from RI_Mgt.fn_GetListOfAllRiskAndIssue($status) where RIlevel_Cd = 'Program' and RiskAndIssue_Key = $RiskAndIssue_Key";
$stmt_risk_issue_regions_up = sqlsrv_query( $data_conn, $sql_risk_issue_regions_up);
//$row_risk_issue_regions_up  = sqlsrv_fetch_array($stmt_risk_issue_regions_up , SQLSRV_FETCH_ASSOC);
//echo $row_risk_issue_regions_up['Risk_Issue_Name']; 			
//echo $sql_risk_issue_regions_up;

//GET DISTINCT REGIONS
$sql_risk_issue_regions = "select * from RI_Mgt.fn_GetListOfAllRiskAndIssue($status) where RIlevel_Cd = 'Program' and RiskAndIssue_Key = $RiskAndIssue_Key";
$stmt_risk_issue_regions  = sqlsrv_query( $data_conn, $sql_risk_issue_regions);
//$row_risk_issue_regions  = sqlsrv_fetch_array($stmt_risk_issue_drivers , SQLSRV_FETCH_ASSOC);
//echo $row_risk_issue_regions['Risk_Issue_Name']; 			
//echo $sql_risk_issue_regions . "<BR><BR>";

//GET ASSOCIATED PROJECTS
//FIRST GET THE PROGRAM RI KEY
//$ri_name = $row_risk_issue['RI_Nm'];
$sql_progRIkey = "select * from RI_Mgt.fn_GetListOfAllRiskAndIssue($status) where RIlevel_Cd = 'Program' and RiskAndIssue_Key = $RiskAndIssue_Key";
$stmt_progRIkey  = sqlsrv_query( $data_conn, $sql_progRIkey  );
$row_progRIkey  = sqlsrv_fetch_array($stmt_progRIkey , SQLSRV_FETCH_ASSOC);
//echo $sql_progRIkey;
//exit();
$progRIkey = $row_progRIkey ['MLMProgramRI_Key']; 
$programKey = $row_progRIkey ['MLMProgram_Key'];
$riLog_Key =  $row_progRIkey ['RiskAndIssueLog_Key'];

//echo $sql_progRIkey . "<br><br>";

//GET DISTINCT DRIVERS FOR UPDATE 
$sql_risk_issue_drivers_up = "select * from [RI_MGT].[fn_GetListOfDriversForRILogKey]($status) WHERE RiskAndIssueLog_Key = $riLog_Key";
$stmt_risk_issue_drivers_up  = sqlsrv_query( $data_conn, $sql_risk_issue_drivers_up);
//$row_risk_issue_drivers_up  = sqlsrv_fetch_array($stmt_risk_issue_drivers_up , SQLSRV_FETCH_ASSOC);
//echo $row_risk_issue_up['Risk_Issue_Name']; 			
//echo $sql_risk_issue_drivers_up;
//exit();

//GET DISTINCT DRIVERS
$sql_risk_issue_drivers = "select * from [RI_MGT].[fn_GetListOfDriversForRILogKey]($status) WHERE RiskAndIssueLog_Key = $riLog_Key";
$stmt_risk_issue_drivers  = sqlsrv_query( $data_conn, $sql_risk_issue_drivers);
//$row_risk_issue_drivers  = sqlsrv_fetch_array($stmt_risk_issue_drivers , SQLSRV_FETCH_ASSOC);
//echo $row_risk_issue['Risk_Issue_Name']; 			
//echo $sql_risk_issue_drivers  . "<br><br>";
//exit();

//GET THE ASSOCIATED PROJECTS USING THE PROGRAMRI_KEY
$sql_risk_issue_assoc_proj = "select * from RI_Mgt.fn_GetListOfAssociatedProjectsForProgramRIKey($RiskAndIssue_Key,$progRIkey,$status)";
$stmt_risk_issue_assoc_proj = sqlsrv_query( $data_conn, $sql_risk_issue_assoc_proj );
//$row_risk_issue_assoc_proj = sqlsrv_fetch_array($stmt_risk_issue__assoc_proj, SQLSRV_FETCH_ASSOC);
//echo $row_risk_issue_assoc_proj['ProgramRI_Key'];
//echo $sql_risk_issue_assoc_proj; 

//USER AUTHORIZATION
$authUser = strtolower($windowsUser);
$alias = "";
  if(!empty($row_winuser_prg['User_UID'])) {
  $alias = strtolower($row_winuser_prg['User_UID']);
  }
$tempID = uniqid();
//$row_risk_issue = sqlsrv_fetch_array($stmt_risk_issue, SQLSRV_FETCH_ASSOC);
//echo $sql_risk_issue;

$uaccess = "false";
if($alias == $authUser){
  $uaccess = "true";
} 

//exit;
//DECLARE
$name = $row_risk_issue['RI_Nm'];
$RILevel = "";
$RIType = $row_risk_issue['RIType_Cd'];
$createdFrom  = "";
$programs = $row_risk_issue['MLMProgram_Nm'];
$prject_nm = "";
$descriptor  = $row_risk_issue['ScopeDescriptor_Txt'];
$description = $row_risk_issue['RIDescription_Txt'];
$regionx = "";
$Driversx = "<div id='driversx'></div>";//$row_risk_issue['Driver_Nm'];
$impactArea2 = $row_risk_issue['ImpactArea_Nm'];
$impactLevel2 = $row_risk_issue['ImpactLevel_Nm'];
$individual = $row_risk_issue['POC_Nm'];
$internalExternal = $row_risk_issue['POC_Department'];
$responseStrategy2 = $row_risk_issue['ResponseStrategy_Nm'];
$unknown = ""; // IF DATE IS EMPTY
$date = $row_risk_issue['ForecastedResolution_Dt'];
$transProgMan = $row_risk_issue['TransferredPM_Flg'];
$opportunity = $row_risk_issue['Opportunity_Txt'];
$assocProject = "";
$actionPlan = $row_risk_issue['ActionPlanStatus_Cd'];
$formaction =  "update"; 

$raidLogx = $row_risk_issue['RaidLog_Flg'];
if($raidLogx == 1) {$raidLog =  "Yes";}
if($raidLogx == 0) {$raidLog = "No";}

$department = $row_risk_issue['POC_Department'];

if(!empty($row_risk_issue['RIClosed_Dt'])){
$dateClosed = date_format($row_risk_issue['RIClosed_Dt'], 'Y-m-d');
} else {
$dateClosed = "";
}
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

<body style="font-family:Mulish, serif;" onload="copyDiv()">
	<div align="center"><h3>PROGRAM RISKS & ISSUES DETAILS</h3></div>
	<div align="center"><?php echo $name ?></div>
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
      <td width="20%">Risk/Issue Name</td>
      <td><?php echo $name; ?></td>
    </tr>
    <tr>
      <td width="20%">Type</td>
      <td><?php echo $RILevel . " " . $RIType; ?></td>
    </tr>
<?php if(isset($_POST['CreatedFrom'])) { ?>
    <tr>
      <td>Created From</td>
      <td><?php echo $createdFrom ; ?></td>
    </tr>
<?php } ?>
    <tr>
      <td>Program</td>
      <td><?php echo $programs ; ?></td>
    </tr>
    <tr>
      <td>Region(s)</td>
      <td>
      <?php 
        while ($row_risk_issue_regions  = sqlsrv_fetch_array($stmt_risk_issue_regions , SQLSRV_FETCH_ASSOC)) {
        echo $row_risk_issue_regions['MLMRegion_Cd'] . '<br>';
        }
        ?>
      </td>
    </tr>
    <tr>
      <td>Descriptor</td>
      <td><?php echo $descriptor ; ?></td>
    </tr>
    <tr>
      <td>Description</td>
      <td><?php echo $description; ?></td>
    </tr>
    <tr>
      <td>Drivers</td>
      <td><div id="drivers">
        <?php 
        while ($row_risk_issue_drivers  = sqlsrv_fetch_array($stmt_risk_issue_drivers , SQLSRV_FETCH_ASSOC)) {
        echo $row_risk_issue_drivers['Driver_Nm'] . '<br>';
        }
        ?></div>
      </td>
    </tr>
    <tr>
      <td>Impact Area</td>
      <td><?php echo $impactArea2; ?></td>
    </tr>
    <tr>
      <td>Impact Level</td>
      <td><?php echo $impactLevel2; ?></td>
    </tr>
    <tr>
      <td>Individua POC</td>
      <td><?php echo $individual; ?></td>
    </tr>
    <tr>
      <td>Team POC</td>
      <td><?php echo $department; ?></td>
    </tr>
      <tr>
      <td>Response Strategy</td>
      <td><?php echo $responseStrategy2; ?></td>
    </tr>
    <tr>
      <td>Notify Portfolio Team</td>
      <td><?php echo $raidLog; ?></td>
    </tr>
    <tr>
      <td>Forecasted Resolution Date</td>
      <td>
        <?php if($unknown == "off"){
        echo $date; 
        } else {
        echo "Unknown";
        }
        ?>
        </td>
    </tr>
<?php if(!empty($_POST['TransfertoProgramManager'])) { ?>
    <tr>
      <td>Tranfer to Program Manager</td>
      <td>
        Yes
    </td>
    </tr>
<?php } ?>
<?php if(isset($_POST['opportunity'])) { ?>
    <tr>
      <td>Opportunity</td>
      <td><?php $_POST['opportunity']; ?>
    </td>
    </tr>
<?php } ?>
    <tr>
      <td>Associated Projects ()</td>
      <td>
        <?php 
        while ($row_risk_issue_assoc_proj = sqlsrv_fetch_array($stmt_risk_issue_assoc_proj, SQLSRV_FETCH_ASSOC)) {
        echo $row_risk_issue_assoc_proj['EPSProject_Nm'] . "<br>"; 
        }
        ?>
      </td>
    </tr>
    <tr>
      <td>Action Plan <a data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><span class="glyphicon glyphicon-calendar"></span></a></td>
      <td><?php echo $actionPlan; ?>
        <div class="collapse" id="collapseExample">
          <div class="well">
          <iframe id="actionPlan" src="action_plan.php?rikey=<?php echo $RiskAndIssue_Key?>" width="100%" frameBorder="0"></iframe>
          </div>
        </div>
    </td>
    </tr>
    <tr>
      <td>Date Closed</td>
      <td>
        <?php echo $dateClosed; ?>
    </td>
    </tr>
  </tbody>
</table>
<div align="center">
<?php if($alias == $authUser){ ?> 
<?php if($RIType == "Risk") { $formType = "program-risk-update.php";} else {$formType = "program-issue-update.php";} ?>
    <a href="javascript:void(0);" onclick="javascript:history.go(-1)" class="btn btn-primary"><span class="glyphicon glyphicon-step-backward"></span> Back </a>
<?php if($au == "true")  {?>  
<?php if($status == 1){ ?>
    <a href="<?php echo $formType ?>?action=update&status=1&ri_level=prg&fscl_year=<?php echo $fscl_year?>&name=<?php echo $name?>&ri_type=<?php echo $RIType ?>&rikey=<?php echo $RiskAndIssue_Key?>&progRIkey=<?php echo $progRIkey;?>&progkey=<?php echo $programKey;?>&progname=<?php echo $prog_name ?>&projname=<?php echo $proj_name;?>&uid=<?php echo $uid ;?>&drivertime=<?php 
        while ($row_risk_issue_drivers_up  = sqlsrv_fetch_array($stmt_risk_issue_drivers_up , SQLSRV_FETCH_ASSOC)) {
        echo $row_risk_issue_drivers_up ['Driver_Nm'] . ',';
        }
        ?>&regions=<?php 
        while ($row_risk_issue_regions_up  = sqlsrv_fetch_array($stmt_risk_issue_regions_up , SQLSRV_FETCH_ASSOC)) {
        echo $row_risk_issue_regions_up['MLMRegion_Cd'] . ',';
        }
        ?>"  class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span> Update </a>
    <a href="mailto:?subject=RISKS AND ISSUES - <?php echo $name;?>
      &body=%0D%0A----------------------------------------RISKS AND ISSUES DETAILS ----------------------------------------
      %0D%0ARisk/Issue Name: <?php echo $name;?>
      %0D%0AType: <?php echo $RIType?>
      %0D%0AProject: <?php echo $prog_name ?>
      %0D%0AIssue Descriptor: <?php echo $descriptor ?>
      %0D%0ADescription: <?php echo $description?>
      %0D%0ADrivers: <?php //echo $Driversx?>
      %0D%0AImpact Area: <?php echo $impactArea2?>
      %0D%0AImpact Level: <?php echo $impactLevel2?>
      %0D%0APOC Group/Name: <?php echo $individual?>
      %0D%0AResponse Strategy: <?php echo $responseStrategy2?>
      %0D%0AForecasted Resolution Date: <?php if($unknown == "off"){ echo $date; } else { echo "Unknown"; } ?>
      %0D%0AAssociated Projects: <?php echo $assocProject?>
      %0D%0AAction Plan: <?php echo $actionPlan?>
      %0D%0ADate Closed: <?php echo $dateClosed?>
      " 
      class="btn btn-primary"><span class="glyphicon glyphicon-envelope"></span> Email </a>
    </div>
    <?php } ?>
    <?php } ?>
    <?php } ?>
</div>
</form>

</div>
<?php
    //print_r($_POST);

?>
</body>
<script>
function copyDiv() {
    var firstDivContent = document.getElementById('drivers');
    var secondDivContent = document.getElementById('driversx');
    secondDivContent.innerHTML = firstDivContent.innerHTML;
}
  </script>
</html>