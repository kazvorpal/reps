<?php 
include ("../includes/functions.php");
include ("../db_conf.php");
include ("../data/emo_data.php");

//FIND PROJECT RISK AND ISSUES FUNCTION 1.26.2022
$RiskAndIssue_Key = $_GET['rikey'];
$fscl_year = $_GET['fscl_year'];
$proj_name = $_GET['proj_name'];
$prog_name = $_GET['prg_nm'];
  
$sql_risk_issue = "select * from [RI_MGT].[fn_GetListOfRiskAndIssuesForProgram] (2022, 'Advance Access Network') where RiskAndIssue_Key = $RiskAndIssue_Key";
$stmt_risk_issue = sqlsrv_query( $conn_COX_QA, $sql_risk_issue );
$row_risk_issue = sqlsrv_fetch_array($stmt_risk_issue, SQLSRV_FETCH_ASSOC);
// echo $row_risk_issue['Risk_Issue_Name']; 			

//DECLARE
$name = $row_risk_issue['RI_Nm'];
$RILevel = "";
$RIType = $row_risk_issue['RIType_Cd'];
$createdFrom  = "";
$programs = $row_risk_issue['Program_Nm'];
$prject_nm = "";
$descriptor  = $row_risk_issue['ScopeDescriptor_Txt'];
$description = $row_risk_issue['RIDescription_Txt'];
$regionx = "";
$Driversx = $row_risk_issue['Driver_Nm'];
$impactArea2 = $row_risk_issue['ImpactArea_Nm'];
$impactLevel2 = $row_risk_issue['ImpactLevel_Nm'];
$individual = $row_risk_issue['POC_Nm'];
$internalExternal = $row_risk_issue['POC_Nm'];
$responseStrategy2 = $row_risk_issue['ResponseStrategy_Nm'];
$unknown = ""; // IF DATE IS EMPTY
$date = $row_risk_issue['ForecastedResolution_Dt'];
$transProgMan = $row_risk_issue['TransferredPM_Flg'];
$opportunity = $row_risk_issue['Opportunity_Txt'];
$assocProject = "";
$actionPlan = $row_risk_issue['ActionPlanStatus_Cd'];
$dateClosed = "";
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
      <td>Issue Descriptor</td>
      <td><?php echo $descriptor ; ?></td>
    </tr>
    <tr>
      <td>Description</td>
      <td><?php echo $description; ?></td>
    </tr>
    <tr>
      <td>Drivers</td>
      <td><?php echo $Driversx; ?></td>
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
      <td>Individual/Team POC</td>
      <td><?php echo $individual; ?></td>
    </tr>
      <tr>
      <td>Response Strategy</td>
      <td><?php echo $responseStrategy2; ?></td>
    </tr>
    <tr>
      <td>Task POC Date</td>
      <td>
        <?php if($unknown == "off"){
        echo $date; 
        } else {
        echo "Unknown";
        }
        ?>
        </td>
    </tr>
    <tr>
      <td>Tranfer to Program Manager</td>
      <td>
        <?php 
            if(!empty($_POST['TransfertoProgramManager'])) {
            //echo $_POST['TransfertoProgramManager']; 
              echo "Yes"; 
            } else {
              echo "No";
            }
        ?>
    </td>
    </tr>
<?php if(isset($_POST['opportunity'])) { ?>
    <tr>
      <td>Opportunity</td>
      <td><?php $_POST['opportunity']; ?>
    </td>
    </tr>
<?php } ?>
    <tr>
      <td>Action Plan</td>
      <td><?php echo $actionPlan; ?>
    </td>
    </tr>
    <tr>
      <td>Date Closed</td>
      <td>
        <?php 
        if($dateClosed == "NULL") {
        echo "Open";
        } else { 
        echo $dateClosed;  
        }
        ?>
    </td>
    </tr>
  </tbody>
</table>
<div align="center">
    <a href="javascript:history.back()"  class="btn btn-primary"><span class="glyphicon glyphicon-step-backward"></span> Back </a>
    <!--<input type="submit" name="submit2" id="submit2" value="Submit" class="btn btn-primary"> -->
</div>
</form>

</div>
<?php
    //print_r($_POST);

?>
</body>
</html>