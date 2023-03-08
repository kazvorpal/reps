<?php 
// DECLARE
//4 update, 3 close, 2 create/add, 1 initialize, 5 delete
//echo str_replace('  ', '&nbsp; ', nl2br(print_r($_POST, true)));
//exit();

// Posted Values
$global = 0; 
if(isset($_POST['global'])){
  $global = $_POST['global'];
}

$formType = $_POST['formType']; // NEW OR DELETE
$lrpYear = $_POST['fiscalYer']; // FISCAL YEAR OF THE PROJECT
$riTypeCode = $_POST['RIType']; // RISK OR ISSUE
$riLevel = $_POST['RILevel']; // PRJECT OR PROGRAM

$createdFrom = "";
if(isset($_POST['CreatedFrom'])){
$createdFrom = $_POST['CreatedFrom']; // THE RISK THE ISSUE WAS CREATED FROM - FOR ISSUE ONLY
}

$descriptor = str_replace("'","",$_POST['Descriptor']);  // DESCRIPTOR
$description = $_POST['Description']; 
$impactArea = $_POST['ImpactArea']; 
$impactLevel = $_POST['ImpactLevel']; 

$delete = "";
if(isset($_POST['delete'])){
$delete = $_POST['delete'];
}

$assCRID = "";
if(isset($_POST['assCRID'])){
  $assCRID = $_POST['assCRID'];
}

$portfolioType = "";
if(isset($_POST['portfolioType'])) {
  $portfolioType = $_POST['portfolioType'];
} else if($riLevel = "Program"){
  $portfolioType = "";
}

$portfolioType_Key = "";
if(isset($_POST['portfolioType_Key'])) {
  $portfolioType_Key = $_POST['portfolioType_Key'];
}

$program = "";
if(!empty($_POST['program']) && $global == 1){
  $program = implode(",", $_POST['program']);
} else if(!empty($_POST['program'])) {
  $program = $_POST['program'];
}

$programs = "";
if($global == 1) {
  if(!empty($_POST['programs'])) {
    $programs = implode(",",$_POST['programs']);
    }
} else { 
  if(!empty($_POST['programs'])) {
  $programs = $_POST['programs'];
  }
}

$subprogram = "";
if(!empty($_POST['subprogram'])){
$subprogram = implode(",",$_POST['subprogram']);
}

//CHANGE LOG VALUES
$changeLogAct = explode(":", $_POST['changeLogAction']);

if($_POST['changeLogAction'] != "" && $_POST['changeLogReason'] != ""){
  $changeLogActionVal = $changeLogAct[0];
  $changeLogAction = $changeLogAct[1];
  $changeLogReason = $_POST['changeLogReason'];
  $PRJILog_Flg = 1;
} else {
  $changeLogActionVal = $changeLogAct[0]; //key
  $changeLogReason = $_POST['changeLogReason']; // reason
  $PRJILog_Flg = 0; //flag
}

//CHANGE LOG ESTIMATED DATES
if($_POST['changeLogAction'] == "5:POR Schedule Update") {
  $EstActiveDate = $_POST['EstActiveDate'];
  $EstMigrateDate = $_POST['EstMigrateDate'];
} else {
  $EstActiveDate = NULL;
  $EstMigrateDate = NULL;
}

//GLOBAL SUBPROGRAMS
if($global == 1 && $riLevel != "Portfolio") {
  
  $sql_subprg = "DECLARE @SUBP_IDs VARCHAR(1000)
      SELECT @SUBP_IDs = COALESCE(@SUBP_IDs+'<br>','')+ CAST(SubProgram_Nm AS VARCHAR(1000))
      FROM mlm.fn_getlistofsubprogramforprogram(-1) WHERE SubProgram_Key IN ($subprogram) AND Program_Nm = '$program' AND LRPYear = $lrpYear
      SELECT @SUBP_IDs AS SubProgram_Nm";
    $stmt_subprg = sqlsrv_query( $data_conn, $sql_subprg );
    $row_subprg = sqlsrv_fetch_array( $stmt_subprg, SQLSRV_FETCH_ASSOC);
    $subprogram_glb = $row_subprg['SubProgram_Nm'];
    //echo $sql_subprg;
}


if(!empty($_POST['status'])){
  $status = $_POST['status'];
} else {
  $status = 2;
}
$RiskAndIssue_Key = "";
if(!empty($_POST['RiskAndIssue_Key'])){
  $RiskAndIssue_Key = $_POST['RiskAndIssue_Key'];
}

