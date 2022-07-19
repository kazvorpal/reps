<?php 
include ("../../includes/functions.php");
include ("../../db_conf.php");
include ("../../data/emo_data.php");

      //$uid = $_GET['uid'];
      $ri_type = $_GET['ri_type'];
      //$action = $_GET['action'];
      //$fiscal_year =  $_GET['fscl_year'];
      //$tempid =  $_GET['tempid'];
      $ri_level = $_GET['ri_level'];
      $ri_proj_name = $_GET['proj_name'];
      $rikey = $_GET['rikey'];


//FIND PROJECT RISK AND ISSUES 1.26.2022
$RiskAndIssue_Key = $_GET['rikey'];
$fscl_year = $_GET['fscl_year'];
$proj_name = $_GET['proj_name'];
$name = $_GET['name'];
$status = $_GET['status'];
  
$sql_risk_issue = "select * from [RI_MGT].[fn_GetListOfRiskAndIssuesForEPSProject]  ($fscl_year,'$proj_name') where RiskAndIssue_Key = $RiskAndIssue_Key";
$stmt_risk_issue = sqlsrv_query( $data_conn, $sql_risk_issue );
$row_risk_issue = sqlsrv_fetch_array($stmt_risk_issue, SQLSRV_FETCH_ASSOC);
//$row_risk_issue['columnName'];
//echo $sql_risk_issue;

//RI ROW COUNT
$sql_riRows = "SELECT COUNT(*) as ttlRows
               FROM (
                select distinct RiskAndIssue_Key,PROJECT_key, Issue_Descriptor, RIDescription_Txt, RILevel_Cd, RIType_Cd, RI_Nm,ActionPlanStatus_Cd 
                from RI_MGT.fn_GetListOfAssociatedProjectsForProjectRINm('$name',1) 
                where RI_Nm != '$name') a ";
$stmt_riRows = sqlsrv_query( $data_conn, $sql_riRows);
$row_riRows = sqlsrv_fetch_array($stmt_riRows, SQLSRV_FETCH_ASSOC);
//echo $row_riRows['ttlRows'];
//echo $sql_riRows;
//exit();

//GET DRIVERS
$sql_risk_issue_driver = "select * from [RI_MGT].[fn_GetListOfRiskAndIssuesForEPSProject]  ($fscl_year,'$proj_name') where RiskAndIssue_Key = $RiskAndIssue_Key";
$stmt_risk_issue_driver = sqlsrv_query( $data_conn, $sql_risk_issue_driver );
// $row_risk_issue_driver = sqlsrv_fetch_array($stmt_risk_issue_driver, SQLSRV_FETCH_ASSOC);
// echo $row_risk_issue_driver['Driver_Nm]; 			

//GET DRIVERS LIST 
$sql_ri_driver_lst = "select Driver_Nm from [RI_MGT].[fn_GetListOfRiskAndIssuesForEPSProject]  ($fscl_year,'$proj_name') where RiskAndIssue_Key = $RiskAndIssue_Key";
$stmt_ri_driver_lst = sqlsrv_query( $data_conn, $sql_ri_driver_lst );
// $row_ri_driver_lst = sqlsrv_fetch_array($stmt_ri_driver_lst, SQLSRV_FETCH_ASSOC);
// echo $row_ri_driver_lst['Driver_Nm]; 			

//GET ASSOCIATED PROJECTS
//$ri_name = $row_risk_issue['RI_Nm'];
$sql_risk_issue_assoc_proj = "select distinct RiskAndIssue_Key,PROJECT_key, proj_nm, Issue_Descriptor, RIDescription_Txt, RILevel_Cd, RIType_Cd, RI_Nm,ActionPlanStatus_Cd from RI_MGT.fn_GetListOfAssociatedProjectsForProjectRINm('$name',$status) where RI_Nm != '$name' ";
$stmt_risk_issue_assoc_proj = sqlsrv_query( $data_conn, $sql_risk_issue_assoc_proj );
// $row_risk_issue_assoc_proj = sqlsrv_fetch_array($stmt_risk_issue_assoc_proj, SQLSRV_FETCH_ASSOC);
// echo $row_risk_issue_assoc_proj['RI_Nm]; 		
//echo $sql_risk_issue_assoc_proj;	

//DECLARE
$name = $row_risk_issue['RI_Nm'];
$RILevel = "ri_level";
$RIType = $row_risk_issue['RIType_Cd'];
$createdFrom  = "";
$programs = "";
$project_nm = $row_risk_issue['proj_nm'];
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

if($_GET['ri_type'] == "Risk"){
  $gotoPage = "../project-risk-update.php";
} else {
  $gotoPage = "../project-issue-update.php";
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<style>
    .box {
    border: 1px solid #BCBCBC;
    border-radius: 5px;
    padding: 5px;
    }
    .finePrint {
    font-size: 9px; 
    color: red; 
    }
</style>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"> 
  <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
  <link rel="stylesheet" href="../steps/style.css" type='text/css'> 
  <link href='http://fonts.googleapis.com/css?family=Mulish' rel='stylesheet' type='text/css'>

  
  <script language="javascript">
	$(document).ready(function() {
    	$('#fiscal_year').multiselect({
          includeSelectAllOption: true,
        });
		$('#pStatus').multiselect({
          includeSelectAllOption: true,
        });
		$('#owner').multiselect({
          includeSelectAllOption: true,
        });
		$('#program').multiselect({
          includeSelectAllOption: true,
        });
		$('#subprogram').multiselect({
          includeSelectAllOption: true,
        });
		$('#region').multiselect({
          includeSelectAllOption: true,
        });
		$('#market').multiselect({
          includeSelectAllOption: true,
        });
    	$('#facility').multiselect({
          includeSelectAllOption: true,
        });
		$('#fiscal_year2').multiselect({
          includeSelectAllOption: true,
        });
		$('#program2').multiselect({
          includeSelectAllOption: true,
        });
  });
</script>
<script language="JavaScript">
function toggle(source) {
  checkboxes = document.getElementsByName('proj_select[]');
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
  }
}
</script>
</head>

