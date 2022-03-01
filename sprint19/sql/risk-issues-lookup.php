<?php 
// DATA CONNECTION
$server = '$conn_COX_QA'; // QA
//$server = '$conn_COXProd'; //PROD
//$server = '$conn'; //DEV

// DECLARE

// Entered Values
$Drivers = implode(',', $_POST['Drivers']);
  $Driversx = $Drivers;

$regionx = "";
  if(!empty($_POST['Region'])) {
$region = implode(',', $_POST['Region']);
  $regionx = $region;
  }
$userId = $_POST['userId']; // WINDOWS LOGIN NAME
$formName = $_POST['formName']; // PRJR, PRJI, PRGI, PRGR
$formType = $_POST['formType']; // NEW OR DELETE
$lrpYear = $_POST['fiscalYer']; // FISCAL YEAR OF THE PROJECT
$riTypeCode = $_POST['RIType']; // RISK OR ISSUE
$riLevel = $_POST['RILevel']; // PRJECT OR PROGRAM
$name = trim($_POST['Namex']); // PROJECT NAME
$createdFrom = $_POST['CreatedFrom']; // THE RISK THE ISSUE WAS CREATED FROM - FOR ISSUE ONLY
$descriptor = $_POST['Descriptor'];  // DESCRIPTOR
$description = $_POST['Description']; 
$impactArea = $_POST['ImpactArea']; 
$impactLevel = $_POST['ImpactLevel']; 
$riskProbability = $_POST['RiskProbability'];
$assocProject = $_POST['assocProjects']; 
$actionPlan = $_POST['ActionPlan']; 
$responseStrategy = $_POST['ResponseStrategy']; 
$date = $_POST['date']; // FORCASTED RESOLUTION DATE

$programs = "";
if(!empty($_POST['programs'])) {
$programs = $_POST['programs'];
}

$dateClosed = 'NULL';
if (!empty($_POST['DateClosed'])) {
$dateClosed = $_POST['DateClosed']; 
}

$unknown = 'off';
  if(!empty($_POST['Unknown'])) {
  $unknown = $_POST['Unknown'];
  }

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

if ($individual == "") {
  $poc = $internalExternal;
} else {
  $poc = $individual;
}

// LOOKUP KEY VALUES 
// IMPACT AREA
$sql_imp_area = "SELECT* FROM RI_MGT.Impact_Area WHERE ImpactArea_Key = $impactArea";
$stmt_imp_area  = sqlsrv_query( $conn_COX_QA, $sql_imp_area  ); 
$row_imp_area  = sqlsrv_fetch_array( $stmt_imp_area , SQLSRV_FETCH_ASSOC);
$impactArea2 = $row_imp_area['ImpactArea_Nm'];

//IMPACT LEVEL
$sql_imp_lvl = "SELECT* FROM RI_MGT.Impact_Level WHERE ImpactLevel_Key = $impactLevel";
$stmt_imp_lvl = sqlsrv_query( $conn_COX_QA, $sql_imp_lvl );  
$row_imp_lvl = sqlsrv_fetch_array( $stmt_imp_lvl, SQLSRV_FETCH_ASSOC);
$impactLevel2 = $row_imp_lvl['ImpactLevel_Nm'];


//RESPONSE STRATIGY
$sql_resp_strg = "SELECT* FROM RI_MGT.Response_Strategy WHERE ResponseStrategy_Key = $responseStrategy";
$stmt_resp_strg = sqlsrv_query( $conn_COX_QA, $sql_resp_strg );  
$row_resp_strg = sqlsrv_fetch_array( $stmt_resp_strg, SQLSRV_FETCH_ASSOC);
$responseStrategy2 = $row_resp_strg['ResponseStrategy_Nm'];
?>