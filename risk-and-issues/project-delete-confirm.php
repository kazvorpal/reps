<?php 
//echo str_replace('  ', '&nbsp; ', nl2br(print_r($_POST, true)));
include ("../includes/functions.php");
include ("../includes/big_bro_functions.php");
include ("../db_conf.php");
include ("../data/emo_data.php");
include ("../sql/RI_Internal_External.php");

//$action = $_GET['action']; //new
//$temp_id = $_GET['tempid'];
$user_id = preg_replace("/^.+\\\\/", "", $_SERVER["AUTH_USER"]);
//$ass_project = $row_projID['PROJ_NM'];
//$forcastDate =  date('m/d/Y');

//FIND PROJECT RISK AND ISSUES
$RiskAndIssue_Key = $_POST['rikey'];
$fscl_year = $_POST['fscl_year'];
$proj_name = $_POST['proj_name'];
$status = $_POST['status'];
$del_project = implode(",",$_POST['del_proj_select']);
  
$sql_risk_issue = "select * from [RI_MGT].[fn_GetListOfRiskAndIssuesForEPSProject]  ($fscl_year,'$proj_name') where RiskAndIssue_Key = $RiskAndIssue_Key";
$stmt_risk_issue = sqlsrv_query( $data_conn, $sql_risk_issue );
$row_risk_issue = sqlsrv_fetch_array($stmt_risk_issue, SQLSRV_FETCH_ASSOC);
// echo $row_risk_issue['Risk_Issue_Name']; 
//echo $sql_risk_issue . "<br>";		

//GET DRIVERS
$sql_risk_issue_driver = "select * from [RI_MGT].[fn_GetListOfRiskAndIssuesForEPSProject]  ($fscl_year,'$proj_name') where RiskAndIssue_Key = $RiskAndIssue_Key";
$stmt_risk_issue_driver = sqlsrv_query( $data_conn, $sql_risk_issue_driver );
// $row_risk_issue_driver = sqlsrv_fetch_array($stmt_risk_issue_driver, SQLSRV_FETCH_ASSOC);
// echo $row_risk_issue_driver['Driver_Nm]; 			
//echo $sql_risk_issue_driver;

//DECLARE
$changeLogKey = 5;
$name = trim($row_risk_issue['RI_Nm']);
$RILevel = "";
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
$probability = $row_risk_issue['RiskProbability_Nm'];
$individual = $row_risk_issue['POC_Nm'];
$internalExternal = $row_risk_issue['POC_Nm'];
$responseStrategy2 = $row_risk_issue['ResponseStrategy_Nm'];
$unknown = ""; // IF DATE IS EMPTY
$date = $row_risk_issue['ForecastedResolution_Dt'];
$transProgMan = $row_risk_issue['TransferredPM_Flg'];
$opportunity = $row_risk_issue['Opportunity_Txt'];
$actionPlan = $row_risk_issue['ActionPlanStatus_Cd'];
$DateClosed = $row_risk_issue['RIClosed_Dt'];
$driverList = rtrim($_POST['drivertime'], ","); 
$driverArr = explode(",", $driverList); 
$RIClosed_Dt = $row_risk_issue['RIClosed_Dt'];
$raidLog = $row_risk_issue['RaidLog_Flg'];
$riskRealized =  $row_risk_issue['RiskRealized_Flg']; 
$department = $row_risk_issue['POC_Department'];
$del_proj_select = $_POST['del_proj_select'];
$POC_Nm = $row_risk_issue['POC_Nm'];
$changeLogReason = $row_risk_issue['Reason_Txt'];
$changeLogName = $row_risk_issue['RequestAction_Nm'];
$changeLogActionKey = $row_risk_issue['RequestAction_Key'];

$changeLogActionVal = $row_risk_issue['RequestAction_Key'];
$changeLogReason = $row_risk_issue['Reason_Txt'];
$changeLogName = $row_risk_issue['RequestAction_Nm'];

$Estimated_Act= ($row_risk_issue['PRJI_Estimated_Act_Ts']) ? $row_risk_issue['PRJI_Estimated_Act_Ts']->format('Y-m-d') : '';
$Estimated_Mig= ($row_risk_issue['PRJI_Estimated_Mig_Ts']) ? $row_risk_issue['PRJI_Estimated_Mig_Ts']->format('Y-m-d') : '';


$groupID = "";
if (isset($_POST['groupID'])) {
  $groupID = $_POST['groupID'];
}

$disble_it = "";
if (isset($_POST['del_proj_select'])){
  $del_proj_select = implode(",", $_POST['del_proj_select']);
  $disble_it = " disable";
}

