<?php 
include ("../includes/functions.php");
include ("../db_conf.php");
include ("../data/emo_data.php");

//FIND PROJECT RISK AND ISSUES 1.26.2022
$RiskAndIssue_Key = $_GET['rikey'];
$fscl_year = $_GET['fscl_year'];
$proj_name = $_GET['proj_name'];
$status = $_GET['status']; //0=closed , 1=open
$popup = $_GET['popup'];
  
$sql_risk_issue = "select * from RI_MGT.fn_GetListOfAllRiskAndIssue ($status)  where RiskAndIssue_Key = $RiskAndIssue_Key";
$stmt_risk_issue = sqlsrv_query( $data_conn, $sql_risk_issue );
$row_risk_issue = sqlsrv_fetch_array($stmt_risk_issue, SQLSRV_FETCH_ASSOC);
// echo $row_risk_issue['Risk_Issue_Name']; 
$ri_name = $row_risk_issue['RI_Nm'];
$riLog_Key = $row_risk_issue['RiskAndIssueLog_Key'];

//GET DRIVERS
$sql_risk_issue_driver = "select * from [RI_MGT].[fn_GetListOfDriversForRILogKey]($riLog_Key,$status)";
$stmt_risk_issue_driver = sqlsrv_query( $data_conn, $sql_risk_issue_driver );
// $row_risk_issue_driver = sqlsrv_fetch_array($stmt_risk_issue_driver, SQLSRV_FETCH_ASSOC);
//echo $row_risk_issue_driver['Driver_Nm']; 
//echo $sql_risk_issue_driver;

//GET ASSOCIATED PROJECTS
$sql_risk_issue_assoc_proj = "select distinct RiskAndIssue_Key, proj_nm from RI_MGT.fn_GetListOfAssociatedProjectsForProjectRINm('$ri_name',$status)";
$stmt_risk_issue_assoc_proj = sqlsrv_query( $data_conn, $sql_risk_issue_assoc_proj );
// $row_risk_issue_assoc_proj = sqlsrv_fetch_array($stmt_risk_issue__assoc_proj, SQLSRV_FETCH_ASSOC);
// echo $row_risk_issue_assoc_proj['RI_Nm]; 

//DECLARE
$name = trim($row_risk_issue['RI_Nm']);
$RILevel = "";
$RIType = $row_risk_issue['RIType_Cd'];
$createdFrom  = "";
$programs = "";
$project_nm = $row_risk_issue['Proj_Nm'];
$descriptor  = $row_risk_issue['ScopeDescriptor_Txt'];
$description = $row_risk_issue['RIDescription_Txt'];
$regionx = "";
$Driversx = ""; //$row_risk_issue_driver['Driver_Nm'];
$impactArea2 = $row_risk_issue['ImpactArea_Nm'];
$impactLevel2 = $row_risk_issue['ImpactLevel_Nm'];
$riskProbability = $row_risk_issue['RiskProbability_Nm'];
$individual = $row_risk_issue['POC_Nm'];
$internalExternal = $row_risk_issue['POC_Nm'];
$responseStrategy2 = $row_risk_issue['ResponseStrategy_Nm'];
$unknown = ""; // IF DATE IS EMPTY
$date = $row_risk_issue['ForecastedResolution_Dt'];
$transProgMan = $row_risk_issue['TransferredPM_Flg'];
$opportunity = $row_risk_issue['Opportunity_Txt'];
$assocProject = "";
$actionPlan = $row_risk_issue['ActionPlanStatus_Cd'];
$dateClosed = $row_risk_issue['RIClosed_Dt'];
$driver_list = "";
$ri_list = "";
$uaccess = $_GET['au'];
$status = $_GET['status'];
$department = $row_risk_issue['POC_Department'];
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
	<div align="center"><h3>PROJECT RISKS & ISSUES DETAILS</h3></div>
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
      <td>Project</td>
      <td><?php echo $project_nm ; ?></td>
    </tr>
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
        <?php 
        while ($row_risk_issue_driver = sqlsrv_fetch_array($stmt_risk_issue_driver, SQLSRV_FETCH_ASSOC)) {
        echo $row_risk_issue_driver['Driver_Nm'] . '<br>';
        }
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
    <?php if(!empty($riskProbability)) {?>
    <tr>
      <td>Risk Probibility</td>
      <td><?php echo $riskProbability; ?></td>
    </tr>
    <?php } ?>
    <tr>
      <td>POC Name</td>
      <td><?php echo $individual; ?></td>
    </tr>
    <tr>
      <td>Team/Group POC</td>
      <td><?php echo $department; ?></td>
    </tr>
    <tr>
      <td>Response Strategy</td>
      <td><?php echo $responseStrategy2; ?></td>
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
      <td>Associated Projects</td>
      <td>
        <?php 
        while ($row_risk_issue_assoc_proj = sqlsrv_fetch_array($stmt_risk_issue_assoc_proj, SQLSRV_FETCH_ASSOC)) {
        echo $row_risk_issue_assoc_proj['proj_nm'] . "<br>"; 
        }
        ?>
      </td>
    </tr>
    <tr>
      <td>Action Plan</td>
      <td><?php echo $actionPlan; ?>
    </td>
    </tr>
    <tr>
      <td>Notify Porfolio Team</td>
      <td><?php echo $department; ?>
    </td>
    </tr>
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
        <a href="javascript:history.back()"  class="btn btn-primary"><span class="glyphicon glyphicon-step-backward"></span> Back </a>
      <?php } ?>

      <?php if($status == 1){ ?>
        <?php if($uaccess=="true"){?>
          <a href="includes/associated_prj_update.php?ri_level=prj&fscl_year=<?php echo $fscl_year?>&name=<?php echo $name?>&proj_name=<?php echo $project_nm?>&ri_type=<?php echo $RIType ?>&rikey=<?php echo $RiskAndIssue_Key?>&status=<?php echo $status ?>"  class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span> Update </a>
        <?php } ?>
      <a href="mailto:?subject=RISKS AND ISSUES - <?php echo $name;?>
      &body=%0D%0A----------------------------------------RISKS AND ISSUES DETAILS ----------------------------------------
      %0D%0ARisk/Issue Name: <?php echo $name;?>
      %0D%0AType: <?php echo $RIType?>
      %0D%0AProject: <?php echo $project_nm?>
      %0D%0AIssue Descriptor: <?php echo $descriptor ?>
      %0D%0ADescription: <?php echo $description?>
      %0D%0ADrivers: <?php echo $Driversx?>
      %0D%0AImpact Area: <?php echo $impactArea2?>
      %0D%0AImpact Level: <?php echo $impactLevel2?>
      %0D%0APOC Group/Name: <?php echo $individual?>
      %0D%0AResponse Strategy: <?php echo $responseStrategy2?>
      %0D%0AForecasted Resolution Date:: <?php if($unknown == "off"){ echo $date; } else { echo "Unknown"; }?>
      %0D%0AAssociated Projects: <?php echo $assocProject?>
      %0D%0AAction Plan: <?php echo $actionPlan?>
      %0D%0ADate Closed: <?php convtimex($dateClosed)?>
      " 
      class="btn btn-primary"><span class="glyphicon glyphicon-envelope"></span> Email </a>
      <?php } ?>
    </div>
  </form>
</div>
</body>
</html>