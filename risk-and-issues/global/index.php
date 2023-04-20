<?php include ("../../includes/functions.php");?>
<?php include ("../../db_conf.php");?>
<?php include ("../../data/emo_data.php");?>
<?php include ("../../sql/RI_Internal_External.php");?>
<?php include ("../../sql/update-time.php");?>
<?php 
//$action = $_GET['action'];
$user_id = preg_replace("/^.+\\\\/", "", $_SERVER["AUTH_USER"]);
//$ass_project = $row_projID['PROJ_NM'];

//PROGRAM
function fixutf8($target) {
  return (gettype($target) == "string") ? (utf8_encode($target)) : ($target);
}  

$sql_port_user = "SELECT * FROM [RI_MGT].[RiskandIssues_Users] WHERE Username = '$user_id'";
// echo "$sql_port_user <br/>";
$stmt_port_user   = sqlsrv_query( $data_conn, $sql_port_user );
$row_port_user  = sqlsrv_fetch_array( $stmt_port_user , SQLSRV_FETCH_ASSOC);

// print("row_port_user: '<pre>");
// print(json_encode($row_port_user, JSON_PRETTY_PRINT));
// print("'</pre>, uid=$user_id");

$thisyear = date('Y');
$sqluseraccess = "SELECT * FROM [RI_MGT].[fn_GetListOfMLMProgramAccessforUserUID]('$user_id', $thisyear)";
// echo $sqluseraccess;
$sqluserresults = sqlsrv_query($data_conn, $sqluseraccess);
$userrows = array();
$count = 1;
if($sqluserresults === false) {
  if(($error = sqlsrv_errors()) != null) {
    foreach($error as $errors) {
      echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
      echo "code: ".$error[ 'code']."<br />";
      echo "message: ".$error[ 'message']."<br />";
    }
  }
} else {
  while($userrow = sqlsrv_fetch_array($sqluserresults, SQLSRV_FETCH_ASSOC)) {
    $userrows[] = array_map("fixutf8", $userrow);
  }
}
$sql_prog = "SELECT * FROM [RI_MGT].[fn_GetListOfMLMProgramAccessforUserUID]('$user_id', $thisyear)"; /// this was static of 2022 and changed on 3.3.2023   
$stmt_prog   = sqlsrv_query( $data_conn, $sql_prog ); 
if($stmt_prog === false) {
  if(($error = sqlsrv_errors()) != null) {
    foreach($error as $errors) {
      echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
      echo "code: ".$error[ 'code']."<br />";
      echo "message: ".$error[ 'message']."<br />";
    }
  }
} else {
  $programrows = array();
  $count = 1;
  while($programrow = sqlsrv_fetch_array($stmt_prog, SQLSRV_FETCH_ASSOC)) {
    $programrows[] = array_map("fixutf8", $programrow);
  }
}

$sql_sub = "select * from mlm.fn_getlistofsubprogramforprogram(-1)";
$stmt_sub   = sqlsrv_query( $data_conn, $sql_sub ); 
if($stmt_sub === false) {
  if(($error = sqlsrv_errors()) != null) {
    foreach($error as $errors) {
      echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
      echo "code: ".$error[ 'code']."<br />";
      echo "message: ".$error[ 'message']."<br />";
    }
  }
} else {
  $subprogramrows = array();
  $count = 1;
  while($subprogramrow = sqlsrv_fetch_array($stmt_sub, SQLSRV_FETCH_ASSOC)) {
    $subprogramrows[] = array_map("fixutf8", $subprogramrow);
  }
}

//$row_prog   = sqlsrv_fetch_array( $stmt_prog , SQLSRV_FETCH_ASSOC);
// $row_prog ['Program_Nm'];
$programout = json_encode($programrows);
$subprogramout = json_encode($subprogramrows);

