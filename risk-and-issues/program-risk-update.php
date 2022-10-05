<?php include ("../includes/functions.php");?>
<?php include ("../db_conf.php");?>
<?php include ("../data/emo_data.php");?>
<?php //include ("../sql/project_by_id.php");?>
<?php include ("../sql/RI_Internal_External.php");?>
<?php 
//echo str_replace('  ', '&nbsp; ', nl2br(print_r($_POST, true)));
//echo str_replace('  ', '&nbsp; ', nl2br(print_r($_GET, true)));

  //FIND PROJECT RISK AND ISSUES
  $RiskAndIssue_Key = $_GET['rikey'];
  $fscl_year = $_GET['fscl_year'];
  //$proj_name = $_GET['projname'];  // I DONT NEED THIS FOR PROGRAM
  $progkey = $_GET['progkey']; //MLMProgramkey
  $progrikey = $_GET['progRIkey']; //ASSIGNED PROGRAM RI KEY
  $status = $_GET['status']; // ALWAY 1 FOR ACTIVE
  $progName = $_GET['progname']; // MLM PRGRAM NAME
  $formaction =  $_GET['action'];

  
  $projFromURL ="";
  if(isset($_GET['assoc_prj'])) {
    $projFromURL = "'" . str_replace(",", "','", $_GET['assoc_prj']) . "'"; 
  }

  $assc_prj_update = "";
  if(!empty($_GET['assc_prj_update'])) {
  $assc_prj_update = $_GET['assc_prj_update'];
  }
  //echo $_GET['assc_prj_update'];
    
  $sql_risk_issue = "select * from RI_Mgt.fn_GetListOfAllRiskAndIssue(-1) where RIlevel_Cd = 'Program' and RiskAndIssue_Key = $RiskAndIssue_Key";
  $stmt_risk_issue = sqlsrv_query( $data_conn, $sql_risk_issue );
  $row_risk_issue = sqlsrv_fetch_array($stmt_risk_issue, SQLSRV_FETCH_ASSOC);
  //echo $sql_risk_issue; //exit();

  //DECLARE
  //$action = $_GET['action']; //new
  //$temp_id = $_GET['tempid'];
  $user_id = preg_replace("/^.+\\\\/", "", $_SERVER["AUTH_USER"]);

  $ass_project = $projFromURL ;//$row_projID['PROJ_NM']; echo $ass_project;

  if(isset($_POST['proj_select'])) { 
    $ass_project_regions = implode("','", $_POST['proj_select']); 
    $ass_project_regionsx = $ass_project_regions; 
    $regionIN = "'" . $ass_project_regions . "'"; 
      } else { 
    //$regionIN = "'" . $ass_project . "'"; 
    $regionIN = $ass_project ;
      }

      //echo "<b>Assoc Projects from submission: ass_project_regions</b> " . $ass_project ;
      //echo "<br> <b>Get Regions from these projects:</b> " .  $regionIN . "<br><br>";

  //GET ASSOCIATED PROJECTS FROM 
  $sql_assoc_prj = "select * from RI_Mgt.fn_GetListOfAssociatedProjectsForProgramRIKey($RiskAndIssue_Key,$progrikey,$status)";
  $stmt_assoc_prj = sqlsrv_query( $data_conn, $sql_assoc_prj );
  //$row_assoc_prj= sqlsrv_fetch_array($stmt_assoc_prj, SQLSRV_FETCH_ASSOC);
  //$row_assoc_prj['EPSProject_Nm'];
  //echo $sql_assoc_prj;

  $sql_assoc_prj_keys = "select * from RI_Mgt.fn_GetListOfAssociatedProjectsForProgramRIKey($RiskAndIssue_Key,$progrikey,$status)";
  $stmt_assoc_prj_keys  = sqlsrv_query( $data_conn, $sql_assoc_prj_keys  );
  //$_keys = sqlsrv_fetch_array($stmt_assoc_prj_keys , SQLSRV_FETCH_ASSOC);
  //$row_assoc_prj_keys ['PROJECT_key'];
  //echo $sql_assoc_prj_keys;

  //GET REGIONS FROM NAME
  $sql_regions = "SELECT DISTINCT Region_key, Region
                  FROM [EPS].[ProjectStage]
                  JOIN [CR_MGT].[Region] ON [EPS].[ProjectStage].[Region] = [CR_MGT].[Region].[Region_Cd] WHERE PROJ_NM IN ($regionIN)";
  $stmt_regions = sqlsrv_query( $data_conn, $sql_regions );
  //$row_regions = sqlsrv_fetch_array( $stmt_regions, SQLSRV_FETCH_ASSOC);
  //$row_regions['Region'];
  //echo $sql_regions;

  //GET EPS PROJECT KEYS FROM PROJECT NAMES
  $sql_epsProjKey = "DECLARE @EPS_IDs VARCHAR(100)
      SELECT @EPS_IDs = COALESCE(@EPS_IDs+',','')+ CAST(EPSProject_key AS VARCHAR(100))
      FROM RI_MGT.fn_GetListOfLocationsForEPSProject(1) WHERE EPSProject_Nm in ($regionIN)
      SELECT @EPS_IDs AS eps_proj_key";
  $stmt_epsProjKey = sqlsrv_query( $data_conn, $sql_epsProjKey );
  $row_epsProjKey = sqlsrv_fetch_array( $stmt_epsProjKey, SQLSRV_FETCH_ASSOC);
