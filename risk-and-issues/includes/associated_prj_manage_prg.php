<?php 
include ("../../includes/functions.php");
include ("../../db_conf.php");
include ("../../data/emo_data.php");
include ("../../sql/project_by_id.php");
include ("../../sql/ri_filter_vars.php");
include ("../../sql/ri_filters.php");
include ("../../sql/ri_filtered_data.php");

//DECLARE
$uid = $_GET['uid'];
$ri_type = $_GET['ri_type'];
$ri_level = $_GET['ri_level'];
$action = $_GET['action'];
$fiscal_year =  $_GET['fiscal_year'];
$ri_proj_name = $row_projID['PROJ_NM'];
$RiskAndIssue_Key = $_GET['rikey'];
$progRIKey = $_GET['progRIKey'];
$MLMProgNm = $_GET['prg_nm'];
$status = 1;

//GET  PROGRAM INFORMATION
$sql_prg_inf = "select * from RI_MGT.fn_GetListOfRiskAndIssuesForMLMProgram($fiscal_year,'$MLMProgNm')";
$stmt_prg_inf  = sqlsrv_query( $data_conn, $sql_prg_inf );
$row_prg_inf = sqlsrv_fetch_array( $stmt_prg_inf, SQLSRV_FETCH_ASSOC);

$MLMProgKey = $row_prg_inf['Program_Key'];
$regions = "";

//GET PROGRAM ASSOC PROJECTS FROM PROJECT KEY
$sql_prg_assc = "DECLARE @temp VARCHAR(MAX)
                SELECT @temp = COALESCE(@temp+', ' ,'') + EPSProject_Nm
                FROM [RI_MGT].[fn_GetListOfAssociatedProjectsForProgramRIKey] ($status,$progRIKey,$RiskAndIssue_Key)
                SELECT @temp AS eps_projects";
$stmt_prg_assc = sqlsrv_query( $data_conn, $sql_prg_assc );
$row_prg_assc= sqlsrv_fetch_array( $stmt_prg_assc, SQLSRV_FETCH_ASSOC);

$avRaw = $row_prg_assc ['eps_projects'];
$avCom = str_replace(", ","','",$avRaw);
$pjNames =  "'" . $avCom . "'";
//echo "<br><br>". $pjNames;
//echo "<br><br>".$sql_prg_assc;

//FIRST GET THE PROGRAM RI KEY
//$ri_name = $row_risk_issue['RI_Nm'];
$sql_progRIkey = "select * from RI_Mgt.fn_GetListOfAllRiskAndIssue($status) where RIlevel_Cd = 'Program' and RiskAndIssue_Key = $RiskAndIssue_Key";
$stmt_progRIkey  = sqlsrv_query( $data_conn, $sql_progRIkey  );
$row_progRIkey  = sqlsrv_fetch_array($stmt_progRIkey , SQLSRV_FETCH_ASSOC);
//echo $sql_progRIkey;
//exit();
$progRIkey = $row_progRIkey ['MLMProgramRI_Key']; 
$programKey = $row_progRIkey ['MLMProgram_Key'];
$riLog_Key =  $row_progRIkey ['RiskAndIssueLog_Key'];

//GET DISTINCT DRIVERS FOR UPDATE 
$sql_risk_issue_drivers_up = "select * from [RI_MGT].[fn_GetListOfDriversForRILogKey]($status) WHERE RiskAndIssueLog_Key = $riLog_Key";
$stmt_risk_issue_drivers_up  = sqlsrv_query( $data_conn, $sql_risk_issue_drivers_up);
$row_risk_issue_drivers_up  = sqlsrv_fetch_array($stmt_risk_issue_drivers_up , SQLSRV_FETCH_ASSOC);
$drivertime = $row_risk_issue_drivers_up['Driver_Nm']; 			
//echo $sql_risk_issue_drivers_up;
//exit();

//GET DISTINCT REGIONS
$sql_risk_issue_regions = "DECLARE @temp VARCHAR(MAX)
                  SELECT @temp = COALESCE(@temp+',' ,'') + MLMRegion_Cd
                  FROM RI_Mgt.fn_GetListOfAllRiskAndIssue($status) where RIlevel_Cd = 'Program' and RiskAndIssue_Key = $RiskAndIssue_Key
                  SELECT @temp AS eps_regions";
$stmt_risk_issue_regions  = sqlsrv_query( $data_conn, $sql_risk_issue_regions);
$row_risk_issue_regions  = sqlsrv_fetch_array($stmt_risk_issue_regions , SQLSRV_FETCH_ASSOC);
//echo $row_risk_issue_regions['Risk_Issue_Name']; 			
//echo $sql_risk_issue_regions . "<BR><BR>";
$regions = $row_risk_issue_regions['eps_regions'];

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
                  <div class="bs-wizard-info text-center">Select Associated Projects</div>
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
        echo "ADD/REMOVE PROJECT RISK ASSOCIATED PROJECTS";
      } elseif ($ri_type == "Risk" && $ri_level == "prg"){
        echo "ADD/REMOVE PROGRAM RISK ASSOCIATED PROJECTS";
      } elseif ($ri_type == "Issue" && $ri_level == "prj"){
        echo "ADD/REMOVE PROJECT ISSUE ASSOCIATED PROJECTS";
      } else {
        echo "ADD/REMOVE PROGRAM ISSUE ASSOCIATED PROJECTS";
      }
      ?>
    </h3>