if(!empty($_POST['proj_select'])) {
  $assocProject = implode(",",$_POST['proj_select']) . "," . $RiskAndIssue_Key ;
} else {
  $assocProject = $RiskAndIssue_Key;
}

//ASSOCIATED RISK AND ISSUES
//$ri_name = $row_risk_issue['RI_Nm'];
$sql_risk_issue_assoc_proj = "DECLARE @PROJS VARCHAR(MAX)
    SELECT @PROJS = COALESCE(@PROJS+',','')+ CAST(Proj_Nm AS VARCHAR(MAX))
    FROM [RI_MGT].[fn_GetListOfAssociatedProjectsForProjectRINm]('$name',1) 
    WHERE RiskAndIssue_Key IN ($del_proj_select)
    SELECT @PROJS AS Proj_Nm";
$stmt_risk_issue_assoc_proj = sqlsrv_query( $data_conn, $sql_risk_issue_assoc_proj );
 $row_risk_issue_assoc_proj = sqlsrv_fetch_array($stmt_risk_issue_assoc_proj, SQLSRV_FETCH_ASSOC);
// echo $row_risk_issue_assoc_proj['Proj_Nm']; 			
//echo "<br>" . $sql_risk_issue_assoc_proj; //exit();

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Carolino, Gil">
    <title>RePS Reporting - Cox Communications</title>

  <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script>-->
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"> 
  <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
  <link rel="stylesheet" href="steps/style.css" type='text/css'> 
  <link rel="stylesheet" href="includes/ri-styles.css" />
  <link rel="stylesheet" href="../colorbox-master/example1/colorbox.css" />
  <script src="../colorbox-master/jquery.colorbox.js"></script>
<script>
$(document).ready(function(){
				//Examples of how to assign the Colorbox event to elements
				$(".group1").colorbox({rel:'group1'});
				$(".group2").colorbox({rel:'group2', transition:"fade"});
				$(".group3").colorbox({rel:'group3', transition:"none", width:"75%", height:"75%"});
				$(".group4").colorbox({rel:'group4', slideshow:true});
				$(".ajax").colorbox();
				$(".youtube").colorbox({iframe:true, innerWidth:640, innerHeight:390});
				$(".vimeo").colorbox({iframe:true, innerWidth:500, innerHeight:409});
				$(".iframe").colorbox({iframe:true, width:"900", height:"600", scrolling:false});
				$(".dno").colorbox({iframe:true, width:"75%", height:"90%", scrolling:true});
				$(".mapframe").colorbox({iframe:true, width:"95%", height:"95%", scrolling:true});
				$(".miniframe").colorbox({iframe:true, width:"30%", height:"50%", scrolling:true});
				$(".ocdframe").colorbox({iframe:true, width:"60%", height:"90%", scrolling:true, escKey: false, overlayClose: false});
				$(".miframe").colorbox({iframe:true, width:"1500", height:"650", scrolling:true});
				$(".inline").colorbox({inline:true, width:"50%"});
				$(".callbacks").colorbox({
					onOpen:function(){ alert('onOpen: colorbox is about to open'); },
					onLoad:function(){ alert('onLoad: colorbox has started to load the targeted content'); },
					onComplete:function(){ alert('onComplete: colorbox has displayed the loaded content'); },
					onCleanup:function(){ alert('onCleanup: colorbox has begun the close process'); },
					onClosed:function(){ alert('onClosed: colorbox has completely closed'); }
				});

				$('.non-retina').colorbox({rel:'group5', transition:'none'})
				$('.retina').colorbox({rel:'group5', transition:'none', retinaImage:true, retinaUrl:true});
				
				//Example of preserving a JavaScript event for inline calls.
				$("#click").click(function(){ 
					$('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("Open this window again and this message will still be here.");
					return false;
				});
			});
</script>      
<script language="JavaScript">
function toggle(source) {
  checkboxes = document.getElementsByName('proj_select');
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
  }
}
</script>

<script language="JavaScript">
function toggle(source) {
  checkboxes = document.getElementsByName('proj_select');
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
  }
}
</script>
</head>
<body style="background: #F8F8F8; font-family:Mulish, serif;" onload="myFunction(); date.value = frcstDt_temp.value">
<main align="center">
  <!-- PROGRESS BAR -->
