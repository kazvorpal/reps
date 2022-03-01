<?php include ("includes/functions.php");?>
<?php include ("db_conf.php");?>
<?php include ("data/emo_data.php");?>
<?php 

// DECLARE PARAMETERS
$fislcalYear = $_GET['fiscalYear'];
$status = $_GET['status'];
$owner = $_GET['owner'];
$program = $_GET['prog'];
$subprogram = $_GET['subprogram'];
$region  = $_GET['region'];
$market = $_GET['market'];
$facility = $_GET['facility'];

// EXECUTE SQL
$bmysql = "Select * From PORMgt.fn_GetListOfPlans ($fislcalYear,'$status','$owner', '$program', '$subprogram', '$region', '$market', '$facility')";
$sql_por = "$bmysql"; 
//$stmt_por = sqlsrv_query( $conn_COXProd, $sql_por );
$stmt_por = sqlsrv_query( $conn_COX_QA, $sql_por );

//CREATE EXCEL SHEET
 header("Content-type: application/vnd.ms-excel; name='excel'");
 header("Content-Disposition: attachment; filename=export_DPR_plan.xls");
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

<table border="1" cellpadding="5" cellspacing="0" class="table-striped table-bordered table-hover ">
  <thead>
    <tr align="center" valign="middle" style="color:#FFFFFF; font-size:11px; padding:2px; background-color; #000000">
	  <th bgcolor="#337AB7"><div align="center">PROGRAM</div></th>
      <th bgcolor="#337AB7"><div align="center">SUBPROGRAM</div></th>
      <th bgcolor="#337AB7"><div align="center">REGION</div></th>
      <th bgcolor="#337AB7"><div align="center">MARKET</div></th>
      <th bgcolor="#337AB7"><div align="center">FACILITY</div></th>
      <th bgcolor="#337AB7"><div align="center">OWNER</div></th>
      <th bgcolor="#337AB7"><div align="center">EPS PROJECT NAME</div></th>
      <th bgcolor="#337AB7"><div align="center">POR EQUIPMENT ID</div></th>
      <th bgcolor="#337AB7"><div align="center">MS PROJECT START DATE</div></th>
      <th bgcolor="#337AB7"><div align="center">POR NEED BY DATE</div></th>
      <th bgcolor="#337AB7"><div align="center">MS PROJECT INSTALLATION PHASE FINISH DATE</div></th>
      <th bgcolor="#337AB7"><div align="center">POR ACTIVATION DATE</div></th>
      <th bgcolor="#337AB7">MS PROJECT MIGRATION PHASE FINISH DATE</th>
      <th bgcolor="#337AB7">POR MIGRATION DATE</th>
      <th bgcolor="#337AB7">MS PROJECT FINISH DATE</th>
     </tr>
    </thead>
    <tbody>
    <?php while($row_program_n = sqlsrv_fetch_array( $stmt_por, SQLSRV_FETCH_ASSOC)) { ?>
    
    <tr align="left" valign="middle" style="font-size:11px">
	  <td style="padding:2px"><?php echo htmlspecialchars($row_program_n['Program_Nm']);?></td>
      <td style="padding:2px"><?php echo htmlspecialchars($row_program_n['SubProgram_Nm']);?></td>
      <td style="padding:2px"><?php echo htmlspecialchars($row_program_n['Region_Abb']);?></td>
      <td style="padding:2px"><?php echo htmlspecialchars($row_program_n['Market_Abb']);?></td>
      <td style="padding:2px"><?php echo htmlspecialchars($row_program_n['Location_Abb']);?></td>
      <td style="padding:2px"><?php echo htmlspecialchars($row_program_n['EPS_Owner']);?></td>
      <td style="padding:2px"><?php echo htmlspecialchars($row_program_n['EPSProject_Nm']);?></td>
      <td style="padding:2px"><?php echo htmlspecialchars($row_program_n['EquipPlan_Id']);?></td>
      <td style="padding:2px"><?php echo convtimex($row_program_n['MSP_Start_Dt']);?></td>
      <td style="padding:2px"><?php echo convtimex($row_program_n['POR_NeedBy_Dt']);?></td>
      <td style="padding:2px"><?php echo convtimex($row_program_n['MSP_Install_Finish_Dt']);?></td>
      <td style="padding:2px"><?php echo convtimex($row_program_n['POR_Activation_Dt']);?></td>
      <td style="padding:2px"><?php echo convtimex($row_program_n['MSP_Migration_Finish_Dt']);?></td>
      <td style="padding:2px"><?php echo convtimex($row_program_n['POR_Migration_Dt']);?></td>
      <td style="padding:2px"><?php echo convtimex($row_program_n['MSP_Project_Finish_Dt']);?></td>
    </tr>

    <?php } ?>
  </tbody>
</table>
</body>
</html>