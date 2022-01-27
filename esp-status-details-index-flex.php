<?php include ("includes/functions.php");?>
<?php include ("db_conf.php");?>
<?php include ("data/emo_data.php");?>
<?php include ("sql/filtered_data.php");?>
<?php include ("sql/filters.php");?>
<?php include ("sql/update-time.php");?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<title>Detailed Phase Report</title>
<link rel="shortcut icon" href="favicon.ico"/>
<?php include ("includes/load.php");?>
<link href="jQueryAssets/jquery.ui.core.min.css" rel="stylesheet" type="text/css">
<link href="jQueryAssets/jquery.ui.theme.min.css" rel="stylesheet" type="text/css">
<link href="jQueryAssets/jquery.ui.button.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="colorbox-master/example1/colorbox.css" />
<link href="css/bootstrap-3.3.4.css" rel="stylesheet" type="text/css">
<script src="bootstrap/js/jquery-1.11.2.min.js"></script>
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
				$(".iframe").colorbox({iframe:true, width:"900", height:"600", scrolling:false});
				$(".dno").colorbox({iframe:true, width:"60%", height:"50%", scrolling:false});
				$(".mapframe").colorbox({iframe:true, width:"90%", height:"75%", scrolling:true});
				$(".miniframe").colorbox({iframe:true, width:"30%", height:"50%", scrolling:true});
				$(".ocdframe").colorbox({iframe:true, width:"97%", height:"90%", scrolling:true});
				$(".miframe").colorbox({iframe:true, width:"1000", height:"650", scrolling:false});
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
	<style type="text/css">
        .popover{
            max-width:600px;
        }
    </style>
</head>

<body onload="myFunction()" style="margin:0;">
<!--loader-->
<div id="loader"></div>
<div style="display:block;" id="myDiv" class="animate-bottom"><!--chang none to block when developing-->
<!--show sql-->
<div class="alert-danger"><?php echo $sql_por?></div>
<!--menu-->
<?php include ("includes/menu.php");?>
<section>
  <div class="row" align="center">
    <div style="width:98%">
      <div class="col-xs-12 text-center">
        <h1><?php if($fiscal_year !=0) {echo $fiscal_year;}?> Detailed Phase Report</h1>
        <h5><?php echo $row_da_count['daCount']?> Projects Found </h5>
        <form action="" method="post" class="navbar-form navbar-center" id="formfilter" title="formfilter">
          <div class="form-group">
<!--fiscal year drop down-->                
      <select name="fiscal_year" id="fiscal_year" title="Move this selection back to Fiscal Year to clear this filter" class="form-control"  onchange='this.form.submit()' <?php if(isset($_POST['fiscal_year'])) { fltrSet($_POST['fiscal_year']); }?>>
      	<option value="All">Select Fiscal Year</option>
      		<?php while($row_fiscal_year = sqlsrv_fetch_array( $stmt_fiscal_year, SQLSRV_FETCH_ASSOC)) { ?>
      	<option value="<?php echo $row_fiscal_year['FISCL_PLAN_YR'];?>"<?php if($row_fiscal_year['FISCL_PLAN_YR'] == $fiscal_year ) {?> selected="selected" <?php } ?>><?php echo $row_fiscal_year['FISCL_PLAN_YR'];?></option><?php } ?>
      </select>
      
