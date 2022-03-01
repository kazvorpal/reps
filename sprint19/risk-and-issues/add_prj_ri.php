<?php include ("../includes/functions.php");?>
<?php include ("../db_conf.php");?>
<?php include ("../data/emo_data.php");?>
<?php // include ("../sql/collapse.php");?>
<?php include ("../sql/project_by_id.php");?>
<?php include ("../sql/ri_filter_vars.php");?>
<?php include ("../sql/ri_filters.php");?>
<?php include ("../sql/ri_filtered_data.php");?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Carolino, Gil">
    <title>RePS Reporting - Cox Communications</title>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script>
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

</head>
<body>
<main align="center">
  <div>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Project Risk</a></li>
    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Project Issue</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <?php echo $row_projID['PROJ_ID'] ?>
    <div class="progress">
  <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
</div>

    <div role="tabpanel" class="tab-pane fade in" id="home">
 <div style="padding: 20px;">
<form action="confirm.php" method="post" id="projectRisk">
  <table width="100%" border="0" cellpadding="10" cellspacing="10">
    <tbody>
      <tr>
        <th align="left"><h4 style="color: #00aaf5">PROJECT RISK</h4></th>
        <th colspan="2" align="left">&nbsp;</th>
      </tr>
      <tr>
        <td width="50%" align="left">
			<label for="Created From">Name</label>
			<br>
<input name="Namex" type="text" disabled="disabled" required="required" class="form-control" id="Namex" value="<?php echo $row_projID['PROJ_NM'] ?>">
<input name="Name" type="hidden" id="Name" value="<?php echo $row_projID['PROJ_NM'] ?>">
		</td>
        <td colspan="2" align="left">
			<label for="Created From">Created From</label>
			<br>
        	<input name="CreatedFrom" type="text" class="form-control" id="Created From">			
		</td>
      </tr>
      <tr>
        <td align="left">
		 	<label for="Created From">Issue Descriptor<br>
		 	</label>
        	<input name="Descriptor" type="text" required="required" class="form-control" id="Descriptor">
		</td>
        <td colspan="2" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3" align="left">
			<label for="Description">Description<br>
			</label>
  			<textarea name="Description" cols="120" required="required" class="form-control" id="Description"></textarea>  
		</td>
        </tr>
      <tr>
        <td colspan="3" align="left"><hr></td>
        </tr>
      <tr>
        <td align="left"><h4 style="color: #00aaf5">DRIVERS</h4>
          <table width="100%" border="0">
          <tr>
            <td width="60%"><label>
              <input name="Drivers[]" type="checkbox" id="Drivers_0" value="Budget/Funding">
              Budget/Funding</label></td>
            <td width="39%"><label>
              <input type="checkbox" name="Drivers[]" value="external" id="External">
              External</label></td>
            </tr>
          <tr>
            <td><label>
              <input type="checkbox" name="Drivers[]" value="Communications Breakdown" id="Drivers_1">
              Communications Breakdown</label></td>
            <td><label>
              <input type="checkbox" name="Drivers[]" value="People Resources" id="Drivers_6">
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
        </table></td>
        <td colspan="2" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3" align="left"><hr></td>
        </tr>
      <tr>
        <td align="left"><h4  style="color: #00aaf5">IMPACT</h4></td>
        <td colspan="2" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td align="left"><table width="100%" border="0">
          <tbody>
            <tr>
              <td width="61%"><strong>Impacted Area</strong></td>
              <td width="39%"><strong>Impact Level</strong></td>
              </tr>
            <tr>
              <td width="60%"><table width="200" border="0">
                <tr>
                  <td><label>
                    <input name="ImpactArea" type="radio" id="ImpactArea_0" value="Scope">
                    Scope</label></td>
                  </tr>
                <tr>
                  <td><label>
                    <input type="radio" name="ImpactArea" value="Schedule" id="ImpactArea_1">
                    Schedule</label></td>
                  </tr>
                <tr>
                  <td><label>
                    <input type="radio" name="ImpactArea" value="Budget-Cost Change" id="ImpactArea_2">
                    Budget (Cost Change)</label></td>
                  </tr>
                </table></td>
              <td><table width="200" border="0">
                <tr>
                  <td><label>
                    <input name="ImpactLevel" type="radio" id="ImpactLevel_0" value="Minor Impact">
                    Minor Impact</label></td>
                  </tr>
                <tr>
                  <td><label>
                    <input type="radio" name="ImpactLevel" value="Moderate Impact" id="ImpactLevel_1">
                    Moderate Impact</label></td>
                  </tr>
                <tr>
                  <td><label>
                    <input type="radio" name="ImpactLevel" value="Major Impact" id="ImpactLevel_2">
                    Major Impact</label></td>
                  </tr>

                </table></td>
              </tr>
            </tbody>
          </table></td>
        <td colspan="2" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3" align="left"><hr></td>
      </tr>
      <tr>
        <td align="left"><h4 style="color: #00aaf5">CURRENT TASK POC</h4></td>
        <td colspan="2" align="left"><h4 style="color: #00aaf5">RESPONSE STRATEGY</h4></td>
      </tr>
      <tr>
        <td align="left"><label for="Individual">Individual<br>
        </label>
          <select name="Individual" id="Individual" class="form-control">
          </select><br>
          -And/Or <br>
          <br>
          <label for="Individual3">Internal/External<br>
          </label>
          <select name="InternalExternal" id="InternalExternal" class="form-control">
          </select></td>
        <td colspan="2" align="left" valign="top"><table width="246" border="0" cellpadding="5" cellspacing="5">
          <tr>
            <td>&nbsp;</td>
            <td><label>
              <input type="radio" name="ResponseStrategy" value="Avoid" id="Response_Strategy_0">
              Avoid</label></td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td><label>
              <input type="radio" name="ResponseStrategy" value="Mitigate" id="Response_Strategy_1">
              Mitigate</label></td>
            </tr>
          <tr>
            <td width="16">&nbsp;</td>
            <td width="195"><label>
              <input type="radio" name="ResponseStrategy" value="Transfer" id="Response_Strategy_2">
              Transfer</label></td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td><label>
              <input type="radio" name="ResponseStrategy" value="Accept" id="Response_Strategy_3">
              Accept</label></td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td align="left"><label for="date">Date:</label>
          <input name="date" type="date" required="required" class="form-control" id="date"> </td>
        <td align="center" valign="bottom"><input type="checkbox" name="Unknown" id="Unknown">
          <label for="Unknown">Unknown</label></td>
        <td align="center" valign="bottom"><input type="checkbox" name="TransfertoProgramManager" id="TransfertoProgramManager">
          <label for="TransfertoProgramManager">Transfer to Program Manager</label></td>
      </tr>
      <tr>
        <td colspan="3" align="left"></hr></td>
        </tr>
      <tr>
        <td colspan="3" align="left"><hr></td>
      </tr>
      <tr>
        <td align="left"><h4 style="color: #00aaf5">PROJECT ASSOCIATION</h4></td>
        <td colspan="2" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td align="left"><strong>Associated Projects</strong></td>
        <td colspan="2" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3" align="center">

