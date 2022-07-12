<?php 
//print_r($_POST);
include ("../includes/functions.php");
include ("../db_conf.php");
include ("../data/emo_data.php");
include("../sql/risk-issues-lookup.php"); 

//ASSOCIATED RISK AND ISSUES FROM KEYS
//$ri_name = $row_risk_issue['RI_Nm'];
$sql_risk_issue_assoc_proj = "select distinct RiskAndIssue_Key,PROJECT_key, Issue_Descriptor, RIDescription_Txt, RILevel_Cd, RIType_Cd, RI_Nm,ActionPlanStatus_Cd 
                              from RI_MGT.fn_GetListOfAssociatedProjectsForProjectRINm('$name',$status)
                              where RiskAndIssue_Key in($RiskAndIssue_Key)";
$stmt_risk_issue_assoc_proj = sqlsrv_query( $data_conn, $sql_risk_issue_assoc_proj );
// $row_risk_issue_assoc_proj = sqlsrv_fetch_array($stmt_risk_issue_assoc_proj, SQLSRV_FETCH_ASSOC);
// echo $row_risk_issue_assoc_proj['RI_Nm]; 			
// echo "<br>" . $sql_risk_issue_assoc_proj;
//echo $sql_risk_issue_assoc_proj;
//exit();

//GET DRIVERS FROM ID'S
$sql_risk_issue_driver = "SELECT * FROM [RI_MGT].[Driver] where Driver_Key in ($Driversx)";
$stmt_risk_issue_driver = sqlsrv_query( $data_conn, $sql_risk_issue_driver );
// $row_risk_issue_driver = sqlsrv_fetch_array($stmt_risk_issue_driver, SQLSRV_FETCH_ASSOC);
// echo $row_risk_issue_driver['Driver_Nm]; 			
//echo  "<br>" . $sql_risk_issue_driver;
//exit();

//GET ASSOCIATED PROJECTS 
$sql_ri_assoc_prj = "select distinct RiskAndIssue_Key, proj_nm from RI_MGT.fn_GetListOfAssociatedProjectsForProjectRINm('$name',1)";
$stmt_ri_assoc_prj = sqlsrv_query( $data_conn, $sql_ri_assoc_prj );
//$row_risk_issue_assoc_proj = sqlsrv_fetch_array($stmt_risk_issue_assoc_proj, SQLSRV_FETCH_ASSOC);
//$Projassoc = $row_risk_issue_assoc_proj['proj_nm']; //NEED TO SHOW ALL DRIVER LOOP

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
  <link rel="stylesheet" href="steps/style.css" type='text/css'> 

<body>
    <!-- PROGRESS BAR -->
<div class="container">       
            <div class="row bs-wizard" style="border-bottom:0;">
                
                <div class="col-xs-3 bs-wizard-step complete">
                  <div class="text-center bs-wizard-stepnum">STEP 1</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="bs-wizard-dot"></a>
                  <div class="bs-wizard-info text-center">Select Associated Projects</div>
                </div>
                
                <div class="col-xs-3 bs-wizard-step complete"><!-- complete -->
                  <div class="text-center bs-wizard-stepnum">STEP 2</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="bs-wizard-dot"></a>
                  <div class="bs-wizard-info text-center">Enter Risk or Issue Details</div>
                </div>
                
                <div class="col-xs-3 bs-wizard-step active"><!-- complete -->
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
	<div align="center"><h3>Confirm Risk or Issue</h3></div>
	<div align="center">Please review your risk or issue.  If you need to make an update, use the Edit button below.</div>
	<div style="padding: 20px" class="alert">  </div>
  <form action="update-do.php" method="post" name="confirmation" id="confirmation">

    <input name="changeLogKey" type="hidden" id="changeLogKey" value="<?php echo $changeLogKey?>"><!-- 5 delete, 4 update, 3 close, 2 create, 1 initialize -->
    <input name="userId" type="hidden" id="userId" value="<?php echo $userId ?>">
    <input name="formName" type="hidden" id="formName" value="<?php echo $formName ?>">
    <input name="formType" type="hidden" id="formType" value="<?php echo $formType ?>">
    <input name="fiscalYer" type="hidden" id="fiscalYer" value="<?php echo $fiscalYer ?>">
    <input name="RIType" type="hidden" id="RIType" value="<?php echo $RIType ?>">
    <input name="RILevel" type="hidden" id="RILevel" value="<?php echo $RILevel ?>">
    <input name="assocRegions" type="hidden" id="assocRegions" value="<?php echo $regionx ?>">
    <input name="poc" type="hidden" id="poc" value="<?php echo $poc ?>">
    <input name="pocFlag" type="hidden" id="pocFlag" value="<?php echo $pocFlag ?>">
    <input name="name" type="hidden" id="name" value="<?php echo $name ?>">
    <input name="createdFrom" type="hidden" id="createdFrom" value="<?php echo $createdFrom ?>">
    <input name="descriptor" type="hidden" id="descriptor" value="<?php echo $descriptor ?>">
    <input name="description" type="hidden" id="description" value="<?php echo $description ?>">
    <input name="drivers" type="hidden" id="drivers" value="<?php echo $Driversx ?>">
    <input name="impactArea" type="hidden" id="impactArea" value="<?php echo $impactArea ?>">
    <input name="impactLevel" type="hidden" id="impactLevel" value="<?php echo $impactLevel ?>">
    <input name="individual" type="hidden" id="individual" value="<?php echo $individual ?>">
    <input name="internalExternal" type="hidden" id="internalExternal" value="<?php echo $internalExternal ?>">
    <input name="responseStrategy" type="hidden" id="responseStrategy" value="<?php echo $responseStrategy ?>">
    <input name="date" type="hidden" id="date" value="<?php echo $date ?>">
    <input name="unknown" type="hidden" id="unknown" value="<?php echo $unknown ?>">
    <input name="transfer2prgManager" type="hidden" id="transfer2prgManager" value="<?php echo $transfer2prgManager ?>">
    <input name="opportunity" type="hidden" id="opportunity" value="<?php echo $opportunity?>">
    <input name="assocProjects" type="hidden" id="assocProjects" value="<?php while ($row_ri_assoc_prj = sqlsrv_fetch_array($stmt_ri_assoc_prj, SQLSRV_FETCH_ASSOC)) { echo $row_ri_assoc_prj['proj_nm'] . '<br>'; } ?>">
    <input name="actionPlan" type="hidden" id="actionPlan" value="<?php echo $actionPlan ?>">
    <input name="DateClosed" type="hidden" id="DateClosed" value="<?php echo $DateClosed ?>">
    <input name="RiskProbability" type="hidden" id="RiskProbability" value="<?php echo $riskProbability ?>">
    <input name="programs" type="hidden" id="programs" value="<?php echo $programs ?>">
    <input name="program" type="hidden" id="program" value="<?php echo $program ?>"> <!-- ESP PROGRAM -->
    <input name="raidLog" type="hidden" id="raidLog" value="<?php echo $raidLog ?>"> 
    <input name="assocProjectsKeys" type="hidden" id="assocProjectsKeys" value="<?php echo $assocProjectsKeys ?>"> 
    <input name="RiskAndIssue_Key" type="hidden" id="RiskAndIssue_Key" value="<?php echo $RiskAndIssue_Key ?>"> 
    <input name="regionKeys" type="hidden" id="regionKey" value="<?php echo $regionKeys ?>"> 
    <input name="programKeys" type="hidden" id="programKeys" value="<?php echo $programKeys ?>"> 
    <input name="riskRealized" type="hidden" id="riskRealized" value="<?php echo $riskRealized ?>"> 
    <input name="del_proj_select" type="hidden" id="del_proj_select" value="<?php echo $del_proj_select ?>"> 
    
	<table class="table table-bordered table-striped" width="90%">
  <thead>
    <tr>
      <th>Field</th>
      <th>Value</th>
    </tr>
