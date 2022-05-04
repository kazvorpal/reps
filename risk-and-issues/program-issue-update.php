<?php include ("../includes/functions.php");?>
<?php include ("../db_conf.php");?>
<?php include ("../data/emo_data.php");?>
<?php include ("../sql/project_by_id.php");?>
<?php include ("../sql/RI_Internal_External.php");?>
<?php 

  //FIND PROJECT RISK AND ISSUES
  $RiskAndIssue_Key = $_GET['rikey'];
  $fscl_year = $_GET['fscl_year'];
  $proj_name = $_GET['projname'];
  $progkey =$_GET['progkey'];
  $progkey =$_GET['progkey'];
  $progrikey =$_GET['progRIkey'];
    
  $sql_risk_issue = "select * from RI_Mgt.fn_GetListOfAllRiskAndIssue(-1) where RIlevel_Cd = 'Program' and RiskAndIssue_Key = $RiskAndIssue_Key";
  $stmt_risk_issue = sqlsrv_query( $data_conn, $sql_risk_issue );
  $row_risk_issue = sqlsrv_fetch_array($stmt_risk_issue, SQLSRV_FETCH_ASSOC);
  //echo $sql_risk_issue;

  //DECLARE
  //$action = $_GET['action']; //new
  //$temp_id = $_GET['tempid'];
  $user_id = preg_replace("/^.+\\\\/", "", $_SERVER["AUTH_USER"]);
  $ass_project = $row_projID['PROJ_NM'];

  if(!empty($_POST['proj_select'])) { 
    $ass_project_regions = implode("','", $_POST['proj_select']); 
    $ass_project_regionsx = $ass_project_regions; 
    $regionIN = "'" . $ass_project_regions . "','" . $ass_project . "'"; 
      } else { 
    $regionIN = "'" . $ass_project . "'"; 
      }
  
  //GET ASSOCIATED PROJECTS FROM 
  $sql_assoc_prj = "select * from RI_Mgt.fn_GetListOfAssociatedProjectsForProgramRIKey($RiskAndIssue_Key,$progrikey)";
  $stmt_assoc_prj = sqlsrv_query( $data_conn, $sql_assoc_prj );
  //$row_assoc_prj= sqlsrv_fetch_array($stmt_assoc_prj, SQLSRV_FETCH_ASSOC);
  //$row_assoc_prj['EPSProject_Nm'];

  $sql_assoc_prj_keys = "select * from RI_Mgt.fn_GetListOfAssociatedProjectsForProgramRIKey($RiskAndIssue_Key,$progrikey)";
  $stmt_assoc_prj_keys  = sqlsrv_query( $data_conn, $sql_assoc_prj_keys  );
  //$_keys = sqlsrv_fetch_array($stmt_assoc_prj_keys , SQLSRV_FETCH_ASSOC);
  //$row_assoc_prj_keys ['PROJECT_key'];

  //GET REGIONS FROM NAME
  $sql_regions = "SELECT DISTINCT Region_key, Region
                  FROM [COX_Dev].[EPS].[ProjectStage]
                  JOIN [COX_Dev].[CR_MGT].[Region] ON [COX_Dev].[EPS].[ProjectStage].[Region] = [COX_Dev].[CR_MGT].[Region].[Region_Cd] WHERE PROJ_NM IN ($regionIN)";
  $stmt_regions = sqlsrv_query( $data_conn, $sql_regions );
  //$row_regions = sqlsrv_fetch_array( $stmt_regions, SQLSRV_FETCH_ASSOC);
  //$row_regions['Region'];
  //echo $sql_regions;
  
  //GET REGIONS KEYS FOR HIDDEN FIELD
  $sql_regions_f = "select distinct Region_key,[RI_MGT].[fn_GetListOfRiskAndIssuesForMLMProgram].[Region_Cd]
                    from [RI_MGT].[fn_GetListOfRiskAndIssuesForMLMProgram] (2022, 'CB Funding for Growth') 
                    left join [CR_MGT].[Region] on [RI_MGT].[fn_GetListOfRiskAndIssuesForMLMProgram].[Region_Cd] = [CR_MGT].[Region].[Region_Cd]
                    where RiskAndIssue_Key = $RiskAndIssue_Key
                    order by [CR_MGT].[Region].[Region_key]";
  $stmt_regions_f = sqlsrv_query( $data_conn, $sql_regions_f );
  //$row_region_fs = sqlsrv_fetch_array( $stmt_regions_f, SQLSRV_FETCH_ASSOC);
  //$row_regions_f['Region'];
  //echo $sql_regions_f;
  //exit();

  //GET ALL REGIONS FOR REGIONS SELECTION
  $sql_regions = "SELECT DISTINCT Region_key, Region
                  FROM [COX_Dev].[EPS].[ProjectStage]
                  JOIN [COX_Dev].[CR_MGT].[Region] ON [COX_Dev].[EPS].[ProjectStage].[Region] = [COX_Dev].[CR_MGT].[Region].[Region_Cd] WHERE PROJ_NM IN ($regionIN)";
  $stmt_regions = sqlsrv_query( $data_conn, $sql_regions );
  //$row_regions = sqlsrv_fetch_array( $stmt_regions, SQLSRV_FETCH_ASSOC);
  //$row_regions['Region'];

  //SINGLE REGION FOR NAME CONCATINATION
  $sql_region = "SELECT DISTINCT Region_key, Region
                  FROM [COX_Dev].[EPS].[ProjectStage]
                  JOIN [COX_Dev].[CR_MGT].[Region] ON [COX_Dev].[EPS].[ProjectStage].[Region] = [COX_Dev].[CR_MGT].[Region].[Region_Cd] WHERE PROJ_NM IN ($regionIN)";
  $stmt_region = sqlsrv_query( $data_conn, $sql_region );
  $row_region = sqlsrv_fetch_array( $stmt_region, SQLSRV_FETCH_ASSOC);
  //echo $sql_region;

  //MULTI-REGION CONCATINATION - COUNT
  $sql_regions_con = "SELECT Count(DISTINCT Region_key) as numRows
                  FROM [COX_Dev].[EPS].[ProjectStage]
                  JOIN [COX_Dev].[CR_MGT].[Region] ON [COX_Dev].[EPS].[ProjectStage].[Region] = [COX_Dev].[CR_MGT].[Region].[Region_Cd] WHERE PROJ_NM IN ($regionIN)";
  $stmt_regions_con  = sqlsrv_query( $data_conn, $sql_regions_con  );
  $row_regions_con = sqlsrv_fetch_array( $stmt_regions_con, SQLSRV_FETCH_ASSOC );
 //echo $sql_regions_con;

  $numRows = $row_regions_con['numRows'];
  //echo $numRows;
  //exit();

  if($numRows == 7){
      $regionCD = "All";
  } else if($numRows == 1) {
      $regionCode =  $row_region['Region'];
        if($regionCode=='Corporate'){
            $regionCD = 'COR';
        } else if($regionCode=='California'){
            $regionCD = 'CA';
        } else if($regionCode=='Central'){
            $regionCD = 'CE';
        } else if($regionCode=='Northeast'){
            $regionCD = 'NE';
        } else if($regionCode=='Southeast'){
            $regionCD = 'SE';
        } else if($regionCode=='Southwest'){
            $regionCD = 'SW';
        } else if($regionCode=='Virginia'){
            $regionCD = 'VA';
        } 
  } else if($numRows >= 2 && $numRows <= 6){
      $regionCD= "Multi";
  }
  
  //DEFINE
  $changeLogKey = 4;
  $name = trim($row_risk_issue['RI_Nm']);
  $RILevel = "";
  $RIType = $row_risk_issue['RIType_Cd'];
  $createdFrom  = "";
  $programs = "";
  $project_nm = "";
  $descriptor  = $row_risk_issue['ScopeDescriptor_Txt'];
  $description = $row_risk_issue['RIDescription_Txt'];
  $regionx = "";
  $Driversx = "";
  $impactArea2 = $row_risk_issue['ImpactArea_Nm'];
  $impactLevel2 = $row_risk_issue['ImpactLevel_Nm'];
  $RiskProbability = $row_risk_issue['RiskProbability_Nm'];
  $RiskProbability_Key = $row_risk_issue['RiskProbability_Key'];
  $individual = $row_risk_issue['POC_Nm'];
  $internalExternal = $row_risk_issue['POC_Nm'];
  $responseStrategy2 = $row_risk_issue['ResponseStrategy_Nm'];
  $unknown = ""; // IF DATE IS EMPTY
  $date = $row_risk_issue['ForecastedResolution_Dt'];
  $transProgMan = $row_risk_issue['TransferredPM_Flg'];
  $opportunity = $row_risk_issue['Opportunity_Txt'];
  $actionPlan = $row_risk_issue['ActionPlanStatus_Cd'];
  $DateClosed = $row_risk_issue['RIClosed_Dt'];
  $driverList = rtrim($_GET['drivertime'], ",");
  $driverArr = explode(",", $driverList);
  $regionList = rtrim($_GET['regions'], ",");
  $regionArr = explode(",", $regionList);
  $RIClosed_Dt = $row_risk_issue['RIClosed_Dt'];
  $raid = $row_risk_issue['RaidLog_Flg'];
  $riskRealized = $row_risk_issue['RiskRealized_Flg'];
  $assCRID = $row_risk_issue['AssociatedCR_Key'];
  $regions = $_GET['regions'];


  if(!empty($_POST['proj_select'])) {
  $assocProject = implode(",",$_POST['proj_select']) . "," . $RiskAndIssue_Key ;
  } else {
  $assocProject = $RiskAndIssue_Key;
  }

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
  <!--<link href='http://fonts.googleapis.com/css?family=Mulish' rel='stylesheet' type='text/css'>-->

