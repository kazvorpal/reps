<?php include ("../../includes/functions.php");?>
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
<!-- <link 
  rel="stylesheet"
  href="/css/bootstrap.css" rel="nofollow"
  integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" 
  crossorigin="anonymous"
>
<script 
  src=”https://code.jquery.com/jquery-3.2.1.slim.min.js” 
  integrity=”sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN” 
  crossorigin=”anonymous”>
</script>
<script src=https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js 
  integrity=”sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q” 
  crossorigin=”anonymous”>
</script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
  integrity=”sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl” 
  crossorigin=”anonymous”>
</script> -->
<?php include ("../../includes/load.php");

function fixutf8($target) {
  if (gettype($target) == "string")
  return (utf8_encode($target));
  else 
  return ($target);
}

$sqlstr = "select * from RI_Mgt.fn_GetListOfAllRiskAndIssue() where rilevel_cd = 'program'";
ini_set('mssql.charset', 'UTF-8');
$riquery = sqlsrv_query($conn, $sqlstr);
if($riquery === false) {
  if(($error = sqlsrv_errors()) != null) {
    foreach($errors as $error) {
      echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
      echo "code: ".$error[ 'code']."<br />";
      echo "message: ".$error[ 'message']."<br />";
    }
  }
} else {
  // $resultset = sqlsrv_fetch_array($riquery, SQLSRV_FETCH_ASSOC);
  // $rijson = json_encode($resultset);
  // print_r($resultset);
  // print ('<pre>'.$rijson.'</pre>');
  $rows = array();
  $count = 1;
  while($row = sqlsrv_fetch_array($riquery, SQLSRV_FETCH_ASSOC)) {
    $rows[] = array_map("fixutf8", $row);
  }
  
  $p4plist = array();
  foreach ($rows as $row)  {
    if($row["ProgramRI_Key"] != '') {
      $sqlstr = "select * from RI_Mgt.fn_GetListOfAssociatedProjectsForProgramRIKey(". $row["RiskAndIssue_Key"] ." ,". $row["ProgramRI_Key"] .")";
      // echo $sqlstr . "<br/>";
      ini_set('mssql.charset', 'UTF-8');
      $p4pquery = sqlsrv_query($conn, $sqlstr);
      if($p4pquery === false) {
        if(($error = sqlsrv_errors()) != null) {
          foreach($errors as $error) {
            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            echo "code: ".$error[ 'code']."<br />";
            echo "message: ".$error[ 'message']."<br />";
          }
        }
      } else {
        $count = 1;
        $p4prows = array();
        $checker = 1;
        while($p4prow = sqlsrv_fetch_array($p4pquery, SQLSRV_FETCH_ASSOC)) {
          // print_r('$p4prow');
          // print_r($p4prow);
          $p4prows[] = array_map("fixutf8", $p4prow);
          $checker = 0;
        }
        if ($checker == 1) {      
          $p4prows[] = array(
            "RiskAndIssue_Key" => $row["RiskAndIssue_Key"], 
            "ProgramRI_Key" => $row["RiskAndIssue_Key"], 
            "EPSProject_Nm"=>"EPS Project Placeholder", 
            "PROJECT_Key"=>rand(1, 100), 
            "Subprogram_nm"=>"Subprogram Placeholder", 
            "Location_Key"=> 3, 
            "EPS_Location_Cd"=>rand(1, 10), 
            "EPSProject_Owner"=>"Elvis Presley"
          );
          // print ("BAD");
        } else {
          // print("GOOD");
        }
      }
      $p4plist[$row["RiskAndIssue_Key"]."-".$row["ProgramRI_Key"]] = $p4prows;
    }
  }
  
  $mangerlist = array();
  foreach ($rows as $row)  {
    if($row["ProgramRI_Key"] != '') {
      $sqlstr = "select * from RI_MGT.fn_GetListOfOwnersInfoForProgram(". $row["Fiscal_Year"] ." ,'". $row["Program_Nm"] ."')";
      // echo $sqlstr . "<br/>";
      ini_set('mssql.charset', 'UTF-8');
      $mangerquery = sqlsrv_query($conn, $sqlstr);
      if($mangerquery === false) {
        if(($error = sqlsrv_errors()) != null) {
          foreach($errors as $error) {
            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            echo "code: ".$error[ 'code']."<br />";
            echo "message: ".$error[ 'message']."<br />";
          }
        }
      } else {
        $count = 1;
        $mangerrows = array();
          while($mangerrow = sqlsrv_fetch_array($mangerquery, SQLSRV_FETCH_ASSOC)) {
            $mangerrows[] = array_map("fixutf8", $mangerrow);
            // echo "<pre>";
            // print_r($mangerrow);
            // echo "</pre>";
          }
        }
        $mangerlist[$row["Fiscal_Year"]."-".$row["MLMProgram_Key"]] = $mangerrows;
      }
    }
    
    // select * from RI_MGT.fn_GetListOfOwnersInfoForProgram(2021,'Metro Transport')


  // echo "<pre>";
  // print_r($p4plist);
  // echo "</pre>";
  $p4pout = json_encode($p4plist);
  $mangerout = json_encode($mangerlist);
  $jsonout = json_encode($rows);
  // echo "<pre>'";
  // foreach ($p4plist as $arr) { 
  //   foreach($arr as $inside) {
  //     print_r($inside);
  //   }
  // }
  // // print_r($p4plist);
  // echo "</pre>'";
  // $utfEncodedArray = array_map('gettype', $rows);
  // $utfEncodedArray = array_map("utf8_encode", $rows);
  // print($rows);
  // print json_last_error_msg() ;
  ?>
    <?php
  //   <div id="body" class="toppleat">
  //   <a href="">Program A</a> (R:3 I:3)
  // </div>
  
  
  
  // echo "'</pre>";
  // //LOOP 2
  // //Build inner pieces
  // while ($row = mysql_fetch_assoc($result)) {
    
    // echo "<div id='accordian-".$row['id']."' class='accordion-body collapse'>";
    // echo "<div class='accordion-inner'>";
    
    // echo "<td>Rank: {$rank} <br /> </td>";
    // echo "<td>Wins: {$row['wins']} <br /> </td>";
    // echo "<td>Losses: {$row['losses']} <br /> </td>";
    
    // echo "</div>";
    // echo "</div>";  }
  }
  // while ()

