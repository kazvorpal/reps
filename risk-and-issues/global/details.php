<?php 
include ("../../includes/functions.php");
include ("../../db_conf.php");
include ("../../data/emo_data.php");
include ("../../sql/MS_Users.php");
include ("../../sql/update-time.php");

//SEESION STATE FOR MENU SHOW = 0 / NO SHOW = 1
session_start();

$unframe = "0"; // NO COLOR BOX
$_SESSION['unframe'] = $unframe;

$status = 0;
if(isset($_GET['status'])) {
  $status = $_GET['status']; //0=closed , 1=open
}

if(isset($_GET['unframe'])) {
  $unframe = $_GET['unframe'];
  $_SESSION['unframe'] = $unframe;
} 

//GET GLOBAL PROGRAM BY ID OR RIKEY
if(isset($_GET['rikey'])){
  $ri_id = $_GET['rikey'];
} 

if(isset($_GET['id'])){
  $ri_id = $_GET['id'];
}

$sql_glb_prog = "SELECT* FROM [RI_MGT].[fn_GetListOfAllRiskAndIssue]($status) WHERE RiskAndIssue_Key = $ri_id";
$stmt_glb_prog   = sqlsrv_query( $data_conn, $sql_glb_prog ); 
$row_glb_prog   = sqlsrv_fetch_array( $stmt_glb_prog , SQLSRV_FETCH_ASSOC);
// $row_glb_prog[''];
//echo $sql_glb_prog;

//DRIVER FROM LOG KEY
$RiskAndIssueLog_Key = $row_glb_prog['RiskAndIssueLog_Key'];

$sql_glb_drv = "SELECT* FROM [RI_MGT].[fn_GetListOfDriversForRILogKey]($status) WHERE RiskAndIssueLog_Key = $RiskAndIssueLog_Key";
$stmt_glb_drv   = sqlsrv_query( $data_conn, $sql_glb_drv ); 
$row_glb_drv   = sqlsrv_fetch_array( $stmt_glb_drv , SQLSRV_FETCH_ASSOC);
//echo $sql_glb_drv;

//PROGRAM FROM RIKEY
$sql_rikey_prg = "DECLARE @PROG_NMs VARCHAR(1000)
    SELECT @PROG_NMs = COALESCE(@PROG_NMs+'<br>','')+ CAST(Program_Nm AS VARCHAR(1000))
    FROM [RI_MGT].[fn_GetListOfProgramsForPortfolioRI_Key] ($ri_id)
    SELECT @PROG_NMs AS Program_Nm";
$stmt_rikey_prg = sqlsrv_query( $data_conn, $sql_rikey_prg); 
$row_rikey_prg = sqlsrv_fetch_array( $stmt_rikey_prg , SQLSRV_FETCH_ASSOC);
//echo $sql_glb_drv;

//SUBPROGRAM FROM RIKEY
$sql_rikey_subprg = "DECLARE @SPROG_NMs VARCHAR(1000)
    SELECT @SPROG_NMs = COALESCE(@SPROG_NMs+'<br>','')+ CAST(SubProgram_Nm AS VARCHAR(1000))
    FROM RI_Mgt.fn_GetListSubProgramsforRIKey($ri_id,$status)
    SELECT @SPROG_NMs AS SubProgram_Nm";
$stmt_rikey_subprg = sqlsrv_query( $data_conn, $sql_rikey_subprg); 
$row_rikey_subprg = sqlsrv_fetch_array( $stmt_rikey_subprg , SQLSRV_FETCH_ASSOC);
$subprograms = $row_rikey_subprg['SubProgram_Nm'];

//REGIONS FOR GLOBAL PROGRAM R/I
$sql_regions = "DECLARE @EPS_IDs VARCHAR(1000)
    SELECT @EPS_IDs = COALESCE(@EPS_IDs+'<br>','')+ CAST(MLMRegion_Cd AS VARCHAR(1000))
    FROM RI_MGT.fn_GetListOfAllRiskAndIssue(1) where RiskAndIssue_Key = $ri_id
    SELECT @EPS_IDs AS MLMRegion_Cd";
$stmt_regions = sqlsrv_query( $data_conn, $sql_regions );
$row_regions = sqlsrv_fetch_array( $stmt_regions, SQLSRV_FETCH_ASSOC);
//echo $sql_regions;
//echo $row_regions['MLMRegion_Cd'];

