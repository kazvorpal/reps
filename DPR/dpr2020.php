<table width="100%" border="0" cellpadding="5" cellspacing="0" class="table-striped table-bordered table-hover">
  <thead>
    <tr align="center" valign="middle" style="color:#FFFFFF; background-color:#00aaf5; font-size:10px; padding:2px">
      <th class="sticky" ><strong>PROGRAM</strong></th>
      <th class="sticky">SUBPROGRAM</th>
      <th class="sticky"><strong>PROJECT NAME</strong></th>
      <th class="sticky"><strong>OWNER</strong></th>
      <th class="sticky"><strong>FY</strong></th>
      <th class="sticky"><strong>REGION</strong></th>
      <th class="sticky"><strong>MARKET</strong></th>
      <th class="sticky"><strong>FACILITY</strong></th>
      <th class="sticky"><strong>ORACLE CODE</strong></th>
      <th class="sticky"><strong>ORACLE START</strong></th>
      <th class="sticky"><strong>ORACLE END</strong></th>
      <th class="sticky"><strong>STAGE</strong></th>
      <th class="sticky"><strong>WATTS MO</strong></th>
      <th class="sticky"><strong>SCOPE DESC</strong></th>
      <th width="70" class="sticky">START DATE</th>
      <th width="70" class="sticky">FINISH DATE</th>
      <th class="sticky" align="center"><strong>EXEC PREP</strong></th>
      <th class="sticky"><strong>SITE PREP</strong></th>
      <th class="sticky"><strong>INSTALL</strong></th>
      <th class="sticky"><strong>MIGRATION</strong></th>
      <th class="sticky"><strong>DECOM</strong></th>
      <th width="30" class="sticky"><strong>PR<br>
