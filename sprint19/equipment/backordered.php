<?php include ("../includes/functions.php");?>
<?php include ("../db_conf.php");?>
<?php include ("../data/emo_data.php");?>
<?php include ("../sql/eq_backordered.php");?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<link rel="apple-touch-icon" type="image/png" href="https://static.codepen.io/assets/favicon/apple-touch-icon-5ae1a0698dcc2402e9712f7d01ed509a57814f994c660df9f7a952f3060705ee.png" />
<meta name="apple-mobile-web-app-title" content="CodePen">
<link rel="shortcut icon" type="image/x-icon" href="https://static.codepen.io/assets/favicon/favicon-aec34940fbc1a6e787974dcd360f2c6b63348d4b1f4e06c77743096d55480f33.ico" />
<link rel="mask-icon" type="" href="https://static.codepen.io/assets/favicon/logo-pin-8f3771b1072e3c38bd662872f6b673a722f4b3ca2421637d5596661b4e2132cc.svg" color="#111" />
<title>Backordered Equipment</title>
<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css'>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.10.0/bootstrap-table.min.css'>
<link rel='stylesheet' href='https://rawgit.com/vitalets/x-editable/master/dist/bootstrap3-editable/css/bootstrap-editable.css'>
<script>
  window.console = window.console || function(t) {};
</script>
<script>
  if (document.location.search.match(/type=embed/gi)) {
    window.parent.postMessage("resize", "*");
  }

  $('#table').dataTable( {
    "paging": true
	} );
</script>
</head>
<body translate="no">
<div class="containerx">
<div align="center">
  <h3>Backordered Equipment</h3></div>
<div id="toolbar">
    <select class="form-control">
        <option value="">Export Basic</option>
        <option value="all">Export All</option>
        <option value="selected">Export Selected</option>
    </select>
</div>
<table width="98%" align="center" class="table-responsive table-striped" id="table" data-toggle="table" data-search="true" data-filter-control="true" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar" style="font-size:10px">
<thead>
    <tr>
        <th data-field="row"></th>
        <th data-field="Project_Num" data-filter-control="input" data-sortable="true">Proj No.</th>
        <th data-field="OrderType" data-filter-control="input" data-sortable="true">Order Type</th>
        <th data-field="Order_Num" data-filter-control="input" data-sortable="true">Order No. </th>
        <th data-field="Line_Num" data-filter-control="select" data-sortable="true">Line No.</th>
        <th data-field="Manufacturer_Nm" data-filter-control="select" data-sortable="true">Manufacturer</th>
        <th data-field="Description" data-filter-control="input" data-sortable="true">Description</th>
        
        <th data-field="COX_PID" data-filter-control="select" data-sortable="true">Cox Part</th>
        <th data-field="MFG_PART_Num" data-filter-control="select" data-sortable="true">MFR Part</th>
        <th data-field="amount" data-filter-control="select" data-sortable="true">Amount</th>
        <th data-field="Shipment_Status" data-filter-control="select" data-sortable="true">Shipment Status</th>
        <th data-field="Received" data-filter-control="select" data-sortable="true">Received</th>
        
        <th data-field="needbydate" data-filter-control="select" data-sortable="true">Need by Date</th>
        <th data-field="Transaction_Date" data-filter-control="select" data-sortable="true">Transaction Date</th>
        <th data-field="qtyordered" data-filter-control="select" data-sortable="true">Qty Ordered</th>
        <th data-field="Qty_Received" data-filter-control="select" data-sortable="true">Qty Received</th>
        <th data-field="WWTOrder" data-filter-control="select" data-sortable="true">WWT Order</th>
        <th data-field="preparedby" data-filter-control="select" data-sortable="true">Preparer</th>
    </tr>
</thead>
<tbody>
<?php while($row_lf = sqlsrv_fetch_array( $stmt_lf, SQLSRV_FETCH_ASSOC)) { ?>
    <tr>
        <td><?php echo $row_lf['Row#'] ?></td>
        <td><?php echo $row_lf['Project_Num'] ?></td>
        <td><?php echo $row_lf['OrderType'] ?></td>
        <td><?php echo $row_lf['Order_Num'] ?></td>
        <td><?php echo $row_lf['Line_Num'] ?></td>
        <td><?php echo $row_lf['Manufacturer_Nm'] ?></td>        
        <td><?php echo $row_lf['Description'] ?></td>
        
        <td><?php echo $row_lf['COX_PID'] ?></td>
        <td><?php echo $row_lf['MFG_PART_Num'] ?></td>
		<td><?php echo $row_lf['Ordered_Amt'] ?></td>
        <td><?php echo $row_lf['Shipment_Status'] ?></td>
        <td><?php echo $row_lf['Received'] ?></td>
        
        <td><?php echo convtimex($row_lf['Deducted_RQT_Dt']) ?></td>
        <td><?php echo convtimex($row_lf['Transaction_Date']) ?></td>
        <td><?php echo $row_lf['Ordered_QTY'] ?></td> 
        <td><?php echo $row_lf['Qty_Received'] ?></td>
        <td><?php echo $row_lf['WWTOrder'] ?></td>
        <td><?php echo $row_lf['Preparer_Nm'] ?></td>
        
    </tr>
<?php } ?>
</tbody>
<tfoot>
	    <tr>
        <th data-field="row"></th>
        <th data-field="prenom" data-filter-control="input" data-sortable="true">Project ID</th>
        <th data-field="date" data-filter-control="input" data-sortable="true">Project </th>
        <th data-field="examen" data-filter-control="select" data-sortable="true">Program</th>
        <th data-field="note" data-filter-control="select" data-sortable="true">PPM</th>
        <th data-field="oraclecd" data-filter-control="input" data-sortable="true">Oracle Code</th>
        
        <th data-field="start" data-filter-control="select" data-sortable="true">Start Date</th>
        <th data-field="finish" data-filter-control="select" data-sortable="true">Finish Date</th>
        <th data-field="owner" data-filter-control="select" data-sortable="true">Owner</th>
        <th data-field="fiscalyear" data-filter-control="select" data-sortable="true">Fiscal Year</th>
    </tr>
</tfoot>
</table>
</div>
<script src="https://static.codepen.io/assets/common/stopExecutionOnTimeout-30d18ea41045577cdb11c797602d08e0b9c2fa407c8b81058b1c422053ad8041.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.10.0/bootstrap-table.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.9.1/extensions/editable/bootstrap-table-editable.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.9.1/extensions/export/bootstrap-table-export.js'></script>
<script src='https://rawgit.com/hhurz/tableExport.jquery.plugin/master/tableExport.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.9.1/extensions/filter-control/bootstrap-table-filter-control.js'></script>
<script id="rendered-js">
//exporte les données sélectionnées
var $table = $('#table');
$(function () {
  $('#toolbar').find('select').change(function () {
    $table.bootstrapTable('refreshOptions', {
      exportDataType: $(this).val() });

  });
});

var trBoldBlue = $("table");

$(trBoldBlue).on("click", "tr", function () {
  $(this).toggleClass("bold-blue");
});
//# sourceURL=pen.js
    </script>
</body>
</html>
