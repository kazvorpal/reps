<?php 
include ("../includes/functions.php");
include ("../db_conf.php");
include ("../data/emo_data.php");
include ("../sql/MS_Users_prg.php");
//print_r($_REQUEST);

//FIND PROJECT RISK AND ISSUES
$RiskAndIssue_Key = $_GET['rikey'];
$fscl_year = $_GET['fscl_year'];

$proj_name = "";
if(!empty($_GET['proj_name'])){
  $proj_name = $_GET['proj_name'];
}

$prog_name = $_GET['program'];

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
$sql_risk_issue_regions = "DECLARE @temp VARCHAR(MAX) 
                          SELECT @temp = COALESCE(@temp+'<br> ' ,'') + MLMRegion_Cd 
                          FROM RI_Mgt.fn_GetListOfAllRiskAndIssue($status) where RIlevel_Cd = 'Program' and RiskAndIssue_Key = $RiskAndIssue_Key 
                          SELECT @temp AS MLMRegion_Cd ";
$stmt_risk_issue_regions  = sqlsrv_query( $data_conn, $sql_risk_issue_regions);
$row_risk_issue_regions  = sqlsrv_fetch_array($stmt_risk_issue_regions , SQLSRV_FETCH_ASSOC);
//echo $row_risk_issue_regions['MLMRegion_Cd'];
//echo $sql_risk_issue_regions;			

//GET ASSOCIATED PROJECTS
//FIRST GET THE PROGRAM RI KEY
//$ri_name = $row_risk_issue['RI_Nm'];
$sql_progRIkey = "select * from RI_Mgt.fn_GetListOfAllRiskAndIssue($status) where RIlevel_Cd = 'Program' and RiskAndIssue_Key = $RiskAndIssue_Key";
$stmt_progRIkey  = sqlsrv_query( $data_conn, $sql_progRIkey  );
$row_progRIkey  = sqlsrv_fetch_array($stmt_progRIkey , SQLSRV_FETCH_ASSOC);
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
$row_risk_issue_drivers  = sqlsrv_fetch_array($stmt_risk_issue_drivers , SQLSRV_FETCH_ASSOC);
//echo $row_risk_issue['Risk_Issue_Name']; 			
//echo $sql_risk_issue_drivers  . "<br><br>";
//exit();

//GET THE ASSOCIATED PROJECTS USING THE PROGRAMRI_KEY
$sql_risk_issue_assoc_proj = "DECLARE @temp VARCHAR(MAX)
                              SELECT @temp = COALESCE(@temp+'<br>' ,'') + EPSProject_Nm
                              FROM RI_Mgt.fn_GetListOfAssociatedProjectsForProgramRIKey($RiskAndIssue_Key,$progRIkey,$status)
                              SELECT @temp AS eps_projects
                              ";
$stmt_risk_issue_assoc_proj = sqlsrv_query( $data_conn, $sql_risk_issue_assoc_proj );
$row_risk_issue_assoc_proj = sqlsrv_fetch_array($stmt_risk_issue_assoc_proj, SQLSRV_FETCH_ASSOC);
//echo $row_risk_issue_assoc_proj['eps_pojects'];
//echo "<br><br>" . $sql_risk_issue_assoc_proj; 

//COUNT ASSOCIATED PROJECTS USING THE PROGRAMRI_KEY
$sql_assoc_proj_cnt = "SELECT COUNT(*) AS AsscPrjCnt FROM RI_Mgt.fn_GetListOfAssociatedProjectsForProgramRIKey($RiskAndIssue_Key,$progRIkey,$status)";
$stmt_assoc_proj_cnt = sqlsrv_query( $data_conn, $sql_assoc_proj_cnt );
$row_assoc_proj_cnt = sqlsrv_fetch_array($stmt_assoc_proj_cnt, SQLSRV_FETCH_ASSOC);
$assPrjCnt = $row_assoc_proj_cnt['AsscPrjCnt'];


//GET UID FOR ASSOCIATED PROJECTS - 11.9.2023
$sql_uid = "Select EPSProject_Nm, PROJ_ID
                  FROM RI_Mgt.fn_GetListOfAssociatedProjectsForProgramRIKey($RiskAndIssue_Key,$progRIkey,$status) 
                  left join [EPS].[ProjectStage] on PROJ_NM = EPSProject_Nm";
$stmt_uid = sqlsrv_query( $data_conn, $sql_uid );
$row_uid = sqlsrv_fetch_array($stmt_uid  , SQLSRV_FETCH_ASSOC);
$uid_frm_prj = $row_uid ['PROJ_ID']; 
//echo $uid_frm_prj;

$uid = $uid_frm_prj;
if(isset($_GET['uid'])) {
  $uid = $_GET['uid'];
}

//echo $uid;

