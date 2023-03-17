<?php include ("includes/functions.php");?>
<?php include ("db_conf.php");?>
<?php include ("data/emo_data.php");?>
<?php include ("sql/filter_vars.php");?>
<?php include ("sql/filtered_data.php");?>
<?php include ("sql/filters.php");?>
<?php include ("sql/update-time.php"); //echo $sql_por; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>EPS Status Report</title>
<link rel="shortcut icon" href="favicon.ico"/>
<?php include ("includes/load.php");?>
<!--<link href="jQueryAssets/jquery.ui.core.min.css" rel="stylesheet" type="text/css">
<link href="jQueryAssets/jquery.ui.theme.min.css" rel="stylesheet" type="text/css">
<link href="jQueryAssets/jquery.ui.button.min.css" rel="stylesheet" type="text/css">-->

<link rel="stylesheet" href="colorbox-master/example1/colorbox.css" />
<!--<link href="css/bootstrap-3.3.4.css" rel="stylesheet" type="text/css">-->

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
  <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">

<!--<script src="bootstrap/js/jquery-1.11.2.min.js"></script>-->
<script src="colorbox-master/jquery.colorbox.js"></script>
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
				$(".iframe").colorbox({iframe:true, width:"560", height:"800", scrolling:false});
				$(".dno").colorbox({iframe:true, width:"90%", height:"50%", scrolling:false});
				$(".mapframe").colorbox({iframe:true, width:"90%", height:"98%", scrolling:true});
				$(".miframe").colorbox({iframe:true, width:"560", height:"650", scrolling:false});
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
        </script>
</head>

<body onload="myFunction()" style="margin:0;">
<!--loader-->
<div id="loader"></div>
<div style="display:block;" id="myDiv" class="animate-bottom">
<?php //echo $sql_por ?>
<!--menu-->
<?php include ("includes/menu.php");?>
<section>
  <div class="row" align="center">
    <div style="width:98%">
      <div class="col-xs-12 text-center">
        <h1>EPS Status Report</h1>
        <h5><?php echo $row_da_count['daCount']?> Projects Found </h5>
                
<form action="" method="post" class="navbar-form navbar-center" id="formfilter" title="formfilter">
 <div class="form-group">
