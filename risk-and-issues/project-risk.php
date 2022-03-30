<?php include ("../includes/functions.php");?>
<?php include ("../includes/big_bro_functions.php");?>
<?php include ("../db_conf.php");?>
<?php include ("../data/emo_data.php");?>
<?php include ("../sql/project_by_id.php");?>
<?php include ("../sql/RI_Internal_External.php");?>
<?php 
  $action = $_GET['action']; //new
  $temp_id = $_GET['tempid'];
  $user_id = preg_replace("/^.+\\\\/", "", $_SERVER["AUTH_USER"]);
  $ass_project = $row_projID['PROJ_NM'];
?>
<?php 
$forcastDate =  date('m/d/Y');
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Carolino, Gil">
    <title>RePS Reporting - Cox Communications</title>

  <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script>-->
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"> 
  <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
  <link rel="stylesheet" href="steps/style.css" type='text/css'> 

  

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
<body style="background: #F8F8F8; font-family:Mulish, serif;" onload="myFunction(); Namex.value = NameA.value +' '+ Descriptor.value  + ' ' +NameC.value">
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
  <h2>PROJECT RISK</h2>
  Enter the details of your Project Risk
</div>
<div class="finePrint">
<?php  
  //echo "Project UID: " . $row_projID['PROJ_ID'] . "<br>"; 
  //echo "Logged in as: " . $user_id . "<br>"; 
  //echo "Project Owner: " . $row_projID['PROJ_OWNR_NM'] . "<br>"; 
  //echo "Temp ID: " . $_GET['tempid'];
  //echo "Location Code: " . $row_projID['EPSLocation_Cd']; 
