<?php 
$sql_lf = "SELECT ROW_NUMBER() OVER(ORDER BY Order_Num ASC) AS Row#, 
				OrderType, Order_Num, Line_Num
				, Manufacturer_Nm, [Description], Deducted_RQT_Dt
				, COX_PID, MFG_PART_Num, Ordered_QTY, Ordered_Amt, Shipment_Status
				, Case When Receipt_Num Is Null then 'No' Else 'Yes' End As Received
				, Transaction_Date
				, Project_Num
				, Qty_Received
				, Preparer_Nm
				, Case When WWT_AsOf_Dt Is Not Null then 'Yes' Else 'No' End As WWTOrder
				FROM DBO.fn_GetOrderHistory ()
				Where Shipment_Status = 'BACKORDERED'
				ORDER BY Order_Num, Line_Num
		  ";
$stmt_lf = sqlsrv_query( $conn_COXProd, $sql_lf );
//$row_lf = sqlsrv_fetch_array( $stmt_lf, SQLSRV_FETCH_ASSOC);
?>