$del_proj_select = "";
if(isset($_POST['del_proj_select'])) {
  $del_proj_select = $_POST['del_proj_select'];
  $RiskAndIssue_Key = $del_proj_select;
}

$Drivers = implode(',', $_POST['Drivers']);
  $Driversx = $Drivers;

$Drivers_confirm = implode('<br>', $_POST['Drivers']); // For Confirmation page DISPLAY
  $Drivers_conx = $Drivers_confirm;


//CONVERT DRIVER ID TO NAME FOR PROJECT ASSOCIATION ADDITION
if(!empty($_POST['add_proj_select'])) {
  $sql_assocproj_driver = "SELECT * FROM [RI_MGT].[Driver] WHERE Driver_Key = $Driversx";
  $stmt_assocproj_driver  = sqlsrv_query( $data_conn, $sql_assocproj_driver ); 
  $row_assocproj_driver  = sqlsrv_fetch_array( $stmt_assocproj_driver , SQLSRV_FETCH_ASSOC);

  $Driversx = $row_assocproj_driver['Driver_Nm'];
  $Drivers_conx = $row_assocproj_driver['Driver_Nm'];
}

$regionx = "";
if($_POST['changeLogKey']==3 || ($_POST['changeLogKey']==4 && $global != 1) || ($_POST['changeLogKey']==2 && $global != 1)){
  if(!empty($_POST['Region'])) {
    //$region = substr($_POST['Region'],0, -1);
    $region = $_POST['Region'];
    $regionx = $region;
    $region_confirm = substr($_POST['Region'],0, -1);
    $region_conx = $region_confirm;
    //$region_conx_dsply = str_replace(",","<br>",$region_conx);
    $region_conx_dsply = str_replace(",", "<br>", $_POST['Region']);
  }
} else {
  if(!empty($_POST['Region'])) {
    $region = implode(',', $_POST['Region']);
    $regionx = $region;
    $region_confirm = implode(',', $_POST['Region']);
    $region_conx = $region_confirm;
    $region_conx_dsply = str_replace(",","<br>",$region_conx);
    //$region_conx_dsply = str_replace(",", "<br>", $_POST['Region']);
  }
}

//$userId = $_POST['userId']; // WINDOWS LOGIN NAME

$riskProbability = "";
  if(isset($_POST['RiskProbability'])) {
    $riskProbability = $_POST['RiskProbability'];
  }

$project_nm = "";
if(isset($_POST['project_nm'])) {
$project_nm = $_POST['project_nm'];
}

$assocProject = "";
if(!empty($_POST['assocProjects'])){
  $assocProject = $_POST['assocProjects']; 
} 

if(isset($_POST['add_proj_select'])) {
  $assocProject = $_POST['add_proj_select'];
}

$assocProject_dsply = str_replace(",","<br>",$assocProject);

$actionPlan = $_POST['ActionPlan'];
  if(empty($_POST['ActionPlan'])) {
    $actionPlan = $_POST['ActionPlan_b'];
  }

$responseStrategy = $_POST['ResponseStrategy']; 
$date = $_POST['date']; // FORCASTED RESOLUTION DATE


$DateClosed = NULL;
if (!empty($_POST['DateClosed'])) {
$DateClosed = $_POST['DateClosed']; 
}

$unknown = 'off';
  if(!empty($_POST['Unknown'])) {
  $unknown = $_POST['Unknown'];
  }

//LOGIC FOR CURRENT POC
$individual = ""; 
  if(isset($_POST['Individual'])) {
    $individual = $_POST['Individual']; 
    $pocFlag = 1;
  }

//DEPARTMENT ($internalExternal) GET DEPARTMENT FROM POC NAME ($individual)
$internalExternal = "";

$sql_dept = "select * from [RI_MGT].[fn_GetListOfCurrentTaskPOC] (1) WHERE POC_Nm = '$individual' ";
$stmt_dept = sqlsrv_query( $data_conn, $sql_dept );
$row_dept = sqlsrv_fetch_array($stmt_dept, SQLSRV_FETCH_ASSOC);

if($row_dept != "" ) {
  $internalExternal= $row_dept['POC_Department'];
}

$pocFlag = 0;

$opportunity = "";
  if (!empty($_POST['opportunity'])){
    $opportunity = $_POST['opportunity'];
  }

$transfer2prgManager = "0";
  if(isset($_POST['TransfertoProgramManager'])) {
    $transfer2prgManager = 1;
  }

