<?php include ("../../includes/functions.php");?>
<?php include ("../../db_conf.php");?>
<?php include ("../../data/emo_data.php");?>
<?php include ("../../sql/RI_Internal_External.php");?>
<?php include ("../../sql/update-time.php");?>
<?php 
//$action = $_GET['action'];
$user_id = preg_replace("/^.+\\\\/", "", $_SERVER["AUTH_USER"]);
$ri_id = $_GET['id'];
echo $ri_id;

//GET GLOBAL PROGRAM BY ID
$sql_glb_prog = "SELECT* FROM [RI_MGT].[fn_GetListOfAllRiskAndIssue](1) WHERE RiskAndIssue_Key = $ri_id";
$stmt_glb_prog   = sqlsrv_query( $data_conn, $sql_glb_prog ); 
$row_glb_prog   = sqlsrv_fetch_array( $stmt_glb_prog , SQLSRV_FETCH_ASSOC);
// $row_glb_prog [''];

$RI_Nm = $row_glb_prog ['RI_Nm'];
//$RILevel_Cd = $row_glb_prog ['RILevel_Cd'];
$RILevel_Cd = "nt 2.0"; //STATIC. NEEDS TO BE INCLUDED IN FUNCTION
$RIType_Cd = $row_glb_prog ['RIType_Cd'];
$MLMProgram_Nm = $row_glb_prog ['MLMProgram_Nm'];
$Fiscal_Year = $row_glb_prog ['Fiscal_Year'];
$ScopeDescriptor_Txt = $row_glb_prog ['ScopeDescriptor_Txt '];
$SubProgram = "CB Funding for Growth";
$MLMRegion_Key = $row_glb_prog ['MLMRegion_Key'];
$RIDescription_Txt = $row_glb_prog ['RIDescription_Txt'];


//PROGRAM
$sql_prog = "select * from mlm.fn_getlistofPrograms(2022)";
$stmt_prog   = sqlsrv_query( $data_conn, $sql_prog ); 
//$row_prog   = sqlsrv_fetch_array( $stmt_prog , SQLSRV_FETCH_ASSOC);
// $row_prog ['Program_Nm'];

//SUBPROGRAM 
//Needs to be limited according to the program selection. replce -1 with Program ID 8.17.2022
$sql_subprog = "select * from mlm.fn_getlistofsubprogramforprogram(-1)";
$stmt_subprog   = sqlsrv_query( $data_conn, $sql_subprog ); 
//$row_subprog   = sqlsrv_fetch_array( $stmt_subprog , SQLSRV_FETCH_ASSOC);
// $row_subprog ['SubProgram_Nm'];
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Carolino, Gil">
    <title>Global Program Risk/Issue</title>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="../../colorbox-master/jquery.colorbox.js"></script>
  <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
  <link rel="stylesheet" href="../steps/style.css" type='text/css'> 
  <link rel="stylesheet" href="../includes/ri-styles.css" />
  <link rel="stylesheet" href="../../colorbox-master/example1/colorbox.css" />
  
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
  checkboxes = document.getElementsByName('Region[]');
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
  }
}
</script>
<script language="javascript">
	$(document).ready(function() {
    $('#subprogram').multiselect({
          includeSelectAllOption: true,
        });
  });
</script>

</head>
<body style=" font-family:Mulish, serif;">
<?php include ("../../includes/menu.php");?>
<main align="center">
  <!-- PROGRESS BAR -->
  <div class="container">       
            <div class="row bs-wizard" style="border-bottom:0;">
                <div class="col-xs-4 bs-wizard-step active"><!-- complete -->
                  <div class="text-center bs-wizard-stepnum">STEP 1</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="bs-wizard-dot"></a>
                  <div class="bs-wizard-info text-center">Enter Risk or Issue Details</div>
                </div>
                
                <div class="col-xs-4 bs-wizard-step disabled"><!-- complete -->
                  <div class="text-center bs-wizard-stepnum">STEP 2</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="bs-wizard-dot"></a>
                  <div class="bs-wizard-info text-center">Confirm Your Entry</div>
                </div>
                
                <div class="col-xs-4 bs-wizard-step disabled"><!-- active -->
                  <div class="text-center bs-wizard-stepnum">STEP 3</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="bs-wizard-dot"></a>
                  <div class="bs-wizard-info text-center">Completed</div>
                </div>
            </div>
  </div>
  <!-- END PROGRESS BAR -->
