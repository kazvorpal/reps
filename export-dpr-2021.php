<?php include ("includes/functions.php");?>
<?php include ("db_conf.php");?>
<?php include ("data/emo_data.php");?>
<?php 
// workflow_stage
$bmysql = $_GET['sql'];
$sql_por = "$bmysql"; 
$stmt_por = sqlsrv_query( $conn_COXProd, $sql_por );
//$stmt_por = sqlsrv_query( $conn_COX_QA, $sql_por );


//mysql_select_db($database_data, $data);
//$query_program_n = "$bmysql";
//$program_n = mysql_query($query_program_n, $data) or die(mysql_error());
//$row_program_n = mysql_fetch_assoc($program_n);
//$totalRows_program_n = mysql_num_rows($program_n);


 header("Content-type: application/vnd.ms-excel; name='excel'");
 header("Content-Disposition: attachment; filename=export_DPR.xls");
 header("Pragma: no-cache");
 header("Expires: 0");
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<?php //echo $bmysql?>

<table border="1" cellpadding="5" cellspacing="0" class="table-striped table-bordered table-hover ">
  <thead>
    <tr align="center" valign="middle" style="color:#FFFFFF; font-size:10px; padding:2px; background-color; #000000">
	  <th bgcolor="#337AB7"><div align="center">STATUS</div></th>
	  <th bgcolor="#337AB7"><div align="center">PROGRAM</div></th>
      <th bgcolor="#337AB7"><div align="center">SUBPROGRAM</div></th>
      <th bgcolor="#337AB7"><div align="center">PROJECT NAME</div></th>
      <th bgcolor="#337AB7"><div align="center">OWNER</div></th>
      <th bgcolor="#337AB7"><div align="center">FY</div></th>
      <th bgcolor="#337AB7"><div align="center">REGION</div></th>
      <th bgcolor="#337AB7"><div align="center">MARKET</div></th>
      <th bgcolor="#337AB7"><div align="center">FACILITY</div></th>
      <th bgcolor="#337AB7">ORACLE CODE</th>
      <th bgcolor="#337AB7">WATTS MO</th>
      <!-- <th ><strong>ORACLE CODE</strong></th> -->
      <!-- <th ><strong>ORACLE START</strong></th> -->
      <!-- <th ><strong>ORACLE END</strong>< -->
      <!-- <th ><strong>WATTS MO</strong></th> -->
      <th bgcolor="#337AB7"><div align="center">STAGE</div></th>
	  <!--<th bgcolor="#337AB7"><div align="center">POR NEED BY DATE</div></th>-->
      <!--<th bgcolor="#337AB7"><div align="center">POR ACTIVATION DATE</div></th>-->
      <!--<th bgcolor="#337AB7"><div align="center">POR MIGRATION DATE</div></th>-->
      <th width="70" bgcolor="#337AB7"><div align="center">START MTH/YR</div></th>
      <th width="70" bgcolor="#337AB7"><div align="center">START DATE</div></th>
      <th width="70" bgcolor="#337AB7"><div align="center">FINISH MTH/YR</div></th>
      <th width="70" bgcolor="#337AB7"><div align="center">FINISH DATE</div></th>
      <th bgcolor="#337AB7"><div align="center">INITIATING</div></th>
      <th bgcolor="#337AB7"><div align="center">INITIATE DATE</div></th>
      <th bgcolor="#337AB7"><div align="center">PLANNING</div></th>
      <th bgcolor="#337AB7"><div align="center">PLAN DATE</div></th>
      <th bgcolor="#337AB7"><div align="center">EXECUTING<span class="glyphicon glyphicon-triangle-right" style="font-size:10px"></span></div></th>
      <th bgcolor="#337AB7"><div align="center">EXECUTE DATE</div></th>
      <th bgcolor="#34AEE8"><div align="center">SITE PREP</div></th>
      <th bgcolor="#34AEE8"><div align="center">SITE PREP DATE</div></th>
      <th bgcolor="#34AEE8"><div align="center">INSTALLATION</div></th>
      <th bgcolor="#34AEE8"><div align="center">INSTALL DATE</div></th>
      <th bgcolor="#34AEE8"><div align="center">MIGRATION</div></th>
      <th bgcolor="#34AEE8"><div align="center">MIGRATE DATE</div></th>
      <th bgcolor="#34AEE8"><div align="center">DECOMMISSION</div></th>
      <th bgcolor="#34AEE8"><div align="center">DECOM DATE</div></th>
      <th bgcolor="#337AB7"><div align="center">CLOSING</div></th>
      <th bgcolor="#337AB7">CLOSE DATE</th>
	  <th width="50" bgcolor="#337AB7"><div align="center">PRJ R/I</div></th>
	  <th width="50" bgcolor="#337AB7"><div align="center">PRG R/I</div></th>
	  <th bgcolor="#337AB7"><div align="center">OA HLTH</div></th>
	  <th bgcolor="#337AB7">OA HLTH SUMMARY</th>
    </tr>
    </thead>
    <tbody>
    <?php while($row_program_n = sqlsrv_fetch_array( $stmt_por, SQLSRV_FETCH_ASSOC)) { ?>
    		<?php 
							// COLOR LOGIC
							
							// <Stage name>_Flg                     Stage Flag, 0 for project has no stage, 1 has a stage
							// <Stage name>_Pln_Dt                  Stage Plan Date
							// <Stage name>_Act_Dt                  Stage Actual Date
							// <Stage name>_Late_Flg                Stage Late flag (0 for No, 1 for Late, Null for no stage)
							
							// FOR ALL NOT IN EXECUTE OR CLOSING
							$grey_stages = 1;
							if($row_program_n['PHASE_NAME'] == '01 Proposed' || $row_program_n['PHASE_NAME'] == '02 Allocated' || $row_program_n['PHASE_NAME'] == '03 Released' || /*(*/$row_program_n['ENTRPRS_PROJ_TYPE_NM'] == 'Reporting Only' /*&& $row_program_n['FISCL_PLAN_YR'] != '2021' )*/ || $row_program_n['ENTRPRS_PROJ_TYPE_NM'] == '' || $row_program_n['PHASE_NAME'] == 'Cancelled') {
							$grey_stages = 0;
							}
							
							// FOR ALL NOT IN EXECUTE OR CLOSING (INITIATING AND PLANING )
              				$grey_stagesRLS = 1;
							if($row_program_n['PHASE_NAME'] == '01 Proposed' || $row_program_n['PHASE_NAME'] == '02 Allocated' || /*(*/$row_program_n['ENTRPRS_PROJ_TYPE_NM'] == 'Reporting Only' /*&& $row_program_n['FISCL_PLAN_YR'] != '2021' )*/ || $row_program_n['ENTRPRS_PROJ_TYPE_NM'] == '' || $row_program_n['PHASE_NAME'] == 'Cancelled') {
							$grey_stagesRLS = 0;
							}
														
							// execute prep cell color
							$exe = '#00d257'; // Cox Green
                            if($row_program_n['Executing_Flg'] != 1 || $row_program_n['Executing_Act_Dt'] == ''){ // 0 = has no stage | 1 = has stage
								$exe = '#00aaf5'; //Cox Blue
							}
							
                                 // red logic fixed; do not show red on day of exec prep
                                if(is_null($row_program_n['Executing_Pln_Dt'])){ 
                                
                                    if($row_program_n['Executing_Late_Flg'] == 1){ // 0 = not late | 1 = late c1c1c1
								    $exe = 'red'; // Red
							        }

                                } else {

                                    $execPDx = $row_program_n['Executing_Pln_Dt'];
								    $execPD = date_format($execPDx, 'm-d-Y');
								    $execTD = date('m-d-Y');

                                    if($row_program_n['Executing_Late_Flg'] == 1 && $execPD != $execTD){ //if exec prep late flag = 1 and exec prep plan date is not equal to today
								    $exe = 'red'; // Red
                                    }
                                }

							if($grey_stages == 0 || $row_program_n['Executing_Flg'] != 1){ // if reporting only and not in Execute Prep then turn grey
								$exe = '#c1c1c1'; 
							}
																					
							// site prep cell color
							$site = '#00d257';
							if($row_program_n['Exec_Site_Prep_Flg'] != 1 || $row_program_n['Exec_Site_Prep_Act_Dt'] == ''){
								$site = '#00aaf5'; // Cox Blue
							}
                                // red logic fixed; do not show red on day of site prep
                                if(is_null($row_program_n['Exec_Site_Prep_Pln_Dt'])){ 

							        if($row_program_n['Exec_Site_Prep_Late_Flg'] == 1){
								    $site = 'red';
                                    }

                                } else {

                                    $sitePDx = $row_program_n['Exec_Site_Prep_Pln_Dt'];
								    $sitePD = date_format($sitePDx, 'm-d-Y');
								    $siteTD = date('m-d-Y');

                                    if($row_program_n['Exec_Site_Prep_Late_Flg'] == 1 && $sitePD != $siteTD){ //if site prep late flag = 1 and site prep plan date is not equal to today
								    $site = 'red';
                                    }
                                }
							if($grey_stages == 0 || $row_program_n['Exec_Site_Prep_Flg'] != 1 ){
								$site = '#c1c1c1';
							}
								
                            // install cell color							
							
							$instal = '#00d257';

							if($row_program_n['Exec_Installation_Flg'] != 1 || $row_program_n['Exec_Installation_Act_Dt'] == ''){
								$instal = '#00aaf5';
							}
                                // red logic fixed; do not show red on day of install
							    if (is_null($row_program_n['Exec_Installation_Pln_Dt'])){

								    if($row_program_n['Exec_Installation_Late_Flg'] == 1){ 
                                    $instal = 'red'; 
                                    }

							    } else {

								    $installPDx = $row_program_n['Exec_Installation_Pln_Dt'];
								    $installPD = date_format($installPDx, 'm-d-Y');
								    $installTD = date('m-d-Y');
									
							        if($row_program_n['Exec_Installation_Late_Flg'] == 1 && $installPD != $installTD){  // if install late flag = 1 and install plan date is not equal to today
                                    $instal = 'red'; 
                                    }
							    }

							if($grey_stages == 0 || $row_program_n['Exec_Installation_Flg'] != 1){
								$instal = '#c1c1c1';
							}

							
							// migration cell color
							
							$migr = '#00d257'; //cox green
							if($row_program_n['Exec_Migration_Pln_Dt'] != '' || $row_program_n['Exec_Migration_Late_Flg'] != 1){
								$migr = '#00aaf5'; //cox blue
							}
                             // red logic fixed; do not show red on day of migration
							    if (is_null($row_program_n['Exec_Migration_Pln_Dt'])){

							        if($row_program_n['Exec_Migration_Late_Flg'] == 1) {
								        $migr = 'red';
							        }

                                } else {

   								    $migratePDx = $row_program_n['Exec_Migration_Pln_Dt'];
								    $migratePD = date_format($migratePDx, 'm-d-Y');
								    $migrateTD = date('m-d-Y');  
                                    
                                    if($row_program_n['Exec_Migration_Late_Flg'] == 1 && $migratePD != $migrateTD) {
								        $migr = 'red';
							        }
                                }
							if($row_program_n['Exec_Migration_Act_Dt'] != '') {
								$migr = '#00d257';
							}
							if($grey_stages == 0 || $row_program_n['Exec_Migration_Pln_Dt'] == '' ){
								$migr = '#c1c1c1'; //grey
							} 
								
                            // decom cell color
							$decm = '#00d257'; // cox green
							if($row_program_n['Exec_Decommission_Flg'] != 1 || $row_program_n['Exec_Decommission_Act_Dt'] == '' ){
								$decm = '#00aaf5'; //cox blue
							}
                             // red logic fixed; do not show red on day of decom
							    if (is_null($row_program_n['Exec_Decommission_Pln_Dt'])){

							        if($row_program_n['Exec_Decommission_Late_Flg'] == 1){
								        $decm = 'red';
							        }

                                } else {

                                    $decomPDx = $row_program_n['Exec_Decommission_Pln_Dt'];
								    $decomPD = date_format($decomPDx, 'm-d-Y');
								    $decomTD = date('m-d-Y'); 

                                    if($row_program_n['Exec_Decommission_Late_Flg'] == 1 && $decomPD != $decomTD){
								        $decm = 'red';
							        }
                				}

							if($grey_stages == 0 || $row_program_n['Exec_Decommission_Pln_Dt'] == '' ){
								$decm = '#c1c1c1';
							}
							
							// INITIATING CELL COLOR
							$init = '#00d257'; // cox green
							if($row_program_n['Initiating_Flg'] != 1 || $row_program_n['Initiating_Act_Dt'] == '' ){
								$init = '#00aaf5'; //cox blue
							}
							  // red logic fixed; do not show red on day of decom
											if (is_null($row_program_n['Initiating_Pln_Dt'])){
			
									if($row_program_n['Initiating_Late_Flg'] == 1){
									  $init = 'red';
									}
							  
							  } else if ($row_program_n['PHASE_NAME'] == '03 Released') {
									$initPDx = $row_program_n['Initiating_Pln_Dt'];
									$initPD = date_format($initPDx, 'm-d-Y');
									$initTD = date('m-d-Y'); 
			
									//for Released function
									// missing release date fix
                                    if(is_null($row_program_n['Released_Dt'])){
										$iDate = date('Y-m-d');
									  } else {
										$iDate = date_format($row_program_n['Released_Dt'],'Y-m-d'); 
									  }
									  // end
  
									$iDatex = $iDate;
									$iDate8 = date('Y-m-d', strtotime($iDatex. ' + 8 days'));
									$iToday = date('Y-m-d');
									
			
									$start = strtotime($iDatex);
									$end = strtotime($iToday);
									$holidays=array("2021-01-01","2021-01-18","2021-05-31","2021-07-05","2021-09-06","2021-11-25","2021-12-24","2021-12-31");
									$days_between = getWorkingDays($iDate8,$iToday,$holidays);
			
									if($row_program_n['Initiating_Late_Flg'] == 1 && $initPD != $initTD && $days_between > 8){
									//if($days_between > 8){
									  $init = 'red';
									}
			
							  } else {
			
									$initPDx = $row_program_n['Initiating_Pln_Dt'];
									$initPD = date_format($initPDx, 'm-d-Y');
									$initTD = date('m-d-Y'); 
			
									if($row_program_n['Initiating_Late_Flg'] == 1 && $initPD != $initTD){
									  $init = 'red';
									}
								}
			
										if($grey_stagesRLS == 0 || $row_program_n['Initiating_Pln_Dt'] == '' ){
											$init = '#c1c1c1';
										}
										
										// PLANNING CELL COLOR
										$plnng = '#00d257'; // cox green
										if($row_program_n['Planning_Flg'] != 1 || $row_program_n['Planning_Act_Dt'] == '' ){
											$plnng = '#00aaf5'; //cox blue
										}
						  
						  // red logic fixed; do not show red on day of decom
										if (is_null($row_program_n['Planning_Pln_Dt'])){
			
											  if($row_program_n['Planning_Late_Flg'] == 1){
												$plnng = 'red';
											  }
			
						  } else if ($row_program_n['PHASE_NAME'] == '03 Released') {
								
								$plnngPDx = $row_program_n['Planning_Pln_Dt'];
								$plnngPD = date_format($plnngPDx, 'm-d-Y');
								$plnngTD = date('m-d-Y'); 
			
								//for Released function
								// missing date fix
                                if(is_null($row_program_n['Released_Dt'])){
									$iDate2 = date('Y-m-d');
								  } else {
									$iDate2 = date_format($row_program_n['Released_Dt'],'Y-m-d'); 
								  }
								  //end 
									
								$iDatex2 = $iDate2;
								$iDate82 = date('Y-m-d', strtotime($iDatex2. ' + 8 days'));
								$iToday2 = date('Y-m-d');
			
								$start2 = strtotime($iDatex2);
								$end2 = strtotime($iToday2);
								$holidays2=array("2021-01-01","2021-01-18","2021-05-31","2021-07-05","2021-09-06","2021-11-25","2021-12-24","2021-12-31");
								$days_between2 = getWorkingDays($iDate82,$iToday2,$holidays2);
			
								if($row_program_n['Planning_Late_Flg'] == 1 && $plnngPD != $plnngTD && $days_between2 > 8){
								//if($days_between2 > 8){  
									$plnng = 'red';
								}
							  
						  } else {
			
								$plnngPDx = $row_program_n['Planning_Pln_Dt'];
												$plnngPD = date_format($plnngPDx, 'm-d-Y');
												$plnngTD = date('m-d-Y'); 
			
								if($row_program_n['Planning_Late_Flg'] == 1 && $plnngPD != $plnngTD){
													$plnng = 'red';
											  }
						  }
			
										if($grey_stagesRLS == 0 || $row_program_n['Planning_Pln_Dt'] == '' ){
											$plnng = '#c1c1c1';
										}
										
							// CLOSING CELL COLOR
							$clsng = '#00d257'; // cox green
							if($row_program_n['Closing_Flg'] != 1 || $row_program_n['Closing_Act_Dt'] == '' ){
								$clsng = '#00aaf5'; //cox blue
							}
                             // red logic fixed; do not show red on day of decom
							    if (is_null($row_program_n['Closing_Pln_Dt'])){

							        if($row_program_n['Closing_Late_Flg'] == 1){
								        $clsng = 'red';
							        }

                                } else {

                                    $clsngPDx = $row_program_n['Closing_Pln_Dt'];
								    $clsngPD = date_format($clsngPDx, 'm-d-Y');
								    $clsngTD = date('m-d-Y'); 

                                    if($row_program_n['Closing_Late_Flg'] == 1 && $clsngPD != $clsngTD){
								        $clsng = 'red';
							        }
                				}

							if($grey_stages == 0 || $row_program_n['Closing_Pln_Dt'] == '' ){
								$clsng = '#c1c1c1';
							}
							
								
							
							//Trim UID Brackets
							//$uid_x = substr($row_program_n['PROJ_ID'],1,-1);
							$uid_x = $row_program_n['PROJ_ID'];
							$region_clps_mtch = htmlspecialchars($row_program_n['Region']);
							$program_clps_mtch = htmlspecialchars($row_program_n['PRGM']);
							
							// Risk and Issues Indicator
//							$projRct = $row_program_n['Prj_Risk_Cnt'];
//							$projIct = $row_program_n['prj_Issue_Cnt'];
//							$progRct = $row_program_n['Prg_Risk_Cnt'];
//							$progIct = $row_program_n['Prg_Issue_Cnt'];
//							
//							$riskColor = "";
//							if ($projRct >= 1 or $progRct >= 1 or $projIct >= 1 or $progIct >= 1 ) {
//								$riskColor = ''; //broken on purpose - $riskColor = 'yellow';
//								}
//							
//							$issueORrisk = "None";
//							if ($projIct >= 1 or $progIct >= 1 ) {
//									$issueORrisk = '--'; // broken on purpose - $issueORrisk = 'Issue';
//								} else if ($projRct >= 1 or $progRct >= 1) {
//									$issueORrisk = '--';  // broken on purpose - $issueORrisk = 'Risk';
//								} else {
//									$issueORrisk = '--';
//								}
//								
								if ($row_program_n['Prj_RiskAndIssue_Cnt'] > 0) {
									$proj_clr = '#fcd12a';
								} else { 
									$proj_clr = '';
								}
								
								if ($row_program_n['Prg_RiskAndIssue_Cnt'] > 0) {
									$prog_clr = '#fcd12a';  
								} else { 
									$prog_clr = '';
								}						
                            ?>
    
    <tr align="left" valign="middle" style="font-size:11px">
	  <td style="padding:2px"><?php echo htmlspecialchars($row_program_n['PROJ_STAT']);?></td>
      <td style="padding:2px"><?php echo htmlspecialchars($row_program_n['PRGM']);?></td>
      <td style="padding:2px"><?php echo htmlspecialchars($row_program_n['Sub_Prg']);?></td>
      <td style="padding:2px"><?php echo htmlspecialchars($row_program_n['PROJ_NM']);?></td>
      <td style="padding:2px"><?php echo htmlspecialchars($row_program_n['PROJ_OWNR_NM']);?></td>
	  <td style="padding:2px" align="center"><?php echo htmlspecialchars($row_program_n['FISCL_PLAN_YR']);?></td>
      <td style="padding:2px"><?php echo htmlspecialchars($row_program_n['Region']);?></td>
      <td style="padding:2px"><?php echo htmlspecialchars($row_program_n['Market']);?></td>
      <td style="padding:2px"><?php echo htmlspecialchars($row_program_n['Facility']);?></td>
      <td style="padding:2px"><?php echo htmlspecialchars($row_program_n['OracleProject_Cd']);?></td>
      <td style="padding:2px"><?php echo wattsRepl($row_program_n['WATTS_MO']);?></td>

      <td style="padding:2px" bgcolor="<?php pcntComp($row_program_n['PrjComplete_Pct'], $row_program_n['PHASE_NAME']);?>" >
          <div align="center">
          	<?php 
              $PhaseName = $row_program_n['PHASE_NAME'];
              $PhaseNameShort = strstr($PhaseName, ' ' );
              echo $PhaseNameShort; //. '<br>'; //modified for dev
              
            //if ($row_program_n['PHASE_NAME'] == '03 Released') {
            //   echo '<br>' . $days_between . ' Days';
            //}
            ?>
          </div>
      </td>
	  
      <!--3/1 POR Fields Added-->
	  <!--<td style="padding:2px" align="center"><?php // echo convtimeDPR($row_program_n['POR_Needby_Dt']);?></td>-->
      <!--<td style="padding:2px" align="center"><?php // echo convtimeDPR($row_program_n['POR_Migration_Dt']);?></td>-->
      <!--<td style="padding:2px" align="center"><?php //echo convtimeDPR($row_program_n['POR_Activation_Dt']);?></td>-->
      <!-- -->
      
      <!--3/1 Month Year Added - Convert current date to month/year-->
      <td style="padding:2px" align="center"><?php monthYear($row_program_n['Plan_Start_Dt']);?></td>
      <!-- -->
      
      <td style="padding:2px" align="center"><?php echo convtimeDPR($row_program_n['Plan_Start_Dt']);?></td>
      
      <!--3/1 Month Year Added - Convert current date to month/year-->
      <td style="padding:2px" align="center"><?php monthYear($row_program_n['Plan_Finish_Dt'])?></td>
      <!-- -->
      
      <td style="padding:2px" align="center"><?php fnshdt($row_program_n['PHASE_NAME'],$row_program_n['Plan_Finish_Dt'], $row_program_n['FISCL_PLAN_YR'])?></td>


      <td width="35" align="center" bgcolor="<?php echo htmlspecialchars($init);?>" style="color:#FFFFFF; font-size:9px"><?php pcntshwR($row_program_n['Initiating_Cmpl_Prct'], $row_program_n['Initiating_Pln_Dt'], $row_program_n['PHASE_NAME'], $row_program_n['Initiating_Act_Dt'], $uid_x, $row_program_n['ENTRPRS_PROJ_TYPE_NM']) ?></td>
      <td width="50" align="center" bgcolor="<?php echo htmlspecialchars($init);?>" style="color:#FFFFFF; font-size:9px"><?php dateshwNLR($row_program_n['Initiating_Pln_Dt'], $row_program_n['PHASE_NAME'], $row_program_n['Initiating_Act_Dt'], $uid_x, $row_program_n['ENTRPRS_PROJ_TYPE_NM']) ?></td>
      <td width="35" align="center" bgcolor="<?php echo htmlspecialchars($plnng);?>" style="color:#FFFFFF; font-size:9px"><?php pcntshwR($row_program_n['Planning_Cmpl_Prct'], $row_program_n['Planning_Pln_Dt'], $row_program_n['PHASE_NAME'], $row_program_n['Planning_Act_Dt'], $uid_x, $row_program_n['ENTRPRS_PROJ_TYPE_NM'])?></td>
      <td width="50" align="center" bgcolor="<?php echo htmlspecialchars($plnng);?>" style="color:#FFFFFF; font-size:9px"><?php dateshwNLR($row_program_n['Planning_Pln_Dt'], $row_program_n['PHASE_NAME'], $row_program_n['Planning_Act_Dt'], $uid_x, $row_program_n['ENTRPRS_PROJ_TYPE_NM']) ?></td>
      <td width="35" align="center" bgcolor="<?php echo htmlspecialchars($exe);?>" style="color:#FFFFFF; font-size:9px"><?php pcntshw($row_program_n['Executing_Cmpl_Prct'], $row_program_n['Executing_Pln_Dt'], $row_program_n['PHASE_NAME'], $row_program_n['Executing_Act_Dt'], $uid_x, $row_program_n['ENTRPRS_PROJ_TYPE_NM'])?></td>
      <td width="50" align="center" bgcolor="<?php echo htmlspecialchars($exe);?>" style="color:#FFFFFF; font-size:9px"><?php dateshwX($row_program_n['Executing_Pln_Dt'], $row_program_n['PHASE_NAME'], $row_program_n['Executing_Act_Dt'], $uid_x, $row_program_n['ENTRPRS_PROJ_TYPE_NM']) ?></td>
      <td width="35" align="center" bgcolor="<?php echo htmlspecialchars($site);?>" style="color:#FFFFFF; font-size:9px"><?php pcntshw($row_program_n['Exec_Site_Prep_Cmpl_Prct'], $row_program_n['Exec_Site_Prep_Pln_Dt'], $row_program_n['PHASE_NAME'], $row_program_n['Exec_Site_Prep_Act_Dt'], $uid_x, $row_program_n['ENTRPRS_PROJ_TYPE_NM'])?></td>
      <td width="50" align="center" bgcolor="<?php echo htmlspecialchars($site);?>" style="color:#FFFFFF; font-size:9px"><?php dateshwX($row_program_n['Exec_Site_Prep_Pln_Dt'], $row_program_n['PHASE_NAME'], $row_program_n['Exec_Site_Prep_Act_Dt'], $uid_x, $row_program_n['ENTRPRS_PROJ_TYPE_NM'])?></td>
      <td width="35" align="center" bgcolor="<?php echo htmlspecialchars($instal);?>" style="color:#FFFFFF; font-size:9px"><?php pcntshw($row_program_n['Exec_Installation_Cmpl_Prct'], $row_program_n['Exec_Installation_Pln_Dt'], $row_program_n['PHASE_NAME'], $row_program_n['Exec_Installation_Act_Dt'], $uid_x, $row_program_n['ENTRPRS_PROJ_TYPE_NM'])?></td>
      <td width="50" align="center" bgcolor="<?php echo htmlspecialchars($instal);?>" style="color:#FFFFFF; font-size:9px"><?php dateshwX($row_program_n['Exec_Installation_Pln_Dt'], $row_program_n['PHASE_NAME'], $row_program_n['Exec_Installation_Act_Dt'], $uid_x, $row_program_n['ENTRPRS_PROJ_TYPE_NM'])?></td>
      <td width="35" align="center" bgcolor="<?php echo htmlspecialchars($migr);?>" style="color:#FFFFFF; font-size:9px"><?php pcntshw($row_program_n['Exec_Migration_Cmpl_Prct'], $row_program_n['Exec_Migration_Pln_Dt'], $row_program_n['PHASE_NAME'], $row_program_n['Exec_Migration_Act_Dt'], $uid_x, $row_program_n['ENTRPRS_PROJ_TYPE_NM'])?></td>
      <td width="50" align="center" bgcolor="<?php echo htmlspecialchars($migr);?>" style="color:#FFFFFF; font-size:9px"><?php dateshwX($row_program_n['Exec_Migration_Pln_Dt'], $row_program_n['PHASE_NAME'], $row_program_n['Exec_Migration_Act_Dt'], $uid_x, $row_program_n['ENTRPRS_PROJ_TYPE_NM'])?></td>
      <td width="35" align="center" bgcolor="<?php echo htmlspecialchars($decm);?>" style="color:#FFFFFF; font-size:9px"><?php pcntshw($row_program_n['Exec_Decommission_Cmpl_Prct'], $row_program_n['Exec_Decommission_Pln_Dt'], $row_program_n['PHASE_NAME'], $row_program_n['Exec_Decommission_Act_Dt'], $uid_x, $row_program_n['ENTRPRS_PROJ_TYPE_NM'])?></td>
      <td width="50" align="center" bgcolor="<?php echo htmlspecialchars($decm);?>" style="color:#FFFFFF; font-size:9px"><?php dateshwX($row_program_n['Exec_Decommission_Pln_Dt'], $row_program_n['PHASE_NAME'], $row_program_n['Exec_Decommission_Act_Dt'], $uid_x, $row_program_n['ENTRPRS_PROJ_TYPE_NM'])?></td>
	  <td width="35" align="center" bgcolor="<?php echo htmlspecialchars($clsng);?>" style="color:#FFFFFF; font-size:9px"><?php pcntshw($row_program_n['Closing_Cmpl_Prct'], $row_program_n['Closing_Pln_Dt'], $row_program_n['PHASE_NAME'], $row_program_n['Closing_Act_Dt'], $uid_x, $row_program_n['ENTRPRS_PROJ_TYPE_NM'])?></td>
      <td align="center" bgcolor="<?php echo htmlspecialchars($clsng);?>" style="color:#FFFFFF; font-size:9px"><?php dateshwX($row_program_n['Closing_Pln_Dt'], $row_program_n['PHASE_NAME'], $row_program_n['Closing_Act_Dt'], $uid_x, $row_program_n['ENTRPRS_PROJ_TYPE_NM'])?></td>
      							<?php    
								// RISK AND ISSUES HIGHLIGHT CELL
								// Move this to functions
								$prj_name = $row_program_n['PROJ_NM'];
								
								$ri_region = $row_program_n['Region'];
								$ri_program = $row_program_n['PRGM'];
								$ri_fyear = $row_program_n['FISCL_PLAN_YR'];
								// echo $row_rip_t['progCt_t']; 
								// RandI colors
								
								if ($row_program_n['Prj_RiskAndIssue_Cnt'] > 0) {
									$proj_clr = '#fcd12a';
								} else { 
									$proj_clr = '';
								}
								
								if ($row_program_n['Prg_RiskAndIssue_Cnt'] > 0) {
									$prog_clr = '#fcd12a';
								} else { 
									$prog_clr = '';
								}
								?>
                          <td align="center" bgcolor="<?php echo $proj_clr ?>">
								                <?php if($row_program_n['Prj_RiskAndIssue_Cnt'] > 0) { // prject risk and issues?>
                                <a href="ri2.php?prj_name=<?php echo htmlspecialchars($row_program_n['PROJ_NM']);?>&count=<?php echo htmlspecialchars($row_program_n['Prj_RiskAndIssue_Cnt']); ?>" class="miframe"><?php echo htmlspecialchars($row_program_n['Prj_RiskAndIssue_Cnt']); ?></a>
                                <?php } else { ?>
                                <?php echo $row_program_n['Prj_RiskAndIssue_Cnt']; ?>
                                <?php } ?>
                          </td>

                          <td align="center" bgcolor="<?php echo $prog_clr ?>">
                          	    <?php if($row_program_n['Prg_RiskAndIssue_Cnt'] > 0) { // program risk and issues?>
                                <a href="ri2-prg.php?region=<?php echo htmlspecialchars($region_clps_mtch)?>&program=<?php echo htmlspecialchars($program_clps_mtch)?>&fscl_year=<?php echo htmlspecialchars($row_program_n['FISCL_PLAN_YR'])?>&count=<?php echo htmlspecialchars($row_program_n['Prg_RiskAndIssue_Cnt'])?>" class="miframe"><?php echo htmlspecialchars($row_program_n['Prg_RiskAndIssue_Cnt']); ?></a>
                                <?php } else { ?>
                                <?php echo htmlspecialchars($row_program_n['Prg_RiskAndIssue_Cnt']); ?>
                                <?php } ?>
                          </td>
						        <td style="padding:2px; color:#ffffff; font-size:9px" bgcolor="<?php OV_health($row_program_n['Ovr_Hlth']);?>" > 
						  <div align="center">
          					<?php 
							  //$PhaseName = $row_program_n['PHASE_NAME'];
							  //$PhaseNameShort = strstr($PhaseName, ' ' );
							  //echo $PhaseNameShort;
							  echo convtimeDPR($row_program_n['Hlth_Stat_Dt']);
              
							?>
						  </div>
					  </td>
                          <td align="center"><?php echo trim($row_program_n['Curr_Stat_Sum']); ?></td>
	  </tr>

    <?php } ?>
  </tbody>
</table>
</body>
</html>