<body style="background: #F8F8F8; font-family:Mulish, serif;">
<!-- PROGRESS BAR -->
<div class="container">       
            <div class="row bs-wizard" style="border-bottom:0;">
                
                <div class="col-xs-3 bs-wizard-step active">
                  <div class="text-center bs-wizard-stepnum">STEP 1</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="bs-wizard-dot"></a>
                  <div class="bs-wizard-info text-center">Select Associated Risks/Issues</div>
                </div>
                
                <div class="col-xs-3 bs-wizard-step disabled"><!-- complete -->
                  <div class="text-center bs-wizard-stepnum">STEP 2</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="bs-wizard-dot"></a>
                  <div class="bs-wizard-info text-center">Edit Risk or Issue Details</div>
                </div>
                
                <div class="col-xs-3 bs-wizard-step disabled"><!-- complete -->
                  <div class="text-center bs-wizard-stepnum">STEP 3</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="bs-wizard-dot"></a>
                  <div class="bs-wizard-info text-center">Confirm Your Updates</div>
                </div>
                
                <div class="col-xs-3 bs-wizard-step disabled"><!-- active -->
                  <div class="text-center bs-wizard-stepnum">STEP 4</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="bs-wizard-dot"></a>
                  <div class="bs-wizard-info text-center">Completed</div>
                </div>
            </div>
  </div>
  <!-- END PROGRESS BAR -->
  <div align="center" class="finePrint"><?php //echo $sql_por?></div>
  <div align="Center">
    <h3>
      <?php
      if($ri_type == "Risk" && $ri_level == "prj"){
        echo "BULK EDIT PROJECT RISKS";
      } elseif ($ri_type == "RISKS" && $ri_level == "prg"){
        echo "BULK EDIT PROGRAM RISKS";
      } elseif ($ri_type == "Issue" && $ri_level == "prj"){
        echo "BULK EDIT PROJECT ISSUES";
      } else {
        echo "BULK EDIT PROGRAM ISSUES";
      }
      ?>
    </h3>
</div>
<div align="center"><?php echo $name; ?></div>

<!-- <div align="center">Select any project associated with this Risk or Issue</div> --><br>
<form action="<?php echo $gotoPage; ?>" method="post" class="navbar-form navbar-center" id="assProjects" title="Associated Projects">
<input type="hidden" name="rikey" value="<?php echo $_GET['rikey']; ?>">
<input type="hidden" name="fscl_year" value="<?php echo $_GET['fscl_year']; ?>">
<input type="hidden" name="proj_name" value="<?php echo $_GET['proj_name']; ?>">
<input type="hidden" name="name" value="<?php echo $name;?>">
<input type="hidden" name="drivertime" value="<?php while ($row_ri_driver_lst = sqlsrv_fetch_array($stmt_ri_driver_lst, SQLSRV_FETCH_ASSOC)) { echo $row_ri_driver_lst['Driver_Nm'] . ','; } ?>">
<input type="hidden" name="status" value="<?php echo $status;?>">

<?php if($row_riRows['ttlRows'] > 0) { ?>
  <div align="center" class="aalert alert-info" style="padding:20px; font-size:18px; font-color: #000000;">It is <b><u><i>optional</i></u></b> to uncheck Associated <?php echo $row_risk_issue['RIType_Cd']?>(s) if you don't want to include them in this update.</div>
<table class="table table-bordered table-striped table-hover" width="90%">
  <tr>
    <th><input type="checkbox" name="checkbox" id="checkbox" onClick="toggle(this)"></th>
    <th>Risk/Issues Name</th>
    <th>Project Name</th>
    <th>Action Plan</th>
  </tr>
  <tr>
    <td><input type="checkbox" name="dummy" id="dummy" value="" disabled checked></td> <!-- DUMMY CHECKBOX -->
    <td bgcolor="#d9edf7"><?php echo $row_risk_issue['RI_Nm']; ?> [ORIGINATING R/I]</td>
    <td bgcolor="#d9edf7"><?php echo $row_risk_issue['proj_nm']; ?></td>
    <td bgcolor="#d9edf7"><?php echo $row_risk_issue['RIDescription_Txt']; ?></td>
  </tr>
  <?php while ($row_risk_issue_assoc_proj = sqlsrv_fetch_array($stmt_risk_issue_assoc_proj, SQLSRV_FETCH_ASSOC)) { ?>
  <tr>
    <td><input type="checkbox" name="proj_select[]" id="proj_select" value="<?php echo $row_risk_issue_assoc_proj['RiskAndIssue_Key'];?>" checked></td>
    <td><?php echo $row_risk_issue_assoc_proj['RI_Nm'];?></td>
    <td><?php echo $row_risk_issue_assoc_proj['proj_nm'];?></td>
    <td><?php echo $row_risk_issue_assoc_proj['RIDescription_Txt'];?></td>
  </tr>
  <?php } ?>
</table>
<?php } else { echo "<div align='center' class='alert alert-info'>There are no Associate Risks/Issues related to " . $name . "<div><br><br>"; }?>

<div align='center'> 
  <a href="javascript:history.back()"  class="btn btn-primary"><span class="glyphicon glyphicon-step-backward" title="Back to previous page"></span> Back </a>
  <input name="selectedProjects" type="submit" id="selectedProjects" form="assProjects" value="Next >" class="btn btn-primary"> 
</div>
</form>
			  
</body>
</html>