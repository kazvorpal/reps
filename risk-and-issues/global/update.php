<?php include ("../../includes/functions.php");?>
<?php include ("../../db_conf.php");?>
<?php include ("../../data/emo_data.php");?>
<?php include ("../../sql/RI_Internal_External.php");?>
<?php include ("../../sql/update-time.php");?>
<?php 
//$action = $_GET['action'];
$user_id = preg_replace("/^.+\\\\/", "", $_SERVER["AUTH_USER"]);
$ri_id = $_GET['id'];

//GET GLOBAL PROGRAM BY ID
$sql_glb_prog = "SELECT* FROM [RI_MGT].[fn_GetListOfAllRiskAndIssue](1) WHERE RiskAndIssue_Key = $ri_id";
$stmt_glb_prog   = sqlsrv_query( $data_conn, $sql_glb_prog ); 
$row_glb_prog   = sqlsrv_fetch_array( $stmt_glb_prog , SQLSRV_FETCH_ASSOC);
// $row_glb_prog[''];
//echo $sql_glb_prog;

if(empty($row_glb_prog)){
  echo "No Risk/issue found.";
  exit();
}

//DELARE
$RiskAndIssueLog_Key = $row_glb_prog['RiskAndIssueLog_Key'];

$RILevel_Cd = $row_glb_prog['RILevel_Cd']; 
$RIPortfolio_Key = $row_glb_prog['PortfolioType_Key']; 
$RIType_Cd = $row_glb_prog['RIType_Cd'];

$RI_Nm = $row_glb_prog['RI_Nm'];
$MLMProgram_Nm = $row_glb_prog['MLMProgram_Nm'];
$Fiscal_Year = $row_glb_prog['Fiscal_Year'];

$ScopeDescriptor_Txt = $row_glb_prog['ScopeDescriptor_Txt'];
//$SubProgram = "CB Funding For Growth"; // GET FROM FUNCTION
//$MLMRegion_Key = $row_glb_prog['MLMRegion_Key'];// NEED THE REGIONS - MULTIPLE KEYS

$ImpactLevel_Key = $row_glb_prog['ImpactLevel_Key'];
$ImpactArea_Key = $row_glb_prog['ImpactArea_Key'];
$RiskProbability_Key = $row_glb_prog['RiskProbability_Key'];

$RIDescription_Txt = $row_glb_prog['RIDescription_Txt'];
$Drivers = "";//NEED THE DRIVERS

$POC_Nm = $row_glb_prog['POC_Nm'];
$POC_Department = $row_glb_prog['POC_Department'];
$ForecastedResolution_Dt = $row_glb_prog['ForecastedResolution_Dt']; //echo $ForecastedResolution_Dt;
$transfer2prgManager = $row_glb_prog['TransferredPM_Flg']; 
$ResponseStrategy_Cd = $row_glb_prog['ResponseStrategy_Cd'];

$ActionPlanStatus_Cd = $row_glb_prog['ActionPlanStatus_Cd'];
$actionPlan = "";
$actionPlan_b = $row_glb_prog['ActionPlanStatus_Cd'];

$RiskRealized_Flg =  $row_glb_prog['RiskRealized_Flg'];
$AssociatedCR_Key = $row_glb_prog['AssociatedCR_Key'];
$RaidLog_Flg = $row_glb_prog['RaidLog_Flg'];

$global = 1;

//MAX AND MIN FOR CLOSING DATE
$createDT = date_format($row_glb_prog['Created_Ts'],'Y-m-d');

if(!empty($row_glb_prog['ForecastedResolution_Dt'])) {
  $forecastMin = date_format($ForecastedResolution_Dt, "Y-m-d");
} else {
  $forecastMin = $closeDateMax;
}