<!--- <form action="" method="post" class="navbar-form navbar-center" id="formfilter" title="formfilter"> --->
<table cellspacing="0" cellpadding="0">
  <tbody>
    <tr align="center">
      <?php  //if($fiscal_year !=0) { ?>
      <td height="23">Fiscal Year</td>
      <td>Program</td>
      <td>Subprogram</td>
      <td>Region</td>
      <td>Market</td>
      <td>Facility</td>
    <?php // } ?>
      <td>
		  <input name="pStatus" type="hidden" value="Active">
		  <input name="Owner" type="hidden" value="get owner">
	  </td>
      <td>&nbsp;</td>
    </tr>
    <tr>
    <td><select name="fiscal_year[]"  class="form-control form-group-sm" id="fiscal_year" title="Move this selection back to Fiscal Year to clear this filter" require <?php //if(isset($_POST['fiscal_year'])) { fltrSet($_POST['fiscal_year']); }?>>
      <!--<option value="All">Select Fiscal Year</option>-->
      <?php while($row_fiscal_year = sqlsrv_fetch_array( $stmt_fiscal_year, SQLSRV_FETCH_ASSOC)) { ?>
      <option value="<?php echo $row_fiscal_year['FISCL_PLAN_YR'];?>"<?php if($row_fiscal_year['FISCL_PLAN_YR'] == '2021' ) {?> selected="selected" <?php } ?>><?php echo $row_fiscal_year['FISCL_PLAN_YR'];?></option>
      <?php } ?>
    </select></td>

      <td><select name="program[]" multiple="multiple" class="form-control form-group-sm" id="program" title="Move this selection back to SELECT PROGRAM to clear this filter" <?php //fltrSet($_POST['program'])?>>
        <!--<option value="">Select Program</option>-->
        <?php while($row_program_n = sqlsrv_fetch_array( $stmt_program_n, SQLSRV_FETCH_ASSOC)) { ?>
        <option value="<?php echo $row_program_n['PRGM'];?>" <?php if($fiscal_year != 0) {echo 'selected="selected"';} ?>><?php echo $row_program_n['PRGM'];?></option>
        <?php } ?>
      </select></td>

      <td><select name="subprogram[]" multiple="multiple" id="subprogram" title="Move this selection back to SELECT SUBPROGRAM to clear this filter" class="form-control" <?php //fltrSet($_POST['subprogram'])?>>
        <!--<option value="">Select Subprogram</option>-->
        <?php while($row_subprog = sqlsrv_fetch_array( $stmt_subprogram, SQLSRV_FETCH_ASSOC)) { ?>
        <option value="<?php echo $row_subprog['Sub_Prg'];?>" <?php if($fiscal_year != 0) {echo 'selected="selected"';} ?>><?php echo $row_subprog['Sub_Prg'];?> </option>
        <?php } ?>
      </select></td>

      <td><select name="region[]" multiple="multiple" id="region" title="Move this selection back to SELECT REGION to clear this filter" class="form-control"  <?php //fltrSet($_POST['region'])?>>
        <!--<option value="">Select Region</option>-->
        <?php while($row_region_drop = sqlsrv_fetch_array( $stmt_region_drop, SQLSRV_FETCH_ASSOC)) { ?>
        <option value="<?php echo $row_region_drop['Region'];?>" <?php if($fiscal_year != 0) {echo 'selected="selected"';} ?>><?php echo $row_region_drop['Region'];?></option>
        <?php } ?>
      </select></td>

      <td><select name="market[]" multiple="multiple" class="form-control" id="market" title="Move this selection back to SELECT MARKET to clear this filter" <?php //fltrSet($_POST['market'])?>>
        <!--<option value="">Select Market</option>-->
        <?php while($row_market_drop = sqlsrv_fetch_array( $stmt_market_drop, SQLSRV_FETCH_ASSOC)) { ?>
        <option value="<?php echo $row_market_drop['Market'];?>" <?php if($fiscal_year != 0) {echo 'selected="selected"';} ?>> <?php echo $row_market_drop['Market'];?></option>
        <?php } ?>
      </select></td>

      <td><select name="facility[]" multiple="multiple" class="form-control" id="facility" title="Move this selection back to SELECT MARKET to clear this filter" <?php //fltrSet($_POST['facility'])?>>
        <!--<option value="">Select Facility</option>-->
        <?php while($row_facility_drop = sqlsrv_fetch_array( $stmt_facility_drop, SQLSRV_FETCH_ASSOC)) { ?>
        <option value="<?php echo $row_facility_drop['Facility'];?>" <?php if($fiscal_year != 0) {echo 'selected="selected"';} ?>> <?php echo $row_facility_drop['Facility'];?></option>
        <?php } ?>
      </select></td>

      <td><input name="Go" type="submit" id="Go" form="formfilter" value="Submit" class="btn btn-primary"></td>
      <td><a href="esp-status-details-index.php" title="Clear all filters"><span class="btn btn-default">Clear</span></a>    </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </tbody>
