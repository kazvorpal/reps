<?php include ("../includes/functions.php");?>
<?php include ("../db_conf.php");?>
<?php include ("../data/emo_data.php");?>
<?php include ("../sql/update-time.php");?>
<?php 
//Row Parameters
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

//CR General Information
$fundingKey = $_GET['fk'];
$crid = $_GET['sn'];
$year = $_GET['year'];

$sql_crs = "Select * From dbo.fn_GetCRInformation('$fundingKey')";
$stmt_crs = sqlsrv_query( $conn_COXProd, $sql_crs );
$row_crs = sqlsrv_fetch_array( $stmt_crs, SQLSRV_FETCH_ASSOC);
//echo $row_crs['column_name']

$sql_crsF = "Select * from [dbo].[fn_GetCRFinancialSummary]('$fundingKey')";
$stmt_crsF = sqlsrv_query( $conn_COXProd, $sql_crsF );
$row_crsF = sqlsrv_fetch_array( $stmt_crsF, SQLSRV_FETCH_ASSOC);
//echo $row_crsF['column_name']

// Plan of Record
$sql_cfPor = "SELECT * FROM [PORMgt].[fn_GetListOfPlanChangeForCR]('$fundingKey') ORDER BY Program_Nm";
$stmt_cfPor = sqlsrv_query( $conn_COXProd, $sql_cfPor );
//$row_cfPor = sqlsrv_fetch_array( $stmt_cfPor, SQLSRV_FETCH_ASSOC);
//echo $row_cfPor['column_name']
//echo $sql_cfPor;

// CHANGE REQUEST FROM CURRENT PLAN (RED) $fundingKey
$sql_budCapCCR = "SELECT * 
					FROM fn_GetCRPlanSummary ($fundingKey)
					WHERE Src = 'CR' and Cat_Type = 'capex' and (Category_Key in (1,2,3))";
$stmt_budCapCCR = sqlsrv_query( $conn_COXProd, $sql_budCapCCR, $params, $options );
$rowCount_budCapCCR = sqlsrv_num_rows( $stmt_budCapCCR );
	//$row_budCapCCR = sqlsrv_fetch_array( $stmt_budCapCCR, SQLSRV_FETCH_ASSOC); //comment out when looping
	//echo $row_budCapCCR['column_name']
	
	// CHANGE REQUEST TOTALS
	$sql_budCapCCR_ttl = "SELECT sum(Project_01) AS jan,
									sum(Project_02) AS feb,
									sum(Project_03) AS mar,
									sum(Project_04) AS apr,
									sum(Project_05) AS may,
									sum(Project_06) AS jun,
									sum(Project_07) AS jul,
									sum(Project_08) AS aug,
									sum(Project_09) AS sep,
									sum(Project_10) AS oct,
									sum(Project_11) AS nov,
									sum(Project_12) AS dec
							FROM ( SELECT * 
									FROM fn_GetCRPlanSummary ($fundingKey)
									WHERE Src = 'CR' and Cat_Type = 'capex' and (Category_Key = 1 or Category_Key = 2 or Category_Key = 3)) AS c";
	$stmt_budCapCCR_ttl = sqlsrv_query( $conn_COXProd, $sql_budCapCCR_ttl, $params, $options );
	$rowCount_budCapCCR_ttl = sqlsrv_num_rows( $stmt_budCapCCR_ttl );
	$row_budCapCCR_ttl = sqlsrv_fetch_array( $stmt_budCapCCR_ttl, SQLSRV_FETCH_ASSOC); //comment out when looping
		//echo $row_budCapCCR_ttl['column_name']

// CURRENT PLAN (BLUE)
// $sql_budSubCCR = "SELECT* FROM (select * from fn_GetCRPlanSummary($fundingKey)) AS C WHERE Src = 'All' AND Cat_Type = 'Capex'";
$sql_budSubCCR = "SELECT * 
					FROM fn_GetCRPlanSummary ($fundingKey)
					WHERE Src = 'All' and Cat_Type = 'capex' and (Category_Key in (1,2,3))";
$stmt_budSubCCR = sqlsrv_query( $conn_COXProd, $sql_budSubCCR, $params, $options);
$rowCount_budSubCCR = sqlsrv_num_rows( $stmt_budSubCCR );
	//$row_budSubCCR = sqlsrv_fetch_array( $stmt_budSubCCR, SQLSRV_FETCH_ASSOC); //comment out when looping
	//echo $row_budSubCCR['column_name']
	
		//CURRENT PLAN TOTAL
		$sql_budSubCCR_ttl = "SELECT sum(Project_01) as jan,
								   sum(Project_02) as feb,
								   sum(Project_03) as mar,
								   sum(Project_04) as apr,
								   sum(Project_05) as may,
								   sum(Project_06) as jun,
								   sum(Project_07) as jul,
								   sum(Project_08) as aug,
								   sum(Project_09) as sep,
								   sum(Project_10) as oct,
								   sum(Project_11) as nov,
								   sum(Project_12) as dec
						
								FROM ( SELECT * 
										FROM fn_GetCRPlanSummary ($fundingKey)
										WHERE Src = 'All' and Cat_Type = 'capex' and (Category_Key = 1 or Category_Key = 2 or Category_Key = 3)) AS C ";
								//FROM(SELECT* FROM (select * from fn_GetCRPlanSummary($fundingKey)) AS C WHERE Src = 'All' AND Cat_Type = 'Capex') AS b";
		$stmt_budSubCCR_ttl = sqlsrv_query( $conn_COXProd, $sql_budSubCCR_ttl, $params, $options);
		//$rowCount_budSubCCR_ttl = sqlsrv_num_rows( $stmt_budSubCCR_ttl );
		$row_budSubCCR_ttl = sqlsrv_fetch_array( $stmt_budSubCCR_ttl, SQLSRV_FETCH_ASSOC); //comment out when looping
			//echo $row_budSubCCR_ttl['column_name']

// CURRENT PLAN + CR (GREEN)
$sql_budttlCCR = " SELECT * 
					FROM fn_GetCRPlanSummary ($fundingKey)
					WHERE Src = 'CCRBandCR' and Cat_Type = 'capex' and (Category_Key in (1,2,3))
				 ";
$stmt_budttlCCR = sqlsrv_query( $conn_COXProd, $sql_budttlCCR, $params, $options);
$rowCount_budttlCCR = sqlsrv_num_rows( $stmt_budttlCCR );
	//$row_budttlCCR = sqlsrv_fetch_array( $stmt_budttlCCR, SQLSRV_FETCH_ASSOC); //comment out when looping
	//echo $row_budttlCCR['column_name']
	
	// CURRENT PLAN + CR TOTALS
	$sql_budttlCCR_ttl = "SELECT sum(Project_01) as jan,
								   sum(Project_02) as feb,
								   sum(Project_03) as mar,
								   sum(Project_04) as apr,
								   sum(Project_05) as may,
								   sum(Project_06) as jun,
								   sum(Project_07) as jul,
								   sum(Project_08) as aug,
								   sum(Project_09) as sep,
								   sum(Project_10) as oct,
								   sum(Project_11) as nov,
								   sum(Project_12) as dec
						FROM( SELECT * 
								FROM fn_GetCRPlanSummary ($fundingKey)
								WHERE Src = 'CCRBandCR' and Cat_Type = 'capex' and (Category_Key = 1 or Category_Key = 2 or Category_Key = 3)) AS D";
		$stmt_budttlCCR_ttl = sqlsrv_query( $conn_COXProd, $sql_budttlCCR_ttl, $params, $options);
		$rowCount_budttlCCR_ttl = sqlsrv_num_rows( $stmt_budttlCCR_ttl );
		$row_budttlCCR_ttl = sqlsrv_fetch_array( $stmt_budttlCCR_ttl, SQLSRV_FETCH_ASSOC); //comment out when looping
			//echo $row_budttlCCR_ttl['column_name']
	
	

