<?php    
//TOOLTIP
//$row_tooltip['ToolTip_Nm']
$ToolTip_Key = $_GET['tooltipkey'];

$sql_tooltip = "select * from RI_Mgt.tooltip WHERE ToolTip_Key = $ToolTip_Key";
$stmt_tooltip = sqlsrv_query( $data_conn, $sql_tooltip );
$row_tooltip = sqlsrv_fetch_array( $stmt_tooltip, SQLSRV_FETCH_ASSOC)
?>