<div class="container">       
            <div class="row bs-wizard" style="border-bottom:0;">
                
                <div class="col-xs-3 bs-wizard-step complete">
                  <div class="text-center bs-wizard-stepnum">STEP 1</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="bs-wizard-dot"></a>
                  <div class="bs-wizard-info text-center">Select Associated Projects</div>
                </div>
                
                <div class="col-xs-3 bs-wizard-step active"><!-- complete -->
                  <div class="text-center bs-wizard-stepnum">STEP 2</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="bs-wizard-dot"></a>
                  <div class="bs-wizard-info text-center">Enter Risk or Issue Details</div>
                </div>
                
                <div class="col-xs-3 bs-wizard-step disabled"><!-- complete -->
                  <div class="text-center bs-wizard-stepnum">STEP 3</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="bs-wizard-dot"></a>
                  <div class="bs-wizard-info text-center">Confirm Your Entry</div>
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

<div align="center">
  <h2>REMOVE PROJECT ASSOCIATION</h2>
</div>
<div class="finePrint">
<?php  
  //echo "Project UID: " . $row_projID['PROJ_ID'] . "<br>"; 
  //echo "Logged in as: " . $user_id . "<br>"; 
  //echo "Project Owner: " . $row_projID['PROJ_OWNR_NM'] . "<br>"; 
  //echo "Temp ID: " . $_GET['tempid'];
  //echo "Location Code: " . $row_projID['EPSLocation_Cd']; 
?>
</div>
<div style="padding: 20px;">
  <form action="update-confirm.php" method="post" id="projectRisk">
  <input name="changeLogKey" type="hidden" id="changeLogKey" value="<?php echo $changeLogKey;?>">
  <input name="userId" type="hidden" id="userId " value="<?php echo $user_id; ?>">
  <input name="formName" type="hidden" id="formName" value="PRJR">
  <input name="formType" type="hidden" id="formType" value="New">
  <input name="fiscalYer" type="hidden" id="fiscalYer" value="<?php echo $fscl_year; ?>">
  <input name="RIType" type="hidden" id="RIType" value="<?php echo $RIType; ?>">
  <input name="RILevel" type="hidden" id="RILevel" value="Project">
  <input name="frcstDt_temp" type="hidden" id="frcstDt_temp" value="<?php if(!empty($date)){ echo convDate($date);} ?>">
  <input name="assocProjects" type="hidden" id="assocProjects" value="<?php //echo $assocProject; ?>">
  <input name="RiskAndIssue_Key" type="hidden" id="RiskAndIssue_Key" value="<?php echo $assocProject; ?>">
  <input name="assocProjectsKeys" type="hidden" id="assocProjectsKeys" value="<?php $assocProject; ?>">
  <input name="regionKeys" type="hidden" id="regionKeys" value="">
  <input name="programKeys" type="hidden" id="programKeys" value="">
  <input name="status" type="hidden" id="status" value="<?php echo $status; ?>">
  <input name="riskRealized" type="hidden" id="riskRealized" value="<?php echo $riskRealized; ?>">
  <input name="raidLog" type="hidden" value="No" id="raidLog">
  <input name="groupID" type="hidden" value="<?php echo $groupID; ?>">
  <input name="del_proj_select" type="hidden" value="<?php echo $del_proj_select; ?>">
  <input name="DateClosed"type="hidden" id="DateClosed" value="<?php echo date('Y-m-d') ?>">
  <input name="formaction" type="hidden" id="formaction" value="update">
  <input name="delete" type="hidden" id="delete" value="1">
  <input name="Individual" type="hidden" id="Individual" value="">
  <input name="dispAssocProj" type="hidden" id="dispAssocProj" value="<?php echo str_replace(",", "<br>",$row_risk_issue_assoc_proj['Proj_Nm']) ?>">
  <input name="changeLogAction" type="hidden" id="changeLogAction" value="<?php echo $changeLogActionKey. ":" . $changeLogName ?>"> 
  <input name="changeLogReason" type="hidden" id="changeLogReason" value="<?php echo $changeLogReason ?>">

  <div class="alert alert-danger">
  <div align="left">
    <span class="glyphicon glyphicon-warning-sign"></span> Project(s) selected for removal will have their associated risks/issues deleted. You won't be able to make any updates. If removal of this project association was unintentional, do NOT submit this form..
  </div>
  </br>
      <table width="100%" border="0" cellpadding="10" cellspacing="10">
        <tr>
          <td colspan="3" align="left"><h4 style="color: #00aaf5"><?php if(empty($del_proj_select)) {echo strtoupper($RIType);} else { echo "PROJECT";}?> ASSOCIATION</h4></td>
        </tr>
        <tr>
          <td colspan="3">
            <div class="box <?php echo $disble_it;?>" align="left" style="font-size: 12px;">
              <?php 
                echo str_replace(",", "<br>",$row_risk_issue_assoc_proj['Proj_Nm']);?>
            </div>
		      </td>
        </tr>
      </table>
  </div>
  <hr>
    <table width="100%" border="0" cellpadding="10" cellspacing="10">
      <tbody>
        <tr>
          <th colspan="3" align="left">
            <div id="myRisk">
              <h4 style="color: #00aaf5">PROJECT RISK</h4>
              </div>
            <div id="myIssue">
              <h4 style="color: #00aaf5">PROJECT ISSUE</h4>
              </div>
            
          </th>
          </tr>
        <tr>
          <td colspan="3" align="left">
			<div class="box <?php echo $disble_it;?>">
			<table width="100%" border="0" cellpadding="10" cellspacing="10">
            <tbody>
              <tr>
                <td><div id="myDIV">
                  <label for="Created From">Created From</label>
                  <br>
                  <input name="CreatedFrom" type="text" class="form-control" id="Created From">
                </div></td>
                </tr>
              <tr>
                <td><label for="Created From">Name</label>
                  <br>
                  <input name="Namex" type="text" readonly class="form-control" id="Namex" value="<?php echo $name ?>" >
                </td>
                </tr>
              <tr>
                <td><label for="Created From">Risk Descriptor<br>
                  </label>
                  <input name="Descriptor" type="text" class="form-control" id="Descriptor" maxlength="30" value="<?php echo $descriptor ?>" readonly>
                </td>
                </tr>
              <tr>
                <td><label for="Description">Description<br>
            </label>
            <textarea name="Description" cols="120" required="required" class="form-control" id="Description"><?php echo $description ?></textarea>  </td>
                </tr>
            </tbody>
          </table>
		</div>
		</td>
          </tr>
        <tr>
          <td colspan="3" align="left">
            
            </td>
        </tr>

        <tr>
          <td colspan="3" align="left"><h4 style="color: #00aaf5">DRIVERS <a href="includes/instructions.php" class="dno"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
  <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
