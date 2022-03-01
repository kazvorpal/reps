<?php include ("../includes/functions.php");?>
<?php include ("../db_conf.php");?>
<?php include ("../data/emo_data.php");?>
<?php include ("../sql/order_history_search_bo.php");?>
<?php include ("../sql/update-time.php");?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Order History</title>
<link rel="shortcut icon" href="../favicon.ico"/>
<?php include ("../includes/load.php");?>
<link href="../jQueryAssets/jquery.ui.core.min.css" rel="stylesheet" type="text/css">
<link href="../jQueryAssets/jquery.ui.theme.min.css" rel="stylesheet" type="text/css">
<link href="../jQueryAssets/jquery.ui.button.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="../colorbox-master/example1/colorbox.css" />
<!-- Bootstrap -->
<!-- <link rel="stylesheet" href="css/bootstrap.css"> -->
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
<link href="../css/bootstrap-3.3.4.css" rel="stylesheet" type="text/css">
<script src="../bootstrap/js/jquery-1.11.2.min.js"></script> 
<!--<script src="js/bootstrap-3.3.4.js" type="text/javascript"></script>-->
<script src="../colorbox-master/jquery.colorbox.js"></script>
<script>
$(document).ready(function(){
				//Examples of how to assign the Colorbox event to elements
				$(".group1").colorbox({rel:'group1'});
				$(".group2").colorbox({rel:'group2', transition:"fade"});
				$(".group3").colorbox({rel:'group3', transition:"none", width:"75%", height:"75%"});
				$(".group4").colorbox({rel:'group4', slideshow:true});
				$(".ajax").colorbox();
				$(".youtube").colorbox({iframe:true, innerWidth:640, innerHeight:390});
				$(".vimeo").colorbox({iframe:true, innerWidth:500, innerHeight:409});
				$(".iframe").colorbox({iframe:true, width:"95%", height:"95%", scrolling:false});
				$(".dno").colorbox({iframe:true, width:"60%", height:"50%", scrolling:false});
				$(".mapframe").colorbox({iframe:true, width:"90%", height:"75%", scrolling:true});
				$(".ocdframe").colorbox({iframe:true, width:"97%", height:"90%", scrolling:true});
				$(".miframe").colorbox({iframe:true, width:"1000", height:"650", scrolling:false});
				$(".inline").colorbox({inline:true, width:"50%"});
				$(".callbacks").colorbox({
					onOpen:function(){ alert('onOpen: colorbox is about to open'); },
					onLoad:function(){ alert('onLoad: colorbox has started to load the targeted content'); },
					onComplete:function(){ alert('onComplete: colorbox has displayed the loaded content'); },
					onCleanup:function(){ alert('onCleanup: colorbox has begun the close process'); },
					onClosed:function(){ alert('onClosed: colorbox has completely closed'); }
				});

				$('.non-retina').colorbox({rel:'group5', transition:'none'})
				$('.retina').colorbox({rel:'group5', transition:'none', retinaImage:true, retinaUrl:true});
				
				//Example of preserving a JavaScript event for inline calls.
				$("#click").click(function(){ 
					$('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("Open this window again and this message will still be here.");
					return false;
				});
			});
function MM_setTextOfTextfield(objId,x,newText) { //v9.0
  with (document){ if (getElementById){
    var obj = getElementById(objId);} if (obj) obj.value = newText;
  }
}

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

</script>
<style type="text/css">
    .popover{
        max-width:800px;
    }
</style>
</head>

<body onload="myFunction()" style="margin:0;">
<!--loader-->
<div id="loader"></div>
<div style="display:block;" id="myDiv" class="animate-bottom"> <!--change block to none when not debugging-->
<!--menu-->
<?php include ("../includes/menu.php");?>
<!-- Body Start -->
<div class="container-fluid">
<h3>Backordered Equipment Search</h3>
	<div align="center">
<div class="row">
	<form method="post" enctype="multipart/form-data">
		<div class="col-lg-12">
        	<table border="0">
              <tbody>
                <tr>
                  <td width="231"><input name="ocd" type="search" required="required" id="ocd" class="form-control"></td>
                  <td width="80" align="right"><input name="Submit" type="submit" class="btn btn-default" id="Submit" formmethod="POST" value="Submit"></td>
                </tr>
                <tr>
                  <td colspan="2" align="center">Enter Oracle Code, Order Number or PPM Number</td>
                </tr>
              </tbody>
			</table>
        </div>
    </form>
    </div>
    </div>
    <div align="right" style="margin-bottom:10px;">
    <a href="export.php?sqlex=<?php echo urlencode($sql_eqh) ?>" class="btn btn-default">Export Results</a>
    <a href="#" data-toggle="popover" title="IO/PO Status Definitions" data-placement="left"  data-content=""><span class="btn btn-primary">Shipment Status Definitions</span></a>
    	<div id="popover_content" style="display: none; padding-bottom:6">
            
<table width="100%" cellpadding="0" cellspacing="0" class="table-bordered table-condensed table-striped" style="font-size:11px">
  <tr bgcolor="#00aaf5" style="background-color:#00aaf5; color:#FFFFFF">
    <td colspan="3" bgcolor="#00aaf5" dir="LTR"><strong>AFC SOURCED ORDERS</strong></td>
  </tr>
  <tr style="background-color:#00aaf5; color:#FFFFFF">
    <td bgcolor="#00aaf5" dir="LTR">Tracker Status</td>
    <td bgcolor="#00aaf5" dir="LTR">Oracle Status</td>
    <td bgcolor="#00aaf5" dir="LTR">Status Description</td>
  </tr>
  <col width="137">
  <col width="180">
  <col width="460">
  <tr>
    <td dir="LTR" width="137">PO    to Vendor Pending</td>
    <td dir="LTR" width="180">Backordered    / Ready to Release</td>
    <td dir="LTR" width="460">Material    not at Cox location, vendor purchase order pending</td>
  </tr>
  <tr>
    <td dir="LTR" width="137">PO to Vendor Created</td>
    <td dir="LTR" width="180">Backordered / Ready to Release</td>
    <td dir="LTR" width="460">Material not at Cox location, vendor purchase order created</td>
  </tr>
  <tr>
    <td dir="LTR" width="137">Material Available</td>
    <td dir="LTR" width="180">Backordered / Ready to Release</td>
    <td dir="LTR" width="460">Material available at Cox location</td>
  </tr>
  <tr>
    <td dir="LTR" width="137">Pick Release to AFC</td>
    <td dir="LTR" width="180">Release to Warehouse</td>
    <td dir="LTR" width="460">Order released to AFC for fulfillment</td>
  </tr>
  <tr>
    <td dir="LTR" width="137">Ready for Pickup</td>
    <td dir="LTR" width="180">Staged/Pick Confirmed</td>
    <td dir="LTR" width="460">Order picked at AFC and ready for pickup</td>
  </tr>
  <tr>
    <td dir="LTR" width="137">Completed</td>
    <td dir="LTR" width="180">Shipped</td>
    <td dir="LTR" width="460">Order interfaced and picked up or in transit to destination</td>
  </tr>
</table>
<br>
<table width="100%" cellpadding="0" cellspacing="0"  class="table-bordered table-condensed table-striped" style="font-size:11px">
  <col width="137">
  <col width="180">
  <col width="460">
  <tr bgcolor="#00aaf5" style="font-size:10px; background-color:#00aaf5; color:#FFFFFF">
    <td colspan="3" bgcolor="#00aaf5" dir="LTR"><strong>PDC SOURCED ORDERS</strong></td>
  </tr>
  <tr style="font-size:10px; background-color:#00aaf5; color:#FFFFFF">
    <td bgcolor="#00aaf5" dir="LTR">Tracker Status</td>
    <td bgcolor="#00aaf5" dir="LTR">Oracle Status</td>
    <td bgcolor="#00aaf5" dir="LTR">Status Description</td>
  </tr>
  <tr>
    <td dir="LTR" width="137">PO    to Vendor Pending</td>
    <td dir="LTR" width="180">Backordered    / Ready to Release</td>
    <td dir="LTR" width="460">Material    not at Cox location, vendor purchase order pending</td>
  </tr>
  <tr>
    <td dir="LTR" width="137">PO to Vendor Created</td>
    <td dir="LTR" width="180">Backordered / Ready to Release</td>
    <td dir="LTR" width="460">Material not at Cox location, vendor purchase order created</td>
  </tr>
  <tr>
    <td dir="LTR" width="137">Material Available</td>
    <td dir="LTR" width="180">Backordered / Ready to Release</td>
    <td dir="LTR" width="460">Material available at Cox location</td>
  </tr>
  <tr>
    <td dir="LTR" width="137">Pick Release to PDC</td>
    <td dir="LTR" width="180">Release to Warehouse</td>
    <td dir="LTR" width="460">Order released to PDC for fulfillment</td>
  </tr>
  <tr>
    <td dir="LTR" width="137">Picked at PDC</td>
    <td dir="LTR" width="180">Staged/Pick Confirmed</td>
    <td dir="LTR" width="460">Order picked at PDC and waiting shipment to AFC</td>
  </tr>
  <tr>
    <td dir="LTR" width="137">In Transit to AFC</td>
    <td dir="LTR" width="180">Staged/Pick Confirmed</td>
    <td dir="LTR" width="460">Order left PDC for delivery to AFC</td>
  </tr>
  <tr>
    <td dir="LTR" width="137">Ready for Pickup</td>
    <td dir="LTR" width="180">Staged/Pick Confirmed</td>
    <td dir="LTR" width="460">Order arrived AFC and is ready for pickup</td>
  </tr>
  <tr>
    <td dir="LTR" width="137">Completed</td>
    <td dir="LTR" width="180">Shipped</td>
    <td dir="LTR" width="460">Order interfaced and picked up or in transit to destination</td>
  </tr>
</table>
<br>
<table width="100%" border="0" class="table-bordered table-condensed table-striped" style="font-size:11px">
  <tbody>
    <tr style="font-size:10px; background-color:#00aaf5; color:#FFFFFF">
      <th colspan="2" align="left" scope="col">PO ORDERS</th>
    </tr>
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
      <td>Shipped</td>
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
        <?php if(isset($_POST['ocd'])) { ?>
        <div align="left"><strong>Search Results for:</strong> <?php echo $oracleCD ?></div>
        <?php } ?>
  <div align="center">
    	<table width="100%" class="table-bordered table-hover table-striped table-responsive">
  <thead>
    <tr style="font-size:10px; background-color:#00aaf5; color:#FFFFFF" >
      <th valign="top" style="padding:3px"><div align="center">Order Type</div></th>
      <th valign="top" style="padding:3px">PPM #</th>
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
    </div>
</div>
<!-- Body End -->
</div> <!--loader encapsilation -->
<!--<footer class="footer">
  <div class="container" align="center">
    <div class="row">
      <div class="col-xs-12"><br>
      	<img src="images/emo.png" width="225" height="40" alt=""/>
<p>Copyright© 2019. Cox Communications. All rights reserved.</p>
        <span style="font-size:9px" >This site contains confidential information intended solely for the use of authorized users of Cox Communications, Engineering Management Office. If you are not authorized to view this site, you
should exit immediately and are hereby notified that disclosure, copying, distribution, or reuse of this message or any information contained therein by any other person is strictly prohibited.</span> </div>
    </div>
  </div>
</footer>-->
<script src="../js/bootstrap-3.3.4.js" type="text/javascript"></script>
<script>
	var myVar;
	
	function myFunction() {
	  myVar = setTimeout(showPage, 1000);
	}
	
	function showPage() {
	  document.getElementById("loader").style.display = "none";
	  document.getElementById("myDiv").style.display = "block";
	}
</script>
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
</body>
</html>