</table>  
<!--- </form>		--->
		</td>
      </tr>
      <tr>
        <td colspan="3" align="left"><table width="100%" border="0" cellpadding="5" cellspacing="5" class="table table-bordered table-hover">
          <tbody>
            <tr>
              <th bgcolor="#EFEFEF"><input type="checkbox" name="checkbox" id="checkbox" onClick="toggle(this)"></th>
              <th bgcolor="#EFEFEF">Project Name</th>
              <th bgcolor="#EFEFEF">Program</th>
              <th bgcolor="#EFEFEF">Region</th>
              <th bgcolor="#EFEFEF">Market</th>
              <th bgcolor="#EFEFEF">Facility</th>
            </tr>
            <?php while ($row_por = sqlsrv_fetch_array( $stmt_por, SQLSRV_FETCH_ASSOC)) { ?>
              <tr>
                <td><input type="checkbox" name="proj_select" id="proj_select" value="uid"></td>
                <td><?php $row_por['PROJ_NM'] ?></td>
                <td><?php $row_por['PROGM'] ?></td>
                <td><?php $row_por['Sub_Prog'] ?></td>
                <td><?php $row_por['Market'] ?></td>
                <td><?php $row_por['Facility'] ?></td>
              </tr>
            <?php } ?>
          </tbody>
        </table></td>
        </tr>
      <tr>
        <td align="left"><strong>Selected Associated Projects</strong></td>
        <td colspan="2" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3" align="left">
			<table width="100%" border="0" cellpadding="5" cellspacing="5" class="table table-bordered table-hover">
          <tbody>
            <tr>
              <th bgcolor="#EFEFEF">Program</th>
              <th bgcolor="#EFEFEF">Sub Program</th>
              <th bgcolor="#EFEFEF">Region</th>
              <th bgcolor="#EFEFEF">Market</th>
              <th bgcolor="#EFEFEF">Facility</th>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </tbody>
        </table></td>
        </tr>
      <tr>
        <td colspan="3" align="left"><hr></td>
      </tr>
      <tr>
        <td colspan="3" align="left"><label for="Action Plan">Action Plan</label>
          <br>
          <input type="text" name="ActionPlan" id="Action Plan" class="form-control" required="required"></td>
      </tr>
      <tr>
        <td align="left"><h4>ACTION PLAN STATUS LOG</h4></td>
        <td colspan="2" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3" align="left"><table width="100%" border="0" cellpadding="5" cellspacing="5" class="table table-bordered table-hover">
          <tbody>
            <tr>
              <th width="24%" bgcolor="#EFEFEF">User</th>
              <th width="55%" bgcolor="#EFEFEF">Change</th>
              <th width="21%" bgcolor="#EFEFEF">Timestamp</th>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </tbody>
        </table></td>
      </tr>
      <tr>
        <td colspan="3" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3" align="left"><hr></td>
        </tr>
      <tr>
        <td align="left"><label for="DateClosed">Date Closed:</label>
          <input type="date" name="DateClosed" id="DateClosed" class="form-control">
        </td>
        <td colspan="2" align="center" valign="bottom">
          <input type="submit" name="submit" id="submit" value="Submit" class="btn btn-primary">
          <a href="" class="btn btn-primary">Email</a>
        </td>
      </tr>
      <tr>
        <td align="left">&nbsp;</td>
        <td colspan="2" align="center" valign="bottom">&nbsp;</td>
      </tr>
    </tbody>
  </table>