?>
</div>
<div style="padding: 20px;">
  <form action="confirm.php" method="post" id="projectRisk"  oninput="Namex.value = NameA.value +' '+ Descriptor.value  + ' ' +NameC.value">

    <input name="changeLogKey" type="hidden" id="changeLogKey" value="2">
    <input name="userId" type="hidden" id="userId " value="<?php echo $user_id ?>">
    <input name="formName" type="hidden" id="formName" value="PRJR">
    <input name="formType" type="hidden" id="formType" value="New">
    <input name="fiscalYer" type="hidden" id="fiscalYer" value="<?php echo $row_projID['FISCL_PLAN_YR'] ?>">
    <input name="RIType" type="hidden" id="RIType" value="Risk">
    <input name="RILevel" type="hidden" id="RILevel" value="Project">
    <input name="program" type="hidden" id="program" value='<?php echo $row_projID['PRGM']; ?>'> <!-- EPS PROGRAM -->
    <input name="RIName" type="hidden" id="RIName" value="">

    <table width="100%" border="0" cellpadding="10" cellspacing="10">
      <tbody>
        <tr>
          <th colspan="3" align="left">
            <div id="myRisk">
              <h4 style="color: #00aaf5">PROJECT RISK</h4>
              </div>
            <div id="myIssue">
              <h4 style="color: #00aaf5">PROJECT ISSUE</h4>
              </div>
            
          </th>
          </tr>
        <tr>
          <td colspan="3" align="left">
			<div class="box">
			<table width="100%" border="0" cellpadding="10" cellspacing="10">
            <tbody>
              <tr>
                <td><div id="myDIV">
                  <label for="Created From">Created From</label>
                  <br>
                  <input name="CreatedFrom" type="text" class="form-control" id="Created From">
                </div></td>
                </tr>
              <tr>
                <td><label for="Created From">Name</label>
                  <br>
                  <input name="Namex" type="text" readonly required="required" class="form-control" id="Namex" >
                  <input name="NameA" type="hidden" id="NameA" value="<?php echo $row_projID['PRGM'] . " " . $row_projID['Sub_Prg'] . " " . $row_projID['EPSLocation_Cd'];?>">
                  <input name="NameC" type="hidden" id="NameC" value="<?php echo "POR" . substr($row_projID['FISCL_PLAN_YR'], -2) ?>">
                </td>
                </tr>
              <tr>
                <td><label for="Created From">Risk Descriptor<br>
                  </label>
                  <input name="Descriptor" type="text" required="required" class="form-control" id="Descriptor" maxlength="30">
                </td>
                </tr>
              <tr>
                <td><label for="Description">Description<br>
            </label>
            <textarea name="Description" cols="120" required="required" class="form-control" id="Description"></textarea>  </td>
                </tr>
            </tbody>
          </table>
		</div>
		</td>
          </tr>
        <tr>
          <td colspan="3" align="left">
            
            </td>
        </tr>

        <tr>
          <td colspan="3" align="left"><h4 style="color: #00aaf5">DRIVERS</h4>
            <div class="box">
              <table width="100%" border="0">
                <tr>
                  <td width="51%"><label>
                    <input type="checkbox" name="Drivers[]" value="Budget/Funding"  id="Drivers_0" class="required_group">
                    Budget/Funding</label></td>
                  <td width="49%"><label>
                    <input type="checkbox" name="Drivers[]" value="External" id="Drivers_10" class="required_group">
                    External</label></td>
                  </tr>
                <tr>
                  <td><label>
                    <input type="checkbox" name="Drivers[]" value="Communication BreakDown" id="Drivers_1" class="required_group">
                    Communications Breakdown</label></td>
                  <td><label>
                    <input type="checkbox" name="Drivers[]" value="People Resource" id="Drivers_6" class="required_group">
                    People Resources</label></td>
                  </tr>
                <tr>
                  <td><label>
                    <input type="checkbox" name="Drivers[]" value="Contractor" id="Drivers_2" class="required_group">
                    Contractor</label></td>
                  <td><label>
                    <input type="checkbox" name="Drivers[]" value="Procurement" id="Drivers_7" class="required_group">
                    Procurement</label></td>
                  </tr>
                <tr>
                  <td><label>
                    <input type="checkbox" name="Drivers[]" value="Dependency Conflict" id="Drivers_3" class="required_group">
                    Dependency Conflict</label></td>
                  <td><label>
                    <input type="checkbox" name="Drivers[]" value="Schedule Impact" id="Drivers_8" class="required_group">
                    Schedule Impact</label></td>
                  </tr>
                <tr>
                  <td><label>
                    <input type="checkbox" name="Drivers[]" value="Equipment Integration" id="Drivers_4" class="required_group">
                    Equipment Integration</label></td>
                  <td><label>
                    <input type="checkbox" name="Drivers[]" value="Other" id="Drivers_9" class="required_group">
                    Other</label></td>
                  </tr>
                </table>
              </div>
          </td>
          </tr>
        <tr>
          <td colspan="3" align="left"></td>
        </tr>
        <tr>
          <td colspan="3" align="left"><h4  style="color: #00aaf5">IMPACT</h4></td>
          </tr>
        <tr>
          <td colspan="3" align="left">
			<div class="box"> 
			<table width="100%" border="0">
            <tbody>

              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>

              <tr>
                <td valign="top">
                  <table width="200" border="0">
                  <tr>
                  <strong>Impacted Area</strong>
                  </tr>
                  <tr>
                    <td><label>
                      <input name="ImpactArea" type="radio"  id="ImpactArea_0" value="1" required>
                      Scope</label></td>
                    </tr>
                  <tr>
                    <td><label>
                      <input type="radio" name="ImpactArea" value="2" id="ImpactArea_1" required>
                      Schedule</label></td>
                    </tr>
                  <tr>
                    <td><label>
                      <input type="radio" name="ImpactArea" value="3" id="ImpactArea_2" required>
                      Budget (Cost Change)</label></td>
                    </tr>
                  </table></td>
                <td valign="top">
                  <table width="200" border="0">
                    <tr>
                      <strong>Impact Level</strong>
                    </tr>
                    <tr>
                      <td><label>
                        <input name="ImpactLevel" type="radio" id="ImpactLevel_0" value="1" required>
                        Minor Impact</label></td>
                      </tr>
                    <tr>
                      <td><label>
                        <input type="radio" name="ImpactLevel" value="2" id="ImpactLevel_1" required>
                        Moderate Impact</label></td>
                      </tr>
                    <tr>
                      <td><label>
                        <input type="radio" name="ImpactLevel" value="3" id="ImpactLevel_2" required>
                        Major Impact</label></td>
                      </tr>
                    <tr>
                      <td><label>
                        <input type="radio" name="ImpactLevel" value="4" id="ImpactLevel_2" required>
                        No Impact</label></td>
                      </tr>
                    
                    </table>
                  </td>
                <td colspan="2" valign="top">
                  <div id="myDIV2">
                    <table width="200" border="0">
                      <tr>
                        <td>
                          <strong>Risk Probability Score</strong>
                          </td>
                        </tr>
                      <!--<tr>
                                <td><label>
                                  <input name="RiskProbability" type="radio" id="ImpactLevel_0" value="1" required>
                                  0% - Risk Only</label></td>
                              </tr>-->
                      <tr>
                        <td><label>
                          <input name="RiskProbability" type="radio" id="ImpactLevel_0" value="2" required>
                          50% 50/50 Chance</label></td>
                        </tr>
                      <tr>
                        <td><label>
                          <input type="radio" name="RiskProbability" value="3" id="ImpactLevel_1" required>
                          75% Highly Likely</label></td>
                        </tr>
                      <tr>
                        <td><label>
                          <input type="radio" name="RiskProbability" value="4" id="ImpactLevel_2" required>
                          99% Almost Certain</label></td>
                        </tr>
                      </table>
                    </div>
                </td>
                </tr>
              </tbody>
          </table>
		</div> 
        </td>
        </tr>
        <tr>
          <td colspan="3" align="left"></td>
        </tr>
        <tr>
          <td colspan="3" align="left"><h4 style="color: #00aaf5">CURRENT TASK POC</h4></td>
          </tr>
        <tr>
          <td colspan="3" align="left">
            <div class="box">
              <label for="Individual">Individual POC<br>
                </label>
              
              <input type="text" list="Individual" name="Individual" class="form-control" id="indy"  onblur="document.getElementById('intern').disabled = (''!=this.value);"/>
              <datalist id="Individual">
                <?php while($row_internal  = sqlsrv_fetch_array( $stmt_internal , SQLSRV_FETCH_ASSOC)) { ?>
                <option><?php echo $row_internal['POC_Nm'] ?></option>
                <?php } ?>
                </datalist>
              
              <h4 align="center">OR</h4>
              <label for="Individual3">Team/Group POC<br>
                </label>
              
              <input type="text" list="InternalExternal" name="InternalExternal" class="form-control" id="intern" onblur="document.getElementById('indy').disabled = (''!=this.value);"/>
              <datalist id="InternalExternal">
                <?php while($row_external  = sqlsrv_fetch_array( $stmt_external , SQLSRV_FETCH_ASSOC)) { ?>
                <option><?php echo $row_external['POC_Nm'] ?></option>
                <?php } ?>
                </datalist>
              </div>
          </td>
          </tr>
        <tr>

          <td colspan="3" align="left">
              
          </td>

          </tr>
        <tr>
          <td colspan="3" align="left">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" align="left">
			<div class="box">
			<table width="100%" border="0">
            <tbody>
              <tr>
                <td colspan="3">
				<label for="date">Forecasted Resolution Date:</label>
				  <div id="dateUnknown" >
				  <input name="date" 
					type="date"
					class="form-control" 
					id="date" 
							value="<?php echo $forcastDate;?>"
							onChange="forCasted()"  
					oninvalid="this.setCustomValidity('You must select a date or check Unknown ')"
					oninput="this.setCustomValidity('')"	 
					> 
              </div>  
				</td>
                </tr>
              <tr>
                <td>
				<div id="forcastedDate">
				<input type="checkbox" 
					name="Unknown" 
					id="Unknown" 
					onChange="unKnown()"
			  >
            <label for="Unknown">Unknown</label>
          </div>  
				</td>
                <td>
					<input type="checkbox" name="TransfertoProgramManager" id="TransfertoProgramManager">
					<label for="TransfertoProgramManager">Transfer to Program Manager</label>  
				</td>
                <td>&nbsp;</td>
              </tr>
            </tbody>
          </table>
			  </div>
			</td>
        </tr>
        <tr>
          <td colspan="3" align="left"></hr></td>
        </tr>
        <tr>
          <td colspan="3" align="left"></td>
        </tr>
        <tr>
          <td colspan="3" align="left"><h4 style="color: #00aaf5">RESPONSE STRATEGY</h4></td>
        </tr>
        <tr>
          <td colspan="3" align="left"><div class="box">
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
              <!--<tr>
              <td>&nbsp;</td>
              <td><label>
                <input type="radio" name="ResponseStrategy" value="5" id="Response_Strategy_3" required>
                Under Review</label></td>
              </tr>-->
              </table>
          </div>			</td>
        </tr>
        <tr>
          <td colspan="3" align="left"><h4 style="color: #00aaf5">ACTION PLAN</h4>
          
          <div class="box">  
            <table width="100%" border="0" cellpadding="5" cellspacing="5">
              <tbody>
                
                  <tr>
                    <td width="100%">
                          
                          <textarea name="ActionPlan" cols="120" required="required" class="form-control" id="ActionPlan"></textarea>  
                          <input type="hidden" name="user" value="<?php echo $user_id ?>">
                          <input type="hidden" name="tempID"value="<?php echo $temp_id ?>">
                    </td>
                  </tr>
                
                <tr>
                  <td>.</td>
                  <td></td>
                </tr>

              </tbody>
