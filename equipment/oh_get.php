﻿<?php include ("../includes/functions.php");?>
<?php include ("../db_conf.php");?>
<?php include ("../data/emo_data.php");?>
<?php include ("../sql/orderHistory_get.php");?>
<?php include ("../sql/update-time.php");?>
<?php include ("../includes/load.php");?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Order History Search</title>
<meta charset="UTF-8">
<link rel="shortcut icon" href="../favicon.ico"/>
<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css'>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.10.0/bootstrap-table.min.css'>
<link rel='stylesheet' href='https://rawgit.com/vitalets/x-editable/master/dist/bootstrap3-editable/css/bootstrap-editable.css'>

<link rel="stylesheet" href="https://unpkg.com/jquery-resizable-columns@0.2.3/dist/jquery.resizableColumns.css">
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.18.0/dist/extensions/sticky-header/bootstrap-table-sticky-header.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/akottr/dragtable@master/dragtable.css">
<script>
  window.console = window.console || function(t) {};
</script>
<script>
  if (document.location.search.match(/type=embed/gi)) {
    window.parent.postMessage("resize", "*");
  }
</script>
<style type="text/css">
    .popover{
        max-width:800px;
    }
</style>
</head>
<body translate="no" onload="myFunction()" style="margin:0;">
<div id="loader"></div>
<div style="display:block;" id="myDiv" class="animate-bottom"> <!--change block to none when not debugging-->

<?php include ("../includes/menu.php");?>
<div align="center">
  <h3>Equipment Order History</h3>
</div>
<div class="container-fluid">
  <div class="row" align="center">
    <div class="col-md-4"></div>
    <div class="col-md-4" style="padding:4px">
      <form method="post" name="myForm" id="myForm" class="form-group">
        
      </form>
      </div>
    <div class="col-md-4" align="right"></div>
  </div>
</div>
<!-- Start Status definitions floating button -->
<a  style="position:absolute;top:75px;right:5px;margin:0;padding:5px 3px;" 
    href="#" 
    data-toggle="popover" 
    title="IO/PO Status Definitions" 
    data-placement="left"  
    data-content="">
      <span class="btn btn-primary">Status Definitions</span>