</thead>
  <tbody>
      <td width="20%">Risk/Issue Name</td>
      <td><?php echo $name; ?></td>
    </tr>
    <tr>
      <td width="20%">Type</td>
      <td><?php echo $RILevel . " " . $RIType; ?></td>
    </tr>
<!--<?php if(isset($_POST['CreatedFrom'])) { ?>
    <tr>
      <td>Created From</td>
      <td><?php echo $createdFrom ; ?></td>
    </tr>
<?php } ?>-->
    <tr>
    <tr>
      <td>Descriptor</td>
      <td><?php echo $descriptor ; ?></td>
    </tr>
    <tr>
      <td>Description</td>
      <td><?php echo $description; ?></td>
    </tr>
<?php if(!empty($region_conx)){ ?>
    <tr>
      <td>Region</td>
      <td><?php echo $region_conx; ?></td>
    </tr>
<?php } ?>
    <tr>
    <tr>
      <td>Drivers</td>
      <td>
      <?php while ($row_risk_issue_driver = sqlsrv_fetch_array($stmt_risk_issue_driver, SQLSRV_FETCH_ASSOC)) { echo $row_risk_issue_driver['Driver_Nm'] . '<br>'; } ?>
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
    <tr>
      <td>Individual POC</td>
      <td><?php echo $individual; ?></td>
    </tr>
    <tr>
      <td>Team POC</td>
      <td><?php echo $internalExternal; ?></td>
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
      <td>Associated Risk/Issue</td>
      <td>
        <?php  while ($row_risk_issue_assoc_proj = sqlsrv_fetch_array($stmt_risk_issue_assoc_proj, SQLSRV_FETCH_ASSOC)) { echo $row_risk_issue_assoc_proj['RI_Nm'] . '<br>'; } ?>
        
      </td>
    </tr>
    <tr>
      <td>Action Plan</td>
      <td><?php echo $actionPlan; ?>
    </td>
    </tr>
    <tr>
      <td>Notify Portfolio Team</td>
      <td><?php echo $raidLog; ?>
    </td>
    </tr>
<!--<?php if($RIType == "Risk") { ?>
    <tr>
      <td>Risk Realized</td>
      <td><?php if($riskRealized == 0) { echo "No";} else { echo "Yes";} ?>
    </td>
    </tr>
<?php } ?> -->

    <tr>
      <td>Date Closed</td>
      <td>
        <?php 
        if($DateClosed == "NULL") {
        echo "Open";
        } else { 
        echo $DateClosed;  
        }
        ?>
    </td>
    </tr>
  </tbody>
</table>
  <div align="right">
  <button type="submit" class="btn btn-primary" name="submit2">Submit <span class="glyphicon glyphicon-step-forward"></span></button> 
  </div>
</form>
  <div align="left" style="margin-top:-33px;">
  <a href="javascript:history.back()"  class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span> Edit </a>
  </div>
<?php
    //print_r($_POST);

?>
</body>
</html>