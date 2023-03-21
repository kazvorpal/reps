<?php 
include ("../includes/functions.php");
include ("../db_conf.php");
include ("../data/emo_data.php");
//include ("../sql/project_by_id.php");
include ("../sql/RI_Internal_External.php");
 
  $RiskAndIssue_Key = $_GET['rikey'];
  $fscl_year = $_GET['fscl_year'];
  //$proj_name = $_GET['projname']; //NOT NEEDED FOR PROGRAM
  $progkey = $_GET['progkey'];
  $progrikey = $_GET['progRIkey'];
  $status = $_GET['status'];
  $progName = $_GET['progname'];
  $formaction =  $_GET['action'];

  $projFromURL ="";
  if(isset($_GET['assoc_prj'])) {
    $projFromURL = "'" . str_replace(",", "','", $_GET['assoc_prj']) . "'"; 
  }

  $assc_prj_update = "";
  if(!empty($_GET['assc_prj_update'])) {
  $assc_prj_update = $_GET['assc_prj_update'];
  }
    
  $sql_risk_issue = "select * from RI_Mgt.fn_GetListOfAllRiskAndIssue(-1) where RIlevel_Cd = 'Program' and RiskAndIssue_Key = $RiskAndIssue_Key";
  $stmt_risk_issue = sqlsrv_query( $data_conn, $sql_risk_issue );
  $row_risk_issue = sqlsrv_fetch_array($stmt_risk_issue, SQLSRV_FETCH_ASSOC);
  //echo $sql_risk_issue;

  //DECLARE
  //$action = $_GET['action']; //new
  //$temp_id = $_GET['tempid'];
  $user_id = preg_replace("/^.+\\\\/", "", $_SERVER["AUTH_USER"]);
  //$ass_project = $row_projID['PROJ_NM'];
  $ass_project = $projFromURL;

  if(!empty($_POST['proj_select'])) { 
    $ass_project_regions = implode("','", $_POST['proj_select']); 
    $ass_project_regionsx = $ass_project_regions; 
    $regionIN = "'" . $ass_project_regions . "','" . $ass_project . "'"; 
      } else { 
    $regionIN = "" . $ass_project . ""; 
      }
  
  //GET ASSOCIATED PROJECTS FROM 
  $sql_assoc_prj = "select * from RI_Mgt.fn_GetListOfAssociatedProjectsForProgramRIKey($RiskAndIssue_Key,$progrikey,$status)";
  $stmt_assoc_prj = sqlsrv_query( $data_conn, $sql_assoc_prj );
  //$row_assoc_prj= sqlsrv_fetch_array($stmt_assoc_prj, SQLSRV_FETCH_ASSOC);
  //$row_assoc_prj['EPSProject_Nm'];

  $sql_assoc_prj_keys = "select * from RI_Mgt.fn_GetListOfAssociatedProjectsForProgramRIKey($RiskAndIssue_Key,$progrikey,$status)";
  $stmt_assoc_prj_keys  = sqlsrv_query( $data_conn, $sql_assoc_prj_keys  );
  //$_keys = sqlsrv_fetch_array($stmt_assoc_prj_keys , SQLSRV_FETCH_ASSOC);
  //$row_assoc_prj_keys ['PROJECT_key'];
  //echo $sql_assoc_prj_keys;

  //GET REGIONS FROM NAME
  $sql_regions = "SELECT DISTINCT Region_key, Region
                  FROM [RI_MGT].[fn_GetListOfRegionForEPSProject] ()
                  WHERE PROJ_NM IN ($regionIN)";
  $stmt_regions = sqlsrv_query( $data_conn, $sql_regions );
  //$row_regions = sqlsrv_fetch_array( $stmt_regions, SQLSRV_FETCH_ASSOC);
  //$row_regions['Region'];
  //echo $sql_regions;

