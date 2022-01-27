<?php 
$sql_lf = "SELECT ROW_NUMBER() OVER(ORDER BY PROJ_NM ASC) AS Row#
				  ,[PROJ_ID]
				  ,[PROJ_NM]
				  ,[PRGM]
				  ,PPM_PROJ
				  ,ORACLE_PROJ_CD
				  ,MARKET
				  ,FACILITY
				  ,PROJ_STRT_DT
				  ,PROJ_FNSH_DT
				  ,COMIT_DT
				  ,PROJ_OWNR_NM
				  ,FISCL_PLAN_YR
			FROM [ODS].[EPS].[PROJECT_STG]
			order by PROJ_NM
		  ";
$stmt_lf = sqlsrv_query( $conn_COXProd, $sql_lf );
//$row_lf = sqlsrv_fetch_array( $stmt_lf, SQLSRV_FETCH_ASSOC);
?>