if($RILevel_Cd == "Program") {
  //PROGRAM FOR PROGRAM RI (SINGLE PRGRAM)
  $sql_prog = "select * from mlm.fn_getlistofPrograms($Fiscal_Year) where Program_Nm = '$MLMProgram_Nm' ";
  $stmt_prog = sqlsrv_query( $data_conn, $sql_prog ); 
  //$row_prog = sqlsrv_fetch_array( $stmt_prog , SQLSRV_FETCH_ASSOC);
  // $row_prog['Program_Nm'];
  //echo "<br>" . $sql_prog . "HERE";

  //PROGRAM FROM RIKEY (ARRAY)(0 Non Global or 1 Global )
  $sql_rikey_prg = "DECLARE @PROG_IDs VARCHAR(100)
  SELECT @PROG_IDs = COALESCE(@PROG_IDs+',','')+ CAST(Program_Key AS VARCHAR(100))
  FROM [RI_MGT].[fn_GetListOfProgramsForRI_Key] ($ri_id, 1) 
  SELECT @PROG_IDs AS Program_Key";
  $stmt_rikey_prg = sqlsrv_query( $data_conn, $sql_rikey_prg); 
  $row_rikey_prg = sqlsrv_fetch_array( $stmt_rikey_prg , SQLSRV_FETCH_ASSOC);
  $Programkeys = $row_rikey_prg['Program_Key'];
  $Program_Key = explode(",", $Programkeys); 

} else { 

  //PROGRAM FOR PORTFOLIO RI (MULTIPUL PROGRAMS)
  $sql_prog = "SELECT * FROM [RI_MGT].[fn_GetListOfMLMProgramAccessforUserUID]('$user_id', 2022)";
  $stmt_prog = sqlsrv_query( $data_conn, $sql_prog ); 
  //$row_prog = sqlsrv_fetch_array( $stmt_prog , SQLSRV_FETCH_ASSOC);
  // $row_prog['Program_Nm'];
  //echo "<br>" . $sql_prog . "NO";

  //PROGRAM FROM PORTFOLIO RIKEY (ARRAY)(0 Non Global or 1 Global )
  $sql_rikey_prg = "DECLARE @PROG_IDs VARCHAR(100)
  SELECT @PROG_IDs = COALESCE(@PROG_IDs+',','')+ CAST(Program_Key AS VARCHAR(100))
  FROM [RI_MGT].[fn_GetListOfProgramsForPortfolioRI_Key] ($ri_id) 
  SELECT @PROG_IDs AS Program_Key";
  $stmt_rikey_prg = sqlsrv_query( $data_conn, $sql_rikey_prg); 
  $row_rikey_prg = sqlsrv_fetch_array( $stmt_rikey_prg , SQLSRV_FETCH_ASSOC);
  $Programkeys = $row_rikey_prg['Program_Key'];
  $Program_Key = explode(",", $Programkeys); 
}

//SUBPROGRAM 
$sql_subprog = "select * from mlm.fn_getlistofsubprogramforprogram(-1) where Program_Nm = '$MLMProgram_Nm' and LRPYear = $Fiscal_Year";
$stmt_subprog = sqlsrv_query( $data_conn, $sql_subprog ); 
//$row_subprog = sqlsrv_fetch_array( $stmt_subprog , SQLSRV_FETCH_ASSOC);
// $row_subprog['SubProgram_Nm'];
//echo "<br>" . $sql_subprog;

//SUBPROGRAM FROM RIKEY
$sql_rikey_subprog = "DECLARE @SUB_IDs VARCHAR(100)
    SELECT @SUB_IDs = COALESCE(@SUB_IDs+',','')+ CAST(SubProgram_Key AS VARCHAR(100))
    FROM [RI_MGT].[fn_GetListSubProgramsforRIKey] ($ri_id,1)
    SELECT @SUB_IDs AS SubProgram_Key";
$stmt_rikey_subprog = sqlsrv_query( $data_conn, $sql_rikey_subprog ); 
$row_rikey_subprog = sqlsrv_fetch_array( $stmt_rikey_subprog , SQLSRV_FETCH_ASSOC);
$SubProgramkeys = $row_rikey_subprog['SubProgram_Key'];
$SubProgram = explode(",", $SubProgramkeys);
//echo "<br>" . $sql_rikey_subprog;

