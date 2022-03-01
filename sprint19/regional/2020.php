<table width="100%" border="0" cellpadding="5" cellspacing="0" class="table-striped table-bordered table-hover">
  <thead>
    <tr align="left" valign="top" style="color:#FFFFFF; background-color:#00aaf5">
      <th colspan="2" align="left"><h5 style="padding:5px"><strong><?php echo htmlspecialchars($fsyear); ?> &gt; REGION &gt; PROGRAM &gt; PROJECT DATA &gt; DETAILED PHASE</strong> (<?php echo htmlspecialchars($row_pcount['pcount']);?>)</h5></th>
      </tr>
    </thead>
    <tbody>
    
    
    <?php while( $row_reg_clsp = sqlsrv_fetch_array( $stmt_reg_clsp, SQLSRV_FETCH_ASSOC)){?>
    <tr align="left" valign="top">
      <td colspan="2" valign="top" style="padding:5px"><!--<span class="glyphicon glyphicon-triangle-right" style="font-size:10px"></span>--> <a data-toggle="collapse" data-parent="#accordion<?php echo htmlspecialchars($row_reg_clsp['Region']);?>" href="#collapseOne<?php echo htmlspecialchars($row_reg_clsp['Region']);?>"><?php echo htmlspecialchars($row_reg_clsp['Region']);?></a> <span style="font-size:11px">(<?php echo htmlspecialchars($row_reg_clsp['projCount']);?>)</span>
        <div id="accordion<?php echo htmlspecialchars($row_reg_clsp['Region']);?>">
          <div id="collapseOne<?php echo htmlspecialchars($row_reg_clsp['Region']);?>" class="panel-collapse collapse out">
            <div class="row" align="center">
              <div>
					<!--start program list count loop-->
					<?php 
                    // program_collapse
					$match_reg = htmlspecialchars($row_reg_clsp['Region']);
					
					$sql_prog_clsp = "SELECT ROW_NUMBER() OVER(ORDER BY EPS.ProjectStage.PRGM ) AS RWNM , EPS.ProjectStage.PRGM, COUNT(EPS.ProjectStage.PRGM) AS prog_count
					FROM EPS.ProjectStage
					WHERE EPS.ProjectStage.Region = '$match_reg' AND FISCL_PLAN_YR = '$fsyear'
					GROUP BY EPS.ProjectStage.PRGM
					";
					$stmt_prog_clsp = sqlsrv_query($conn_COXProd, $sql_prog_clsp);
					//$row_prog_clsp = sqlsrv_fetch_array( $stmt_prog_clsp, SQLSRV_FETCH_ASSOC)
					//echo $row_prog_clsp['PROJ_ID']
                    ?>
                <table width="98%" border="0" class="table-striped table-bordered table-hover">
                <?php while ($row_prog_clsp = sqlsrv_fetch_array($stmt_prog_clsp, SQLSRV_FETCH_ASSOC)){?>
                <!--add accordian and loop through all projects related to region>program>project data-->
					        <?php 
							// proj_clps
							$region_clps_mtch = htmlspecialchars($row_reg_clsp['Region']);
							$program_clps_mtch = ($row_prog_clsp['PRGM']);
							
							$sql_proj_clps = "SELECT *
							FROM EPS.ProjectStage
							WHERE EPS.ProjectStage.Region = '$region_clps_mtch' AND
							EPS.ProjectStage.PRGM = '$program_clps_mtch' AND
							FISCL_PLAN_YR = '$fsyear'
							ORDER BY EPS.ProjectStage.FISCL_PLAN_YR, EPS.ProjectStage.Sub_Prg, EPS.ProjectStage.Plan_Finish_Dt, EPS.ProjectStage.PROJ_NM";
							$stmt_proj_clps = sqlsrv_query( $conn_COXProd, $sql_proj_clps );
							//echo $row_pcount['PROJ_ID']
							?>  
                	
                  <tr style="vertical-align:text-middle"> 
                    <td align="left" style="padding:5px"><span class="glyphicon glyphicon-triangle-right" style="font-size:10px"></span><span style="font-size:12px"><a data-toggle="collapse" data-parent="#accordion1<?php echo htmlspecialchars($row_prog_clsp['RWNM']) . $region_clps_mtch;?>" href="#collapseOne1<?php echo htmlspecialchars($row_prog_clsp['RWNM']) . $region_clps_mtch ;?>" ><?php echo htmlspecialchars($row_prog_clsp['PRGM']);?> </a> (<?php echo htmlspecialchars($row_prog_clsp['prog_count']);?>)</span>

                        <div id="accordion1<?php echo htmlspecialchars($row_prog_clsp['RWNM']) . $region_clps_mtch;?>">
                              <div id="collapseOne1<?php echo htmlspecialchars($row_prog_clsp['RWNM']). $region_clps_mtch ;?>" class="panel-collapse collapse out">
                                     <div class="panel-body"> 
                                     	<table width="100%" border="0" class="table-striped table-bordered table-hover">
                      <tbody>
                        <tr style="font-size:12px; background-color:#00aaf5; color:#FFFFFF">
                          <td><strong>SCOPE DESC</strong></td>
                          <td><strong>PROJECT NAME</strong></td>
                          <td><strong>FACILITY</strong></td>
                          <td><strong>OWNER</strong></td>
                          <td><strong>FISCAL YR</strong></td>
                          <td><strong>ORACLE CODE</strong></td>
                          <td><strong>WATTS MO</strong></td>
                          <td><strong>STAGE</strong></td>
                          <td align="center"><strong>START</strong></td>
                          <td align="center"><strong>FINISH</strong></td>
                          <td align="center"><strong>EXEC PREP</strong></td>
                          <td align="center"><strong>SITE PREP</strong></td>
                          <td align="center"><strong>INSTALL</strong></td>
                          <td align="center"><strong>MIGRATION</strong></td>
                          <td align="center"><strong>DECOM</strong></td>
                          <td align="center"><strong>PRJ RI</strong></td>
                          <td align="center"><strong>PRG RI</strong></td>
                          <td align="center"><strong>VIEW</strong></td>
                          <!--<td align="center"><strong>PP</strong></td>-->
                          
                        </tr> 
                        <?php while ($row_proj_clps = sqlsrv_fetch_array($stmt_proj_clps, SQLSRV_FETCH_ASSOC)){ ?>
                        
							<?php 
							// COLOR LOGIC
							// <Stage name>_Flg                     Stage Flag, 0 for project has no stage, 1 has a stage
							// <Stage name>_Pln_Dt                  Stage Plan Date
							// <Stage name>_Act_Dt                  Stage Actual Date
							// <Stage name>_Late_Flg                Stage Late flag (0 for No, 1 for Late, Null for no stage)
							
							// FOR ALL NOT IN EXECUTE OR CLOSING
							$grey_stages = 1;
							if($row_proj_clps['PHASE_NAME'] == '01 Proposed' || $row_proj_clps['PHASE_NAME'] == '02 Allocated' || $row_proj_clps['PHASE_NAME'] == '03 Released' || $row_proj_clps['ENTRPRS_PROJ_TYPE_NM'] == 'Reporting Only' || $row_proj_clps['ENTRPRS_PROJ_TYPE_NM'] == '' || $row_proj_clps['PHASE_NAME'] == 'Cancelled') {
							$grey_stages = 0;
							}
														
							// execute prep cell color
							$exe = '#00d257'; // Cox Green
                            if($row_proj_clps['Exec_Prep_Flg'] != 1 || $row_proj_clps['Exec_Prep_Act_Dt'] == ''){ // 0 = has no stage | 1 = has stage
								$exe = '#00aaf5'; //Cox Blue
							}
							
                                 // red logic fixed; do not show red on day of exec prep

                                if(is_null($row_proj_clps['Exec_Prep_Pln_Dt'])){ 
                                
                                    if($row_proj_clps['Exec_Prep_Late_Flg'] == 1){ // 0 = not late | 1 = late c1c1c1
								    $exe = 'red'; // Red
							        }

                                } else {

                                    $execPDx = $row_proj_clps['Exec_Prep_Pln_Dt'];
								    $execPD = date_format($execPDx, 'm-d-Y');
								    $execTD = date('m-d-Y');

                                    if($row_proj_clps['Exec_Prep_Late_Flg'] == 1 && $execPD != $execTD){ //if exec prep late flag = 1 and exec prep plan date is not equal to today
								    $exe = 'red'; // Red
                                    }
                                }

							if($grey_stages == 0 || $row_proj_clps['Exec_Prep_Flg'] != 1){ // if reporting only and not in Execute Prep then turn grey
								$exe = '#c1c1c1'; 
							}
																					
							// site prep cell color
							$site = '#00d257';
							if($row_proj_clps['Site_Prep_Flg'] != 1 || $row_proj_clps['Site_Prep_Act_Dt'] == ''){
								$site = '#00aaf5'; // Cox Blue
							}
                                // red logic fixed; do not show red on day of site prep
                                if(is_null($row_proj_clps['Site_Prep_Pln_Dt'])){ 

							        if($row_proj_clps['Site_Prep_Late_Flg'] == 1){
								    $site = 'red';
                                    }

                                } else {

                                    $sitePDx = $row_proj_clps['Site_Prep_Pln_Dt'];
								    $sitePD = date_format($sitePDx, 'm-d-Y');
								    $siteTD = date('m-d-Y');

                                    if($row_proj_clps['Site_Prep_Late_Flg'] == 1 && $sitePD != $siteTD){ //if site prep late flag = 1 and site prep plan date is not equal to today
								    $site = 'red';
                                    }
                                }
							if($grey_stages == 0 || $row_proj_clps['Site_Prep_Flg'] != 1 ){
								$site = '#c1c1c1';
							}
								
                            // install cell color							
							
							$instal = '#00d257';
							if($row_proj_clps['Install_Flg'] != 1 || $row_proj_clps['Install_Act_Dt'] == ''){
								$instal = '#00aaf5';
							}
                                // red logic fixed; do not show red on day of install
							    if (is_null($row_proj_clps['Install_Pln_Dt'])){

								    if($row_proj_clps['Install_Late_Flg'] == 1){ 
                                    $instal = 'red'; 
                                    }

							    } else {

								    $installPDx = $row_proj_clps['Install_Pln_Dt'];
								    $installPD = date_format($installPDx, 'm-d-Y');
								    $installTD = date('m-d-Y');
									
							        if($row_proj_clps['Install_Late_Flg'] == 1 && $installPD != $installTD){  // if install late flag = 1 and install plan date is not equal to today
                                    $instal = 'red'; 
                                    }
							    }

							if($grey_stages == 0 || $row_proj_clps['Install_Flg'] != 1){
								$instal = '#c1c1c1';
							}
							
							// migration cell color
							
							$migr = '#00d257'; //cox green
							if($row_proj_clps['Migration_Pln_Dt'] != '' || $row_proj_clps['Migration_Late_Flg'] != 1){
								$migr = '#00aaf5'; //cox blue
							}
                             // red logic fixed; do not show red on day of migration
							    if (is_null($row_proj_clps['Migration_Pln_Dt'])){

							        if($row_proj_clps['Migration_Late_Flg'] == 1) {
								        $migr = 'red';
							        }

                                } else {

   								    $migratePDx = $row_proj_clps['Migration_Pln_Dt'];
								    $migratePD = date_format($migratePDx, 'm-d-Y');
								    $migrateTD = date('m-d-Y');  
                                    
                                    if($row_proj_clps['Migration_Late_Flg'] == 1 && $migratePD != $migrateTD) {
								        $migr = 'red';
							        }
                                }
							if($row_proj_clps['Migration_Act_Dt'] != '') {
								$migr = '#00d257';
							}
							if($grey_stages == 0 || $row_proj_clps['Migration_Pln_Dt'] == '' ){
								$migr = '#c1c1c1'; //grey
							} 
								
                            // decom cell color
							$decm = '#00d257'; // cox green
							if($row_proj_clps['Decom_Flg'] != 1 || $row_proj_clps['Decom_Act_Dt'] == '' ){
								$decm = '#00aaf5'; //cox blue
							}
                             // red logic fixed; do not show red on day of decom
							    if (is_null($row_proj_clps['Decom_Pln_Dt'])){

							        if($row_proj_clps['Decom_Late_Flg'] == 1){
								        $decm = 'red';
							        }

                                } else {

                                    $decomPDx = $row_proj_clps['Decom_Pln_Dt'];
								    $decomPD = date_format($decomPDx, 'm-d-Y');
								    $decomTD = date('m-d-Y'); 

                                    if($row_proj_clps['Decom_Late_Flg'] == 1 && $decomPD != $decomTD){
								        $decm = 'red';
							        }
                				}

							if($grey_stages == 0 || $row_proj_clps['Decom_Pln_Dt'] == '' ){
								$decm = '#c1c1c1';
							}
								
							
							//Trim UID Brackets
							//htmlspecialchars($uid_x) = substr($row_proj_clps['uid'],1,-1);
							$uid_x = $row_proj_clps['PROJ_ID'];	
							?>
                        
                        <tr style="font-size:10px">
                          <td><?php echo $row_proj_clps['SCOP_DESC'];?></td>
                          <td><a href="https://coxcomminc.sharepoint.com/sites/pwaeng/project%20detail%20pages/schedule.aspx?projuid=<?php echo htmlspecialchars($uid_x)?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Click to View schedule for - <?php echo htmlspecialchars($row_proj_clps['PROJ_NM']);?> on EPS"><?php echo htmlspecialchars($row_proj_clps['PROJ_NM']);?></a></td>
                          <td><?php echo htmlspecialchars($row_proj_clps['EPSLocation_Cd']);?></td>
                          <td><?php echo htmlspecialchars($row_proj_clps['PROJ_OWNR_NM']);?></td>
                          <td align="center"><?php echo htmlspecialchars($row_proj_clps['FISCL_PLAN_YR']);?></td>                          
                          <td>
                                  <?php 
								  $ocd = $row_proj_clps['OracleProject_Cd'];
								  $ocd_splits = explode(';',$ocd);
								  foreach($ocd_splits as $ocd_codes) {
								  echo '<a class="mapframe"  data-toggle="tooltip" data-placement="top"  data-title="Click to view Order History" href="eq_history.php?ocd='. htmlspecialchars(trim($ocd_codes)) . '">' . htmlspecialchars(trim($ocd_codes)) . '</a> ';
								  }
								  ?>
                          </td>
                          <td><?php echo htmlspecialchars($row_proj_clps['WATTS_MO']);?></td>
                          <td><a href="l2-frame.php?uid=<?php echo htmlspecialchars($uid_x)?>" class="dno" data-toggle="tooltip" data-placement="top" title="Click to view Level 2 Tasks"><?php echo htmlspecialchars($row_proj_clps['PHASE_NAME']);?></a></td>
                          <td align="center"><?php convtimex($row_proj_clps['Plan_Start_Dt']);?></td>
                          <td align="center">
						  			<?php //convtimex($row_proj_clps['Plan_Finish_Dt']);?>
                                    <?php if($grey_stages == 0){ 
										echo '---';
									 } else {
									  	echo convtimex($row_proj_clps['Plan_Finish_Dt']);		
									 }
									?>
                          </td>
                          <td width="100px" align="center"  bgcolor="<?php echo htmlspecialchars($exe)?>" style="color:#FFFFFF" >
                          		<?php //if($row_proj_clps['Exec_Prep_Act_Dt'] == '') { echo 'this works';} 
								if($row_proj_clps['Exec_Prep_Pln_Dt'] == '' || $row_proj_clps['PHASE_NAME'] == '01 Proposed' || $row_proj_clps['PHASE_NAME'] == '02 Allocated' || $row_proj_clps['PHASE_NAME'] == '03 Released') {
									echo "--";
								}else if($row_proj_clps['Exec_Prep_Act_Dt'] != '' ) {	
									echo "<a style='color:#FFFFFF' href='l2-frame.php?uid=" . htmlspecialchars(htmlspecialchars($uid_x)) . "' class='dno'>" . date_format($row_proj_clps['Exec_Prep_Act_Dt'], 'm-d-Y') . "</a>";
								}else{
									echo "<a style='color:#FFFFFF' href='l2-frame.php?uid=" . htmlspecialchars(htmlspecialchars($uid_x)) . "' class='dno'>" . date_format($row_proj_clps['Exec_Prep_Pln_Dt'], 'm-d-Y') . "</a>";
								}
							
								?>
                          </td>
                          <td width="100px" align="center" bgcolor="<?php echo htmlspecialchars($site) ?>" style="color:#FFFFFF">
						        <?php 
								if($row_proj_clps['Site_Prep_Pln_Dt'] == ''|| $row_proj_clps['PHASE_NAME'] == '01 Proposed' || $row_proj_clps['PHASE_NAME'] == '02 Allocated' || $row_proj_clps['PHASE_NAME'] == '03 Released') {
									echo "--";
								}else if($row_proj_clps['Site_Prep_Act_Dt'] != ''){	
									echo "<a style='color:#FFFFFF' href='l2-frame.php?uid=" . htmlspecialchars($uid_x) . "' class='dno'>" . date_format($row_proj_clps['Site_Prep_Act_Dt'], 'm-d-Y') . "</a>";
								}else{
									echo "<a style='color:#FFFFFF' href='l2-frame.php?uid=" . htmlspecialchars($uid_x) . "' class='dno'>" . date_format($row_proj_clps['Site_Prep_Pln_Dt'], 'm-d-Y'). "</a>";
								}
								
								?>
						  </td>
                          <td width="100px" align="center" bgcolor="<?php echo htmlspecialchars($instal) ?>" style="color:#FFFFFF">
                          		<?php 
								if($row_proj_clps['Install_Pln_Dt'] == ''|| $row_proj_clps['PHASE_NAME'] == '01 Proposed' || $row_proj_clps['PHASE_NAME'] == '02 Allocated' || $row_proj_clps['PHASE_NAME'] == '03 Released') {
									echo "--";
								}else if($row_proj_clps['Install_Act_Dt'] != ''){	
									echo "<a style='color:#FFFFFF' href='l2-frame.php?uid=" . htmlspecialchars($uid_x) . "' class='dno'>" . date_format($row_proj_clps['Install_Act_Dt'], 'm-d-Y') . "</a>";
								}else{
									echo "<a style='color:#FFFFFF' href='l2-frame.php?uid=" . htmlspecialchars($uid_x) . "' class='dno'>" . date_format($row_proj_clps['Install_Pln_Dt'], 'm-d-Y') . "</a>";
								}
								?>
                          </td>
                          <td width="100px" align="center" bgcolor="<?php echo htmlspecialchars($migr) ?>" style="color:#FFFFFF">
                                <?php 
								if($row_proj_clps['Migration_Pln_Dt'] == ''|| $row_proj_clps['PHASE_NAME'] == '01 Proposed' || $row_proj_clps['PHASE_NAME'] == '02 Allocated' || $row_proj_clps['PHASE_NAME'] == '03 Released') {
									echo "--";
								}else if($row_proj_clps['Migration_Act_Dt'] != ''){	
									echo "<a style='color:#FFFFFF' href='l2-frame.php?uid=" . htmlspecialchars($uid_x) . "' class='dno'>" . date_format($row_proj_clps['Migration_Act_Dt'], 'm-d-Y') . "</a>";
								}else{
									echo "<a style='color:#FFFFFF' href='l2-frame.php?uid=" . htmlspecialchars($uid_x) . "' class='dno'>" . date_format($row_proj_clps['Migration_Pln_Dt'], 'm-d-Y') . "</a>";
								}
								?>
                          </td>
                          <td width="100px" align="center" bgcolor="<?php echo $decm ?>" style="color:#FFFFFF">
                                <?php 
								if($row_proj_clps['Decom_Pln_Dt'] == ''|| $row_proj_clps['PHASE_NAME'] == '01 Proposed' || $row_proj_clps['PHASE_NAME'] == '02 Allocated' || $row_proj_clps['PHASE_NAME'] == '03 Released') {
									echo "--";
								}else if($row_proj_clps['Decom_Act_Dt'] != ''){	
									echo "<a style='color:#FFFFFF' href='l2-frame.php?uid=" . htmlspecialchars($uid_x) . "' class='dno'>" . date_format($row_proj_clps['Decom_Act_Dt'], 'm-d-Y') . "</a>";
								}else{
									echo "<a style='color:#FFFFFF' href='l2-frame.php?uid=" . htmlspecialchars($uid_x) . "' class='dno'>" . date_format($row_proj_clps['Decom_Pln_Dt'], 'm-d-Y') . "</a>";
								}
								?>
                          </td>
                                <?php    
								// echo $row_rip_t['progCt_t']; 
								// RandI colors
								
								if ($row_proj_clps['Prj_RiskAndIssue_Cnt'] > 0) {
									$proj_clr = '#fcd12a';
								} else { 
									$proj_clr = '';
								}
								
								if ($row_proj_clps['Prg_RiskAndIssue_Cnt'] > 0) {
									$prog_clr = '#fcd12a';
								} else { 
									$prog_clr = '';
								}
								?>
                          <td align="center" bgcolor="<?php echo $proj_clr ?>">
								<?php if($row_proj_clps['Prj_RiskAndIssue_Cnt'] > 0) { // prject risk and issues?>
                                <a href="ri2.php?prj_name=<?php echo htmlspecialchars($row_proj_clps['PROJ_NM']);?>&count=<?php echo htmlspecialchars($row_proj_clps['Prj_RiskAndIssue_Cnt']); ?>" class="miframe"><?php echo htmlspecialchars($row_proj_clps['Prj_RiskAndIssue_Cnt']); ?></a>
                                <?php } else { ?>
                                <?php echo $row_proj_clps['Prj_RiskAndIssue_Cnt']; ?>
                                <?php } ?>
                          </td>

                          <td align="center" bgcolor="<?php echo $prog_clr ?>">
                          	    <?php if($row_proj_clps['Prg_RiskAndIssue_Cnt'] > 0) { // program risk and issues?>
                                <a href="ri2-prg.php?region=<?php echo htmlspecialchars($region_clps_mtch)?>&program=<?php echo htmlspecialchars($program_clps_mtch)?>&fscl_year=<?php echo htmlspecialchars($row_proj_clps['FISCL_PLAN_YR'])?>&count=<?php echo htmlspecialchars($row_proj_clps['Prg_RiskAndIssue_Cnt'])?>" class="miframe"><?php echo htmlspecialchars($row_proj_clps['Prg_RiskAndIssue_Cnt']); ?></a>
                                <?php } else { ?>
                                <?php echo htmlspecialchars($row_proj_clps['Prg_RiskAndIssue_Cnt']); ?>
                                <?php } ?>
                          </td>
                                
						   	
                          <td align="center" title="UID: <?php echo htmlspecialchars($uid_x)?>" ><a data-toggle="collapse" data-parent="#accordion<?php echo htmlspecialchars($uid_x)?>" href="#collapseOne1<?php echo htmlspecialchars($uid_x)?>">+</a></td>
                          <!--<td align="center"><a href="level_2_details.php?uid=<?php // echo htmlspecialchars($uid_x)?>" target="_blank" class="Iframe">+</a></td>-->
                        </tr>
                        
                        <tr>
                        	<td colspan="18">
									<div id="collapseOne1<?php echo htmlspecialchars($uid_x)?>" class="panel-collapse collapse out" style="background-color:white">
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
                                                                  <td><?php echo htmlspecialchars($row_proj_clps['WATTS_MO']);?></td>
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
                                                                  <td>
																  	<?php //convtimex($row_proj_clps['Plan_Finish_Dt']);?>
																<?php if($grey_stages == 0){ 
                                                                    echo '---';
                                                                 } else {
                                                                    echo convtimex($row_proj_clps['Plan_Finish_Dt']);		
                                                                 }
                                                                ?>
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
                                   
                                                              </tbody>
                                                            </table><br>

																	<br>
														  </div>
                                                          <div class="col-lg-7">
                                                          	<h4>PROJECT INFO</h4>
                                                            <h5>Access Required </h5>
                                                            <P>
                                                            <a href="https://coxcomminc.sharepoint.com/sites/pwaeng/project detail pages/schedule.aspx?projuid=<?php echo htmlspecialchars($uid_x)?>" target="_blank">EPS Project Schedule</a> 
                                                            <br>
																<?php if($row_proj_clps['Prj_RiskAndIssue_Cnt'] > 0) { // prject risk and issues?>
                                                                <a href="ri2.php?prj_name=<?php echo htmlspecialchars($row_proj_clps['PROJ_NM']);?>&count=<?php echo htmlspecialchars($row_proj_clps['Prj_RiskAndIssue_Cnt']) ?>" class="miframe">Project Risks& Issues (<?php echo htmlspecialchars($row_proj_clps['Prj_RiskAndIssue_Cnt']); ?>)</a>
                                                                <?php } else { ?>
                                                                <?php echo 'Project Risks& Issues (' . htmlspecialchars($row_proj_clps['Prj_RiskAndIssue_Cnt']) . ')'; ?>
                                                                <?php } ?>
                                                            <br>  
                                                             	<?php if($row_proj_clps['Prg_RiskAndIssue_Cnt'] > 0) { // program risk and issues?>
                                                                <a href="ri2-prg.php?region=<?php echo htmlspecialchars($region_clps_mtch);?>&program=<?php echo htmlspecialchars($program_clps_mtch);?>&fscl_year=<?php echo htmlspecialchars($row_proj_clps['FISCL_PLAN_YR']);?>&count=<?php echo htmlspecialchars($row_proj_clps['Prg_RiskAndIssue_Cnt']);?>" class="miframe">Program Risks& Issues (<?php echo htmlspecialchars($row_proj_clps['Prg_RiskAndIssue_Cnt']); ?>)</a>
                                                                <?php } else { ?>
                                                                <?php echo 'Project Risks& Issues (' . htmlspecialchars($row_proj_clps['Prg_RiskAndIssue_Cnt']) . ')' ;?>
                                                                <?php } ?>
															</P>
                                                            
                                                            <h5>
                                                            No Access Required
                                                            </h5>
                                                            <p>
                                                            <a href="level_2_details_all.php?uid=<?php echo htmlspecialchars($uid_x)?>" class="mapframe">Project Schedule</a>
                                                            <br>
																<?php if($row_proj_clps['Prj_RiskAndIssue_Cnt'] > 0) { // prject risk and issues?>
                                                                <a href="ri-no_access_proj.php?proj_name=<?php echo htmlspecialchars($row_proj_clps['PROJ_NM']);?>&count=<?php echo htmlspecialchars($row_proj_clps['Prj_RiskAndIssue_Cnt']) ?>" class="mapframe">Project Risks& Issues (<?php echo htmlspecialchars($row_proj_clps['Prj_RiskAndIssue_Cnt']); ?>)</a>
                                                                <?php } else { ?>
                                                                <?php echo 'Project Risks& Issues (' . htmlspecialchars($row_proj_clps['Prj_RiskAndIssue_Cnt']) . ')'; ?>
                                                                <?php } ?>
                                                             <br>
                                                             	<?php if($row_proj_clps['Prg_RiskAndIssue_Cnt'] > 0) { // program risk and issues?>
                                                                <a href="ri-no_access_prog.php?region=<?php echo htmlspecialchars($region_clps_mtch);?>&program=<?php echo $program_clps_mtch?>&fscl_year=<?php echo htmlspecialchars($row_proj_clps['FISCL_PLAN_YR']);?>&count=<?php echo htmlspecialchars($row_proj_clps['Prg_RiskAndIssue_Cnt'])?>" class="mapframe">Program Risks& Issues (<?php echo htmlspecialchars($row_proj_clps['Prg_RiskAndIssue_Cnt']); ?>)</a>
                                                                <?php } else { ?>
                                                                <?php echo 'Program Risks& Issues (' . htmlspecialchars($row_proj_clps['Prg_RiskAndIssue_Cnt']) . ')' ;?>
                                                                <?php } ?>
                                                            </p>
															<!--<a href="eq-order-history?uid=<?php // echo htmlspecialchars($uid_x)?>" class="mapframe">Equipment Order History</a>    -->                                                        <!--<table width="100%" border="0" class="table-striped table-bordered table-hover">
                                                             
                                                         <!--<iframe width="1150" height="500" src="l2-frame.php?uid=<?php //echo htmlspecialchars($uid_x)?>" frameborder="0" allowfullscreen></iframe>-->
                                                            
                                                        </div>
                                                          
                                                      </div>
												  </div>
								    </div>                    
                            </td>
                       </tr>                            
                        <?php } ?>
                      </tbody>
                    </table> 
                    	         </div>
                              </div>
                        	</div>
                        </td>
				</tr> 
                 <?php } ?> 
                 </table>
                </div>
			</div>
        </div>        </td>
    </tr>
 <?php } 
 sqlsrv_free_stmt($stmt_reg_clsp);
 ?>
  <td width="94%">
 </tbody>
</table>