</svg></a></h4>
            <div class="box subscriber <?php echo $disble_it;?>">
            <table width="100%" border="0">
                <tr>
                  <td width="51%"><label>
                    <input type="radio" name="Drivers[]" value="1"  id="Drivers_0" class="required_group" <?php if(in_array("Material Delay", $driverArr)) { echo "checked";} ?> onclick="this.checked = false;">
                    Material Delay</label></td>
                  <td width="49%"><label>
                    <input type="radio" name="Drivers[]" value="6" id="Drivers_10" class="required_group" <?php if(in_array("Project Dependency", $driverArr)) { echo "checked";} ?> onclick="this.checked = false;">
                    Project Dependency</label></td>
                  </tr>
                <tr>
                  <td><label>
                    <input type="radio" name="Drivers[]" value="2" id="Drivers_1" class="required_group" <?php if(in_array("Shipping/Receiving Delay", $driverArr)) { echo "checked";} ?> onclick="this.checked = false;">
                    Shipping/Receiving Delay</label></td>
                  <td><label>
                    <input type="radio" name="Drivers[]" value="7" id="Drivers_6" class="required_group" <?php if(in_array("Budget/Funding", $driverArr)) { echo "checked";} ?> onclick="this.checked = false;">
                    Budget/Funding</label></td>
                  </tr>
                <tr>
                  <td><label>
                    <input type="radio" name="Drivers[]" value="3" id="Drivers_2" class="required_group" <?php if(in_array("Ordering Error", $driverArr)) { echo "checked";} ?> onclick="this.checked = false;">
                    Ordering Error</label></td>
                  <td><label>
                    <input type="radio" name="Drivers[]" value="8" id="Drivers_7" class="required_group" <?php if(in_array("Design/Scope Change", $driverArr)) { echo "checked";} ?> onclick="this.checked = false;">
                    Design/Scope Change</label></td>
                  </tr>
                <tr>
                  <td><label>
                    <input type="radio" name="Drivers[]" value="4" id="Drivers_3" class="required_group" <?php if(in_array("People Resource", $driverArr)) { echo "checked";} ?> onclick="this.checked = false;">
                    People Resource</label></td>
                  <td><label>
                    <input type="radio" name="Drivers[]" value="9" id="Drivers_8" class="required_group" <?php if(in_array("Admin Error", $driverArr)) { echo "checked";} ?> onclick="this.checked = false;">
                    Admin Error</label></td>
                  </tr>
                <tr>
                  <td><label>
                    <input type="radio" name="Drivers[]" value="5" id="Drivers_4" class="required_group" <?php if(in_array("3PL Resource", $driverArr)) { echo "checked";} ?> onclick="this.checked = false;">
                    3PL Resource</label></td>
                  <td><label>
                    <input type="radio" name="Drivers[]" value="10" id="Drivers_9" class="required_group" <?php if(in_array("External Forces", $driverArr)) { echo "checked";} ?> onclick="this.checked = false;">
                    External Forces</label></td>
                  </tr>
                </table>
              </div>
          </td>
          </tr>
        <tr>
          <td colspan="3" align="left"></td>
        </tr>
        <tr>
          <td colspan="3" align="left"><h4  style="color: #00aaf5">IMPACT</h4></td>
          </tr>
        <tr>
          <td colspan="3" align="left">
			<div class="box <?php echo $disble_it;?>"> 
			<table width="100%" border="0">
            <tbody>

              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>

              <tr>
                <td valign="top">
                  <table width="200" border="0">
                  <tr>
                  <strong>Impacted Area </strong>
                  <a href="includes/instructions-impact-area.php" class="dno"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                    <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                  </svg></a>
                  </tr>
                  <?php while($row_impArea= sqlsrv_fetch_array( $stmt_impArea , SQLSRV_FETCH_ASSOC)) { ?>
                    <tr>
                    <td><label>
                      <input type="radio" name="ImpactArea" value="<?php echo $row_impArea['ImpactArea_Key'] ?>" id="ImpactArea_<?php echo $row_impArea['ImpactArea_Key'] ?>" <?php if($impactArea2==$row_impArea['ImpactArea_Nm']){echo "checked";}?> onclick="this.checked = false;">
                      <?php echo $row_impArea['ImpactArea_Nm'] ?></label></td>
                    </tr>
                  <?php } ?>
                  </table>
                </td>
                <td valign="top">
                  <table width="200" border="0">
                    <tr>
                      <strong>Impact Level </strong>
                      <a href="includes/instructions-impact-level.php" class="dno"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                      </svg></a>
                    </tr>
                    <?php while($row_imLevel = sqlsrv_fetch_array( $stmt_imLevel , SQLSRV_FETCH_ASSOC)) { ?>
                    <tr>
                      <td><label>
                        <input name="ImpactLevel" type="radio" id="ImpactLevel_<?php echo $row_imLevel['ImpactLevel_Key'] ?>" value="<?php echo $row_imLevel['ImpactLevel_Key'] ?>" required <?php if($impactLevel2==$row_imLevel['ImpactLevel_Nm']){echo "checked";}?> onclick="this.checked = false;">
                        <?php echo $row_imLevel['ImpactLevel_Nm'] ?></label></td>
                      </tr>
                    <?php } ?>  
                    </table>
                  </td>
                <td colspan="2" valign="top">
                  <div id="myDIV2">
                  <?php if($RIType == "Risk") { ?>  
                  <table width="200" border="0">
                      <tr>
                        <td>
                          <strong>Risk Probability Score</strong>
                          </td>
                        </tr>
                        <?php while($row_probability= sqlsrv_fetch_array( $stmt_probability , SQLSRV_FETCH_ASSOC)) { ?>
                          <tr>
                            <td><label>
                              <input name="RiskProbability" type="radio" id="RiskProbability_<?php echo $row_probability['RiskProbability_Key'] ?>" value="<?php echo $row_probability['RiskProbability_Key'] ?>" required <?php if($probability==$row_probability['RiskProbability_Nm']){echo "checked";}?>>
                              <?php echo $row_probability['RiskProbability_Nm'] ?></label>
                            </td>
                          </tr>
                        <?php } ?>
                  </table>
                  <?php } ?>
                  <?php if($RIType == "Issue"){ ?>
                    <input name="RiskProbability" type="hidden" id="RiskProbability" value="">
                  <?php } ?>
                    </div>
                </td>
                </tr>
              </tbody>
          </table>
		</div> 
        </td>
        </tr>
        <!--
        <tr>
          <td colspan="3" align="left"></td>
        </tr>
        <tr>
          <td colspan="3" align="left"><h4 style="color: #00aaf5">CURRENT TASK POC</h4></td>
          </tr>
        <tr>
          <td colspan="3" align="left">
          <div class="box <?php echo $disble_it;?>">
            <label for="Individual">Individual POC *<br></label>
                <select type="text" list="Individual" name="Individual" class="form-control" id="indy" required>
                    <?php while($row_internal  = sqlsrv_fetch_array( $stmt_internal , SQLSRV_FETCH_ASSOC)) { ?>
                      <option value=""></option>
                      <option value="<?php echo $row_internal['POC_Nm'] ;?>" <?php if($POC_Nm == $row_internal['POC_Nm']) { echo "selected";} ?>><?php echo $row_internal['POC_Nm'] . " : " . $row_internal['POC_Department'] ;?></option>
                    <?php } ?>
                </select>  
              <hr>
                <div align="left">
                  <span class="glyphicon glyphicon-edit"></span> <a href="https://coxcomminc.sharepoint.com/teams/engmgmtoffice/Lists/EPS%20Support%20%20Enhancement%20Portal/AllItems.aspx" target="_blank">Request POC Addition</a>
                </div>
          </div>
          </td>
          </tr>
          <script>
            document.getElementById("indy").addEventListener("change", function(){
            const v = this.value.split(" : ");
            this.value = v[0];
            document.getElementById("InternalExternal").value = v[1];
            });
          </script>
        <tr>
          <td colspan="3" align="left">             
          </td>
        </tr>
          -->
        <tr>
          <td colspan="3" align="left"><h4 style="color: #00aaf5">RELATED DATES</h4></td>
        </tr>
        <tr>
          <td colspan="3" align="left">
			<div class="box <?php echo $disble_it;?>">
			<table width="100%" border="0">
            <tbody>
              <tr>
                <td colspan="3">
				<label for="date">Forecasted Resolution Date</label>
				  <div id="dateUnknown" >
				  <input name="date" 
					type="date"
					class="form-control" 
					id="date" 
					value=""
					oninvalid="this.setCustomValidity('You must select a date or check Unknown ')"
					oninput="this.setCustomValidity('')"	
					> 
              </div>  
				</td>
                </tr>
              <tr>
                <td>
				<div id="forcastedDate">
				<input type="checkbox" name="Unknown" id="Unknown" <?php if(empty($date)){ echo "checked"; } ?> onclick="this.checked = false;">
            <label for="Unknown" <?php if(is_null($date)){ echo "checked";  } ?>>Unknown</label>
          </div>  
				</td>
                <td>
					<input type="checkbox" name="TransfertoProgramManager" id="TransfertoProgramManager" <?php if($transProgMan != 0){ echo "checked"; } ?> onclick="this.checked = false;">
					<label for="TransfertoProgramManager">Transfer to Program Manager</label>  
				</td>
                <td>&nbsp;</td>
              </tr>
            </tbody>
          </table>
			  </div>
			</td>
        </tr>
        <tr>
          <td colspan="3" align="left"></hr></td>
        </tr>
        <tr>
          <td colspan="3" align="left"></td>
        </tr>
        <tr>
          <td colspan="3" align="left"><h4 style="color: #00aaf5">RESPONSE STRATEGY</h4></td>
        </tr>
        <tr>
          <td colspan="3" align="left"><div class="box <?php echo $disble_it;?>">
            <table width="246" border="0" cellpadding="5" cellspacing="5">
              <tr>
                <td>&nbsp;</td>
                <td><label>
                  <input type="radio" name="ResponseStrategy" value="1" id="Response_Strategy_0" required <?php if($responseStrategy2=="Avoid" ) { echo "checked";} ?> onclick="this.checked = false;">
                  Avoid</label></td>
                </tr>
              <tr>
                <td>&nbsp;</td>
                <td><label>
                  <input type="radio" name="ResponseStrategy" value="2" id="Response_Strategy_1" required <?php if($responseStrategy2=="Mitigate" ) { echo "checked";} ?> onclick="this.checked = false;">
                  Mitigate</label></td>
                </tr>
              <tr>
                <td width="16">&nbsp;</td>
                <td width="195"><label>
                  <input type="radio" name="ResponseStrategy" value="3" id="Response_Strategy_2" required <?php if($responseStrategy2=="Transfer" ) { echo "checked";} ?> onclick="this.checked = false;">
                  Transfer</label></td>
                </tr>
              <tr>
                <td>&nbsp;</td>
                <td><label>
                  <input type="radio" name="ResponseStrategy" value="4" id="Response_Strategy_3" required <?php if($responseStrategy2=="Accept" ) { echo "checked";} ?> onclick="this.checked = false;">
                  Accept</label></td>
                </tr>
            </table>
          </div>			</td>
        </tr>
        <tr>
          <td colspan="3" align="left"><h4 style="color: #00aaf5">ACTION PLAN</h4><!-- ADD EST DATE FOR CR 3.1.2023-->
          
          <div class="box <?php echo $disble_it;?>">  
            <table width="100%" border="0" cellpadding="5" cellspacing="5">
              <tbody>
                  <tr>
                    <td width="100%">
                          <textarea name="ActionPlan" cols="120" required="required" class="form-control" id="ActionPlan"><?php echo $actionPlan; ?></textarea>  
                          <input type="hidden" name="user" value="<?php echo $user_id ?>">
                          <input type="hidden" name="tempID"value="<?php //echo $temp_id ?>">
                    </td>
                  </tr>
                  <tr>
                    <td>
                    <div align="right" style="margin-top:10px; margin-bottom:10px;">  
                    <a class="btn btn-primary" role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">History</a>
                    </div>
                        <div class="collapse" id="collapseExample">
                          <div class="well">
                            <iframe id="actionPlan" src="action_plan.php?rikey=<?php echo $RiskAndIssue_Key?>" width="100%" frameBorder="0"></iframe>
                          </div>
                        </div>
                    </td>
                  </tr>
              </tbody>
            </table>
          <div>
          </td>
        </tr>
        <tr>
          <td colspan="3" align="left">
          </td>
        </tr>
        <tr>
          <td colspan="3" align="left"></td>
        </tr>
        <tr>
          <td colspan="3" align="left">
          <h4 style="color: #00aaf5">CHANGE LOG REQUEST</H4>
          </td>
        </tr>
        <tr>
          <td colspan="3" align="left">
            <div class="box <?php echo $disble_it?>" align="left">
              <table>
                <tr>
                  <td><label for="changeLogAction">Requested Action</label>
                    <select name="changeLogAction" id="changeLogAction" class="form-control">
                      <option value=""></option> 
                      <?php while($row_changeLogAction = sqlsrv_fetch_array( $stmt_changeLogAction , SQLSRV_FETCH_ASSOC)) { ?>
                        <option value="<?php echo $row_changeLogAction['RequestAction_Key'] . ":" . $row_changeLogAction['RequestAction_Nm'];?>" <?php if($changeLogActionVal == $row_changeLogAction['RequestAction_Key']) { echo "selected" ;} ?>><?php echo $row_changeLogAction['RequestAction_Nm'];?></option>
                      <?php } ?>
                    </select>
                  </td>
                  <td width="20px"></td>
                  <td>
                    <label for="changeLogReason">Reason</label>
                    <input name="changeLogReason" type="text" class="form-control" id="changeLogReason" size="100" value="<?php echo $changeLogReason; ?>">
                  </td>
                </tr>
              </table>
              <!--ESTAMATED DATES NEED TO BE FINSIHED 2.27.2023  -->
              <table>
                <div id="hidden_div">
                  <tr>
                    <td width="213px">
                      <label for="EstActiveDate">Est. Activation Date*</label>
                      <input name="EstActiveDate" type="date" class="form-control" id="EstActiveDate" size="40" value="<?php echo $Estimated_Act; ?>">
                    </td>
                    <td width="20px"></td>
                    <td>
                      <label for="EstMigrateDate">Est. Migration Date*</label>
                      <input name="EstMigrateDate" type="date" class="form-control" id="EstMigrateDate" size="40" value="<?php echo $Estimated_Mig; ?>">
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                  </tr>
                </div>
              </table>  
            </div>
          </td>
        </tr>