</div>
<!-- <div align="center">Using Project: <?php // echo $ri_proj_name; ?></div> -->
<!-- <div align="center">Select any project associated with this Risk or Issue</div> -->
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
    <?php if ($ri_type === "Risk"){ ?> 
      <form action="../program-risk-update.php?formName=PRGR&assc_prj_update=yes&regions=<?php echo $regions?>&drivertime=<?php echo $drivertime; ?>&status=1&uid=<?php echo $uid ?>&progname=<?php echo $MLMProgNm; ?>&progkey=<?php echo $MLMProgKey?>&progRIkey=<?php echo $progRIKey; ?>&ri_type=<?php echo $ri_type ?>&action=<?php echo $action ?>&fscl_year=<?php echo $fiscal_year?>&rikey=<?php echo $RiskAndIssue_Key ?>" method="post" class="navbar-form navbar-center" id="assProjects" name="assProjects">
    <?php } elseif ($ri_type === "Issue"){ ?> 
      <form action="../program-risk-update.php?formName=PRGI&assc_prj_update=yes&regions=<?php echo $regions?>&drivertime=<?php echo $drivertime; ?>&status=1&uid=<?php echo $uid ?>&progname=<?php echo $MLMProgNm; ?>&progkey=<?php echo $MLMProgKey?>&progRIkey=<?php echo $progRIKey; ?>&ri_type=<?php echo $ri_type ?>&action=<?php echo $action ?>&fscl_year=<?php echo $fiscal_year?>&rikey=<?php echo $RiskAndIssue_Key ?>" method="post" class="navbar-form navbar-center" id="assProjects" name="assProjects">
    <?php } ?>
      <div align="center" class="aalert alert-info" style="padding:20px; font-size:18px; font-color: #000000;">Select projects to associate to this Program Risk/Issue.  Current associated projects are checked.</div>
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
                    <tr>
                            <td><input type="checkbox" name="proj_select[]" id="proj_select" value="<?php echo $row_ri['PROJ_NM'] ?>" checked></td> <!-- NO CHECKBOX -->
                            <td><?php echo $row_ri['PROJ_NM'] ?></td>
                            <td><?php echo $row_ri['PRGM'] ?></td>
                            <td><?php echo $row_ri['Region'] ?></td>
                            <td><?php echo $row_ri['Market'] ?></td>
                            <td><?php echo $row_ri['Facility'] ?></td>
                    </tr>
                    <?php while($row_por = sqlsrv_fetch_array( $stmt_por, SQLSRV_FETCH_ASSOC)) { 
                      $pject = $row_por['PROJ_NM'];
                      $sql_check = "select * from [RI_MGT].[fn_GetListOfAssociatedProjectsForProgramRIKey] ($RiskAndIssue_Key,$progRIKey,1) WHERE EPSProject_Nm = '$pject'";
                      $stmt_check = sqlsrv_query( $data_conn, $sql_check );
                      $row_check= sqlsrv_fetch_array( $stmt_check, SQLSRV_FETCH_ASSOC);
                      //echo $sql_check . "<br>";
                      ?>
                        <tr>
                            <td><input type="checkbox" name="proj_select[]" id="proj_select" value="<?php echo $row_por['PROJ_NM'];?>" <?php if(!empty($row_check)){echo "checked";} ?>  ></td> <!-- CHECK BOX FOR PROJECT SELECT -->
                            <td><?php echo $row_por['PROJ_NM'] ?></td>
                            <td><?php echo $row_por['PRGM'] ?></td>
                            <td><?php echo $row_por['Region'] ?></td>
                            <td><?php echo $row_por['Market'] ?></td>
                            <td><?php echo $row_por['Facility'] ?></td>
                        </tr>
                    <?php } ?>
                  </tbody>
        </table>
        <div align='center'> 
          <a href="javascript:void(0);" onclick="javascript:history.go(-1)"class="btn btn-primary">< Back </a>
          <input name="selectedProjects" type="submit" id="selectedProjects" form="assProjects" value="Next >" class="btn btn-primary"> 
        </div>
    </form>
			    <?php //WHY IS THIS HERE?
          if(!empty($_POST['proj_select'])) {
          $assProjects = implode(',', $_POST['proj_select']);
          }
          ?>
</body>
<script>
$("input[name='proj_select[]']").on('change', function() {
  $("input[name='selectedProjects']").prop('disabled', !$("input[name='proj_select[]']:checked").length);
})
</script>
<script>
$("input[name='checkbox']").on('change', function() {
  $("input[name='selectedProjects']").prop('disabled', !$("input[name='checkbox']:checked").length);
})
</script>
</html>