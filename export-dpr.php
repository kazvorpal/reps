<?php include ("includes/functions.php");?>
<?php include ("db_conf.php");?>
<?php include ("data/emo_data.php");?>
<?php 
// workflow_stage
$bmysql = $_GET['sql'];
$sql_por = "$bmysql"; 
$stmt_por = sqlsrv_query( $conn_COXProd, $sql_por );


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

<table width="100%" border="1" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td>UID</td>
      <td>PROGRAM</td>
      <td>SUBPROGRAM</td>
      <td>PROJECT NAME</td>
      <td>OWNER</td>
      <td>TECH PROJ MGR</td>
      <td>FISCAL YR</td>
      <td>REGION</td>
      <td>MARKET</td>
      <td>FACILITY</td>
      <td>ORACLE CODE</td>
      <td>ORACLE START</td>
      <td>ORACLE END</td>
      <td>STAGE</td>
      <td>WATTS MO</td>
      <td>SCOPE DESC</td>
      <td>PPM#</td>
      <td>PROJECT TYPE</td>
      <td align="center">START DATE</td>
      <td align="center">FINISH DATE</td>
      <td align="center">COMMIT DATE</td>
      <td align="center">EXECUTION PREP</td>
      <td align="center">SITE PREP</td>
      <td align="center">INSTALL </td>
      <td align="center">MIGRATION</td>
      <td align="center">DECOM</td>
      
    </tr>
    <?php while( $row_program_n = sqlsrv_fetch_array( $stmt_por, SQLSRV_FETCH_ASSOC)) {?>
    <tr>
      <td><?php // $uid_x = substr($row_program_n['uid'],1,-1);?>
          <?php $uid_x = $row_program_n['PROJ_ID'];?>
          <?php echo $uid_x?>
      </td>
      <td><?php echo $row_program_n['PRGM'];?></td>
      <td><?php //echo $row_program_n['PROJECT_SIZE'];?></td>
      <td><?php echo $row_program_n['PROJ_NM'];?></td>
      <td><?php echo $row_program_n['PROJ_OWNR_NM'];?></td>
      <td><?php echo $row_program_n['TECH_PROJ_MGR'];?></td>
      <td><?php echo $row_program_n['FISCL_PLAN_YR'];?></td>
      <td><?php echo $row_program_n['Region'];?></td>
      <td><?php echo $row_program_n['Market'];?></td>
      <td><?php echo $row_program_n['Facility'];?></td>
      <td><?php echo str_replace('', '', str_replace(";", "</br>", $row_program_n['OracleProject_Cd'])) ;?></td>
      <td><?php if(is_null($row_program_n['OracleProjectStart_Dt'])) {
		  										echo '';
	  											} else {
		  										echo date_format($row_program_n['OracleProjectStart_Dt'], 'Y-m-d');
												}
											?>
      </td>
      <td><?php if(is_null($row_program_n['OracleProjectEnd_Dt'])) {
		  										echo '';
	  											} else {
		  										echo date_format($row_program_n['OracleProjectEnd_Dt'], 'Y-m-d');
												}
											?>
      </td>
      <td><?php echo $row_program_n['PHASE_NAME'];?></td>
      <td><?php echo $row_program_n['WATTS_MO'];?></td>
      <td><?php echo $row_program_n['SCOP_DESC'];?></td>
      <td><?php echo $row_program_n['PPM_PROJ'];?></td>
      <td><?php echo $row_program_n['ENTRPRS_PROJ_TYPE_NM'];?></td>
      <td align="center"><?php echo convtimex($row_program_n['Plan_Start_Dt']);?></td>
      <td align="center"><?php 		if($row_program_n['Exec_Prep_Pln_Dt'] == '' || $row_program_n['PHASE_NAME'] == '01 Proposed' || $row_program_n['PHASE_NAME'] == '02 Allocated' || $row_program_n['PHASE_NAME'] == '03 Released'){ 
										echo '---';
									 } else {
						convtimex($row_program_n['Plan_Finish_Dt']);
									 }
						?>
      </td>
      <td align="center"><?php echo convtimex($row_program_n['COMIT_DT']);?></td>
      <td align="center">
                          		<?php //if($row_program_n['Exec_Prep_Act_Dt'] == '') { echo 'this works';} 
								if($row_program_n['Exec_Prep_Pln_Dt'] == '' || $row_program_n['PHASE_NAME'] == '01 Proposed' || $row_program_n['PHASE_NAME'] == '02 Allocated' || $row_program_n['PHASE_NAME'] == '03 Released') {
									echo "--";
								}else if($row_program_n['Exec_Prep_Act_Dt'] != '' ) {	
									echo date_format($row_program_n['Exec_Prep_Act_Dt'], 'Y-m-d') . "";
								}else{
									echo date_format($row_program_n['Exec_Prep_Pln_Dt'], 'Y-m-d');
							    }
								?>
                          </td>
                          <td align="center">
						        <?php 
								if($row_program_n['Site_Prep_Pln_Dt'] == '' || $row_program_n['PHASE_NAME'] == '01 Proposed' || $row_program_n['PHASE_NAME'] == '02 Allocated' || $row_program_n['PHASE_NAME'] == '03 Released') {
									echo "--";
								}else if($row_program_n['Site_Prep_Act_Dt'] != ''){	
									echo date_format($row_program_n['Site_Prep_Act_Dt'], 'Y-m-d') . "";
								}else{
									echo date_format($row_program_n['Site_Prep_Pln_Dt'], 'Y-m-d');
								}
								
								?>
						  </td>
                          <td align="center">
                          		<?php 
								if($row_program_n['Install_Pln_Dt'] == '' || $row_program_n['PHASE_NAME'] == '01 Proposed' || $row_program_n['PHASE_NAME'] == '02 Allocated' || $row_program_n['PHASE_NAME'] == '03 Released') {
									echo "--";
								}else if($row_program_n['Install_Act_Dt'] != ''){	
									echo date_format($row_program_n['Install_Act_Dt'], 'Y-m-d') . "";
								}else{
									echo date_format($row_program_n['Install_Pln_Dt'], 'Y-m-d');
								}
								?>
                          </td>
                          <td align="center">
                                <?php 
								if($row_program_n['Migration_Pln_Dt'] == '' || $row_program_n['PHASE_NAME'] == '01 Proposed' || $row_program_n['PHASE_NAME'] == '02 Allocated' || $row_program_n['PHASE_NAME'] == '03 Released') {
									echo "--";
								}else if($row_program_n['Migration_Act_Dt'] != ''){	
									echo date_format($row_program_n['Migration_Act_Dt'], 'Y-m-d') . "";
								}else{
									echo date_format($row_program_n['Migration_Pln_Dt'], 'Y-m-d');
								}
								?>
                          </td>
                          <td align="center">
                                <?php 
								if($row_program_n['Decom_Pln_Dt'] == '' || $row_program_n['PHASE_NAME'] == '01 Proposed' || $row_program_n['PHASE_NAME'] == '02 Allocated' || $row_program_n['PHASE_NAME'] == '03 Released') {
									echo "--";
								}else if($row_program_n['Decom_Act_Dt'] != ''){	
									echo date_format($row_program_n['Decom_Act_Dt'], 'Y-m-d') . "";
								}else{
									echo date_format($row_program_n['Decom_Pln_Dt'], 'Y-m-d');
								}
								?>
                          </td>

      </tr>
    <?php } ?>
  </tbody>
</table>





</body>
</html>