<div align="center">
  <h3>GLOBAL PROGRAM RISK OR ISSUE</h3>
  Enter the details of your Program Risk/Issue
</div>

<div style="padding: 20px;">
  <form action="../confirm.php" method="post" id="programRisk" oninput="Namex.value = program.value + ' ' + Region.value + ' ' + Descriptor.value + ' POR' + fiscalYer.value.slice(2)">
  <input name="changeLogKey" type="hidden" id="changeLogKey" value="2">
  <input name="userId" type="hidden" id="userId " value="<?php echo $user_id ?>">
  <input name="formName" type="hidden" id="formName" value="PRGR"> <!--this needs to be prgi or prgr-->
  <input name="formType" type="hidden" id="formType" value="Update">
  <input name="CreatedFrom" type="hidden" id="Created From" value="">
  <input name="TransfertoProgramManager" type="hidden" id="Created From" value="0">
  <input name="RIName" type="hidden" id="RIName" value="">
  <input name="assocProjectsKeys" type="hidden" id="assocProjectsKeys" value="">
  <input name="CreatedFrom" type="hidden" class="form-control" id="CreatedFrom" value="">
  <input name="DateClosed" type="hidden" id="DateClosed" value="">
  <input name="global" type="hidden" id="global" value="1">

  <div class="container">
  <!--ROW 1 -->
  <div class="row row-eq-height">
    <div class="col-md-4" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">RISK/ISSUE LEVEL</h3>
        </div>
        <div class="panel-body">
          <label for="RILevel"><input type="radio" name="RILevel" value="Program" required <?php if($RILevel_Cd == "program") { echo 'checked';} ?>> Program </label> 
          <label for="RILevel"><input type="radio" name="RILevel" value="Portfolio" required disabled> Portfolio </label>
        </div>
      </div>
    </div>
    <div class="col-md-4" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">PORTFOLIO TYPE</h3>
        </div>
        <div class="panel-body">
        <label for="portfolioType"><input type="radio" name="portfolioType" value="nt 2.0" required> NT 2.0 </label> 
        <label for="portfolioType1"><input type="radio" name="portfolioType" value="bau" required> BAU </label>
        </div>
    </div>
    </div>
    <div class="col-md-4" align="left">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">RISK/ISSUE TYPE</h3>
      </div>
      <div class="panel-body">
      <label for="RIType"><input type="radio" name="RIType" value="Risk" required> Risk </label> 
      <label for="RIType"><input type="radio" name="RIType" value="Issue" required> Issue </label>
      </div>
    </div>
    </div>
  </div>
  <!--ROW 2 -->
  <div class="row row-eq-height">
    <div class="col-md-4" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title">NAME (Autofill)</h4>
        </div>
        <div class="panel-body">
        <input name="Namex" type="text" readonly required="required" class="form-control" id="Namex" value="<?php echo $RI_Nm ?>>" >
        </div>
      </div>
    </div>
    <div class="col-md-4" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">PROGRAM</h3>
        </div>
        <div class="panel-body">
          <select name="program" id="program" class="form-control">
            <?php while($row_prog = sqlsrv_fetch_array( $stmt_prog , SQLSRV_FETCH_ASSOC)) { ?>
            <option value="<?php echo $row_prog ['Program_Nm']; ?>"><?php echo $row_prog ['Program_Nm']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
    </div>
    <div class="col-md-4" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">LRP YEAR</h3>
        </div>
        <div class="panel-body">
          <select name="fiscalYer" id="fiscalYer" class="form-control">
            <option value="2022" <?php if(date('Y') == "2022") { echo " selected ";} ?>>2022</option>
            <option value="2023" <?php if(date('Y') == "2023") { echo " selected ";} ?>>2023</option>
          </select>
        </div>
      </div>
    </div>
  </div>
  <!--ROW 3 -->
  <div class="row row-eq-height">
    <div class="col-md-4" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">RISK DESCRIPTOR</h3>
        </div>
        <div class="panel-body">
          <input name="Descriptor" type="text" required="required" class="form-control" id="Descriptor" maxlength="30" onChange="updatebox()"> 
        </div>
      </div>
    </div>
    <div class="col-md-4 " align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">SUBPROGRAM (Limit to Program)</h3>
        </div>
        <div class="panel-body">
          <select name="subprogram[]" id="subprogram" class="form-control" multiple="multiple" required>
            <?php while($row_subprog = sqlsrv_fetch_array( $stmt_subprog , SQLSRV_FETCH_ASSOC)) { ?>
              <option value="<?php echo $row_subprog ['SubProgram_Key']; ?>"><?php echo $row_subprog ['SubProgram_Nm']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
    </div>
    <div class="col-md-4" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">REGION (Concat to Name, Require)</h3>
        </div>
        <div class="panel-body">
          <table width="100%">
          <tr>
            <td><label for="California"><input type="checkbox" id="Region" name="Region[]" value="California"> California </label> </td>
            <td><label for="Central"><input type="checkbox" name="Region[]" value="Central"> Central </label></td>
            <td><label for="Corporate"><input type="checkbox" name="Region[]" value="Corporate"> Corporate </label></td>
            <td><label for="Northeast"><input type="checkbox" name="Region[]" value="Northeast"> Northeast </label></td>
          </tr>
          <tr>
            <td><label for="Southeast"><input type="checkbox" name="Region[]" value="Southeast"> Southeast </label> </td>
            <td><label for="Southwest"><input type="checkbox" name="Region[]" value="Southwest"> Southwest </label></td>
            <td><label for="Virginia"><input type="checkbox" name="Region[]" value="Virginia"> Virginia </label></td>
            <td><label for="All"><input type="checkbox" name="Region[]" value="All" onClick="toggle(this)"> All </label></td>
          </tr>
        </table>
        </div>
      </div>
    </div>
  </div>
  <!-- ROW 4 -->
  <div class="row row-eq-height">
    <div class="col-md-6" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">DESCRIPTION</h3>
        </div>
        <div class="panel-body">
        <textarea name="Description" cols="120" rows="6" required="required" class="form-control" id="Description"></textarea>
        </div>
      </div>      
    </div>
    <div class="col-md-6" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">DRIVERS</h3>
        </div>
        <div class="panel-body">
          <table width="100%" border="0">
                  <tr>
                    <td width="50%"><label>
                      <input type="radio" name="Drivers[]" value="Material Delay"  id="Drivers_0" class="required_group" required>
                      Material Delay</label></td>
                      <td><label>
                      <input type="radio" name="Drivers[]" value="Project Dependency" id="Drivers_1" class="required_group" required>
                      Project Dependency</label></td>
                  </tr>
                  <tr>
                      <td width="49%"><label>
                      <input type="radio" name="Drivers[]" value="Shipping/Receiving Delay" id="Drivers_10" class="required_group" required>
                      Shipping/Receiving Delay</label></td>
                    <td><label>
                      <input type="radio" name="Drivers[]" value="Budget/Funding" id="Drivers_6" class="required_group" required>
                      Budget/Funding</label></td>
                  </tr>
                  <tr>
                    <td><label>
                      <input type="radio" name="Drivers[]" value="Ordering Error" id="Drivers_2" class="required_group" required>
                      Ordering Error</label></td>
                      <td><label>
                      <input type="radio" name="Drivers[]" value="Design/Scope Change" id="Drivers_7" class="required_group" required>
                      Design/Scope Change</label></td>
                  </tr>
                  <tr>
                    <td><label>
                      <input type="radio" name="Drivers[]" value="People Resource" id="Drivers_3" class="required_group" required>
                      People Resource</label></td>
                    <td><label title="">
                      <input type="radio" name="Drivers[]" value="Admin Error" id="Drivers_8" class="required_group" required>
                      Admin Error</label></td>
                    </tr>
                  <tr>
                    <td><label title="">
                      <input type="radio" name="Drivers[]" value="3PL Resource" id="Drivers_4" class="required_group" required>
                      3PL Resource</label></td>
                    <td><label title="">
                      <input type="radio" name="Drivers[]" value="External Forces" id="Drivers_9" class="required_group" required>
                      External Forces</label></td>
                  </tr>
        </table>
        </div>
      </div>      
    </div>
  </div>
  <!--ROW 5 | IMPACT -->
  <div class="row row-eq-height">
    <div class="col-md-12" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">IMPACT</h3>
        </div>
        <div class="panel-body">
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
                  <strong>Impacted Area * </strong>
                  <a href="../includes/definitions.php?tooltipkey=IMPA" class="dno"></a>
                  </tr>
                  <?php while($row_impArea= sqlsrv_fetch_array( $stmt_impArea , SQLSRV_FETCH_ASSOC)) { ?>
                    <tr>
                    <td><label>
                      <input type="radio" name="ImpactArea" value="<?php echo $row_impArea['ImpactArea_Key'] ?>" id="ImpactArea_<?php echo $row_impArea['ImpactArea_Key'] ?>" required>
                      <?php echo $row_impArea['ImpactArea_Nm'] ?></label></td>
                    </tr>
                  <?php } ?>
                  </table></td>
                <td valign="top">
                  <table width="200" border="0">
                    <tr>
                      <strong>Impact Level * </strong>
                      <a href="../includes/definitions.php?tooltipkey=IMPL" class="dno"></a>
                    </tr>
                    <?php while($row_imLevel = sqlsrv_fetch_array( $stmt_imLevel , SQLSRV_FETCH_ASSOC)) { ?>
                    <tr>
                      <td><label>
                        <input name="ImpactLevel" type="radio" id="ImpactLevel_<?php echo $row_imLevel['ImpactLevel_Key'] ?>" value="<?php echo $row_imLevel['ImpactLevel_Key'] ?>" required>
                        <?php echo $row_imLevel['ImpactLevel_Nm'] ?></label></td>
                      </tr>
                    <?php } ?>                    
                    </table>
                  </td>
                <td valign="top">
				<div id="myDIV2">
                    <table width="200" border="0">
                        <tr>
                          <td>
                            <strong>Risk Probability Score *</strong>
                          </td>
                        <?php while($row_probability= sqlsrv_fetch_array( $stmt_probability , SQLSRV_FETCH_ASSOC)) { ?>
                        <tr>
                        <td><label>
                          <input name="RiskProbability" type="radio" id="RiskProbability_<?php echo $row_probability['RiskProbability_Key'] ?>" value="<?php echo $row_probability['RiskProbability_Key'] ?>" required>
                          <?php echo $row_probability['RiskProbability_Nm'] ?></label></td>
                        </tr>
                        <?php } ?>
                    </table>
                </div>
            </td>
            <td>
            </td>
          </tr>
        </tbody>
      </table>
        </div>
      </div>
    </div>
  </div>
  <!--ROW 6 | POC | FORCAST DATE | RESPONSE STRATIGY -->
  <div class="row row-eq-height">
    <div class="col-md-4" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">CURRENT TASK POC (Send Dept. Key)</h3>
        </div>
        <div class="panel-body">
          <label for="Individual">Individual POC *<br></label>
            <input type="text" list="Individual" name="Individual" class="form-control" id="indy" required/>
              <datalist id="Individual">
                <?php while($row_internal  = sqlsrv_fetch_array( $stmt_internal , SQLSRV_FETCH_ASSOC)) { ?>
                  <option value="<?php echo $row_internal['POC_Nm'] . " : " . $row_internal['POC_Department'] ;?>"><span style="font-size:8px;"> <?php echo $row_internal['POC_Department'];?></span>
                <?php } ?>
              </datalist>
          <label for="Individual3">Team/Group POC *<br></label>
            <input type="text" name="InternalExternal" class="form-control" id="InternalExternal" onclick="myFunction()" required/>
        </div>
      </div>
    </div>
    <div class="col-md-4" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">FORCASTED RESOLUTION DATE</h3>
        </div>
        <div class="panel-body">
          <div id="dateUnknown">
                <input name="date" 
                    type="date"
                    min="<?php echo $closeDateMax ?>"
                    class="form-control" 
                    id="date" 
                    value="2022-01-01"
                    onChange="forCastedX()"  
                    oninvalid="this.setCustomValidity('You must select a date or check Unknown ')"
                    oninput="this.setCustomValidity('')">
          </div>
        <hr>
          <div id="forcastedDate">
                <input type="checkbox" 
                    name="Unknown" 
                    id="Unknown" 
                    onChange="unKnownX()">
                <label for="Unknown">Unknown</label> - Overrides Resolution Date
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">RESPONSE STRATIGY</h3>
        </div>
        <div class="panel-body">
          <table width="246" border="0" cellpadding="5" cellspacing="5">
                <tr>
                  <td>&nbsp;</td>
                  <td><label>
                    <input type="radio" name="ResponseStrategy" value="1" id="Response_Strategy_0" required>
                    Avoid</label></td>
                  </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td><label>
                    <input type="radio" name="ResponseStrategy" value="2" id="Response_Strategy_1" required>
                    Mitigate</label></td>
                  </tr>
                <tr>
                  <td width="16">&nbsp;</td>
                  <td width="195"><label>
                    <input type="radio" name="ResponseStrategy" value="3" id="Response_Strategy_2" required>
                    Transfer</label></td>
                  </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td><label>
                    <input type="radio" name="ResponseStrategy" value="4" id="Response_Strategy_3" required>
                    Accept</label></td>
                  </tr>
            </table>
        </div>
      </div>
    </div>
  </div>
  <!--ROW 7 - ACTION PLAN-->
  <div class="row row-eq-height">
    <div class="col-md-12" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">ACTION PLAN</h3>
        </div>
        <div class="panel-body">
        <textarea name="ActionPlan" cols="120" required="required" class="form-control" id="ActionPlan"></textarea>
        </div>
      </div>
    </div>
  </div>
  <!--ROW 8 - RISK REALIZED | ASSOC CR | NOTIFY PORT TEAM-->
  <div class="row row-eq-height">
    <div class="col-md-4" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">RISK REALIZED</h3>
        </div>
        <div class="panel-body">
          <table width="50%" border="0">
            <tr>
              <td><label>
                  <input type="radio" name="riskRealized" value="Yes" id="RiskRelized_0" checked>
                  Yes</label></td>
              <td><label>
                  <input type="radio" name="riskRealized" value="No" id="RiskRelized_1">
                  No</label></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <div class="col-md-4" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">ASSOCIATED CR ID</h3>
        </div>
        <div class="panel-body">
          <input name="assCRID" type="text" class="form-control" id="assCRID" maxlength="10">
        </div>
      </div>       
    </div>
    <div class="col-md-4" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">NOTIFY PORTFOLIO TEAM</h3>
        </div>
        <div class="panel-body">
          <table width="50%" border="0">
            <tr>
              <td><label><input type="radio" name="raidLog" value="Yes" id="raid_0" checked> Yes </label>
              </td>
            <td>
              <label><input type="radio" name="raidLog" value="No" id="raid_1"> No </label>
            </td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<!--end container -->
  <button class="btn btn-primary" onclick="myConfirmation()"><span class="glyphicon glyphicon-step-backward"></span> Back </button>
  <button type="submit" class="btn btn-primary" onmouseover="Namex.value = program.value + ' ' + Region.value + ' ' + Descriptor.value + ' POR' + fiscalYer.value.slice(2)">Review <span class="glyphicon glyphicon-step-forward"></span></button>
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

  var today = year + "-" + month + "-" + day;

  document.getElementById('date').value = today;
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