?>
<!--<link href="jQueryAssets/jquery.ui.core.min.css" rel="stylesheet" type="text/css">
<link href="jQueryAssets/jquery.ui.theme.min.css" rel="stylesheet" type="text/css">
<link href="jQueryAssets/jquery.ui.button.min.css" rel="stylesheet" type="text/css">-->

<link rel="stylesheet" href="../../colorbox-master/example1/colorbox.css" />
<!--<link href="css/bootstrap-3.3.4.css" rel="stylesheet" type="text/css">-->

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"> 
  <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 
  <!-- <link rel="stylesheet" href="/css/bootstrap.css" rel="nofollow">
  <script src="/js/bootstrap.js"></script> -->

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
        .header {
          background-color: #00aaf5;
          color: #fff;
          font-size: large;
          text-align: left;
          padding:8px 16px;
        }
        .toppleat {
          color: #0;
          background-color: #eee;
          text-align: left;
          padding: 8px 8px;
          cursor: pointer;
          /* height: 36px; */
        }
        .accordion-button {
          width: 25%;
          float: right;
        }
        .a-proj {
          text-decoration:underline;
        }
        .toppleat:nth-child(odd) {
          background-color: #fff;
        }
        @media (max-width:2000px){
    
          /* Menu BreakPoint */
          .navbar-header {float: none;}
          .navbar-left,.navbar-right {    float: none !important;}
          .navbar-toggle {    display: block;}
          .navbar-collapse {  border-top: 1px solid transparent;  box-shadow: inset 0 1px 0 rgba(255,255,255,0.1);}
          .navbar-fixed-top {    top: 0;    border-width: 0 0 1px;}
          .navbar-collapse.collapse { display: none!important;}
          .navbar-nav {float: none!important; margin-top: 7.5px;}
          .navbar-nav>li {float: none;}
          .navbar-nav>li>a { padding-top: 10px;padding-bottom: 10px;}
          .collapse.in{display:block !important;}
          .navbar-nav .open .dropdown-menu { position: static; float: none; width: auto; margin-top: 0; background-color: transparent; border: 0; -webkit-box-shadow: none; box-shadow: none;}

        }
        .headbox {
          background-color: #00aaf5;
          color: #fff;
          border-left: 1px solid #fff;
          padding: 4px;
        }
        .databox {
          background-color: #d9d9d9;
          color: #00;
          border: 1px solid #888;
          padding: 4px;
        }
        .namebox {
          background-color: #fff;
          color: #00f;
          border: 1px solid #ddd;
          padding: 4px;
        }
        .arrows {
          color:black;
          float:left;
          height:100%;
          padding:4px
        }
    </style>