</form>
</div>
	  
	</div>
    <div role="tabpanel" class="tab-pane fade in active" id="profile">
	
<div style="padding: 20px;">
<form action="confirm.php" method="post" id="projectRisk">
  <table width="100%" border="0" cellpadding="10" cellspacing="10">
    <tbody>
      <tr>
        <th align="left"><h4 style="color: #00aaf5">PROJECT ISSUE</h4></th>
        <th colspan="2" align="left">&nbsp;</th>
      </tr>
      <tr>
        <td width="50%" align="left">
			<label for="Created From">Name</label>
			<br>
<input name="Namex" type="text" disabled="disabled" required="required" class="form-control" id="Namex" value="<?php echo $row_projID['PROJ_NM'] ?>">
<input name="Name" type="hidden" id="Name" value="<?php echo $row_projID['PROJ_NM'] ?>">
		</td>
        <td colspan="2" align="left">
			<label for="Created From">Created From</label>
			<br>
        	<input name="CreatedFrom" type="text" class="form-control" id="Created From">			
		</td>
      </tr>
      <tr>
        <td align="left">
		 	<label for="Created From">Issue Descriptor<br>
		 	</label>
        	<input name="Descriptor" type="text" required="required" class="form-control" id="Descriptor">
		</td>
        <td colspan="2" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3" align="left">
			<label for="Description">Description<br>
			</label>
  			<textarea name="Description" cols="120" required="required" class="form-control" id="Description"></textarea>  
		</td>
        </tr>
      <tr>
        <td colspan="3" align="left"><hr></td>
        </tr>
      <tr>
        <td align="left"><h4 style="color: #00aaf5">DRIVERS</h4>
          <table width="100%" border="0">
          <tr>
            <td width="60%"><label>
              <input name="Drivers[]" type="checkbox" id="Drivers_0" value="Budget/Funding">
              Budget/Funding</label></td>
            <td width="39%"><label>
              <input type="checkbox" name="Drivers[]" value="external" id="External">
              External</label></td>
            </tr>
          <tr>
            <td><label>
              <input type="checkbox" name="Drivers[]" value="Communications Breakdown" id="Drivers_1">
              Communications Breakdown</label></td>
            <td><label>
              <input type="checkbox" name="Drivers[]" value="People Resources" id="Drivers_6">
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
        </table></td>
        <td colspan="2" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3" align="left"><hr></td>
        </tr>
      <tr>
        <td align="left"><h4  style="color: #00aaf5">IMPACT</h4></td>
        <td colspan="2" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td align="left"><table width="100%" border="0">
          <tbody>
            <tr>
              <td width="61%"><strong>Impacted Area</strong></td>
              <td width="39%"><strong>Impact Level</strong></td>
              </tr>
            <tr>
              <td width="60%"><table width="200" border="0">
                <tr>
                  <td><label>
                    <input name="ImpactArea" type="radio" id="ImpactArea_0" value="Scope">
                    Scope</label></td>
                  </tr>
                <tr>
                  <td><label>
                    <input type="radio" name="ImpactArea" value="Schedule" id="ImpactArea_1">
                    Schedule</label></td>
                  </tr>
                <tr>
                  <td><label>
                    <input type="radio" name="ImpactArea" value="Budget-Cost Change" id="ImpactArea_2">
                    Budget (Cost Change)</label></td>
                  </tr>
                </table></td>
              <td><table width="200" border="0">
                <tr>
                  <td><label>
                    <input name="ImpactLevel" type="radio" id="ImpactLevel_0" value="Minor Impact">
                    Minor Impact</label></td>
                  </tr>
                <tr>
                  <td><label>
                    <input type="radio" name="ImpactLevel" value="Moderate Impact" id="ImpactLevel_1">
                    Moderate Impact</label></td>
                  </tr>
                <tr>
                  <td><label>
                    <input type="radio" name="ImpactLevel" value="Major Impact" id="ImpactLevel_2">
                    Major Impact</label></td>
                  </tr>

                </table></td>
              </tr>
            </tbody>
          </table></td>
        <td colspan="2" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3" align="left"><hr></td>
      </tr>
      <tr>
        <td align="left"><h4 style="color: #00aaf5">CURRENT TASK POC</h4></td>
        <td colspan="2" align="left"><h4 style="color: #00aaf5">RESPONSE STRATEGY</h4></td>
      </tr>
      <tr>
        <td align="left"><label for="Individual">Individual<br>
        </label>
          <select name="Individual" id="Individual" class="form-control">
          </select><br>
          -And/Or <br>
          <br>
          <label for="Individual3">Internal/External<br>
          </label>
          <select name="InternalExternal" id="InternalExternal" class="form-control">
          </select></td>
        <td colspan="2" align="left" valign="top"><table width="246" border="0" cellpadding="5" cellspacing="5">
          <tr>
            <td>&nbsp;</td>
            <td><label>
              <input type="radio" name="ResponseStrategy" value="Avoid" id="Response_Strategy_0">
              Avoid</label></td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td><label>
              <input type="radio" name="ResponseStrategy" value="Mitigate" id="Response_Strategy_1">
              Mitigate</label></td>
            </tr>
          <tr>
            <td width="16">&nbsp;</td>
            <td width="195"><label>
              <input type="radio" name="ResponseStrategy" value="Transfer" id="Response_Strategy_2">
              Transfer</label></td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td><label>
              <input type="radio" name="ResponseStrategy" value="Accept" id="Response_Strategy_3">
              Accept</label></td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td align="left"><label for="date">Date:</label>
          <input name="date" type="date" required="required" class="form-control" id="date"> </td>
        <td align="center" valign="bottom"><input type="checkbox" name="Unknown" id="Unknown">
          <label for="Unknown">Unknown</label></td>
        <td align="center" valign="bottom"><input type="checkbox" name="TransfertoProgramManager" id="TransfertoProgramManager">
          <label for="TransfertoProgramManager">Transfer to Program Manager</label></td>
      </tr>
      <tr>
        <td colspan="3" align="left"></hr></td>
        </tr>
      <tr>
        <td colspan="3" align="left"><hr></td>
      </tr>
      <tr>
        <td align="left"><h4 style="color: #00aaf5">PROJECT ASSOCIATION</h4></td>
        <td colspan="2" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td align="left"><strong>Associated Projects</strong></td>
        <td colspan="2" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3" align="center">