//echo "<b>EPS PROJECT KEYS FROM PROJECT NAMES</b> " . $sql_epsProjKey . "<br><br>";
  $eps_proj_keys = $row_epsProjKey['eps_proj_key'];
//echo "Project Keys: " . $eps_proj_keys;

  //REGIONS FROM ASSOC PROJECT KEYS FOR DISPLAY CHECKBOX
  $sql_region_update = "DECLARE @temp VARCHAR(MAX)
      SELECT @temp = COALESCE(@temp+',' ,'') + Region_Cd 
      FROM RI_MGT.fn_GetListOfLocationsForEPSProject(1) Where  EPSProject_key in ($eps_proj_keys)
      SELECT @temp AS Region_Cd ";

  if($assc_prj_update == "yes") {

    $inRegion = $ass_project_regions; //$row_region_update['Region_Cd'];
    $IReplace = "'" . $inRegion . "'";
    //$IReplace = str_replace(",", "','", $inRegion);

    $sql_region_update = "DECLARE @REG_Nms VARCHAR(100) 
    SELECT @REG_Nms = COALESCE(@REG_Nms+',','')+ CAST(Region_Cd AS VARCHAR(100)) 
    FROM [EPS].[ProjectStage] INNER JOIN [CR_MGT].[Region] on Region_Cd = Region 
    WHERE PROJ_NM IN ($IReplace ) 
    SELECT @REG_Nms AS Region_Cd";

      }

  $stmt_region_update = sqlsrv_query( $data_conn, $sql_region_update );
  $row_region_update = sqlsrv_fetch_array( $stmt_region_update, SQLSRV_FETCH_ASSOC);
  //echo "<B>Regions from ASSOC PROJECTS: </B>" . $sql_region_update . "<br><br>";

  //GET REGIONS KEYS FOR HIDDEN FIELD
  $regionkeySQL = " DECLARE @REG_IDs VARCHAR(100) 
      SELECT @REG_IDs = COALESCE(@REG_IDs+',','')+ CAST(Region_key AS VARCHAR(100)) 
      FROM [EPS].[ProjectStage]
      INNER JOIN [CR_MGT].[Region] on Region_Cd = Region
      WHERE PROJ_NM IN ($projFromURL) 
      SELECT @REG_IDs AS Region_key";

  if($assc_prj_update == "yes") {

      $inRegion = $ass_project_regions; //$row_region_update['Region_Cd'];
      $IReplace = "'" . $inRegion . "'";
      //$IReplace = str_replace(",", "','", $inRegion);

      $regionkeySQL = " DECLARE @REG_IDs VARCHAR(100) 
      SELECT @REG_IDs = COALESCE(@REG_IDs+',','')+ CAST(Region_key AS VARCHAR(100)) 
      FROM [EPS].[ProjectStage]
      INNER JOIN [CR_MGT].[Region] on Region_Cd = Region
      WHERE PROJ_NM IN ($IReplace) 
      SELECT @REG_IDs AS Region_key";
    }