<!--
        <tr>
          <td colspan="3" align="left"><h4 style="color: #00aaf5">RISK REALIZED</h4></td>
        </tr>
        <tr>
          <td colspan="3" align="left">
            <div class="box <?php echo $disble_it;?>">
              <table width="50%" border="0">
                <tr>
                  <td colspan="2"><strong>Risk Realized?</strong></td>
                  </tr>
                <tr>
                  <td><label>
                    <input type="radio" name="riskRealized" value="1" id="RiskRelized_0" <?php //if($riskRealized == 1){ echo "checked";} ?>>
                    Yes</label></td>
                  <td><label>
                    <input type="radio" name="riskRealized" value="0" id="RiskRelized_1" <?php //if($riskRealized == 0){ echo "checked";} ?>>
                    No</label></td>
                  </tr>
                </table>
              </div>
			    </td>
        </tr>
        <tr>
        <td colspan="3" align="left"><h4 style="color: #00aaf5">RAID LOG</h4></td>
			  </tr>
        <tr>
          <td colspan="3" align="left">
            <div class="box <?php echo $disble_it;?>">
              <table width="50%" border="0">
                <tr>
                  <td colspan="2"><strong>Notify Portfolio Team</strong></td>
                  </tr>
                <tr>
                  <td><label>
                    <input type="radio" name="raidLog" value="Yes" id="raid_0"<?php //if($raidLog == 1) {echo "checked";}?>>
                    Yes</label></td>
                  <td><label>
                    <input type="radio" name="raidLog" value="No" id="raid_1" <?php //if($raidLog == 0) {echo "checked";}?>>
                    No</label></td>
                  </tr>
                </table>
              </div>
			    </td>
        </tr>
        <tr>
              <td colspan="3" align="left"><h4 style="color: #00aaf5">DATE CLOSED</h4></td>
            </tr>
        <tr>
          <td colspan="3" align="left">
          <div class="box <?php echo $disble_it;?>">
            <table width="100%" border="0">
            <tbody>
              <tr>
               
              <td colspan="2">
                  <label for="DateClosed">Date Closed:</label>
                  <input type="date" name="DateClosed" id="DateClosed" class="form-control">
                  <input type="checkbox" name="TransfertoProgramManager2" id="TransfertoProgramManager2">
                  <label for="TransfertoProgramManager2">Transfer to Program Manager</label>
                </td>
              </tr>
            -->
              <tr>
                <td width="33%">&nbsp;</td>
                <td width="33%" align="center" valign="bottom">&nbsp;</td>
                </tr>
              </tbody>
            </table></div></td>
          </tr>
        <tr>
          <td colspan="3" align="left" valign="middle"></td>
        </tr>
        <tr>
          <td colspan="3" align="right" valign="middle">&nbsp;</td>
        </tr>
      </tbody>
    </table>
    <div align="right">
    <button type="button" class="btn btn-primary" onclick="myConfirmation()"><span class="glyphicon glyphicon-step-backward"></span> Back </button>
    <button type="submit" class="btn btn-primary">Review <span class="glyphicon glyphicon-step-forward"></span></button>  
    </div>
  </form>
