<?php 
include ("../includes/functions.php");
include ("../db_conf.php");
include ("../data/emo_data.php");
include ("../sql/risk-issues-lookup.php");
include ("../sql/update-time.php");
//echo str_replace('  ', '&nbsp; ', nl2br(print_r($_POST, true)));
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Confirm Risk/Issue</title>
</head>
	
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"> 
  <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
  <link rel="stylesheet" href="steps/style.css" type='text/css'> 

<body style="font-family:Mulish, serif;">
<?php if($global == 1) { include ("../includes/menu.php"); }?>
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
	<div align="center"><h2>CONFIRM <?= strtoupper($RILevel)  . " " . strtoupper($RIType); ?></h2></div>
	<div align="center">Please review your risk or issue.  If you need to make an update, use the Edit button below.</div>
	<div style="padding: 20px" class="alert">  </div>
  <form action="confirm-do.php" method="post" name="confirmation" id="confirmation">
    <input name="changeLogKey" type="hidden" id="changeLogKey " value="<?= $changeLogKey ?>">
    <input name="userId" type="hidden" id="userId " value="<?= $userId ?>">
    <input name="formName" type="hidden" id="formName" value="<?= $formName ?>">
    <input name="formType" type="hidden" id="formType" value="<?= $formType ?>">
    <input name="fiscalYer" type="hidden" id="fiscalYer" value="<?= $fiscalYer ?>">
    <input name="RIType" type="hidden" id="RIType" value="<?= $RIType ?>">
    <input name="RILevel" type="hidden" id="RILevel" value="<?= $RILevel ?>">
    <input name="assocRegions" type="hidden" id="assocRegions" value="<?= $regionx ?>">
    <input name="poc" type="hidden" id="poc" value="<?= $poc ?>">
    <input name="pocFlag" type="hidden" id="pocFlag" value="<?= $pocFlag ?>">
    <input name="name" type="hidden" id="name" value="<?= $name ?>">
    <input name="createdFrom" type="hidden" id="createdFrom" value="<?= $createdFrom ?>">
    <input name="descriptor" type="hidden" id="descriptor" value="<?= $descriptor ?>">
    <input name="description" type="hidden" id="description" value="<?= str_replace(["'", '"'], ['&#39;', '&quot;'], $description); ?>">
    <input name="drivers" type="hidden" id="drivers" value="<?= $Driversx ?>">
    <input name="impactArea" type="hidden" id="impactArea" value="<?= $impactArea ?>">
    <input name="impactLevel" type="hidden" id="impactLevel" value="<?= $impactLevel ?>">
    <input name="individual" type="hidden" id="individual" value="<?= $individual ?>">
    <input name="internalExternal" type="hidden" id="internalExternal" value="<?= $internalExternal ?>">
    <input name="responseStrategy" type="hidden" id="responseStrategy" value="<?= $responseStrategy ?>">
    <input name="date" type="hidden" id="date" value="<?= $date ?>">
    <input name="unknown" type="hidden" id="unknown" value="<?= $unknown ?>">
    <input name="transfer2prgManager" type="hidden" id="transfer2prgManager" value="<?= $transfer2prgManager ?>">
    <input name="opportunity" type="hidden" id="opportunity" value="<?= $opportunity?>">
    <input name="assocProjects" type="hidden" id="assocProjects" value="<?= $assocProject ?>">
    <input name="actionPlan" type="hidden" id="actionPlan" value="<?= str_replace(["'", '"'], ['&#39;', '&quot;'], $actionPlan); ?>">
    <input name="DateClosed" type="hidden" id="DateClosed" value="<?= $DateClosed ?>">
    <input name="RiskProbability" type="hidden" id="RiskProbability" value="<?= $riskProbability ?>">
    <input name="programs" type="hidden" id="programs" value="<?= $programs ?>">
    <input name="program" type="hidden" id="program" value="<?= $program ?>"> <!-- ESP PROGRAM -->
    <input name="raidLog" type="hidden" id="raidLog" value="<?= $raidLog ?>">
    <input name="riskRealized" type="hidden" id="riskRealized" value="<?= $riskRealized ?>">
    <input name="groupID" type="hidden" id="groupID" value="<?= $groupID ?>">
    <input name="assCRID" type="hidden" id="assCRID" value="<?= $assCRID?>"> 
    <input name="changeLogActionVal" type="hidden" id="changeLogActionVal" value="<?= $changeLogActionVal?>"> 
    <input name="changeLogReason" type="hidden" id="changeLogReason" value="<?= $changeLogReason?>"> 
    <input name="PRJILog_Flg" type="hidden" id="PRJILog_Flg" value="<?= $PRJILog_Flg ?>">
<!-- new for global portfolio/program-->
    <input name="portfolioType" type="hidden" id="portfolioType" value="<?= $portfolioType?>"> 
    <input name="subprogram" type="hidden" id="subprogram" value="<?= $subprogram?>"> 
    <input name="global" type="hidden" id="global" value="<?= $global?>">
    <input name="EstMigrateDate" type="hidden" id="EstMigrateDate" value="<?= $EstMigrateDate?>">
    <input name="EstActiveDate" type="hidden" id="EstActiveDate" value="<?= $EstActiveDate?>">
    <input name="regionKeys" type="hidden" id="regionKeys" value="<?= $regionKeys?>">
    <input name="tags" type="hidden" id="tags" value="<?= $tags ?>">