// Hidden Values
$userId = $_POST['userId'];
//$formName = $_POST['formName'];
$formType = $_POST['formType'];
$fiscalYer = $_POST['fiscalYer'];
$RIType = $_POST['RIType'];
$RILevel = $_POST['RILevel'];
$formName = $_POST['formName']; // PRJR, PRJI, PRGI, PRGR, PRTR, PRTI

  if($global == 1 && $_POST['RIType'] == "Risk") {
      $formName = "PRGR";
  } 
  
  if($global == 1 && $_POST['RIType'] == "Issue"){
      $formName = "PRGI";
      $riskProbability = "";
  } 
  
  if($RILevel == "Portfolio" &&  $_POST['RIType'] == "Risk") {
      $formName = "PRTR";
  } 
  
  if($RILevel == "Portfolio" &&  $_POST['RIType'] == "Issue") {
      $formName = "PRTI";
      $riskProbability = "";
  }
$changeLogKey = $_POST['changeLogKey'];
  if(!empty($DateClosed)){
    $changeLogKey = 3;
  } 
  if($_POST['changeLogKey'] == 5) {
    $changeLogKey = 5;
  }

if (($changeLogKey == 4 && $global !=1) || ($changeLogKey == 3 && $global !=1)){
  $programKeys = $_POST['programKeys'];
  $regionKeys = substr($_POST['regionKeys'],0, -1);
  $regionKeys = $_POST['regionKeys'];

  if($_POST['formaction'] == "update") {
    $assocProjectsKeys = $_POST['assocProjectsKeys'];
  } else {
    $assocProjectsKeys = substr($_POST['assocProjectsKeys'],0, -1);
  }
    
} else {
  $programKeys = NULL;
  $regionKeys = NULL;
  $assocProjectsKeys = $_POST['assocProjectsKeys'];
}

//POC STUFF <DEAD/REMOVE>
if ($individual == "") {
  $poc = $internalExternal;
} else {
  $poc = $individual;
}

//$name = $_POST['RIName'];
//if($changeLogKey == 2){
$name = trim(str_replace("'","",$_POST['Namex'])); // PROJECT NAME 
//}

$riskRealized = "";
if(isset($_POST['riskRealized'])){
  $riskRealized = $_POST['riskRealized'];
}

$raidLog = (isset($_POST['raidLog'])) ? $_POST['raidLog'] : "1";

if(isset($_POST['groupID'])){
  $groupID = $_POST['groupID'];
} else {
  $groupID = NULL;
}

if(isset($_POST['del_proj_select'])) {
$del_proj_select = $_POST['del_proj_select'];
}

//FORM ACTION HANDLER
$messaging = "";
if(isset($_POST['formaction'])) {
  $messaging = $_POST['formaction'];
}

// LOOKUP KEY VALUES 
//IMPACT AREA
$sql_imp_area = "SELECT* FROM RI_MGT.Impact_Area WHERE ImpactArea_Key = $impactArea";
$stmt_imp_area  = sqlsrv_query( $data_conn, $sql_imp_area  ); 
$row_imp_area  = sqlsrv_fetch_array( $stmt_imp_area , SQLSRV_FETCH_ASSOC);
$impactArea2 = $row_imp_area['ImpactArea_Nm'];

//IMPACT LEVEL
$sql_imp_lvl = "SELECT* FROM RI_MGT.Impact_Level WHERE ImpactLevel_Key = $impactLevel";
$stmt_imp_lvl = sqlsrv_query( $data_conn, $sql_imp_lvl );  
$row_imp_lvl = sqlsrv_fetch_array( $stmt_imp_lvl, SQLSRV_FETCH_ASSOC);
$impactLevel2 = $row_imp_lvl['ImpactLevel_Nm'];

//RISK PROBABILITY
if($riTypeCode == "Risk") {
$sql_prob = "SELECT * FROM [RI_MGT].[Risk_Probability] WHERE RiskProbability_Key = $riskProbability";
$stmt_prob = sqlsrv_query( $data_conn, $sql_prob );  
$row_prob = sqlsrv_fetch_array( $stmt_prob, SQLSRV_FETCH_ASSOC);
$riskProbability2 = $row_prob['RiskProbability_Nm'];
//echo $sql_prob;
}

//RESPONSE STRATIGY
$sql_resp_strg = "SELECT* FROM RI_MGT.Response_Strategy WHERE ResponseStrategy_Key = $responseStrategy";
$stmt_resp_strg = sqlsrv_query( $data_conn, $sql_resp_strg );  
$row_resp_strg = sqlsrv_fetch_array( $stmt_resp_strg, SQLSRV_FETCH_ASSOC);
$responseStrategy2 = $row_resp_strg['ResponseStrategy_Nm'];

