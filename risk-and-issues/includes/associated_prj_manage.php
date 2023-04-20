<?php 
//print_r($_GET);
include ("../../includes/functions.php");
include ("../../db_conf.php");
include ("../../data/emo_data.php");
include ("../../sql/project_by_id.php");
include ("../../sql/ri_filter_vars.php");
include ("../../sql/ri_filters.php");
include ("../../sql/ri_prj_assoc_manage.php");

//DECLARE
$uid = $_GET['uid'];
$ri_type = strtolower($_GET['ri_type']);
$action = $_GET['action'];
$fiscal_year =  $_GET['fiscal_year'];
$ri_level = strtolower($_GET['ri_level']);
$ri_proj_name = $row_projID['PROJ_NM'];
$name =$_GET['name'];
$rikey = $_GET['rikey'];
$status = $_GET['status'];
$driver_time = "";
$groupID = $_GET['inc'];
//$RiskAndIssue_Key = $_GET['rikey'];

//GET DRIVERS LIST (DIRVERTIME)
$sql_ri_driver_lst = "select Driver_Nm from [RI_MGT].[fn_GetListOfRiskAndIssuesForEPSProject]  ($fiscal_year,'$ri_proj_name') where RiskAndIssue_Key = $rikey";
$stmt_ri_driver_lst = sqlsrv_query( $data_conn, $sql_ri_driver_lst );
// $row_ri_driver_lst = sqlsrv_fetch_array($stmt_ri_driver_lst, SQLSRV_FETCH_ASSOC);
// echo $row_ri_driver_lst['Driver_Nm]; 

//COUNT PROJECT AVAILABLE TO ADD
$sql_por_count = " SELECT COUNT(*)AS dacount FROM( Select* 
            From [EPS].[fn_GetListOfProjectStageWithCriteria]('$fiscal_year','Active','$program_d','$region','$market','$owner', '$subprogram','$facility') 
            WHERE PROJ_NM not in ( $pjNames ))a";
$stmt_por_count = sqlsrv_query( $data_conn, $sql_por_count ); 
$row_por_count = sqlsrv_fetch_array( $stmt_por_count, SQLSRV_FETCH_ASSOC) ;
//echo $row_por_count['dacount'];
//echo $sql_por_count;

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<style>
    .box {
    border: 1px solid #BCBCBC;
    border-radius: 5px;
    padding: 5px;
    }
    .finePrint {
    font-size: 9px; 
    color: red; 
    }
</style>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"> 
  <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
  <link rel="stylesheet" href="../steps/style.css" type='text/css'> 
  <link href='http://fonts.googleapis.com/css?family=Mulish' rel='stylesheet' type='text/css'>

  
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
  checkboxes = document.getElementsByName('proj_select[]');
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
  }
}

function toggle(source2) {
  checkboxes = document.getElementsByName('add_proj_select[]');
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source2.checked;
  }
}
</script>
</head>

<body style="background: #F8F8F8; font-family:Mulish, serif;">
<!-- PROGRESS BAR -->
<div class="container">       
            <div class="row bs-wizard" style="border-bottom:0;">
                
                <div class="col-xs-3 bs-wizard-step active">
                  <div class="text-center bs-wizard-stepnum">STEP 1</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="bs-wizard-dot"></a>
                  <div class="bs-wizard-info text-center">Select Projects to Add</div>
                </div>
                
                <div class="col-xs-3 bs-wizard-step disabled"><!-- complete -->
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
  <div align="center" class="finePrint"><?php //echo $sql_por?></div>
  <div align="Center">
    <h3>
      <?php
      if($ri_type == "risk" && $ri_level == "prj"){
        echo "ADD PROJECT RISK ASSOCIATED PROJECTS";
      } elseif ($ri_type == "risk" && $ri_level == "prg"){
        echo "ADD PROGRAM RISK ASSOCIATED PROJECTS";
      } elseif ($ri_type == "issue" && $ri_level == "prj"){
        echo "ADD PROJECT ISSUE ASSOCIATED PROJECTS";
      } else {
        echo "ADD PROGRAM ISSUE ASSOCIATED PROJECTS";
      }
      ?>
    </h3>
</div>
<div align="center">For:  <?php echo $name; ?></div>
    </br>