//USER AUTHORIZATION - NOT USED-DELETE
$authProg = $row_risk_issue['MLMProgram_Nm'];
$sql_authorize = "SELECT * FROM [RI_MGT].[fn_GetListOfMLMProgramAccessforUserUID]('gcarolin', 2022) WHERE Program_Nm = '$authProg'";
$stmt_authorize = sqlsrv_query( $data_conn, $sql_authorize );
$row_authorize = sqlsrv_fetch_array($stmt_authorize, SQLSRV_FETCH_ASSOC);

//echo $row_authorize['Program_Nm'];

//old authorizarion
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

//USE THIS LOCK FOR EDIT BUTTON 
if(is_null($row_winuser_prg)) {
  $lock = "yes";
} else { 
  $lock = "no";
} 

//DECLARE
$ri_id = $row_risk_issue['RiskAndIssue_Key'];
$name = $row_risk_issue['RI_Nm'];
$RILevel = $row_risk_issue['RILevel_Cd'];
$RIType = $row_risk_issue['RIType_Cd'];
$createdFrom  = "";
$programs = $row_risk_issue['MLMProgram_Nm'];
$prject_nm = "";
$descriptor  = $row_risk_issue['ScopeDescriptor_Txt'];
$description = $row_risk_issue['RIDescription_Txt'];
$regionx = $row_risk_issue_regions['MLMRegion_Cd']; ;
$Driversx = $row_risk_issue_drivers['Driver_Nm'];
$impactArea2 = $row_risk_issue['ImpactArea_Nm'];
$impactLevel2 = $row_risk_issue['ImpactLevel_Nm'];
$individual = $row_risk_issue['POC_Nm'];
$internalExternal = $row_risk_issue['POC_Department'];
$responseStrategy2 = $row_risk_issue['ResponseStrategy_Nm'];
$unknown = ""; // IF DATE IS EMPTY
$date = $row_risk_issue['ForecastedResolution_Dt'];
$transProgMan = $row_risk_issue['TransferredPM_Flg'];
$opportunity = $row_risk_issue['Opportunity_Txt'];
$assocProject = $row_risk_issue_assoc_proj['eps_projects'];
$assocProjectcomma = str_replace("<br>", ",", $assocProject);
$actionPlan = $row_risk_issue['ActionPlanStatus_Cd'];
$formaction =  "update"; 
$asscCRKey = $row_risk_issue['AssociatedCR_Key'];
$riskRealized_Raw = $row_risk_issue['RiskRealized_Flg'];

if($riskRealized_Raw == 1){
  $riskRealized = "Yes";
} else {
  $riskRealized = "No";
}

$formName = "PRGR";
if($RIType == "Issue"){
  $formName = "PRGI";
}

$raidLogx = $row_risk_issue['RaidLog_Flg'];
if($raidLogx == 1) {$raidLog =  "Yes";}
if($raidLogx == 0) {$raidLog = "No";}

$department = $row_risk_issue['POC_Department'];

if(!empty($row_risk_issue['RIClosed_Dt'])){
$dateClosed = date_format($row_risk_issue['RIClosed_Dt'], 'Y-m-d');
} else {
$dateClosed = "---";
}

$link = urlencode($menu_root . "/risk-and-issues/details-prg.php?au=true&rikey=" . $ri_id ."&fscl_year=" . $fscl_year . "&program=" . $prog_name . "&status=1&popup=true&uid=" . $uid);
//echo $link;
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $name ?></title>
</head>
	
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"> 
  <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">

<script>

    ri = <?= json_encode($row_risk_issue) ?>

</script>

<body style="font-family:Mulish, serif;" onload="copyDiv()">
	<div align="center"><h3>PROGRAM <?php echo strtoupper($RIType) ?> DETAILS</h3></div>
  <div align="center"><h4><?php echo $prog_name ?></h4></div>
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
      <td><?php echo $ri_id; ?></td>
    </tr>  
  <tr>
      <td width="20%">Risk/Issue Name</td>
      <td><?php echo $name; ?></td>
    </tr>
    <tr>
      <td width="20%">Type</td>
      <td><?php echo $RILevel . " " . $RIType; ?></td>
    </tr>
<?php if($RIType == "Risk"){ ?>
    <tr>
      <td width="20%">Risk Realized</td>
      <td><?php echo $riskRealized ; ?></td>
    </tr>
<?php } ?>
<?php if(!empty($asscCRKey)) { ?>
    <tr>
      <td>Associated CR ID</td>
      <td><?php echo $asscCRKey ; ?></td>
    </tr>
<?php } ?>
    <tr>
      <td>Program</td>
      <td><?php echo $programs ; ?></td>
    </tr>
    <tr>
      <td>Region(s)</td>
      <td>
      <?php echo $regionx ?>
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
      <td>Driver</td>
      <td>
        <?php 
        echo $Driversx;
        ?>
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
      <td><?php echo $responseStrategy2; ?></td>
    </tr>
    <tr>
      <td>Notify Portfolio Team</td>
      <td><?php echo $raidLog; ?></td>
    </tr>
    <tr>
      <td>Forecasted Resolution Date</td>
      <td>
        <?php if(!empty($date) || $date != ""){ echo (convtimex($date)); } else { echo "Unknown"; } ?>
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
      <td>Associated Projects (<?php echo $assPrjCnt ?>)</td>
      <td>
        <?php echo $assocProject?>
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
<?php if($popup == "false"){?>
    <a href="javascript:void(0);" onclick="javascript:history.go(-1)" class="btn btn-primary"><span class="glyphicon glyphicon-step-backward"></span> Back </a>
    <?php } ?>
