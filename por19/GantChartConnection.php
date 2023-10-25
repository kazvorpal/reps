<?php include ("../includes/functions.php");?>
<?php include ("../db_conf.php");?>
<?php
//serverName\instanceName
$serverName = "CATL0DB723\EMO"; 
// --------------------- Database Connection ----------------------- //
$connectionInfo = array("Database"=>"$db_nm2", "UID"=>"$db_uid", "PWD"=>"$decrypted");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

// if( $conn ) {
//     echo "Connection established! You are now connected to $serverName.<br />";
// }else{
//     echo "Connection could not be established.<br />";
//     die( print_r( sqlsrv_errors(), true));
// }

// SSMS Query
$sql = "SELECT 
COALESCE (POR_Key, '') AS Primary_Key
,COALESCE(Fiscal_year, '') as Fiscal_Year
,COALESCE(Region_Abb, '') as Region
,COALESCE (Market_Abb, '') as Market
,COALESCE(Location_Cd, '') as Facility
,COALESCE(Program_Nm, '') as Program
,COALESCE (EPSProject_Nm, '') as Project
,COALESCE(EquipPlan_id, '') as Equipment_ID
,COALESCE(SubCR_Id, '') as CR_ID
,COALESCE(CASE 
WHEN (ToEpA_Cd ='NEW') THEN 'New'
WHEN (ToEpA_Cd ='UPDATE') THEN 'Chg'
WHEN (ToEpA_Cd ='INACTIVE') THEN 'Del'
ELSE NULL END, '') AS EPA
,CASE WHEN SubCR_Id <> '' THEN 'All CRs'
ELSE 'Non CRs'  END as CRR
,COALESCE (FORMAT (FromShipping_Dt, 'MM/dd/yyyy', 'en-US'), '') AS From_Shipping_DT
,COALESCE (FORMAT (FromActivation_Dt, 'MM/dd/yyyy', 'en-US'), '') AS From_Activation_DT
,COALESCE (FORMAT (ToShipping_Dt, 'MM/dd/yyyy', 'en-US'), '') AS Shipping_DT
,COALESCE (FORMAT (ToActivation_Dt, 'MM/dd/yyyy', 'en-US'), '') AS Activation_DT
,COALESCE (FORMAT (ToMigration_Dt, 'MM/dd/yyyy', 'en-US'), '') AS Migration_DT
,COALESCE (FORMAT (ToActivation_Dt, 'MMM yyyy', 'en-US'), '') AS Activation_Month
,COALESCE (FORMAT (ToMigration_Dt, 'MMM yyyy', 'en-US'), '') AS Migration_Month
FROM  [MatVw].[POR]
WHERE Fiscal_Year = 2019
AND EquipPlan_Id != 'LC_SAB-New Router'
--WHERE ToEpA_Cd ='UPDATE' and FromActivation_Dt <> ToActivation_Dt
ORDER by ToActivation_Dt ASC,  SubCR_Id DESC
";

$stmt = sqlsrv_query( $conn, $sql );
$json_array =  array();
if( $stmt === false) {
    die( print_r( sqlsrv_errors(), true) );
}
while( $row_test = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) ) {
     $json_array[] = $row_test;
     //  echo $row_test['PROJ_ID'].", ".$row_test['PROJ_NM']."<br />";
}
// sqlsrv_free_stmt( $stmt);
print_r(json_encode($json_array))
?>