//DECLARE
$ri_owner = $row_glb_prog['LastUpdateBy_Nm'];
$global = $row_glb_prog['Global_Flg'];
$ri_id = $row_glb_prog['RiskAndIssue_Key'];
$name = trim($row_glb_prog['RI_Nm']);
$RILevel = $row_glb_prog['RILevel_Cd'];
$RIType = $row_glb_prog['RIType_Cd'];
$createdFrom  = "";

if(!empty($row_glb_prog['PortfolioType_Key'])) {
   $portfoliokey = $row_glb_prog['PortfolioType_Key'];
   if($portfoliokey == 1) {
    $portfolio = "NT 2.0"; //FIX THE HARDCODES
   } else {
    $portfolio = "BAU";
   }
}

if($RILevel == "Program") {
    $programs = $row_glb_prog['MLMProgram_Nm']; //FOR PROGRAM LEVEL
} else {
    $programs = $row_rikey_prg['Program_Nm']; //FOR PORTFOLIO LEVEL
}

$project_nm = $row_glb_prog['EPSProject_Nm'];
$descriptor  = $row_glb_prog['ScopeDescriptor_Txt'];
$description = $row_glb_prog['RIDescription_Txt'];
$regionx = $row_regions['MLMRegion_Cd'];;
$Driversx = $row_glb_drv['Driver_Nm'];
$impactArea2 = $row_glb_prog['ImpactArea_Nm'];
$impactLevel2 = $row_glb_prog['ImpactLevel_Nm'];
$riskProbability = $row_glb_prog['RiskProbability_Nm'];
$individual = $row_glb_prog['POC_Nm'];
$internalExternal = $row_glb_prog['POC_Nm'];
$responseStrategy2 = $row_glb_prog['ResponseStrategy_Nm'];
$date = $row_glb_prog['ForecastedResolution_Dt'];
$unknown = ""; // IF DATE IS EMPTY
$transProgMan = $row_glb_prog['TransferredPM_Flg'];

$opportunity = $row_glb_prog['Opportunity_Txt'];

$opportunityIndicator = "";
if(!empty($row_glb_prog['TransferredPM_Flg'])) {
  $opportunityIndicator = "Yes";
}

$actionPlan = $row_glb_prog['ActionPlanStatus_Cd'];
$dateClosed = $row_glb_prog['RIClosed_Dt'];
$driver_list = "";
$ri_list = "";
//$uaccess = $_GET['au'];
//$status = $_GET['status'];
$department = $row_glb_prog['POC_Department'];
$raidLog = $row_glb_prog['RaidLog_Flg'];
$riskRealized_Raw = $row_glb_prog['RiskRealized_Flg'];
$popup = "true";

if($riskRealized_Raw == 1){
  $riskRealized = "Yes";
} else {
  $riskRealized = "No";
}

//EDIT AUTHORIZATION - SHOW OR HIDE EDIT BUTTON
$sql_port_user = "SELECT * FROM [RI_MGT].[RiskandIssues_Users] WHERE Username = '$windowsUser' and [RI_MGT].[RiskandIssues_Users].[Group] = 'PORT'";
$stmt_port_user   = sqlsrv_query( $data_conn, $sql_port_user ); 
$row_port_user  = sqlsrv_fetch_array( $stmt_port_user , SQLSRV_FETCH_ASSOC);
//echo $row_port_user['Username'];
//echo $sql_port_user;

$portUser = 0; //HIDE BUTTONS
if(!empty($row_port_user)){
  $portUser = 1; //SHOW BUTTONS
}

//LINK FOR EMAIL
$mailLinkx = $menu_root . "/risk-and-issues/global/details.php?rikey=" . $ri_id . "&status=" . urlencode($status) ; 
$mailLink = urlencode($mailLinkx);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Global Risk and Issues Details</title>
</head>
	
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"> 
  <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">

<body style="font-family:Mulish, serif;">

<?php // SHOW MENU IF OPENING DIRECT
if($unframe == "0") { //NO COLORBOX
  include ("../../includes/menu.php");
}

?>
<div id='dlist'></div> 
	<div align="center"><h3>GLOBAL <?= strtoupper($RILevel) . " " . strtoupper($RIType) ?> DETAILS</h3></div>
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
    <tr>
      <td width="20%">Type</td>
      <td><?= $RILevel . " " . $RIType; ?></td>
    </tr>