// Schedule Change section
$sql_budschChng = "select * from [dbo].[fn_GetCRInformation]($fundingKey)";
$stmt_budschChng = sqlsrv_query( $conn_COXProd, $sql_budschChng );
$row_budschChng = sqlsrv_fetch_array( $stmt_budschChng, SQLSRV_FETCH_ASSOC); //comment out when looping
//echo $row_budschChng['column_name']


///////// OPEX FINANCIAL PANEL //////////
// OPEX Budget Current Plan for the Program - BLUE $fundingKey
		$sql_budSubCCR_O = "
						SELECT * 
						FROM fn_GetCRPlanSummary ($fundingKey)
						WHERE Src = 'All' and Cat_Type = 'OPEX' and (Category_Key = 4 or Category_Key = 5 or Category_Key = 6)
						";
		$stmt_budSubCCR_O = sqlsrv_query( $conn_COXProd, $sql_budSubCCR_O, $params, $options);
		//$rowCount_budSubCCR_O = sqlsrv_num_rows( $stmt_budSubCCR_O );
		//$row_budSubCCR = sqlsrv_fetch_array( $stmt_budSubCCR, SQLSRV_FETCH_ASSOC); //comment out when looping
		//echo $row_budSubCCR['column_name']
	
			// OPEX Budget Total - BLUE 
				$sql_budSubCCR_O_ttl = "
								SELECT SUM(Project_01) AS Project_01,
										SUM(Project_02) AS Project_02,
										SUM(Project_03) AS Project_03,
										SUM(Project_04) AS Project_04,
										SUM(Project_05) AS Project_05,
										SUM(Project_06) AS Project_06,
										SUM(Project_07) AS Project_07,
										SUM(Project_08) AS Project_08,
										SUM(Project_09) AS Project_09,
										SUM(Project_10) AS Project_10,
										SUM(Project_11) AS Project_11,
										SUM(Project_12) AS Project_12
								FROM(
									SELECT * 
									FROM fn_GetCRPlanSummary ($fundingKey)
									WHERE Src = 'All' and Cat_Type = 'OPEX' and (Category_Key = 4 or Category_Key = 5 or Category_Key = 6)
									) AS a
								";
				$stmt_budSubCCR_O_ttl = sqlsrv_query( $conn_COXProd, $sql_budSubCCR_O_ttl, $params, $options);
				//$rowCount_budSubCCR_O_ttl = sqlsrv_num_rows( $stmt_budSubCCR_O_ttl );
				$row_budSubCCR_O_ttl = sqlsrv_fetch_array( $stmt_budSubCCR_O_ttl, SQLSRV_FETCH_ASSOC); //comment out when looping
				//echo $row_budSubCCR_O_ttl['Project_01']
	
// OPEX Budget Submitted CR(s) for the Program - RED
	$sql_budCapCCR_O = "
						SELECT * 
						FROM fn_GetCRPlanSummary ($fundingKey)
						WHERE Src = 'CR' and Cat_Type = 'OPEX' and (Category_Key = 4 or Category_Key = 5 or Category_Key = 6)
				 ";
	$stmt_budCapCCR_O = sqlsrv_query( $conn_COXProd, $sql_budCapCCR_O, $params, $options );
	//$rowCount_budCapCCR_O = sqlsrv_num_rows( $stmt_budCapCCR_O );
	//$row_budCapCCR = sqlsrv_fetch_array( $stmt_budCapCCR, SQLSRV_FETCH_ASSOC); //comment out when looping
	//echo $row_budCapCCR['column_name']
	
				// OPEX Budget Submitted CR(s) Total - RED
				$sql_budCapCCR_O_ttl = "
									SELECT * 
									FROM fn_GetCRPlanSummary ($fundingKey)
									WHERE Src = 'CR' and Cat_Type = 'OPEX' and (Category_Key = 4 or Category_Key = 5 or Category_Key = 6)
							 ";
				$stmt_budCapCCR_O_ttl = sqlsrv_query( $conn_COXProd, $sql_budCapCCR_O_ttl, $params, $options );
				//$rowCount_budCapCCR_O_tt = sqlsrv_num_rows( $stmt_budCapCCR_O_tt );
				$row_budCapCCR_O_ttl = sqlsrv_fetch_array( $stmt_budCapCCR_O_ttl, SQLSRV_FETCH_ASSOC); //comment out when looping
				//echo $row_budCapCCR_O_ttl['Project_01']

// OPEX Budget CCRB + CR - GREEN
	$sql_budttlCCR_O = "
						SELECT * 
						FROM fn_GetCRPlanSummary ($fundingKey)
						WHERE Src = 'CCRBandCR' and Cat_Type = 'OPEX' and (Category_Key = 4 or Category_Key = 5 or Category_Key = 6)
					 ";
	$stmt_budttlCCR_O = sqlsrv_query( $conn_COXProd, $sql_budttlCCR_O, $params, $options);
	//$rowCount_budttlCCR_O = sqlsrv_num_rows( $stmt_budttlCCR_O );
	//$row_budttlCCR = sqlsrv_fetch_array( $stmt_budttlCCR, SQLSRV_FETCH_ASSOC); //comment out when looping
	//echo $row_budttlCCR['column_name']
	
				// OPEX Budget CCRB + CR TOTAL- GREEN
				$sql_budttlCCR_O_ttl = "
									SELECT SUM(Project_01) AS Project_01,
											SUM(Project_02) AS Project_02,
											SUM(Project_03) AS Project_03,
											SUM(Project_04) AS Project_04,
											SUM(Project_05) AS Project_05,
											SUM(Project_06) AS Project_06,
											SUM(Project_07) AS Project_07,
											SUM(Project_08) AS Project_08,
											SUM(Project_09) AS Project_09,
											SUM(Project_10) AS Project_10,
											SUM(Project_11) AS Project_11,
											SUM(Project_12) AS Project_12
									FROM(
										SELECT * 
										FROM fn_GetCRPlanSummary ($fundingKey)
										WHERE Src = 'CCRBandCR' and Cat_Type = 'OPEX' and (Category_Key = 4 or Category_Key = 5 or Category_Key = 6)
										) AS a
								 ";
				$stmt_budttlCCR_O_ttl = sqlsrv_query( $conn_COXProd, $sql_budttlCCR_O_ttl, $params, $options);
				//$rowCount_budttlCCR_O = sqlsrv_num_rows( $stmt_budttlCCR_O_ttl );
				$row_budttlCCR_O_ttl = sqlsrv_fetch_array( $stmt_budttlCCR_O_ttl, SQLSRV_FETCH_ASSOC); //comment out when looping
				//echo $row_budttlCCR_O_ttl['Project_01']
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Untitled Document</title>
<link rel="shortcut icon" href="../favicon.ico"/>
<?php include ("../includes/load.php");?>
<link href="../jQueryAssets/jquery.ui.core.min.css" rel="stylesheet" type="text/css">
<link href="../jQueryAssets/jquery.ui.theme.min.css" rel="stylesheet" type="text/css">
<link href="../jQueryAssets/jquery.ui.button.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="../colorbox-master/example1/colorbox.css" />
<!-- Bootstrap -->
<!-- <link rel="stylesheet" href="css/bootstrap.css"> -->
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
<link href="../css/bootstrap-3.3.4.css" rel="stylesheet" type="text/css">
<script src="../bootstrap/js/jquery-1.11.2.min.js"></script>
<script src="../js/bootstrap-3.3.4.js" type="text/javascript"></script>
</head>