<script language="JavaScript">
function toggle(source) {
  checkboxes = document.getElementsByName('proj_select');
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
  }
}
</script>
<style>
    .box {
    border: 1px solid #BCBCBC;
	  background-color: #ffffff;
    border-radius: 5px;
    padding: 5px;
    }
    .finePrint {
    font-size: 9px;  
    color: red;
    }
</style>

</head>
<body style="background: #F8F8F8; font-family:Mulish, serif;">
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
  <h3>PROGRAM ISSUE UPDATE</h3>
  Edit the details of your Program Issue
</div>
<div class="finePrint">
<?php  
  //echo "Project UID: " . $row_projID['PROJ_ID'] . "<br>"; 
  //echo "Logged in as: " . $user_id . "<br>"; 
  //echo "Project Owner: " . $row_projID['PROJ_OWNR_NM'] . "<br>"; 
  //echo "Temp ID for Associated: " . $_GET['tempid'];
  //echo "Location Code: " . $row_projID['EPSLocation_Cd']; 
?>
</div>
<div style="padding: 20px;">
  <form action="update-confirm.php" method="post" id="programRisk">

  <input name="changeLogKey" type="hidden" id="changeLogKey" value="4"><!-- 4 update, 3 close, 2 create, 1 initialize -->
  <input name="programs" type="hidden" id="programs" value="<?php echo $row_projID['PRGM'] ?>">
  <input name="userId" type="hidden" id="userId " value="<?php echo $user_id ?>">
  <input name="formName" type="hidden" id="formName" value="PRGR">
  <input name="formType" type="hidden" id="formType" value="Update">
  <input name="fiscalYer" type="hidden" id="fiscalYer" value="<?php echo $row_projID['FISCL_PLAN_YR'] ?>">
  <input name="RIType" type="hidden" id="RIType" value="Risk">
  <input name="RILevel" type="hidden" id="RILevel" value="Program">
  <input name="assocProjects" type="hidden" id="assocProjects" value="<?php echo $row_projID['PROJ_NM'] ?>">
  <input name="CreatedFrom" type="hidden" id="Created From" value=''>
  <input name="TransfertoProgramManager" type="hidden" id="TransfertoProgramManager" value="">
  <input name="program" type="hidden" id="program" value='<?php echo $row_projID['PRGM']; ?>'> <!-- EPS PROGRAM -->
  <input name="RIName" type="hidden" id="RIName" value=''>
  <input name="assocProjectsKeys" type="hidden" id="assocProjectsKeys" value='<?php while ($row_assoc_prj_keys= sqlsrv_fetch_array($stmt_assoc_prj_keys, SQLSRV_FETCH_ASSOC)) { echo $row_assoc_prj_keys['PROJECT_key'] . ',';} ?>'>
  <input name="RiskAndIssue_Key" type="hidden" id="RiskAndIssue_Key" value='<?php echo $RiskAndIssue_Key ?>'>
  <input name="programKeys" type="hidden" id="programKeys" value='<?php echo $progkey ?>'>
  <input name="regionKeys" type="hidden" id="regionKeys" value="<?php while ($row_regions_f= sqlsrv_fetch_array($stmt_regions_f, SQLSRV_FETCH_ASSOC)) { echo $row_regions_f['Region_key'] . ',';} ?>">
  <input name="Region" type="hidden" id="Region" value="<?php echo $regions ?>">
  <input name="RiskProbability" type="hidden" id="RiskProbability" value="">
  <input  name="Risk Relized" type="hidden" value="">

    <table width="100%" border="0" cellpadding="10" cellspacing="10">
      <tbody>
        <tr>
          <th width="50%" align="left">
            <div id="myRisk">
              <h4 style="color: #00aaf5">PROGRAM RISK</h4>
            </div></th>
          <th align="left">&nbsp;</th>
        </tr>
        <tr>
          <td colspan="2" align="left">&nbsp;</td>
          </tr>
        <tr>
          <td colspan="2" align="left">
            
            </td>
        </tr>

        <tr>
          <td align="left" valign="top"><div class="box">
			<table width="100%" border="0" cellpadding="10px" cellspacing="10">
            <tbody>
              <tr>
                <td width="50%"><label for="Created From">Name</label>
                <br>
                <input name="Namex" type="text" readonly required="required" class="form-control" id="Namex" value="<?php echo $name ?>">
                <input name="NameA" type="hidden" id="NameA" value="<?php echo $row_projID['PRGM'];?>">
                <input name="NameA1" type="hidden" id="NameA1" value="<?php echo $row_projID['SCOP_DESC'];?>">
                <input name="NameB" type="hidden" id="NameB" value="<?php echo $regionCD; ?>"> <!-- Region -->
                <input name="NameC" type="hidden" id="NameC" value="<?php echo "POR" . substr($row_projID['FISCL_PLAN_YR'], -2) ?>"></td>
              </tr>
              <tr>
                <td><label for="Descriptor">Risk Descriptor<br>
                  </label>
                  <input name="Descriptor" type="text" required="required" class="form-control" id="Descriptor" maxlength="30" value="<?php echo $descriptor;?>" readonly>  
                </td>
              </tr>
              <tr>
                <td><label for="Description">Description<br>
                  </label>
                  <textarea name="Description" cols="120" rows="5" required="required" class="form-control" id="Description"><?php echo $description; ?></textarea>  </td>
              </tr>
            </tbody>
          </table>
		</div></td>
          <td align="left" valign="top">
          <div style="padding-left: 10px">  
          <div class="box">
          <table width="100%" border="0" cellpadding="10px" cellspacing="10">
            <tbody>
              <tr>
                <td>
                  <div style="padding: 0px 0px 0px 30px">
                    <p><strong>Select Regions
                      </strong><br>
                      <label>
                        <input type="checkbox" name="Regionx[]" value="All" id="Region" onClick="toggle(this); updatebox()" class="required_group_reg" <?php if(in_array("All", $regionArr)) { echo "checked";} ?> disabled>
                        Select All</label>
                      <br>
                      <label>
                        <input type="checkbox" name="Regionx[]" value="Corporate" id="Region_6" onClick="updatebox()" class="required_group_reg" <?php if(in_array("Corporate", $regionArr)) { echo "checked";} ?> disabled>
                        Corporate (COR)</label>
                      <br>
                      <label>
                        <input type="checkbox" name="Regioxn[]" value="California" id="Region_0" onClick="updatebox()" class="required_group_reg" <?php if(in_array("California", $regionArr)) { echo "checked";} ?> disabled>
                        California (CA)</label>
                      <br>
                      <label>
                        <input type="checkbox" name="Regionx[]" value="Central" id="Region_1" onClick="updatebox()" class="required_group_reg" <?php if(in_array("Central", $regionArr)) { echo "checked";} ?> disabled>
                        Central (CE)</label>
                      <br>
                      <label>
                        <input type="checkbox" name="Regionx[]" value="Northeast" id="Region_2" onClick="updatebox()" class="required_group_reg" <?php if(in_array("Northeast", $regionArr)) { echo "checked";} ?> disabled>
                        Northeast (NE)</label>
                      <br>
                      <label>
                        <input type="checkbox" name="Regionx[]" value="Southeast" id="Region_3" onClick="updatebox()" class="required_group_reg" <?php if(in_array("Southeast", $regionArr)) { echo "checked";} ?> disabled>
                        Southeast (SE)</label>
                      <br>
                      <label>
                        <input type="checkbox" name="Regionx[]" value="Southwest" id="Region_4" onClick="updatebox()" class="required_group_reg" <?php if(in_array("Southwest", $regionArr)) { echo "checked";} ?> disabled>
                        Southwest (SW)</label>
                      <br>
                      <label>
                        <input type="checkbox" name="Regionx[]" value="Virginia" id="Region_5" onClick="updatebox()" class="required_group_reg" <?php if(in_array("Virginia", $regionArr)) { echo "checked";} ?> disabled>
                        Virginia (VA)</label>
                      <br>
                      </p>
                    </div>
                </td>
              </tr>
            </tbody>
          </table>
		  </div>
      </div>
        </td>
        </tr>
        <tr>
          <td colspan="2" align="left"><h4 style="color: #00aaf5">DRIVERS</h4>
            <div class="box">
            <table width="100%" border="0">
                <tr>
                  <td width="51%"><label>
                    <input type="checkbox" name="Drivers[]" value="1"  id="Drivers_0" class="required_group" <?php if(in_array("Budget/Funding", $driverArr)) { echo "checked";} ?>>
                    Budget/Funding</label></td>
                  <td width="49%"><label>
                    <input type="checkbox" name="Drivers[]" value="2" id="Drivers_10" class="required_group" <?php if(in_array("External", $driverArr)) { echo "checked";} ?>>
                    External</label></td>
                  </tr>
                <tr>
                  <td><label>
                    <input type="checkbox" name="Drivers[]" value="3" id="Drivers_1" class="required_group" <?php if(in_array("Communication BreakDown", $driverArr)) { echo "checked";} ?>>
                    Communications Breakdown</label></td>
                  <td><label>
                    <input type="checkbox" name="Drivers[]" value="7" id="Drivers_6" class="required_group" <?php if(in_array("People Resource", $driverArr)) { echo "checked";} ?>>
                    People Resources</label></td>
                  </tr>
                <tr>
                  <td><label>
                    <input type="checkbox" name="Drivers[]" value="4" id="Drivers_2" class="required_group" <?php if(in_array("Contractor", $driverArr)) { echo "checked";} ?>>
                    Contractor</label></td>
                  <td><label>
                    <input type="checkbox" name="Drivers[]" value="8" id="Drivers_7" class="required_group" <?php if(in_array("Procurement", $driverArr)) { echo "checked";} ?>>
                    Procurement</label></td>
                  </tr>
                <tr>
                  <td><label>
                    <input type="checkbox" name="Drivers[]" value="5" id="Drivers_3" class="required_group" <?php if(in_array("Dependency Conflict", $driverArr)) { echo "checked";} ?>>
                    Dependency Conflict</label></td>
                  <td><label>
                    <input type="checkbox" name="Drivers[]" value="9" id="Drivers_8" class="required_group" <?php if(in_array("Schedule Impact", $driverArr)) { echo "checked";} ?>>
                    Schedule Impact</label></td>
                  </tr>
                <tr>
                  <td><label>
                    <input type="checkbox" name="Drivers[]" value="6" id="Drivers_4" class="required_group" <?php if(in_array("Equipment Integration", $driverArr)) { echo "checked";} ?>>
                    Equipment Integration</label></td>
                  <td><label>
                    <input type="checkbox" name="Drivers[]" value="10" id="Drivers_9" class="required_group" <?php if(in_array("Other", $driverArr)) { echo "checked";} ?>>
                    Other</label></td>
                  </tr>
                </table>
              </div>
          </td>
          </tr>
        <tr>
          <td colspan="2" align="left"></td>
        </tr>
        <tr>
          <td align="left"><h4  style="color: #00aaf5">IMPACT</h4></td>
          <td align="left">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" align="left">
			<div class="box"> 
			<table width="100%" border="0">
            <tbody>

              <tr>
                <td width="25%"></td>
                <td width="25%"></td>
                <td width="25%"></td>
                <td width="25%"></td>
              </tr>

              <tr>
                <td  valign="top">
                  <table width="200" border="0">
                  <tr>
                  <strong>Impacted Area</strong>
                  </tr>
                  <tr>
                    <td><label>
                      <input type="radio" name="ImpactArea" value="2" id="ImpactArea_1" required <?php if($impactArea2=="Schedule"){ echo "checked";}?>>
                      Schedule</label></td>
                    </tr>
                  <tr>
                    <td><label>
                      <input name="ImpactArea" type="radio"  id="ImpactArea_0" value="1" required <?php if($impactArea2=="Scope"){ echo "checked";}?>>
                      Scope</label></td>
                    </tr>
                  <tr>
                    <td><label>
                      <input type="radio" name="ImpactArea" value="3" id="ImpactArea_2" required <?php if($impactArea2=="Budget (Cost Change)"){ echo "checked";}?>>
                      Budget (Cost Change)</label></td>
                    </tr>
                  </table></td>
                <td valign="top">
                  <table width="200" border="0">
                    <tr>
                      <strong>Impact Level</strong>
                    </tr>
                    <tr>
                      <td><label>
                        <input name="ImpactLevel" type="radio" id="ImpactLevel_0" value="1" required <?php if($impactLevel2=="Minor Impact"){echo "checked";}?>>
                        Minor Impact</label></td>
                      </tr>
                    <tr>
                      <td><label>
                        <input type="radio" name="ImpactLevel" value="2" id="ImpactLevel_1" required <?php if($impactLevel2=="Moderate Impact"){echo "checked";}?>>
                        Moderate Impact</label></td>
                      </tr>
                    <tr>
                      <td><label>
                        <input type="radio" name="ImpactLevel" value="3" id="ImpactLevel_2" required <?php if($impactLevel2=="Major Impact"){echo "checked";}?>>
                        Major Impact</label></td>
                      </tr>
                    </table>
                  </td>
                <td valign="top">
        				</td>
                <td>
                
				        </td>
                </tr>
              </tbody>
          </table>
		</div> 
        </td>
        </tr>
        <tr>
          <td colspan="2" align="left"></td>
        </tr>
        <tr>
          <td align="left"><h4 style="color: #00aaf5">CURRENT TASK POC</h4></td>
          <td align="left">
			  
		  </td>
        </tr>
        <tr>
          <td colspan="2" align="left">
            <div class="box">
              <label for="Individual">Individual POC<br>
                </label>
              
              <input type="text" list="Individual" name="Individual" class="form-control" id="indy"  value="<?php echo $individual ?>" onblur="document.getElementById('intern').disabled = (''!=this.value);"/>
              <datalist id="Individual">
                <?php while($row_internal  = sqlsrv_fetch_array( $stmt_internal , SQLSRV_FETCH_ASSOC)) { ?>
                <option><?php echo $row_internal['POC_Nm'] ?></option>
                <?php } ?>
                </datalist>
              <!--
              <h4 align="center">OR</h4>
              <label for="Individual3">Team/Group POC<br>
                </label>
              
              <input type="text" list="InternalExternal" name="InternalExternal" class="form-control" id="intern" onblur="document.getElementById('indy').disabled = (''!=this.value);"/>
              <datalist id="InternalExternal">
                <?php while($row_external  = sqlsrv_fetch_array( $stmt_external , SQLSRV_FETCH_ASSOC)) { ?>
                <option><?php echo $row_external['POC_Nm'] ?></option>
                <?php } ?>
                </datalist>-->
              </div>
          </td>
          </tr>
        <tr>
          <td colspan="2" align="left"></hr></td>
        </tr>
        <tr>
          <td colspan="2" align="left"></td>
        </tr>
        <tr>
          <td colspan="2" align="left"><h4 style="color: #00aaf5">RELATED DATES</h4></td>
        </tr>
        <tr>
          <td colspan="2" align="left"><div class="box">
              <label for="date">Forecasted Resolution Date:</label>
			  <div id="dateUnknown">
              <input name="date" 
                  type="date"
                  class="form-control" 
                  id="date" 
                  value=""
                  onChange="forCasted()"  
                  oninvalid="this.setCustomValidity('You must select a date or check Unknown ')"
                  oninput="this.setCustomValidity('')"
                  >
          </div>
          <div id="forcastedDate">
              <input type="checkbox" 
                  name="Unknown" 
                  id="Unknown" 
                  onChange="unKnown()"
                  <?php if(empty($date)){ echo "checked";} ?>
                  >
              <label for="Unknown">Unknown</label>
          </div>
          </div></td>
        </tr>
        <tr>
          <td colspan="2" align="left"><h4 style="color: #00aaf5">RESPONSE STRATEGY</h4>			  </td>
        </tr>
        <tr>
          <td colspan="2" align="left"><div class="box">
          <table width="246" border="0" cellpadding="5" cellspacing="5">
              <tr>
                <td>&nbsp;</td>
                <td><label>
                  <input type="radio" name="ResponseStrategy" value="1" id="Response_Strategy_0" required <?php if($responseStrategy2=="Avoid" ) { echo "checked";} ?>>
                  Avoid</label></td>
                </tr>
              <tr>
                <td>&nbsp;</td>
                <td><label>
                  <input type="radio" name="ResponseStrategy" value="2" id="Response_Strategy_1" required <?php if($responseStrategy2=="Mitigate" ) { echo "checked";} ?>>
                  Mitigate</label></td>
                </tr>
              <tr>
                <td width="16">&nbsp;</td>
                <td width="195"><label>
                  <input type="radio" name="ResponseStrategy" value="3" id="Response_Strategy_2" required <?php if($responseStrategy2=="Transfer" ) { echo "checked";} ?>>
                  Transfer</label></td>
                </tr>
              <tr>
                <td>&nbsp;</td>
                <td><label>
                  <input type="radio" name="ResponseStrategy" value="4" id="Response_Strategy_3" required <?php if($responseStrategy2=="Accept" ) { echo "checked";} ?>>
                  Accept</label></td>
                </tr>
            </table>
          </div>			</td>
        </tr>
        <tr>
          <td colspan="2" align="left"><h4 style="color: #00aaf5">ACTION PLAN</h4>
          
          <div class="box">  
            <table width="100%" border="0" cellpadding="5" cellspacing="5">
              <tbody>
                
                  <tr>
                    <td width="100%">
                          
                          <textarea name="ActionPlan" cols="120" required="required" class="form-control" id="ActionPlan"><?php echo $actionPlan; ?></textarea></td>
                  </tr>
                
                <tr>
                  <td>&nbsp;</td>
                  <td></td>
                </tr>
              </tbody>
