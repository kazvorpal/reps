﻿<?php include ("../../includes/functions.php");?>
<?php include ("../../db_conf.php");?>
<?php include ("../../data/emo_data.php");?>
<?php include ("../../sql/filter_vars.php");?>
<?php include ("../../sql/filtered_data.php");?>
<?php include ("../../sql/filters.php");?>
<?php include ("../../sql/update-time.php");?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Program/Project Risk & Issues</title>
<link rel="shortcut icon" href="favicon.ico"/>
<?php include ("../../includes/load.php");?>
<!--<link href="jQueryAssets/jquery.ui.core.min.css" rel="stylesheet" type="text/css">
<link href="jQueryAssets/jquery.ui.theme.min.css" rel="stylesheet" type="text/css">
<link href="jQueryAssets/jquery.ui.button.min.css" rel="stylesheet" type="text/css">-->

<link rel="stylesheet" href="../../colorbox-master/example1/colorbox.css" />
<!--<link href="css/bootstrap-3.3.4.css" rel="stylesheet" type="text/css">-->

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"> 
  <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 

  <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous"> -->
  <!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script> -->
	
  
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />


  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">

<!--<script src="bootstrap/js/jquery-1.11.2.min.js"></script>-->
<script src="../../colorbox-master/jquery.colorbox.js"></script>
	
	
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
				$(".dno").colorbox({iframe:true, width:"80%", height:"60%", scrolling:false});
				$(".mapframe").colorbox({iframe:true, width:"95%", height:"95%", scrolling:true});
				$(".miniframe").colorbox({iframe:true, width:"30%", height:"50%", scrolling:true});
				$(".ocdframe").colorbox({iframe:true, width:"75%", height:"90%", scrolling:true});
				$(".miframe").colorbox({iframe:true, width:"1500", height:"650", scrolling:false});
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
function MM_setTextOfTextfield(objId,x,newText) { //v9.0
  with (document){ if (getElementById){
    var obj = getElementById(objId);} if (obj) obj.value = newText;
  }
}

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

</script>
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
		$('#risk_issue').multiselect({
          includeSelectAllOption: true,
        });
		$('#impact_level').multiselect({
          includeSelectAllOption: true,
        });
		
  });
</script>
	<style type="text/css">
        .popover{
            max-width:600px;
        }
    </style>
</head>

<body onload="myFunction()" style="margin:0;">
<!--LOADER-->
<div id="loader"></div>
<div style="display:block;" id="myDiv" class="animate-bottom"><!--chang none to block when developing-->
<!--FOR DEV ONLY - show sql-->
<div class="alert-danger">
  <?php //echo $sql_por ?>
</div>
<div class="alert-danger">
  <?php
  // DEFAULTS
 // if(isset($_POST['fiscal_year'])) {
  //echo 'Fiscal Year Post: ' . $list_fy . '<br>';
  //}
  //echo 'Fiscal Year: ' . $fiscal_year . '<br>';
  //echo 'Status: ' . $pStatus . '<br>';
  //echo 'Program: ' . $program_d . '<br>';
  //echo 'Region: ' . $region . '<br>';
  //echo 'Market: ' . $market . '<br>';
  //echo 'Owner: ' . $owner  . '<br>';
  //echo 'Subprogram: ' . $subprogram . '<br>';
  ?>