</a>
<!-- End -->
<!-- Start DataTable-->
  <table 
    class="table-responsive table-striped" 
    id="table" 
    data-toggle="table" 
    data-search="false" 
    data-filter-control="true" 
    data-show-export="true" 
    data-click-to-select="true" 
    data-toolbar=".toolbar" 
    data-pagination="true" 
    data-height="700"
    data-show-columns="true"
    data-show-columns-toggle-all="true"
    data-resizable="true"
    data-reorderable-columns="true"
    data-page-size="25"
    data-buttons-class="primary"
    data-show-refresh="false"
    >
    <thead style="font-size:11px; background-color:#00aaf5; color:#FFFFFF"">
        <tr>  
            <th data-field="OrderType" data-filter-control="select" data-sortable="true" data-title-tooltip="Order Type - Internal Order or Purchase Order">ORDER TYPE</th>
          	<th data-field="OrderNumber" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="Order Number - IO or PO Number">ORDER #</span></th>
            <th data-field="LineNumber" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="Line Number - Line and sub line number, sub line is used when a line is split into different delivery date.">LINE #</span></th>
            <th data-field="RequisitionNumber" data-filter-control="select" data-sortable="true" data-title-tooltip="Requisition Number - Requisition Id (Before IO's are created)">REQUISITION #</th>
            <th data-field="RequisitionSourcer" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="Requisition Source - Where the requisition was imported from">REQUISITION SOURCE</span></th>
            <th data-field="WATTSOrderNumber" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="WATTS Order Number">WATTS ORDER #</span></th>
            <th data-field="RequisitionCreationDate" data-filter-control="select" data-sortable="true" data-title-tooltip="Requisition Creation Date - Requisition created date within Oracle">REQ CREATION DATE</th>
            <th data-field="RequistionApprovalDate" data-filter-control="select" data-sortable="true" data-title-tooltip="Requistion Approval Date - Requisition approved date">REQ APPROVED DATE</th>
            <th data-field="NeedByDate" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="Need by Date - Requested delivery date from original Requisition">NEED BY DATE</span></th>
            <th data-field="PPM_Number" data-filter-control="select" data-sortable="true" data-title-tooltip="PPM Number - PPM">PPM NUMBER</th>
            <th data-field="ProjectName" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="Engineering Project Name">PROJECT NAME</span></th>
            <th data-field="Project_Number" data-filter-control="select" data-sortable="true" data-title-tooltip="Project Number - Oracle Project Code">PROJECT #</th>
	    <th data-field="Oracle_Project_Name" data-filter-control="select" data-sortable="true" data-title-tooltip="Oracle Project Name">ORACLE PROJECT NAME</th>
            <th data-field="Facility_Code" data-filter-control="select" data-sortable="true" data-title-tooltip="Facility Code">FACILTIY CODE</th>
            <th data-field="Faclity_Name" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="Facility Name">FACILITY NAME</span></th>
            <th data-field="region" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="Region - ">REGION</span></th>
            <th data-field="ScheduleShipDate" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="Scheduled Ship Date - Uepdated delivery date for when Supply Chain plans to deliver to AFC/Pickup Location">SCHEDULED SHIP DT</span></th>
            <th data-field="scheduledshipdate" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="Scheduled Ship Date - WWT Projected Shipping Date (after WWT lab processing)">WWT SCHEDULED SHIP DATE</span></th>
            <th data-field="PickReleaseDate" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="Pick Release Date - When Oracle releases the pick slip to the warehouse for picking">PICK RELEASE DATE</span></th>
            <th data-field="PickConfirmDate" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="Pick Confirm Date - When the warehouse picks the order off the shelf and stages for shipment">PICK CONFIRM DATE</span></th>
            <th data-field="ActualShipDate" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="Actual Shipment Date - When the material is picked up / Interfaced">ACTUAL SHIPPED DT</span></th>
            <th data-field="shipmentdate" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="Shipment Date - Actual Shipment Date from WWT">WWT SHIPMENT DATE</span></th>
            <th data-field="openyn" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="Open (Yes/No) - If line is complete or cancelled, it's 'N'">OPEN (Y/N)</span></th>
            <th data-field="orderstatus" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="Order Status">ORDER STATUS</span></th>
            <th data-field="ordernotes" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="Order Notes - Additional details based on order">ORDER NOTES</span></th>
            <th data-field="orderqty" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="Order Quantitiy - Quantity Ordered and Unit of the quantity shipped">ORDER QTY</span></th>
            <th data-field="shippedqty" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="Shipped Quantity - Quantity Shipped">SHIPMENT QTY</span></th>
            <th data-field="cancelledqty" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="Cancelled Quantity - Quantity Cancelled">CANCELLED QTY</span></th>
            <th data-field="extendedcost" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="Extended Cost - Total cost of the line">EXTENDED COST</span></th>
            <th data-field="outstandingAmt" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="Outstanding Amount - Total amount outstanding">OUTSTANDING AMOUNT</span></th>
            <th data-field="backorderedqty" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="Backordered Quanitity - Qty on backorder">BACKORDER QTY</span></th>
            <th data-field="releaseqty" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="Ready to Release Quanitity - Quantity ready to release to warehouse">READY TO RELEASE QTY</span></th>
            <th data-field="pickedqty" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="Picked Quantity - Quantity stage picked confirmed">PICKED QTY</span></th>
            <th data-field="CoxPrt" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="COX Part Number - COX Item Number ">COX PART#</span></th>
            <th data-field="ItemDesc" data-filter-control="select" data-sortable="true" data-width="400" data-toggle="tooltip" data-placement="top" data-title-tooltip="Item Description- SCM Item Description">ITEM DESCRIPTION</th>
            <th data-field="Vendor" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="Vendor - Supplier Name">VENDOR</span></th>
            <th data-field="vendorpart" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="Vendor Part Number - ">VENDOR PART #</span></th>
            <th data-field="wwtquote" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="WWT Quote Number ">QUOTE #</span></th>
            <th data-field="franchise" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="Franchise - Program of the order">WWT Program</span></th>
            <th data-field="projectdesc" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="Project Description">PROJECT DESCRIPTION</span></th>
            <th data-field="subttl" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="Subtotal - Order total for the facility">WWT SUBTOTAL</span></th>
            <th data-field="coxrequestdate" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="COX Request Date - COX Requested Need By Date">COX REQUSITION DATE</span></th>
            <th data-field="promisedate" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="Promise Date on Site - WWT Projected Reception Date (after WWT lab processing)">PROMISE DATE ON SITE</span></th>
            <th data-field="coxorderstat" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="COX Order Status">COX ORDER STATUS</span></th>
            <th data-field="headership" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="Header Shipping Method - Carrier name contracted by WWT to Deliver the Equipment">WWT CARRIER NAME</span></th>
            <th data-field="waybill" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="Waybill Number - This is the Shipping/Invoice Number listing all the equipment">WAYBILL #</span></th>
            <th data-field="requesternmr" data-filter-control="select" data-sortable="true" data-title-tooltip="Requester Name - TPS Resource who keyed the order">REQUESTER</th>
            <th data-field="preparer" data-filter-control="select" data-sortable="true" data-title-tooltip="Preparer Name">PREPARER</th>
            <th data-field="optixid" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="Optix ID">OPTIX ID</span></th>
            <th data-field="deliverto" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="Deliver to Address - Faclity Address">DELIVER TO ADDRESS</span></th>
            <th data-field="helpDeskTickeNum" data-filter-control="select" data-sortable="true" data-visible="false"><span data-toggle="tooltip" data-placement="top" title="Help Desk Ticket Number - PDG Help Desk Ticket Number ">HELP DESK TICKET#</span></th>
            <th data-field="lastorcleupdate" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="Last Oracle Update - Date of last change in Oracle ">LAST ORACLE UPDATE</span></th>
            <th data-field="PDCShipDate" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="PDC Ship Date -For PDC Sourced items, when CTDI shipped the order to the AFC CTDI = Commnications Test & Design, Inc., the 3rd party that runs our PDCs">PDC - SHIP DATE</span></th>
            <th data-field="PDCRecieptDat" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="PDC Reciept Date - For PDC Sourced items, when the AFC received the shipment from PDC, available for pickup">PDC - AFC RECEIPT DT</span></th>
            <th data-field="PDCAvailPickupDate" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="PDC Available Pickup Date - For PDC Sourced items, when the AFC placed the shipment in the bin, available for pickup">PDC - AVAILABLE PICKUP DATE</span></th>
            <th data-field="originsourcetype" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="Source of Origin Type - This is my mapping of Source Org to PDC, AFC, IMM, TR">SOURCE ORG TYP</span></th>
            <th data-field="originsource" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="Source of Origin - Possible values: AFCs, PDCs, IMM Yorgs, TR Triage sits">SOURCE OF ORG</span></th>
            <th data-field="glcode" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="GL Code - Accounting GL string (department, account, etc) ">GL CODE</span></th>
            <th data-field="seperetor"><span class="glyphicon glyphicon-plus" data-title-tooltip="Use grid button above to show more fields."></span></th><!--Leave Empty-->
            <!-- Hidden Fields-->
          <th data-field="GL_Project_Id" data-filter-control="select" data-sortable="true" data-visible="false" data-title-tooltip="">GL PROJECT ID</th>
          <th data-field="GL_Project_Start_Dt" data-filter-control="select" data-sortable="true" data-visible="false" data-title-tooltip="">GL PROJECT START DATE</th>
          <th data-field="OrderStatus_Cd" data-filter-control="select" data-sortable="true" data-visible="false" data-title-tooltip="">ORDER STATUS</th>
          <th data-field="LOB_Cd" data-filter-control="select" data-sortable="true" data-visible="false" data-title-tooltip="">LOB</th>
          <th data-field="PurchasedType_Cd" data-filter-control="select" data-sortable="true" data-visible="false" data-title-tooltip="">PURCHASED TYPE</th>
          <th data-field="SC_TotalOnHand_Qty" data-filter-control="select" data-sortable="true" data-visible="false" data-title-tooltip="">TOTAL ON HAND QTY</th>
          <th data-field="SC_Next30DaysDemand_Qty" data-filter-control="select" data-sortable="true" data-visible="false" data-title-tooltip="">NEXT 30 DAYS DEMAND QTY</th>
          <th data-field="SC_TotalDemand_Qty" data-filter-control="select" data-sortable="true" data-visible="false" data-title-tooltip="">TOTAL DEMAND QTY</th>
          <th data-field="Calc_DeliveryWindow_Cd" data-filter-control="select" data-sortable="true" data-visible="false" data-title-tooltip="">CALCULATED DELIVERY WINDOW</th>
           </tr>
    </thead>
    <tbody style="font-size:11px">
    <?php while( $row_eqh = sqlsrv_fetch_array( $stmt_eqh, SQLSRV_FETCH_ASSOC)){?>
    		<tr style="font-size:11px">
              <td><?php echo htmlspecialchars($row_eqh['OrderType_Cd'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['Order_Num'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['OrderLine_Num'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['Requisition_Num'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['RequisitionSource_Cd'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['WATTSSubOrder_Num'])?></td>
              <td><?php echo convtimex($row_eqh['RequisitionCreation_Dt'])?></td>
              <td><?php echo convtimex($row_eqh['RequisitionApproved_Dt'])?></td>
              <td><?php echo convtimex($row_eqh['NeedBy_Dt'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['PPM_Num'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['Project_Nm'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['GL_Project_Num'])?></td>
	      <td><?php echo htmlspecialchars($row_eqh['GL_Project_Nm'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['DestinationOrg_Cd'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['Location_Cd'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['Region_Cd'])?></td>
              <td><?php echo convtimex($row_eqh['ScheduledShip_Dt'])?></td>
              <td><?php echo convtimex($row_eqh['WWT_ScheduledShip_Dt'])?></td>
              <td><?php echo convtimex($row_eqh['Pick_Release_Dt'])?></td>
              <td><?php echo convtimex($row_eqh['Pick_Confirm_Dt'])?></td>
              <td><?php echo convtimex($row_eqh['Actual_Shipment_Dt'])?></td>
              <td><?php echo convtimex($row_eqh['WWT_Shipment_Dt'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['OrderOpen_Flg'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['OrderStatus_Cd'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['OrderNotes_Txt'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['Ordered_Qty']) . ' x ' . htmlspecialchars($row_eqh['Ordered_UOM'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['Shipped_Qty']) . ' x ' . htmlspecialchars($row_eqh['Shipped_UOM'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['Cancelled_Qty'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['TotalLine_Amt'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['Outstanding_Amt'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['BackOrdered_Qty'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['ReadyToRelease_Qty'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['Picked_Qty'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['COX_Part_Num'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['ItemDescription_Txt'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['Vendor_Nm'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['Vendor_Part_Num'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['WWT_Quote_Num'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['WWT_Franchise_Cd'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['WWT_Project_Desc'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['WWT_SubTotal_Amt'])?></td>
              <td><?php echo convtimex($row_eqh['WWT_COXRequest_Dt'])?></td>
              <td><?php echo convtimex($row_eqh['WWT_PromiseOnSite_Dt'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['WWT_COX_OrderStatus_Cd'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['WWT_Carrier_Nm'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['WWT_Waybill_Num'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['Requester_Nm'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['Preparer_Nm'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['OPTIX_Id'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['DeliverToAddress_Txt'])?></td>
              <td><?php echo convtimex($row_eqh['LastOracleUpdate_Dt'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['HelpDeskTicket_Num'])?></td>
              <td><?php echo convtimex($row_eqh['PDC_Ship_Dt'])?></td>
              <td><?php echo convtimex($row_eqh['PDC_AFCReceipt_Dt'])?></td>
              <td><?php echo convtimex($row_eqh['PDC_AvailablePickUp_Dt'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['SourceOrgType_Cd'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['SourceOrg_Cd'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['GL_Segments'])?></td>
              <td></td><!--Leave Empty-->
             <!-- Hidden Fields-->
              <td><?php echo htmlspecialchars($row_eqh['GL_Project_Id'])?></td>
              <td><?php echo convtimex($row_eqh['GL_ProjectStart_Dt'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['OrderStatus_Cd'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['LOB_Cd'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['PurchaseType_Cd'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['SC_TotalOnHand_Qty'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['SC_Next30DaysDemand_Qty'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['SC_TotalDemand_Qty'])?></td>
              <td><?php echo htmlspecialchars($row_eqh['Calc_DeliveryWindow_Cd'])?></td>
        	</tr>
    <?php } ?>
    </tbody>
</table>
<!-- End DataTable-->
</div>
<!-- Start Status Definitions-->
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
      <th colspan="2" align="left" >PO ORDERS</th>
    </tr>
    <tr style="font-size:10px; background-color:#00aaf5; color:#FFFFFF">

      <th width="33%" >PO Status</th>
      <th>PO Definition</th>
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
</div>
<!-- End Status Definition s-->
<!-- Start Search Criteria information box -->
<div id="popover_info_content" style="display: none; padding-bottom:6">
  Requisition #<br>
  Internal Order #<br>
  Oracle Project # <br>
  PPM #<br>
  Optix ID <br>
  TPS HLP Ticket<br><br>
  Search multiple Oracle Project Numbers by seperating them with commas.<br>  
  Example:  1234554321ERQER, 1234554321SRGFD, 1234554321SYDEM
</div>
<!-- End Search Criteria -->
<script src="https://static.codepen.io/assets/common/stopExecutionOnTimeout-30d18ea41045577cdb11c797602d08e0b9c2fa407c8b81058b1c422053ad8041.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.10.0/bootstrap-table.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.9.1/extensions/editable/bootstrap-table-editable.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.9.1/extensions/export/bootstrap-table-export.js'></script>
<script src='https://rawgit.com/hhurz/tableExport.jquery.plugin/master/tableExport.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.9.1/extensions/filter-control/bootstrap-table-filter-control.js'></script>

<script src="https://unpkg.com/jquery-resizable-columns@0.2.3/dist/jquery.resizableColumns.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.0/dist/extensions/resizable/bootstrap-table-resizable.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.0/dist/extensions/sticky-header/bootstrap-table-sticky-header.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/akottr/dragtable@master/jquery.dragtable.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.0/dist/extensions/reorder-columns/bootstrap-table-reorder-columns.min.js"></script>

<script id="rendered-js">
//Tool Bar
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


//Tool Tip
  $(function(){
    $('[data-toggle="popover"]').popover({ 
      html : true, 
      content: function() {
        return $('#popover_content').html();
      }
    });
  });

//Tool Tip Info Icon
$(function(){
    $('[data-toggle="info"]').popover({ 
      html : true, 
      content: function() {
        return $('#popover_info_content').html();
      }
    });
  });


//Show Bootstrap Table
  $(function() {
    $('#table').bootstrapTable()
  })


//Load Spinner
	var myVar;
	
	function myFunction() {
	  myVar = setTimeout(showPage, 1000);
	}
	
	function showPage() {
	  document.getElementById("loader").style.display = "none";
	  document.getElementById("myDiv").style.display = "block";
	}
</script>
</body>
</html>