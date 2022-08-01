<?php 
// LIMIT DATE TODAY FOR DATE CLOSED USED ON UPDATE PAGES
$closeDateMax = date("Y-m-d"); //universal


//INTERNAL
$sql_internal = "select * from [RI_MGT].[fn_GetListOfCurrentTaskPOC] (1) order by POC_Nm";
$stmt_internal = sqlsrv_query( $data_conn, $sql_internal ); 
//$row_internal = sqlsrv_fetch_array( $stmt_internal, SQLSRV_FETCH_ASSOC);
//$row_internal['columnname']

//EXTERNAL
$sql_external = "select * from [RI_MGT].[fn_GetListOfCurrentTaskPOC] (0) order by POC_Nm";
$stmt_external  = sqlsrv_query( $data_conn, $sql_external  ); 
//$row_external  = sqlsrv_fetch_array( $stmt_external , SQLSRV_FETCH_ASSOC);
//$row_external['columnname']

//DRIVERS
$sql_drivers = "SELECT *  FROM [COX_Dev].[RI_MGT].[Driver]";
$stmt_drivers  = sqlsrv_query( $data_conn, $sql_drivers ); 
//$row_drivers = sqlsrv_fetch_array( $stmt_drivers , SQLSRV_FETCH_ASSOC);
//$row_drivers['columnname']

//IMPACTED AREA
$sql_impArea= "SELECT *  
                FROM [RI_MGT].[Impact_Area]
                ORDER BY CASE
                    WHEN ImpactArea_Nm = 'Schedule' THEN 1
                    WHEN ImpactArea_Nm = 'Scope' THEN 2
                    WHEN ImpactArea_Nm = 'Budget (Cost Change)' THEN 3
                    ELSE 4 END";
$stmt_impArea  = sqlsrv_query( $data_conn, $sql_impArea ); 
//$row_ impArea= sqlsrv_fetch_array( $stmt_impArea , SQLSRV_FETCH_ASSOC);
//$row_impArea['columnname']

//IMPACTED LEVEL
$sql_imLevel = "SELECT *  
                FROM [RI_MGT].[Impact_Level]
                WHERE ImpactLevel_Nm != 'No Impact'";
$stmt_imLevel  = sqlsrv_query( $data_conn, $sql_imLevel ); 
//$row_imLevel = sqlsrv_fetch_array( $stmt_imLevel , SQLSRV_FETCH_ASSOC);
//$row_imLevel['columnname']

//RISK PROBABILITY
$sql_probability = "SELECT *  FROM [RI_MGT].[Risk_Probability]
                    WHERE RiskProbability_Nm != '0% - Risk Only'";
$stmt_probability  = sqlsrv_query( $data_conn, $sql_probability ); 
//$row_ probability= sqlsrv_fetch_array( $stmt_probability , SQLSRV_FETCH_ASSOC);
//$row_probability['columnname']

//RESPONSE STRATEGY
$sql_strategy = "SELECT *  FROM [RI_MGT].[Response_Strategy]";
$stmt_strategy  = sqlsrv_query( $data_conn, $sql_strategy  ); 
//$row_strategy = sqlsrv_fetch_array( $stmt_strategy , SQLSRV_FETCH_ASSOC);
//$row_strategy['columnname']
?>