//SUBPROGRAM 
$sql_subprog = "select * from mlm.fn_getlistofsubprogramforprogram(-1) where LRPYear = $thisyear";
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
  <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js"></script>

  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
  <link rel="stylesheet" href="../steps/style.css" type='text/css'> 
  <link rel="stylesheet" href="../includes/ri-styles.css" />
  <link rel="stylesheet" href="../../colorbox-master/example1/colorbox.css" />
  
  <script>
   let prop = {
      includeSelectAllOption: true, 
      enableCaseInsensitiveFiltering: true, 
      selectAllNumber: true, 
      numberDisplayed: 1, 
      includeResetOption: true
    }
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
          // $("#programRisk").validate()
          // $.validator.addMethod("needsSelection", function(value, element) {
          //   return $(element).multiselect("getChecked").length > 0;
          // })
          // $.validator.messages.needsSelection = "You gotta pick something";
          // $('#programRisk').validate({
          //   rules: {
          //     program: "required needsSelection",
          //   },
          //   ignore: ':hidden:not("#program")', 
          //   errorClass: "invalid"
          // });
        });
        programs = <?= $programout ?>;
        subprograms = <?= $subprogramout ?>;
        </script> 

<script language="JavaScript">
  function toggle(source) {
    checkboxes = document.getElementsByName('Region[]');
    for(var i=0, n=checkboxes.length;i<n;i++) {
      checkboxes[i].checked = source.checked;
    }
    // document.getElementById("Region").checked = false;
    // document.getElementById("Region").click();
  }
  var user  = <?= json_encode($row_port_user, JSON_PRETTY_PRINT) ?>
  ;
  var useraccess  = <?= json_encode($userrows, JSON_PRETTY_PRINT) ?>
  ;
  if (useraccess == null) {
    alert("You dont have access to Create a Global Risk or Issue. Please contact EES Support to request access.");
    document.location.href="/"
  };
</script>