</div>
</main>

<script>
function myFunction() {
  var x = document.getElementById("myDIV");
  if (x.style.display === "none") {
    x.style.display = "none";
  } else {
    x.style.display = "none";
  }
  
  var y = document.getElementById("myDIV2");
  if (y.style.display === "none") {
    y.style.display = "block";
  } else {
    y.style.display = "block";
  }

  var z = document.getElementById("myIssue");
  if (z.style.display === "none") {
    z.style.display = "none";
  } else {
    z.style.display = "none";
  }

  var w = document.getElementById("myRisk");
  if (w.style.display === "none") {
    w.style.display = "block";
  } else {
    w.style.display = "block";
  }

}

function myFunctionOff() {
  var x = document.getElementById("myDIV");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "block";
  }
  
  var y = document.getElementById("myDIV2");
  if (y.style.display === "none") {
    y.style.display = "none";
  } else {
    y.style.display = "none";
  }
  
  var z = document.getElementById("myIssue");
  if (z.style.display === "none") {
    z.style.display = "block";
  } else {
    z.style.display = "block";
  }

  var w = document.getElementById("myRisk");
  if (w.style.display === "none") {
    w.style.display = "none";
  } else {
    w.style.display = "none";
  }

}

// jQuery(function ($) {
//     var $inputs = $('input[name=date],input[name=unknown]');
//     $inputs.on('input', function () {
//         // Set the required property of the other input to false if this input is not empty.
//         $inputs.not(this).prop('required', !$(this).val().length);
//     });
// });
function validateGrp() {
  let things = document.querySelectorAll('.required_group')
  let checked = 0;
  for (let thing of things) {
    thing.checked && checked++
  }
  if (checked) {
    things[things.length - 1].setCustomValidity("");
    document.getElementById('checkGroup').submit();
  } else {
    things[things.length - 1].setCustomValidity("You must select at least one Driver");
    things[things.length - 1].reportValidity();
  }
}

document.querySelector('[name=submit]').addEventListener('click', () => {
  validateGrp()
});
</script>

<script>
var date = new Date();

var day = date.getDate();
var month = date.getMonth() + 1;
var year = date.getFullYear();

if (month < 10) month = "0" + month;
if (day < 10) day = "0" + day;

var today = <?php if(is_null($date)) {echo ""; } else { echo json_encode(date_format($date,'Y-m-d'), JSON_HEX_TAG); } ?>

document.getElementById('date').value = today;
</script>

<script>
$('.subscriber :checkbox').change(function () {
    var $cs = $(this).closest('.subscriber').find(':checkbox:checked');
    if ($cs.length > 3) {
        this.checked = false;
    }
});
</script>  
<script src="includes/ri-functions.js"></script>
</body>
</html>
	  