</head>

<body onload="myFunction()" style="margin:0;">
<!--LOADER-->
<div id="loader"></div>
<div style="display:block;" id="myDiv" class="animate-bottom"><!--change none to block when developing-->
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
        <h1><?php if($fiscal_year !=0) {echo $fiscal_year;}?>Risks & Issues Aggregate</h1>
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
                  <td align="center"><input type="text" class="daterange form-control" /></td>
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
      <!-- <td>Region</td>
      <td>Market</td> -->
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

      <!-- <td><select name="market[]" multiple="multiple" class="form-control" id="market" title="Move this selection back to SELECT MARKET to clear this filter" <?php //fltrSet($_POST['market'])?>>
        <?php while($row_market_drop = sqlsrv_fetch_array( $stmt_market_drop, SQLSRV_FETCH_ASSOC)) { ?>
        <option value="<?php echo $row_market_drop['Market'];?>" <?php if($fiscal_year != 0) {echo 'selected="selected"';} ?>> <?php echo $row_market_drop['Market'];?></option>
        <?php } ?>
      </select></td>

      <td><select name="facility[]" multiple="multiple" class="form-control" id="facility" title="Move this selection back to SELECT MARKET to clear this filter" <?php //fltrSet($_POST['facility'])?>>
        <?php while($row_facility_drop = sqlsrv_fetch_array( $stmt_facility_drop, SQLSRV_FETCH_ASSOC)) { ?>
        <option value="<?php echo $row_facility_drop['Facility'];?>" <?php if($fiscal_year != 0) {echo 'selected="selected"';} ?>> <?php echo $row_facility_drop['Facility'];?></option>
        <?php } ?>
      </select></td> -->

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
    
