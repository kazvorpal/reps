<?php include ("../includes/functions.php");?>
<?php include ("../db_conf.php");?>
<?php include ("../data/emo_data.php");?>
<?php include ("../sql/collapse-details.php");?>
<?php include ("../sql/update-time.php");?>
<?php include ("../sql/dpr-plan.php");
$CPhase = $row_proj_clps['PHASE_NAME'];
?>


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
    <title>RePS - Regional Project Summary</title>
    <?php include ("../includes/load.php");?>
    <link href="../jQueryAssets/jquery.ui.core.min.css" rel="stylesheet" type="text/css">
    <link href="../jQueryAssets/jquery.ui.theme.min.css" rel="stylesheet" type="text/css">
    <link href="../jQueryAssets/jquery.ui.button.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="../colorbox-master/example1/colorbox.css" />
    <!-- Bootstrap -->
    <!-- <link rel="stylesheet" href="css/bootstrap.css"> -->
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    <link href="../css/bootstrap-3.3.4.css" rel="stylesheet" type="text/css">
    <script src="../bootstrap/js/jquery-1.11.2.min.js"></script>
    <script src="../colorbox-master/jquery.colorbox.js"></script>
</head>
<body>
<div class="panel-body" style="font-size:10px">
<div class="row">
  <div class="col-lg-2">
	<h4>PROJECT INFO</h4>
  <h5>Access Required </h5>
  <P>
  <a href="https://coxcomminc.sharepoint.com/sites/pwaeng/project detail pages/schedule.aspx?projuid=<?php echo htmlspecialchars($PUid)?>" target="_blank">EPS Project Schedule</a> 
  <br>
			<?php if($row_proj_clps['Prj_RiskAndIssue_Cnt'] > 0) { // prject risk and issues?>
            <a href="../ri2.php?uid=<?php echo htmlspecialchars($PUid) ?>&prj_name=<?php echo htmlspecialchars($row_proj_clps['PROJ_NM']);?>&count=<?php echo htmlspecialchars($row_proj_clps['Prj_RiskAndIssue_Cnt']) ?>" class="miframe">Project Risks& Issues (<?php echo htmlspecialchars($row_proj_clps['Prj_RiskAndIssue_Cnt']); ?>)</a>
      <?php } else { ?>
            <?php echo 'Project Risks& Issues (' . htmlspecialchars($row_proj_clps['Prj_RiskAndIssue_Cnt']) . ')'; ?>
      <?php } ?>
  <br>  
   	  <?php if($row_proj_clps['Prg_RiskAndIssue_Cnt'] > 0) { // program risk and issues?>
            <a href="../ri2-prg.php?proj_nm=<?php echo htmlspecialchars($row_proj_clps['PROJ_NM'])?>&uid=<?php echo htmlspecialchars($PUid) ?>&region=<?php echo htmlspecialchars($row_proj_clps['Region']);?>&program=<?php echo htmlspecialchars($row_proj_clps['PRGM']);;?>&fscl_year=<?php echo htmlspecialchars($row_proj_clps['FISCL_PLAN_YR']);?>&count=<?php echo htmlspecialchars($row_proj_clps['Prg_RiskAndIssue_Cnt']);?>" class="miframe">Program Risks& Issues (<?php echo htmlspecialchars($row_proj_clps['Prg_RiskAndIssue_Cnt']); ?>)</a>
      <?php } else { ?>
            <?php echo 'Project Risks& Issues (' . htmlspecialchars($row_proj_clps['Prg_RiskAndIssue_Cnt']) . ')' ;?>
      <?php } ?>
															</P>
  
  <h5>
  No Access Required
  </h5>
  <p>
  <a href="level_2_details_all.php?uid=<?php echo htmlspecialchars($PUid)?>" class="mapframe">Project Schedule</a>
  <br>
      <?php if($row_proj_clps['Prj_RiskAndIssue_Cnt'] > 0) { // prject risk and issues?>
            <a href="../ri-no_access_proj.php?uid=<?php echo htmlspecialchars($PUid) ?>&proj_name=<?php echo htmlspecialchars($row_proj_clps['PROJ_NM']);?>&count=<?php echo htmlspecialchars($row_proj_clps['Prj_RiskAndIssue_Cnt']) ?>" class="mapframe">Project Risks& Issues (<?php echo htmlspecialchars($row_proj_clps['Prj_RiskAndIssue_Cnt']); ?>)</a>
      <?php } else { ?>
            <?php echo 'Project Risks& Issues (' . htmlspecialchars($row_proj_clps['Prj_RiskAndIssue_Cnt']) . ')'; ?>
      <?php } ?>
  <br>
      <?php if($row_proj_clps['Prg_RiskAndIssue_Cnt'] > 0) { // program risk and issues?>
            <a href="../ri-no_access_prog.php?uid=<?php echo htmlspecialchars($PUid) ?>&region=<?php echo htmlspecialchars($row_proj_clps['Region']);?>&program=<?php echo htmlspecialchars($row_proj_clps['PRGM']);?>&fscl_year=<?php echo htmlspecialchars($row_proj_clps['FISCL_PLAN_YR']);?>&count=<?php echo htmlspecialchars($row_proj_clps['Prg_RiskAndIssue_Cnt'])?>" class="mapframe">Program Risks& Issues (<?php echo htmlspecialchars($row_proj_clps['Prg_RiskAndIssue_Cnt']); ?>)</a>
      <?php } else { ?>
            <?php echo 'Program Risks& Issues (' . htmlspecialchars($row_proj_clps['Prg_RiskAndIssue_Cnt']) . ')' ;?>
      <?php } ?>
  </p>

  </div>
  <div class="col-lg-5">
	<h4>PROJECT DATA</h4>
	
  <table width="100%" border="0" cellpadding="3" class="table-striped table-bordered table-hover">
    <tbody>
      <tr>
        <td width="25%">UID</td>
        <td><?php echo htmlspecialchars($_GET['uid'])?></td>
      </tr>
      <tr>
        <td>Project Name</td>
        <td><?php echo htmlspecialchars($row_proj_clps['PROJ_NM']);?></td>
      </tr>
      <tr>
        <td>Program</td>
        <td><?php echo htmlspecialchars($row_proj_clps['PRGM']);?></td>
      </tr>

      <tr>
        <td>Sub Program</td>
        <td><?php echo htmlspecialchars($row_proj_clps['Sub_Prg']);?></td>
      </tr>
      <tr>
        <td>Oracle Code</td>
        <td><?php echo str_replace('', '', htmlspecialchars($row_proj_clps['OracleProject_Cd']));?></td>
      </tr>
      <tr>
        <td>Oracle Start</td>
        <td><?php convtimex($row_proj_clps['OracleProjectStart_Dt']);?></td>
      </tr>      
      <tr>
        <td>Oracle End</td>
        <td><?php convtimex($row_proj_clps['OracleProjectEnd_Dt']);?></td>
      </tr>      
      <tr>
        <td>WATTS Master Order</td>
        <td><?php echo wattsRepl($row_proj_clps['WATTS_MO']);?></td>
      </tr>
      <tr>
        <td>Region</td>
        <td><?php echo htmlspecialchars($row_proj_clps['Region']);?></td>
      </tr>
      <tr>
        <td>Market</td>
        <td><?php echo htmlspecialchars($row_proj_clps['Market']);?></td>
      </tr>
      <tr>
        <td>Faclity</td>
        <td><?php echo htmlspecialchars($row_proj_clps['Facility']);?></td>
      </tr>
      <tr>
        <td>PPM No</td>
        <td><?php echo htmlspecialchars($row_proj_clps['PPM_PROJ']);?></td>
      </tr>
      <tr>
        <td>Owner</td>
        <td><?php echo htmlspecialchars($row_proj_clps['PROJ_OWNR_NM']);?></td>
      </tr>
      <tr>
        <td>Fiscal Year</td>
        <td><?php echo htmlspecialchars($row_proj_clps['FISCL_PLAN_YR']);?></td>
      </tr>
      <tr>
        <td>Project Type</td>
        <td><?php echo htmlspecialchars($row_proj_clps['ENTRPRS_PROJ_TYPE_NM']);?></td>
      </tr>
      <tr>
        <td>Workflow Stage</td>
        <td><?php echo htmlspecialchars($row_proj_clps['PHASE_NAME']);?></td>
      </tr>
      <tr>
        <td>Start Date</td>
        <td><?php echo convtimex($row_proj_clps['Plan_Start_Dt']);?></td>
      </tr>
      <tr>
        <td>Finish Date</td>
        <td><?php echo convtimex($row_proj_clps['Plan_Finish_Dt']);?>
        </td>
      </tr>
      <tr>
        <td>Commit Date</td>
        <td><?php echo convtimex($row_proj_clps['COMIT_DT']);?></td>
      </tr>
      <tr>     
        <td>Equipment Type 1</td>
        <td>(<?php echo htmlspecialchars($row_proj_clps['Equip1_Cnt']); ?>) <?php echo htmlspecialchars($row_proj_clps['Equip1_TYPE']);?></td>
      </tr>
      <tr>     
        <td>Equipment Type 2</td>
        <td>(<?php echo htmlspecialchars($row_proj_clps['Equip2_Cnt']); ?>) <?php echo htmlspecialchars($row_proj_clps['Equip2_TYPE']);?></td>
      </tr>
      <tr>     
        <td>Equipment Type 3</td>
        <td>(<?php echo htmlspecialchars($row_proj_clps['Equip3_Cnt']); ?>) <?php echo htmlspecialchars($row_proj_clps['Equip3_TYPE']);?></td>
      </tr>
      <tr>     
        <td>Equipment Type 4</td>
        <td>(<?php echo htmlspecialchars($row_proj_clps['Equip4_Cnt']); ?>) <?php echo htmlspecialchars($row_proj_clps['Equip4_TYPE']);?></td>
      </tr>
								                                                <tr>     
        <td>OA Health Summary</td>
        <td><?php echo htmlspecialchars($row_proj_clps['CURR_STAT_SUM']); ?></td>
      </tr>
                                   
    </tbody>
  </table>
	</div>
  <div class="col-lg-5">
                            <h4>EQUIPMENT PLAN</h4>
														 
 <?php while($row_plan = sqlsrv_fetch_array( $stmt_plan, SQLSRV_FETCH_ASSOC)) { ?>    
  <table width="100%" border="0" cellpadding="3" class="table-bordered table-hover">
    <tbody>
    <tr style="padding: 3px">
        <td width="250px" style="background-color: #337AB7; color: #FFFFFF; padding: 3px">Equipment ID</td>
        <td><?php echo $row_plan['EquipPlan_Id'] ?></td>
      </tr>
      <tr>
        <td width="250px" style="background-color: #337AB7; color: #FFFFFF; padding: 3px">Kit Name</td>
        <td><?php echo $row_plan['Kit_Nm'] ?></td>
      </tr>
      <tr>
        <td style="background-color: #337AB7; color: #FFFFFF; padding: 3px">EQ Qty</td>
        <td><?php echo $row_plan['POR_Qty'] ?></td>
      </tr>
      <tr>
        <td style="background-color: #337AB7; color: #FFFFFF; padding: 3px">POR Need-by-Date</td>
        <td><?php echo convtimex($row_plan['POR_NeedBy_Dt']) ?></td>
      </tr>
      <tr>
        <td style="background-color: #337AB7; color: #FFFFFF; padding: 3px">POR Activation Date</td>
        <td><?php echo convtimex($row_plan['POR_Activation_Dt']) ?></td>
      </tr>
      <tr>
        <td style="background-color: #337AB7; color: #FFFFFF; padding: 3px">POR Migration Date</td>
        <td><?php echo convtimex($row_plan['POR_Migration_Dt']) ?></td>
      </tr>
      <tr>
        <td style="background-color: #337AB7; color: #FFFFFF; padding: 3px">MSProject Installation Phase Complete</td>
        <td>
          <?php 
          if($CPhase == "04 Execute" || $CPhase == "05 Closing"){
            convtimex($row_plan['MSP_Install_Finish_Dt']);
          } else {
            echo "---";
          }
          ?>
        </td>
      </tr>
      <tr>
        <td style="background-color: #337AB7; color: #FFFFFF; padding: 3px">MSProject Migration Phase Complete</td>
        <td>
          <?php 
          if($CPhase == "04 Execute" || $CPhase == "05 Closing"){
            echo convtimex($row_plan['MSP_Migration_Finish_Dt']); 
          } else {
            echo "---";
          }
          ?>
        </td>
      </tr>
    </tbody>
  </table><br><br>
<?php } ?>
                               
  </div>
</div>
</div>
												
</body>
</html>