</div>
<!--menu-->
<?php include ("../../includes/menu.php");?>
<section>
  <div class="row" align="center">
    <div style="width:98%">
      <div class="col-xs-12 text-center">
        <h1><?php if($fiscal_year !=0) {echo $fiscal_year;}?>Risks & Issues</h1>
        <h5><?php echo $row_da_count['daCount']?> Risks and Issues Found </h5>
	<form action="" method="post" class="navbar-form navbar-center" id="formfilter" title="formfilter">
          <div class="form-group">
            <table width="500" border="0" align="center">
              <tbody>
                <tr>
                  <td align="center">Risk/Issue</td>
                  <td align="center">Impact Level</td>
                  <td align="center">Forcasted Resolution Date Range</td>
                </tr>
                <tr>
                  <td align="center"><select name="risk_issue[]" id="risk_issue" multiple="multiple" class="form-control">
                    <option value="Risk">Risk</option>
                    <option value="Issue">Issue</option>
                    </select></td>
                  <td align="center"><select name="impact_level[]" id="impact_level" multiple="multiple" class="form-control">
                    <option value="MIN">Minor Impact</option>
                    <option value="MOD">Moderate Impact</option>
                    <option value="MAJ">Major Impact</option>
                    <option value="NOI">No Impact</option>
                    </select></td>
                  <td align="center"><input type="text" class="daterange" /></td>
                </tr>
              </tbody>
            </table>
            <br>
 <table cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td>*Fiscal Year</td>
    <?php  //if($fiscal_year !=0) { ?>
      <td>Status</td>
      <td>Owner</td>
      <td>Program</td>
      <td>Subprogram</td>
      <td>Region</td>
      <td>Market</td>
      <td>Facility</td>
    <?php // } ?>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
    <td><select name="fiscal_year[]"  class="form-control" id="fiscal_year" title="Move this selection back to Fiscal Year to clear this filter" require <?php //if(isset($_POST['fiscal_year'])) { fltrSet($_POST['fiscal_year']); }?>>
        <!--<option value="All">Select Fiscal Year</option>-->
        <?php while($row_fiscal_year = sqlsrv_fetch_array( $stmt_fiscal_year, SQLSRV_FETCH_ASSOC)) { ?>
        <option value="<?php echo $row_fiscal_year['FISCL_PLAN_YR'];?>"<?php if($row_fiscal_year['FISCL_PLAN_YR'] == '2021' ) {?> selected="selected" <?php } ?>><?php echo $row_fiscal_year['FISCL_PLAN_YR'];?></option>
        <?php } ?>
      </select></td>

      <td><select name="pStatus[]" multiple="multiple" class="form-control" id="pStatus" style="background-color:#ededed">
        <option value="Active" <?php if($pStatus == -1 || $pStatus == 'Active' || $pStatus == 'Active|Closed') { echo 'selected="selected"';} ?>>Active</option>
        <option value="Closed" <?php if($pStatus == 'Closed' || $pStatus == 'Active|Closed') { echo 'selected="selected"';} ?>>Closed</option>
      </select></td>

      <td><select name="owner[]" multiple="multiple" class="form-control" id="owner" title="Move this selection back to SELECT OWNER to clear this filter" <?php //fltrSet($_POST['owner'])?>>
        <!--<option value="">Select Owner</option>-->
        <?php while($row_owner_drop = sqlsrv_fetch_array( $stmt_owner_drop, SQLSRV_FETCH_ASSOC)) { ?>
        <option value="<?php echo $row_owner_drop['PROJ_OWNR_NM']?>" <?php if($fiscal_year != 0) {echo 'selected="selected"';} ?>><?php echo $row_owner_drop['PROJ_OWNR_NM'];?></option>
        <?php } ?>
      </select></td>

      <td><select name="program[]" multiple="multiple" class="form-control" id="program" title="Move this selection back to SELECT PROGRAM to clear this filter" <?php //fltrSet($_POST['program'])?>>
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
      <td><a href="esp-status-details-index.php" title="Clear all filters"><span class="btn btn-default">Clear</span></a></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
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

</div>
	</form>
    
<div class="">

</div>
      
      </div>
    </div>
  </div>
</section>
<section>

</section>

<section></section>
<section>
  <div class="container">
    <div class="row"></div>
  </div>
</section>
<!--<script src="js/bootstrap-3.3.4.js" type="text/javascript"></script>-->

<script>
	var myVar;
	
	function myFunction() {
	  myVar = setTimeout(showPage, 1000);
	}
	
	function showPage() {
	  document.getElementById("loader").style.display = "none";
	  document.getElementById("myDiv").style.display = "block";
	}
</script>
<script>
  $(function(){
    $('[data-toggle="popover"]').popover({ 
      html : true, 
      content: function() {
        return $('#popover_content').html();
      }
    });
  });
</script>
<script type="text/javascript">
	$('.daterange').daterangepicker();
</script>

</div>
</body>
</html>