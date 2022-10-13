<table width="100%" border="0" cellpadding="5" cellspacing="0" class="table-striped table-bordered table-hover">
  <thead>
    <tr align="center" valign="middle" style="color:#FFFFFF; font-size:10px; padding:2px; background-color; #000000">
      <th bgcolor="#337AB7"><div align="center">PROGRAM</div></th>
      <th bgcolor="#337AB7"><div align="center">SUBPROGRAM</div></th>
      <th bgcolor="#337AB7"><div align="center">PROJECT NAME</div></th>
      <th bgcolor="#337AB7"><div align="center">OWNER</div></th>
      <th bgcolor="#337AB7"><div align="center">REGION</div></th> 
      <th bgcolor="#337AB7"><div align="center">MARKET</div></th>
      <th bgcolor="#337AB7"><div align="center">FACILITY</div></th>
      <th bgcolor="#337AB7"><div align="center">STAGE</div></th>
      <th width="70" bgcolor="#337AB7"><div align="center">START DATE</div></th>
      <th width="70" bgcolor="#337AB7"><div align="center">FINISH DATE</div></th>
      <th width="70" bgcolor="#337AB7"><div align="center">OA HLTH</div></th>
      <th colspan="2" bgcolor="#337AB7"><div align="center">INITIATING</div></th>
      <th colspan="2" bgcolor="#337AB7"><div align="center">PLANNING</div></th>
      <th colspan="2" bgcolor="#337AB7"><div align="center">EXECUTING<span class="glyphicon glyphicon-triangle-right" style="font-size:10px"></span></div></th>
      <th colspan="2" bgcolor="#34AEE8"><div align="center">SITE PREP</div></th>
      <th colspan="2" bgcolor="#34AEE8"><div align="center">INSTALLATION</div></th>
      <th colspan="2" bgcolor="#34AEE8"><div align="center">MIGRATION</div></th>
      <th colspan="2" bgcolor="#34AEE8"><div align="center">DECOMMISSION</div></th>
      <th colspan="2" bgcolor="#337AB7"><div align="center">CLOSING</div></th>
      <th width="30" bgcolor="#337AB7" ><div align="center">PRJ R/I</div></th>
      <th width="30" bgcolor="#337AB7" ><div align="center">PRG R/I</div></th>
      <th width="30" bgcolor="#337AB7" ><div align="center">VIEW</div></th>
    </tr>
  </thead>
    <tbody>
    <?php while($row_por = sqlsrv_fetch_array( $stmt_por, SQLSRV_FETCH_ASSOC)) { ?>
    		<?php 
							// COLOR LOGIC
							// Convert this into a function when you have time
							
							// <Stage name>_Flg                     Stage Flag, 0 for project has no stage, 1 has a stage
							// <Stage name>_Pln_Dt                  Stage Plan Date
							// <Stage name>_Act_Dt                  Stage Actual Date
							// <Stage name>_Late_Flg                Stage Late flag (0 for No, 1 for Late, Null for no stage)
							
							$aa = $row_por['PHASE_NAME'];
							$bb = $row_por['ENTRPRS_PROJ_TYPE_NM'];
							$cc = $row_por['Executing_Flg'];
							$dd = $row_por['Executing_Act_Dt'];
							$ee = $row_por['Executing_Pln_Dt'];
							$ff = $row_por['Executing_Late_Flg'];
							
							
							// FOR ALL NOT IN EXECUTE OR CLOSING
							$grey_stages = 1;
							if($row_por['PHASE_NAME'] == '01 Proposed' || $row_por['PHASE_NAME'] == '02 Allocated' || $row_por['PHASE_NAME'] == '03 Released' || /*(*/$row_por['ENTRPRS_PROJ_TYPE_NM'] == 'Reporting Only' /*&& $row_por['FISCL_PLAN_YR'] != '2021' )*/ || $row_por['ENTRPRS_PROJ_TYPE_NM'] == '' || $row_por['PHASE_NAME'] == 'Cancelled') {
							$grey_stages = 0;
							}

              // FOR ALL NOT IN EXECUTE OR CLOSING (INITIATING AND PLANING )
              $grey_stagesRLS = 1;
							if($row_por['PHASE_NAME'] == '01 Proposed' || $row_por['PHASE_NAME'] == '02 Allocated' || /*(*/$row_por['ENTRPRS_PROJ_TYPE_NM'] == 'Reporting Only' /*&& $row_por['FISCL_PLAN_YR'] != '2021' )*/ || $row_por['ENTRPRS_PROJ_TYPE_NM'] == '' || $row_por['PHASE_NAME'] == 'Cancelled') {
							$grey_stagesRLS = 0;
							}
														
							// execute prep cell color
							$exe = '#00d257'; // Cox Green
                            if($row_por['Executing_Flg'] != 1 || $row_por['Executing_Act_Dt'] == ''){ // 0 = has no stage | 1 = has stage
								$exe = '#00aaf5'; //Cox Blue
							}
							
                                 // red logic fixed; do not show red on day of exec prep
                                if(is_null($row_por['Executing_Pln_Dt'])){ 
                                
                                    if($row_por['Executing_Late_Flg'] == 1){ // 0 = not late | 1 = late c1c1c1
								    $exe = 'red'; // Red
							        }

                                } else {

                                    $execPDx = $row_por['Executing_Pln_Dt'];
								    $execPD = date_format($execPDx, 'm-d-Y');
								    $execTD = date('m-d-Y');

                                    if($row_por['Executing_Late_Flg'] == 1 && $execPD != $execTD){ //if exec prep late flag = 1 and exec prep plan date is not equal to today
								    $exe = 'red'; // Red
                                    }
                                }

							if($grey_stages == 0 || $row_por['Executing_Flg'] != 1){ // if reporting only and not in Execute Prep then turn grey
								$exe = '#c1c1c1'; 
							}
																					
							// site prep cell color
							$site = '#00d257';
							if($row_por['Exec_Site_Prep_Flg'] != 1 || $row_por['Exec_Site_Prep_Act_Dt'] == ''){
								$site = '#00aaf5'; // Cox Blue
							}
                                // red logic fixed; do not show red on day of site prep
                                if(is_null($row_por['Exec_Site_Prep_Pln_Dt'])){ 

							        if($row_por['Exec_Site_Prep_Late_Flg'] == 1){
								    $site = 'red';
                                    }

                                } else {

                                    $sitePDx = $row_por['Exec_Site_Prep_Pln_Dt'];
								    $sitePD = date_format($sitePDx, 'm-d-Y');
								    $siteTD = date('m-d-Y');

                                    if($row_por['Exec_Site_Prep_Late_Flg'] == 1 && $sitePD != $siteTD){ //if site prep late flag = 1 and site prep plan date is not equal to today
								    $site = 'red';
                                    }
                                }
							if($grey_stages == 0 || $row_por['Exec_Site_Prep_Flg'] != 1 ){
								$site = '#c1c1c1';
							}
								
                            // install cell color							
							
							$instal = '#00d257';
							if($row_por['Exec_Installation_Flg'] != 1 || $row_por['Exec_Installation_Act_Dt'] == ''){
								$instal = '#00aaf5';
							}
                                // red logic fixed; do not show red on day of install
							    if (is_null($row_por['Exec_Installation_Pln_Dt'])){

								    if($row_por['Exec_Installation_Late_Flg'] == 1){ 
                                    $instal = 'red'; 
                                    }

							    } else {

								    $installPDx = $row_por['Exec_Installation_Pln_Dt'];
								    $installPD = date_format($installPDx, 'm-d-Y');
								    $installTD = date('m-d-Y');
									
							        if($row_por['Exec_Installation_Late_Flg'] == 1 && $installPD != $installTD){  // if install late flag = 1 and install plan date is not equal to today
                                    $instal = 'red'; 
                                    }
							    }

							if($grey_stages == 0 || $row_por['Exec_Installation_Flg'] != 1){
								$instal = '#c1c1c1';
							}

							
							// migration cell color
							
							$migr = '#00d257'; //cox green
							if($row_por['Exec_Migration_Pln_Dt'] != '' || $row_por['Exec_Migration_Late_Flg'] != 1){
								$migr = '#00aaf5'; //cox blue
							}
                             // red logic fixed; do not show red on day of migration
							    if (is_null($row_por['Exec_Migration_Pln_Dt'])){

							        if($row_por['Exec_Migration_Late_Flg'] == 1) {
								        $migr = 'red';
							        }

                                } else {

   								    $migratePDx = $row_por['Exec_Migration_Pln_Dt'];
								    $migratePD = date_format($migratePDx, 'm-d-Y');
								    $migrateTD = date('m-d-Y');  
                                    
                                    if($row_por['Exec_Migration_Late_Flg'] == 1 && $migratePD != $migrateTD) {
								        $migr = 'red';
							        }
                                }
							if($row_por['Exec_Migration_Act_Dt'] != '') {
								$migr = '#00d257';
							}
							if($grey_stages == 0 || $row_por['Exec_Migration_Pln_Dt'] == '' ){
								$migr = '#c1c1c1'; //grey
							} 
								
                            // decom cell color
							$decm = '#00d257'; // cox green
							if($row_por['Exec_Decommission_Flg'] != 1 || $row_por['Exec_Decommission_Act_Dt'] == '' ){
								$decm = '#00aaf5'; //cox blue
							}
                             // red logic fixed; do not show red on day of decom
							    if (is_null($row_por['Exec_Decommission_Pln_Dt'])){

							        if($row_por['Exec_Decommission_Late_Flg'] == 1){
								        $decm = 'red';
							        }

                                } else {

                                    $decomPDx = $row_por['Exec_Decommission_Pln_Dt'];
								    $decomPD = date_format($decomPDx, 'm-d-Y');
								    $decomTD = date('m-d-Y'); 

                                    if($row_por['Exec_Decommission_Late_Flg'] == 1 && $decomPD != $decomTD){
								        $decm = 'red';
							        }
                				}

							if($grey_stages == 0 || $row_por['Exec_Decommission_Pln_Dt'] == '' ){
								$decm = '#c1c1c1';
							}
							
							// INITIATING CELL COLOR
							$init = '#00d257'; // cox green
							if($row_por['Initiating_Flg'] != 1 || $row_por['Initiating_Act_Dt'] == '' ){
								$init = '#00aaf5'; //cox blue
							}
                              // red logic fixed; do not show red on day of decom
							                if (is_null($row_por['Initiating_Pln_Dt'])){

                                    if($row_por['Initiating_Late_Flg'] == 1){
                                      $init = 'red';
                                    }
                  
                              } else if ($row_por['PHASE_NAME'] == '03 Released') {
                                    $initPDx = $row_por['Initiating_Pln_Dt'];
                                    $initPD = date_format($initPDx, 'm-d-Y');
                                    $initTD = date('m-d-Y'); 

                                    //for Released function

                                    // missing release date fix
                                    if(is_null($row_por['Released_Dt'])){
                                      $iDate = date('Y-m-d');
                                    } else {
                                      $iDate = date_format($row_por['Released_Dt'],'Y-m-d'); 
                                    }
                                    // end

                                    $iDatex = $iDate;
                                    $iDate8 = date('Y-m-d', strtotime($iDatex. ' + 8 days')); //change to 8 days
                                    $iToday = date('Y-m-d');

                                    $start = strtotime($iDatex);
                                    $end = strtotime($iToday);
                                    $holidays=array("2021-01-01","2021-01-18","2021-05-31","2021-07-05","2021-09-06","2021-11-25","2021-12-24","2021-12-31");
                                    $days_between = getWorkingDays($iDate8,$iToday,$holidays);

                                    if($row_por['Initiating_Late_Flg'] == 1 && $initPD != $initTD && $days_between > 8){
                                    //if($days_between > 8){
                                      $init = 'red';
                                    }

                              } else {

                                    $initPDx = $row_por['Initiating_Pln_Dt'];
                                    $initPD = date_format($initPDx, 'm-d-Y');
                                    $initTD = date('m-d-Y'); 

                                    if($row_por['Initiating_Late_Flg'] == 1 && $initPD != $initTD){
                                      $init = 'red';
                                    }
                	            }

							            if($grey_stagesRLS == 0 || $row_por['Initiating_Pln_Dt'] == '' ){
								            $init = '#c1c1c1';
							            }
							
							            // PLANNING CELL COLOR
							            $plnng = '#00d257'; // cox green
							            if($row_por['Planning_Flg'] != 1 || $row_por['Planning_Act_Dt'] == '' ){
								            $plnng = '#00aaf5'; //cox blue
							            }
              
                          // red logic fixed; do not show red on day of decom
							            if (is_null($row_por['Planning_Pln_Dt'])){

							                  if($row_por['Planning_Late_Flg'] == 1){
								                $plnng = 'red';
							                  }

                          } else if ($row_por['PHASE_NAME'] == '03 Released') {
                    
                                $plnngPDx = $row_por['Planning_Pln_Dt'];
                                $plnngPD = date_format($plnngPDx, 'm-d-Y');
                                $plnngTD = date('m-d-Y'); 

                                //for Released function
                                // missing date fix
                                if(is_null($row_por['Released_Dt'])){
                                  $iDate2 = date('Y-m-d');
                                } else {
                                  $iDate2 = date_format($row_por['Released_Dt'],'Y-m-d'); 
                                }
                                //end 
                                  
                                $iDatex2 = $iDate2;
                                $iDate82 = date('Y-m-d', strtotime($iDatex2. ' + 8 days')); //change to 7 days
                                $iToday2 = date('Y-m-d');

                                $start2 = strtotime($iDatex2);
                                $end2 = strtotime($iToday2);
                                $holidays2=array("2021-01-01","2021-01-18","2021-05-31","2021-07-05","2021-09-06","2021-11-25","2021-12-24","2021-12-31");
                                $days_between2 = getWorkingDays($iDate82,$iToday2,$holidays2);

                                if($row_por['Planning_Late_Flg'] == 1 && $plnngPD != $plnngTD && $days_between2 > 8){
                                //if($days_between2 > 8){  
                                    $plnng = 'red';
                                }
                  
                          } else {

                                $plnngPDx = $row_por['Planning_Pln_Dt'];
								                $plnngPD = date_format($plnngPDx, 'm-d-Y');
								                $plnngTD = date('m-d-Y'); 

                                if($row_por['Planning_Late_Flg'] == 1 && $plnngPD != $plnngTD){
								                    $plnng = 'red';
							                  }
              }

							if($grey_stagesRLS == 0 || $row_por['Planning_Pln_Dt'] == '' ){
								$plnng = '#c1c1c1';
							}
							
							// CLOSING CELL COLOR
							$clsng = '#00d257'; // cox green
							if($row_por['Closing_Flg'] != 1 || $row_por['Closing_Act_Dt'] == '' ){
								$clsng = '#00aaf5'; //cox blue
							}
                             // red logic fixed; do not show red on day of decom
							    if (is_null($row_por['Closing_Pln_Dt'])){

							        if($row_por['Closing_Late_Flg'] == 1){
								        $clsng = 'red';
							        }

                                } else {

                                    $clsngPDx = $row_por['Closing_Pln_Dt'];
								    $clsngPD = date_format($clsngPDx, 'm-d-Y');
								    $clsngTD = date('m-d-Y'); 

                                    if($row_por['Closing_Late_Flg'] == 1 && $clsngPD != $clsngTD){
								        $clsng = 'red';
							        }
                				}

							if($grey_stages == 0 || $row_por['Closing_Pln_Dt'] == '' ){
								$clsng = '#c1c1c1';
							}
							
								
							
							//Trim UID Brackets
							//$uid_x = substr($row_por['PROJ_ID'],1,-1);
							$uid_x = $row_por['PROJ_ID'];
							$region_clps_mtch = htmlspecialchars($row_por['Region']);
							$program_clps_mtch = htmlspecialchars($row_por['PRGM']);
							

//	convert this to a function when you have time							
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
      <td style="padding:2px"><?php OwnNm($row_por['PROJ_OWNR_NM']);?></td>
      <!--<td style="padding:2px" align="center"><?php // echo htmlspecialchars($row_por['FISCL_PLAN_YR']);?></td>-->
      <td style="padding:2px"><?php echo htmlspecialchars($row_por['Region']);?></td>
      <td style="padding:2px"><?php echo htmlspecialchars($row_por['Market']);?></td>
      <td style="padding:2px"><?php echo htmlspecialchars($row_por['Facility']);?></td>

      <td style="padding:2px" bgcolor="<?php pcntComp($row_por['PrjComplete_Pct'], $row_por['PHASE_NAME']);?>" >
          <div align="center">
          	<?php 
              $PhaseName = $row_por['PHASE_NAME'];
              $PhaseNameShort = strstr($PhaseName, ' ' );
              echo $PhaseNameShort; //. '<br>'; //modified for dev
              
            //if ($row_por['PHASE_NAME'] == '03 Released') {
            //   echo '<br>' . $days_between . ' Days';
            // }
            ?>
          </div>
      </td>
      
      <td style="padding:2px" align="center"><?php echo convtimeDPR($row_por['Plan_Start_Dt']);?></td>
      <td style="padding:2px" align="center"><?php fnshdt($row_por['PHASE_NAME'],$row_por['Plan_Finish_Dt'], $fiscal_year)?> </td>

      <td style="padding:2px; color:#ffffff; font-size:9px" bgcolor="<?php OV_health($row_por['Ovr_Hlth']);?>" > 
          <div align="center">
          	<?php 
              //$PhaseName = $row_por['PHASE_NAME'];
              //$PhaseNameShort = strstr($PhaseName, ' ' );
              //echo $PhaseNameShort;
              echo convtimeDPR($row_por['Hlth_Stat_Dt']);
              
            ?>
          </div>
      </td>

      <td width="35" align="center" bgcolor="<?php echo htmlspecialchars($init);?>" style="color:#FFFFFF; font-size:9px"><?php pcntshwR($row_por['Initiating_Cmpl_Prct'], $row_por['Initiating_Pln_Dt'], $row_por['PHASE_NAME'], $row_por['Initiating_Act_Dt'], $uid_x, $row_por['ENTRPRS_PROJ_TYPE_NM']) ?></td>
      <td width="50" align="center" bgcolor="<?php echo htmlspecialchars($init);?>" style="color:#FFFFFF; font-size:9px"><?php dateshwNLR($row_por['Initiating_Pln_Dt'], $row_por['PHASE_NAME'], $row_por['Initiating_Act_Dt'], $uid_x, $row_por['ENTRPRS_PROJ_TYPE_NM']) ?></td>
      <td width="35" align="center" bgcolor="<?php echo htmlspecialchars($plnng);?>" style="color:#FFFFFF; font-size:9px"><?php pcntshwR($row_por['Planning_Cmpl_Prct'], $row_por['Planning_Pln_Dt'], $row_por['PHASE_NAME'], $row_por['Planning_Act_Dt'], $uid_x, $row_por['ENTRPRS_PROJ_TYPE_NM'])?></td>
      <td width="50" align="center" bgcolor="<?php echo htmlspecialchars($plnng);?>" style="color:#FFFFFF; font-size:9px"><?php dateshwNLR($row_por['Planning_Pln_Dt'], $row_por['PHASE_NAME'], $row_por['Planning_Act_Dt'], $uid_x, $row_por['ENTRPRS_PROJ_TYPE_NM']) ?></td>
      <td width="35" align="center" bgcolor="<?php echo htmlspecialchars($exe);?>" style="color:#FFFFFF; font-size:9px"><?php pcntshw($row_por['Executing_Cmpl_Prct'], $row_por['Executing_Pln_Dt'], $row_por['PHASE_NAME'], $row_por['Executing_Act_Dt'], $uid_x, $row_por['ENTRPRS_PROJ_TYPE_NM'])?></td>
      <td width="50" align="center" bgcolor="<?php echo htmlspecialchars($exe);?>" style="color:#FFFFFF; font-size:9px"><?php dateshwNL($row_por['Executing_Pln_Dt'], $row_por['PHASE_NAME'], $row_por['Executing_Act_Dt'], $uid_x, $row_por['ENTRPRS_PROJ_TYPE_NM']) ?></td>
      <td width="35" align="center" bgcolor="<?php echo htmlspecialchars($site);?>" style="color:#FFFFFF; font-size:9px"><?php pcntshw($row_por['Exec_Site_Prep_Cmpl_Prct'], $row_por['Exec_Site_Prep_Pln_Dt'], $row_por['PHASE_NAME'], $row_por['Exec_Site_Prep_Act_Dt'], $uid_x, $row_por['ENTRPRS_PROJ_TYPE_NM'])?></td>
      <td width="50" align="center" bgcolor="<?php echo htmlspecialchars($site);?>" style="color:#FFFFFF; font-size:9px"><?php dateshwNL($row_por['Exec_Site_Prep_Pln_Dt'], $row_por['PHASE_NAME'], $row_por['Exec_Site_Prep_Act_Dt'], $uid_x, $row_por['ENTRPRS_PROJ_TYPE_NM'])?></td>
      <td width="35" align="center" bgcolor="<?php echo htmlspecialchars($instal);?>" style="color:#FFFFFF; font-size:9px"><?php pcntshw($row_por['Exec_Installation_Cmpl_Prct'], $row_por['Exec_Installation_Pln_Dt'], $row_por['PHASE_NAME'], $row_por['Exec_Installation_Act_Dt'], $uid_x, $row_por['ENTRPRS_PROJ_TYPE_NM'])?></td>
      <td width="50" align="center" bgcolor="<?php echo htmlspecialchars($instal);?>" style="color:#FFFFFF; font-size:9px"><?php dateshw($row_por['Exec_Installation_Pln_Dt'], $row_por['PHASE_NAME'], $row_por['Exec_Installation_Act_Dt'], $uid_x, $row_por['ENTRPRS_PROJ_TYPE_NM'])?></td>
      <td width="35" align="center" bgcolor="<?php echo htmlspecialchars($migr);?>" style="color:#FFFFFF; font-size:9px"><?php pcntshw($row_por['Exec_Migration_Cmpl_Prct'], $row_por['Exec_Migration_Pln_Dt'], $row_por['PHASE_NAME'], $row_por['Exec_Migration_Act_Dt'], $uid_x, $row_por['ENTRPRS_PROJ_TYPE_NM'])?></td>
      <td width="50" align="center" bgcolor="<?php echo htmlspecialchars($migr);?>" style="color:#FFFFFF; font-size:9px"><?php dateshw($row_por['Exec_Migration_Pln_Dt'], $row_por['PHASE_NAME'], $row_por['Exec_Migration_Act_Dt'], $uid_x, $row_por['ENTRPRS_PROJ_TYPE_NM'])?></td>
      <td width="35" align="center" bgcolor="<?php echo htmlspecialchars($decm);?>" style="color:#FFFFFF; font-size:9px"><?php pcntshw($row_por['Exec_Decommission_Cmpl_Prct'], $row_por['Exec_Decommission_Pln_Dt'], $row_por['PHASE_NAME'], $row_por['Exec_Decommission_Act_Dt'], $uid_x, $row_por['ENTRPRS_PROJ_TYPE_NM'])?></td>
      <td width="50" align="center" bgcolor="<?php echo htmlspecialchars($decm);?>" style="color:#FFFFFF; font-size:9px"><?php dateshw($row_por['Exec_Decommission_Pln_Dt'], $row_por['PHASE_NAME'], $row_por['Exec_Decommission_Act_Dt'], $uid_x, $row_por['ENTRPRS_PROJ_TYPE_NM'])?></td>
	    <td width="35" align="center" bgcolor="<?php echo htmlspecialchars($clsng);?>" style="color:#FFFFFF; font-size:9px"><?php pcntshw($row_por['Closing_Cmpl_Prct'], $row_por['Closing_Pln_Dt'], $row_por['PHASE_NAME'], $row_por['Closing_Act_Dt'], $uid_x, $row_por['ENTRPRS_PROJ_TYPE_NM'])?></td>
      <td width="50" align="center" bgcolor="<?php echo htmlspecialchars($clsng);?>" style="color:#FFFFFF; font-size:9px"><?php dateshwNL($row_por['Closing_Pln_Dt'], $row_por['PHASE_NAME'], $row_por['Closing_Act_Dt'], $uid_x, $row_por['ENTRPRS_PROJ_TYPE_NM'])?></td>

                <?php    // convert this to a function when you have time
								$prj_name = $row_por['PROJ_NM'];
								
								$ri_region = $row_por['Region'];
								$ri_program = $row_por['PRGM'];
								$ri_fyear = $row_por['FISCL_PLAN_YR'];
								// echo $row_rip_t['progCt_t']; 
								// RandI colors // convert to function when you have time
								
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
                                  <a href="ri2.php?prj_name=<?php echo htmlspecialchars($row_por['PROJ_NM']);?>&count=<?php echo htmlspecialchars($row_por['Prj_RiskAndIssue_Cnt']); ?>&uid=<?php echo $uid_x; ?>&winuser=<?php echo $row_por['PROJ_OWNR_NM']; ?>&fscl_year=<?php echo $row_por['FISCL_PLAN_YR']; ?>" class="ocdframe"><?php echo htmlspecialchars($row_por['Prj_RiskAndIssue_Cnt']); ?></a>
                                <?php } else { ?>
                                  <a href="ri2.php?prj_name=<?php echo htmlspecialchars($row_por['PROJ_NM']);?>&count=<?php echo htmlspecialchars($row_por['Prj_RiskAndIssue_Cnt']); ?>&uid=<?php echo $uid_x; ?>&winuser=<?php echo $row_por['PROJ_OWNR_NM']; ?>&fscl_year=<?php echo $row_por['FISCL_PLAN_YR']; ?>" class="ocdframe"><?php echo htmlspecialchars($row_por['Prj_RiskAndIssue_Cnt']); ?></a>
                                <?php } ?>
                          </td>
                          <td align="center" bgcolor="<?php //echo $prog_clr ?>">
                          	    <?php if($row_por['Prg_RiskAndIssue_Cnt'] > 0) { // program risk and issues?>
                                  <a href="ri2-prg.php?<?php echo "proj_nm=" . $row_por['PROJ_NM'] . "&region=" . $region_clps_mtch . "&program=" . $program_clps_mtch . "&fscl_year=" . $row_por['FISCL_PLAN_YR'] . "&count=" . $row_por['Prg_RiskAndIssue_Cnt'] . "&uid=" .  $uid_x ; ?>" class="ocdframe">0<?php //echo htmlspecialchars($row_por['Prg_RiskAndIssue_Cnt']); ?></a>
                                <?php } else { ?>
                                  <a href="ri2-prg.php?<?php echo "proj_nm=" . $row_por['PROJ_NM'] . "&region=" . $region_clps_mtch . "&program=" . $program_clps_mtch . "&fscl_year=" . $row_por['FISCL_PLAN_YR'] . "&count=" . $row_por['Prg_RiskAndIssue_Cnt'] . "&uid=" .  $uid_x ; ?>" class="ocdframe">0<?php //echo htmlspecialchars($row_por['Prg_RiskAndIssue_Cnt']); ?></a>
                                <?php } ?>
                          </td>
                                                           
                <td style="padding:2px" align="center">
                  <a href="../regional/details.php?fiscal_yr=<?php echo $row_por['FISCL_PLAN_YR']?>&uid=<?php echo htmlspecialchars($uid_x)?>" class="miframe"><span class="glyphicon glyphicon-zoom-in" style="font-size:12px;"></span></a>
                </td>
    </tr>
    <?php } ?>
  </tbody>
</table>