</head>
<body style=" font-family:Mulish, serif;">
<?php 
  include ("../../includes/menu.php");
  if ($userrows == null) {
    echo "<div class='container'>
            <h2>You Don't Have Access</h2>
            <div class='panel panel-default'>
              <div class='panel-body'>The following roles in Master List Management (MLM) can create Global Risks/Issues: Planning Program Manager, Delivery Program Manager, Forecast Manager, Boundary Program Manager, and Portfolio Lead.<br><br>For any access related questions, please reach out to your manager.<br><br>Thanks,<br>Engineering Enablement Solutions Team</div>
            </div>
          </div>";
    exit();
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
  <h3>CREATE GLOBAL RISK OR ISSUE</h3>
  Enter the details of your Risk/Issue
</div>

<div style="padding: 20px;">
  <form action="../confirm.php" method="post" id="programRisk" oninputD="nameevent()">

  <input name="changeLogKey" type="hidden" id="changeLogKey" value="2">
  <input name="userId" type="hidden" id="userId " value="<?php echo $user_id ?>">
  <input name="formName" type="hidden" id="formName" value=""> <!--DETERMINED IN LOOKUP-->
  <input name="formType" type="hidden" id="formType" value="New">
  <input name="CreatedFrom" type="hidden" id="Created From" value="">
  <input name="RIName" type="hidden" id="RIName" value="">
  <input name="assocProjectsKeys" type="hidden" id="assocProjectsKeys" value="">
  <input name="CreatedFrom" type="hidden" class="form-control" id="CreatedFrom" value="">
  <input name="DateClosed" type="hidden" id="DateClosed" value="">
  <input name="global" type="hidden" id="global" value="1">
  <input name="TransfertoProgramManager" type="hidden" id="TransfertoProgramManager" value="">
  <input name="changeLogAction" type="hidden" id="changeLogAction" value="">
  <input name="changeLogReason" type="hidden" id="changeLogReason" value="">
  <input name="portfolioType" type="hidden" id="portfolioType" value="" >
  

  <div class="container">
  <!--ROW 1 -->
  <div class="row row-eq-height">
    <div class="col-md-4" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">LRP YEAR*</h3>
        </div>
        <div class="panel-body">
          <select name="fiscalYer" id="fiscalYer" class="form-control">
            <option value="2022" <?php if(date('Y') == "2022") { echo " selected ";} ?>>2022</option>
            <option value="2023" <?php if(date('Y') == "2023") { echo " selected ";} ?>>2023</option>
            <option value="2024" <?php if(date('Y') == "2024") { echo " selected ";} ?>>2024</option>
          </select>
        </div>
      </div>
    </div>
    <div class="col-md-4" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">RISK/ISSUE LEVEL*</h3>
        </div>
        <div class="panel-body">
          <label for="RILevel"><input type="radio" id="RILevel.Program" name="RILevel" value="Program" required > Program </label> 
          <label for="RILevel"><input type="radio" id="RILevel.Portfolio" name="RILevel" value="Portfolio" required <?php if(empty($row_port_user)){ echo " disabled";} ?>> Portfolio </label>
        </div>
      </div>
    </div>
    <div class="col-md-4" align="left">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">RISK/ISSUE TYPE*</h3>
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
        <input name="Namex" type="text" readonly required="required" class="form-control" id="Namex" >
        </div>
      </div>
    </div>
    <div class="col-md-4" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">PROGRAM*</h3>
        </div>
        <div class="panel-body" id="programdiv">
          <!-- <select name="program" id="program" class="form-control">
            <?php //while($row_prog = sqlsrv_fetch_array( $stmt_prog , SQLSRV_FETCH_ASSOC)) { ?>
            <option value="<?php //echo $row_prog ['Program_Nm']; ?>"><?php //echo $row_prog ['Program_Nm']; ?></option>
            <?php //} ?>
          </select> -->
        </div>
      </div>
    </div>
    <div class="col-md-4 " align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">SUBPROGRAM*</h3>
        </div>
        <div class="panel-body" id="subdiv">
          <select name="subprogram[]" id="subprogram" class="form-control" multiple="multiple" required oninvalid="//alert('You must select a subprogram');return false">
          <option>Select Program first
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
          <h3 class="panel-title">DESCRIPTOR* <a href="../includes/definitions.php?tooltipkey=RSKD" class="dno"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
            <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
            </svg></a>
          </h3>
        </div>
        <div class="panel-body">
          <input name="Descriptor" type="text" required="required" class="form-control" id="Descriptor" maxlength="30" onChange="//updatebox()"> 
        </div>
      </div>
    </div>
    <div class="col-md-8" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">REGION*</h3>
        </div>
        <div class="panel-body">
          <table width="100%" class="checkbox_group required" id="regionsold" required>
            <tr>
              <td><label for="California"><input type="checkbox" id="Region" name="Region[]" value="California" required oninvalid="this.setCustomValidity('Please choose at least one region')"> California </label> </td>
              <td><label for="Central"><input type="checkbox" name="Region[]" value="Central" required> Central </label></td>
              <td><label for="Corporate"><input type="checkbox" name="Region[]" value="Corporate" required> Corporate </label></td>
              <td><label for="Northeast"><input type="checkbox" name="Region[]" value="Northeast" required> Northeast </label></td>
            </tr>
            <tr>
              <td><label for="Southeast"><input type="checkbox" name="Region[]" value="Southeast" required> Southeast </label> </td>
              <td><label for="Southwest"><input type="checkbox" name="Region[]" value="Southwest" required> Southwest </label></td>
              <td><label for="Virginia"><input type="checkbox" name="Region[]" value="Virginia" required> Virginia </label></td>
              <td><label for="All"><input type="checkbox" name="allbutton" value="All" onClick="toggle(this)"> All </label></td>
            </tr>
          </table>
          <table width="100%" class="checkbox_group required" id="regionsnew" style="display:none" required>
          <tr>
            <td><label for="Central"><input type="checkbox" name="Region[]" value="Central" required> Central </label></td>
            <td><label for="Corporate"><input type="checkbox" name="Region[]" value="Corporate" required> Corporate </label></td>
            <td><label for="East"><input type="checkbox" name="Region[]" value="East" required> East </label> </td>
            <td><label for="West"><input type="checkbox" name="Region[]" value="West" required> West </label></td>
            <td><label for="All"><input type="checkbox" name="allbutton" value="All" onClick="toggle(this)"> All </label></td>
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
          <h3 class="panel-title">DESCRIPTION* <a href="../includes/definitions.php?tooltipkey=DESC" class="dno"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
            <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
            </svg></a>
          </h3>
        </div>
        <div class="panel-body">
        <textarea name="Description" cols="120" rows="6" required="required" class="form-control" id="Description"></textarea>
        </div>
      </div>      
    </div>
    <div class="col-md-6" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">DRIVERS* <a href="../includes/definitions.php?tooltipkey=DRVR" class="dno"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
            <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
            </svg></a>
          </h3>
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
  <div class="row equal">
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
              <tr id="impacts">
                <td  valign="top">
                  <table width="200" border="0">
                  <tr>
                    <strong>Impacted Area * </strong><a href="../includes/definitions.php?tooltipkey=IMPA" class="dno"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                      <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                      <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                    </svg></a>
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
                      <strong>Impact Level * </strong> <a href="../includes/definitions.php?tooltipkey=IMPL" class="dno"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                        </svg></a>
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
                <td valign="top" id="riskprobability">
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
  <div class="row eq-height">
    <!--<div class="col-md-4" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">CURRENT TASK POC</h3>
        </div>
        <div class="panel-body">

          <label for="Individual">Individual POC *<br></label>
            <select type="text" list="Individual" name="Individual" class="form-control" id="indy" required>
                  <option value=""></option>
                <?php while($row_internal  = sqlsrv_fetch_array( $stmt_internal , SQLSRV_FETCH_ASSOC)) { ?>
                  <option value="<?php echo $row_internal['POC_Nm'] ;?>"><?php echo $row_internal['POC_Nm'] . " : " . $row_internal['POC_Department'] ;?></option>
                <?php } ?>
            </select>  
            <hr>
            <div align="center">
            <span class="glyphicon glyphicon-edit"></span> <a href="https://coxcomminc.sharepoint.com/teams/engmgmtoffice/Lists/EPS%20Support%20%20Enhancement%20Portal/AllItems.aspx" target="_blank">Request POC Addition</a>
            </div>
            <hr>
          <label for="Individual3">Team/Group POC *<br></label>
            <input type="text" name="InternalExternal" class="form-control" id="InternalExternal" onclick="myFunction()" readonly/>
        </div>
      </div>
    </div>-->
    <div class="col-md-6" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">FORCASTED RESOLUTION DATE <a href="../includes/definitions.php?tooltipkey=FRRD" class="dno"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
            <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
            </svg></a>
          </h3> 
        </div>
        <div class="panel-body">
          <div id="dateUnknown">
                <input name="date" 
                    type="date"
                    min="<?php echo $closeDateMax ?>"
                    class="form-control" 
                    id="date" 
                    value=""
                    onChange="//forCastedX()"  
                    oninvalidDisabled="this.setCustomValidity('You must select a date or check Unknown ')"
                    oninputDisabled="this.setCustomValidity('')">
          </div>
        <br>
          <div id="forcastedDate">
                <input type="checkbox" 
                    name="Unknown" 
                    id="Unknown" 
                    onChange="unKnownX()">
                <label for="Unknown"> Unknown</label> - Overrides Resolution Date
          </div>
        </div>
      </div>
    </div>
                          
    <div class="col-md-6" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">RESPONSE STRATEGY* <a href="../includes/definitions.php?tooltipkey=RSPS" class="dno"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
            <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
            </svg></a>
          </h3>
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
          <h3 class="panel-title">ACTION PLAN* <a href="../includes/definitions.php?tooltipkey=ACTP" class="dno"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
            <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
            </svg></a>
          </h3>
        </div>
        <div class="panel-body">
        <textarea name="ActionPlan" cols="120" required="required" class="form-control" id="ActionPlan"></textarea>
        </div>
      </div>
    </div>
  </div>
  <!--ROW 8 - RISK REALIZED | ASSOC CR | NOTIFY PORT TEAM-->
  <div class="row row-eq-height">
    <div class="col-md-4" align="left" id="realizeddiv">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">RISK REALIZED*</h3>
        </div>
        <div class="panel-body">
          <table width="50%" border="0">
            <tr>
              <td><label>
                  <input type="radio" name="riskRealized" value="1" id="RiskRelized_0">
                  Yes</label></td>
              <td><label>
                  <input type="radio" name="riskRealized" value="0" id="RiskRelized_1" checked>
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
    <div class="col-md-4" align="left" style="display:none" id="npt">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">NOTIFY PORTFOLIO TEAM* <a href="../includes/definitions.php?tooltipkey=NTPT" class="dno"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
            <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
            </svg></a>
          </h3>
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
<input type="hidden" id="programcache" value=""/>
<!--end container -->
  <button type="submit" class="btn btn-primary" id="review" >Review <span class="glyphicon glyphicon-step-forward"></span></button>
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
    let $inputs = $('input[name=date],input[name=unknown]');
    $inputs.on('input', function () {
        // Set the required property of the other input to false if this input is not empty.
        $inputs.not(this).prop('required', !$(this).val().length);
    });
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

  document.getElementById('datexxx').value = today;
  /*** This whole item is commented out, now/
  document.getElementById("indy").addEventListener("change", function(){
    const v = this.value.split(" : ");
    this.value = v[0];
    // document.getElementById("InternalExternal").value = v[1];
    // ^^^^ This refers to an element that's commented out
  });
  */
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

  const getuniques = (list, field) => {
    console.log("sort", sort);
    return list.map(item => item[field]).filter((value, index, self) => self.indexOf(value) === index).sort();
  }
  const makeselect = (o) => {
    const select = makeelement(o);
      list = o.l;
      // select.appendChild(makeelement({e: "option", v: "", t: "None selected"}));
      for (option in list) 
        if(list[option].Program_Nm != ""&& list[option].Program_Nm != null)
          select.appendChild(makeelement({e: "option", v: list[option].Program_Nm, t: list[option].Program_Nm}));
    return select;
  }

  const makeelement = (o) => {

    // o is an (o)bject with these optional properties:
    // o.e is the (e)lement, like "td" or "tr"
    // o.c is the (i)d
    // o.c is the (c)lasses, separated by spaces like usual
    // o.t is the innerHTML (t)ext
    // o.s is the col(s)pan
    // o.a is a list of (a)ttributes

    const t = document.createElement(o.e);
    t.id = (typeof o.i == "undefined") ? "" : o.i;
    t.name = (typeof o.n == "undefined") ? "" : o.n + "[]";
    t.className = (typeof o.c == "undefined") ? "" : o.c;
    t.innerHTML = (typeof o.t == "undefined") ? "" : o.t;
    t.colSpan = (typeof o.s == "undefined") ? "" : o.s;
    t.width = (typeof o.w == "undefined") ? "" : o.w + "%";
    t.multiple = (typeof o.m == "undefined") ? "" : o.m;
    t.value = (typeof o.v == "undefined") ? "" : o.v;
    if (o.a) {
      o.a.forEach((a) => {
        t[a] = true;
      })
    }
    if (typeof o.j != "undefined") {
      t.onclick = o.j;
    }
    return t;
  }

  // Event functions to be used later

  const regions = {"California": "CA", "Southwest": "SW", "Central": "CE", East: "EA", "Northeast": "NE", "Virginia": "VA", "Southeast": "SE", West: "WS", "Northwest": "NW", "Corporate": "COR"}
  const nameevent = () => {
    let locations = "";
    if (document.querySelector('input[name="allbutton"][value=All]').checked == true) {
      locations = "ALL ";
      document.getElementsByName("Region[]").forEach((o) => {
        o.required = false;
        o.setCustomValidity("");
      });
    } else {
      document.getElementsByName("Region[]").forEach((e) => {
        locations += (e.checked) ? regions[e.value] + " " : "";
      });
      let required = (!locations.length > 0);
      document.getElementsByName("Region[]").forEach((o) => {
        o.required = required;
        o.setCustomValidity((required) ? "You must select a region" : "");
      });
      if (locations.length > 4) {
        locations = "MULTI ";
      }
    }
    let p = n = "";
    if (document.querySelector('input[name="RILevel"][value=Portfolio]').checked == true) {
      p = "PORTFOLIO ";
      n = ($("#program").val().length == 1) ? $("#program").val()[0] :"";
    } else {
      p = "";
      pn = $("#program").val();
      n = (pn != null) ? pn : "";
    }
    Namex.value = p + n + ' ' + locations + Descriptor.value + ' POR' + fiscalYer.value.slice(2)
    return(locations)
  }

  const subevent = () => {
    // console.log("subevent");
    let p = document.getElementById("program");
    let s = document.getElementById("subprogram");
    let p0 = p.options[0];
    localStorage.setItem("program", $("#program").val());
    if (p0.value == '') p0.remove();
    s.options.length = 0;
    let target = p.options[p.selectedIndex].text;
    // console.log(target);
    let sublist = {};
    subprograms.forEach(subtarget => {
      // console.log(subtarget)
      if (subtarget.Program_Nm == target && 
          !sublist[subtarget.SubProgram_Nm] &&
          subtarget.LRPYear == document.getElementById('fiscalYer').value) {
        // console.log(subtarget.SubProgram_Nm)
        sublist[subtarget.SubProgram_Nm] = subtarget.SubProgram_Key;
      } 
    });
    // console.log(typeof sublist);
    if (sublist.length == 0) {
      s.appendChild(makeelement({e: "option", t: "No Subprograms Available", v: ""}));
    } else {
      Object.entries(sublist).forEach(([o, k]) => {
        // console.log(o)
        s.appendChild(makeelement({e: "option", t: o, v: k}));
      });
    }
    $('#subprogram').multiselect("destroy").multiselect(prop);
    setTimeout(function() {
      if (!document.backbutton)
        document.getElementById("programcache").value = document.getElementById("program").value;
    }, 1000);
    $('#subprogram').removeAttr('style')
    document.getElementById("subprogram").style.height = "0px";
    document.getElementById("subprogram").style.width = "0px";
    document.getElementById("subprogram").style.position= "absolute";
  }

  const levelevent = (e) => {
    programlevel();
  }

  const disablebyname = (target, disable) => {
    document.getElementsByName(target).forEach(t2 => {
      t2.checked = (disable) ? false : t2.checked;
      t2.disabled = disable;
      t2.title = (disable) ? `Only available for ${o.e}` : `Choose an option for your ${o.e}`;
    })
  }
  const disableevent = (o) => {
    
    // o is the object, with properties:
    // o.t = type (the id or name of the element to be clicked on)
    // o.v = the (v)alue that will cause something to be disabled
    // o.d = a, [array] of ids that will be disabled
    // o.e = the mode where the disabled thing is allowed

    if (!document.querySelector(`input[name="${o.t}"]:checked`)) return false; // if you can't find the element to be clicked
    const disable = (document.querySelector(`input[name="${o.t}"]:checked`).value == o.v);
    o.d.forEach(field => {
      console.log(field);
      console.log(document.getElementsByName(field).length);
      if(document.getElementsByName(field).length == 0) {
        $(`#${field}`).val("").multiselect(disable ? "disable" : "enable");
        $('#subprogram').multiselect("destroy").multiselect(prop);
      } else {
        disablebyname(field, disable)
        if (field == "Region[]") {
          disablebyname("allbutton", disable);
        }
      }
    })
    if (o.v == "Issue") {
      console.log("Yes")
      document.getElementById("riskprobability").style.display = (disable) ? "none" : "block";
      document.getElementById("RiskRelized_1").checked = (!disable);
    }
    if (o.t == "RILevel" && o.v == "Program") {
      setTimeout(() => document.getElementById("raid_0").click());
    }
  }

  const programlevel = () => {
    if (document.querySelector(`input[name="RILevel"]:checked`) && document.querySelector(`input[name="RILevel"]:checked`).value == "Portfolio") {
      console.log("portfolio");
      $("#program").val(false).attr("required", true).attr("multiple", true).multiselect("destroy").multiselect(prop).attr("display", "block");;
    } else {
      if (!document.backbutton) {
        console.log("ping")
        $("#program").val(false).attr("required", true).attr("multiple", false).val(false).multiselect("destroy").multiselect(prop).attr("display", "block");;
      }
    }
  }

  // Event creators

  $("#subprogram").change(() => {
    console.log("sub");
    console.log($("#subprogram").val());
    localStorage.setItem("subprogram", $("#subprogram").val());
  })

  const setregionevent = () => {
    document.getElementsByName("Region[]").forEach((target) => {
      target.addEventListener("click", (e) => {
        nameevent();
      });
    });
  };

  var prog;
  const setsubprogramevent = () => {
    prog = makeselect({l: programs, f: "Program_Nm", i: "program", n: "program", t: "Programs", e: "select", c: "form-control", a: ["required"]});
    document.getElementById("programdiv").innerHTML = ""
    document.getElementById("programdiv").appendChild(prog);
    $('#subprogram').removeAttr('style')
    document.getElementById("subprogram").style.height = "0px";
    document.getElementById("subprogram").style.width = "0px";
    document.getElementById("subprogram").style.position= "absolute";
    $("#program").change(subevent);
  };

  const setlevelevent = () => {
    document.getElementsByName("RILevel").forEach((target) =>  {
      target.addEventListener("click", (e) => {
        levelevent();
      });
    })
  };

  nameparts = ["program", "Descriptor", "fiscalYer"]
  const setnameevent = () => {
    nameparts.forEach((target) => {
      document.getElementById(target).addEventListener("change", (e) => nameevent());
    });
  };

  const setdisableevent = (o) => {
    document.getElementsByName(o.t).forEach((target) =>  {
      target.addEventListener("click", (e) => {
        disableevent(o);
      });
    })
  }


  const conditionals = () => {
    console.log("running conditionals");
    nameevent();
    disableevent({t: "RIType", v: "Issue", d: ["RiskProbability", "riskRealized"], e: "risk"})
    disableevent({t: "RILevel", v: "Program", d: ["portfolioType", "TransfertoProgramManager"], e: "Portfolio"})
    disableevent({t: "RILevel", v: "Portfolio", d: ["Region[]", "subprogram", "raidLog"], e: "Portfolio"})
  }

  document.getElementsByName("RIType").forEach((o) => {
    o.addEventListener("click", (e) => {
      document.getElementById("realizeddiv").style.display = (e.target.value == "Issue") ? "none" : "block";
    })
  })

  $(document).ready(function() {
    document.getElementById(id="programRisk").addEventListener("oninput", () => {nameevent()})
    document.querySelector("#date").addEventListener("keydown", (e) => {e.preventDefault()});
    document.querySelector("#DateClosed").addEventListener("keydown", (e) => {e.preventDefault()});
    setdisableevent({t: "RIType", v: "Issue", d: ["RiskProbability", "riskRealized"], e: "risk"})
    setdisableevent({t: "RILevel", v: "Program", d: ["portfolioType", "TransfertoProgramManager"], e: "Portfolio"})
    setdisableevent({t: "RILevel", v: "Portfolio", d: ["Region[]", "subprogram", "raidLog"], e: "Programs"})
    setsubprogramevent();
    setregionevent();
    setlevelevent();
    $("#program").val(false).attr("multiple", true).multiselect(prop);
    $("#subprogram").val(false).attr("multiple", true).multiselect(prop);
    $('#program').removeAttr('style')
    document.getElementById("program").style.height = "1px";
    document.getElementById("program").style.width = "1px";
    document.getElementById("program").style.position= "absolute";
    $('#subprogram').removeAttr('style')
    document.getElementById("subprogram").style.height = "0px";
    document.getElementById("subprogram").style.width = "0px";
    document.getElementById("subprogram").style.position= "absolute";
    if (user == null || user.Group != "PORT")  {
      console.log("port")
      document.getElementById("RILevel.Portfolio").disabled = true;
      document.getElementById("RILevel.Portfolio").title = "Your account does not have Portfolio access. Please request access if you need it.";
      document.getElementsByName("portfolioType").forEach(t2 => {
        t2.disabled = true;
        t2.title = `Only available for Portfolios`;
      })
      document.getElementById("TransfertoProgramManager").disabled = true;
    } else {
      console.log("starboard")
    }
  });

  setInterval(function() {
    nameevent();
    document.getElementById("program").style.display = "block";
    document.getElementById("subprogram").style.display = "block";
  }, 1000);
  
  const restoredata = () => {
    if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
      document.backbutton = true;
      document.subprogram = localStorage.getItem("subprogram");
      document.program = localStorage.getItem("program");
      // document.getElementById("program").value = document.program;
      console.log(document.program)
      console.log("restoring sub");
      console.log(document.subprogram)
      setTimeout(conditionals, 2000);
      setTimeout(function(){
        $('#program').val(document.program).multiselect("destroy").multiselect(prop);
        subevent();
        // setTimeout(function() {
          $('#subprogram').val(document.subprogram.split(",")).multiselect("destroy").multiselect(prop);
          // }, 100);
        }, 3000);
      } else 
      document.backbutton = false;
    }
    setTimeout(restoredata), 4000;
  const prescreen = () => {
    document.querySelectorAll("input[type=text], textarea").forEach(o => { 
      o.value = o.value.trim()
    })
  }
  document.getElementById("review").addEventListener("mouseover", () => {
    prescreen();
  })
  document.getElementById("fiscalYer").addEventListener("change", (e) => {
    let yer = e.target.value;
    console.log(e.target.value);
    document.getElementById("regionsold").style.display = (yer == 2024) ? "none" : "";
    document.getElementById("regionsnew").style.display = (yer != 2024) ? "none" : "";
  })

  let summerprops = {
    toolbar: [
      ['style', ['style']],
      ['font', ['bold', 'underline', 'italic', 'clear']],
      ['fontname', ['fontname']],
      ['para', ['ul', 'ol', 'paragraph']
    ]
  ]};

  summercss = {"position": "absolute", "width": "0px", "height": "0px"}
  $(document).ready(function() {
    $('#Description').summernote(summerprops).css(summercss).show();
    $('#ActionPlan').summernote(summerprops).css(summercss).show();
  });

</script>

<script src="../includes/ri-functions.js"></script>
</body>
</html>