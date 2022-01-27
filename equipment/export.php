<?php include ("../includes/functions.php");?>
<?php include ("../db_conf.php");?>
<?php include ("../data/emo_data.php");?>
<?php include ("../sql/order_history_search_export.php");?>
<?php 
 header("Content-type: application/vnd.ms-excel; name='excel'");
 header("Content-Disposition: attachment; filename=eqExport.xls");
 header("Pragma: no-cache");
 header("Expires: 0");
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Order History</title>
</head>

<body>
<table width="100%" class="table-bordered table-hover table-striped table-responsive">
  <thead>
    <tr style="font-size:12px; background-color:#00aaf5; color:#FFFFFF" >
      <th valign="top" style="padding:3px"><div align="center">Order Type</div></th>
      <th valign="top" style="padding:3px"><div align="center">PPM#</div></th>
      <th valign="top" style="padding:3px"><div align="center">Order#</div></th>
      <th valign="top" style="padding:3px"><div align="center">Oracle Code</div></th>
      <th valign="top" style="padding:3px"><div align="center">Line#</div></th>
      <th valign="top" style="padding:3px">Manufacturer</th>
      <th valign="top" style="padding:3px">Description</th>
      <th valign="top" style="padding:3px">Cox Part #</th>
      <th valign="top" style="padding:3px">MFR Part #</th>
      <th valign="top" style="padding:3px"><div align="right">Amount</div></th>
      <th valign="top" style="padding:3px"><div align="center">Shipment Status</div></th>
      <th valign="top" style="padding:3px"><div align="center">Received</div></th>
      <th valign="top" style="padding:3px"><div align="center">Need By Date</div></th>
      <th valign="top" style="padding:3px"><div align="center">Transaction Date</div></th>
      <th valign="top" style="padding:3px"><div align="center">Qty Ordered</div></th>
      <th valign="top" style="padding:3px"><div align="center">Qty Received</div></th>
      <th valign="top" style="padding:3px"><div align="center">WWT Order</div></th>
      <th valign="top" style="padding:3px"><div align="center">Preparer</div></th>
    </tr>
   </thead>
<?php while( $row_eqh = sqlsrv_fetch_array( $stmt_eqh, SQLSRV_FETCH_ASSOC)){?>
    <tbody>
    <tr style="font-size:12px; padding:3px">
      <td align="center" style="padding:3px"><?php echo htmlspecialchars($row_eqh['OrderType'])?></td>
      <td align="center" style="padding:3px"><?php echo htmlspecialchars($row_eqh['PPM_Num'])?></td>
      <td align="center" style="padding:3px"><?php echo htmlspecialchars($row_eqh['Order_Num'])?></td>
      <td align="center" style="padding:3px"><?php echo htmlspecialchars($row_eqh['Project_Num'])?></td>
      <td align="center" style="padding:3px"><?php echo floor($row_eqh['Line_Num'])?></td>
      <td style="padding:3px"><?php echo htmlspecialchars($row_eqh['Manufacturer_Nm'])?></td>
      <td width="500" style="padding:3px"><?php echo htmlspecialchars($row_eqh['Description'])?></td>
      <td style="padding:3px"><?php echo htmlspecialchars($row_eqh['COX_PID'])?></td>
      <td style="padding:3px"><?php echo htmlspecialchars($row_eqh['MFG_PART_Num'])?></td>
      <td align="right" style="padding:3px">$<?php echo number_format($row_eqh['Ordered_Amt'], 2, '.', '')?></td>
      <td align="center" style="padding:3px"><?php echo htmlspecialchars($row_eqh['Shipment_Status'])?></td>
		    <?php 
              if($row_eqh['Received'] == 'Yes') { 
                  $rx_receive = '#00d257'; 
			  } else if($row_eqh['Shipment_Status'] == 'BACKORDERED')  {
				  $rx_receive = '#fcd12a'; 
              } else if(date('Y-m-d') > date_format($row_eqh['Deducted_RQT_Dt'], 'Y-m-d'))  {
				  $rx_receive = 'red'; 
			  } else {
                  $rx_receive = 'grey';
                  } 
              ?>
      <td align="center" style="padding:3px; color:#FFFFFF" bgcolor="<?php echo htmlspecialchars($rx_receive) ?>"><?php echo htmlspecialchars($row_eqh['Received'])?></td>
      <td align="center" style="padding:3px"><?php echo convtimex($row_eqh['Deducted_RQT_Dt'])?></td>
      <td align="center" style="padding:3px"><?php echo convtimex($row_eqh['Transaction_Date'])?></td>
      <td align="center" style="padding:3px"><?php echo htmlspecialchars(number_format($row_eqh['Ordered_QTY'],2))?></td>
      <td align="center" style="padding:3px"><?php echo htmlspecialchars (number_format($row_eqh['Qty_Received'],2))?></td>
      <td align="center" style="padding:3px"><?php echo htmlspecialchars($row_eqh['WWTOrder'])?></td>
      <td align="center" style="padding:3px"><?php echo htmlspecialchars($row_eqh['Preparer_Nm'])?></td>
   </tr>
<?php } ?>
  </tbody>
</table>
</body>
</html>