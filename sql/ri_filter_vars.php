<?php 
// FISCAL YEAR
//$fiscal_year = '2023';
$fiscal_year = $_GET['fiscal_year'];

if (isset($_POST['fiscal_year'])) { 
	//$values_fy = $_POST['fiscal_year'];
	
	//$list_fy = implode('|', $values_fy);
	//$fiscal_year = $list_fy;
	$fiscal_year = $_POST['fiscal_year'];
}

// STATUS 
$pStatus = '-1';
if (!empty($_POST['pStatus'])) {
$pStatus = $_POST['pStatus'];

$list_status = implode('|', $pStatus);
$pStatus = $list_status;
}
// OWNER // GET THE OWNER FROM THE PROJECT ID

$owner = $row_projID['PROJ_OWNR_NM'];
if (!empty($_POST['owner'])) {
$owner = $_POST['owner'];

$list_owner = implode('|', $owner);
$owner = $list_owner;
}

//PROGRAM_N for filters
$program_n = '-1';
if (!empty($_POST['program'])) {
$program_n = $_POST['program'];

$list_program_n = implode('|', $program_n);
$program_n = $list_program_n;
}
// PROGRAM 
$program_d = '-1';
if (!empty($_GET['program'])){
	$program_d = $row_projID['PRGM'];
} else if (!empty($_POST['program'])) {
	$program_d = $_POST['program'];

	$list_program_d = implode('|', $program_d);
	$program_d = $list_program_d;
}
// SUBPRGRAM
$subprogram = '-1';
if (!empty($_POST['subprogram'])) {
$subprogram = $_POST['subprogram'];

$list_subprogram = implode('|', $subprogram);
$subprogram = $list_subprogram;
}
// REGION
$region = '-1';
if (!empty($_POST['region'])) {
	$region = $_POST['region'];

	$list_region = implode('|', $region);
	$region = $list_region;
}

// MARKET
$market = '-1';
if (!empty($_POST['market'])) {
$market = $_POST['market'];

$list_market = implode('|', $market);
$market = $list_market;
}

// FACILITY
$facility = '-1';
if (!empty($_POST['facility'])) {
$facility = $_POST['facility'];

$list_facility = implode('|', $facility);
$facility = $list_facility;
}
?>