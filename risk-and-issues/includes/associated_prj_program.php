<?php include ("../../includes/functions.php");?>
<?php include ("../../db_conf.php");?>
<?php include ("../../data/emo_data.php");?>
<?php // include ("../sql/collapse.php");?>
<?php include ("../../sql/project_by_id.php");?>
<?php include ("../../sql/ri_filter_vars.php");?>
<?php include ("../../sql/ri_filters.php");?>
<?php include ("../../sql/ri_filtered_data.php");?>
<?php include ("../../sql/RI_Internal_External.php");?>
<?php
      $uid = $_GET['uid'];
      $ri_type = $_GET['ri_type'];
      $action = $_GET['action'];
      $fiscal_year =  $_GET['fiscal_year'];
      $tempid =  $_GET['tempid'];
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
</script>
</head>

<body style="background: #F8F8F8;">

  <div align="center" class="finePrint"><?php //echo $sql_por?></div>
  <div align="Center">
    <h1>
      <?php
      if($ri_type == "risk"){
      echo "Create Program Risk";
      } else {
      echo "Create Program Issue";
      }
      ?>
    </h1>
</div>
  <div align="Center">Select any project associated with this Risk or Issue</div>
  <form action="" method="post" class="navbar-form navbar-center" id="formfilter" title="formfilter">
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
            <input name="fiscal_year" type="hidden" value="<?php echo $_GET['fiscal_year']?>">
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
    <?php if($ri_type == "risk"){    ?>      
    <form action="../program-risk.php?uid=<?php echo $uid ?>&ri_type=<?php echo $ri_type ?>&action=<?php echo $action ?>&fiscal_year=<?php $fiscal_year?>&tempid=<?php echo $tempid ?>" method="post" class="navbar-form navbar-center" id="assProjects" name="assProjects">
    <?php } else {    ?>   
    <form action="../program-issue.php?uid=<?php echo $uid ?>&ri_type=<?php echo $ri_type ?>&action=<?php echo $action ?>&fiscal_year=<?php $fiscal_year?>&tempid=<?php echo $tempid ?>" method="post" class="navbar-form navbar-center" id="assProjects" name="assProjects">
    <?php } ?>   
        <table width="100%" border="0" cellpadding="5" cellspacing="5" class="table table-bordered table-hover">
                  <tbody>
                    <tr>
                        <th bgcolor="#EFEFEF"><input type="checkbox" name="checkbox" id="checkbox" onClick="toggle(this)"></th>
                        <th bgcolor="#EFEFEF">Project Name</th>
                        <th bgcolor="#EFEFEF">Program</th>
                        <th bgcolor="#EFEFEF">Region</th>
                        <th bgcolor="#EFEFEF">Market</th>
                        <th bgcolor="#EFEFEF">Facility</th>
                    </tr>
                    <?php while($row_por = sqlsrv_fetch_array( $stmt_por, SQLSRV_FETCH_ASSOC)) { ?>
                        <tr>
                            <td><input type="checkbox" name="proj_select[]" id="proj_select" value="<?php echo $row_por['PROJ_NM'];?>"></td> <!-- CHECK BOX FOR PROJECT SELECT -->
                            <td><?php echo $row_por['PROJ_NM'] ?></td>
                            <td><?php echo $row_por['PRGM'] ?></td>
                            <td><?php echo $row_por['Sub_Prg'] ?></td>
                            <td><?php echo $row_por['Market'] ?></td>
                            <td><?php echo $row_por['Facility'] ?></td>
                        </tr>
                    <?php } ?>
                  </tbody>
        </table>
        <div align='center'> 
          <a href="javascript:history.back()"  class="btn btn-primary">< Back </a> 
          <input name="selectedProjects" type="submit" id="selectedProjects" form="assProjects" value="Add Selected Projects" class="btn btn-primary"> 
        </div>
    </form>
			    <?php 
          if(!empty($_POST['proj_select'])) {
          $assProjects = implode(',', $_POST['proj_select']);
          }
          ?>
</body>
</html>