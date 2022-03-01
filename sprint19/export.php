<?php include ("includes/functions.php");?>
<?php include ("db_conf.php");?>
<?php include ("data/emo_data.php");?>
<?php 
// workflow_stage
$bmysql = $_GET['sql'];

$sql_por = "$bmysql"; 
//echo $sql_por;
//exit;
$stmt_por = sqlsrv_query( $conn_COXProd, $sql_por );

//mysql_select_db($database_data, $data);
//$query_program_n = "$bmysql";
//$program_n = mysql_query($query_program_n, $data) or die(mysql_error());
//$row_program_n = mysql_fetch_assoc($program_n);
//$totalRows_program_n = mysql_num_rows($program_n);


 header("Content-type: application/vnd.ms-excel; name='excel'");
 header("Content-Disposition: attachment; filename=export_EPS_Status.xls");
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
      <td bgcolor="#ECECEC">PROGRAM</td>
      <td bgcolor="#ECECEC">SUBPROGRAM</td>
      <td bgcolor="#ECECEC">PROJECT NAME</td>
      <td bgcolor="#ECECEC">OWNER</td>
      <td bgcolor="#ECECEC">FISCAL YR</td>
      <td bgcolor="#ECECEC">REGION</td>
      <td bgcolor="#ECECEC">MARKET</td>
      <td bgcolor="#ECECEC">FACILITY</td>
      <td bgcolor="#ECECEC">START DATE</td>
      <td bgcolor="#ECECEC">ORACLE CODE</td>
      <td bgcolor="#ECECEC">ORACLE START</td>
      <td bgcolor="#ECECEC">ORACLE END</td>
      <td bgcolor="#ECECEC">STAGE</td>
      <td bgcolor="#ECECEC">WATTS MO</td>
      <td bgcolor="#ECECEC">SCOPE DESC</td>
      <td bgcolor="#ECECEC">PPM#</td>
      <td bgcolor="#ECECEC">PROJECT TYPE</td>
      <td bgcolor="#ECECEC">START DATE</td>
      <td bgcolor="#ECECEC">FINISH DATE</td>
      <td bgcolor="#ECECEC">COMMIT DATE</td>
      <td bgcolor="#ECECEC">UID</td>
    </tr>
    <?php while( $row_program_n = sqlsrv_fetch_array( $stmt_por, SQLSRV_FETCH_ASSOC)) {?>
    <tr>
      <td><?php echo $row_program_n['PRGM'];?></td>
      <td><?php echo $row_program_n['Sub_Prg'];?></td>
      <td><?php echo $row_program_n['PROJ_NM'];?></td>
      <td><?php echo $row_program_n['PROJ_OWNR_NM'];?></td>
      <td><?php echo $row_program_n['FISCL_PLAN_YR'];?></td>
      <td><?php echo $row_program_n['Region'];?></td>
      <td><?php echo $row_program_n['Market'];?></td>
      <td><?php echo $row_program_n['Facility'];?></td>
      <td><?php echo convtimex($row_program_n['Plan_Start_Dt']);?></td>
      <td><?php echo str_replace('', '', str_replace(";", "</br>", $row_program_n['OracleProject_Cd'])) ;?></td>
      <td style="padding:2px"><?php if(is_null($row_program_n['OracleProjectStart_Dt'])) {
		  										echo '';
	  											} else {
		  										echo date_format($row_program_n['OracleProjectStart_Dt'], 'Y-m-d');
												}
											?>
      </td>
      <td style="padding:2px"><?php if(is_null($row_program_n['OracleProjectEnd_Dt'])) {
		  										echo '';
	  											} else {
		  										echo date_format($row_program_n['OracleProjectEnd_Dt'], 'Y-m-d');
												}
											?>
      </td>
      <td><?php echo $row_program_n['PHASE_NAME'];?></td>
      <td><?php echo $row_program_n['WATTS_MO'];?></td>
      <td><?php echo $row_program_n['Sub_Prg'];?></td>
      <td><?php echo $row_program_n['PPM_PROJ'];?></td>
      <td><?php echo $row_program_n['ENTRPRS_PROJ_TYPE_NM'];?></td>
      <td><?php echo convtimex($row_program_n['Plan_Start_Dt'], 'Y-m-d');?></td>
      <td><?php echo convtimex($row_program_n['Plan_Finish_Dt'], 'Y-m-d');?></td>
      <td><?php echo convtimex($row_program_n['COMIT_DT'], 'Y-m-d');?></td>
      <td><?php // $uid_x = substr($row_program_n['uid'],1,-1);?>
          <?php $uid_x = $row_program_n['PROJ_ID'];?>
          <?php echo $uid_x?>
      </td>
      </tr>
    <?php } ?>
  </tbody>
</table>





</body>
</html>