//GET EPS PROJECT KEYS FROM PROJECT NAMES
  $sql_epsProjKey = "DECLARE @EPS_IDs VARCHAR(1000)
      SELECT @EPS_IDs = COALESCE(@EPS_IDs+',','')+ CAST(EPSProject_key AS VARCHAR(1000))
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
      //echo $sql_region_update;
  if($assc_prj_update == "yes") {

    $inRegion = $ass_project_regions; //$row_region_update['Region_Cd'];
    $IReplace = "'" . $inRegion . "'";
    //$IReplace = str_replace(",", "','", $inRegion);

    $sql_region_update = "DECLARE @REG_Nms VARCHAR(1000) 
    SELECT @REG_Nms = COALESCE(@REG_Nms+',','')+ CAST(Region_Key AS VARCHAR(1000)) 
    FROM [RI_MGT].[fn_GetListOfRegionForEPSProject] () 
    WHERE PROJ_NM IN ($IReplace ) 
    SELECT @REG_Nms AS Region_Cd";
    //echo $sql_region_update;
      }

  $stmt_region_update = sqlsrv_query( $data_conn, $sql_region_update );
  $row_region_update = sqlsrv_fetch_array( $stmt_region_update, SQLSRV_FETCH_ASSOC);
  //echo "<B>Regions from ASSOC PROJECTS: </B>" . $sql_region_update . "<br><br>";
  
  //GET REGIONS KEYS FOR HIDDEN FIELD
  $regionkeySQL = " DECLARE @REG_IDs VARCHAR(1000) 
  SELECT @REG_IDs = COALESCE(@REG_IDs+',','')+ CAST(Region_key AS VARCHAR(1000)) 
  FROM [RI_MGT].[fn_GetListOfRegionForEPSProject] () 
  WHERE PROJ_NM IN ($projFromURL) 
  SELECT @REG_IDs AS Region_key";
  //echo $regionkeySQL;