RI</strong></th>
      <th width="30" class="sticky"><strong>PRG<br>
        RI</strong></th>
      <th width="30" class="sticky"><strong>VIEW</strong></th>
    </tr>
    </thead>
    <tbody>
    <?php while($row_por = sqlsrv_fetch_array( $stmt_por, SQLSRV_FETCH_ASSOC)) { ?>
    		<?php 
							// COLOR LOGIC
							// <Stage name>_Flg                     Stage Flag, 0 for project has no stage, 1 has a stage
							// <Stage name>_Pln_Dt                  Stage Plan Date
							// <Stage name>_Act_Dt                  Stage Actual Date
							// <Stage name>_Late_Flg                Stage Late flag (0 for No, 1 for Late, Null for no stage)
							
							// FOR ALL NOT IN EXECUTE OR CLOSING
							$grey_stages = 1;
							if($row_por['PHASE_NAME'] == '01 Proposed' || $row_por['PHASE_NAME'] == '02 Allocated' || $row_por['PHASE_NAME'] == '03 Released' || ($row_por['ENTRPRS_PROJ_TYPE_NM'] == 'Reporting Only' && $row_por['FISCL_PLAN_YR'] != '2021' ) || $row_por['ENTRPRS_PROJ_TYPE_NM'] == '' || $row_por['PHASE_NAME'] == 'Cancelled') {
							$grey_stages = 0;
							}
														
							// execute prep cell color
							$exe = '#00d257'; // Cox Green
                            if($row_por['Exec_Prep_Flg'] != 1 || $row_por['Exec_Prep_Act_Dt'] == ''){ // 0 = has no stage | 1 = has stage
								$exe = '#00aaf5'; //Cox Blue
							}
							
                                 // red logic fixed; do not show red on day of exec prep
                                if(is_null($row_por['Exec_Prep_Pln_Dt'])){ 
                                
                                    if($row_por['Exec_Prep_Late_Flg'] == 1){ // 0 = not late | 1 = late c1c1c1
								    $exe = 'red'; // Red
							        }

                                } else {

                                    $execPDx = $row_por['Exec_Prep_Pln_Dt'];
								    $execPD = date_format($execPDx, 'm-d-Y');
								    $execTD = date('m-d-Y');

                                    if($row_por['Exec_Prep_Late_Flg'] == 1 && $execPD != $execTD){ //if exec prep late flag = 1 and exec prep plan date is not equal to today
								    $exe = 'red'; // Red
                                    }
                                }

							if($grey_stages == 0 || $row_por['Exec_Prep_Flg'] != 1){ // if reporting only and not in Execute Prep then turn grey
								$exe = '#c1c1c1'; 
							}
																					
							// site prep cell color
							$site = '#00d257';
							if($row_por['Site_Prep_Flg'] != 1 || $row_por['Site_Prep_Act_Dt'] == ''){
								$site = '#00aaf5'; // Cox Blue
							}
                                // red logic fixed; do not show red on day of site prep
                                if(is_null($row_por['Site_Prep_Pln_Dt'])){ 

							        if($row_por['Site_Prep_Late_Flg'] == 1){
								    $site = 'red';
                                    }

                                } else {

                                    $sitePDx = $row_por['Site_Prep_Pln_Dt'];
								    $sitePD = date_format($sitePDx, 'm-d-Y');
								    $siteTD = date('m-d-Y');

                                    if($row_por['Site_Prep_Late_Flg'] == 1 && $sitePD != $siteTD){ //if site prep late flag = 1 and site prep plan date is not equal to today
								    $site = 'red';
                                    }
                                }
							if($grey_stages == 0 || $row_por['Site_Prep_Flg'] != 1 ){
								$site = '#c1c1c1';
							}
								
                            // install cell color							
							
							$instal = '#00d257';
							if($row_por['Install_Flg'] != 1 || $row_por['Install_Act_Dt'] == ''){
								$instal = '#00aaf5';
							}
                                // red logic fixed; do not show red on day of install
							    if (is_null($row_por['Install_Pln_Dt'])){

								    if($row_por['Install_Late_Flg'] == 1){ 
                                    $instal = 'red'; 
                                    }

							    } else {

								    $installPDx = $row_por['Install_Pln_Dt'];
								    $installPD = date_format($installPDx, 'm-d-Y');
								    $installTD = date('m-d-Y');
									
							        if($row_por['Install_Late_Flg'] == 1 && $installPD != $installTD){  // if install late flag = 1 and install plan date is not equal to today
                                    $instal = 'red'; 
                                    }
							    }

							if($grey_stages == 0 || $row_por['Install_Flg'] != 1){
								$instal = '#c1c1c1';
							}

							
							// migration cell color
							
							$migr = '#00d257'; //cox green
							if($row_por['Migration_Pln_Dt'] != '' || $row_por['Migration_Late_Flg'] != 1){
								$migr = '#00aaf5'; //cox blue
							}
                             // red logic fixed; do not show red on day of migration
							    if (is_null($row_por['Migration_Pln_Dt'])){

							        if($row_por['Migration_Late_Flg'] == 1) {
								        $migr = 'red';
							        }

                                } else {

   								    $migratePDx = $row_por['Migration_Pln_Dt'];
								    $migratePD = date_format($migratePDx, 'm-d-Y');
								    $migrateTD = date('m-d-Y');  
                                    
                                    if($row_por['Migration_Late_Flg'] == 1 && $migratePD != $migrateTD) {
								        $migr = 'red';
							        }
                                }
							if($row_por['Migration_Act_Dt'] != '') {
								$migr = '#00d257';
							}
							if($grey_stages == 0 || $row_por['Migration_Pln_Dt'] == '' ){
								$migr = '#c1c1c1'; //grey
							} 
								
                            // decom cell color
							$decm = '#00d257'; // cox green
							if($row_por['Decom_Flg'] != 1 || $row_por['Decom_Act_Dt'] == '' ){
								$decm = '#00aaf5'; //cox blue
							}
                             // red logic fixed; do not show red on day of decom
							    if (is_null($row_por['Decom_Pln_Dt'])){

							        if($row_por['Decom_Late_Flg'] == 1){
								        $decm = 'red';
							        }

                                } else {

                                    $decomPDx = $row_por['Decom_Pln_Dt'];
								    $decomPD = date_format($decomPDx, 'm-d-Y');
								    $decomTD = date('m-d-Y'); 

                                    if($row_por['Decom_Late_Flg'] == 1 && $decomPD != $decomTD){
								        $decm = 'red';
							        }
                				}

							if($grey_stages == 0 || $row_por['Decom_Pln_Dt'] == '' ){
								$decm = '#c1c1c1';
							}
								
							
							//Trim UID Brackets
							//$uid_x = substr($row_por['PROJ_ID'],1,-1);
							$uid_x = $row_por['PROJ_ID'];
							$region_clps_mtch = htmlspecialchars($row_por['Region']);
							$program_clps_mtch = htmlspecialchars($row_por['PRGM']);
							
							// Risk and Issues Indicator
//							$projRct = $row_por['Prj_Risk_Cnt'];
//							$projIct = $row_por['prj_Issue_Cnt'];
//							$progRct = $row_por['Prg_Risk_Cnt'];
//							$progIct = $row_por['Prg_Issue_Cnt'];
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
								if ($row_por['Prj_RiskAndIssue_Cnt'] > 0) {
									$proj_clr = '#fcd12a';
								} else { 
									$proj_clr = '';
								}
								
								if ($row_por['Prg_RiskAndIssue_Cnt'] > 0) {
									$prog_clr = '#fcd12a';
								} else { 
									$prog_clr = '';
								}						
                            ?>
    
    <tr align="left" valign="middle" style="font-size:11px">
      <td style="padding:2px"><?php echo htmlspecialchars($row_por['PRGM']);?></td>
      <td style="padding:2px"><?php echo htmlspecialchars($row_por['Sub_Prg']);?></td>
      <td style="padding:2px"><a href="https://coxcomminc.sharepoint.com/sites/pwaeng/project%20detail%20pages/schedule.aspx?projuid=<?php echo urlencode($row_por['PROJ_ID']);?>" title="Open in EPS" target="_blank"><?php echo htmlspecialchars($row_por['PROJ_NM']);?></a></td>
      <td style="padding:2px"><?php echo  htmlspecialchars($row_por['PROJ_OWNR_NM']);?></td>
      <td style="padding:2px" align="center"><?php echo  htmlspecialchars($row_por['FISCL_PLAN_YR']);?></td>
      <td style="padding:2px"><?php echo htmlspecialchars($row_por['Region']);?></td>
      <td style="padding:2px"><?php echo htmlspecialchars($row_por['Market']);?></td>
      <td style="padding:2px"><?php echo htmlspecialchars($row_por['Facility']);?></td>
      <td style="padding:2px">
		  <?php 
			  $ocd = trim($row_por['OracleProject_Cd']);
			  $ocd_splits = str_replace(" ", ",", $ocd);
			  $ocd_splits2 = str_replace(",,", ",", $ocd_splits);
			  
			  echo '<a class="mapframe" href="eq_history.php?ocd='. htmlspecialchars($ocd_splits2) . '">' . htmlspecialchars($ocd) . '</a><br>';
			  //echo $row_por['OracleProject_Cd'];
		  ?>
      </td>
      <td style="padding:2px"><?php if(is_null($row_por['OracleProjectStart_Dt'])) {
		  										echo '';
	  											} else {
		  										echo date_format($row_por['OracleProjectStart_Dt'], 'm-d-Y');
												}
											?>
      </td>
      <td style="padding:2px"><?php if(is_null($row_por['OracleProjectEnd_Dt'])) {
		  										echo '';
	  											} else {
		  										echo date_format($row_por['OracleProjectEnd_Dt'], 'm-d-Y');
												}
											?>
      </td>
      <td style="padding:2px"><a href="l2-frame.php?uid=<?php echo urlencode($uid_x)?>" class="dno"><?php echo htmlspecialchars($row_por['PHASE_NAME']);?></a></td>
      <td style="padding:2px"><span style="padding:2px"><?php echo wattsRepl($row_por['WATTS_MO']);?></span></td>
      <td style="padding:2px" align="left"><?php echo htmlspecialchars($row_por['SCOP_DESC']);?></td>
      <td style="padding:2px" align="center"><?php echo convtimex($row_por['Plan_Start_Dt']);?></td>
      <td style="padding:2px" align="center"><?php fnshdt($row_por['PHASE_NAME'],$row_por['Plan_Finish_Dt'], $fiscal_year)?></td>
      <td width="100px" align="center"  bgcolor="<?php echo htmlspecialchars($exe);?>" style="color:#FFFFFF">
                          		<?php //if($row_por['Exec_Prep_Act_Dt'] == '') { echo 'this works';} 
								if($row_por['Exec_Prep_Pln_Dt'] == '' || $row_por['PHASE_NAME'] == '01 Proposed' || $row_por['PHASE_NAME'] == '02 Allocated' || $row_por['PHASE_NAME'] == '03 Released' || $row_por['ENTRPRS_PROJ_TYPE_NM'] == 'Reporting Only') {
									echo "--";
								}else if($row_por['Exec_Prep_Act_Dt'] != '' ) {	
									echo "<a style='color:#FFFFFF' href='l2-frame.php?uid=" . urlencode($uid_x) . "' class='dno'>" . date_format($row_por['Exec_Prep_Act_Dt'], 'm-d-Y') . "</a>";
								}else{
									echo "<a style='color:#FFFFFF' href='l2-frame.php?uid=" . urlencode($uid_x) . "' class='dno'>" . date_format($row_por['Exec_Prep_Pln_Dt'], 'm-d-Y') . "</a>";
								}
								?>
                		 </td>
                          <td width="90px" align="center" bgcolor="<?php echo $site ?>" style="color:#FFFFFF">
						        <?php 
								if($row_por['Site_Prep_Pln_Dt'] == '' || $row_por['PHASE_NAME'] == '01 Proposed' || $row_por['PHASE_NAME'] == '02 Allocated' || $row_por['PHASE_NAME'] == '03 Released' || $row_por['ENTRPRS_PROJ_TYPE_NM'] == 'Reporting Only') {
									echo "--";
								}else if($row_por['Site_Prep_Act_Dt'] != ''){	
									echo "<a style='color:#FFFFFF' href='l2-frame.php?uid=" . urlencode($uid_x) . "' class='dno'>" . date_format($row_por['Site_Prep_Act_Dt'], 'm-d-Y') . "</a>";
								}else{
									echo "<a style='color:#FFFFFF' href='l2-frame.php?uid=" . urlencode($uid_x) . "' class='dno'>" . date_format($row_por['Site_Prep_Pln_Dt'], 'm-d-Y') . "</a>";
								}
								?>
						  </td>
                          <td width="90px" align="center" bgcolor="<?php echo $instal ?>" style="color:#FFFFFF">
                          		<?php 
								if($row_por['Install_Pln_Dt'] == '' || $row_por['PHASE_NAME'] == '01 Proposed' || $row_por['PHASE_NAME'] == '02 Allocated' || $row_por['PHASE_NAME'] == '03 Released' || $row_por['ENTRPRS_PROJ_TYPE_NM'] == 'Reporting Only') {
									echo "--";
								}else if($row_por['Install_Act_Dt'] != ''){	
									echo "<a style='color:#FFFFFF' href='l2-frame.php?uid=" . urlencode($uid_x) . "' class='dno'>" . date_format($row_por['Install_Act_Dt'], 'm-d-Y') . "</a>";
								}else{
									echo "<a style='color:#FFFFFF' href='l2-frame.php?uid=" . urlencode($uid_x) . "' class='dno'>" . date_format($row_por['Install_Pln_Dt'], 'm-d-Y') . "</a>";
								}
								?>
                          </td>
                          <td width="90px" align="center" bgcolor="<?php echo $migr ?>" style="color:#FFFFFF">
                                <?php 
								if($row_por['Migration_Pln_Dt'] == '' || $row_por['PHASE_NAME'] == '01 Proposed' || $row_por['PHASE_NAME'] == '02 Allocated' || $row_por['PHASE_NAME'] == '03 Released' || $row_por['ENTRPRS_PROJ_TYPE_NM'] == 'Reporting Only') {
									echo "--";
								}else if($row_por['Migration_Act_Dt'] != ''){	
									echo "<a style='color:#FFFFFF' href='l2-frame.php?uid=" . urlencode($uid_x) . "' class='dno'>" . date_format($row_por['Migration_Act_Dt'], 'm-d-Y') . "</a>";
								}else{
									echo "<a style='color:#FFFFFF' href='l2-frame.php?uid=" . urlencode($uid_x) . "' class='dno'>" . date_format($row_por['Migration_Pln_Dt'], 'm-d-Y') . "</a>";
								}
								?>
                          </td>
                          <td width="90px" align="center" bgcolor="<?php echo $decm ?>" style="color:#FFFFFF">
                                <?php 
								if($row_por['Decom_Pln_Dt'] == '' || $row_por['PHASE_NAME'] == '01 Proposed' || $row_por['PHASE_NAME'] == '02 Allocated' || $row_por['PHASE_NAME'] == '03 Released' || $row_por['ENTRPRS_PROJ_TYPE_NM'] == 'Reporting Only') {
									echo "--";
								}else if($row_por['Decom_Act_Dt'] != ''){	
									echo "<a style='color:#FFFFFF' href='l2-frame.php?uid=" . urlencode($uid_x) . "' class='dno'>" . date_format($row_por['Decom_Act_Dt'], 'm-d-Y')  . "</a>";
								}else{
									echo "<a style='color:#FFFFFF' href='l2-frame.php?uid=" . urlencode($uid_x) . "' class='dno'>" . date_format($row_por['Decom_Pln_Dt'], 'm-d-Y') . "</a>";
								}
								?>
                          </td>

                <?php    
								$prj_name = $row_por['PROJ_NM'];
								
								$ri_region = $row_por['Region'];
								$ri_program = $row_por['PRGM'];
								$ri_fyear = $row_por['FISCL_PLAN_YR'];
								// echo $row_rip_t['progCt_t']; 
								// RandI colors
								
								if ($row_por['Prj_RiskAndIssue_Cnt'] > 0) {
									$proj_clr = '#fcd12a';
								} else { 
									$proj_clr = '';
								}
								
								if ($row_por['Prg_RiskAndIssue_Cnt'] > 0) {
									$prog_clr = '#fcd12a';
								} else { 
									$prog_clr = '';
								}
								?>
                          <td align="center" bgcolor="<?php echo $proj_clr ?>">
								                <?php if($row_por['Prj_RiskAndIssue_Cnt'] > 0) { // prject risk and issues?>
                                <a href="ri2.php?prj_name=<?php echo htmlspecialchars($row_por['PROJ_NM']);?>&count=<?php echo htmlspecialchars($row_por['Prj_RiskAndIssue_Cnt']); ?>" class="miframe"><?php echo htmlspecialchars($row_por['Prj_RiskAndIssue_Cnt']); ?></a>
                                <?php } else { ?>
                                <?php echo $row_por['Prj_RiskAndIssue_Cnt']; ?>
                                <?php } ?>
                          </td>

                          <td align="center" bgcolor="<?php echo $prog_clr ?>">
                          	    <?php if($row_por['Prg_RiskAndIssue_Cnt'] > 0) { // program risk and issues?>
                                <a href="ri2-prg.php?region=<?php echo htmlspecialchars($region_clps_mtch)?>&program=<?php echo htmlspecialchars($program_clps_mtch)?>&fscl_year=<?php echo htmlspecialchars($row_por['FISCL_PLAN_YR'])?>&count=<?php echo htmlspecialchars($row_por['Prg_RiskAndIssue_Cnt'])?>" class="miframe"><?php echo htmlspecialchars($row_por['Prg_RiskAndIssue_Cnt']); ?></a>
                                <?php } else { ?>
                                <?php echo htmlspecialchars($row_por['Prg_RiskAndIssue_Cnt']); ?>
                                <?php } ?>
                          </td>
                                                           
                <td style="padding:2px" align="center"><a href="#collapseOne<?php echo  htmlspecialchars($row_por['ProjectStage_key']);?>" title="Show all Project Data" data-toggle="collapse" data-parent="#accordion<?php echo  htmlspecialchars($row_por['ProjectStage_key']);?>">+<?php //echo $grey_stages?></a></td>
    </tr>

             

    <tr align="left" valign="top" style="background:white">                      
      <td colspan="26">
            
		 	<div id="collapseOne<?php echo htmlspecialchars($row_por['ProjectStage_key']);?>" class="panel-collapse collapse out">
				<div class="panel-body" style="font-size:10px">
                                                      <div class="row">
                                                          <div class="col-lg-5">
                                                          	<h4>PROJECT DATA</h4>
                                                          	
                                                            <table width="95%" border="0" cellpadding="3" class="table-striped table-bordered table-hover">
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
                                                                  <td><?php //echo str_replace('', '', htmlspecialchars($row_por['OracleProject_Cd'])) ;?>
                                                                  		  <?php 
                                                                          $ocd1 = $row_por['OracleProject_Cd'];
                                                                          $ocd1_splits = explode(';',$ocd1);
                                                                          foreach($ocd1_splits as $ocd1_codes) {
                                                                          echo '<a class="mapframe" href="eq_history.php?ocd='. htmlspecialchars(trim($ocd1_codes)) . '">' . htmlspecialchars(trim($ocd1_codes)) . '</a><br>';
                                                                          }
                                                                          ?>
                                                                  </td>
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
                                                                  <td><?php echo wattsRepl($row_por['WATTS_MO']);?></td>
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
                                                                  <td><?php fnshdt($row_por['PHASE_NAME'],$row_por['Plan_Finish_Dt'], $fiscal_year)?></td>
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
                                                                  <td>(<?php echo htmlspecialchars($row_por['Equip3_Cnt']); ?>) <?php echo htmlspecialchars($row_por['Equip3_TYPE']);?></td>
                                                                </tr>
                                                                <tr>                                                               
                                                                  <td>Equipment Type 4</td>
                                                                  <td>(<?php echo htmlspecialchars($row_por['Equip4_Cnt']); ?>) <?php echo htmlspecialchars($row_por['Equip4_TYPE']);?></td>
                                                                </tr>
                                   
                                                              </tbody>
                                                            </table><br>
																	<br>
														  </div>

                                                          
                                                          <div class="col-lg-7">
                                                          	<h4>PROJECT INFO</h4>
                                                            <h5>Access Required </h5>
                                                            <P>
                                                            <a href="https://coxcomminc.sharepoint.com/sites/pwaeng/project detail pages/schedule.aspx?projuid=<?php echo urlencode($uid_x)?>" target="_blank">EPS Project Schedule</a> 
                                                            <br>
																<?php if($row_por['Prj_RiskAndIssue_Cnt'] > 0) { // prject risk and issues?>
                                                                <a href="ri2.php?prj_name=<?php echo urlencode($row_por['PROJ_NM']);?>&count=<?php echo urlencode($row_por['Prj_RiskAndIssue_Cnt']) ?>" class="miframe">Project Risks& Issues (<?php echo htmlspecialchars($row_por['Prj_RiskAndIssue_Cnt']); ?>)</a>
                                                                <?php } else { ?>
                                                                <?php echo 'Project Risks& Issues (' . htmlspecialchars($row_por['Prj_RiskAndIssue_Cnt']) . ')'; ?>
                                                                <?php } ?>
                                                            <br>  
                                                             	<?php if($row_por['Prg_RiskAndIssue_Cnt'] > 0) { // program risk and issues?>
                                                                <a href="ri2-prg.php?region=<?php echo urlencode($ri_region)?>&program=<?php echo urlencode($ri_program)?>&fscl_year=<?php echo urlencode($row_por['FISCL_PLAN_YR']);?>&count=<?php echo urlencode($row_por['Prg_RiskAndIssue_Cnt'])?>" class="miframe">Program Risks& Issues (<?php echo htmlspecialchars($row_por['Prg_RiskAndIssue_Cnt']); ?>)</a>
                                                                <?php } else { ?>
                                                                <?php echo 'Program Risks& Issues (' . htmlspecialchars($row_por['Prg_RiskAndIssue_Cnt']) . ')' ;?>
                                                                <?php } ?>
															</P>
                                                            
                                                            <h5>
                                                            No Access Required
                                                            </h5>
                                                            <p>
                                                            <a href="level_2_details_all.php?uid=<?php echo urlencode($uid_x)?>" class="mapframe">Project Schedule</a>
                                                            <br>
																<?php if($row_por['Prj_RiskAndIssue_Cnt'] > 0) { // prject risk and issues?>
                                                                <a href="ri-no_access_proj.php?proj_name=<?php echo urlencode($row_por['PROJ_NM']);?>&count=<?php echo urlencode($row_por['Prj_RiskAndIssue_Cnt']); ?>" class="mapframe">Project Risks& Issues (<?php echo htmlspecialchars($row_por['Prj_RiskAndIssue_Cnt']); ?>)</a>
                                                                <?php } else { ?>
                                                                <?php echo 'Project Risks& Issues (' . htmlspecialchars($row_por['Prj_RiskAndIssue_Cnt']) . ')'; ?>
                                                                <?php } ?>
                                                             <br>
                                                             	<?php if($row_por['Prg_RiskAndIssue_Cnt'] > 0) { // program risk and issues?>
                                                                <a href="ri-no_access_prog.php?region=<?php echo urlencode($ri_region);?>&program=<?php echo urlencode($ri_program);?>&fscl_year=<?php echo urlencode($row_por['FISCL_PLAN_YR']);?>&count=<?php echo urlencode($row_por['Prg_RiskAndIssue_Cnt']);?>" class="mapframe">Program Risks& Issues (<?php echo htmlspecialchars($row_por['Prg_RiskAndIssue_Cnt']); ?>)</a>
                                                                <?php } else { ?>
                                                                <?php echo 'Program Risks& Issues (' . htmlspecialchars($row_por['Prg_RiskAndIssue_Cnt']) . ')' ;?>
                                                                <?php } ?>
                                                            </p>
															<!--<a href="eq-order-history?uid=<?php // echo $uid_x?>" class="mapframe">Equipment Order History</a>    -->                                                        <!--<table width="100%" border="0" class="table-striped table-bordered table-hover">
                                                             
                                                         <!--<iframe width="1150" height="500" src="l2-frame.php?uid=<?php //echo $uid_x?>" frameborder="0" allowfullscreen></iframe>-->
                                                            
                                                        </div>
                                                      </div>
												  </div>
      </div>
      </td>
      </tr>

    <?php } ?>
  </tbody>
</table>