<!--HIDE ADD PROJECTS -->
<?php if($row_por_count['dacount'] == 0) {?>
  <div align="center" class="alert alert-danger" style="padding:20px; font-size:18px; font-color: #000000;">There are no project available to add to this Risk/Issue</div>
<?php } else {?>
  <form action="" method="post" class="navbar-form navbar-center" id="formfilter">
    <table align="center" cellpadding="0" cellspacing="0">
        <tbody>
        <tr align="center">
            <?php  //if($fiscal_year !=0) { ?>
            <td height="23">Program</td>
            <td>Subprogram</td>
            <td>Region</td>
            <td>Market</td>
            <td>Facility</td>
            <?php // } ?>
            <td>
            <input name="pStatus[]" type="hidden" value="Active">
            <input name="Owner" type="hidden" value="get owner">
            <input name="fiscal_year" type="hidden" value="<?php echo $fiscal_year?>">
            </td>
            <td>&nbsp;</td>
            </tr>
        <tr>
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
        
            <td><input name="Go" type="submit" id="Go" form="formfilter" value="Filter" class="btn btn-primary"></td>
            <td><a href="" title="Clear all filters"><span class="btn btn-default">Clear</span></a>    </td>
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        </tr>
      </tbody>
    </table>  
    </form>
    <?php if($ri_type == "risk" && $ri_level == "prj"){ ?>      
      <form action="../project-risk-update.php?uid=<?php echo $uid ?>&ri_type=<?php echo $ri_type ?>&action=<?php echo $action ?>&fiscal_year=<?php $fiscal_year?>" method="post" class="navbar-form navbar-center" id="assProjects" name="assProjects">
    <?php } elseif ($ri_type == "issue" && $ri_level == "prj"){ ?>   
      <form action="../project-issue-update.php?uid=<?php echo $uid ?>&ri_type=<?php echo $ri_type ?>&action=<?php echo $action ?>&fiscal_year=<?php $fiscal_year?>" method="post" class="navbar-form navbar-center" id="assProjects" name="assProjects">
    <?php } elseif ($ri_type == "risk" && $ri_level == "prg"){ ?> 
      <form action="../program-risk-update.php?uid=<?php echo $uid ?>&ri_type=<?php echo $ri_type ?>&action=<?php echo $action ?>&fiscal_year=<?php $fiscal_year?>" method="post" class="navbar-form navbar-center" id="assProjects" name="assProjects">
    <?php } elseif ($ri_type == "issue" && $ri_level == "prg"){ ?> 
      <form action="../program-issue-update.php?uid=<?php echo $uid ?>&ri_type=<?php echo $ri_type ?>&action=<?php echo $action ?>&fiscal_year=<?php $fiscal_year?>" method="post" class="navbar-form navbar-center" id="assProjects" name="assProjects">
    <?php } ?>

    <!-- HIDDEN VALUES -->
    <input type="hidden" name="rikey" id="rikey" value="<?php echo $rikey ?>">
    <input type="hidden" name="fscl_year" id="fscl_year" value="<?php echo $fiscal_year ?>">
    <input type="hidden" name="proj_name" id="proj_name" value="<?php echo $ri_proj_name ?>">
    <input type="hidden" name="status" id="status" value="<?php echo $status ?>">
    <input type="hidden" name="drivertime" value="<?php while ($row_ri_driver_lst = sqlsrv_fetch_array($stmt_ri_driver_lst, SQLSRV_FETCH_ASSOC)) { echo $row_ri_driver_lst['Driver_Nm'] . ','; } ?>">
    <input type="hidden" name="groupID" id="groupID" value="<?php echo $groupID ?>">
    <input type="hidden" name="changeLogKey" id="changeLogKey" value="4">

    <p>              
            <div align="center" class="alert alert-success" style="padding:20px; font-size:18px; font-color: #000000;">ADD - Check any projects you would like to add to this <?php echo $ri_type?>.</div>
          </p>
          <table width="100%" border="0" cellpadding="5" cellspacing="5" class="table table-bordered table-hover">
                    <tbody>
                      <tr>
                          <th bgcolor="#EFEFEF"><input type="checkbox" name="add_checkbox" id="add_checkbox" onClick="toggle(this)"></th>
                          <th bgcolor="#EFEFEF">Project Name</th>
                          <th bgcolor="#EFEFEF">Program</th>
                          <th bgcolor="#EFEFEF">Region</th>
                          <th bgcolor="#EFEFEF">Market</th>
                          <th bgcolor="#EFEFEF">Facility</th>
                      </tr>
                      <tr>
                          <td bgcolor="#d9edf7"><input type="checkbox" name="dummy" id="dummy" checked disabled></td> <!-- CHECK BOX FOR PROJECT SELECT -->
                          <td bgcolor="#d9edf7"><?php echo $row_projID['PROJ_NM'] ?> [ORIGINATING PROJECT]</td>
                          <td bgcolor="#d9edf7"><?php echo $row_projID['PRGM'] ?></td>
                          <td bgcolor="#d9edf7"><?php echo $row_projID['Region'] ?></td>
                          <td bgcolor="#d9edf7"><?php echo $row_projID['Market'] ?></td>
                          <td bgcolor="#d9edf7"><?php echo $row_projID['Facility'] ?></td>
                      </tr>
                      <?php while($row_por = sqlsrv_fetch_array( $stmt_por, SQLSRV_FETCH_ASSOC)) { ?>
                          <tr>
                              <td><input type="checkbox" name="add_proj_select[]" id="add_proj_select" value="<?php echo $row_por['PROJ_NM'];?>"></td> <!-- CHECK BOX FOR PROJECT SELECT -->
                              <td><?php echo $row_por['PROJ_NM'] ?></td>
                              <td><?php echo $row_por['PRGM'] ?></td>
                              <td><?php echo $row_por['Region'] ?></td>
                              <td><?php echo $row_por['Market'] ?></td>
                              <td><?php echo $row_por['Facility'] ?></td>
                          </tr>
                      <?php } ?>
                    </tbody>
          </table>
<?php } ?>
<!--STOP HIDE ADD PROJECT -->          
        <div align='center'> 
          <a href="javascript:void(0);" onclick="javascript:history.go(-1)"class="btn btn-primary">< Back </a>
          <?php if($row_por_count['dacount'] != 0) {?>
          <input name="selectedProjects" type="submit" id="selectedProjects" form="assProjects" value="Next >" class="btn btn-primary" disabled>
          <?php } ?> 
        </div>
    </form>
   
			    <?php //WHY IS THIS HERE?
          if(!empty($_POST['proj_select'])) {
          $assProjects = implode(',', $_POST['proj_select']);
          }
          ?>
</body>
<script>
$("input[name='add_proj_select[]']").on('change', function() {
  $("input[name='selectedProjects']").prop('disabled', !$("input[name='add_proj_select[]']:checked").length);
})
</script>
<script>
$("input[name='add_checkbox']").on('change', function() {
  $("input[name='selectedProjects']").prop('disabled', !$("input[name='add_checkbox']:checked").length);
})
</script>
</html>