</table>
          <div>

          </td>
        </tr>
        <tr>
          <td colspan="3" align="left">
            
        </td>
        </tr>
        <tr>
        <td colspan="3" align="left"><h4 style="color: #00aaf5">PROJECT ASSOCIATION</h4></td>
        </tr>
        <tr>
          <td colspan="3">
        <div class="box" align="left">
          <textarea name="assocProjects" cols="120" id="assocProjects" class="form-control" readonly><?php if(!empty($_POST['proj_select'])) { $proj_select = implode(',', $_POST['proj_select']); $proj_selectx = $proj_select; echo $ass_project . "," . $proj_selectx; } else { echo $ass_project; }?>
          </textarea>
        </div>
		  </td>
        </tr>

        <tr>
          <td colspan="3" align="left"><h4 style="color: #00aaf5">RISK REALIZED</h4></td>
        </tr>
        <tr>
          <td colspan="3" align="left">
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
                    <input type="radio" name="Risk Relized" value="Yes" id="RiskRelized_0">
                    Yes</label></td>
                  <td><label>
                    <input type="radio" name="Risk Relized" value="No" id="RiskRelized_1" checked>
                    No</label></td>
                  </tr>
                </table>
              </div>
			</td>
          </tr>
			  <tr>
			  	<td width="50%">&nbsp;
				</td>
			  </tr>
        <tr>
          <td colspan="3" align="left">
            </td>
        </tr>
        <tr>
          <td colspan="3" align="left"></td>
        </tr>
        <tr>
          <td colspan="3" align="left">
			  <div class="box">
			<table width="100%" border="0">
            <tbody>
              <tr>
                <td colspan="2">
                  <label for="DateClosed">Date Closed:</label>
                  <input type="date" name="DateClosed" id="DateClosed" class="form-control">
                </td>
                </tr>
              <tr>
                <td width="33%">&nbsp;</td>
                <td width="33%" align="center" valign="bottom">&nbsp;</td>
                </tr>
              </tbody>
            </table></div></td>
          </tr>
        <tr>
          <td colspan="3" align="right" valign="middle">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" align="right" valign="middle"><input type="submit" name="submit" id="submit" value="Review >" class="btn btn-primary">
                  <?php if($action == "edit"){ ?>  
                    <a href="" class="btn btn-primary">Email</a>
                  <?php } else { ?>
                    <a href="" class="btn btn-primary" disabled>Email</a>

                  <?php } ?>
                  
                </td>
        </tr>
      </tbody>
    </table>
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
    things[things.length - 1].setCustomValidity("You must check at least one checkbox");
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

</body>
</html>
	  
  