<div id="main" class="accordion" >
    <div class="header">
      Program Name (Risks, Issues)
    </div>
    <!-- <div id="body" class="toppleat">
      <a href="">Program A</a> (R:3 I:3)
    </div>
    <div id="body" class="toppleat">
      <a href="">Program B</a> (R:22 I:11)
    </div>
    <div id="body" class="toppleat">
      <a href="">Program C</a> (R:12 I:10)
    </div> -->

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
<script>
  
  const ridata = <?= $jsonout ?>;  
  const mangerlist = <?= $mangerout ?>;
  const p4plist = <?= $p4pout ?>;
  
  console.log(ridata);
  const fieldlist = ["Program", "Region", "Program Manager", "ID #", "Impact Level", "Action Status", "Forecast Resol. Date", "Current Task POC", "Response Strat", "Open Duration"];
  const projectfields = ["Project Name", "Facility", "Owner", "Subprogram"];
  // console.log(ridata)
  const finder = (target, objective) => (target.find(o => o.Program_Nm == objective));
  
  // Takes a program name and returns the row object
  const getprogrambyname = (target) =>  mlm = ridata.find(o => o.Program_Nm == target);
  
  // Takes a program key and name and returns the row object
  const getprogrambykey = (target, name) =>  mlm = ridata.find(o => o.RiskAndIssue_Key == target && o.Program_Nm == name);
  
  const uniques = ridata.map(item => item.Program_Nm).filter((value, index, self) => self.indexOf(value) === index)
  // console.log(ridata[1].RiskAndIssue_Key);

  const makesafe = (target) => target.replace(/\s/g,'');

  const createrow = (name, risks, issues) => {
    const safename = makesafe(name);
    const item = document.createElement("div");
    item.id = "item" + safename;
    item.className = "toppleat accordion-item";
    const banner = makebanner(safename);

    const program = document.createElement("a");
    program.id = "program" + safename;
    program.className = "a-proj";
    program.innerHTML = name;
    const counts = document.createTextNode(" (R:" + risks + " I:" + issues + ")");

    const collapse = document.createElement("div");
    collapse.id = "collapse" + safename;
    collapse.className = "panel-collapse collapse";
    const body = document.createElement("div");
    body.id = "body" + safename;
    body.className = "accordion-body";
    const table = document.createElement("table");
    table.className = "table";
    table.id = "table" + safename;

    banner.appendChild(program);
    banner.appendChild(counts);
    item.appendChild(banner);
    item.appendChild(collapse).appendChild(body).appendChild(table);
    document.getElementById("main").appendChild(item);

    makeri(name, "Risk");
    makeri(name, "Issue");
  }  

  const makebanner = (safename) => {
    const banner = document.createElement("div");
    banner.id = "banner" + safename;
    banner.className = "accordion-banner";
    banner.setAttribute("aria-labelledby", "banner" + safename);
    banner.setAttribute("data-bs-target", "#collapse" + safename);
    banner.setAttribute("data-target", "#collapse" + safename);
    banner.setAttribute("data-toggle", "collapse");
    banner.ariaExpanded = true;
    banner.setAttribute("aria-controls", "collapse" + safename);
    return banner;
  }  

  const makeri = (name, type) => {
    document.getElementById("table"+makesafe(name)).appendChild(makeheader(name, type));
    program = getprogrambyname(name);
    let lr = listri(name, type);
    for (ri of lr) {
      // console.log(ri);
      makedata(ri, type, name);  
    }
  }    

  const rirow = ["Program_Nm", "Region_Cd", null, "RiskAndIssue_Key", "ImpactLevel_Nm", "ActionPlanStatus_Cd", 
                function() {"ForecastedResolution_Dt"}];

  const makedata = (id, type, name) => {            
    // Make all the data inside a risk or issue
    const program = getprogrambykey(id, name);
    const safename = makesafe(program.Program_Nm);
    const saferi = makesafe(program.RI_Nm);

    const trid = "tr" + type + saferi + Math.random();
    const trdata = document.createElement("tr");
    trdata.id = trid;
    document.getElementById("table" + safename).appendChild(trdata);
    const header = document.createElement("th");
    header.className = "p-4 namebox";
    header.innerHTML = "<div class='arrows'>▶ </div><div style='overflow:hidden'>" + program.RI_Nm + "</div>";
    
    header.onclick = function() {
      const target = document.getElementById("projects" + saferi);
      console.log(target);
      target.className = (target.className.indexOf("Show") == -1) ? target.className += "Show" : target.className.replace("Show", "");
    }
    document.getElementById(trid).appendChild(header);
    maketd(program.Program_Nm, trid);
    maketd(program.Region_Cd, trid);
    const manger = mangerlist[program.Fiscal_Year + "-" + program.MLMProgram_Key];
    let mangers = [];
    for (man of manger) {
      mangers.push(man.User_Nm);
    }  
    maketd(mangers.join().replace(",", ", "), trid);
    maketd(program.RiskAndIssue_Key, trid);
    maketd(program.ImpactLevel_Nm, trid);
    maketd(program.ActionPlanStatus_Cd, trid);
    maketd(todate(program.ForecastedResolution_Dt.date), trid);
    maketd(program.POC_Nm, trid);
    maketd(program.ResponseStrategy_Cd, trid);
    maketd(Math.floor(program.RIOpen_Hours/24), trid);
    // console.log("table" + safename);
    makeprojects(p4plist[program.RiskAndIssue_Key + "-" + program.ProgramRI_Key], program.Program_Nm, "table" + safename, saferi);
  }    




  const makeprojects = (projects, programname, tableid, saferi) => {

    // Make the rows of projects inside the program
    // console.log("projects");
    // console.log(projects);
    const trp = document.createElement("tr");
    trp.className = "collapse";
    console.log("projects" + saferi)
    trp.id = "projects" + saferi;
    document.getElementById(tableid).appendChild(trp);
    const spacer = document.createElement("td");
    spacer.innerHTML = "&nbsp;";
    const tdp = document.createElement("td");
    tdp.id = "td" + saferi;
    tdp.colSpan = "10"
    trp.appendChild(spacer);
    trp.appendChild(tdp);
    // console.log(makesafe(programname));
    const table = document.createElement("table");
    table.id = "table" + saferi;
    table.appendChild(projectheader());
    document.getElementById("td" + saferi).appendChild(table);
    for(project of projects) {
      // console.log(project);
      const tr = document.createElement("tr");
      tr.id = "tr" + project.PROJECT_Key;
      document.getElementById("table" + saferi).appendChild(tr);
      const blanktd = document.createElement("td");
      blanktd.innerHTML = "&nbsp;";
      tr.appendChild(blanktd);
      tr.appendChild(makeptd(project.EPSProject_Nm));
      tr.appendChild(makeptd(project.EPS_Location_Cd));
      tr.appendChild(makeptd(project.EPSProject_Owner));
      tr.appendChild(makeptd(project.Subprogram_nm));
      tr.appendChild(blanktd);
      // const name = getprogrambykey(project.RiskAndIssue_Key, name)  
    }
  }  
  const projectheader = () => {
    // Make the header row for a project 
    const trri = document.createElement("tr");
    for (field of projectfields) {
      trri.appendChild(maketh(field));
    }
    return trri;
  }  

  const makeptd = (value) => {
    const td = document.createElement("td");
    td.className = "p4 databox";
    td.innerHTML = value;
    return td;
  }  

  const todate = (date) => new Date(date).toLocaleString("en-US", {
    day: "numeric",
    month: "numeric",
    year: "numeric",
  });  

  const makeheader = (name, type) => {
    // Make the header row for a risk or issue
    const safename = makesafe(name);
    const trri = document.createElement("tr");
    trri.id = "tr" + type + safename;
    // document.getElementById("table"+safename).appendChild(trri);
    const header = document.createElement("th");
    header.className = "p-4";
    header.innerHTML = type+"s";
    trri.appendChild(header);
    for (field of fieldlist) {
      trri.appendChild(maketh(field));
    }
    return trri;
  }  

  const maketd = (value, target) => {
    const header = document.createElement("td");
    header.className = "p-4 databox";
    header.innerHTML = value;
    document.getElementById(target).appendChild(header);
  }

  const maketh = (name) => {
    const header = document.createElement("th");
    header.className = "p-4 headbox";
    header.innerHTML = name;
    return header;
  }  



  function countri(target, type) {
    
    // returns count of risks or issues for a given program, taking program name and type (risk, issue)
    
    pre = ridata.filter(o => o.RILevel_Cd == "Program" && o.RIType_Cd == type && o.Program_Nm == target);
    // uni = pre.filter((value, index, self) => self.indexOf(value) === index);
    uni = pre.map(item => item.RiskAndIssue_Key).filter((value, index, self) => self.indexOf(value) === index);
    return uni.length;
    // counter = ridata.map(item => item.RiskAndIssue_Key).filter((value, index, self) => self.indexOf(value) === index);
  }
  function listri(target, type) {
    
    // returns a list of risks or issues for a given program, taking program name and type (risk, issue)
    
    pre = ridata.filter(o => o.RILevel_Cd == "Program" && o.RIType_Cd == type && o.Program_Nm == target);
    uni = pre.map(item => item.RiskAndIssue_Key).filter((value, index, self) => self.indexOf(value) === index);
    return uni;
  }

  for (loop of uniques) {
    // creates all the programs
    if(loop != null) {
      createrow(loop, countri(loop, "Risk"), countri(loop, "Issue"));
    }
  }



  // const getdistinct = (target) => {
  //   uniques = [];
  //   for (item of target) {
  //     if(item.Program_Nm != null) {
  //       if(!(item.Program_Nm in uniques)) {
  //         // console.log(item.Program_Nm)
  //         uniques.push(item.Program_Nm)
  //       }
  //     }
  //   }
  //   return uniques;
  // }



  // console.log("MORe");
  // console.log(countri("Metro Transport", "Risk"));
  // console.log(listri("Metro Transport", "Risk"));
  // console.log(countri("Metro Transport", "Issue"));
  // console.log(listri("Metro Transport", "Issue"));
  // select  count (distinct RIskandissue_Key) as Risk_count from RI_MGT.fn_GetListOfAllRiskAndIssue() where
  // riLevel_cd = 'program' and fiscal_year > 2020 and RIType_Cd = 'Risk'
  // and Program_Nm = 'Metro Transport'
  // and fiscal_year = 2021


  // const unique = [...new Set(ridata.map(item => item.program_Nm))]; // [ 'A', 'B']
  // console.log(uniques);
  // for(ri of ridata) {
  //   if(ri.ProgramRI == null) {
  //     // console.log(ri);
  //   } else {
  //     // console.log(ri);
  //     createrow(ri);
  //   }
  // }
  // ridata.every(dumper);
</script>
</html>