<!--- <form action="" method="post" class="navbar-form navbar-center" id="formfilter" title="formfilter"> --->
<table cellspacing="0" cellpadding="0">
  <tbody>
    <tr align="center">
      <?php  //if($fiscal_year !=0) { ?>
      <td height="23">Fiscal Year</td>
      <td>Program</td>
      <td>Subprogram</td>
      <td>Region</td>
      <td>Market</td>
      <td>Facility</td>
    <?php // } ?>
      <td>
		  <input name="pStatus" type="hidden" value="Active">
		  <input name="Owner" type="hidden" value="get owner">
	  </td>
      <td>&nbsp;</td>
    </tr>
    <tr>
    <td><select name="fiscal_year[]"  class="form-control form-group-sm" id="fiscal_year" title="Move this selection back to Fiscal Year to clear this filter" require <?php //if(isset($_POST['fiscal_year'])) { fltrSet($_POST['fiscal_year']); }?>>
      <!--<option value="All">Select Fiscal Year</option>-->
      <?php while($row_fiscal_year = sqlsrv_fetch_array( $stmt_fiscal_year, SQLSRV_FETCH_ASSOC)) { ?>
      <option value="<?php echo $row_fiscal_year['FISCL_PLAN_YR'];?>"<?php if($row_fiscal_year['FISCL_PLAN_YR'] == '2021' ) {?> selected="selected" <?php } ?>><?php echo $row_fiscal_year['FISCL_PLAN_YR'];?></option>
      <?php } ?>
    </select></td>

      <td><select name="program[]" multiple="multiple" class="form-control form-group-sm" id="program" title="Move this selection back to SELECT PROGRAM to clear this filter" <?php //fltrSet($_POST['program'])?>>
        <!--<option value="">Select Program</option>-->
        <?php while($row_program_n = sqlsrv_fetch_array( $stmt_program_n, SQLSRV_FETCH_ASSOC)) { ?>
        <option value="<?php echo $row_program_n['PRGM'];?>" <?php if($fiscal_year != 0) {echo 'selected="selected"';} ?>><?php echo $row_program_n['PRGM'];?></option>
        <?php } ?>
      </select></td>

      <td><select name="subprogram[]" multiple="multiple" id="subprogram" title="Move this selection back to SELECT SUBPROGRAM to clear this filter" class="form-control" <?php //fltrSet($_POST['subprogram'])?>>
        <!--<option value="">Select Subprogram</option>-->
        <?php while($row_subprog = sqlsrv_fetch_array( $stmt_subprogram, SQLSRV_FETCH_ASSOC)) { ?>
        <option value="<?php echo $row_subprog['Sub_Prg'];?>" <?php if($fiscal_year != 0) {echo 'selected="selected"';} ?>><?php echo $row_subprog['Sub_Prg'];?> </option>
        <?php } ?>
      </select></td>

      <td><select name="region[]" multiple="multiple" id="region" title="Move this selection back to SELECT REGION to clear this filter" class="form-control"  <?php //fltrSet($_POST['region'])?>>
        <!--<option value="">Select Region</option>-->
        <?php while($row_region_drop = sqlsrv_fetch_array( $stmt_region_drop, SQLSRV_FETCH_ASSOC)) { ?>
        <option value="<?php echo $row_region_drop['Region'];?>" <?php if($fiscal_year != 0) {echo 'selected="selected"';} ?>><?php echo $row_region_drop['Region'];?></option>
        <?php } ?>
      </select></td>

      <td><select name="market[]" multiple="multiple" class="form-control" id="market" title="Move this selection back to SELECT MARKET to clear this filter" <?php //fltrSet($_POST['market'])?>>
        <!--<option value="">Select Market</option>-->
        <?php while($row_market_drop = sqlsrv_fetch_array( $stmt_market_drop, SQLSRV_FETCH_ASSOC)) { ?>
        <option value="<?php echo $row_market_drop['Market'];?>" <?php if($fiscal_year != 0) {echo 'selected="selected"';} ?>> <?php echo $row_market_drop['Market'];?></option>
        <?php } ?>
      </select></td>

      <td><select name="facility[]" multiple="multiple" class="form-control" id="facility" title="Move this selection back to SELECT MARKET to clear this filter" <?php //fltrSet($_POST['facility'])?>>
        <!--<option value="">Select Facility</option>-->
        <?php while($row_facility_drop = sqlsrv_fetch_array( $stmt_facility_drop, SQLSRV_FETCH_ASSOC)) { ?>
        <option value="<?php echo $row_facility_drop['Facility'];?>" <?php if($fiscal_year != 0) {echo 'selected="selected"';} ?>> <?php echo $row_facility_drop['Facility'];?></option>
        <?php } ?>
      </select></td>

      <td><input name="Go" type="submit" id="Go" form="formfilter" value="Submit" class="btn btn-primary"></td>
      <td><a href="esp-status-details-index.php" title="Clear all filters"><span class="btn btn-default">Clear</span></a>    </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </tbody>
