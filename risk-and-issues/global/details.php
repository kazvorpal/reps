<?php 
include ("../../includes/functions.php");
include ("../../db_conf.php");
include ("../../data/emo_data.php");
include ("../../sql/MS_Users.php");

//GET GLOBAL PROGRAM BY ID
$ri_id = $_GET['rikey'];

$sql_glb_prog = "SELECT* FROM [RI_MGT].[fn_GetListOfAllRiskAndIssue](1) WHERE RiskAndIssue_Key = $ri_id";
$stmt_glb_prog   = sqlsrv_query( $data_conn, $sql_glb_prog ); 
$row_glb_prog   = sqlsrv_fetch_array( $stmt_glb_prog , SQLSRV_FETCH_ASSOC);
// $row_glb_prog[''];
//echo $sql_glb_prog;

//DRIVER FROM LOG KEY
$RiskAndIssueLog_Key = $row_glb_prog['RiskAndIssueLog_Key'];

$sql_glb_drv = "SELECT* FROM [RI_MGT].[fn_GetListOfDriversForRILogKey](1) WHERE RiskAndIssueLog_Key = $RiskAndIssueLog_Key";
$stmt_glb_drv   = sqlsrv_query( $data_conn, $sql_glb_drv ); 
$row_glb_drv   = sqlsrv_fetch_array( $stmt_glb_drv , SQLSRV_FETCH_ASSOC);
//echo $sql_glb_drv;

//PROGRAM FROM RIKEY
$sql_rikey_prg = "DECLARE @PROG_NMs VARCHAR(100)
    SELECT @PROG_NMs = COALESCE(@PROG_NMs+'<br>','')+ CAST(Program_Nm AS VARCHAR(100))
    FROM [RI_MGT].[fn_GetListOfProgramsForPortfolioRI_Key] ($ri_id)
    SELECT @PROG_NMs AS Program_Nm";
$stmt_rikey_prg = sqlsrv_query( $data_conn, $sql_rikey_prg); 
$row_rikey_prg = sqlsrv_fetch_array( $stmt_rikey_prg , SQLSRV_FETCH_ASSOC);
//echo $sql_glb_drv;

//DECLARE
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
$regionx = "";
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
$actionPlan = $row_glb_prog['ActionPlanStatus_Cd'];
$dateClosed = $row_glb_prog['RIClosed_Dt'];
$driver_list = "";
$ri_list = "";
//$uaccess = $_GET['au'];
//$status = $_GET['status'];
$department = $row_glb_prog['POC_Department'];
$raidLog = $row_glb_prog['RaidLog_Flg'];
$riskRealized_Raw = $row_glb_prog['RiskRealized_Flg'];


if($riskRealized_Raw == 1){
  $riskRealized = "Yes";
} else {
  $riskRealized = "No";
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

<body style="font-family:Mulish, serif;">
<div id='dlist'></div> 
	<div align="center"><h3>PROJECT <?php echo strtoupper($RIType) ?> DETAILS</h3></div>
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
<?php if(!empty($portfolio)) { ?>
    <tr>
      <td width="20%">Portfolio</td>
      <td><?php echo $portfolio ?></td>
    </tr>
<?php } ?>
    <tr>
      <td width="20%">Program</td>
      <td><?php echo $programs ?></td>
    </tr>
<?php if(isset($_POST['CreatedFrom'])) { ?>
    <tr>
      <td>Created From</td>
      <td><?php echo $createdFrom ; ?></td>
    </tr>
<?php } ?>
<?php if(isset($_POST['CreatedFrom'])) { ?>
    <tr>
      <td>Created From</td>
      <td><?php echo $createdFrom ; ?></td>
    </tr>
<?php } ?>
    <tr>
      <td>Issue Descriptor</td>
      <td><?php echo $descriptor ; ?></td>
    </tr>
    <tr>
      <td>Description</td>
      <td><?php echo $description; ?></td>
    </tr>
    <!--<tr>
      <td>Region</td>
      <td><?php //echo $regionx; ?></td>
    </tr> -->
    <tr>
      <td>Drivers</td>
      <td>
        <?php echo $Driversx;?>
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
    <?php if(!empty($riskProbability)) {?>
    <tr>
      <td>Risk Probibility</td>
      <td><?php echo $riskProbability; ?></td>
    </tr>
    <?php } ?>
    <tr>
      <td>Individual POC</td>
      <td><?php echo $individual; ?></td>
    </tr>
    <tr>
      <td>POC Team</td>
      <td><?php echo $department; ?></td>
    </tr>
    <tr>
      <td>Response Strategy</td>
      <td><?php echo $responseStrategy2; ?></td>
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
      <td><?php echo $actionPlan; ?>

        <div class="collapse" id="collapseExample">
          <div class="well">
          <iframe id="actionPlan" src="action_plan.php?rikey=<?php echo $ri_id?>" width="100%" frameBorder="0"></iframe>
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
      <td><?php echo $riskRealized ; ?></td>
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
      <?php// if($popup=="false"){?>
        <a href="javascript:void(0);" onclick="javascript:history.go(-1)" class="btn btn-primary"><span class="glyphicon glyphicon-step-backward"></span> Back </a>
      <?php// } ?>

        <?php// if($access=="true"){?>  
            <?php// if($status == 1){ ?>
            <a href="../global/update.php?&id=<?php echo $ri_id?>"  class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span> Update </a>
            <a href="mailto:?subject=RISKS AND ISSUES - <?php echo $name;?>
            &body=%0D%0A----------------------------------------RISKS AND ISSUES DETAILS ----------------------------------------
            %0D%0AID: <?php echo $ri_id;?>
            %0D%0AName: <?php echo $name;?>
            %0D%0AType: <?php echo $RILevel . " " . $RIType?>
            %0D%0ADescriptor: <?php echo $descriptor ?>
            %0D%0ADescription: <?php echo $description?>
            %0D%0ADriver: <?php echo $Driversx?>
            %0D%0AImpact Area: <?php echo $impactArea2?>
            %0D%0AImpact Level: <?php echo $impactLevel2?>
            %0D%0AIndividual POC: <?php echo $individual?>
            %0D%0ATeam POC: <?php echo $department?>
            %0D%0AResponse Strategy: <?php echo $responseStrategy2?>
            %0D%0AForecasted Resolution Date: <?php if(!empty($date) || $date != ""){ echo (convtimex($date)); } else { echo "Unknown"; }?>
            %0D%0AAction Plan: <?php echo $actionPlan?>
            %0D%0ADate Closed: <?php convtimex($dateClosed)?>
            %0D%0ALink: <?php echo "https://catl0dwas10222.corp.cox.com/risk-and-issues/global/details.php?rikey=" . $ri_id;?>
            " 
            class="btn btn-primary"><span class="glyphicon glyphicon-envelope"></span> Email </a>
            <?php// } ?>
        <?php// } ?>
    </div>
  </form>
</div>
</body>
</html>