<?php if($lock == "no")  {?>  
<?php if($status == 1){ ?>
  <?php $eregions = str_replace("<br>", ",", $regionx)?>
    <a href="<?php echo $formType ?>?formName=<?php echo $formName?>&action=update&status=1&ri_level=prg&assoc_prj=<?php echo $assocProjectcomma; ?>&fscl_year=<?php echo $fscl_year?>&name=<?php echo $name?>&ri_type=<?php echo $RIType ?>&rikey=<?php echo $RiskAndIssue_Key?>&progRIkey=<?php echo $progRIkey;?>&progkey=<?php echo $programKey;?>&progname=<?php echo $prog_name ?>&projname=<?php echo $proj_name;?>&uid=<?php echo $uid ;?>&drivertime=<?php 
        while ($row_risk_issue_drivers_up  = sqlsrv_fetch_array($stmt_risk_issue_drivers_up , SQLSRV_FETCH_ASSOC)) {
        echo $row_risk_issue_drivers_up ['Driver_Nm'] . ',';
        }
        ?>&regions=<?php 
        while ($row_risk_issue_regions_up  = sqlsrv_fetch_array($stmt_risk_issue_regions_up , SQLSRV_FETCH_ASSOC)) {
        echo $row_risk_issue_regions_up['MLMRegion_Cd'] . ',';
        }
        ?>"  class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span> Update </a>

<?php 
$desc = (strlen($description) > 100) ? substr($description, 0, 100) . "[...]" : $description;
$act = (strlen($actionPlan) > 100) ? substr($actionPlan, 0, 100) . "[...]" : $actionPlan;
?>

<a href="mailto:?subject=RISKS AND ISSUES - <?php echo $name;?>
      &body=%0D%0A----------------------------------------RISKS AND ISSUES DETAILS ----------------------------------------
      %0D%0AID: <?php echo $ri_id;?>
      %0D%0ARisk/Issue Name: <?php echo $name;?>
      %0D%0AType: <?php echo $RILevel . " " . $RIType;?>
      %0D%0AProgram: <?php echo $prog_name;?>
      %0D%0ARegion(s): <?php echo $eregions;?>
      %0D%0ADescriptor: <?php echo $descriptor;?>
      %0D%0ADescription: <?php echo $desc;?>
      %0D%0ADriver: <?php echo $Driversx;?>
      %0D%0AImpact Area: <?php echo $impactArea2;?>
      %0D%0AImpact Level: <?php echo $impactLevel2;?>
      %0D%0AResponse Strategy: <?php echo $responseStrategy2;?>
      %0D%0ANotify Portfolio Team: <?php echo $raidLog;?>
      %0D%0AForecasted Resolution Date: <?php if(!empty($date) || $date != ""){ echo (convtimex($date)); } else { echo "Unknown"; } ;?>
      %0D%0AAssociated Project(s): <?php echo str_replace("<br>", ", ", $assocProject);?>
      %0D%0AAction Plan: <?php echo $act;?>
      %0D%0ADate Closed: <?php echo $dateClosed;?>
      %0D%0ALink: <?php echo $link;?>" 
      class="btn btn-primary"><span class="glyphicon glyphicon-envelope"></span> Email </a>
      
    <span style="font-size: 24px;"> | </span>

    <?php
    $assocProjLink = "includes/associated_prj_manage_prg.php?action=update&ri_level=prg&program=" . $prog_name . "&prg_nm=" . $prog_name . "&progRIKey=" . $progRIkey . "&fiscal_year=" . $fscl_year . "&name=" . $row_risk_issue['RI_Nm'] . "&proj_name=" . $proj_name . "&ri_type=" . $row_risk_issue['RIType_Cd'] . "&rikey=" . $row_risk_issue['RiskAndIssue_Key'] . "&status=1&uid=" . $uid;
    ?>

    <a href="<?php echo $assocProjLink ?>"><span class="btn btn-primary">+/- Assoc. Projects</span></a>

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
    if (firstDivContent && secondDivContent) {
      console.log("true")
      var firstDivContent = document.getElementById('drivers');
      var secondDivContent = document.getElementById('driversx');
      secondDivContent.innerHTML = firstDivContent.innerHTML;
    } else
    console.log("false")
}
  </script>
</html>