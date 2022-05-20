<?php 
// DECLARE
//4 update, 3 close, 2 create, 1 initialize 
//print_r($_POST);
//exit();
// Entered Values
if(!empty($_POST['status'])){
  $status = $_POST['status'];
} else {
  $status = 2; //
}
$RiskAndIssue_Key = "";
if(!empty($_POST['RiskAndIssue_Key'])){
  $RiskAndIssue_Key = $_POST['RiskAndIssue_Key'];
}

$Drivers = implode(',', $_POST['Drivers']);
  $Driversx = $Drivers;

$Drivers_confirm = implode('<br>', $_POST['Drivers']); // For Confirmation page
  $Drivers_conx = $Drivers_confirm;

$regionx = "";
if($_POST['changeLogKey']==3 || $_POST['changeLogKey']==4 || $_POST['changeLogKey']==2 ){
  if(!empty($_POST['Region'])) {
    $region = substr($_POST['Region'],0, -1);
    $regionx = $region;
    $region_confirm = substr($_POST['Region'],0, -1);
    $region_conx = $region_confirm;
    $region_conx_dsply = str_replace(",","<br>",$region_conx);
  }
} else {
  if(!empty($_POST['Region'])) {
    $region = implode(',', $_POST['Region']);
    $regionx = $region;
    $region_confirm = implode(',', $_POST['Region']);
    $region_conx = $region_confirm;
    $region_conx_dsply = str_replace(",","<br>",$region_conx);
  }
}

$userId = $_POST['userId']; // WINDOWS LOGIN NAME
$formName = $_POST['formName']; // PRJR, PRJI, PRGI, PRGR
$formType = $_POST['formType']; // NEW OR DELETE
$lrpYear = $_POST['fiscalYer']; // FISCAL YEAR OF THE PROJECT
$riTypeCode = $_POST['RIType']; // RISK OR ISSUE
$riLevel = $_POST['RILevel']; // PRJECT OR PROGRAM
$createdFrom = $_POST['CreatedFrom']; // THE RISK THE ISSUE WAS CREATED FROM - FOR ISSUE ONLY
$descriptor = str_replace("'","",$_POST['Descriptor']);  // DESCRIPTOR
$description = $_POST['Description']; 
$impactArea = $_POST['ImpactArea']; 
$impactLevel = $_POST['ImpactLevel']; 
$riskProbability = $_POST['RiskProbability'];

$assocProject = "";
if(!empty($_POST['assocProjects'])){
$assocProject = $_POST['assocProjects']; 
}
$assocProject_dsply = str_replace(",","<br>",$assocProject);

$actionPlan = $_POST['ActionPlan']; 
$responseStrategy = $_POST['ResponseStrategy']; 
$date = $_POST['date']; // FORCASTED RESOLUTION DATE

$program = "";
if(!empty($_POST['program'])){
$program = $_POST['program'];
}

$programs = "";
if(!empty($_POST['programs'])) {
$programs = $_POST['programs'];
}

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

$internalExternal = "";
  if(isset($_POST['InternalExternal'])) {
    $internalExternal = $_POST['InternalExternal']; 
    $pocFlag = 0;
  }
//END

$opportunity = "";
  if (!empty($_POST['opportunity'])){
    $opportunity = $_POST['opportunity'];
  }

$transfer2prgManager = 0;
  if(isset($_POST['TransfertoProgramManager'])) { 
        $transfer2prgManager = 1;
  }

// Hidden Values
$userId = $_POST['userId'];
$formName = $_POST['formName'];
$formType = $_POST['formType'];
$fiscalYer = $_POST['fiscalYer'];
$RIType = $_POST['RIType'];
$RILevel = $_POST['RILevel'];

$changeLogKey = $_POST['changeLogKey'];
if(!empty($DateClosed)){
$changeLogKey = 3;
}

if ($changeLogKey == 4 || $changeLogKey == 3){
  $programKeys = $_POST['programKeys'];
  $regionKeys = substr($_POST['regionKeys'],0, -1);
  $assocProjectsKeys = substr($_POST['assocProjectsKeys'],0, -1);
} else {
  $programKeys = NULL;
  $regionKeys = NULL;
  $assocProjectsKeys = $_POST['assocProjectsKeys'];
}

if ($individual == "") {
  $poc = $internalExternal;
} else {
  $poc = $individual;
}

//$name = $_POST['RIName'];
//if($changeLogKey == 2){
$name = trim(str_replace("'","",$_POST['Namex'])); // PROJECT NAME
//}

$raidLog = $_POST['raidLog'];

//echo $changeLogKey . " - ";
//echo $DateClosed;


// LOOKUP KEY VALUES 
// IMPACT AREA
$sql_imp_area = "SELECT* FROM RI_MGT.Impact_Area WHERE ImpactArea_Key = $impactArea";
$stmt_imp_area  = sqlsrv_query( $data_conn, $sql_imp_area  ); 
$row_imp_area  = sqlsrv_fetch_array( $stmt_imp_area , SQLSRV_FETCH_ASSOC);
$impactArea2 = $row_imp_area['ImpactArea_Nm'];

//IMPACT LEVEL
$sql_imp_lvl = "SELECT* FROM RI_MGT.Impact_Level WHERE ImpactLevel_Key = $impactLevel";
$stmt_imp_lvl = sqlsrv_query( $data_conn, $sql_imp_lvl );  
$row_imp_lvl = sqlsrv_fetch_array( $stmt_imp_lvl, SQLSRV_FETCH_ASSOC);
$impactLevel2 = $row_imp_lvl['ImpactLevel_Nm'];


//RESPONSE STRATIGY
$sql_resp_strg = "SELECT* FROM RI_MGT.Response_Strategy WHERE ResponseStrategy_Key = $responseStrategy";
$stmt_resp_strg = sqlsrv_query( $data_conn, $sql_resp_strg );  
$row_resp_strg = sqlsrv_fetch_array( $stmt_resp_strg, SQLSRV_FETCH_ASSOC);
$responseStrategy2 = $row_resp_strg['ResponseStrategy_Nm'];
?>