<?php if($fiscal_year != 0){ // Hide all filter option until fiscal year is select?>

<!--project status drop down-->
    <select name="pStatus" id="pStatus" class="form-control" onchange='this.form.submit()' <?php if(isset($_POST['pStatus'])) { fltrSet($_POST['pStatus']); }?>>
            <option value="Active" <?php if($pStatus == 'Active') { echo 'selected="selected"';} ?>>Active</option>
            <option value="Closed" <?php if($pStatus == 'Closed') { echo 'selected="selected"';} ?>>Closed</option>
          </select>

<!--owner drop down-->  
              
    <select name="owner" required class="form-control" id="owner" title="Move this selection back to SELECT OWNER to clear this filter"  onchange='this.form.submit()' <?php fltrSet($_POST['owner'])?>>
      <option value="">Select Owner</option>
      <?php while($row_owner_drop = sqlsrv_fetch_array( $stmt_owner_drop, SQLSRV_FETCH_ASSOC)) { ?>
      <option value="<?php echo $row_owner_drop['PROJ_OWNR_NM']?>"  
                <?php if($row_owner_drop['PROJ_OWNR_NM'] == $owner) {?>
                selected="selected"
                <?php } ?>
                ><?php echo $row_owner_drop['PROJ_OWNR_NM'];?></option>
      <?php } ?>
      </select>
      
<!--program drop down-->           
           
    <select name="program" id="program" title="Move this selection back to SELECT PROGRAM to clear this filter" class="form-control"  onchange='this.form.submit()'  <?php fltrSet($_POST['program'])?>>
      <option value="">Select Program</option>
      <?php while($row_program_n = sqlsrv_fetch_array( $stmt_program_n, SQLSRV_FETCH_ASSOC)) { ?>
      <option value="<?php echo $row_program_n['PRGM'];?>"
                <?php if($row_program_n['PRGM'] == $program_n) {?>
                selected="selected"
                <?php } ?>
              ><?php echo $row_program_n['PRGM'];?></option>
      <?php } ?>
      </select>
      
<!--subprogram drop down-->
    <select name="subprogram" id="subprogram" itle="Move this selection back to SELECT SUBPROGRAM to clear this filter" class="form-control" onchange='this.form.submit()' <?php fltrSet($_POST['subprogram'])?>>
       <option value="">Select Subprogram</option>
             <?php while($row_subprog = sqlsrv_fetch_array( $stmt_subprogram, SQLSRV_FETCH_ASSOC)) { ?>
      <option value="<?php echo $row_subprog['Sub_Prg'];?>"
                <?php if($row_subprog['Sub_Prg'] == $subprogram) {?>
                selected="selected"
                <?php } ?>
              ><?php echo $row_subprog['Sub_Prg'];?>
       </option>
      <?php } ?>
    </select>
   
<!--region drop down-->
           
    <select name="region" id="region" title="Move this selection back to SELECT REGION to clear this filter" class="form-control"  onchange='this.form.submit()' <?php fltrSet($_POST['region'])?>>
      <option value="">Select Region</option>
      <?php while($row_region_drop = sqlsrv_fetch_array( $stmt_region_drop, SQLSRV_FETCH_ASSOC)) { ?>
      <option value="<?php echo $row_region_drop['Region'];?>"
                <?php if($row_region_drop['Region'] == $region) {?>
                selected="selected"
                <?php } ?>
              ><?php echo $row_region_drop['Region'];?></option>
      <?php } ?>
      </select>
      
      
<!--market drop down-->  
         
    <select name="market" id="market" title="Move this selection back to SELECT MARKET to clear this filter" class="form-control"  onchange='this.form.submit()' <?php fltrSet($_POST['market'])?>>
      <option value="">Select Market</option>
      <?php while($row_market_drop = sqlsrv_fetch_array( $stmt_market_drop, SQLSRV_FETCH_ASSOC)) { ?>
      <option value="<?php echo $row_market_drop['Market'];?>"
                <?php if($row_market_drop['Market'] == $market) {?>
                selected="selected"
                <?php } ?>
              ><?php echo $row_market_drop['Market'];?></option>
      <?php } ?>
      </select>
      
    <?php } // End hidding filters?>  
      
   <!-- <input name="filter" type="submit" class="btn btn-default" id="filter" title="Filter" value="Filter">-->
    <a href="esp-status-details-index-flex.php" title="Clear all filters"><span class="btn btn-default">Clear</span></a>    
    <?php if($fiscal_year <= '2020'){ ?>
    <a href="export-dpr.php?sql=<?php echo urlencode($sql_por)?>" title="Click to export your results" target="_blank"><span class="btn btn-primary">Export Results</span></a>
    <?php } else {?>
    <a href="export-dpr-2021.php?sql=<?php echo urlencode($sql_por)?>" title="Click to export your results" target="_blank"><span class="btn btn-primary">Export Results</span></a>
    <?php } ?>
    <a href="#" data-toggle="popover" title="Status Legend" data-placement="left"  data-content=""><span class="btn btn-primary">View Legend</span></a>
    	<div id="popover_content" style="display: none; padding-bottom:6">
            <table  border="0" align="left" style="font-size:9px;" class="table-bordered">
              <tbody>
                <tr>
                  <td width="62" bgcolor="#c1c1c1" style="padding:2px" >Grey</td>
                  <td style="padding:2px">Cancelled/Not in the Execute stage/No actual Finish Date or Milestone Phase </td>
                </tr>
                <tr>
                  <td bgcolor="#00d257" style="padding:2px">Green</td>
                  <td style="padding:2px">Completed - Actual Finish Date</td>
                </tr>
                <tr>
                  <td bgcolor="#00aaf5" style="padding:2px">Blue</td>
                  <td style="padding:2px">On track – The Estimated Completion Date</td>
                </tr>
                <tr>
                  <td bgcolor="red" style="padding:2px">Red</td>
                  <td style="padding:2px">Not Completed - Finish Date Passed - No Actual Finish Date</td>
                </tr>
                <tr>
                  <td bgcolor="#fcd12a" style="padding:2px">Yellow</td>
                  <td style="padding:2px">There’s a Risk or Issue associated with the Program or Project</td>
                </tr>
              </tbody>
            </table>
          
          <table width="100%" border="0">
          <tbody>
            <tr>
              <td>&nbsp;</td>
            </tr>
          </tbody>
        </table>
		</div><!--end popover-->
        
    </div>
    
</form>
<p>
<?php 
if($fiscal_year <= '2020'){
include ("DPR/dpr2020.php");
}

if($fiscal_year >= '2021'){
include ("DPR/dpr2021.php");
}
?>
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
<script src="js/bootstrap-3.3.4.js" type="text/javascript"></script>

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
</div>
</body>
</html>