<?php if($messaging  == "update") { ?>
  <div class="alert alert-success">
    <div align="left">
      <span class="glyphicon glyphicon-warning-sign"></span> 
      You are about to add the following project(s) to this <?= $RILevel . " " . $RIType; ?>.  If you need to edit this list, please use the edit button.
    </div>
<hr>
    <div>
      <?= $assocProject_dsply; ?>
    </div>
  </div>
<?php } ?>

	<table class="table table-bordered table-striped" width="90%">
  <thead>
    <tr>
      <th>Field</th>
      <th>Value</th>
    </tr>
</thead>
  <tbody>
    <tr>
      <td width="20%">Risk/Issue Name</td>
      <td><?=  $name; ?></td>
    </tr>
    <tr>
      <td width="20%">Type</td>
      <td><?= ucfirst($RILevel) . " " . ucfirst($RIType); ?></td>
    </tr>
<?php if($portfolioType != "") { ?>
    <tr>
      <td>Portfolio</td>
      <td><?= $portfolio_Nm; ?></td>
    </tr>
<?php } ?>
<?php if(!empty($_POST['assCRID'])) { ?>
    <tr>
      <td>Associated CR ID</td>
      <td><?= $assCRID; ?></td>
    </tr>
<?php } ?>

<?php 
  if($program != "") { ?>
    <tr>
      <td>Program</td>
      <td><?= $program ; ?></td>
    </tr>
<?php } ?>

<?php // if($programs != "") { ?>
  <!--<tr>
      <td>Program</td>
      <td><?= $programs ; ?></td>
    </tr> -->
<?php //} ?>

<?php if(!empty($subprogram) && $global != 1) { ?>
    <tr>
      <td>Subprogram</td>
      <td><?= $subprogram ; ?></td>
    </tr>
<?php } ?>
<?php if($global == 1 && $RILevel != "Portfolio") { ?>
    <tr>
      <td>Subprogram</td>
      <td><?= $subprogram_glb ; ?></td>
    </tr>
<?php } ?>

    <tr>
      <td>Descriptor</td>
      <td><?= $descriptor ; ?></td>
    </tr>
    <tr>
      <td>Description</td>
      <td><?= $description; ?></td>
    </tr>
<?php if(!empty($region_conx)){ ?>
    <tr>
      <td>Region</td>
      <td><?= $region_conx_dsply; ?></td>
    </tr>
<?php } ?>
    <tr>  
    <tr>
      <td>Drivers</td>
      <td><?= $Drivers_conx; ?></td>
    </tr>
    <tr>
      <td>Impact Area</td>
      <td><?= $impactArea2; ?></td>
    </tr>
    <tr>
      <td>Impact Level</td>
      <td><?= $impactLevel2; ?></td>
    </tr>
<?php if(!empty($riskProbability)) { ?>
    <tr>
      <td>Risk Probability</td>
      <td><?= $riskProbability2; ?></td>
    </tr>
<?php } ?>
<!--
    <tr>
      <td>Individual POC</td>
      <td><?= $individual; ?></td>
    </tr>
    <tr>
      <td>POC Team</td>
      <td><?= $internalExternal; ?></td>
    </tr>
-->
    <tr>
      <td>Response Strategy</td>
      <td><?= $responseStrategy2; ?></td>
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
<?php if($global != 0 || $RILevel != 'Program') { ?>
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
<?php } ?>
<?php if(isset($_POST['opportunity'])) { ?>
    <tr>
      <td>Opportunity</td>
      <td><?php $_POST['opportunity']; ?>
    </td>
    </tr>
<?php } ?>
<?php if(!isset($_POST['global'])) { ?>
    <tr>
      <td>Associated Projects</td>
      <td><?= $assocProject_dsply; ?></td>
    </tr>
    <?php } ?>
    <tr>
      <td>Action Plan</td>
      <td><?= $actionPlan; ?></td>
    </tr>
<?php if(!empty($_POST['changeLogAction'])) { ?>
    <tr>
      <td>Change Log Action</td>
      <td><?php if(!empty($changeLogAction)) {echo $changeLogAction;} ?></td>
    </tr>
    <tr>
      <td>Change Log Reason</td>
      <td><?= $changeLogReason; ?></td>
    </tr>
<?php } ?>
<?php if(!is_null($EstActiveDate)) { ?>
    <tr>
      <td>Est. Activation Date</td>
      <td><?= $EstActiveDate?></td>
    </tr>
    <tr>
      <td>Est. Migration Date</td>
      <td><?= $EstMigrateDate?></td>
    </tr>
<?php } ?>
<?php //if($RILevel == "Program") { ?>
    <!--<tr>
      <td>Notify Portfolio Team</td>
      <td><?= $raidLog; ?></td>
    </tr>-->
<?php //} ?>
<?php if($RIType == "Risk") { ?>
    <tr>
      <td>Risk Realized</td>
      <td><?php if($riskRealized == 0) { echo "No";} else { echo "Yes";} ?>
    </td>
    </tr>
<?php } ?>
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
<?php if(!empty($tags)){?>
    <tr>
      <td>Tags</td>
      <td>
        <?php echo $tags; ?>
    </td>
    </tr>
<?php } ?>
  </tbody>
</table>
<div align="right">
  <button type="submit" class="btn btn-primary" name="submit2">Submit <span class="glyphicon glyphicon-step-forward"></span></button> 
  </div>
</form>
  <div align="left" style="margin-top:-33px;">
  <a href="javascript:history.back()"  class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span> Edit </a>
  </div>
</div>
<?php
    //print_r($_POST);
?>
</body>
</html>