</table>
          <div>

          </td>
        </tr>
        <tr>
          <td colspan="2" align="left"></td>
        </tr>
        <tr align="left">
        <td colspan="2" align="left"><h4 style="color: #00aaf5">PROJECT ASSOCIATION</h4></td>
        </tr>
        <tr>
          <td colspan="2" align="left">
        <div class="box" style="font-size: 12px;">
				  <?php while ($row_assoc_prj= sqlsrv_fetch_array($stmt_assoc_prj, SQLSRV_FETCH_ASSOC)) { echo $row_assoc_prj['EPSProject_Nm'] . '<br>';} ?>
        </div>
      </td>
        </tr>
        <tr>
          <td colspan="2" align="left"></td>
        </tr>
        <tr>
          <td colspan="2" align="left">
            <br>
            <div class="box">
              <label for="Created From">Associated CR ID</label>
              <input name="CreatedFrom" type="text" class="form-control" id="Created From" value="<?php echo $assCRID;?>">
            </div>
          </td>
      </tr>
      <tr>
        <td colspan="3" align="left"><h4 style="color: #00aaf5">RAID LOG</h4></td>
			  </tr>
        <tr>
          <td colspan="3" align="left">
            <div class="box">
              <table width="50%" border="0">
                  <td colspan="2"><strong>Notify Portfolio Team</strong></td>
                  </tr>
                <tr>
                  <td><label>
                    <input type="radio" name="raidLog" value="Yes" id="raid_0" <?php if($raid == "Yes"){echo "checked";};?>>
                    Yes</label></td>
                  <td><label>
                    <input type="radio" name="raidLog" value="No" id="raid_1" <?php if($raid == "No" || empty($raid)){echo "checked";}?>>
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
          <td colspan="2" align="left">
			  <div class="box">
			<label for="DateClosed">Date Closed:</label>
            <input type="date" name="DateClosed" id="DateClosed" class="form-control">
			</div>
		  </td>
        </tr>
        <tr>
          <td colspan="2" align="right" valign="middle">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" align="right" valign="middle">
          <input type="submit" name="submit" id="submit" value="Review" class="btn btn-primary">
          </td>
        </tr>
      </tbody>
    </table>
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

</script>
<script>
function forCasted() {
  var x = document.getElementById("forcastedDate");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}
</script>
<script>
function unKnown() {
  var x = document.getElementById("dateUnknown");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}

jQuery(function ($) {
    var $inputs = $('input[name=date],input[name=unknown]');
    $inputs.on('input', function () {
        // Set the required property of the other input to false if this input is not empty.
        $inputs.not(this).prop('required', !$(this).val().length);
    });
});
</script>

<script>
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
    things[things.length - 1].setCustomValidity("You must check at least one checkbox");
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
var closeday = <?php if(is_null($RIClosed_Dt)) {echo ""; } else { echo json_encode(date_format($RIClosed_Dt,'Y-m-d'), JSON_HEX_TAG); } ?>

document.getElementById('DateClosed').value = closeday;
</script>
</body>
</html>