//DRIVERS
$sql_driver= "select * from [RI_MGT].[fn_GetListOfDriversForRILogKey](1) where RiskAndIssueLog_Key = $RiskAndIssueLog_Key";
$stmt_driver = sqlsrv_query( $data_conn, $sql_driver ); 
$row_driver = sqlsrv_fetch_array( $stmt_driver , SQLSRV_FETCH_ASSOC);

$Driver_Key = $row_driver['Driver_Key'];

//REGIONS
$sql_regions = "DECLARE @EPS_IDs VARCHAR(100)
    SELECT @EPS_IDs = COALESCE(@EPS_IDs+',','')+ CAST(MLMRegion_Key AS VARCHAR(100))
    FROM RI_MGT.fn_GetListOfAllRiskAndIssue(1) where RiskAndIssue_Key = $ri_id
    SELECT @EPS_IDs AS MLMRegion_Key";
$stmt_regions = sqlsrv_query( $data_conn, $sql_regions );
$row_regions = sqlsrv_fetch_array( $stmt_regions, SQLSRV_FETCH_ASSOC);

$MLMRegion_Key = $row_regions['MLMRegion_Key'];
$RegionArr = explode(",", $MLMRegion_Key);
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

    $('#program').multiselect({
          includeSelectAllOption: true,
        });
  
  });
</script>

</head>
<body style=" font-family:Mulish, serif;">
<?php 
  if($global != 1) {
    include ("../../includes/menu.php");
  } 
?>
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
  <h3>UPDATE GLOBAL RISK OR ISSUE</h3>
  <h4><?php echo "<b>ID: </b>" . $ri_id . " | " .  $RI_Nm ?></h4>
</div>

