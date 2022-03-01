<?php include ("../includes/functions.php");?>
<?php include ("../db_conf.php");?>
<?php include ("../data/emo_data.php");?>
<?php include ("../sql/project_by_id.php");?>
<?php //include ("../sql/ri_filter_vars.php");?>
<?php //include ("../sql/ri_filters.php");?>
<?php //include ("../sql/ri_filtered_data.php");?>
<?php include ("../sql/RI_Internal_External.php");?>
<?php 
  $action = $_GET['action']; //new
  $temp_id = $_GET['tempid'];
  $user_id = preg_replace("/^.+\\\\/", "", $_SERVER["AUTH_USER"]);

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

  
  <script language="javascript">
	$(document).ready(function() {
    	$('#fiscal_year').multiselect({
          includeSelectAllOption: true,
        });
		$('#pStatus').multiselect({
          includeSelectAllOption: true,
        });
		$('#owner').multiselect({
          includeSelectAllOption: true,
        });
		$('#program').multiselect({
          includeSelectAllOption: true,
        });
		$('#subprogram').multiselect({
          includeSelectAllOption: true,
        });
		$('#region').multiselect({
          includeSelectAllOption: true,
        });
		$('#market').multiselect({
          includeSelectAllOption: true,
        });
    	$('#facility').multiselect({
          includeSelectAllOption: true,
        });
		$('#fiscal_year2').multiselect({
          includeSelectAllOption: true,
        });
		$('#program2').multiselect({
          includeSelectAllOption: true,
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
<body style="background: #F8F8F8; font-family:Mulish, serif;" onload="Namex.value = NameA.value +' '+NameB.value+' '+NameA1.value+' '+NameA2.value+' '+NameC.value">
<main align="center">
<div align="center">
  <h2>PROGRAM ISSUE</h2>
	<!-- <table border="0" cellpadding="5">
	  <tbody>
		<tr>
		  <td align="right" style="padding: 5px;">
        	<a href="#" onclick="myFunction()" class="btn btn-primary">PROJECT RISK</a>
    	</td>
		  <td align="left" style="padding: 5px;">
        	<a href="#" onclick="myFunctionOff()" class="btn btn-primary">PROJECT ISSUE</a>
      </td>
		</tr>
	  </tbody>
	</table> -->
</div>
<div class="finePrint">
<?php  
  //echo "Project UID: " . $row_projID['PROJ_ID'] . "<br>"; 
  //echo "Logged in as: " . $user_id . "<br>"; 
  //echo "Project Owner: " . $row_projID['PROJ_OWNR_NM'] . "<br>"; 
  echo "Temp ID for Associated: " . $_GET['tempid'];
  //echo "Location Code: " . $row_projID['EPSLocation_Cd']; 
?>
</div>
<div style="padding: 20px;">
  <form action="confirm.php" method="post" id="programRisk" name="programRisk"  oninput="Namex.value = NameA.value +' '+NameB.value+' '+NameA1.value+' '+NameA2.value+' '+NameC.value">

  <input name="programs" type="hidden" id="programs" value="<?php echo $row_projID['PRGM'] ?>">
  <input name="userId" type="hidden" id="userId " value="<?php echo $user_id ?>">
  <input name="formName" type="hidden" id="formName" value="PRGI">
  <input name="formType" type="hidden" id="formType" value="New">
  <input name="fiscalYer" type="hidden" id="fiscalYer" value="<?php echo $row_projID['FISCL_PLAN_YR'] ?>">
  <input name="RIType" type="hidden" id="RIType" value="Issue">
  <input name="RILevel" type="hidden" id="RILevel" value="Program">
  <input name="assocProjects" type="hidden" id="assocProjects" value="<?php echo $row_projID['PROJ_NM'] ?>">
  <input name="Descriptor" type="hidden" id="Descriptor" value="">
  <input name="CreatedFrom" type="hidden" id="Created From" value=''>
  <input name="TransfertoProgramManager" type="hidden" id="Created From" value="">
  <input name="RiskProbability" type="hidden" id="RiskProbability" value=''>
  <input name="Risk Relized" type="hidden" id="Risk Relized" value="0">
  
    <table width="100%" border="0" cellpadding="10" cellspacing="10">
      <tbody>
        <tr>
          <th width="50%" align="left">
            <div id="myRisk">
              <h4 style="color: #00aaf5">PROGRAM RISK</h4>
            </div></th>
          <th align="left">&nbsp;</th>
        </tr>
        <tr>
          <td colspan="2" align="left">&nbsp;</td>
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
  <input name="Namex" type="text" readonly required="required" class="form-control" id="Namex" >
  <input name="NameA" type="hidden" id="NameA" value="<?php echo $row_projID['PRGM'] . " " . $row_projID['Sub_Prg'];?>">
  <input name="NameA1" type="hidden" id="NameA1" value="<?php echo $row_projID['SCOP_DESC'];?>">
  <input name="NameB" type="hidden" id="NameB" value=""> <!-- Region -->
  <input name="NameC" type="hidden" id="NameC" value="<?php echo "POR" . substr($row_projID['FISCL_PLAN_YR'], -2) ?>"></td>
              </tr>
              <tr>
                <td><label for="NameA2">Issue Descriptor<br>
                  </label>
                  <input name="NameA2" type="text" required="required" class="form-control" id="NameA2">  </td>
              </tr>
              <tr>
                <td><label for="Description">Description<br>
                  </label>
                  <textarea name="Description" cols="120" rows="6" required="required" class="form-control" id="Description"></textarea>  </td>
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
                    <p><strong>Regions Select
                      </strong><br>
                      <label>
                        <input type="checkbox" name="Region[]" value="All" id="Region" onClick="toggle(this); updatebox()">
                        Select All</label>
                      <br>
                      <label>
                        <input type="checkbox" name="Region[]" value="Corporate" id="Region_6" onClick="updatebox()">
                        Corporate (COR)</label>
                      <br>
                      <label>
                        <input type="checkbox" name="Region[]" value="California" id="Region_0" onClick="updatebox()">
                        California (CA)</label>
                      <br>
                      <label>
                        <input type="checkbox" name="Region[]" value="Central" id="Region_1" onClick="updatebox()">
                        Central (CE)</label>
                      <br>
                      <label>
                        <input type="checkbox" name="Region[]" value="Northeast" id="Region_2" onClick="updatebox()">
                        Northeast (NE)</label>
                      <br>
                      <label>
                        <input type="checkbox" name="Region[]" value="Southeast" id="Region_3" onClick="updatebox()">
                        Southeast (SE)</label>
                      <br>
                      <label>
                        <input type="checkbox" name="Region[]" value="Southwest" id="Region_4" onClick="updatebox()">
                        Southwest (SW)</label>
                      <br>
                      <label>
                        <input type="checkbox" name="Region[]" value="Virginia" id="Region_5" onClick="updatebox()">
                        Virginia (VA)</label>
                      <br>
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
          <td align="left"><h4 style="color: #00aaf5">DRIVERS</h4>
			<div class="box">
            <table width="100%" border="0">
              <tr>
                <td width="51%"><label>
                  <input name="Drivers[]" type="checkbox" id="Drivers_0" value="Budget/Funding">
                Budget/Funding</label></td>
                <td width="49%"><label>
                  <input type="checkbox" name="Drivers[]" value="external" id="External">
                External</label></td>
              </tr>
              <tr>
                <td><label>
                  <input type="checkbox" name="Drivers[]" value="Communication BreakDown" id="Drivers_1">
                Communications Breakdown</label></td>
                <td><label>
                  <input type="checkbox" name="Drivers[]" value="People Resource" id="Drivers_6">
                People Resources</label></td>
              </tr>
              <tr>
                <td><label>
                  <input type="checkbox" name="Drivers[]" value="Contractor" id="Drivers_2">
                Contractor</label></td>
                <td><label>
                  <input type="checkbox" name="Drivers[]" value="Procurement" id="Drivers_7">
                Procurement</label></td>
              </tr>
              <tr>
                <td><label>
                  <input type="checkbox" name="Drivers[]" value="Dependency Conflict" id="Drivers_3">
                Dependency Conflict</label></td>
                <td><label>
                  <input type="checkbox" name="Drivers[]" value="Schedule Impact" id="Drivers_8">
                Schedule Impact</label></td>
              </tr>
              <tr>
                <td><label>
                  <input type="checkbox" name="Drivers[]" value="Equipment Integration" id="Drivers_4">
                Equipment Integration</label></td>
                <td><label>
                  <input type="checkbox" name="Drivers[]" value="Other" id="Drivers_9">
                Other</label></td>
              </tr>
          </table>
		  </div>
		  </td>
          <td align="left">&nbsp;</td>
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
                <td>
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
                <td>
				<div id="myDIV2">
                    <!--<table width="200" border="0">
                              <tr>
                                <td>
                                  <strong>Risk Probability Score</strong>
                                </td>
                              </tr>
                              <tr>
                                <td><label>
                                  <input name="" type="radio" id="ImpactLevel_0" value="1" required>
                                  0% - Risk Only</label></td>
                                </tr>
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
                    </table> -->
                </div>
				</td>
                <td>
                
				        </td>
                </tr>
              </tbody>
          </table>
		</div> 
        </td>
        </tr>
        <tr>
          <td colspan="2" align="left"></td>
        </tr>
        <tr>
          <td align="left"><h4 style="color: #00aaf5">CURRENT TASK POC</h4></td>
          <td align="left">
			  <div style="padding-left: 10px">
			  <h4 style="color: #00aaf5">RESPONSE STRATEGY</h4>
			  </div>
		  </td>
        </tr>
        <tr>
          <td align="left">
			<div class="box">
            <label for="Individual">Individual POC<br>
            </label>

            <input type="text" list="Individual" name="Individual" class="form-control" id="indy"  onblur="document.getElementById('intern').disabled = (''!=this.value);"/>
            <datalist id="Individual">
              <?php while($row_internal  = sqlsrv_fetch_array( $stmt_internal , SQLSRV_FETCH_ASSOC)) { ?>
                <option><?php echo $row_internal['POC_Nm'] ?></option>
              <?php } ?>
            </datalist>

            <h4 align="center">Or</h4>
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
          <td align="left" valign="top">
			  <div style="padding-left: 10px">
			  <div class="box">
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
            <tr>
              <td>&nbsp;</td>
              <td><label>
                <input type="radio" name="ResponseStrategy" value="5" id="Response_Strategy_3" required>
                Under Review</label></td>
              </tr>  
          </table>
			</div>
			</div>
				  </td>
        </tr>
        <tr>
          <td colspan="2" align="left"></hr></td>
        </tr>
        <tr>
          <td colspan="2" align="left"></td>
        </tr>
        <tr>
          <td colspan="2" align="left"><h4 style="color: #00aaf5">ACTION PLAN</h4>
          
          <div class="box">  

            <!--<iframe 
              src="includes/action-plans.php?uid=<?php //echo $_GET['uid'];?>&fiscal_year=<?php //echo $row_projID['FISCL_PLAN_YR'] ?>&tempid=<?php //echo $temp_id?>&username=<?php //echo $user_id ?>" 
              height="200" 
              width="1300" 
              title="Associated Projects"  
              frameBorder="0" 
              scrolling="yes">
            </iframe>		-->
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
                <!-- <tr>
                  <td colspan="2" align="left">
                  <strong>Action Plan Status Log</strong>  
                    table width="100%" border="0" cellpadding="5" cellspacing="5" class="table table-bordered table-hover">
                    <tbody>
                      <tr>
                        <th width="24%" bgcolor="#EFEFEF">User</th>
                        <th width="55%" bgcolor="#EFEFEF">Update</th>
                        <th width="21%" bgcolor="#EFEFEF">Timestamp</th>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                    </tbody>
                    </table> 

                  </td>
                </tr> -->
              </tbody>
</table>
          <div>

          </td>
        </tr>
        <tr>
          <td align="left"></td>
          <td align="left">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" align="left">
            
        </td>
        </tr>
        <tr>
        <td colspan="2" align="left"><h4 style="color: #00aaf5">PROJECT ASSOCIATION</h4></td>
        </tr>
        <tr>
          <td colspan="2">
        <div class="box">
				<!--<iframe 
            src="includes/associated_prj.php?uid=<?php echo $_GET['uid'];?>&fiscal_year=<?php echo $row_projID['FISCL_PLAN_YR'] ?>" 
            height="300" 
            width="1300" 
            title="Associated Projects"  
            frameBorder="0" 
            scrolling="yes">
        </iframe>-->
        <textarea name="assocProjects" cols="120" id="assocProjects" class="form-control" readonly><?php if(!empty($_POST['proj_select'])) { $proj_select = implode(',', $_POST['proj_select']); $proj_selectx = $proj_select; echo $proj_selectx; }?></textarea> 
        </div>
		  </td>
        </tr>

        <tr>
          <td colspan="2" align="left"><h4 style="color: #00aaf5">RELATED DATES</h4></td>
        </tr>
        <tr>
          <td align="left">
          <div class="box">
          <div id="dateUnknown">
              <label for="date">Forecasted Resolution Date:</label>
              <input name="date" 
                  type="date"
                  class="form-control" 
                  id="date" 
                  value="2022-01-01"
                  onChange="forCasted()"  
                  oninvalid="this.setCustomValidity('You must select a date or check Unknown ')"
                  oninput="this.setCustomValidity('')">
          </div>
          <div id="forcastedDate">
              <input type="checkbox" 
                  name="Unknown" 
                  id="Unknown" 
                  onChange="unKnown()">
              <label for="Unknown">Unknown</label>
          </div>
          </div><br>
			</td>
      <td align="left" valign="top" style="padding-left: 10px">
      <div class="box">
			  <label for="Created From">Associated CR ID</label>
        <input name="CreatedFrom" type="text" class="form-control" id="Created From">
      </div>
      </td>
        </tr>
        <tr>
          <td align="left">
            <div class="box">
              <table width="100%" border="0">
                <tbody>
                  <tr>
                    <td colspan="2">
                      <label for="DateClosed">Date Closed:</label>
                      <input type="date" name="DateClosed" id="DateClosed" class="form-control">
                      <!-- <input type="checkbox" name="TransfertoProgramManager2" id="TransfertoProgramManager2"> -->
                      <!-- <label for="TransfertoProgramManager2">Transfer to Program Manager</label> -->
                    </td>
                    </tr>
                  <tr>
                    <td width="33%">&nbsp;</td>
                    <td width="33%" align="center" valign="bottom">&nbsp;</td>
                    </tr>
                  </tbody>
            </table></div></td>
          <td colspan="2" align="right" valign="middle">
          <input type="submit" name="submit" id="submit" value="Review" class="btn btn-primary">
                  <?php if($action == "edit"){ ?>  
                    <a href="" class="btn btn-primary">Email</a>
                  <?php } else { ?>
                    <a href="" class="btn btn-primary" disabled>Email</a>
                  <?php } ?>
          </td>
        </tr>
        <tr>
          <td>
            
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
<SCRIPT LANGUAGE="JavaScript">
<!-- 	
<!-- Begin
function CheckAll(chk)
{
for (i = 0; i < chk.length; i++)
	chk[i].checked = true ;
}

function UnCheckAll(chk)
{
for (i = 0; i < chk.length; i++)
	chk[i].checked = false ;
}
//  End -->
</script>
<script language="JavaScript">
function toggle(source) {
  checkboxes = document.getElementsByName('Region[]');
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
  }
}

function updatebox()
{
    var textbox = document.getElementById("NameB");
    var values = [];

    if(document.getElementById('Region_0').checked && 
      document.getElementById('Region_1').checked && 
      document.getElementById('Region_2').checked && 
      document.getElementById('Region_3').checked && 
      document.getElementById('Region_4').checked && 
      document.getElementById('Region_5').checked &&
      document.getElementById('Region_6').checked 
      ) {
        values.push("All");
    } 

    else if(document.getElementById('Region_1').checked && 
    (
      document.getElementById('Region_0').checked || 
      document.getElementById('Region_2').checked || 
      document.getElementById('Region_3').checked || 
      document.getElementById('Region_4').checked || 
      document.getElementById('Region_5').checked ||
      document.getElementById('Region_6').checked
    )) {
        values.push("Multi");
    }

    else if(document.getElementById('Region_2').checked && 
    (
      document.getElementById('Region_1').checked || 
      document.getElementById('Region_0').checked || 
      document.getElementById('Region_3').checked || 
      document.getElementById('Region_4').checked || 
      document.getElementById('Region_5').checked ||
      document.getElementById('Region_6').checked
    )) {
        values.push("Multi");
    }

    else if(document.getElementById('Region_3').checked && 
    (
      document.getElementById('Region_1').checked || 
      document.getElementById('Region_2').checked || 
      document.getElementById('Region_0').checked || 
      document.getElementById('Region_4').checked || 
      document.getElementById('Region_5').checked ||
      document.getElementById('Region_6').checked
    )) {
        values.push("Multi");
    }

    else if(document.getElementById('Region_4').checked && 
    (
      document.getElementById('Region_1').checked || 
      document.getElementById('Region_2').checked || 
      document.getElementById('Region_3').checked || 
      document.getElementById('Region_0').checked || 
      document.getElementById('Region_5').checked ||
      document.getElementById('Region_6').checked
    )) {
        values.push("Multi");
    }

    else if(document.getElementById('Region_5').checked && 
    (
      document.getElementById('Region_1').checked || 
      document.getElementById('Region_2').checked || 
      document.getElementById('Region_3').checked || 
      document.getElementById('Region_4').checked || 
      document.getElementById('Region_0').checked ||
      document.getElementById('Region_6').checked
    )) {
        values.push("Multi");
    }

    else if(document.getElementById('Region_6').checked && 
    (
      document.getElementById('Region_1').checked || 
      document.getElementById('Region_2').checked || 
      document.getElementById('Region_3').checked || 
      document.getElementById('Region_4').checked || 
      document.getElementById('Region_5').checked ||
      document.getElementById('Region_0').checked
    )) {
        values.push("Multi");
    }
   
    else if(document.getElementById('Region').checked) {
        values.push("All");
    }

    else if(document.getElementById('Region_0').checked) {
        values.push("CA");
    }

    else if(document.getElementById('Region_1').checked) {
        values.push("CE");
    }

    else if(document.getElementById('Region_2').checked) {
        values.push("NE");
    }

    else if(document.getElementById('Region_3').checked) {
        values.push("SE");
    }

    else if(document.getElementById('Region_4').checked) {
        values.push("SW");
    }

    else if(document.getElementById('Region_5').checked) {
        values.push("VA");
    }

    else if(document.getElementById('Region_6').checked) {
        values.push("COR");
    }

    
    textbox.value = values.join(" ");
}
</script>
</body>
</html>