</table>  
<!--- </form>		--->
		</td>
      </tr>
      <tr>
        <td colspan="3" align="left"><table width="100%" border="0" cellpadding="5" cellspacing="5" class="table table-bordered table-hover">
          <tbody>
            <tr>
              <th bgcolor="#EFEFEF"><input type="checkbox" name="checkbox" id="checkbox" onClick="toggle(this)"></th>
              <th bgcolor="#EFEFEF">Project Name</th>
              <th bgcolor="#EFEFEF">Program</th>
              <th bgcolor="#EFEFEF">Region</th>
              <th bgcolor="#EFEFEF">Market</th>
              <th bgcolor="#EFEFEF">Facility</th>
            </tr>
            <?php while ($row_por = sqlsrv_fetch_array( $stmt_por, SQLSRV_FETCH_ASSOC)) { ?>
              <tr>
                <td><input type="checkbox" name="proj_select" id="proj_select" value="uid"></td>
                <td><?php $row_por['PROJ_NM'] ?></td>
                <td><?php $row_por['PROGM'] ?></td>
                <td><?php $row_por['Sub_Prog'] ?></td>
                <td><?php $row_por['Market'] ?></td>
                <td><?php $row_por['Facility'] ?></td>
              </tr>
            <?php } ?>
          </tbody>
        </table></td>
        </tr>
      <tr>
        <td align="left"><strong>Selected Associated Projects</strong></td>
        <td colspan="2" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3" align="left">
			<table width="100%" border="0" cellpadding="5" cellspacing="5" class="table table-bordered table-hover">
          <tbody>
            <tr>
              <th bgcolor="#EFEFEF">Program</th>
              <th bgcolor="#EFEFEF">Sub Program</th>
              <th bgcolor="#EFEFEF">Region</th>
              <th bgcolor="#EFEFEF">Market</th>
              <th bgcolor="#EFEFEF">Facility</th>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </tbody>
        </table></td>
        </tr>
      <tr>
        <td colspan="3" align="left"><hr></td>
      </tr>
      <tr>
        <td colspan="3" align="left"><label for="Action Plan">Action Plan</label>
          <br>
          <input type="text" name="ActionPlan" id="Action Plan" class="form-control" required="required"></td>
      </tr>
      <tr>
        <td align="left"><h4>ACTION PLAN STATUS LOG</h4></td>
        <td colspan="2" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3" align="left"><table width="100%" border="0" cellpadding="5" cellspacing="5" class="table table-bordered table-hover">
          <tbody>
            <tr>
              <th width="24%" bgcolor="#EFEFEF">User</th>
              <th width="55%" bgcolor="#EFEFEF">Change</th>
              <th width="21%" bgcolor="#EFEFEF">Timestamp</th>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </tbody>
        </table></td>
      </tr>
      <tr>
        <td colspan="3" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3" align="left"><hr></td>
        </tr>
      <tr>
        <td align="left"><label for="DateClosed">Date Closed:</label>
          <input type="date" name="DateClosed" id="DateClosed" class="form-control">
        </td>
        <td colspan="2" align="center" valign="bottom">
          <input type="submit" name="submit" id="submit" value="Submit" class="btn btn-primary">
          <a href="" class="btn btn-primary">Email</a>
        </td>
      </tr>
      <tr>
        <td align="left">&nbsp;</td>
        <td colspan="2" align="center" valign="bottom">&nbsp;</td>
      </tr>
    </tbody>
  </table>
</form>
</div>
		
	</div>
   
  </div>

</div>
</main>
</body>
</html>