<div style="padding: 20px;">
  <form action="../update-confirm.php" method="post" id="programRisk">

  <input name="RiskAndIssue_Key" type="hidden" id="RiskAndIssue_Key" value="<?php echo $ri_id ?>">
  <input name="changeLogKey" type="hidden" id="changeLogKey" value="4">
  <input name="userId" type="hidden" id="userId " value="<?php echo $user_id ?>">
  <input name="formName" type="hidden" id="formName" value="PRGR"> <!--this needs to be prgi or prgr-->
  <input name="formType" type="hidden" id="formType" value="Update">
  <input name="CreatedFrom" type="hidden" id="Created From" value="">
  <input name="RIName" type="hidden" id="RIName" value="">
  <input name="assocProjectsKeys" type="hidden" id="assocProjectsKeys" value="">
  <input name="CreatedFrom" type="hidden" class="form-control" id="CreatedFrom" value="">
  <input name="DateClosed" type="hidden" id="DateClosed" value="">
  <input name="global" type="hidden" id="global" value="1">
  <input name="RILevel" type="hidden" id="RILevel" value="<?php echo $RILevel_Cd ?>">
  <input name="portfolioType" type="hidden" id="portfolioType" value="<?php echo $RIPortfolio_Key ?>">
  <input name="RIType" type="hidden" id="RIType" value="<?php echo $RIType_Cd ?>">
  <input name="global"  type="hidden" id="global" value="<?php echo $global ?>">
  <input name="formaction" type="hidden" id="formaction" value="update">
  <input name="fiscalYer" type="hidden" id="fiscalYer" value="<?php echo $Fiscal_Year; ?>">
  <input name="portfolioType_Key" type="hidden" id="portfolioType_Key" value="<?php echo $RIPortfolio_Key; ?>">
  
  <?php if($RILevel_Cd == "Portfolio") {?>
    <input name="Region" type="hidden" id="Region" value="<?php echo $MLMRegion_Key; ?>">
    <input name="subprogram" type="hidden" id="subprogramx" value="<?php echo $SubProgramkeys; ?>">
  <?php } ?>
  
   <div class="container">
  <!--ROW 1 -->
  <div class="row row-eq-height">
    <div class="col-md-3" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">LRP YEAR*</h3>
        </div>
        <div class="panel-body">
          <select name="fiscalYer" id="fiscalYer" class="form-control" disabled>
            <option value="2022" <?php if($Fiscal_Year == "2022") { echo " selected ";} ?>>2022</option>
            <option value="2023" <?php if($Fiscal_Year == "2023") { echo " selected ";} ?>>2023</option>
          </select>
        </div>
      </div>
    </div>  
    <div class="col-md-3" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">RISK/ISSUE LEVEL*</h3>
        </div>
        <div class="panel-body">
          <label for="RILevel"><input type="radio" name="RILevel" value="Program" required <?php if($RILevel_Cd == "Program") { echo 'checked';} ?> disabled> Program </label> 
          <label for="RILevel"><input type="radio" name="RILevel" value="Portfolio" required <?php if($RILevel_Cd == "Portfolio") { echo 'checked';} ?> disabled> Portfolio </label>
        </div>
      </div>
    </div>
    <div class="col-md-3" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">PORTFOLIO TYPE*</h3>
        </div>
        <div class="panel-body">
        <label for="portfolioType"><input type="radio" name="portfolioType" value="nt 2.0" required <?php if($RIPortfolio_Key == 1) { echo 'checked';} ?> disabled> NT 2.0 </label> 
        <label for="portfolioType1"><input type="radio" name="portfolioType" value="bau" required <?php if($RIPortfolio_Key == 2) { echo 'checked';} ?> disabled> BAU </label>
        </div>
    </div>
    </div>
    <div class="col-md-3" align="left">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">RISK/ISSUE TYPE*</h3>
      </div>
      <div class="panel-body">
      <label for="RIType"><input type="radio" name="RIType" value="Risk" required <?php if($RIType_Cd == "Risk") { echo 'checked';} ?> disabled> Risk </label> 
      <label for="RIType"><input type="radio" name="RIType" value="Issue" required <?php if($RIType_Cd == "Issue") { echo 'checked';} ?> disabled> Issue </label>
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
        <input name="Namex" type="text" readonly required="required" class="form-control" id="Namex" value="<?php echo $RI_Nm ?>" >
        </div>
      </div>
    </div>
    <div class="col-md-4" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">PROGRAM*</h3>
        </div>
        <div class="panel-body">
          <select name="program[]" id="program" class="form-control" multiple="multiple" readonly>
            <?php while($row_prog = sqlsrv_fetch_array( $stmt_prog , SQLSRV_FETCH_ASSOC)) { ?>
            <option value="<?php echo $row_prog ['Program_Key']; ?>" <?php if(in_array($row_prog ['Program_Key'], $Program_Key)) { echo "selected"; } ?>><?php echo $row_prog ['Program_Nm']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
    </div>
    <div class="col-md-4 " align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">SUBPROGRAM*</h3>
        </div>
        <div class="panel-body">
          <select name="subprogram[]" id="subprogram" class="form-control" multiple="multiple" required <?php if($RILevel_Cd  == "Portfolio") { echo " disabled"; }?>>
            <?php while($row_subprog = sqlsrv_fetch_array( $stmt_subprog , SQLSRV_FETCH_ASSOC)) { ?>
              <option value="<?php echo $row_subprog ['SubProgram_Key']; ?>" <?php if(in_array($row_subprog['SubProgram_Key'], $SubProgram)) { echo "selected";} ?>><?php echo $row_subprog ['SubProgram_Nm']; ?></option>
            <?php } ?>
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
          <h3 class="panel-title">DESCRIPTOR*</h3>
        </div>
        <div class="panel-body">
          <input name="Descriptor" type="text" required="required" class="form-control" id="Descriptor" maxlength="30" onChange="updatebox()" value="<?php echo $ScopeDescriptor_Txt ?>" readonly> 
        </div>
      </div>
    </div>
    <div class="col-md-8" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">REGION*</h3>
        </div>
        <div class="panel-body">
          <table width="100%">
          <tr>
            <td><label for="California"><input type="checkbox" name="Region[]" value="2" <?php if(in_array("2", $RegionArr)) { echo "checked";} if($RILevel_Cd  == "Portfolio") { echo " disabled"; }?> > California </label> </td>
            <td><label for="Central"><input type="checkbox" name="Region[]" value="3" <?php if(in_array("3", $RegionArr)) { echo "checked";} if($RILevel_Cd  == "Portfolio") { echo " disabled"; } ?> > Central </label></td>
            <td><label for="Corporate"><input type="checkbox" name="Region[]" value="1" <?php if(in_array("1", $RegionArr)) { echo "checked";} if($RILevel_Cd  == "Portfolio") { echo " disabled"; } ?> > Corporate </label></td>
            <td><label for="Northeast"><input type="checkbox" name="Region[]" value="4" <?php if(in_array("4", $RegionArr)) { echo "checked";} if($RILevel_Cd  == "Portfolio") { echo " disabled"; } ?> > Northeast </label></td>
          </tr>
          <tr>
            <td><label for="Southeast"><input type="checkbox" name="Region[]" value="5" <?php if(in_array("5", $RegionArr)) { echo "checked";} if($RILevel_Cd  == "Portfolio") { echo " disabled"; } ?> > Southeast </label> </td>
            <td><label for="Southwest"><input type="checkbox" name="Region[]" value="6" <?php if(in_array("6", $RegionArr)) { echo "checked";} if($RILevel_Cd  == "Portfolio") { echo " disabled"; } ?> > Southwest </label></td>
            <td><label for="Virginia"><input type="checkbox" name="Region[]" value="7" <?php if(in_array("7", $RegionArr)) { echo "checked";} if($RILevel_Cd  == "Portfolio") { echo " disabled"; } ?> > Virginia </label></td>
            <td><label for="All"><input type="checkbox" name="Region[]" value="" onClick="toggle(this)" <?php  if($RILevel_Cd  == "Portfolio") { echo " disabled"; } ?>> All </label></td>
          </tr>
        </table>
        </div>
      </div>
    </div>
  </div>
  <!-- ROW 4 --><hr>
  <div class="row row-eq-height">
    <div class="col-md-6" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">DESCRIPTION*</h3>
        </div>
        <div class="panel-body">
        <textarea name="Description" cols="120" rows="6" required="required" class="form-control" id="Description"><?php echo $RIDescription_Txt ?></textarea>
        </div>
      </div>      
    </div>
    <div class="col-md-6" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">DRIVERS*</h3>
        </div>
        <div class="panel-body">
          <table width="100%" border="0">
                  <tr>
                    <td width="50%"><label>
                      <input type="radio" name="Drivers[]" value="1"  id="Drivers_0" class="required_group" required <?php if($Driver_Key == 1) { echo "checked";} ?>>
                      Material Delay</label></td>
                      <td><label>
                      <input type="radio" name="Drivers[]" value="6" id="Drivers_1" class="required_group" required <?php if($Driver_Key == 6) { echo "checked";} ?>>
                      Project Dependency</label></td>
                  </tr>
                  <tr>
                      <td width="49%"><label>
                      <input type="radio" name="Drivers[]" value="2" id="Drivers_10" class="required_group" required <?php if($Driver_Key == 2) { echo "checked";} ?>>
                      Shipping/Receiving Delay</label></td>
                    <td><label>
                      <input type="radio" name="Drivers[]" value="7" id="Drivers_6" class="required_group" required <?php if($Driver_Key == 7) { echo "checked";} ?>>
                      Budget/Funding</label></td>
                  </tr>
                  <tr>
                    <td><label>
                      <input type="radio" name="Drivers[]" value="3" id="Drivers_2" class="required_group" required <?php if($Driver_Key == 3) { echo "checked";} ?>>
                      Ordering Error</label></td>
                      <td><label>
                      <input type="radio" name="Drivers[]" value="8" id="Drivers_7" class="required_group" required <?php if($Driver_Key == 8) { echo "checked";} ?>>
                      Design/Scope Change</label></td>
                  </tr>
                  <tr>
                    <td><label>
                      <input type="radio" name="Drivers[]" value="4" id="Drivers_3" class="required_group" required <?php if($Driver_Key == 4) { echo "checked";} ?>>
                      People Resource</label></td>
                    <td><label title="">
                      <input type="radio" name="Drivers[]" value="9" id="Drivers_8" class="required_group" required <?php if($Driver_Key == 9) { echo "checked";} ?>>
                      Admin Error</label></td>
                    </tr>
                  <tr>
                    <td><label title="">
                      <input type="radio" name="Drivers[]" value="5" id="Drivers_4" class="required_group" required <?php if($Driver_Key == 5) { echo "checked";} ?>>
                      3PL Resource</label></td>
                    <td><label title="">
                      <input type="radio" name="Drivers[]" value="10" id="Drivers_9" class="required_group" required <?php if($Driver_Key == 10) { echo "checked";} ?>>
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
                      <input type="radio" name="ImpactArea" value="<?php echo $row_impArea['ImpactArea_Key'] ?>" id="ImpactArea_<?php echo $row_impArea['ImpactArea_Key'] ?>" required <?php if($row_impArea['ImpactArea_Key'] == $ImpactArea_Key){ echo "checked";} ?>>
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
                        <input name="ImpactLevel" type="radio" id="ImpactLevel_<?php echo $row_imLevel['ImpactLevel_Key'] ?>" value="<?php echo $row_imLevel['ImpactLevel_Key'] ?>" required <?php if($row_imLevel['ImpactLevel_Key'] == $ImpactLevel_Key){ echo "checked";} ?>>
                        <?php echo $row_imLevel['ImpactLevel_Nm'] ?></label></td>
                      </tr>
                    <?php } ?>                    
                    </table>
                  </td>
                <td valign="top">
                <div id="myDIV2">
                  <?php if($RIType_Cd == "Risk") { ?>
                    <table width="200" border="0">
                        <tr>
                          <td>
                            <strong>Risk Probability Score *</strong>
                          </td>
                        <?php while($row_probability= sqlsrv_fetch_array( $stmt_probability , SQLSRV_FETCH_ASSOC)) { ?>
                        <tr>
                        <td><label>
                          <input name="RiskProbability" type="radio" id="RiskProbability_<?php echo $row_probability['RiskProbability_Key'] ?>" value="<?php echo $row_probability['RiskProbability_Key'] ?>" required <?php if($row_probability['RiskProbability_Key'] == $RiskProbability_Key){ echo "checked";} ?>>
                          <?php echo $row_probability['RiskProbability_Nm'] ?></label></td>
                        </tr>
                        <?php } ?>
                    </table>
                  <?php } ?>
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
  <!--ROW 6 | POC | FORCAST DATE | RESPONSE STRATEGY -->
  <div class="row equal">
    <div class="col-md-4" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">CURRENT TASK POC</h3>
        </div>
        <div class="panel-body">
          <label for="Individual">Individual POC *<br></label>
              <select type="text" list="Individual" name="Individual" class="form-control" id="indy" required>
                  <?php while($row_internal  = sqlsrv_fetch_array( $stmt_internal , SQLSRV_FETCH_ASSOC)) { ?>
                    <option value="<?php echo $row_internal['POC_Nm'] ;?>" <?php if($POC_Nm == $row_internal['POC_Nm']) { echo "selected";} ?>><?php echo $row_internal['POC_Nm'] . " : " . $row_internal['POC_Department'] ;?></option>
                  <?php } ?>
              </select>  
            <hr>
              <div align="center">
                <span class="glyphicon glyphicon-edit"></span> <a href="https://coxcomminc.sharepoint.com/teams/engmgmtoffice/Lists/EPS%20Support%20%20Enhancement%20Portal/AllItems.aspx" target="_blank">Request POC Addition</a>
              </div>
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
                    onChange="unKnownX()"
                    <?php if(empty($ForecastedResolution_Dt)){ echo "checked";} ?>
                >
                <label for="Unknown">Unknown</label> - Overrides Resolution Date
          </div>
          <hr>
          <div id="trans2prgman">
                <input type="checkbox" 
                    name="TransfertoProgramManager" 
                    id="TransfertoProgramManager"
                    <?php if($transfer2prgManager == 1){ echo "checked";} ?>
                    >
                <label for="TransfertoProgramManager"> Transfer to Program Manager</label>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">RESPONSE STRATEGY*</h3>
        </div>
        <div class="panel-body">
          <table width="246" border="0" cellpadding="5" cellspacing="5">
                <tr>
                  <td>&nbsp;</td>
                  <td><label>
                    <input type="radio" name="ResponseStrategy" value="1" id="Response_Strategy_0" required <?php if($ResponseStrategy_Cd == "AVD"){ echo "checked";} ?>>
                    Avoid</label></td>
                  </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td><label>
                    <input type="radio" name="ResponseStrategy" value="2" id="Response_Strategy_1" required <?php if($ResponseStrategy_Cd == "MIT"){ echo "checked";} ?>>
                    Mitigate</label></td>
                  </tr>
                <tr>
                  <td width="16">&nbsp;</td>
                  <td width="195"><label>
                    <input type="radio" name="ResponseStrategy" value="3" id="Response_Strategy_2" required <?php if($ResponseStrategy_Cd == "TRN"){ echo "checked";} ?>>
                    Transfer</label></td>
                  </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td><label>
                    <input type="radio" name="ResponseStrategy" value="4" id="Response_Strategy_3" required <?php if($ResponseStrategy_Cd == "ACP"){ echo "checked";} ?>>
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
          <textarea name="ActionPlan" cols="120" class="form-control" id="ActionPlan" ><?php echo $actionPlan; ?></textarea>  
          <input type="hidden" value="<?php echo $actionPlan_b?>" name="ActionPlan_b">
          <input type="hidden" name="user" value="<?php echo $user_id ?>">
          <input type="hidden" name="tempID"value="<?php //echo $temp_id ?>">
          <div align="right" style="margin-top:10px; margin-bottom:10px;">  
              <a class="btn btn-primary" role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">History</a>
          </div>
          <div class="collapse" id="collapseExample">
            <div class="well">
              <iframe id="actionPlan" src="../action_plan.php?rikey=<?php echo $ri_id?>" width="100%" frameBorder="0"></iframe>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--ROW 8 - RISK REALIZED | ASSOC CR | NOTIFY PORT TEAM-->
  <div class="row row-eq-height">
    <div class="col-md-3" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">RISK REALIZED*</h3>
        </div>
        <div class="panel-body">
          <table width="50%" border="0">
            <tr>
              <td><label>
                  <input type="radio" name="riskRealized" value="1" id="RiskRelized_0" <?php if($RiskRealized_Flg == 1) { echo "checked"; }?>>
                  Yes</label></td>
              <td><label>
                  <input type="radio" name="riskRealized" value="0" id="RiskRelized_1" <?php if($RiskRealized_Flg == 0) { echo "checked"; }?>>
                  No</label></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <div class="col-md-3" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">ASSOCIATED CR ID</h3>
        </div>
        <div class="panel-body">
          <input name="assCRID" type="text" class="form-control" id="assCRID" maxlength="10" value="<?php echo $AssociatedCR_Key ?>">
        </div>
      </div>       
    </div>
    <div class="col-md-3" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">NOTIFY PORTFOLIO TEAM*</h3>
        </div>
        <div class="panel-body">
          <table width="50%" border="0">
            <tr>
              <td><label><input type="radio" name="raidLog" value="Yes" id="raid_0" <?php if($RaidLog_Flg == 1) { echo "checked"; }?>> Yes </label>
              </td>
            <td>
              <label><input type="radio" name="raidLog" value="No" id="raid_1" <?php if($RaidLog_Flg == 0) { echo "checked"; }?>> No </label>
            </td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <div class="col-md-3" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">CLOSING DATE</h3>
        </div>
        <div class="panel-body">
          <input type="date" name="DateClosed" id="DateClosed" class="form-control" min="<?php echo $createDT; ?>" max="<?php echo $closeDateMax; ?>">
        </div>
      </div>
    </div>
  </div>
</div>
<!--end container -->
  <!--<button class="btn btn-primary" onclick="myConfirmation()"><span class="glyphicon glyphicon-step-backward"></span> Back </button> -->
  <button type="submit" class="btn btn-primary">Review <span class="glyphicon glyphicon-step-forward"></span></button>
  </form>
</div>
</main>
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