//echo "<B>Region Keys for Hidden Fields: </B>" . $regionkeySQL;
  $sql_regions_f = "$regionkeySQL";
  $stmt_regions_f = sqlsrv_query( $data_conn, $sql_regions_f );
  //echo $sql_regions_f;
  $row_regions_f = sqlsrv_fetch_array( $stmt_regions_f, SQLSRV_FETCH_ASSOC);
  //$row_regions_f['Region_key'];
  //echo $sql_regions_f;
  //exit(); 
  //echo "<br>Its here" . $row_regions_f['Region_key'];

  //GET ALL REGIONS FOR REGIONS SELECTION
  $sql_regions = "SELECT DISTINCT Region_key, Region
                  FROM [EPS].[ProjectStage]
                  JOIN [CR_MGT].[Region] ON [EPS].[ProjectStage].[Region] = [CR_MGT].[Region].[Region_Cd] WHERE PROJ_NM IN ($regionIN)";
  $stmt_regions = sqlsrv_query( $data_conn, $sql_regions );
  //$row_regions = sqlsrv_fetch_array( $stmt_regions, SQLSRV_FETCH_ASSOC);
  //$row_regions['Region'];

  //SINGLE REGION FOR NAME CONCATINATION
  $sql_region = "SELECT DISTINCT Region_key, Region
                  FROM [EPS].[ProjectStage]
                  JOIN [CR_MGT].[Region] ON [EPS].[ProjectStage].[Region] = [CR_MGT].[Region].[Region_Cd] WHERE PROJ_NM IN ($regionIN)";
  $stmt_region = sqlsrv_query( $data_conn, $sql_region );
  $row_region = sqlsrv_fetch_array( $stmt_region, SQLSRV_FETCH_ASSOC);
  //echo $sql_region;

  //MULTI-REGION CONCATINATION - COUNT
  $sql_regions_con = "SELECT Count(DISTINCT Region_key) as numRows
                  FROM [EPS].[ProjectStage]
                  JOIN [CR_MGT].[Region] ON [EPS].[ProjectStage].[Region] = [CR_MGT].[Region].[Region_Cd] WHERE PROJ_NM IN ($regionIN)";
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
  if($formaction == "new"){
    $changeLogKey = 2;
  }
  $name = trim($row_risk_issue['RI_Nm']);
  $formType = "update";
  $RILevel = "Program";
  $RIType = $row_risk_issue['RIType_Cd'];
  $createdFrom  = ""; //TBA
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
  $internalExternal = $row_risk_issue['POC_Department'];
  $responseStrategy2 = $row_risk_issue['ResponseStrategy_Nm'];
  $unknown = ""; // IF DATE IS EMPTY
  $date = $row_risk_issue['ForecastedResolution_Dt'];
  $transProgMan = $row_risk_issue['TransferredPM_Flg'];
  $opportunity = $row_risk_issue['Opportunity_Txt'];
  $actionPlan = $row_risk_issue['ActionPlanStatus_Cd'];
  $DateClosed = $row_risk_issue['RIClosed_Dt'];
  $driverList = rtrim($_GET['drivertime'], ",");
  $driverArr = explode(",", $driverList);
  $actionPlan_b = $row_risk_issue['ActionPlanStatus_Cd'];
  $regionList = rtrim($_GET['regions'], ","); //echo $regionList;
  $regionArr = explode(",", $regionList);

  $RIClosed_Dt = $row_risk_issue['RIClosed_Dt'];
  $raid = $row_risk_issue['RaidLog_Flg'];
  $riskRealized = $row_risk_issue['RiskRealized_Flg'];
  $assCRID = $row_risk_issue['AssociatedCR_Key'];
  $probability = $row_risk_issue['RiskProbability_Key'];
  $formName = $_GET['formName'];
  $POC_Nm = $row_risk_issue['POC_Nm'];

  $regionkeyUp = $row_region_update['Region_Cd']; //echo "<b></br></br>Regions to update: </b>" .  $regionkeyUp . "</br>";
  $regionkeyUpArray = explode(",", $regionkeyUp); //echo "" . print_r($regionkeyUpArray);

  $regions = $_GET['regions'];
  if($assc_prj_update == "yes"){
    $regions = $regionkeyUp;
  }


  $createDT = date_format($row_risk_issue['Created_Ts'],'Y-m-d');
  $regionKeys = $row_regions_f['Region_key']; 

  if(!empty($row_risk_issue['ForecastedResolution_Dt'])) {
    $forecastMin = date_format($date, "Y-m-d");
  } else {
    $forecastMin = $closeDateMax;
  }
 
  //if project in url use get else post
  if(!isset($_POST['proj_select'])) {
    $assocProject = $_GET['assoc_prj'];
  } else {
    $assocProject = implode(",", $_POST['proj_select']);
  }

  $raidLog = $row_risk_issue['RaidLog_Flg'];
  $department = $row_risk_issue['POC_Department'];

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Carolino, Gil">
    <title>RePS Reporting - Cox Communications</title>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <script src="../colorbox-master/jquery.colorbox.js"></script>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
  <link rel="stylesheet" href="steps/style.css" type='text/css'> 
  <link rel="stylesheet" href="../colorbox-master/example1/colorbox.css" />
  <link rel="stylesheet" href="includes/ri-styles.css" />
  
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
  <h3>PROGRAM <?php echo strtoupper($RIType) ?> UPDATE</h3>
  Edit the details of your Program <?php echo $RIType ?>
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
<?php 
if($formaction == "update") {
  $action = "update-confirm.php";
} else {
  $action = "confirm.php";
}

