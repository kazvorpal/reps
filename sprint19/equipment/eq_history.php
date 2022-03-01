<?php include ("../includes/functions.php");?>
<?php include ("../db_conf.php");?>
<?php include ("../data/emo_data.php");?>
<?php 
	// Equipment History
	//$oracleCD = $_GET['ocd']; //oracle code from 
	
	$sql_eqh = "SELECT *
				FROM(
				SELECT OrderType, Order_Num, Line_Num
								, Manufacturer_Nm, [Description], Deducted_RQT_Dt
								, COX_PID, MFG_PART_Num, Ordered_QTY, Ordered_Amt, Shipment_Status
								, Case When Receipt_Num Is Null then 'No' Else 'Yes' End As Received
								, Transaction_Date
								, Project_Num
								, Qty_Received
								, Preparer_Nm
								, Case When WWT_AsOf_Dt Is Not Null then 'Yes' Else 'No' End As WWTOrder
								FROM DBO.fn_GetOrderHistory ()
								) AS C
				INNER JOIN [COX_QA].[EPS].[ProjectStage] on Project_Num  like + '%' + [COX_QA].[EPS].[ProjectStage].[OracleProject_Cd] + '%'
				--WHERE Project_Num LIKE '42005200000RAMRCHU%'
				WHERE [COX_QA].[EPS].[ProjectStage].[Region] = 'CALIFORNIA' AND [COX_QA].[EPS].[ProjectStage].[PRGM] = 'r phy'";
	$stmt_eqh = sqlsrv_query( $conn_COX_QA, $sql_eqh );
	//$row_eqh = sqlsrv_fetch_array( $stmt_eqh, SQLSRV_FETCH_ASSOC) 
	
	//echo htmlspecialchars($row_eqh['column_name'])
?>
<!doctype html>
<html><head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Untitled Document</title>
 <link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css"> 
<link href="../css/bootstrap-3.3.4.css" rel="stylesheet" type="text/css">
 <script src="../bootstrap/js/jquery-1.11.2.min.js"></script> 
<script src="../js/bootstrap-3.3.4.js" type="text/javascript"></script>
<script src="../colorbox-master/jquery.colorbox.js"></script>
<style type="text/css">
    .popover{
        max-width:600px;
    }
</style>
<script>
function goBack() {
  window.history.back();
}
</script>
</head>
<body>
<h3 align="center">ORDER HISTORY</h3>
<div align="center">Oracle/Order Number: <?php // echo htmlspecialchars($_GET['ocd'])?>
</div>
<div align="right" style="margin-bottom:10px">
	<a href="export.php?sqlex=<?php echo urlencode($sql_eqh) ?>" class="btn btn-default">Export Results</a>
    <a href="#" data-toggle="popover" title="IO/PO Status Definitions" data-placement="left"  data-content=""><span class="btn btn-primary">Shipment Status Definitions</span></a>
    	<div id="popover_content" style="display: block; padding-bottom:6">
            
<table width="100%" border="0" class="table-bordered table-condensed table-striped" style="font-size:11px">
  <tbody>
    <tr style="font-size:10px; background-color:#00aaf5; color:#FFFFFF">
      <th width="33%" scope="col">IO Status</th>
      <th scope="col">IO Definition</th>
    </tr>
    <tr>
      <td>Not Applicable</td>
      <td>The  Order was not valid</td>
    </tr>
    <tr>
      <td>Ready to Release</td>
      <td>Order is Valid and is ready to pass on to Warehouse</td>
    </tr>
    <tr>
      <td>Cancelled</td>
      <td>Order has been cancelled</td>
    </tr>
    <tr>
      <td>Staged/Pick Confirmed</td>
      <td>Material has been picked by AFC and ready for pickup by user </td>
    </tr>
    <tr>
      <td>Interfaced</td>
      <td>Order has been filled by AFC. Usually referred to as "Ship Confirmed" </td>
    </tr>
    <tr>
      <td>Backorder</td>
      <td>Insufficient quantity on hand to fulfill the entire order </td>
    </tr>
    <tr>
      <td>Release to Warehouse</td>
      <td>Order is at Warehouse and is being filled</td>
    </tr>
  </tbody>
</table>
<br>

<table width="100%" border="0" class="table-bordered table-condensed table-striped" style="font-size:11px">
  <tbody>
    <tr style="font-size:10px; background-color:#00aaf5; color:#FFFFFF">
      <th width="33%" scope="col">PO Status</th>
      <th scope="col">PO Definition</th>
    </tr>
    <tr>
      <td>Open</td>
      <td>Line has quantity open to be received. There can be some receipts on this completed.</td>
    </tr>
    <tr>
      <td>Closed for Receiving </td>
      <td>All ordered quantity received, can be partially invoiced. If invoiced quantity is less than quantity received, will triggered for payment to supplier</td>
    </tr>
    <tr>
      <td>Closed for Invoicing </td>
      <td>All ordered quantity invoiced, can be partially received. If invoiced quantity is same as quantity received, will triggered for payment to supplier</td>
    </tr>
    <tr>
      <td>Cancelled</td>
      <td>Line cancelled</td>
    </tr>
    <tr>
      <td>Closed</td>
      <td>All ordered quantity received and invoiced, will trigger for payment to supplier. Can also been manually closed</td>
    </tr>
  </tbody>
</table>

          
          <table width="100%" border="0">
          <tbody>
            <tr>
              <td>&nbsp;</td>
            </tr>
          </tbody>
        </table>
		</div><!--end popover-->
    </div>
<table width="100%" class="table-bordered table-hover table-striped table-responsive">
  <thead>
    <tr style="font-size:10px; background-color:#00aaf5; color:#FFFFFF" >
      <th valign="top" style="padding:3px"><div align="center">Order Type</div></th>
      <th valign="top" style="padding:3px"><div align="center">Order #</div></th>
      <th valign="top" style="padding:3px"><div align="center">Oracle Code</div></th>
      <th valign="top" style="padding:3px"><div align="center">Line #</div></th>
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
    <tr style="font-size:10px; padding:3px">
      <td align="center" style="padding:3px"><?php echo htmlspecialchars($row_eqh['OrderType'])?></td>
      <td align="center" style="padding:3px">
    
	   <?php echo htmlspecialchars($row_eqh['Order_Num']);?>
		
      </td>
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
              } else if(date('Y-m-d') > date_format($row_eqh['Deducted_RQT_Dt'],'Y-m-d'))  {
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
<div align="left">
<?php //if($_SERVER['HTTP_REFERER'] =='http://catl0dwas10222.corp.cox.com/reps_dev/eq_history_search.php') { ?>

<?php // } else {?>
 <?php //if($_SERVER['HTTP_REFERER'] != 'http://catl0dwas10222.corp.cox.com/reps_dev/esp-status-details-index.php') {?>
	<!--<button onclick="goBack()" class="btn btn-primary" style="margin-top:10px;">&lt;&lt; Back</button>-->
 <?php //} }?>
 </div>
</body>
<script>
  $(function(){
    $('[data-toggle="popover"]').popover({ 
      html : true, 
      content: function() {
        return $('#popover_content').html();
      }
    });
  });
</script>
</html>