<body>
<div class="container-fluid">
<h3 align="center">CR Number: <?php echo htmlspecialchars($_GET['sn'])?> / <?php echo htmlspecialchars($_GET['fk'])?> </h3>
<strong>CR INFORMATION</strong>
<div class="row">
  <div class="col-lg-3">
    <table width="100%" border="0" class="table-bordered" style="font-size:11px;">
      <tbody>
        <tr>
          <th width="33%" bgcolor="#00aaf5" scope="row" style="color:#FFFFFF;">CR Type:</th>
          <td><?php echo $row_crs['CR_Type_Des']?></td>
        </tr>
        <tr>
          <th bgcolor="#00aaf5" scope="row" style="color:#FFFFFF;">Program:</th>
          <td><?php echo $row_crs['Program_Nm']?></td>
        </tr>
        <tr>
          <th bgcolor="#00aaf5" scope="row" style="color:#FFFFFF;">PM:</th>
          <td><?php echo $row_crs['CR_PM']?></td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="col-lg-9">
  <table width="100%" border="0"j class="table-bordered" style="font-size:11px">
  <tbody>
    <tr>
      <th width="15%" bgcolor="#00aaf5" style="color:#FFFFFF;" scope="row">CR Name:</th>
      <td><?php echo $row_crs['CR_Nm']?></td>
    </tr>
    <tr>
      <th bgcolor="#00aaf5" scope="row" style="color:#FFFFFF;">Description:</th>
      <td><?php echo $row_crs['CR_Desc']?></td>
    </tr>
    <tr>
      <th bgcolor="#00aaf5" scope="row" style="color:#FFFFFF;">CR Status</th>
      <td><?php echo $row_crs['CR_Status_Abb'] ?></td>
    </tr>
  </tbody>
</table>

  </div>
</div><br>
<strong>OVERALL CHANGES</strong>
<div class="row">
    <div class="col-lg-3"><table width="100%" border="0" class="table-bordered" style="font-size:11px">
  <tbody>
    <tr>
      <th colspan="2" bgcolor="#00aaf5" scope="col" style="color:#FFFFFF;">Over-subcription</th>
      </tr>
    <tr>
      <td width="40%" align="right">Capex:</td>
      <td align="right"><?php echo number_format($row_crsF['Risk_CAPEX'])?></td>
    </tr>
    <tr>
      <td align="right">Opex:</td>
      <td align="right"><?php echo number_format($row_crsF['Risk_OPEX'])?></td>
    </tr>
    <tr>
      <th colspan="2" bgcolor="#00aaf5" scope="col" style="color:#FFFFFF;">PPMs</th>
      </tr>
    <tr>
      <td align="right">Capex:</td>
      <td align="right"><?php $over = $row_crsF['Added_CAPEX'] + $row_crsF['Removed_CAPEX']; echo number_format($over)?></td>
    </tr>
    <tr>
      <td align="right">Opex:</td>
      <td align="right"><?php $ppm = $row_crsF['Added_OPEX'] + $row_crsF['Removed_OPEX']; echo number_format($ppm)?></td>
    </tr>
  </tbody>
</table>
</div>
    <div class="col-lg-3">
    

    </div>
    <div class="col-lg-3">
    
    </div>
    <div class="col-lg-3">

    </div>
  </div>
<br>
<strong>BUDGET CHANGE</strong>
<div class="row">
  <div class="col-lg-12">
    <div role="tabpanel">
      <ul class="nav nav-tabs" role="tablist">
        <li class="active"><a href="#home1" data-toggle="tab" role="tab">Capex</a></li>
        <li><a href="#paneTwo1" data-toggle="tab" role="tab">Opex</a></li>
      </ul>
      <div id="tabContent1" class="tab-content">
        <div class="tab-pane fade in active" id="home1">
          <p>
          <?php // if($row_crs['CR_Status_Abb'] =='Approved') { ?>
          <!--<div class="alert-danger" align="center">Because the CR has been Approved, the 'Current Program Plan' contains ALSO the CR Changes. </div>-->
          <?php // } ?>
  <table width="100%" border="0" class="table-bordered" style="font-size:11px">
  <tbody>
        <tr>
          <th width="100" align="center" scope="row">&nbsp;</th>
          <td width="10%">&nbsp;</td>
          <td width="5%" align="center"><strong>Jan</strong></td>
          <td width="5%" align="center"><strong>Feb</strong></td>
          <td width="5%" align="center"><strong>Mar</strong></td>
          <td width="5%" align="center"><strong>Apr</strong></td>
          <td width="5%" align="center"><strong>May</strong></td>
          <td width="5%" align="center"><strong>Jun</strong></td>
          <td width="5%" align="center"><strong>Jul</strong></td>
          <td width="5%" align="center"><strong>Aug</strong></td>
          <td width="5%" align="center"><strong>Sep</strong></td>
          <td width="5%" align="center"><strong>Oct</strong></td>
          <td width="5%" align="center"><strong>Nov</strong></td>
          <td width="5%" align="center"><strong>Dec</strong></td>
          <td width="5%" align="center"><strong>Total</strong></td>
        </tr>
        <tr>
          <th rowspan="4" align="center" bgcolor="#95b3d7" scope="row" style="color:#FFFFFF"><div align="center">Current Plan for the Program <?php  echo $rowCount_budSubCCR ;?></div></th>
