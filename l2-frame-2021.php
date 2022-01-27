<?php include ("includes/functions.php");?>
<?php include ("db_conf.php");?>
<?php include ("data/emo_data.php");?>

<?php    
// Define proj_id
$prj_id = $_GET['uid'];

// Level 2 information by project id
//$row_l2['last_update']
$sql_proj = "SELECT * FROM [COX_Dev].[EPS].[ProjectStageTaskL2]  WHERE [PROJ_ID] = '$prj_id' ORDER BY [Hw_Id]";
$stmt_proj = sqlsrv_query( $conn, $sql_proj );
//$row_proj = sqlsrv_fetch_array( $stmt_proj, SQLSRV_FETCH_ASSOC)// Define proj_id

// COUNT ROWS
$sql_proj_count = "SELECT count(*) AS ttls FROM [COX_Dev].[EPS].[ProjectStageTaskL2]  WHERE [PROJ_ID] = '$prj_id'";
$stmt_proj_count = sqlsrv_query( $conn, $sql_proj_count );
$row_proj_count = sqlsrv_fetch_array( $stmt_proj_count, SQLSRV_FETCH_ASSOC )// Define proj_id

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>

<link href="css/bootstrap-3.3.4.css" rel="stylesheet" type="text/css">
</head>

<body>
<h4>DETAILED PHASE (Level 3 &amp; 4)</h4>
 <?php // echo $row_proj_count['ttls'];
 // exit
  ?>
 <?php if($row_proj_count['ttls'] == 0) {?>
<div align="center" class=" alert-danger">
        <p>No Data Available</p>
      </div>
 <?php } else { ?>                                                           
                                                            
<table width="100%" border="0" class="table-striped table-bordered table-hover" style="font-size:12px">
                                                              <tbody>
                                                                <tr>
                                                                  <td bgcolor="#ECEBEB"><strong>HARDWARE ID</strong></td>
                                                                  <td colspan="5" align="center" bgcolor="#ECEBEB"><strong>INSTALLATION</strong></td>
                                                                  <td align="center" bgcolor="#ECEBEB"><strong>MIGRATE</strong></td>
                                                                  <td colspan="2" align="center" bgcolor="#ECEBEB"><strong>DECOM</strong></td>
                                                                </tr>
                                                                <tr>
                                                                  <td>&nbsp;</td>
                                                                  <td width="135" align="center">Create EWP</td>
                                                                  <td width="135" align="center">RX Chassis</td>
                                                                  <td width="135" align="center">RX Material</td>
                                                                  <td width="135" align="center">Verify 2nd Px</td>
                                                                  <td width="135" align="center">Servicability</td>
                                                                  <td width="135" align="center">Migration Complete</td>
                                                                  <td width="135" align="center">PX Down</td>
                                                                  <td width="135" align="center">Deliver to WHSE</td>
                                                                </tr>
                                                                <?php while ($row_proj = sqlsrv_fetch_array( $stmt_proj, SQLSRV_FETCH_ASSOC)) { ?>
                                                                <?php									
																// Cell Colors 
																// #00d257 green actual date
																// #00aaf5 blue future date
																// #c1c1c1 grey 
																// red red
																
																// RECEIVE CHASSIS
																$clr_rx_big_irn = '#c1c1c1'; //grey
																
																if($row_proj['Actual Receive Chassis'] != '') {
																	$clr_rx_big_irn = '#00d257'; // green actual date
																
																} else if($row_proj['Plan Receive Chassis'] == '' && $row_proj['Actual Receive Chassis'] == '') {
																	$clr_rx_big_irn = '#c1c1c1'; // grey, no dates availble
																	
																} else if($row_proj['Plan Receive Chassis'] < date("Y-m-d") && $row_proj['Actual Receive Chassis'] == '' ) {
																	$clr_rx_big_irn = 'red'; // red, Late if plan is less than today and actual is not empty
																			
																} else {
																	$clr_rx_big_irn = '#00aaf5'; //grey
																}
																
																// SECONDARY  POWER
																$clr_sec_pwr = '#c1c1c1';
																
																if($row_proj['Actual Verify Secondary Power'] != '') {
																	$clr_sec_pwr = '#00d257'; // green actual date
																
																} else if($row_proj['Plan Verify Secondary Power'] == '' && $row_proj['Actual Verify Secondary Power'] == '') {
																	$clr_sec_pwr = '#c1c1c1'; // grey, no dates availble
																	
																} else if($row_proj['Plan Verify Secondary Power'] < date("Y-m-d") && $row_proj['Actual Verify Secondary Power'] == '' ) {
																	$clr_sec_pwr = 'red'; // red, Late if plan is less than today and actual is not empty
																			
																} else {
																	$clr_sec_pwr = '#00aaf5'; //grey
																}
																
																// CREATE INSTALL EWP
																$clr_inst_eqp = '#c1c1c1'; // grey, no dates available
																
																if($row_proj['Actual Create Install EWP'] != '') {
																	$clr_inst_eqp = '#00d257'; // green actual date
																
																} else if($row_proj['Plan Create Install EWP'] == '' && $row_proj['Actual Create Install EWP'] == '') {
																	$clr_inst_eqp = '#c1c1c1'; // grey, no dates availble
																	
																} else if($row_proj['Plan Create Install EWP'] < date("Y-m-d") && $row_proj['Actual Create Install EWP'] == '' ) {
																	$clr_inst_eqp = 'red'; // red, Late if plan is less than today and actual is not empty
																			
																} else {
																	$clr_inst_eqp = '#00aaf5'; //grey
																}
																
																// RX Ancellary EQ
																$clr_rx_anc_eq = '#c1c1c1';
																
																if($row_proj['Actual RX Ancillary EQ'] != '') {
																	$clr_rx_anc_eq = '#00d257'; // green actual date
																
																} else if($row_proj['Plan RX Ancillary EQ'] == '' && $row_proj['Actual RX Ancillary EQ'] == '') {
																	$clr_rx_anc_eq = '#c1c1c1'; // grey, no dates availble
																	
																} else if($row_proj['Plan RX Ancillary EQ'] < date("Y-m-d") && $row_proj['Actual RX Ancillary EQ'] == '' ) {
																	$clr_rx_anc_eq = 'red'; // red, Late if plan is less than today and actual is not empty
																			
																} else {
																	$clr_rx_anc_eq = '#00aaf5'; //grey
																}
																
																// SERVICEABLILTY
																$clr_rev_tst = '#c1c1c1';
																
																if($row_proj['Actual Review test'] != '') {
																	$clr_rev_tst = '#00d257'; // green actual date
																
																} else if($row_proj['Plan Review test'] == '' && $row_proj['Actual Review test'] == '') {
																	$clr_rev_tst = '#c1c1c1'; // grey, no dates availble
																	
																} else if($row_proj['Plan Review test'] < date("Y-m-d") && $row_proj['Actual Review test'] == '' ) {
																	$clr_rev_tst = 'red'; // red, Late if plan is less than today and actual is not empty
																			
																} else {
																	$clr_rev_tst = '#00aaf5'; //grey
																}
																
																// Migration Complete
																$clr_ecr_tic = '#c1c1c1';
																
																if($row_proj['Actual Migration Complete'] != '') {
																	$clr_ecr_tic = '#00d257'; // green actual date
																
																} else if($row_proj['Plan Migration Complete'] == '' && $row_proj['Actual Migration Complete'] == '') {
																	$clr_ecr_tic = '#c1c1c1'; // grey, no dates availble
																	
																//} else if(strtotime($row_proj['Plan ECR Ticket']) < strtotime(date("Y-m-d")) && $row_proj['Actual ECR Ticket'] == '' ) {
																} else if($row_proj['Plan Migration Complete'] < date("Y-m-d") && $row_proj['Actual Migration Complete'] == '' ) {
																	$clr_ecr_tic = 'red'; // red, Late if plan is less than today and actual is not empty
																			
																} else {
																	$clr_ecr_tic = '#00aaf5'; //grey
																}
																
																//  POWER DOWN
																$clr_prf_mig = '#c1c1c1';
																
																if($row_proj['Actual Power Down'] != '') {
																	$clr_prf_mig = '#00d257'; // green actual date
																
																} else if($row_proj['Plan Power Down'] == '' && $row_proj['Actual Power Down'] == '') {
																	$clr_prf_mig = '#c1c1c1'; // grey, no dates availble
																	
																} else if($row_proj['Plan Power Down'] < date("Y-m-d") && $row_proj['Actual Power Down'] == '' ) {
																	$clr_prf_mig = 'red'; // red, Late if plan is less than today and actual is not empty
																			
																} else {
																	$clr_prf_mig = '#00aaf5'; //grey
																}
																
																// DELIVER TO WAREHOUSE
																$clr_unrack_eq = '#c1c1c1';
																
																if($row_proj['Actual Deliver WHSE'] != '') {
																	$clr_unrack_eq = '#00d257'; // green,  actual date
																												
																} else if($row_proj['Plan Deliver WHSE'] == '' && $row_proj['Actual Deliver WHSE'] == '') {
																	$clr_unrack_eq = '#c1c1c1'; // grey, no dates availble
																
																} else if($row_proj['Plan Deliver WHSE'] < date("Y-m-d") && $row_proj['Actual Deliver WHSE'] == '' ) {
																	$clr_unrack_eq = 'red'; // red, Late if plan is less than today and actual is not empty
																			
																} else {
																	$clr_unrack_eq = '#00aaf5'; //grey
																}
																													
																?>
                                                                <tr>
                                                                  <td><?php echo $row_proj['Hw_Id']; ?></td>
                                                                  <td align="center" bgcolor="<?php echo $clr_inst_eqp?>" style="color:white">
																  	<?php if(is_null($row_proj['Actual Create Install EWP'])){
																					echo convtimex($row_proj['Plan Create Install EWP']); 
																			  } else if(is_null($row_proj['Actual Create Install EWP']) && is_null($row_proj['Plan Create Install EWP'])){
																					echo '---'	;		  
																			  } else {
																					echo convtimex($row_proj['Actual Create Install EWP']);
																			  }	  
																	?>
                                                                   </td>
                                                                   
                                                                  <td align="center" bgcolor="<?php echo $clr_rx_big_irn?>" style="color:white">
																  	<?php if(is_null($row_proj['Actual Receive Chassis'])){
																					echo convtimex($row_proj['Plan Receive Chassis']); 
																			  } else if(is_null($row_proj['Actual Receive Chassis']) && is_null($row_proj['Plan Receive Chassis'])){
																					echo '---'	;
																			  } else {
																					echo convtimex($row_proj['Actual Receive Chassis']); 	  
																			  }	
																	?>
                                                                  </td>
                                                                  
                                                                  <td align="center" bgcolor="<?php echo $clr_rx_anc_eq?>" style="color:white">
																  	<?php if(is_null($row_proj['Actual RX Ancillary EQ'])){
																					echo convtimex($row_proj['Plan RX Ancillary EQ']);  
																			  } else if(is_null($row_proj['Actual RX Ancillary EQ']) && is_null($row_proj['Plan RX Ancillary EQ'])){
																					echo '---'	;		  
																			  } else {
																					echo convtimex($row_proj['Actual RX Ancillary EQ']);	  
																			  }	
																	?>
																  </td>
																  <td align="center" bgcolor="<?php echo $clr_sec_pwr?>" style="color:white">
																  <?php if(is_null($row_proj['Actual Verify Secondary Power'])){
																					echo convtimex($row_proj['Plan Verify Secondary Power']); 
																			  } else if(is_null($row_proj['Actual Verify Secondary Power']) && is_null($row_proj['Plan Verify Secondary Power'])){
																					echo '---'	;
																			  } else {
																					echo convtimex($row_proj['Actual Verify Secondary Power']) ;
																			  }		  
																	?>
																	</td>

                                                                  <td align="center" bgcolor="<?php echo $clr_rev_tst?>" style="color:white">
																  <?php if(is_null($row_proj['Actual Review test'])){
																					echo convtimex($row_proj['Plan Review test']); 
																			  } else if(is_null($row_proj['Actual Review test']) && is_null($row_proj['Plan Review test'])){
																					echo '---'	;
																			  } else {
																					echo convtimex($row_proj['Actual Review test']) ;
																			  }		  
																	?>
																	</td>

                                                                  <td align="center" bgcolor="<?php echo $clr_ecr_tic?>" style="color:white">
																  	<?php if(is_null($row_proj['Actual Migration Complete'])){
																					echo convtimex($row_proj['Plan Migration Complete']);
																			  } else if(is_null($row_proj['Plan Migration Complete']) && is_null($row_proj['Plan Migration Complete'])){
																					echo '---';
																			  } else {
																											echo convtimex($row_proj['Actual Migration Complete']);	 
													  }
																	?>
                                                                  </td>
                                                                  
                                                                  <td align="center" bgcolor="<?php echo $clr_prf_mig?>" style="color:white">
																  	<?php if(is_null($row_proj['Actual Power Down'])){
																					echo convtimex($row_proj['Plan Power Down']); 
																			  } else if(is_null($row_proj['Actual Power Down']) && is_null($row_proj['Plan Power Down'])){
																					echo '---'	;
																			  }	else {
																					echo convtimex($row_proj['Actual Power Down']);		  
																			  }
																	?>
																  </td>
                                                                  
                                                                  <td align="center" bgcolor="<?php echo $clr_unrack_eq?>" style="color:white">
																  	<?php if(empty($row_proj['Actual Deliver WHSE'])){
																					echo convtimex($row_proj['Plan Deliver WHSE']); 
																			  } elseif(is_null($row_proj['Actual Deliver WHSE']) && is_null($row_proj['Plan Deliver WHSE'])){
																				  	echo '---'	;
																			  } else {
																				  	echo convtimex($row_proj['Actual Deliver WHSE']);	
																			  }		  
																	?>
                                                                  </td>
                                                                  
                                                                </tr>
                                                                <?php } ?>
                                                              </tbody>
                                                        </table>                                                         
 <?php } ?>
                                                                                         
</body>
</html>