if($assc_prj_update == "yes") {

  $inRegion = $ass_project_regions; //$row_region_update['Region_Cd'];
  $IReplace = "'" . $inRegion . "'";
  //$IReplace = str_replace(",", "','", $inRegion);

  $regionkeySQL = " DECLARE @REG_IDs VARCHAR(100) 
  SELECT @REG_IDs = COALESCE(@REG_IDs+',','')+ CAST(Region_key AS VARCHAR(100)) 
  FROM [RI_MGT].[fn_GetListOfRegionForEPSProject] () 
  WHERE PROJ_NM IN ($IReplace) 
  SELECT @REG_IDs AS Region_key";
  //echo $regionkeySQL;
}
//echo "<B>Region Keys for Hidden Fields: </B>" . $regionkeySQL;
$sql_regions_f = "$regionkeySQL";
  $stmt_regions_f = sqlsrv_query( $data_conn, $sql_regions_f );
  //echo $sql_regions_f;
  $row_regions_f = sqlsrv_fetch_array( $stmt_regions_f, SQLSRV_FETCH_ASSOC);
  //$row_regions_f['Region_key'];
  //echo $sql_regions_f;

  //explode(",",)
  //exit(); 
  //echo "<br>Its here" . $row_regions_f['Region_key'];

  //GET ALL REGIONS FOR REGIONS SELECTION
  $sql_regions = "SELECT DISTINCT Region_key, Region
                  FROM [RI_MGT].[fn_GetListOfRegionForEPSProject] () 
                  WHERE PROJ_NM IN ($regionIN)";
  $stmt_regions = sqlsrv_query( $data_conn, $sql_regions );
  //$row_regions = sqlsrv_fetch_array( $stmt_regions, SQLSRV_FETCH_ASSOC);
  //$row_regions['Region'];



  //explode(",",)
  //exit(); 
  //echo "<br>Its here" . $row_regions_f['Region_key'];

  //GET ALL REGIONS FOR UPDATE
  $sql_regions_update = "SELECT DISTINCT Region_key, Region
  FROM [RI_MGT].[fn_GetListOfRegionForEPSProject] () 
  WHERE PROJ_NM IN ($regionIN)";
  $stmt_regions_update = sqlsrv_query( $data_conn, $sql_regions_update );
  //$row_regions_update = sqlsrv_fetch_array( $stmt_regions_update, SQLSRV_FETCH_ASSOC);
  //$row_regions_update['Region'];
  //echo $sql_regions_update;

  //SINGLE REGION FOR NAME CONCATINATION
  $sql_region = "SELECT DISTINCT Region_key, Region
  FROM [RI_MGT].[fn_GetListOfRegionForEPSProject] () 
  WHERE PROJ_NM IN ($regionIN)";
  $stmt_region = sqlsrv_query( $data_conn, $sql_region );
  $row_region = sqlsrv_fetch_array( $stmt_region, SQLSRV_FETCH_ASSOC);
  //echo "<br>" . $sql_region;

  //MULTI-REGION CONCATINATION - COUNT
  $sql_regions_con = "SELECT Count(DISTINCT Region_key) as numRows
  FROM [RI_MGT].[fn_GetListOfRegionForEPSProject] () 
  WHERE PROJ_NM IN ($regionIN)";
  $stmt_regions_con  = sqlsrv_query( $data_conn, $sql_regions_con  );
  $row_regions_con = sqlsrv_fetch_array( $stmt_regions_con, SQLSRV_FETCH_ASSOC );
 //echo "<br>" .$sql_regions_con;
  $numRows = $row_regions_con['numRows'];
  //echo $numRows . " " . $regionCode ;
  //exit();


  //GET EPS PROJECT KEYS FROM PROJECT NAMES
  $sql_epsProjKey = "DECLARE @EPS_IDs VARCHAR(100)
                    SELECT @EPS_IDs = COALESCE(@EPS_IDs+',','')+ CAST(EPSProject_key AS VARCHAR(MAX))
                    FROM RI_MGT.fn_GetListOfLocationsForEPSProject(1) WHERE EPSProject_Nm in ($regionIN)
                    SELECT @EPS_IDs AS eps_proj_key";
  $stmt_epsProjKey = sqlsrv_query( $data_conn, $sql_epsProjKey );
  $row_epsProjKey = sqlsrv_fetch_array( $stmt_epsProjKey, SQLSRV_FETCH_ASSOC);
  //echo $sql_epsProjKey;
  $eps_proj_keys = $row_epsProjKey['eps_proj_key'];

  if($numRows == 9){
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
      } else if($regionCode=='East'){
          $regionCD = 'EA';
      } else if($regionCode=='West'){
          $regionCD = 'WS';
      } 
} else if($numRows >= 2 && $numRows <= 8){
    $regionCD= "Multi";
}
  
  //DEFINE
  $changeLogKey = 4;
  if($formaction == "new"){
    $changeLogKey = 2;
  }
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
  $actionPlan = "";
  $actionPlan_b = $row_risk_issue['ActionPlanStatus_Cd'];
  $DateClosed = $row_risk_issue['RIClosed_Dt'];
  $driverList = rtrim($_GET['drivertime'], ",");
  $driverArr = explode(",", $driverList);
  $regionList = rtrim($_GET['regions'], ",");
  $regionArr = explode(",", $regionList); //for update // add array for update assoc projects
  $RIClosed_Dt = $row_risk_issue['RIClosed_Dt'];
  $raid = $row_risk_issue['RaidLog_Flg'];
  $riskRealized = $row_risk_issue['RiskRealized_Flg'];
  $assCRID = $row_risk_issue['AssociatedCR_Key'];
  $regions = $_GET['regions'];
  $raidLog = $row_risk_issue['RaidLog_Flg'];
  $department = $row_risk_issue['POC_Department'];
  $createDT = date_format($row_risk_issue['Created_Ts'],'Y-m-d');
  $POC_Nm = $row_risk_issue['POC_Nm'];

    //REGIONS FROM DATABASE
    $regionkeyUp = $row_region_update['Region_Cd']; //echo "<b></br></br>Regions to update: </b>" .  $regionkeyUp . "</br>";
    $regionkeyUpArray = array_unique(explode(",", $regionkeyUp)); 
    //echo "<br>HERE " . print_r($regionkeyUpArray);
  
    //REGIONS FROM QUERYSTRING
    $regions = $_GET['regions'];
    if($assc_prj_update == "yes"){
      $regions = $regionkeyUp;
    }
    //echo "Selected assoc proj: " . $assc_prj_update . "<br>"; //YES/NO HAS NEW PROJECT ASSOCIATIONS COMING FROM PREVIOUSE PAGE
  
    $createDT = date_format($row_risk_issue['Created_Ts'],'Y-m-d');
    $regionKeys = rtrim(implode(",",array_unique(explode(",", $row_regions_f['Region_key']))), ","); 
    //echo "Region Keys from database: " . $regionKeys; // ALL REGION KEYS

  if(!empty($row_risk_issue['ForecastedResolution_Dt'])) {
    $forecastMin = date_format($date, "Y-m-d");
  } else {
    $forecastMin = $closeDateMax;
  }

  if($formaction == "new"){
    if(!empty($_POST['proj_select'])) {
    $assocProject = implode(",",$_POST['proj_select']) . "," . $RiskAndIssue_Key ;
    } else {
    $assocProject = $RiskAndIssue_Key;
    }
} else {
    if(!empty($_POST['proj_select'])) {
    $assocProject = implode(",",$_POST['proj_select']);
    } else {
    $assocProject = $RiskAndIssue_Key;
    }
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

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <script src="../colorbox-master/jquery.colorbox.js"></script>
  
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"> 
  <link rel="stylesheet" href="includes/ri-styles.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
  <link rel="stylesheet" href="steps/style.css" type='text/css'> 
  <link rel="stylesheet" href="../colorbox-master/example1/colorbox.css" />
  
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
  <input name="formName" type="hidden" id="formName" value="PRGI">
  <input name="fiscalYer" type="hidden" id="fiscalYer" value="<?php echo $row_risk_issue['Fiscal_Year'] ?>">
  <input name="RIType" type="hidden" id="RIType" value="Issue">
  <input name="RILevel" type="hidden" id="RILevel" value="Program">

  <?php if($formaction == "new") {?>
    <input name="assocProjects" type="hidden" id="assocProjects" value="<?php echo $row_projID['PROJ_NM'] ?>">
    <input name="formType" type="hidden" id="formType" value="Update">
    <input name="assocProjectsKeys" type="hidden" id="assocProjectsKeys" value='<?php while ($row_assoc_prj_keys= sqlsrv_fetch_array($stmt_assoc_prj_keys, SQLSRV_FETCH_ASSOC)) { echo $row_assoc_prj_keys['PROJECT_key'] . ',';} ?>'>
  <?php } else { ?>
    <input name="assocProjects" type="hidden" id="assocProjects" value="<?php echo $assocProject ?>">
    <input name="formType" type="hidden" id="formType" value="New">
    <input name="assocProjectsKeys" type="hidden" id="assocProjectsKeys" value="<?php echo $eps_proj_keys?>">
  <?php } ?>

  <input name="TransfertoProgramManager" type="hidden" id="TransfertoProgramManager" value="">
  <input name="program" type="hidden" id="program" value='<?php echo $row_risk_issue['MLMProgram_Nm']; ?>'> <!-- EPS PROGRAM -->
  <input name="RIName" type="hidden" id="RIName" value='<?php echo $_GET['name'] ?>'>
  <input name="RiskAndIssue_Key" type="hidden" id="RiskAndIssue_Key" value='<?php echo $RiskAndIssue_Key ?>'>
  <input name="programKeys" type="hidden" id="programKeys" value='<?php echo $progkey ?>'>
  <input name="regionKeys" type="hidden" id="regionKeys" value="<?php echo $regionKeys?>">
  <input type="hidden" name="Region_add_assc_prj" id="Region_add_assco_proj" value="<?php while($row_regions_update = sqlsrv_fetch_array( $stmt_regions_update, SQLSRV_FETCH_ASSOC)) { echo $row_regions_update['Region'] . "," ; } ?>">
  <input name="Region" type="hidden" id="Region" value="<?php echo $regions ?>">
  <input name="RiskProbability" type="hidden" id="RiskProbability" value="">
  <input name="riskRealized" type="hidden" value="0">
  <input name="CreatedFrom" type="hidden" class="form-control" id="Created From" value="">
  <input name="formaction" type="hidden" id="formaction" value="<?php echo $formaction ?>">
  <input name="Individual" type="hidden" id="Individual" value="">
  <input name="changeLogAction" type="hidden" id="changeLogAction" value="">
  <input name="changeLogReason" type="hidden" id="changeLogReason" value="">
  <input name="raidLog" type="hidden"id="raidLog" value="<?php echo $raidLog ?>">

  <?php if($assc_prj_update == "yes"){ ?>
  <div class="alert alert-danger">
  <div align="left">
    <span class="glyphicon glyphicon-warning-sign"></span> You are Updating the Associated Project list for this Program Risk/Issue to the following.  You may change the details of this Risk/Issue at this time.
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
            <h4 style="color: #00aaf5">PROGRAM ISSUE</h4>
          </th>
          <th align="left">
            <h4 style="color: #00aaf5">REGIONS</h4>
          </th>
        </tr>
        <tr>
          <td colspan="2" align="left"></td>
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
                <input name="NameA" type="hidden" id="NameA" value="<?php //echo $row_projID['PRGM'];?>">
                <input name="NameA1" type="hidden" id="NameA1" value="<?php //echo $row_projID['SCOP_DESC'];?>">
                <input name="NameB" type="hidden" id="NameB" value="<?php echo $regionCD; ?>"> <!-- Region -->
                <input name="NameC" type="hidden" id="NameC" value="<?php //echo "POR" . substr($row_projID['FISCL_PLAN_YR'], -2) ?>"></td>
              </tr>
              <tr>
                <td><br>
                  <label for="Descriptor">Risk Descriptor* <a href="includes/definitions.php?tooltipkey=RSKD" class="dno"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                    <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                    </svg></a><br>
                  </label>
                  <input name="Descriptor" type="text" required="required" class="form-control" id="Descriptor" maxlength="30" value="<?php echo $descriptor;?>" readonly>  
                </td>
              </tr>
              <tr>
                <td><br>
                  <label for="Description">Description * <a href="includes/definitions.php?tooltipkey=DESC" class="dno"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                    <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                    </svg></a><br>
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
                    <p><strong>Selected Regions
                      </strong><br>
                      
<?php if($fscl_year <= "2023") { ?>
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
<?php } else { ?>
                      <label>
                        <input type="checkbox" name="Regionx[]" value="All" id="Region" onClick="toggle(this); updatebox()" class="required_group_reg" 
                        <?php if($assc_prj_update == "yes") { if(in_array("All", $regionkeyUpArray)) { echo "checked";} } else { if(in_array("All", $regionArr)) { echo "checked";} } ?> disabled>
                        All</label>
                      <br>
                      <label>
                        <input type="checkbox" name="Regionx[]" value="Corporate" id="Region_6" onClick="updatebox()" class="required_group_reg" 
                        <?php if($assc_prj_update == "yes") { if(in_array(1, $regionkeyUpArray)) { echo "checked";} } else { if(in_array("Corporate", $regionArr)) { echo "checked";} } ?> disabled>
                        Corporate (COR)</label>
                      <br><label>
                        <input type="checkbox" name="Regionx[]" value="Central" id="Region_7" onClick="updatebox()" class="required_group_reg" 
                        <?php if($assc_prj_update == "yes") { if(in_array(3, $regionkeyUpArray)) { echo "checked";} } else { if(in_array("East", $regionArr)) { echo "checked";} } ?> disabled>
                        Central (CN)</label>
                      <br>
                      <label>
                        <input type="checkbox" name="Regionx[]" value="East" id="Region_7" onClick="updatebox()" class="required_group_reg" 
                        <?php if($assc_prj_update == "yes") { if(in_array(2, $regionkeyUpArray)) { echo "checked";} } else { if(in_array("East", $regionArr)) { echo "checked";} } ?> disabled>
                        East (EA)</label>
                      <br>
                      <label>
                        <input type="checkbox" name="Regionx[]" value="West" id="Region_8" onClick="updatebox()" class="required_group_reg" 
                        <?php if($assc_prj_update == "yes") { if(in_array(4, $regionkeyUpArray)) { echo "checked";} } else { if(in_array("West", $regionArr)) { echo "checked";} } ?> disabled>
                        West (WS)</label>
<?php } ?>
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
          <td colspan="2" align="left"><h4 style="color: #00aaf5">DRIVERS * <a href="includes/definitions.php?tooltipkey=DRVR" class="iframe"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
              <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
              <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
              </svg></a></h4>
            <div class="box subscriber">
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
                <td width="25%"></td>
                <td width="25%"></td>
                <td width="25%"></td>
                <td width="25%"></td>
              </tr>

              <tr>
                <td  valign="top">
                  <table width="200" border="0">
                  <tr>
                  <strong>Impacted Area* </strong>
                  <a href="includes/definitions.php?tooltipkey=IMPA" class="dno"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                    <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                    </svg>
                  </a>
                  </tr>
                  <?php while($row_impArea= sqlsrv_fetch_array( $stmt_impArea , SQLSRV_FETCH_ASSOC)) { ?>
                    <tr>
                  <?php while($row_impArea= sqlsrv_fetch_array( $stmt_impArea , SQLSRV_FETCH_ASSOC)) { ?>
                    <tr>
                    <td><label>
                      <input type="radio" name="ImpactArea" value="<?php echo $row_impArea['ImpactArea_Key'] ?>" id="ImpactArea_<?php echo $row_impArea['ImpactArea_Key'] ?>" required <?php if($impactArea2==$row_impArea['ImpactArea_Nm']){echo "checked";}?>>
                      <?php echo $row_impArea['ImpactArea_Nm'] ?></label></td>
                      <input type="radio" name="ImpactArea" value="<?php echo $row_impArea['ImpactArea_Key'] ?>" id="ImpactArea_<?php echo $row_impArea['ImpactArea_Key'] ?>" required <?php if($impactArea2==$row_impArea['ImpactArea_Nm']){echo "checked";}?>>
                      <?php echo $row_impArea['ImpactArea_Nm'] ?></label></td>
                    </tr>
                  <?php } ?>
                  <?php } ?>
                  </table></td>
                <td valign="top">
                  <table width="200" border="0">
                    <tr>
                      <strong>Impact Level* </strong>
                      <a href="includes/definitions.php?tooltipkey=IMPL" class="dno"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                        </svg>
                      </a>
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
                <div align="left">
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
              <a href="includes/definitions.php?tooltipkey=FRRD" class="dno"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                </svg>
              </a>
			  <div id="dateUnknown">
              <input name="date"
                  min="<?php echo $forecastMin; ?>"
                  type="date"
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
          <td colspan="2" align="left"><h4 style="color: #00aaf5">RESPONSE STRATEGY* <a href="includes/definitions.php?tooltipkey=RSPS" class="dno"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
              <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
              <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
            </svg></a></h4>			  
        </td>
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
          <td colspan="2" align="left"><h4 style="color: #00aaf5">ACTION PLAN* <a href="includes/definitions.php?tooltipkey=ACTP" class="dno"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
              <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
              <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
            </svg></a></h4>
          <div class="box">  
            <table width="100%" border="0" cellpadding="5" cellspacing="5">
              <tbody>
                <tr>
                  <td width="100%">
                    <textarea name="ActionPlan" cols="120" class="form-control" id="ActionPlan"><?php echo $actionPlan; ?></textarea>
                    <input type="hidden" value="<?php echo $actionPlan_b?>" name="ActionPlan_b">
                  </td>
                </tr>
                <tr>
                    <td>
                    <div align="right" style="margin-top:10px; margin-bottom:10px;">  
                      <a class="btn btn-primary" role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="true" aria-controls="collapseExample">History</a>
                    </div>
                        <div class="collapse in" id="collapseExample" >
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
        <tr align="left">
        <td colspan="2" align="left"><h4 style="color: #00aaf5">ASSOCIATIONS</h4></td>
        </tr>
        <tr>
          <td colspan="2" align="left">
          <b>Project Association* <a href="includes/definitions.php?tooltipkey=PRJA" class="dno"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
              <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
              <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
            </svg></a></b>
            <div class="box" style="font-size: 12px;">
              <?php 
                if($formaction == "update") {
                  while ($row_assoc_prj= sqlsrv_fetch_array($stmt_assoc_prj, SQLSRV_FETCH_ASSOC)) { echo $row_assoc_prj['EPSProject_Nm'] . '<br>';} 
                } else {
                  echo str_replace(",","<br>", $assocProject);
                }
              ?>
            </div>
          </td>
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
      <!--
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
        </tr>-->
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
    </table>
    <div align="right">
      <button type="button" class="btn btn-primary" onclick="myConfirmation()"><span class="glyphicon glyphicon-step-backward"></span> Back </button>
    <button type="submit" class="btn btn-primary" onclick="return validateForm()">Review <span class="glyphicon glyphicon-step-forward"></span></button>  
    </div>
  </form><!--
    <div align="left" style="margin-top:-33px;">  
    <button class="btn btn-primary" onclick="myConfirmation()"><span class="glyphicon glyphicon-step-backward"></span> Back </button>
    </div> -->
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
    things[things.length - 1].setCustomValidity("type="radio" name="Drivers");
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

<script>
  document.getElementById("indy").addEventListener("change", function(){
  const v = this.value.split(" : ");
  this.value = v[0];
  document.getElementById("InternalExternal").value = v[1];
  });
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