//PORTFOLIO NAME FROM ID
if($portfolioType != "") {
 $sql_portname = "SELECT * FROM [RI_MGT].[Portfolio_Type] WHERE PortfolioType_Key = $portfolioType";
 $stmt_portname  = sqlsrv_query( $data_conn, $sql_portname  );
 $row_portname  = sqlsrv_fetch_array( $stmt_portname , SQLSRV_FETCH_ASSOC);
 $portfolio_Nm = $row_portname ['PortfolioType_Nm'];
}

//GLOBAL ID TO NAME CONVERSIONS
if($global == 1 && $formType == "Update") {
  //CONVERT ARRAYS
  if($riLevel == "Portfolio") {
    $global_region = $_POST['Region'];
    $global_subprg = $_POST['subprogram'];
  } else {
    //$global_region = substr(implode(',', $_POST['Region']), 0, -1); //DECOMISSIONED 11.08.2023
    $global_region = implode(',', $_POST['Region']);
    $global_subprg = implode(',', $_POST['subprogram']);
  }

  $global_prg = implode(',', $_POST['program']);
  $global_drv = implode(',', $_POST['Drivers']);
  
  //REGIONS FOR GLOBAL
  if($riLevel == "Program") {
    $sql_regions = "DECLARE @REG_IDs VARCHAR(1000)
      SELECT @REG_IDs = COALESCE(@REG_IDs+'</BR>','')+ CAST(Region_Cd AS VARCHAR(1000))
      FROM [CR_MGT].[Region] WHERE Region_key IN($global_region)
      SELECT @REG_IDs AS Region_Cd";
    //echo $sql_regions . "<br>" . $global_region;
    //exit();

    $stmt_regions = sqlsrv_query( $data_conn, $sql_regions );
    $row_regions = sqlsrv_fetch_array( $stmt_regions, SQLSRV_FETCH_ASSOC);
    $region_glb = $row_regions['Region_Cd'];
    
    //echo $sql_regions;
    //exit();

  //SUBPROGRAMS FOR GLOBAL
    $sql_subprg = "DECLARE @SUBP_IDs VARCHAR(1000)
      SELECT @SUBP_IDs = COALESCE(@SUBP_IDs+'</BR>','')+ CAST(SubProgram_Nm AS VARCHAR(1000))
      FROM mlm.fn_getlistofsubprogramforprogram(-1) WHERE SubProgram_Key IN ($global_subprg) AND Program_Key = $global_prg
      SELECT @SUBP_IDs AS SubProgram_Nm";
    $stmt_subprg = sqlsrv_query( $data_conn, $sql_subprg );
    $row_subprg = sqlsrv_fetch_array( $stmt_subprg, SQLSRV_FETCH_ASSOC);
    $subprogram_glb = $row_subprg['SubProgram_Nm'];
  } else {
    $region_glb = $_POST['Region'];
    $subprogram_glb = $_POST['subprogram'];
  }

  //DRIVERS FOR GLOBAL
  $sql_drv = "DECLARE @DRV_IDs VARCHAR(1000)
    SELECT @DRV_IDs = COALESCE(@DRV_IDs+'</BR>','')+ CAST(Driver_Nm AS VARCHAR(100))
    FROM [RI_MGT].[Driver] WHERE Driver_Key in($global_drv)
    SELECT @DRV_IDs AS Driver_Nm";
  $stmt_drv = sqlsrv_query( $data_conn, $sql_drv );
  $row_drv= sqlsrv_fetch_array( $stmt_drv, SQLSRV_FETCH_ASSOC);
  $Drivers_glb = $row_drv['Driver_Nm'];

  //PROGRAMS FOR GLOBAL
  $sql_prg = "DECLARE @PRG_IDs VARCHAR(1000)
  SELECT @PRG_IDs = COALESCE(@PRG_IDs+'</BR>','')+ CAST(Program_Nm AS VARCHAR(100))
  FROM mlm.fn_getlistofPrograms($lrpYear) WHERE Program_Key in ($global_prg)
  SELECT @PRG_IDs AS Program_Nm";
  $stmt_prg = sqlsrv_query( $data_conn, $sql_prg );
  $row_prg= sqlsrv_fetch_array( $stmt_prg, SQLSRV_FETCH_ASSOC);
  $program_glb = $row_prg['Program_Nm'];
}
?>