<?php if(!empty($portfolio)) { ?>
    <tr>
      <td width="20%">Portfolio</td>
      <td><?= $portfolio ?></td>
    </tr>
<?php } ?>
    <tr>
      <td width="20%">Program</td>
      <td><?= $programs ?></td>
    </tr>
<?php if(!empty($subprograms)) {?>
    <tr>
      <td width="20%">Subprograms</td>
      <td><?= $subprograms ?></td>
    </tr>
<?php } ?>
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
      <td>Issue Descriptor</td>
      <td><?= $descriptor ; ?></td>
    </tr>
    <tr>
      <td>Description</td>
      <td><?= str_replace(["'", '"'], ['&#39;', '&quot;'], $description); ?></td>
    </tr>
<?php if($RILevel == "Program" && $global == 1){ ?>
    <tr>
      <td>Region</td>
      <td><?= $regionx; ?></td>
    </tr>
<?php } ?>
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
      <td>POC Team</td>
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
<?php if(!empty($row_glb_prog['TransferredPM_Flg'])) { ?>
    <tr>
      <td>Tranfer to Program Manager</td>
      <td>
        Yes
    </td>
    </tr>
<?php } ?>

<?php if(!empty($row_glb_prog['Opportunity_Txt'])) { ?>
    <tr>
      <td>Opportunity</td>
      <td><?php $row_glb_prog['Opportunity_Txt']; ?>
    </td>
    </tr>
<?php } ?>
    <tr>
      <td>Action Plan <a data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><span class="glyphicon glyphicon-calendar"></span></a></td>
      <td><?= str_replace(["'", '"'], ['&#39;', '&quot;'], $actionPlan); ?>

        <div class="collapse" id="collapseExample">
          <div class="well">
          <iframe id="actionPlan" src="../action_plan.php?rikey=<?= $ri_id?>" width="100%" frameBorder="0"></iframe>
          </div>
        </div>

    </td>
    </tr>
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
      <?php if($popup=="false"){?>
        <a href="javascript:void(0);" onclick="javascript:history.go(-1)" class="btn btn-primary"><span class="glyphicon glyphicon-step-backward"></span> Back </a>
      <?php } ?>

        <?php if($portUser == 1){
            $eregions = "N/A";
              if($regionx != "") {
              $eregions = str_replace("<br>", ", ", $regionx);
              }
            $programs = str_replace("<br>", ", ", $programs);
            $desc = (strlen($description) > 100) ? substr($description, 0, 100) . "[...]" : $description;
            $act = (strlen($actionPlan) > 100) ? substr($actionPlan, 0, 100) . "[...]" : $actionPlan;

            ?>
            <a href="../global/update.php?&id=<?= $ri_id?>"  class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span> Update </a>
            <a href="mailto:?subject=RISKS AND ISSUES - <?= $name;?>
            &body=%0D%0A----------------------------------------RISKS AND ISSUES DETAILS ----------------------------------------
            %0D%0AID: <?= $ri_id;?>
            %0D%0AOwner Name: <?= $ri_owner;?>
            %0D%0AName: <?= $name;?>
            %0D%0AType: <?= $RILevel . " " . $RIType?>
            %0D%0AProgram: <?= $programs;?>
            %0D%0ARegion(s): <?= $eregions;?>
            %0D%0ADescriptor: <?= $descriptor ?>
            <!-- %0D%0ADescription: <?= str_replace(["'", '"'], ['&#39;', '&quot;'], $desc) ?>?> -->
            %0D%0ADriver: <?= $Driversx?>
            %0D%0AImpact Area: <?= $impactArea2?>
            %0D%0AImpact Level: <?= $impactLevel2?>
            %0D%0AResponse Strategy: <?= $responseStrategy2?>
            %0D%0AForecasted Resolution Date: <?php if(!empty($date) || $date != ""){ echo (convtimex($date)); } else { echo "Unknown"; }?>
            %0D%0ATransfer to Program Manager: <?= $opportunityIndicator;?>
            %0D%0AAction Plan: <?= str_replace(["'", '"'], ['&#39;', '&quot;'], $act)?>
            %0D%0ADate Closed: <?php convtimex($dateClosed)?>
            %0D%0ALink: <?= $mailLink;?>
            " 
            class="btn btn-primary"><span class="glyphicon glyphicon-envelope"></span> Email </a>
            <?php// } ?>
        <?php } ?>
    </div>
  </form>
</div>
</body>
</html>