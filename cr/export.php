<?php include ("../includes/functions.php");?>
<?php include ("../db_conf.php");?>
<?php include ("../data/emo_data.php");?>
<?php include ("../sql/update-time.php");?>
<?php 
	$year = $_GET['year'];
	$fundingKey = $_GET['fk'];
	
	$urlSQL = $_GET['sql'];
	
	$sql_crs = "$urlSQL";
	$stmt_crs = sqlsrv_query( $conn_COX_QA, $sql_crs );
	$row_count_crs = sqlsrv_num_rows( $stmt_crs );
	//$row_crs = sqlsrv_fetch_array( $stmt_crs, SQLSRV_FETCH_ASSOC);
	//echo $row_crs['column_name']
	
	 header("Content-type: application/vnd.ms-excel; name='excel'");
	 header("Content-Disposition: attachment; filename=CROverView_" . date('Y-m-d') . ".xls");
	 header("Pragma: no-cache");
	 header("Expires: 0");

?>
<?php include ("trueup_function.php");?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CR Manager</title>

</head>
<body onload="myFunction()" style="margin:0;">
<!-- Body Start -->
<div align="center">
<table width="100%" style="border-color:#D4D4D4; border-width:1px">
  <thead>
    <tr style="font-size:10px; background-color:#00aaf5; color:#FFFFFF" >
      <th class="sticky" width="100" valign="top" style="padding:3px;"><div align="center">CR Id</div></th>
      <th class="sticky" valign="top" style="padding:3px"><div align="left">CR Status</div></th>
      <th class="sticky" width="100" valign="top" style="padding:3px"><div align="center">Status Date</div></th>
      <th width="100" valign="top" class="sticky" style="padding:3px"><div align="center">CR Type</div></th>
      <th class="sticky" valign="top" style="padding:3px">CR Impact</th>
      <th class="sticky" valign="top" style="padding:3px">CR Name</th>
      <th width="150" valign="top" class="sticky" style="padding:3px">Program</th>
      <th class="sticky" width="100" valign="top" style="padding:3px"><div align="center">CR Creation</div></th>
      <th class="sticky" width="150" valign="top" style="padding:3px"><div align="center">CR PM</div></th>
      <th class="sticky" valign="top" style="padding:3px"><div align="center">$ Change</div></th>
      <th class="sticky" valign="top" style="padding:3px"><div align="center">Plan Change</div></th>
      <th class="sticky" valign="top" style="padding:3px"><div align="left">CR Description</div></th>
      <th width="75" valign="top" class="sticky" style="padding:3px"><div align="center">True Up Capex</div></th>
      <th width="75" valign="top" class="sticky" style="padding:3px"><div align="center">True Up Opex</div></th>
      <th width="75" class="sticky" valign="top" style="padding:3px"><div align="center">Capex OS</div></th>
      <th width="75" class="sticky" valign="top" style="padding:3px"><div align="center">Opex OS</div></th>
    </tr>
   </thead>
   <tbody>
 <?php while( $row_crs = sqlsrv_fetch_array( $stmt_crs, SQLSRV_FETCH_ASSOC)){?>
    <tr style="font-size:10px; padding:3px">
      <td align="center" style="padding:3px"><?php echo htmlspecialchars($row_crs['CR_Id'])?></td>
      <td align="left" style="padding:3px"><?php echo htmlspecialchars($row_crs['CR_Status_Abb'])?></td>
      <td align="center" style="padding:3px"><?php echo htmlspecialchars($row_crs['EffectiveDate'])?></td>
      <td align="left" style="padding:3px"><?php echo htmlspecialchars($row_crs['CR_Type_Des'])?></td>
      <td style="padding:3px"><?php echo htmlspecialchars($row_crs['CR_Impact_Cd'])?></td>
      <td width="500" style="padding:3px"><?php echo htmlspecialchars($row_crs['CR_ShortDesc'])?></td>
      <td style="padding:3px"><?php echo htmlspecialchars($row_crs['Program_Nm'])?></td>
      <td align="center" style="padding:3px"><?php echo htmlspecialchars($row_crs['Creation_Date'])?></td>
      <td align="left" style="padding:3px"><?php echo htmlspecialchars($row_crs['CR_PM'])?></td>
      <td align="center" style="padding:3px"><?php if($row_crs['Capex_Amt'] <> 0 || $row_crs['Opex_Amt'] <> 0 ) { echo 'Yes'; } else { echo 'No';}?></td>
      <td align="center" style="padding:3px"><?php if($row_crs['PlanChg'] <> 0) { echo 'Yes'; } else { echo 'No' ;}?></td>
      <td align="left" style="padding:3px"><?php echo htmlspecialchars($row_crs['CR_Description'])?></td>
      <td align="right" style="padding:3px">
		  <?php
		  //echo '$' . number_format($row_capex['capex'],2)
		  echo truCPX($row_crs['CR_Key'], $year, $fundingKey );
          ?>
      </td>
      <td align="right" style="padding:3px">
		  <?php 
		  //echo '$' . number_format($row_opex['capex'],2)
		  echo truOPX($row_crs['CR_Key'], $year, $fundingKey );
          ?>
      </td>
      <td align="right" style="padding:3px"><?php echo htmlspecialchars(number_format($row_crs['Capex_OS_Amt'],2))?></td>
      <td align="right" style="padding:3px"><?php echo htmlspecialchars(number_format($row_crs['Opex_OS_Amt'],2))?></td>
   </tr>
<?php } ?>
  </tbody>
</table>
    </div>
</div>
<!-- Body End -->
</div> 

</body>
</html>