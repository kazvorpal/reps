<?php

//POR DATES
    //CR APPROVAL
    $sql_CR_dts = "select Config_Attributes_Key,Application_Nm,Attribute_Key,FORMAT(CONVERT(date,Attribute_Value),'MM-dd-yyyy') AS Attribute_Value  from [LogMgt].[fn_GetConfigAttributes] ('POR','Last_CR_Approval_Date_2023')";
    $stmt_CR_dts = sqlsrv_query( $data_conn, $sql_CR_dts ); 
    $row_CR = sqlsrv_fetch_array( $stmt_CR_dts, SQLSRV_FETCH_ASSOC) ;

    $por_cr_date = $row_CR['Attribute_Value'];
    //$CRDts = date_format($por_cr_date,"m-d-Y");

    //POR PUBLISH
    $sql_por_dts = "select Config_Attributes_Key,Application_Nm,Attribute_Key,FORMAT(CONVERT(date,Attribute_Value),'MM-dd-yyyy') AS Attribute_Value from [LogMgt].[fn_GetConfigAttributes] ('POR','Last_Process_Date_2023')";
    $stmt_por_dts = sqlsrv_query( $data_conn, $sql_por_dts ); 
    $row_por_dts = sqlsrv_fetch_array( $stmt_por_dts, SQLSRV_FETCH_ASSOC) ;

    $por_pub_date = $row_por_dts['Attribute_Value'];
    //$PRODate = date_format($por_pub_date,"m-d-Y");

?>