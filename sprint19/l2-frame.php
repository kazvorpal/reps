<?php include ("includes/functions.php");?>
<?php include ("db_conf.php");?>
<?php include ("data/emo_data.php");?>

<?php    
// Define proj_id
$prj_id = $_GET['uid'];

// Level 2 information by project id
//$row_l2['last_update']
$sql_proj = "SELECT * FROM [COX].[EPS].[ProjectStageTaskL2]  WHERE [PROJ_ID] = '$prj_id' ORDER BY [Hw_Id]";
$stmt_proj = sqlsrv_query( $conn_COXProd, $sql_proj );
//$row_proj = sqlsrv_fetch_array( $stmt_proj, SQLSRV_FETCH_ASSOC)
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>

<link href="css/bootstrap-3.3.4.css" rel="stylesheet" type="text/css">
</head>

<body>
<h4>DETAILED PHASE (Level 2)</h4>
 
 <?php if($stmt_proj == 'false') {?>
      <div align="center" class=" alert-danger">
        <p>No Data Available</p>
      </div>
 <?php } else { ?>                                                           
                                                            
<table width="100%" border="0" class="table-striped table-bordered table-hover" style="font-size:10px">
                                                              <tbody>
                                                                <tr>
                                                                  <td bgcolor="#ECEBEB"><strong>HARDWARE ID</strong></td>
                                                                  <td bgcolor="#ECEBEB"><strong>EQ TYPE</strong></td>
                                                                  <td align="center" bgcolor="#ECEBEB"><strong>EXEC PREP</strong></td>
                                                                  <td align="center" bgcolor="#ECEBEB"><strong>SITE PREP</strong></td>
                                                                  <td colspan="3" align="center" bgcolor="#ECEBEB"><strong>INSTALLATION STAGE</strong></td>
                                                                  <td colspan="2" align="center" bgcolor="#ECEBEB"><strong>MIGRATION STAGE</strong></td>
                                                                  <td align="center" bgcolor="#ECEBEB"><strong>DECOM STAGE</strong></td>
                                                                </tr>
                                                                <tr>
                                                                  <td>&nbsp;</td>
                                                                  <td>&nbsp;</td>
                                                                  <td width="110" align="center">RX Big Iron</td>
                                                                  <td width="110" align="center">Secondary Power</td>
                                                                  <td width="110" align="center">Install EWP</td>
                                                                  <td width="110" align="center">RX Ancillary EQ</td>
                                                                  <td width="110" align="center">Review, Test</td>
                                                                  <td width="110" align="center">ECR Ticket</td>
                                                                  <td width="110" align="center">Perfom Migration</td>
                                                                  <td width="110" align="center">Unrack EQ</td>
                                                                </tr>
                                                                <?php while ($row_proj = sqlsrv_fetch_array( $stmt_proj, SQLSRV_FETCH_ASSOC)) { ?>
                                                                <?php 
																// Cell Colors 
																// #00d257 green actual date
																// #00aaf5 blue future date
																// #c1c1c1 grey 
																// red red
																
																// RX Big Iron
																$clr_rx_big_irn = '#c1c1c1'; //grey
																
																if($row_proj['Actual RX Big Iron'] != '') {
																	$clr_rx_big_irn = '#00d257'; // green actual date
																
																} else if($row_proj['Plan RX Big Iron'] == '' && $row_proj['Actual RX Big Iron'] == '') {
																	$clr_rx_big_irn = '#c1c1c1'; // grey, no dates availble
																	
																} else if(strtotime($row_proj['Plan RX Big Iron']) < strtotime(date("Y-m-d")) && $row_proj['Actual RX Big Iron'] == '' ) {
																	$clr_rx_big_irn = 'red'; // red, Late if plan is less than today and actual is not empty
																			
																} else {
																	$clr_rx_big_irn = '#00aaf5'; //grey
																}
																
																// Secondary Power
																$clr_sec_pwr = '#c1c1c1';
																
																if($row_proj['Actual Secondary Power'] != '') {
																	$clr_sec_pwr = '#00d257'; // green actual date
																
																} else if($row_proj['Plan Secondary Power'] == '' && $row_proj['Actual Secondary Power'] == '') {
																	$clr_sec_pwr = '#c1c1c1'; // grey, no dates availble
																	
																} else if($row_proj['Plan Secondary Power'] < date("Y-m-d") && $row_proj['Actual Secondary Power'] == '' ) {
																	$clr_sec_pwr = 'red'; // red, Late if plan is less than today and actual is not empty
																			
																} else {
																	$clr_sec_pwr = '#00aaf5'; //grey
																}
																
																// Install EWP
																$clr_inst_eqp = '#c1c1c1'; // grey, no dates available
																
																if($row_proj['Actual Install EPQ'] != '') {
																	$clr_inst_eqp = '#00d257'; // green actual date
																
																} else if($row_proj['Plan Install EPQ'] == '' && $row_proj['Actual Install EPQ'] == '') {
																	$clr_inst_eqp = '#c1c1c1'; // grey, no dates availble
																	
																} else if($row_proj['Plan Install EPQ'] < date("Y-m-d") && $row_proj['Actual Install EPQ'] == '' ) {
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
																
																// Review Test
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
																
																// ECR Ticket
																$clr_ecr_tic = '#c1c1c1';
																
																if($row_proj['Actual ECR Ticket'] != '') {
																	$clr_ecr_tic = '#00d257'; // green actual date
																
																} else if($row_proj['Plan ECR Ticket'] == '' && $row_proj['Actual ECR Ticket'] == '') {
																	$clr_ecr_tic = '#c1c1c1'; // grey, no dates availble
																	
																//} else if(strtotime($row_proj['Plan ECR Ticket']) < strtotime(date("Y-m-d")) && $row_proj['Actual ECR Ticket'] == '' ) {
																} else if($row_proj['Plan ECR Ticket'] < date("Y-m-d") && $row_proj['Actual ECR Ticket'] == '' ) {
																	$clr_ecr_tic = 'red'; // red, Late if plan is less than today and actual is not empty
																			
																} else {
																	$clr_ecr_tic = '#00aaf5'; //grey
																}
																
																//  Perform Migration
																$clr_prf_mig = '#c1c1c1';
																
																if($row_proj['Actual Perform Migration'] != '') {
																	$clr_prf_mig = '#00d257'; // green actual date
																
																} else if($row_proj['Plan Perform Migration'] == '' && $row_proj['Actual Perform Migration'] == '') {
																	$clr_prf_mig = '#c1c1c1'; // grey, no dates availble
																	
																} else if($row_proj['Plan Perform Migration'] < date("Y-m-d") && $row_proj['Actual Perform Migration'] == '' ) {
																	$clr_prf_mig = 'red'; // red, Late if plan is less than today and actual is not empty
																			
																} else {
																	$clr_prf_mig = '#00aaf5'; //grey
																}
																
																// Unrack EQ
																$clr_unrack_eq = '#c1c1c1';
																
																if($row_proj['Actual Unrack EQ'] != '') {
																	$clr_unrack_eq = '#00d257'; // green,  actual date
																												
																} else if($row_proj['Plan Unrack EQ'] == '' && $row_proj['Actual Unrack EQ'] == '') {
																	$clr_unrack_eq = '#c1c1c1'; // grey, no dates availble
																
																} else if($row_proj['Plan Unrack EQ'] < date("Y-m-d") && $row_proj['Actual Unrack EQ'] == '' ) {
																	$clr_unrack_eq = 'red'; // red, Late if plan is less than today and actual is not empty
																			
																} else {
																	$clr_unrack_eq = '#00aaf5'; //grey
																}
																													
																?>
                                                                <tr>
                                                                  <td><?php echo $row_proj['Hw_Id']; ?></td>
                                                                  <td><?php echo $row_proj['Equip_1_type']; ?></td>
                                                                  <td align="center" bgcolor="<?php echo $clr_rx_big_irn?>" style="color:white">
																  	<?php 
																		
																		if(is_null($row_proj['Actual RX Big Iron'])){
																					echo convtimex($row_proj['Plan RX Big Iron']); 
																			  } else if(is_null($row_proj['Actual RX Big Iron']) && is_null($row_proj['Plan RX Big Iron'])){
																					echo '---';		  
																			  } else {
																				  	echo convtimex($row_proj['Actual RX Big Iron']);
																			  }
																	?>
                                                                   </td>
                                                                  <td align="center" bgcolor="<?php echo $clr_sec_pwr?>" style="color:white">
																  	<?php if(is_null($row_proj['Actual Secondary Power'])){
																					echo convtimex($row_proj['Plan Secondary Power']); 
																			  } else if(is_null($row_proj['Actual Secondary Power']) && is_null($row_proj['Plan Secondary Power'])){
																					echo '---'	;		  
																			  } else {
																					echo convtimex($row_proj['Actual Secondary Power']);
																			  }	  
																	?>
                                                                   </td>
                                                                   
                                                                  <td align="center" bgcolor="<?php echo $clr_inst_eqp?>" style="color:white">
																  	<?php if(is_null($row_proj['Actual Install EPQ'])){
																					echo convtimex($row_proj['Plan Install EPQ']); 
																			  } else if(is_null($row_proj['Actual Install EPQ']) && is_null($row_proj['Plan Install EPQ'])){
																					echo '---'	;
																			  } else {
																					echo convtimex($row_proj['Actual Install EPQ']); 	  
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
																  	<?php if(is_null($row_proj['Actual ECR Ticket'])){
																					echo convtimex($row_proj['Plan ECR Ticket']);
																			  } else if(is_null($row_proj['Actual ECR Ticket']) && is_null($row_proj['Plan ECR Ticket'])){
																					echo '---';
																			  } else {
																					echo convtimex($row_proj['Actual ECR Ticket']);	 
																			  }
																	?>
                                                                  </td>
                                                                  
                                                                  <td align="center" bgcolor="<?php echo $clr_prf_mig?>" style="color:white">
																  	<?php if(is_null($row_proj['Actual Perform Migration'])){
																					echo convtimex($row_proj['Plan Perform Migration']); 
																			  } else if(is_null($row_proj['Actual Perform Migration']) && is_null($row_proj['Plan Perform Migration'])){
																					echo '---'	;
																			  }	else {
																					echo convtimex($row_proj['Actual Perform Migration']);		  
																			  }
																	?>
																  </td>
                                                                  
                                                                  <td align="center" bgcolor="<?php echo $clr_unrack_eq?>" style="color:white">
																  	<?php if(empty($row_proj['Actual Unrack EQ'])){
																					echo convtimex($row_proj['Plan Unrack EQ']); 
																			  } elseif(is_null($row_proj['Actual Unrack EQ']) && is_null($row_proj['Plan Unrack EQ'])){
																				  	echo '---'	;
																			  } else {
																				  	echo convtimex($row_proj['Actual Unrack EQ']);	
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