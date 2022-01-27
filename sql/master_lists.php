<?php
// PROGRAM LIST ALL
$sql_ml_program = "SELECT *  FROM [COX_QA].[CR_MGT].[Program]";
$stmt_ml_program = sqlsrv_query( $conn, $sql_ml_program );
$row_ml_program = sqlsrv_fetch_array( $stmt_ml_program, SQLSRV_FETCH_ASSOC) 
//echo $row_ml_program['PROJ_ID']


// COX FACLITIES

// EQUIPMENT

// COX COLOCATIONS

 ?>