<table cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td>Fiscal Year</td>
      <td>Status</td>
      <td>Owner</td>
      <td>Program</td>
      <td>Subprogram</td>
      <td>Region</td>
      <td>Market</td>
      <td>Facility</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
    <td><select name="fiscal_year[]"  class="form-control" id="fiscal_year" title="Move this selection back to Fiscal Year to clear this filter" require <?php //if(isset($_POST['fiscal_year'])) { fltrSet($_POST['fiscal_year']); }?>>
        <!--<option value="All">Select Fiscal Year</option>-->
        <?php while($row_fiscal_year = sqlsrv_fetch_array( $stmt_fiscal_year, SQLSRV_FETCH_ASSOC)) { ?>
        <option value="<?php echo $row_fiscal_year['FISCL_PLAN_YR'];?>"<?php if($row_fiscal_year['FISCL_PLAN_YR'] == $fiscal_year_default ) {?> selected="selected" <?php } ?>><?php echo $row_fiscal_year['FISCL_PLAN_YR'];?></option>
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

      <td><input name="Go" type="submit" id="Go" form="formfilter" value="Apply" class="btn btn-primary"></td>
      <td><a href="index_eps_status.php" title="Clear all filters"><span class="btn btn-default">Clear</span></a>    </td>
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
  
   <a href="export.php?sql=<?php echo urlencode($sql_por);?>" title="Click to export your results" target="_blank"><span class="btn btn-primary">Export Results</span></a></div>
</form>
</div>
<p>
<table width="100%" border="0" cellpadding="5" cellspacing="0" class="table-striped table-bordered table-hover">
  <thead>
    <tr align="left" valign="top" style="color:#FFFFFF; background-color:#00aaf5">
      <th align="left" class="sticky"><h6 style="padding:2px"><strong>PROGRAM</strong></h6></th>
      <th align="left" class="sticky"><h6 style="padding:2px"><strong>SUBPROGRAM</strong></h6></th>
      <th align="left" class="sticky"><h6 style="padding:2px"><strong>PROJECT NAME</strong></h6></th>
      <th align="left" class="sticky"><h6 style="padding:2px"><strong>FISCAL YEAR</strong></h6></th>
      <th align="left" class="sticky"><h6 style="padding:2px"><strong>OWNER</strong></h6></th>
      <th align="left" class="sticky"><h6 style="padding:2px"><strong>REGION</strong></h6></th>
      <th align="left" class="sticky"><h6 style="padding:2px"><strong>MARKET</strong></h6></th>
      <th align="left" class="sticky"><h6 style="padding:2px"><strong><strong>FACILITY</strong></strong></h6></th>
      <th align="left" class="sticky"><h6 style="padding:2px"><strong><strong>START DATE</strong></strong></h6></th>
      <th align="left" class="sticky"><h6 style="padding:2px"><strong>ORACLE CODE</strong></h6></th>
      <th align="left" class="sticky"><h6 style="padding:2px"><strong>ORACLE START</strong></h6></th>
      <th align="left" class="sticky"><h6 style="padding:2px"><strong>ORACLE END</strong></h6></th>
      <th align="left" class="sticky"><h6 style="padding:2px"><strong>STAGE</strong></h6></th>
      <th align="left" class="sticky"><h6 style="padding:2px"><strong>WATTS MO</strong></h6></th>
      <th align="left" class="sticky"><h6 style="padding:2px"><strong>SCOPE DESC</strong></h6></th>
      <th align="left" class="sticky"><h6 style="padding:2px"><strong>MORE INFO</strong></h6></th>
    </tr>
    </thead>
    <tbody>
    <?php while( $row_por = sqlsrv_fetch_array( $stmt_por, SQLSRV_FETCH_ASSOC)) {?>
    <?php $uid_x = $row_por['PROJ_ID'] ?>
    <tr align="left" valign="middle" style="font-size:10px">
      <td style="padding:2px"><?php echo htmlspecialchars($row_por['PRGM']);?></td>
      <td style="padding:2px"><?php echo htmlspecialchars($row_por['Sub_Prg']);?></td>
      <td style="padding:2px"><a href="https://coxcomminc.sharepoint.com/sites/pwaeng/project%20detail%20pages/schedule.aspx?projuid=<?php echo urlencode($uid_x) ;?>" title="Open in EPS" target="_blank"><?php echo htmlspecialchars($row_por['PROJ_NM']);?></a></td>
      <td style="padding:2px"><?php echo htmlspecialchars($row_por['FISCL_PLAN_YR']);?></td>
      <td style="padding:2px"><?php echo htmlspecialchars($row_por['PROJ_OWNR_NM']);?></td>
      <td style="padding:2px"><?php echo htmlspecialchars($row_por['Region']);?></td>
      <td style="padding:2px"><?php echo htmlspecialchars($row_por['Market']);?></td>
      <td style="padding:2px"><?php echo htmlspecialchars($row_por['Facility']);?></td>
      <td style="padding:2px"><?php echo convtimex($row_por['Plan_Start_Dt']);?></td>
      <td style="padding:2px"><?php 
          $ocd = $row_por['OracleProject_Cd'];
          $ocd_splits = explode(';',$ocd);
          foreach($ocd_splits as $ocd_codes) {
          echo '<a class="mapframe" href="eq_history.php?ocd='. htmlspecialchars(trim($ocd_codes)) . '">' . htmlspecialchars(trim($ocd_codes)) . '</a><br>';
          }
          ?></td>
      <td style="padding:2px"><?php echo convtimex($row_por['OracleProjectStart_Dt']);?></td>
      <td style="padding:2px"><?php echo convtimex($row_por['OracleProjectEnd_Dt']);?></td>
      <td style="padding:2px"><?php echo htmlspecialchars($row_por['PHASE_NAME']);?></td>
      <td style="padding:2px"><span style="padding:2px"><?php echo htmlspecialchars($row_por['WATTS_MO']);?></span></td>
      <td style="padding:2px" align="left"><?php echo htmlspecialchars($row_por['SCOP_DESC']);?></td>
      <td style="padding:2px" align="center"><a href="#collapseOne<?php echo htmlspecialchars($uid_x) ;?>" title="Show all Project Data" data-toggle="collapse" data-parent="#accordion<?php echo htmlspecialchars($row_por['ProjectStage_key']);?>">+</a></td>
    </tr>

             

    <tr align="left" valign="top" style="background:white">                      
      <td colspan="15">
            <div id="accordion<?php echo htmlspecialchars($uid_x);?>">
		  <div id="collapseOne<?php echo htmlspecialchars($uid_x);?>" class="panel-collapse collapse out">
<div class="row">
                        <div class="col-lg-6" style="margin:20px">
                          <h4>PROJECT DATA</h4>
                                                          	
                                                            <table width="95%" border="0" cellpadding="3" class="table-striped table-bordered table-hover" style="font-size:10px">
                                                              <tbody>
                                                                <tr>
                                                                  <td>UID</td>
                                                                  <td><?php echo htmlspecialchars($uid_x)?></td>
                                                                </tr>
                                                                <tr>
                                                                  <td>Project Name</td>
                                                                  <td><?php echo htmlspecialchars($row_por['PROJ_NM']);?></td>
                                                                </tr>
                                                                <tr>
                                                                  <td>Program</td>
                                                                  <td><?php echo htmlspecialchars($row_por['PRGM']);?></td>
                                                                </tr>
                                                                <tr>
                                                                  <td>Sub Program</td>
                                                                  <td><?php echo htmlspecialchars($row_por['Sub_Prg']);?></td>
                                                                </tr>
                                                                <tr>
                                                                  <td>Oracle Code</td>
                                                                  <td><?php echo str_replace('', '', htmlspecialchars($row_por['OracleProject_Cd'])) ;?></td>
                                                                </tr>
                                                                <tr>
                                                                  <td>Oracle Start</td>
                                                                  <td><?php echo convtimex($row_por['OracleProjectStart_Dt']);?></td>
                                                                </tr>                                                                
                                                                <tr>
                                                                  <td>Oracle End</td>
                                                                  <td><?php echo convtimex($row_por['OracleProjectEnd_Dt']);?></td>
                                                                </tr>                                                                
                                                                <tr>
                                                                  <td>WATTS Master Order</td>
                                                                  <td><?php echo htmlspecialchars($row_por['WATTS_MO']);?></td>
                                                                </tr>
                                                                <tr>
                                                                  <td>Region</td>
                                                                  <td><?php echo htmlspecialchars($row_por['Region']);?></td>
                                                                </tr>
                                                                <tr>
                                                                  <td>Market</td>
                                                                  <td><?php echo htmlspecialchars($row_por['Market']);?></td>
                                                                </tr>
                                                                <tr>
                                                                  <td>Faclity</td>
                                                                  <td><?php echo htmlspecialchars($row_por['Facility']);?></td>
                                                                </tr>
                                                                <tr>
                                                                  <td>PPM No</td>
                                                                  <td><?php echo htmlspecialchars($row_por['PPM_PROJ']);?></td>
                                                                </tr>
                                                                <tr>
                                                                  <td>Owner</td>
                                                                  <td><?php echo htmlspecialchars($row_por['PROJ_OWNR_NM']);?></td>
                                                                </tr>
                                                                <tr>
                                                                  <td>Fiscal Year</td>
                                                                  <td><?php echo htmlspecialchars($row_por['FISCL_PLAN_YR']);?></td>
                                                                </tr>
                                                                <tr>
                                                                  <td>Project Type</td>
                                                                  <td><?php echo htmlspecialchars($row_por['ENTRPRS_PROJ_TYPE_NM']);?></td>
                                                                </tr>
                                                                <tr>
                                                                  <td>Workflow Stage</td>
                                                                  <td><?php echo htmlspecialchars($row_por['PHASE_NAME']);?></td>
                                                                </tr>
                                                                <tr>
                                                                  <td>Start Date</td>
                                                                  <td><?php echo convtimex($row_por['Plan_Start_Dt']);?></td>
                                                                </tr>
                                                                <tr>
                                                                  <td>Finish Date</td>
                                                                  <td><?php echo convtimex($row_por['Plan_Finish_Dt']);?></td>
                                                                </tr>
                                                                <tr>
                                                                  <td>Commit Date</td>
                                                                  <td><?php echo convtimex($row_por['COMIT_DT']);?></td>
                                                                </tr>
                                                                <tr>                                                               
                                                                  <td>Equipment Type 1</td>
                                                                  <td>(<?php echo htmlspecialchars($row_por['Equip1_Cnt']); ?>) <?php echo htmlspecialchars($row_por['Equip1_TYPE']);?></td>
                                                                </tr>
                                                                <tr>                                                               
                                                                  <td>Equipment Type 2</td>
                                                                  <td>(<?php echo htmlspecialchars($row_por['Equip2_Cnt']); ?>) <?php echo htmlspecialchars($row_por['Equip2_TYPE']);?></td>
                                                                </tr>
                                                                <tr>                                                               
                                                                  <td>Equipment Type 3</td>
                                                                  <td>(<?php echo htmlspecialchars( $row_por['Equip3_Cnt']); ?>) <?php echo htmlspecialchars($row_por['Equip3_TYPE']);?></td>
                                                                </tr>
                                                                <tr>                                                               
                                                                  <td>Equipment Type 4</td>
                                                                  <td>(<?php echo htmlspecialchars($row_por['Equip4_Cnt']); ?>) <?php echo htmlspecialchars($row_por['Equip4_TYPE']);?></td>
                                                                </tr>
                                   
                                                              </tbody>
                                                            </table></div>
                        <!--<div class="col-lg-6">Column 2</div>-->
                        
                    </div>


          
          
          </div>
      </div>
      </td>
      </tr>

    <?php } ;?>
  </tbody>
</table>
        <div class="">
        <!--DEV BOX<br>-->
			<?php // echo 'Owner: ' . $_POST['owner']?><!--<br>-->
            <?php //echo 'Program: ' . $_POST['program']?><!--<br>-->
            <?php //echo 'Region: ' . $_POST['region']?><!--<br>-->
            <?php //echo 'Market: ' . $_POST['market']?><!--<br>-->
            <?php //echo $query_por?><!--<br>-->
        </div>
        </p>       
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
<!--<footer class="text-center" style="margin-top:50">
  <div class="container">
    <div class="row">
      <div class="col-xs-12">
        <p>Copyright© 2019. Cox Communications. All rights reserved.</p>
        <span style="font-size:9px" >This site contains confidential information intended solely for the use of authorized users of Cox Communications, Engineering Management Office. If you are not authorized to view this site, you
should exit immediately and are hereby notified that disclosure, copying, distribution, or reuse of this message or any information contained therein by any other person is strictly prohibited.</span> </div>
      </div>
    </div>
  </div>
</footer>-->

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
</body>
</html>