<?php //if($row_crs['CR_Status_Abb'] != 'Approved'){ // show normal blue?>
		<?php while($row_budSubCCR = sqlsrv_fetch_array( $stmt_budSubCCR, SQLSRV_FETCH_ASSOC)) { ?>
          
          <td align="center" bgcolor="#b8cce4"><?php echo $row_budSubCCR['Category_Name']?></td>
          <td align="right" bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR['Project_01'])?></td>
          <td align="right" bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR['Project_02'])?></td>
          <td align="right" bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR['Project_03'])?></td>
          <td align="right" bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR['Project_04'])?></td>
          <td align="right" bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR['Project_05'])?></td>
          <td align="right" bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR['Project_06'])?></td>
          <td align="right" bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR['Project_07'])?></td>
          <td align="right" bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR['Project_08'])?></td>
          <td align="right" bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR['Project_09'])?></td>
          <td align="right" bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR['Project_10'])?></td>
          <td align="right" bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR['Project_11'])?></td>
          <td align="right" bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR['Project_12'])?></td>
          <td align="right" bgcolor="#b8cce4"><strong>
            <?php $sumSubCCR =
		  											  $row_budSubCCR['Project_01']
													+ $row_budSubCCR['Project_02']
													+ $row_budSubCCR['Project_03']
													+ $row_budSubCCR['Project_04']
													+ $row_budSubCCR['Project_05']
													+ $row_budSubCCR['Project_06']
													+ $row_budSubCCR['Project_07']
													+ $row_budSubCCR['Project_08']
													+ $row_budSubCCR['Project_09']
													+ $row_budSubCCR['Project_10']
													+ $row_budSubCCR['Project_11']
													+ $row_budSubCCR['Project_12'];
													echo FmtNum($sumSubCCR);
		  									 ?>
          </strong></td>
        </tr>
       
      <?php } ?>
      <?php if($rowCount_budSubCCR == 2) {?>
      	
          <td align="center" bgcolor="#b8cce4">---</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
        </tr>
      <?php } else if($rowCount_budSubCCR == 1) {?>
      	
          <td align="center" bgcolor="#b8cce4">---</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
        </tr>
        <tr>
          <td align="center" bgcolor="#b8cce4">---</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
        </tr>
      
      <?php } else if($rowCount_budSubCCR == 0) {?>
       	
          <td align="center" bgcolor="#b8cce4">Material</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
        </tr>
        <tr>
          <td align="center" bgcolor="#b8cce4">In-House Labor</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
        </tr>
        <tr>
          <td align="center" bgcolor="#b8cce4">Contract Labor</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
          <td align="right" bgcolor="#b8cce4">0</td>
        </tr>
      
      <?php } ?>
      
      <tr>
          <td align="center" bgcolor="#95b3d7" style="color:#FFFFFF">Total Plan</td>
          <td align="right" bgcolor="#95b3d7"><strong><?php echo FmtNum($row_budSubCCR_ttl['jan']) ?></strong></td>
          <td align="right" bgcolor="#95b3d7"><strong><?php echo FmtNum($row_budSubCCR_ttl['feb']) ?></strong></td>
          <td align="right" bgcolor="#95b3d7"><strong><?php echo FmtNum($row_budSubCCR_ttl['mar']) ?></strong></td>
          <td align="right" bgcolor="#95b3d7"><strong><?php echo FmtNum($row_budSubCCR_ttl['apr']) ?></strong></td>
          <td align="right" bgcolor="#95b3d7"><strong><?php echo FmtNum($row_budSubCCR_ttl['may']) ?></strong></td>
          <td align="right" bgcolor="#95b3d7"><strong><?php echo FmtNum($row_budSubCCR_ttl['jun']) ?></strong></td>
          <td align="right" bgcolor="#95b3d7"><strong><?php echo FmtNum($row_budSubCCR_ttl['jul']) ?></strong></td>
          <td align="right" bgcolor="#95b3d7"><strong><?php echo FmtNum($row_budSubCCR_ttl['aug']) ?></strong></td>
          <td align="right" bgcolor="#95b3d7"><strong><?php echo FmtNum($row_budSubCCR_ttl['sep']) ?></strong></td>
          <td align="right" bgcolor="#95b3d7"><strong><?php echo FmtNum($row_budSubCCR_ttl['oct']) ?></strong></td>
          <td align="right" bgcolor="#95b3d7"><strong><?php echo FmtNum($row_budSubCCR_ttl['nov']) ?></strong></td>
          <td align="right" bgcolor="#95b3d7"><strong><?php echo FmtNum($row_budSubCCR_ttl['dec']) ?></strong></td>
          <td align="right" bgcolor="#95b3d7"><strong>
            <?php $budSubCCR_ttl =  $row_budSubCCR_ttl['jan'] +
														$row_budSubCCR_ttl['feb'] +
														$row_budSubCCR_ttl['mar'] +
														$row_budSubCCR_ttl['apr'] +
														$row_budSubCCR_ttl['may'] +
														$row_budSubCCR_ttl['jun'] +
														$row_budSubCCR_ttl['jul'] +
														$row_budSubCCR_ttl['aug'] +
														$row_budSubCCR_ttl['sep'] +
														$row_budSubCCR_ttl['oct'] +
														$row_budSubCCR_ttl['nov'] +
														$row_budSubCCR_ttl['dec'] ;
														
		  							  echo FmtNum($budSubCCR_ttl);
								?>
          </strong></td>
        </tr>
     
     <?php //} else { //show what is in red?>
     <?php //} // end red section ?>
        <tr>
          <th width="100" rowspan="4" align="center" bgcolor="#cc3300" scope="row" style="color:#FFFFFF"><div align="center">Submitted CR(s) for the Program <?php //echo $rowCount_budCapCCR ?></div></th>
        <?php //if($row_crs['CR_Status_Abb'] != 'Approved'){ // show normal red?>
		<?php while($row_budCapCCR = sqlsrv_fetch_array( $stmt_budCapCCR, SQLSRV_FETCH_ASSOC)) { ?>
          <td align="center" bgcolor="#da9694"><?php echo $row_budCapCCR['Category_Name']?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR['Project_01'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR['Project_02'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR['Project_03'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR['Project_04'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR['Project_05'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR['Project_06'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR['Project_07'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR['Project_08'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR['Project_09'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR['Project_10'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR['Project_11'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR['Project_12'])?></td>
          <td align="right" bgcolor="#da9694"><strong>
            <?php $sumCapCCR =
		  											  $row_budCapCCR['Project_01']
													+ $row_budCapCCR['Project_02']
													+ $row_budCapCCR['Project_03']
													+ $row_budCapCCR['Project_04']
													+ $row_budCapCCR['Project_05']
													+ $row_budCapCCR['Project_06']
													+ $row_budCapCCR['Project_07']
													+ $row_budCapCCR['Project_08']
													+ $row_budCapCCR['Project_09']
													+ $row_budCapCCR['Project_10']
													+ $row_budCapCCR['Project_11']
													+ $row_budCapCCR['Project_12'];
													echo FmtNum($sumCapCCR);
		  									  ?>
          </strong></td>
        </tr>
        
        <?php } ?>
        <?php if($rowCount_budCapCCR == 2) {?>
        
          <td align="center" bgcolor="#da9694">---</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
        </tr>
		
		<?php } else if($rowCount_budCapCCR == 1) { ?>
        
          <td align="center" bgcolor="#da9694">---</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
        </tr>
        <tr>
          <td align="center" bgcolor="#da9694">---</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
        </tr>
        
        <?php } else if($rowCount_budCapCCR == 0) { ?>
       
          <td align="center" bgcolor="#da9694">Material</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
        </tr>
                <tr>
          <td align="center" bgcolor="#da9694">In-House Labor</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
        </tr>
                <tr>
          <td align="center" bgcolor="#da9694">Contract Labor</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
        </tr>
        
        <?php } ?>
        
        <tr>
          <td align="center" bgcolor="#cc3300" style="color:#FFFFFF">Total Plan</td>
          <td align="right" bgcolor="#cc3300"><strong><?php echo FmtNum($row_budCapCCR_ttl['jan'])?></strong></td>
          <td align="right" bgcolor="#cc3300"><strong><?php echo FmtNum($row_budCapCCR_ttl['feb'])?></strong></td>
          <td align="right" bgcolor="#cc3300"><strong><?php echo FmtNum($row_budCapCCR_ttl['mar'])?></strong></td>
          <td align="right" bgcolor="#cc3300"><strong><?php echo FmtNum($row_budCapCCR_ttl['apr'])?></strong></td>
          <td align="right" bgcolor="#cc3300"><strong><?php echo FmtNum($row_budCapCCR_ttl['may'])?></strong></td>
          <td align="right" bgcolor="#cc3300"><strong><?php echo FmtNum($row_budCapCCR_ttl['jun'])?></strong></td>
          <td align="right" bgcolor="#cc3300"><strong><?php echo FmtNum($row_budCapCCR_ttl['jul'])?></strong></td>
          <td align="right" bgcolor="#cc3300"><strong><?php echo FmtNum($row_budCapCCR_ttl['aug'])?></strong></td>
          <td align="right" bgcolor="#cc3300"><strong><?php echo FmtNum($row_budCapCCR_ttl['sep'])?></strong></td>
          <td align="right" bgcolor="#cc3300"><strong><?php echo FmtNum($row_budCapCCR_ttl['oct'])?></strong></td>
          <td align="right" bgcolor="#cc3300"><strong><?php echo FmtNum($row_budCapCCR_ttl['nov'])?></strong></td>
          <td align="right" bgcolor="#cc3300"><strong><?php echo FmtNum($row_budCapCCR_ttl['dec'])?></strong></td>
          <td align="right" bgcolor="#cc3300"><strong>
            								<?php  $budCapCCR = $row_budCapCCR_ttl['jan'] +
													$row_budCapCCR_ttl['feb'] +
													$row_budCapCCR_ttl['mar'] +
													$row_budCapCCR_ttl['apr'] +
													$row_budCapCCR_ttl['may'] +
													$row_budCapCCR_ttl['jun'] +
													$row_budCapCCR_ttl['jul'] +
													$row_budCapCCR_ttl['aug'] +
													$row_budCapCCR_ttl['sep'] +
													$row_budCapCCR_ttl['oct'] +
													$row_budCapCCR_ttl['nov'] +
													$row_budCapCCR_ttl['dec'];
		  
		  											echo FmtNum($budCapCCR) ?>
          </strong></td>
        </tr>
        <tr>
          <th rowspan="4" align="center" bgcolor="#76933c" scope="row" style="color:#FFFFFF"><div align="center">CCRB + CR <?php echo $rowCount_budttlCCR; ?></div></th>
<?php //if($row_crs['CR_Status_Abb'] != 'Approved'){ // show normal green?>
		<?php while($row_budttlCCR = sqlsrv_fetch_array( $stmt_budttlCCR, SQLSRV_FETCH_ASSOC)) { ?>
          <td align="center" bgcolor="#c4d79b"><?php echo $row_budttlCCR['Category_Name']?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR['Project_01'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR['Project_02'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR['Project_03'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR['Project_04'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR['Project_05'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR['Project_06'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR['Project_07'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR['Project_08'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR['Project_09'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR['Project_10'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR['Project_11'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR['Project_12'])?></td>
          <td align="right" bgcolor="#c4d79b"><strong>
            <?php $sumttlCCR =
		  											  $row_budttlCCR['Project_01']
													+ $row_budttlCCR['Project_02']
													+ $row_budttlCCR['Project_03']
													+ $row_budttlCCR['Project_04']
													+ $row_budttlCCR['Project_05']
													+ $row_budttlCCR['Project_06']
													+ $row_budttlCCR['Project_07']
													+ $row_budttlCCR['Project_08']
													+ $row_budttlCCR['Project_09']
													+ $row_budttlCCR['Project_10']
													+ $row_budttlCCR['Project_11']
													+ $row_budttlCCR['Project_12'];
													echo FmtNum($sumttlCCR);
		  									  ?>
          </strong></td>
        </tr>
       <?php } ?>
       <?php if($rowCount_budttlCCR == 2) {?>
       <td align="center" bgcolor="#c4d79b">--</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b"><strong>0</strong></td>
        </tr>
       <?php } else if($rowCount_budttlCCR == 1) {?>
       
          <td align="center" bgcolor="#c4d79b">--</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b"><strong>0</strong></td>
        </tr>
        <tr>
          <td align="center" bgcolor="#c4d79b">--</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b"><strong>0</strong></td>
        </tr>
       
       <?php } else if($rowCount_budttlCCR == 0) {?>
       
          <td align="center" bgcolor="#c4d79b">Material</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b"><strong>0</strong></td>
        </tr>
        <tr>
          <td align="center" bgcolor="#c4d79b">In-House Labor</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b"><strong>0</strong></td>
        </tr>
        <tr>
          <td align="center" bgcolor="#c4d79b">Contract Labor</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b"><strong>0</strong></td>
        </tr>
       <?php } ?>
       
       
 	  <tr>
          <td align="center" bgcolor="#76933c" style="color:#FFFFFF">Total Plan</td>
          <td align="right" bgcolor="#76933c"><strong><?php echo FmtNum($row_budttlCCR_ttl['jan'])?></strong></td>
          <td align="right" bgcolor="#76933c"><strong><?php echo FmtNum($row_budttlCCR_ttl['feb'])?></strong></td>
          <td align="right" bgcolor="#76933c"><strong><?php echo FmtNum($row_budttlCCR_ttl['mar'])?></strong></td>
          <td align="right" bgcolor="#76933c"><strong><?php echo FmtNum($row_budttlCCR_ttl['apr'])?></strong></td>
          <td align="right" bgcolor="#76933c"><strong><?php echo FmtNum($row_budttlCCR_ttl['may'])?></strong></td>
          <td align="right" bgcolor="#76933c"><strong><?php echo FmtNum($row_budttlCCR_ttl['jun'])?></strong></td>
          <td align="right" bgcolor="#76933c"><strong><?php echo FmtNum($row_budttlCCR_ttl['jul'])?></strong></td>
          <td align="right" bgcolor="#76933c"><strong><?php echo FmtNum($row_budttlCCR_ttl['aug'])?></strong></td>
          <td align="right" bgcolor="#76933c"><strong><?php echo FmtNum($row_budttlCCR_ttl['sep'])?></strong></td>
          <td align="right" bgcolor="#76933c"><strong><?php echo FmtNum($row_budttlCCR_ttl['oct'])?></strong></td>
          <td align="right" bgcolor="#76933c"><strong><?php echo FmtNum($row_budttlCCR_ttl['nov'])?></strong></td>
          <td align="right" bgcolor="#76933c"><strong><?php echo FmtNum($row_budttlCCR_ttl['dec'])?></strong></td>
          <td align="right" bgcolor="#76933c"><strong>
            <?php $budttlCCR = $row_budttlCCR_ttl['jan'] +
													$row_budttlCCR_ttl['feb'] +
													$row_budttlCCR_ttl['mar'] +
													$row_budttlCCR_ttl['apr'] +
													$row_budttlCCR_ttl['may'] +
													$row_budttlCCR_ttl['jun'] +
													$row_budttlCCR_ttl['jul'] +
													$row_budttlCCR_ttl['aug'] +
													$row_budttlCCR_ttl['sep'] +
													$row_budttlCCR_ttl['oct'] +
													$row_budttlCCR_ttl['nov'] +
													$row_budttlCCR_ttl['dec'] ;
																									
										echo FmtNum($budttlCCR)?>
          </strong></td>
        </tr>
               
<?php // } else { ?>
		
          <!--<td align="center" bgcolor="#c4d79b">Material</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b"><strong>0</strong></td>
        </tr>
        <tr>
          <td align="center" bgcolor="#c4d79b">In-House Labor</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b"><strong>0</strong></td>
        </tr>
        <tr>
          <td align="center" bgcolor="#c4d79b">Contract Labor</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b"><strong>0</strong></td>
        </tr>
      
 	  <tr>
          <td align="center" bgcolor="#76933c" style="color:#FFFFFF">Total Plan</td>
          <td align="right" bgcolor="#76933c"><strong>0</strong></td>
          <td align="right" bgcolor="#76933c"><strong>0</strong></td>
          <td align="right" bgcolor="#76933c"><strong>0</strong></td>
          <td align="right" bgcolor="#76933c"><strong>0</strong></td>
          <td align="right" bgcolor="#76933c"><strong>0</strong></td>
          <td align="right" bgcolor="#76933c"><strong>0</strong></td>
          <td align="right" bgcolor="#76933c"><strong>0</strong></td>
          <td align="right" bgcolor="#76933c"><strong>0</strong></td>
          <td align="right" bgcolor="#76933c"><strong>0</strong></td>
          <td align="right" bgcolor="#76933c"><strong>0</strong></td>
          <td align="right" bgcolor="#76933c"><strong>0</strong></td>
          <td align="right" bgcolor="#76933c"><strong>0</strong></td>
          <td align="right" bgcolor="#76933c"><strong>0</strong></td>
        </tr>-->

<?php // }  //end green section?>
      </tbody>
  </table></p>
        </div>
        <div class="tab-pane fade" id="paneTwo1">
          <p>
          
  <!--Opex Table-->   
        <?php if($row_crs['CR_Status_Abb'] =='Approved') { ?>
          <div class="alert-danger" align="center">Because the CR has been Approved, the 'Current Program Plan' contains ALSO the CR Changes. </div>
        <?php } ?> 
  <table width="100%" border="0" class="table-bordered" style="font-size:11px">
  <tbody>
        <tr>
          <th width="100" align="center" scope="row">&nbsp;</th>
          <td width="10%">&nbsp;</td>
          <td width="5%" align="center"><strong>Jan</strong></td>
          <td width="5%" align="center"><strong>Feb</strong></td>
          <td width="5%" align="center"><strong>Mar</strong></td>
          <td width="5%" align="center"><strong>Apr</strong></td>
          <td width="5%" align="center"><strong>May</strong></td>
          <td width="5%" align="center"><strong>Jun</strong></td>
          <td width="5%" align="center"><strong>Jul</strong></td>
          <td width="5%" align="center"><strong>Aug</strong></td>
          <td width="5%" align="center"><strong>Sep</strong></td>
          <td width="5%" align="center"><strong>Oct</strong></td>
          <td width="5%" align="center"><strong>Nov</strong></td>
          <td width="5%" align="center"><strong>Dec</strong></td>
          <td width="5%" align="center"><strong>Total</strong></td>
        </tr>
        <tr>
          <th rowspan="4" align="center" bgcolor="#95b3d7" scope="row" style="color:#FFFFFF"><div align="center">Current Plan for the Program <?php // echo $rowCount_budSubCCR_O ;?></div></th>
<?php // if($row_crs['CR_Status_Abb'] != 'Approved'){ // show normal blue?>
		<?php while($row_budSubCCR_O = sqlsrv_fetch_array( $stmt_budSubCCR_O, SQLSRV_FETCH_ASSOC)) { ?>
          <td align="center" bgcolor="#b8cce4"><?php echo $row_budSubCCR_O['Category_Name']?></td>
          <td align="right" bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR_O['Project_01'])?></td>
          <td align="right" bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR_O['Project_02'])?></td>
          <td align="right" bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR_O['Project_03'])?></td>
          <td align="right" bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR_O['Project_04'])?></td>
          <td align="right" bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR_O['Project_05'])?></td>
          <td align="right" bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR_O['Project_06'])?></td>
          <td align="right" bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR_O['Project_07'])?></td>
          <td align="right" bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR_O['Project_08'])?></td>
          <td align="right" bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR_O['Project_09'])?></td>
          <td align="right" bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR_O['Project_10'])?></td>
          <td align="right" bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR_O['Project_11'])?></td>
          <td align="right" bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR_O['Project_12'])?></td>
          <td align="right" bgcolor="#b8cce4"><?php $sumSubCCR_O =
		  											  $row_budSubCCR_O['Project_01']
													+ $row_budSubCCR_O['Project_02']
													+ $row_budSubCCR_O['Project_03']
													+ $row_budSubCCR_O['Project_04']
													+ $row_budSubCCR_O['Project_05']
													+ $row_budSubCCR_O['Project_06']
													+ $row_budSubCCR_O['Project_07']
													+ $row_budSubCCR_O['Project_08']
													+ $row_budSubCCR_O['Project_09']
													+ $row_budSubCCR_O['Project_10']
													+ $row_budSubCCR_O['Project_11']
													+ $row_budSubCCR_O['Project_12'];
													echo FmtNum($sumSubCCR_O);
		  									 ?>
          </td>
        </tr>
       
      <?php } ?>
      
        <tr>
          <td align="center" bgcolor="#95b3d7" style="color:#FFFFFF">Total Plan</td>
          <td bgcolor="#95b3d7" align="right"><strong><?php echo FmtNum($row_budSubCCR_O_ttl['Project_01']) ?></strong></td>
          <td bgcolor="#95b3d7" align="right"><strong><?php echo FmtNum($row_budSubCCR_O_ttl['Project_02']) ?></strong></td>
          <td bgcolor="#95b3d7" align="right"><strong><?php echo FmtNum($row_budSubCCR_O_ttl['Project_03']) ?></strong></td>
          <td bgcolor="#95b3d7" align="right"><strong><?php echo FmtNum($row_budSubCCR_O_ttl['Project_04']) ?></strong></td>
          <td bgcolor="#95b3d7" align="right"><strong><?php echo FmtNum($row_budSubCCR_O_ttl['Project_05']) ?></strong></td>
          <td bgcolor="#95b3d7" align="right"><strong><?php echo FmtNum($row_budSubCCR_O_ttl['Project_06']) ?></strong></td>
          <td bgcolor="#95b3d7" align="right"><strong><?php echo FmtNum($row_budSubCCR_O_ttl['Project_07']) ?></strong></td>
          <td bgcolor="#95b3d7" align="right"><strong><?php echo FmtNum($row_budSubCCR_O_ttl['Project_08']) ?></strong></td>
          <td bgcolor="#95b3d7" align="right"><strong><?php echo FmtNum($row_budSubCCR_O_ttl['Project_09']) ?></strong></td>
          <td bgcolor="#95b3d7" align="right"><strong><?php echo FmtNum($row_budSubCCR_O_ttl['Project_10']) ?></strong></td>
          <td bgcolor="#95b3d7" align="right"><strong><?php echo FmtNum($row_budSubCCR_O_ttl['Project_11']) ?></strong></td>
          <td bgcolor="#95b3d7" align="right"><strong><?php echo FmtNum($row_budSubCCR_O_ttl['Project_12']) ?></strong></td>
          <td bgcolor="#95b3d7" align="right"><strong>
            							<?php $row_budSubCCR_O_ttl_g =
		  									$row_budSubCCR_O_ttl['Project_01'] +
											$row_budSubCCR_O_ttl['Project_02'] +
											$row_budSubCCR_O_ttl['Project_03'] +
											$row_budSubCCR_O_ttl['Project_04'] +
											$row_budSubCCR_O_ttl['Project_05'] +
											$row_budSubCCR_O_ttl['Project_06'] +
											$row_budSubCCR_O_ttl['Project_07'] +
											$row_budSubCCR_O_ttl['Project_08'] +
											$row_budSubCCR_O_ttl['Project_09'] +
											$row_budSubCCR_O_ttl['Project_10'] +
											$row_budSubCCR_O_ttl['Project_11'] +
											$row_budSubCCR_O_ttl['Project_12'] ;
											
											echo FmtNum($row_budSubCCR_O_ttl_g)
											?>
                                            </strong>
                                            </td>
        </tr>
<?php // } else { // show RED cells?>
<?php // } // end current OPEX Current Plan?>
        <tr>
          <th width="100" rowspan="4" align="center" bgcolor="#cc3300" scope="row" style="color:#FFFFFF"><div align="center">Submitted CR(s) for the Program<?php //echo $rowCount_budCapCCR_O ?></div></th>
<?php // if($row_crs['CR_Status_Abb'] != 'Approved'){ // show normal red OPEX?>        
		<?php while($row_budCapCCR_O = sqlsrv_fetch_array( $stmt_budCapCCR_O, SQLSRV_FETCH_ASSOC)) { ?>
          <td align="center" bgcolor="#da9694"><?php echo $row_budCapCCR_O['Category_Name']?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR_O['Project_01'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR_O['Project_02'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR_O['Project_03'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR_O['Project_04'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR_O['Project_05'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR_O['Project_06'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR_O['Project_07'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR_O['Project_08'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR_O['Project_09'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR_O['Project_10'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR_O['Project_11'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR_O['Project_12'])?></td>
          <td align="right" bgcolor="#da9694"><?php $sumCapCCR_O =
		  											  $row_budCapCCR_O['Project_01']
													+ $row_budCapCCR_O['Project_02']
													+ $row_budCapCCR_O['Project_03']
													+ $row_budCapCCR_O['Project_04']
													+ $row_budCapCCR_O['Project_05']
													+ $row_budCapCCR_O['Project_06']
													+ $row_budCapCCR_O['Project_07']
													+ $row_budCapCCR_O['Project_08']
													+ $row_budCapCCR_O['Project_09']
													+ $row_budCapCCR_O['Project_10']
													+ $row_budCapCCR_O['Project_11']
													+ $row_budCapCCR_O['Project_12'];
													echo FmtNum($sumCapCCR_O);
		  									  ?>
          </td>
        </tr>
        
        <?php } ?>
         <tr>
          <td align="center" bgcolor="#cc3300" style="color:#FFFFFF">Total Plan</td>
          <td bgcolor="#cc3300" align="right"><strong><?php echo FmtNum($row_budCapCCR_O_ttl['Project_01']) ?></strong></td>
          <td bgcolor="#cc3300" align="right"><strong><?php echo FmtNum($row_budCapCCR_O_ttl['Project_02']) ?></strong></td>
          <td bgcolor="#cc3300" align="right"><strong><?php echo FmtNum($row_budCapCCR_O_ttl['Project_03']) ?></strong></td>
          <td bgcolor="#cc3300" align="right"><strong><?php echo FmtNum($row_budCapCCR_O_ttl['Project_04']) ?></strong></td>
          <td bgcolor="#cc3300" align="right"><strong><?php echo FmtNum($row_budCapCCR_O_ttl['Project_05']) ?></strong></td>
          <td bgcolor="#cc3300" align="right"><strong><?php echo FmtNum($row_budCapCCR_O_ttl['Project_06']) ?></strong></td>
          <td bgcolor="#cc3300" align="right"><strong><?php echo FmtNum($row_budCapCCR_O_ttl['Project_07']) ?></strong></td>
          <td bgcolor="#cc3300" align="right"><strong><?php echo FmtNum($row_budCapCCR_O_ttl['Project_08']) ?></strong></td>
          <td bgcolor="#cc3300" align="right"><strong><?php echo FmtNum($row_budCapCCR_O_ttl['Project_09']) ?></strong></td>
          <td bgcolor="#cc3300" align="right"><strong><?php echo FmtNum($row_budCapCCR_O_ttl['Project_10']) ?></strong></td>
          <td bgcolor="#cc3300" align="right"><strong><?php echo FmtNum($row_budCapCCR_O_ttl['Project_11']) ?></strong></td>
          <td bgcolor="#cc3300" align="right"><strong><?php echo FmtNum($row_budCapCCR_O_ttl['Project_12']) ?></strong></td>
          <td bgcolor="#cc3300" align="right"><strong><?php $row_budCapCCR_O_ttl_g=
		  											  $row_budCapCCR_O_ttl['Project_01']
													+ $row_budCapCCR_O_ttl['Project_02']
													+ $row_budCapCCR_O_ttl['Project_03']
													+ $row_budCapCCR_O_ttl['Project_04']
													+ $row_budCapCCR_O_ttl['Project_05']
													+ $row_budCapCCR_O_ttl['Project_06']
													+ $row_budCapCCR_O_ttl['Project_07']
													+ $row_budCapCCR_O_ttl['Project_08']
													+ $row_budCapCCR_O_ttl['Project_09']
													+ $row_budCapCCR_O_ttl['Project_10']
													+ $row_budCapCCR_O_ttl['Project_11']
													+ $row_budCapCCR_O_ttl['Project_12'];
													echo FmtNum($row_budCapCCR_O_ttl_g);
		  									  ?></strong></td>
        </tr>
<?php // } else { //show red 0's?>
		<!--<td align="center" bgcolor="#da9694">Material</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694"><strong>0</strong></td>
        </tr>
        <tr>
          <td align="center" bgcolor="#da9694">In-House Labor</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694"><strong>0</strong></td>
        </tr>
        <tr>
          <td align="center" bgcolor="#da9694">Contract Labor</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694">0</td>
          <td align="right" bgcolor="#da9694"><strong>0</strong></td>
        </tr>
         <tr>
          <td align="center" bgcolor="#cc3300" style="color:#FFFFFF">Total Plan</td>
          <td align="right" bgcolor="#cc3300"><strong>0</strong></td>
          <td align="right" bgcolor="#cc3300"><strong>0</strong></td>
          <td align="right" bgcolor="#cc3300"><strong>0</strong></td>
          <td align="right" bgcolor="#cc3300"><strong>0</strong></td>
          <td align="right" bgcolor="#cc3300"><strong>0</strong></td>
          <td align="right" bgcolor="#cc3300"><strong>0</strong></td>
          <td align="right" bgcolor="#cc3300"><strong>0</strong></td>
          <td align="right" bgcolor="#cc3300"><strong>0</strong></td>
          <td align="right" bgcolor="#cc3300"><strong>0</strong></td>
          <td align="right" bgcolor="#cc3300"><strong>0</strong></td>
          <td align="right" bgcolor="#cc3300"><strong>0</strong></td>
          <td align="right" bgcolor="#cc3300"><strong>0</strong></td>
          <td align="right" bgcolor="#cc3300"><strong>0</strong></td>
        </tr>-->
<?php // } // end CR?>
        <tr>
          <th rowspan="4" align="center" bgcolor="#76933c" scope="row" style="color:#FFFFFF"><div align="center">CCR_OB + CR <?php // echo $rowCount_budttlCCR_O; ?></div></th>
<?php if($row_crs['CR_Status_Abb'] != 'Approved'){ // show normal Green cells?>        
		<?php while($row_budttlCCR_O = sqlsrv_fetch_array( $stmt_budttlCCR_O, SQLSRV_FETCH_ASSOC)) { ?>
          <td align="center" bgcolor="#c4d79b"><?php echo $row_budttlCCR_O['Category_Name']?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR_O['Project_01'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR_O['Project_02'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR_O['Project_03'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR_O['Project_04'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR_O['Project_05'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR_O['Project_06'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR_O['Project_07'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR_O['Project_08'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR_O['Project_09'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR_O['Project_10'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR_O['Project_11'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR_O['Project_12'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php $sumttlCCR_O =
		  											  $row_budttlCCR_O['Project_01']
													+ $row_budttlCCR_O['Project_02']
													+ $row_budttlCCR_O['Project_03']
													+ $row_budttlCCR_O['Project_04']
													+ $row_budttlCCR_O['Project_05']
													+ $row_budttlCCR_O['Project_06']
													+ $row_budttlCCR_O['Project_07']
													+ $row_budttlCCR_O['Project_08']
													+ $row_budttlCCR_O['Project_09']
													+ $row_budttlCCR_O['Project_10']
													+ $row_budttlCCR_O['Project_11']
													+ $row_budttlCCR_O['Project_12'];
													echo FmtNum($sumttlCCR_O);
		  									  ?>
          </td>
        </tr>
       <?php } ?>
 	   <tr>
          <td align="center" bgcolor="#76933c" style="color:#FFFFFF">Total Plan</td>
          <td bgcolor="#76933c" align="right"><strong><?php FmtNum($row_budttlCCR_O_ttl['Project_01'])?></strong></td>
          <td bgcolor="#76933c" align="right"><strong><?php FmtNum($row_budttlCCR_O_ttl['Project_02'])?></strong></td>
          <td bgcolor="#76933c" align="right"><strong><?php FmtNum($row_budttlCCR_O_ttl['Project_03'])?></strong></td>
          <td bgcolor="#76933c" align="right"><strong><?php FmtNum($row_budttlCCR_O_ttl['Project_04'])?></strong></td>
          <td bgcolor="#76933c" align="right"><strong><?php FmtNum($row_budttlCCR_O_ttl['Project_05'])?></strong></td>
          <td bgcolor="#76933c" align="right"><strong><?php FmtNum($row_budttlCCR_O_ttl['Project_06'])?></strong></td>
          <td bgcolor="#76933c" align="right"><strong><?php FmtNum($row_budttlCCR_O_ttl['Project_07'])?></strong></td>
          <td bgcolor="#76933c" align="right"><strong><?php FmtNum($row_budttlCCR_O_ttl['Project_08'])?></strong></td>
          <td bgcolor="#76933c" align="right"><strong><?php FmtNum($row_budttlCCR_O_ttl['Project_09'])?></strong></td>
          <td bgcolor="#76933c" align="right"><strong><?php FmtNum($row_budttlCCR_O_ttl['Project_10'])?></strong></td>
          <td bgcolor="#76933c" align="right"><strong><?php FmtNum($row_budttlCCR_O_ttl['Project_11'])?></strong></td>
          <td bgcolor="#76933c" align="right"><strong><?php FmtNum($row_budttlCCR_O_ttl['Project_12'])?></strong></td>
          <td bgcolor="#76933c" align="right"><strong><?php $row_budttlCCR_O_ttl_g =
															  $row_budttlCCR_O_ttl['Project_01']
															+ $row_budttlCCR_O_ttl['Project_02']
															+ $row_budttlCCR_O_ttl['Project_03']
															+ $row_budttlCCR_O_ttl['Project_04']
															+ $row_budttlCCR_O_ttl['Project_05']
															+ $row_budttlCCR_O_ttl['Project_06']
															+ $row_budttlCCR_O_ttl['Project_07']
															+ $row_budttlCCR_O_ttl['Project_08']
															+ $row_budttlCCR_O_ttl['Project_09']
															+ $row_budttlCCR_O_ttl['Project_10']
															+ $row_budttlCCR_O_ttl['Project_11']
															+ $row_budttlCCR_O_ttl['Project_12'];
															echo FmtNum($row_budttlCCR_O_ttl_g);
		  									  			?>
                                              </strong>
                                              </td>
        </tr>
<?php } else { // show 0's in green cells?>
		<td align="center" bgcolor="#c4d79b">Material</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b"><strong>0</strong></td>
        </tr>
        <tr>
          <td align="center" bgcolor="#c4d79b">In-House Labor</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b"><strong>0</strong></td>
        </tr>
        <tr>
          <td align="center" bgcolor="#c4d79b">Contract Labor</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b">0</td>
          <td align="right" bgcolor="#c4d79b"><strong>0</strong></td>
        </tr>
      
 	  <tr>
          <td align="center" bgcolor="#76933c" style="color:#FFFFFF">Total Plan</td>
          <td align="right" bgcolor="#76933c"><strong>0</strong></td>
          <td align="right" bgcolor="#76933c"><strong>0</strong></td>
          <td align="right" bgcolor="#76933c"><strong>0</strong></td>
          <td align="right" bgcolor="#76933c"><strong>0</strong></td>
          <td align="right" bgcolor="#76933c"><strong>0</strong></td>
          <td align="right" bgcolor="#76933c"><strong>0</strong></td>
          <td align="right" bgcolor="#76933c"><strong>0</strong></td>
          <td align="right" bgcolor="#76933c"><strong>0</strong></td>
          <td align="right" bgcolor="#76933c"><strong>0</strong></td>
          <td align="right" bgcolor="#76933c"><strong>0</strong></td>
          <td align="right" bgcolor="#76933c"><strong>0</strong></td>
          <td align="right" bgcolor="#76933c"><strong>0</strong></td>
          <td align="right" bgcolor="#76933c"><strong>0</strong></td>
        </tr>
<?php } // end CR + Current Plan?>
      </tbody>
  </table>
          
          </p>
        </div>
       </div>
    </div>

  </div>
</div><br>
<div class="row">
    <div class="col-lg-6"><strong>PLAN OF RECORD</strong></div>
    <div class="col-lg-6">
    	          <table cellspacing="1" cellpadding="3" style="font-size:9px" class="table-bordered" width="100%">
                     <tr>
                       <td colspan="5" align="center" style="font-size:11px"><strong>POR COLOR LEGEND</strong></td>
                     </tr>
                     <tr>
                        <td width="150" align="center" bgcolor="#fcd12a" ><span  style="padding:3">Need by Date</span></td>
                        <td width="120" align="center" bgcolor="#c1c1c1" >Range of Shipping to Activation</td>
                        <td width="120" align="center" bgcolor="#00aaf5" >To Activation Date</td>
                        <td width="120" align="center" bgcolor="#00d257" >To Migration Date</td>
                        <td width="120" align="center" >&#8226; Initial Date from Shipping to Activation</td>
                     </tr>
                  </table>
    	          <br>
    </div>
</div>

<div class="row">
  <div class="col-lg-12">
<?php 
if($_GET['year'] == '2019') { 
  	include ("por/2019.php");
} else if($_GET['year'] == '2020') {
	include ("por/2020.php");
} else if($_GET['year'] >= '2021') {
	include ("por/2021.php"); 
} ?>

  </div>
</div>
</div>
</body>
</html>