?>
  <form action="<?php echo $action ?>" method="post" id="programRisk">

  <input name="changeLogKey" type="hidden" id="changeLogKey" value="<?php echo $changeLogKey?>"><!-- 4 update, 3 close, 2 create, 1 initialize -->
  <input name="programs" type="hidden" id="programs" value="<?php echo $row_risk_issue['MLMProgram_Nm'] ?>">
  <input name="userId" type="hidden" id="userId " value="<?php echo $user_id ?>">
  <input name="fiscalYer" type="hidden" id="fiscalYer" value="<?php echo $row_risk_issue['Fiscal_Year'] ?>">
  <input name="formName" type="hidden" id="formName" value="<?php echo $formName ?>">
  <input name="RIType" type="hidden" id="RIType" value="<?php echo $RIType?>">
  <input name="RILevel" type="hidden" id="RILevel" value="<?php echo $RILevel ?>">

    <input name="assocProjects" type="hidden" id="assocProjects" value="<?php echo $assocProject ?>">
    <input name="formType" type="hidden" id="formType" value="<?php echo $formType?>">
    <input name="assocProjectsKeys" type="hidden" id="assocProjectsKeys" value="<?php echo $eps_proj_keys?>">

  <input name="CreatedFrom" type="hidden" id="Created From" value=''>
  <input name="TransfertoProgramManager" type="hidden" id="TransfertoProgramManager" value="">
  <input name="program" type="hidden" id="program" value='<?php echo $row_risk_issue['MLMProgram_Nm']; ?>'> <!-- EPS PROGRAM -->
  <input name="RIName" type="hidden" id="RIName" value=''>
  <input name="RiskAndIssue_Key" type="hidden" id="RiskAndIssue_Key" value='<?php echo $RiskAndIssue_Key ?>'>
  <input name="programKeys" type="hidden" id="programKeys" value='<?php echo $progkey ?>'>
  <input name="regionKeys" type="hidden" id="regionKeys" value="<?php echo $regionKeys?>">
  <input name="Region" type="hidden" id="Region" value="<?php echo $regions ?>">
  <input name="formaction" type="hidden" id="formaction" value="<?php echo $formaction ?>">
  <!--<input type="hidden" name="riskRealized" value="1" id="riskRealized" value="0">-->
  <!--<input name="CreatedFrom" type="hidden" id="Created From" value="<?php //echo $assCRID;?>"-->
  <input name="Individual" type="hidden" id="Individual" value="">

  <?php if($assc_prj_update == "yes"){ ?>
  <div class="alert alert-danger">
  <div align="left">
    <span class="glyphicon glyphicon-warning-sign"></span> You are updating the Associated Project list for this Program <?php echo $RIType ?> to the list below.  You may edit the details of the <?php echo $RIType ?>.
  </div>
  </br>
      <table width="100%" border="0" cellpadding="10" cellspacing="10">
        <tr>
          <td colspan="3" align="left"><h4 style="color: #00aaf5">PROGRAM <?php if(empty($del_proj_select)) {echo strtoupper($RIType);} else { echo "PROJECT";}?> PROJECT ASSOCIATION</h4></td>
        </tr>
        <tr>
          <td colspan="3">
            <div class="box" align="left" style="font-size: 12px;">
              <?php 
                echo str_replace(",", "<br>", $assocProject);?>
            </div>
		      </td>
        </tr>
      </table>
  </div>
  <hr>
<?php } ?>

    <table width="100%" border="0" cellpadding="10" cellspacing="10">
      <tbody>
        <tr>
          <th width="50%" align="left">
            <div id="myRisk">
              <h4 style="color: #00aaf5">PROGRAM <?php echo strtoupper($RIType) ?></h4>
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
                <input name="NameA" type="hidden" id="NameA" value="<?php echo $row_risk_issue['MLMProgram_Nm'];?>">
                <input name="NameA1" type="hidden" id="NameA1" value="<?php //echo $row_projID['SCOP_DESC'];?>">
                <input name="NameB" type="hidden" id="NameB" value="<?php echo $regionCD; ?>"> <!-- Region -->
                <input name="NameC" type="hidden" id="NameC" value="<?php //echo "POR" . substr($row_projID['FISCL_PLAN_YR'], -2) ?>"></td>
              </tr>
              <tr>
                <td><label for="Descriptor">Descriptor<br>
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
          <div class="box subscriber">
          <table width="100%" border="0" cellpadding="10px" cellspacing="10">
            <tbody>
              <tr>
                <td>
                  <div style="padding: 0px 0px 0px 30px">
                    <p><strong>Select Regions 
                      </strong><br>
                      <label>
                        <input type="checkbox" name="Regionx[]" value="All" id="Region" onClick="toggle(this); updatebox()" class="required_group_reg" 
                        <?php if($assc_prj_update == "yes") { if(in_array("All", $regionkeyUpArray)) { echo "checked";} } else { if(in_array("All", $regionArr)) { echo "checked";} } ?> disabled>
                        All</label>
                      <br>
                      <label>
                        <input type="checkbox" name="Regionx[]" value="Corporate" id="Region_6" onClick="updatebox()" class="required_group_reg" 
                        <?php if($assc_prj_update == "yes") { if(in_array("Corporate", $regionkeyUpArray)) { echo "checked";} } else { if(in_array("Corporate", $regionArr)) { echo "checked";} } ?> disabled>
                        Corporate (COR)</label>
                      <br>
                      <label>
                        <input type="checkbox" name="Regioxn[]" value="California" id="Region_0" onClick="updatebox()" class="required_group_reg" 
                        <?php if($assc_prj_update == "yes") { if(in_array("California", $regionkeyUpArray)) { echo "checked";} } else { if(in_array("California", $regionArr)) { echo "checked";} } ?> disabled>
                        California (CA)</label>
                      <br>
                      <label>
                        <input type="checkbox" name="Regionx[]" value="Central" id="Region_1" onClick="updatebox()" class="required_group_reg" 
                        <?php if($assc_prj_update == "yes") { if(in_array("Central", $regionkeyUpArray)) { echo "checked";} } else { if(in_array("Central", $regionArr)) { echo "checked";} } ?> disabled>
                        Central (CE)</label>
                      <br>
                      <label>
                        <input type="checkbox" name="Regionx[]" value="Northeast" id="Region_2" onClick="updatebox()" class="required_group_reg" 
                        <?php if($assc_prj_update == "yes") { if(in_array("Northeast", $regionkeyUpArray)) { echo "checked";} } else { if(in_array("Northeast", $regionArr)) { echo "checked";} } ?> disabled>
                        Northeast (NE)</label>
                      <br>
                      <label>
                        <input type="checkbox" name="Regionx[]" value="Southeast" id="Region_3" onClick="updatebox()" class="required_group_reg" 
                        <?php if($assc_prj_update == "yes") { if(in_array("Southeast", $regionkeyUpArray)) { echo "checked";} } else { if(in_array("Southeast", $regionArr)) { echo "checked";} } ?> disabled>
                        Southeast (SE)</label>
                      <br>
                      <label>
                        <input type="checkbox" name="Regionx[]" value="Southwest" id="Region_4" onClick="updatebox()" class="required_group_reg"
                        <?php if($assc_prj_update == "yes") { if(in_array("Southwest", $regionkeyUpArray)) { echo "checked";} } else { if(in_array("Southwest", $regionArr)) { echo "checked";} } ?> disabled>
                        Southwest (SW)</label>
                      <br>
                      <label>
                        <input type="checkbox" name="Regionx[]" value="Virginia" id="Region_5" onClick="updatebox()" class="required_group_reg" 
                        <?php if($assc_prj_update == "yes") { if(in_array("Virginia", $regionkeyUpArray)) { echo "checked";} } else { if(in_array("Virginia", $regionArr)) { echo "checked";} } ?> disabled>
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
          <td colspan="2" align="left"><h4 style="color: #00aaf5">DRIVERS </h4>
            <div class="box">
            <table width="100%" border="0">
                <tr>
                  <td width="51%"><label>
                    <input type="radio" name="Drivers[]" value="1"  id="Drivers_0" class="required_group" <?php if(in_array("Material Delay", $driverArr)) { echo "checked";} ?>>
                    Material Delay</label></td>
                  <td width="49%"><label>
                    <input type="radio" name="Drivers[]" value="6" id="Drivers_10" class="required_group" <?php if(in_array("Project Dependency", $driverArr)) { echo "checked";} ?>>
                    Project Dependency</label></td>
                  </tr>
                <tr>
                  <td><label>
                    <input type="radio" name="Drivers[]" value="2" id="Drivers_1" class="required_group" <?php if(in_array("Shipping/Receiving Delay", $driverArr)) { echo "checked";} ?>>
                    Shipping/Receiving Delay</label></td>
                  <td><label>
                    <input type="radio" name="Drivers[]" value="7" id="Drivers_6" class="required_group" <?php if(in_array("Budget/Funding", $driverArr)) { echo "checked";} ?>>
                    Budget/Funding</label></td>
                  </tr>
                <tr>
                  <td><label>
                    <input type="radio" name="Drivers[]" value="3" id="Drivers_2" class="required_group" <?php if(in_array("Ordering Error", $driverArr)) { echo "checked";} ?>>
                    Ordering Error</label></td>
                  <td><label>
                    <input type="radio" name="Drivers[]" value="8" id="Drivers_7" class="required_group" <?php if(in_array("Design/Scope Change", $driverArr)) { echo "checked";} ?>>
                    Design/Scope Change</label></td>
                  </tr>
                <tr>
                  <td><label>
                    <input type="radio" name="Drivers[]" value="4" id="Drivers_3" class="required_group" <?php if(in_array("People Resource", $driverArr)) { echo "checked";} ?>>
                    People Resource</label></td>
                  <td><label>
                    <input type="radio" name="Drivers[]" value="9" id="Drivers_8" class="required_group" <?php if(in_array("Admin Error", $driverArr)) { echo "checked";} ?>>
                    Admin Error</label></td>
                  </tr>
                <tr>
                  <td><label>
                    <input type="radio" name="Drivers[]" value="5" id="Drivers_4" class="required_group" <?php if(in_array("3PL Resource", $driverArr)) { echo "checked";} ?>>
                    3PL Resource</label></td>
                  <td><label>
                    <input type="radio" name="Drivers[]" value="10" id="Drivers_9" class="required_group" <?php if(in_array("External Forces", $driverArr)) { echo "checked";} ?>>
                    External Forces</label></td>
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
                <td width="33%"></td>
                <td width="33%"></td>
                <td width="33%"></td>
                <td width="1%"></td>
              </tr>

              <tr>
                <td  valign="top">
                  <table width="200" border="0">
                  <tr>
                  <strong>Impacted Area </strong>
                  </tr>
                  <?php while($row_impArea= sqlsrv_fetch_array( $stmt_impArea , SQLSRV_FETCH_ASSOC)) { ?>
                    <tr>
                    <td><label>
                      <input type="radio" name="ImpactArea" value="<?php echo $row_impArea['ImpactArea_Key'] ?>" id="ImpactArea_<?php echo $row_impArea['ImpactArea_Key'] ?>" required <?php if($impactArea2==$row_impArea['ImpactArea_Nm']){echo "checked";}?>>
                      <?php echo $row_impArea['ImpactArea_Nm'] ?></label></td>
                    </tr>
                  <?php } ?>
                  </table></td>
                <td valign="top">
                  <table width="200" border="0">
                    <tr>
                      <strong>Impact Level</strong>
                    </tr>
                    <?php while($row_imLevel = sqlsrv_fetch_array( $stmt_imLevel , SQLSRV_FETCH_ASSOC)) { ?>
                    <tr>
                      <td><label>
                        <input name="ImpactLevel" type="radio" id="ImpactLevel_<?php echo $row_imLevel['ImpactLevel_Key'] ?>" value="<?php echo $row_imLevel['ImpactLevel_Key'] ?>" required <?php if($impactLevel2==$row_imLevel['ImpactLevel_Nm']){echo "checked";}?>>
                        <?php echo $row_imLevel['ImpactLevel_Nm'] ?></label></td>
                      </tr>
                    <?php } ?>  
                    </table>
                  </td>
                <td valign="top">
				<div id="myDIV2">
          <?php if($RIType == "Risk") { ?>
                    <table width="200" border="0">
                              <tr>
                                <td>
                                  <strong>Risk Probability Score</strong>
                                </td>
                              </tr>
                              </tr>
                        <?php while($row_probability= sqlsrv_fetch_array( $stmt_probability , SQLSRV_FETCH_ASSOC)) { ?>
                        <tr>
                        <td><label>
                          <input name="RiskProbability" type="radio" id="RiskProbability_<?php echo $row_probability['RiskProbability_Key'] ?>" value="<?php echo $row_probability['RiskProbability_Key'] ?>" required <?php if($probability==$row_probability['RiskProbability_Key']){echo "checked";}?>>
                          <?php echo $row_probability['RiskProbability_Nm'] ?></label></td>
                        </tr>
                        <?php } ?>
                    </table>
          <?php } else { ?>
            <input name="RiskProbability" type="hidden" id="RiskProbability" value="">
          <?php } ?>
        </div>
				</td>
                <td>
                
				        </td>
                </tr>
              </tbody>
          </table>
		</div> 
        </td>
        </tr>
        <!--
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
            <label for="Individual">Individual POC *<br></label>
                <select type="text" list="Individual" name="Individual" class="form-control" id="indy" required>
                  
                    <?php while($row_internal  = sqlsrv_fetch_array( $stmt_internal , SQLSRV_FETCH_ASSOC)) { ?>
                      <option value=""></option>
                      <option value="<?php echo $row_internal['POC_Nm'] ;?>" <?php if($POC_Nm == $row_internal['POC_Nm']) { echo "selected";} ?>><?php echo $row_internal['POC_Nm'] . " : " . $row_internal['POC_Department'] ;?></option>
                    <?php } ?>
                </select>  
              <hr>
                <div align="center">
                  <span class="glyphicon glyphicon-edit"></span> <a href="https://coxcomminc.sharepoint.com/teams/engmgmtoffice/Lists/EPS%20Support%20%20Enhancement%20Portal/AllItems.aspx" target="_blank">Request POC Addition</a>
                </div>
          </div>
              </div>
          </td>
          </tr> 
        -->
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
                  min="<?php echo $forecastMin; ?>"
                  class="form-control" 
                  id="date" 
                  value=""
                  onChange="forCastedx()"  
                  oninvalid="this.setCustomValidity('You must select a date or check Unknown ')"
                  oninput="this.setCustomValidity('')"
                  >
          </div>
          <div id="forcastedDate">
              <input type="checkbox" 
                  name="Unknown" 
                  id="Unknown" 
                  onChange="unKnownx()"
                  <?php if(empty($date)){ echo "checked";} ?>
                  >
              <label for="Unknown">Unknown</label> - Overrides Resolution Date
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
                          <textarea name="ActionPlan" cols="120" class="form-control" id="ActionPlan" ><?php //echo $actionPlan; ?></textarea>  
                          <input type="hidden" value="<?php echo $actionPlan_b?>" name="ActionPlan_b">
                          <input type="hidden" name="user" value="<?php echo $user_id ?>">
                          <input type="hidden" name="tempID"value="<?php //echo $temp_id ?>">
                    </td>
                  </tr>
                  <tr>
                    <td>
                    <div align="right" style="margin-top:10px; margin-bottom:10px;">  
                    <a class="btn btn-primary" role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="true" aria-controls="collapseExample">History</a>
                    </div>
                        <div class="collapse in" id="collapseExample">
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
          <td colspan="2" align="left"></td>
        </tr>
        <tr>
        <td colspan="2" align="left"><h4 style="color: #00aaf5">PROJECT ASSOCIATION</h4></td>
        </tr>
        <tr>
          <td colspan="2" align="left">
        <div class="box" style="font-size: 12px;">

				  <?php 
            echo str_replace(",","<br>", $assocProject);
          ?>

        </div>
      </td>
        </tr>

        <tr>
          <td colspan="2" align="left"><h4 style="color: #00aaf5">RISK REALIZED</h4></td>
        </tr>
        <tr>
          <td colspan="2" align="left">
            <div class="box">
              <table width="50%" border="0">
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  </tr>
                <tr>
                  <td colspan="2"><strong>Risk Realized?</strong></td>
                  </tr>
                <tr>
                  <td><label>
                    <input type="radio" name="riskRealized" value="1" id="RiskRelized_0" <?php if($riskRealized == 1){ echo "checked";} ?>>
                    Yes</label></td>
                  <td><label>
                    <input type="radio" name="riskRealized" value="0" id="RiskRelized_1" <?php if($riskRealized == 0){ echo "checked";} ?>>
                    No</label></td>
                  </tr>
                </table>
              </div>
            
          </td>
          </tr>
        <tr>
          <td colspan="2" align="left"></td>
        </tr>
        <tr>
          <td colspan="2" align="left"><h4 style="color: #00aaf5">ASSOCIATED CR ID</h4></td>
        </tr>
        <tr>
          <td colspan="2" align="left">
            <div class="box">
              <label for="assCRID">CR ID</label>
              <input name="assCRID" type="text" class="form-control" id="assCRID" value="<?php echo $assCRID;?>">
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
                    <input type="radio" name="raidLog" value="Yes" id="raid_0"<?php if($raidLog == 1) {echo "checked";}?>>
                    Yes</label></td>
                  <td><label>
                    <input type="radio" name="raidLog" value="No" id="raid_1" <?php if($raidLog == 0) {echo "checked";}?>>
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
                <input type="date" name="DateClosed" id="DateClosed" class="form-control" min="<?php echo $createDT; ?>" max="<?php echo $closeDateMax; ?>">
          </div>
          </td>
        </tr>
        <tr>
          <td colspan="2" align="right" valign="middle">&nbsp;</td>
        </tr>
      </tbody>
    </table><div align="right">
    <button type="button" class="btn btn-primary" onclick="myConfirmation()"><span class="glyphicon glyphicon-step-backward"></span> Back </button>
    <button type="submit" class="btn btn-primary" onclick="return validateForm()">Review <span class="glyphicon glyphicon-step-forward"></span></button>  
    </div>
  </form>
    <div align="left" style="margin-top:-33px;">  
    
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
  let things = document.querySelectorAll('.required_group_reg')
  let checked = 0;
  for (let thing of things) {
    thing.checked && checked++
  }
  if (checked) {
    things[things.length - 1].setCustomValidity("");
    document.getElementById('checkGroup').submit();
  } else {
    // things[things.length - 1].setCustomValidity("type="radio" name="Drivers");
    things[things.length - 1].setCustomValidity("This is where a validation message goes");
    things[things.length - 1].reportValidity();
  }
}

document.querySelector('[type=submit]').addEventListener('click', () => {
  console.log("clicker")
  // validateGrp()
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
<script>
  // They commented out the indy field
  // document.getElementById("indy").addEventListener("change", function(){
  // const v = this.value.split(" : ");
  // this.value = v[0];
  // document.getElementById("InternalExternal").value = v[1];
  // });
</script>
<script>
$('.subscriber :checkbox').change(function () {
    var $cs = $(this).closest('.subscriber').find(':checkbox:checked');
    if ($cs.length > 3) {
        this.checked = false;
    }
});
</script> 

<script language="javascript">
document.getElementById("dateUnknown").addEventListener("change", function(){
document.getElementById("Unknown").checked = false;
})
</script>

<script>
document.querySelector("#date").addEventListener("keydown", (e) => {e.preventDefault()});
document.querySelector("#DateClosed").addEventListener("keydown", (e) => {e.preventDefault()});
</script>

<script src="includes/ri-functions.js"></script>
</body>
</html>