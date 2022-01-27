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

// CHANGE REQUEST FROM CURRENT PLAN (RED)
$sql_budCapCCR = "
					Select [Category Type], Category
				   ,[Jan],[Feb], [Mar],[Apr],[May],[Jun],[Jul],[Aug],[Sep],[Oct],[Nov],[Dec]
					From (
						   Select [Category Type],[Category],Format([Period],'MMM') As PerMth_Nm,Sum([CCRB]) AS CCRB
							 From [dbo].[vw_CR_PrjLog_All] 
							 Where CR_Key=$fundingKey
							 Group By [Category Type],[Category],Format([Period],'MMM')
						   ) Src
					Pivot
						   ( SUM(CCRB) For PerMth_Nm In ([Jan],[Feb], [Mar],[Apr],[May],[Jun],[Jul],[Aug],[Sep],[Oct],[Nov],[Dec])
						   ) Pvt
					where [Category Type] = 'Capex'
					order by Category desc
			 ";
$stmt_budCapCCR = sqlsrv_query( $conn_COXProd, $sql_budCapCCR, $params, $options );
$rowCount_budCapCCR = sqlsrv_num_rows( $stmt_budCapCCR );
	//$row_budCapCCR = sqlsrv_fetch_array( $stmt_budCapCCR, SQLSRV_FETCH_ASSOC); //comment out when looping
	//echo $row_budCapCCR['column_name']
	
	// CHANGE REQUEST TOTALS
	$sql_budCapCCR_ttl = "SELECT sum(Jan) AS jan,
									sum(Feb) AS feb,
									sum(Mar) AS mar,
									sum(Apr) AS apr,
									sum(May) AS may,
									sum(Jun) AS jun,
									sum(Jul) AS jul,
									sum(Aug) AS aug,
									sum(Sep) AS sep,
									sum(Oct) AS oct,
									sum(Nov) AS nov,
									sum(Dec) AS dec
							FROM(
							Select [Category Type], Category
											   ,[Jan],[Feb], [Mar],[Apr],[May],[Jun],[Jul],[Aug],[Sep],[Oct],[Nov],[Dec]
												From (
													   Select [Category Type],[Category],Format([Period],'MMM') As PerMth_Nm,Sum([CCRB]) AS CCRB
														 From [dbo].[vw_CR_PrjLog_All] 
														 Where CR_Key = $fundingKey
														 Group By [Category Type],[Category],Format([Period],'MMM')
													   ) Src
												Pivot
													   ( SUM(CCRB) For PerMth_Nm In ([Jan],[Feb], [Mar],[Apr],[May],[Jun],[Jul],[Aug],[Sep],[Oct],[Nov],[Dec])
													   ) Pvt
												where [Category Type] = 'Capex'
												--order by Category desc
												) AS c";
	$stmt_budCapCCR_ttl = sqlsrv_query( $conn_COXProd, $sql_budCapCCR_ttl, $params, $options );
	$rowCount_budCapCCR_ttl = sqlsrv_num_rows( $stmt_budCapCCR_ttl );
	$row_budCapCCR_ttl = sqlsrv_fetch_array( $stmt_budCapCCR_ttl, SQLSRV_FETCH_ASSOC); //comment out when looping
		//echo $row_budCapCCR_ttl['column_name']

// CURRENT PLAN (BLUE)
$sql_budSubCCR = "SELECT * FROM [dbo].fn_GetCCRBForProgram($fundingKey) where CAPEX = 1 order by Category desc";
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
						
								FROM(
									SELECT * 
									FROM(
										SELECT * 
										FROM dbo.fn_GetCCRBForProgram($fundingKey)
										) AS c
									WHERE CAPEX = 1
									) AS b";
		$stmt_budSubCCR_ttl = sqlsrv_query( $conn_COXProd, $sql_budSubCCR_ttl, $params, $options);
		//$rowCount_budSubCCR_ttl = sqlsrv_num_rows( $stmt_budSubCCR_ttl );
		$row_budSubCCR_ttl = sqlsrv_fetch_array( $stmt_budSubCCR_ttl, SQLSRV_FETCH_ASSOC); //comment out when looping
			//echo $row_budSubCCR_ttl['column_name']

// CURRENT PLAN + CR (GREEN)
$sql_budttlCCR = "
					Select [Category Type], Category
						   ,Sum(Jan) As Jan,Sum(Feb) As Feb,Sum(Mar) As Mar,Sum(Apr) As Apr,Sum(May) As May,Sum(Jun) As Jun
						   ,Sum(Jul) As Jul,Sum(Aug) As Aug,Sum(Sep) As Sep,Sum(Oct) As Oct,Sum(Nov) As Nov,Sum(Dec) As Dec
					From
					(
						   Select [Category Type], Category
									,[Jan],[Feb], [Mar],[Apr],[May],[Jun],[Jul],[Aug],[Sep],[Oct],[Nov],[Dec]
						   From (
									Select [Category Type],[Category],Format([Period],'MMM') As PerMth_Nm,Sum([CCRB]) AS CCRB
										From [dbo].[vw_CR_PrjLog_All] 
										 Where CR_Key = $fundingKey
										Group By [Category Type],[Category],Format([Period],'MMM')
									) Src
						   Pivot
									( SUM(CCRB) For PerMth_Nm In ([Jan],[Feb], [Mar],[Apr],[May],[Jun],[Jul],[Aug],[Sep],[Oct],[Nov],[Dec])
									) Pvt
						   Union
						   Select Case When Capex=1 Then 'Capex' Else 'Opex' End  As [Category Type], Category
						   ,Project_01,Project_02,Project_03,Project_04,Project_05,Project_06,Project_07,Project_08,Project_09,Project_10,Project_11,Project_12
						   From [dbo].fn_GetCCRBForProgram($fundingKey)
					) a
					where [Category Type] = 'Capex'
					Group By [Category Type], Category
					Order By [Category Type], Category desc
				 ";
$stmt_budttlCCR = sqlsrv_query( $conn_COXProd, $sql_budttlCCR, $params, $options);
$rowCount_budttlCCR = sqlsrv_num_rows( $stmt_budttlCCR );
	//$row_budttlCCR = sqlsrv_fetch_array( $stmt_budttlCCR, SQLSRV_FETCH_ASSOC); //comment out when looping
	//echo $row_budttlCCR['column_name']
	
	// CURRENT PLAN + CR TOTALS
	$sql_budttlCCR_ttl = "SELECT SUM(Jan) AS jan,
								SUM(Feb) AS feb,
								SUM(Mar) AS mar,
								SUM(Apr) AS apr,
								SUM(May) AS may,
								SUM(Jun) AS jun,
								SUM(Jul) AS jul,
								SUM(Aug) AS aug,
								SUM(Sep) AS sep,
								SUM(Oct) AS oct,
								SUM(Nov) AS nov,
								SUM(Dec) AS dec
						FROM(
						Select [Category Type], Category
												   ,Sum(Jan) As Jan,Sum(Feb) As Feb,Sum(Mar) As Mar,Sum(Apr) As Apr,Sum(May) As May,Sum(Jun) As Jun
												   ,Sum(Jul) As Jul,Sum(Aug) As Aug,Sum(Sep) As Sep,Sum(Oct) As Oct,Sum(Nov) As Nov,Sum(Dec) As Dec
											From
											(
												   Select [Category Type], Category
															,[Jan],[Feb], [Mar],[Apr],[May],[Jun],[Jul],[Aug],[Sep],[Oct],[Nov],[Dec]
												   From (
															Select [Category Type],[Category],Format([Period],'MMM') As PerMth_Nm,Sum([CCRB]) AS CCRB
																From [dbo].[vw_CR_PrjLog_All] 
																 Where CR_Key = $fundingKey
																Group By [Category Type],[Category],Format([Period],'MMM')
															) Src
												   Pivot
															( SUM(CCRB) For PerMth_Nm In ([Jan],[Feb], [Mar],[Apr],[May],[Jun],[Jul],[Aug],[Sep],[Oct],[Nov],[Dec])
															) Pvt
												   Union
												   Select Case When Capex=1 Then 'Capex' Else 'Opex' End  As [Category Type], Category
												   ,Project_01,Project_02,Project_03,Project_04,Project_05,Project_06,Project_07,Project_08,Project_09,Project_10,Project_11,Project_12
												   From [dbo].fn_GetCCRBForProgram($fundingKey)
											) a
											where [Category Type] = 'Capex'
											Group By [Category Type], Category
											) AS D";
		$stmt_budttlCCR_ttl = sqlsrv_query( $conn_COXProd, $sql_budttlCCR_ttl, $params, $options);
		$rowCount_budttlCCR_ttl = sqlsrv_num_rows( $stmt_budttlCCR_ttl );
		$row_budttlCCR_ttl = sqlsrv_fetch_array( $stmt_budttlCCR_ttl, SQLSRV_FETCH_ASSOC); //comment out when looping
			//echo $row_budttlCCR_ttl['column_name']
	
	

// Schedule Change section
$sql_budschChng = "select * from [dbo].[fn_GetCRInformation]($fundingKey)";
$stmt_budschChng = sqlsrv_query( $conn_COXProd, $sql_budschChng );
$row_budschChng = sqlsrv_fetch_array( $stmt_budschChng, SQLSRV_FETCH_ASSOC); //comment out when looping
//echo $row_budschChng['column_name']


// OPEX Budget Current Plan for the Program
$sql_budCapCCR_O = "
					Select [Category Type], Category
				   ,[Jan],[Feb], [Mar],[Apr],[May],[Jun],[Jul],[Aug],[Sep],[Oct],[Nov],[Dec]
					From (
						   Select [Category Type],[Category],Format([Period],'MMM') As PerMth_Nm,Sum([CCRB]) AS CCRB
							 From [dbo].[vw_CR_PrjLog_All] 
							 Where CR_Key=$fundingKey
							 Group By [Category Type],[Category],Format([Period],'MMM')
						   ) Src
					Pivot
						   ( SUM(CCRB) For PerMth_Nm In ([Jan],[Feb], [Mar],[Apr],[May],[Jun],[Jul],[Aug],[Sep],[Oct],[Nov],[Dec])
						   ) Pvt
					where [Category Type] = 'Opex'
					order by Category desc
			 ";
$stmt_budCapCCR_O = sqlsrv_query( $conn_COXProd, $sql_budCapCCR_O, $params, $options );
$rowCount_budCapCCR_O = sqlsrv_num_rows( $stmt_budCapCCR_O );
//$row_budCapCCR = sqlsrv_fetch_array( $stmt_budCapCCR, SQLSRV_FETCH_ASSOC); //comment out when looping
//echo $row_budCapCCR['column_name']

// OPEX Budget Submitted CR(s) for the Program
$sql_budSubCCR_O = "SELECT * FROM [dbo].fn_GetCCRBForProgram($fundingKey) where CAPEX = 0 order by Category desc";
$stmt_budSubCCR_O = sqlsrv_query( $conn_COXProd, $sql_budSubCCR_O, $params, $options);
$rowCount_budSubCCR_O = sqlsrv_num_rows( $stmt_budSubCCR_O );
//$row_budSubCCR = sqlsrv_fetch_array( $stmt_budSubCCR, SQLSRV_FETCH_ASSOC); //comment out when looping
//echo $row_budSubCCR['column_name']

// OPEX Budget CCRB + CR
$sql_budttlCCR_O = "
					Select [Category Type], Category
						   ,Sum(Jan) As Jan,Sum(Feb) As Feb,Sum(Mar) As Mar,Sum(Apr) As Apr,Sum(May) As May,Sum(Jun) As Jun
						   ,Sum(Jul) As Jul,Sum(Aug) As Aug,Sum(Sep) As Sep,Sum(Oct) As Oct,Sum(Nov) As Nov,Sum(Dec) As Dec
					From
					(
						   Select [Category Type], Category
									,[Jan],[Feb], [Mar],[Apr],[May],[Jun],[Jul],[Aug],[Sep],[Oct],[Nov],[Dec]
						   From (
									Select [Category Type],[Category],Format([Period],'MMM') As PerMth_Nm,Sum([CCRB]) AS CCRB
										From [dbo].[vw_CR_PrjLog_All] 
										 Where CR_Key=$fundingKey
										Group By [Category Type],[Category],Format([Period],'MMM')
									) Src
						   Pivot
									( SUM(CCRB) For PerMth_Nm In ([Jan],[Feb], [Mar],[Apr],[May],[Jun],[Jul],[Aug],[Sep],[Oct],[Nov],[Dec])
									) Pvt
						   Union
						   Select Case When Capex=1 Then 'Capex' Else 'Opex' End  As [Category Type], Category
						   ,Project_01,Project_02,Project_03,Project_04,Project_05,Project_06,Project_07,Project_08,Project_09,Project_10,Project_11,Project_12
						   From [dbo].fn_GetCCRBForProgram($fundingKey)
					) a
					where [Category Type] = 'Opex'
					Group By [Category Type], Category
					Order By [Category Type], Category desc
				 ";
$stmt_budttlCCR_O = sqlsrv_query( $conn_COXProd, $sql_budttlCCR_O, $params, $options);
$rowCount_budttlCCR_O = sqlsrv_num_rows( $stmt_budttlCCR_O );
//$row_budttlCCR = sqlsrv_fetch_array( $stmt_budttlCCR, SQLSRV_FETCH_ASSOC); //comment out when looping
//echo $row_budttlCCR['column_name']
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
<h3 align="center">CR Number: <?php echo htmlspecialchars($_GET['sn'])?></h3>
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
<!--    <table width="100%" border="0" class="table-bordered" style="font-size:11px">
  <tbody>
    <tr>
      <th colspan="2" bgcolor="#00aaf5" scope="col"  style="color:#FFFFFF;">Votes</th>
      </tr>
    <tr>
      <td><strong>Name</strong></td>
      <td><strong>Vote</strong></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </tbody>
</table>-->
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
          <th rowspan="4" align="center" bgcolor="#95b3d7" scope="row" style="color:#FFFFFF"><div align="center">Current Plan for the Program <?php // echo $rowCount_budSubCCR ;?></div></th>
        <?php while($row_budSubCCR = sqlsrv_fetch_array( $stmt_budSubCCR, SQLSRV_FETCH_ASSOC)) { ?>
          <td align="center" bgcolor="#b8cce4"><?php echo $row_budSubCCR['Category']?></td>
          <td align="right" bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR['Project_01'])?></td>
          <td align="right"bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR['Project_02'])?></td>
          <td align="right"bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR['Project_03'])?></td>
          <td align="right"bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR['Project_04'])?></td>
          <td align="right"bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR['Project_05'])?></td>
          <td align="right"bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR['Project_06'])?></td>
          <td align="right"bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR['Project_07'])?></td>
          <td align="right"bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR['Project_08'])?></td>
          <td align="right"bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR['Project_09'])?></td>
          <td align="right"bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR['Project_10'])?></td>
          <td align="right"bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR['Project_11'])?></td>
          <td align="right"bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR['Project_12'])?></td>
          <td align="right"bgcolor="#b8cce4"><strong>
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
      <?php if($rowCount_budSubCCR == 0) { ?>
        
          <td align="center" bgcolor="#b8cce4">Material</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4"><strong>0</strong></td>
        </tr>
        <tr>
          <td align="center" bgcolor="#b8cce4">In-House Labor</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4"><strong>0</strong></td>
       </tr>
       <tr>
          <td align="center" bgcolor="#b8cce4">Contract Labor</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4"><strong>0</strong></td>
        </tr>
        <?php } else if($rowCount_budSubCCR == 1) { ?>
        <tr>
          <td align="center" bgcolor="#b8cce4">--</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4"><strong>0</strong></td>
        </tr>
        <tr>
          <td align="center" bgcolor="#b8cce4">--</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4"><strong>0</strong></td>
        </tr>
        
        <?php } else if($rowCount_budSubCCR == 2) { ?>
        <tr>
          <td align="center" bgcolor="#b8cce4">--</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4"><strong>0</strong></td>
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
        <tr>
          <th width="100" rowspan="4" align="center" bgcolor="#cc3300" scope="row" style="color:#FFFFFF"><div align="center">Submitted CR(s) for the Program<?php //echo $rowCount_budCapCCR ?></div></th>
        <?php while($row_budCapCCR = sqlsrv_fetch_array( $stmt_budCapCCR, SQLSRV_FETCH_ASSOC)) { ?>
          <td align="center" bgcolor="#da9694"><?php echo $row_budCapCCR['Category']?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR['Jan'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR['Feb'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR['Mar'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR['Apr'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR['May'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR['Jun'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR['Jul'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR['Aug'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR['Sep'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR['Oct'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR['Nov'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR['Dec'])?></td>
          <td align="right" bgcolor="#da9694"><strong>
            <?php $sumCapCCR =
		  											  $row_budCapCCR['Jan']
													+ $row_budCapCCR['Feb']
													+ $row_budCapCCR['Mar']
													+ $row_budCapCCR['Apr']
													+ $row_budCapCCR['May']
													+ $row_budCapCCR['Jun']
													+ $row_budCapCCR['Jul']
													+ $row_budCapCCR['Aug']
													+ $row_budCapCCR['Sep']
													+ $row_budCapCCR['Oct']
													+ $row_budCapCCR['Nov']
													+ $row_budCapCCR['Dec'];
													echo FmtNum($sumCapCCR);
		  									  ?>
          </strong></td>
        </tr>
        
        <?php } ?>
        <?php if($rowCount_budCapCCR == 0) { ?>
         
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
        
        <?php } else if($rowCount_budCapCCR == 1) { ?>
        <!--<tr>-->
          <td align="center" bgcolor="#da9694">--</td>
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
          <td align="center" bgcolor="#da9694">--</td>
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
        <?php } else if($rowCount_budCapCCR == 2) { ?>
        <!--<tr>-->
          <td align="center" bgcolor="#da9694">--</td>
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
        
        <?php }?>
        
        
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
          <th rowspan="4" align="center" bgcolor="#76933c" scope="row" style="color:#FFFFFF"><div align="center">CCRB + CR <?php // echo $rowCount_budttlCCR; ?></div></th>
        <?php while($row_budttlCCR = sqlsrv_fetch_array( $stmt_budttlCCR, SQLSRV_FETCH_ASSOC)) { ?>
          <td align="center" bgcolor="#c4d79b"><?php echo $row_budttlCCR['Category']?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR['Jan'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR['Feb'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR['Mar'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR['Apr'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR['May'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR['Jun'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR['Jul'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR['Aug'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR['Sep'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR['Oct'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR['Nov'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR['Dec'])?></td>
          <td align="right" bgcolor="#c4d79b"><strong>
            <?php $sumttlCCR =
		  											  $row_budttlCCR['Jan']
													+ $row_budttlCCR['Feb']
													+ $row_budttlCCR['Mar']
													+ $row_budttlCCR['Apr']
													+ $row_budttlCCR['May']
													+ $row_budttlCCR['Jun']
													+ $row_budttlCCR['Jul']
													+ $row_budttlCCR['Aug']
													+ $row_budttlCCR['Sep']
													+ $row_budttlCCR['Oct']
													+ $row_budttlCCR['Nov']
													+ $row_budttlCCR['Dec'];
													echo FmtNum($sumttlCCR);
		  									  ?>
          </strong></td>
        </tr>
       <?php } ?>
 	   <?php if($rowCount_budttlCCR == 0) { ?>
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
        <?php } else if($rowCount_budttlCCR == 1) { ?>
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
        <?php } else if($rowCount_budttlCCR == 2) { ?>
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
      </tbody>
  </table></p>
        </div>
        <div class="tab-pane fade" id="paneTwo1">
          <p>
  <!--Opex Table-->        
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
        <?php while($row_budSubCCR_O = sqlsrv_fetch_array( $stmt_budSubCCR_O, SQLSRV_FETCH_ASSOC)) { ?>
          <td align="center" bgcolor="#b8cce4"><?php echo $row_budSubCCR_O['Category']?></td>
          <td align="right" bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR_O['Project_01'])?></td>
          <td align="right"bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR_O['Project_02'])?></td>
          <td align="right"bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR_O['Project_03'])?></td>
          <td align="right"bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR_O['Project_04'])?></td>
          <td align="right"bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR_O['Project_05'])?></td>
          <td align="right"bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR_O['Project_06'])?></td>
          <td align="right"bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR_O['Project_07'])?></td>
          <td align="right"bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR_O['Project_08'])?></td>
          <td align="right"bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR_O['Project_09'])?></td>
          <td align="right"bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR_O['Project_10'])?></td>
          <td align="right"bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR_O['Project_11'])?></td>
          <td align="right"bgcolor="#b8cce4"><?php echo FmtNum($row_budSubCCR_O['Project_12'])?></td>
          <td align="right"bgcolor="#b8cce4"><?php $sumSubCCR_O =
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
      <?php if($rowCount_budSubCCR_O == 0) { ?>
        
          <td align="center" bgcolor="#b8cce4">Material</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
        </tr>
        <tr>
          <td align="center" bgcolor="#b8cce4">In-House Labor</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
       </tr>
       <tr>
          <td align="center" bgcolor="#b8cce4">Contract Labor</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
        </tr>
        <?php } else if($rowCount_budSubCCR_O == 1) { ?>
       <tr>
          <td align="center" bgcolor="#b8cce4">--</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
        </tr>
               <tr>
          <td align="center" bgcolor="#b8cce4">--</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
        </tr>        
        <?php } else if($rowCount_budSubCCR_O == 2) { ?>
        <tr>
          <td align="center" bgcolor="#b8cce4">--</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
          <td align="right"  bgcolor="#b8cce4">0</td>
        </tr>
        
        <?php } ?>
        <tr>
          <td align="center" bgcolor="#95b3d7" style="color:#FFFFFF">Total Plan</td>
          <td bgcolor="#95b3d7">&nbsp;</td>
          <td bgcolor="#95b3d7">&nbsp;</td>
          <td bgcolor="#95b3d7">&nbsp;</td>
          <td bgcolor="#95b3d7">&nbsp;</td>
          <td bgcolor="#95b3d7">&nbsp;</td>
          <td bgcolor="#95b3d7">&nbsp;</td>
          <td bgcolor="#95b3d7">&nbsp;</td>
          <td bgcolor="#95b3d7">&nbsp;</td>
          <td bgcolor="#95b3d7">&nbsp;</td>
          <td bgcolor="#95b3d7">&nbsp;</td>
          <td bgcolor="#95b3d7">&nbsp;</td>
          <td bgcolor="#95b3d7">&nbsp;</td>
          <td bgcolor="#95b3d7">&nbsp;</td>
        </tr>
        <tr>
          <th width="100" rowspan="4" align="center" bgcolor="#cc3300" scope="row" style="color:#FFFFFF"><div align="center">Submitted CR(s) for the Program<?php //echo $rowCount_budCapCCR_O ?></div></th>
        <?php while($row_budCapCCR_O = sqlsrv_fetch_array( $stmt_budCapCCR_O, SQLSRV_FETCH_ASSOC)) { ?>
          <td align="center" bgcolor="#da9694"><?php echo $row_budCapCCR_O['Category']?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR_O['Jan'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR_O['Feb'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR_O['Mar'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR_O['Apr'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR_O['May'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR_O['Jun'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR_O['Jul'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR_O['Aug'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR_O['Sep'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR_O['Oct'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR_O['Nov'])?></td>
          <td align="right" bgcolor="#da9694"><?php echo FmtNum($row_budCapCCR_O['Dec'])?></td>
          <td align="right" bgcolor="#da9694"><?php $sumCapCCR_O =
		  											  $row_budCapCCR_O['Jan']
													+ $row_budCapCCR_O['Feb']
													+ $row_budCapCCR_O['Mar']
													+ $row_budCapCCR_O['Apr']
													+ $row_budCapCCR_O['May']
													+ $row_budCapCCR_O['Jun']
													+ $row_budCapCCR_O['Jul']
													+ $row_budCapCCR_O['Aug']
													+ $row_budCapCCR_O['Sep']
													+ $row_budCapCCR_O['Oct']
													+ $row_budCapCCR_O['Nov']
													+ $row_budCapCCR_O['Dec'];
													echo FmtNum($sumCapCCR_O);
		  									  ?>
          </td>
        </tr>
        
        <?php } ?>
        <?php if($rowCount_budCapCCR_O == 0) { ?>
         
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
        
        <?php } else if($rowCount_budCapCCR_O == 1) { ?>
        <!--<tr>-->
          <td align="center" bgcolor="#da9694">--</td>
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
          <td align="center" bgcolor="#da9694">--</td>
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
        <?php } else if($rowCount_budCapCCR_O == 2) { ?>
        <!--<tr>-->
          <td align="center" bgcolor="#da9694">--</td>
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
        
        <?php }?>
        
        
        <tr>
          <td align="center" bgcolor="#cc3300" style="color:#FFFFFF">Total Plan</td>
          <td bgcolor="#cc3300">&nbsp;</td>
          <td bgcolor="#cc3300">&nbsp;</td>
          <td bgcolor="#cc3300">&nbsp;</td>
          <td bgcolor="#cc3300">&nbsp;</td>
          <td bgcolor="#cc3300">&nbsp;</td>
          <td bgcolor="#cc3300">&nbsp;</td>
          <td bgcolor="#cc3300">&nbsp;</td>
          <td bgcolor="#cc3300">&nbsp;</td>
          <td bgcolor="#cc3300">&nbsp;</td>
          <td bgcolor="#cc3300">&nbsp;</td>
          <td bgcolor="#cc3300">&nbsp;</td>
          <td bgcolor="#cc3300">&nbsp;</td>
          <td bgcolor="#cc3300">&nbsp;</td>
        </tr>
        <tr>
          <th rowspan="4" align="center" bgcolor="#76933c" scope="row" style="color:#FFFFFF"><div align="center">CCR_OB + CR <?php // echo $rowCount_budttlCCR_O; ?></div></th>
        <?php while($row_budttlCCR_O = sqlsrv_fetch_array( $stmt_budttlCCR_O, SQLSRV_FETCH_ASSOC)) { ?>
          <td align="center" bgcolor="#c4d79b"><?php echo $row_budttlCCR_O['Category']?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR_O['Jan'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR_O['Feb'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR_O['Mar'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR_O['Apr'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR_O['May'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR_O['Jun'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR_O['Jul'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR_O['Aug'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR_O['Sep'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR_O['Oct'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR_O['Nov'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php echo FmtNum($row_budttlCCR_O['Dec'])?></td>
          <td align="right" bgcolor="#c4d79b"><?php $sumttlCCR_O =
		  											  $row_budttlCCR_O['Jan']
													+ $row_budttlCCR_O['Feb']
													+ $row_budttlCCR_O['Mar']
													+ $row_budttlCCR_O['Apr']
													+ $row_budttlCCR_O['May']
													+ $row_budttlCCR_O['Jun']
													+ $row_budttlCCR_O['Jul']
													+ $row_budttlCCR_O['Aug']
													+ $row_budttlCCR_O['Sep']
													+ $row_budttlCCR_O['Oct']
													+ $row_budttlCCR_O['Nov']
													+ $row_budttlCCR_O['Dec'];
													echo FmtNum($sumttlCCR_O);
		  									  ?>
          </td>
        </tr>
       <?php } ?>
 	   <?php if($rowCount_budttlCCR_O == 0) { ?>
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
          <td align="right" bgcolor="#c4d79b">0</td>
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
          <td align="right" bgcolor="#c4d79b">0</td>
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
          <td align="right" bgcolor="#c4d79b">0</td>
        </tr>
        <?php } else if($rowCount_budttlCCR_O == 1) { ?>
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
          <td align="right" bgcolor="#c4d79b">0</td>
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
          <td align="right" bgcolor="#c4d79b">0</td>
        </tr>
        
        <?php } else if($rowCount_budttlCCR_O == 2) { ?>
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
          <td align="right" bgcolor="#c4d79b">0</td>
        </tr>
        <?php } ?>
        <tr>
          <td align="center" bgcolor="#76933c" style="color:#FFFFFF">Total Plan</td>
          <td bgcolor="#76933c">&nbsp;</td>
          <td bgcolor="#76933c">&nbsp;</td>
          <td bgcolor="#76933c">&nbsp;</td>
          <td bgcolor="#76933c">&nbsp;</td>
          <td bgcolor="#76933c">&nbsp;</td>
          <td bgcolor="#76933c">&nbsp;</td>
          <td bgcolor="#76933c">&nbsp;</td>
          <td bgcolor="#76933c">&nbsp;</td>
          <td bgcolor="#76933c">&nbsp;</td>
          <td bgcolor="#76933c">&nbsp;</td>
          <td bgcolor="#76933c">&nbsp;</td>
          <td bgcolor="#76933c">&nbsp;</td>
          <td bgcolor="#76933c">&nbsp;</td>
        </tr>
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
  <?php if($_GET['year'] == '2019') { ?>
    <table width="100%" border="0" class="table-bordered table-hover table-striped" style="font-size:11px">
      <tbody>
        <tr style="color:#FFFFFF; background-color:#ffffff">
          <th colspan="9" scope="col"><?php //echo $row_count ?> </th>
          <th colspan="13" bgcolor="#068AC4" scope="col"><div align="center">2018</div></th>
          <th colspan="52" bgcolor="#068AC4" scope="col"><div align="center">2019</div></th>
          <th colspan="13" bgcolor="#068AC4" scope="col"><div align="center">2020</div></th>
        </tr>
        <tr style="color:#FFFFFF; background-color:#95b3d7">
          <th scope="col">PPM</th>
          <th scope="col">Identifier</th>
          <th scope="col">Reg</th>
          <th scope="col">fMrk</th>
          <th scope="col">TLA</th>
          <th scope="col">Facility</th>
          <th scope="col">CR Act</th>
          <th scope="col">Kit Name</th>
          <th scope="col">Qty</th>
          <th colspan="4" scope="col"><div align="center">Oct</div></th>
          <th colspan="4" scope="col"><div align="center">Nov</div></th>
          <th colspan="5" scope="col"><div align="center">Dec</div></th>
          <th colspan="4" scope="col"><div align="center">Jan</div></th>
          <th colspan="4" scope="col"><div align="center">Feb</div></th>
          <th colspan="5" scope="col"><div align="center">Mar</div></th>
          <th colspan="4" scope="col"><div align="center">Apr</div></th>
          <th colspan="4" scope="col"><div align="center">May</div></th>
          <th colspan="5" scope="col"><div align="center">Jun</div></th>
          <th colspan="4" scope="col"><div align="center">Jul</div></th>
          <th colspan="4" scope="col"><div align="center">Aug</div></th>
          <th colspan="5" scope="col"><div align="center">Sep</div></th>
          <th colspan="4" scope="col"><div align="center">Oct</div></th>
          <th colspan="4" scope="col"><div align="center">Nov</div></th>
          <th colspan="5" scope="col"><div align="center">Dec</div></th>
          <th colspan="4" scope="col"><div align="center">Jan</div></th>
          <th colspan="4" scope="col"><div align="center">Feb</div></th>
          <th colspan="5" scope="col"><div align="center">Mar</div></th>
        </tr>
        
        <tr style="font-size:8px; font-family:'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', 'DejaVu Sans', Verdana, sans-serif">
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td width="12" align="center">40</td>
          <td width="12" align="center">41</td>
          <td width="12" align="center">42</td>
          <td width="12" align="center">43</td>
          <td width="12" align="center">44</td>
          <td width="12" align="center">45</td>
          <td width="12" align="center">46</td>
          <td width="12" align="center">47</td>
          <td width="12" align="center">48</td>
          <td width="12" align="center">49</td>
          <td width="12" align="center">50</td>
          <td width="12" align="center">51</td>
          <td width="12" align="center">52</td>
          <td width="12" align="center">1</td>
          <td width="12" align="center">2</td>
          <td width="12" align="center">3</td>
          <td width="12" align="center">4</td>
          <td width="12" align="center">5</td>
          <td width="12" align="center">6</td>
          <td width="12" align="center">7</td>
          <td width="12" align="center">8</td>
          <td width="12" align="center">9</td>
          <td width="12" align="center">10</td>
          <td width="12" align="center">11</td>
          <td width="12" align="center">12</td>
          <td width="12" align="center">13</td>
          <td width="12" align="center">14</td>
          <td width="12" align="center">15</td>
          <td width="12" align="center">16</td>
          <td width="12" align="center">17</td>
          <td width="12" align="center">18</td>
          <td width="12" align="center">19</td>
          <td width="12" align="center">20</td>
          <td width="12" align="center">21</td>
          <td width="12" align="center">22</td>
          <td width="12" align="center">23</td>
          <td width="12" align="center">24</td>
          <td width="12" align="center">25</td>
          <td width="12" align="center">26</td>
          <td width="12" align="center">27</td>
          <td width="12" align="center">28</td>
          <td width="12" align="center">29</td>
          <td width="12" align="center">30</td>
          <td width="12" align="center">31</td>
          <td width="12" align="center">32</td>
          <td width="12" align="center">33</td>
          <td width="12" align="center">34</td>
          <td width="12" align="center">35</td>
          <td width="12" align="center">36</td>
          <td width="12" align="center">37</td>
          <td width="12" align="center">38</td>
          <td width="12" align="center">39</td>
          <td width="12" align="center">40</td>
          <td width="12" align="center">41</td>
          <td width="12" align="center">42</td>
          <td width="12" align="center">43</td>
          <td width="12" align="center">44</td>
          <td width="12" align="center">45</td>
          <td width="12" align="center">46</td>
          <td width="12" align="center">47</td>
          <td width="12" align="center">48</td>
          <td width="12" align="center">49</td>
          <td width="12" align="center">50</td>
          <td width="12" align="center">51</td>
          <td width="12" align="center">52</td>
          <td width="12" align="center">1</td>
          <td width="12" align="center">2</td>
          <td width="12" align="center">3</td>
          <td width="12" align="center">4</td>
          <td width="12" align="center">5</td>
          <td width="12" align="center">6</td>
          <td width="12" align="center">7</td>
          <td width="12" align="center">8</td>
          <td width="12" align="center">9</td>
          <td width="12" align="center">10</td>
          <td width="12" align="center">11</td>
          <td width="12" align="center">12</td>
          <td width="12" align="center">13</td>
        </tr>
        <?php while($row_cfPor = sqlsrv_fetch_array( $stmt_cfPor, SQLSRV_FETCH_ASSOC)) { ?>
        <?php 
			if(is_null($row_cfPor['InitShip_Dt'])) { 
			 $por_year = 0; 
			 $por_week = 0;
			 $nxtwk_shp = 0;
			} else {
			 $por_year = date_format($row_cfPor['InitShip_Dt'], 'Y'); 
			 $por_week = date_format($row_cfPor['InitShip_Dt'], 'W');
			 
			 //Week proceeding Initial Ship Week
			 $nxtwk_shp = $por_week + 1;
			 
			 // Crossing years
			 // Get InitShip Date and convert to week
			 // Get InitActi date and convert to week
			 // if initShip is 2018 and InitActi is 2019 and week number <= 52 then grey
			 //if($por_year == 2018 && $por_acti_year == 2019 & $nxtwk_shp < 50 && $nxtwk_shp <52 ) { echo 'bgcolor="#c1c1c1"'; }
			}
			
			if(is_null($row_cfPor['InitActi_Dt'])) { 
			 $por_acti_year = 0; 
			 $por_acti_week = 0;
			 $por_acti_month = 0;
			 $endweek = 0;
			} else {
			 $por_acti_year = date_format($row_cfPor['InitActi_Dt'], 'Y'); 
			 $por_acti_week = date_format($row_cfPor['InitActi_Dt'], 'W');
			 $por_acti_month = date_format($row_cfPor['InitActi_Dt'], 'm');
			
			//Week prior of month, before activation 
			 $prior_wk = date_format($row_cfPor['InitActi_Dt'], 'Y-m-01');
			 $strtodate = strtotime($prior_wk);
			 $realdate = date('W',$strtodate);
			 $endweek = $realdate - 1;
			 }
						
						
			//Initial Migration Date InitMig_Dt			
			if(is_null($row_cfPor['InitMig_Dt'])) { 
			 $por_Mig_year = 0; 
			 $por_Mig_week = 0;
			 $por_Mig_month = 0;
			 $por_Mig_endweek = 0;
			} else {
			 $por_Mig_year = date_format($row_cfPor['InitMig_Dt'], 'Y'); 
			 $por_Mig_week = date_format($row_cfPor['InitMig_Dt'], 'W');
			 $por_Mig_month = date_format($row_cfPor['InitMig_Dt'], 'm');
			}
		?>
        <tr>
          <td><?php //echo $row_cfPor['column_name'] ?></td>
          <td><?php echo $row_cfPor['EquipPlan_Id'] ?></td>
          <td><?php echo $row_cfPor['Region_Abb'] ?></td>
          <td><?php echo $row_cfPor['Market_Abb'] ?></td>
          <td><?php echo $row_cfPor['Location_Abb'] ?></td>
          <td><?php echo $row_cfPor['Location_Cd'] ?></td>
          <td><?php echo  convtimex($row_cfPor['CR_Acti_Dt']) ?></td>
          <td><?php echo $row_cfPor['Kit_Nm'] ?></td>
          <td><?php echo $row_cfPor['InitQty'] ?></td>
          <td align="center" <?php if($por_year == '2018' && $por_week == 40) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2018' && $por_acti_month == 10) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2018' && $endweek == 40) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2018' && $nxtwk_shp == 40) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $nxtwk_shp < 40 && $nxtwk_shp <52 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2018' && $por_week == 41) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2018' && $por_acti_month == 10) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2018' && $endweek == 41) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2018' && $nxtwk_shp == 41) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $nxtwk_shp < 41 && $nxtwk_shp <52 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2018' && $por_week == 42) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2018' && $por_acti_month == 10) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2018' && $endweek == 42) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2018' && $nxtwk_shp == 42) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $nxtwk_shp < 42 && $nxtwk_shp <52 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2018' && $por_week == 43) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2018' && $por_acti_month == 10) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2018' && $endweek == 43) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2018' && $nxtwk_shp == 43) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $nxtwk_shp < 43 && $nxtwk_shp <52 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2018' && $por_week == 44) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2018' && $por_acti_month == 11) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2018' && $endweek == 44) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2018' && $nxtwk_shp == 44) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $nxtwk_shp < 44 && $nxtwk_shp <52 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2018' && $por_week == 45) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2018' && $por_acti_month == 11) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2018' && $endweek == 45) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2018' && $nxtwk_shp == 45) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $nxtwk_shp < 45 && $nxtwk_shp <52 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2018' && $por_week == 46) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2018' && $por_acti_month == 11) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2018' && $endweek == 46) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2018' && $nxtwk_shp == 46) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $nxtwk_shp < 46 && $nxtwk_shp <52 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2018' && $por_week == 47) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2018' && $por_acti_month == 11) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2018' && $endweek == 47) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2018' && $nxtwk_shp == 47) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $nxtwk_shp < 47 && $nxtwk_shp <52 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2018' && $por_week == 48) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2018' && $por_acti_month == 12) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2018' && $endweek == 48) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2018' && $nxtwk_shp == 48) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $nxtwk_shp < 48 && $nxtwk_shp <52 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2018' && $por_week == 49) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2018' && $por_acti_month == 12) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2018' && $endweek == 49) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2018' && $nxtwk_shp == 49) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $nxtwk_shp < 49 && $nxtwk_shp <52 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2018' && $por_week == 50) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2018' && $por_acti_month == 12) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2018' && $endweek == 50) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2018' && $nxtwk_shp == 50) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $nxtwk_shp < 50 && $nxtwk_shp <52 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2018' && $por_week == 51) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2018' && $por_acti_month == 12) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2018' && $endweek == 51) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2018' && $nxtwk_shp == 51) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $nxtwk_shp < 51 && $nxtwk_shp <52 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2018' && $por_week == 52) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2018' && $por_acti_month == 12) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2018' && $endweek == 52) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2018' && $nxtwk_shp == 52) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $nxtwk_shp < 52 && $nxtwk_shp <52 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
<!--2019-->
          <td align="center" <?php if($por_year == '2019' && $por_week == 1) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '01') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==1) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 1) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 1) && (1 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 1 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 2) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '01') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==2) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 2) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 2) && (2 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 2 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 3) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '01') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==3) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 3) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 3) && (3 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 3 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 4) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '01') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==4) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 4) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 4) && (4 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 4 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 5) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '02') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==5) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 5) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 5) && (5 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 5 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 6) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '02') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==6) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 6) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 6) && (6 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 6 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 7) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '02') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==7) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 7) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 7) && (7 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 7 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 8) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '02') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==8) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 8) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 8) && (8 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 8 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 9) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '03') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==9) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 9) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 9) && (9 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 9 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 10) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '03') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==10) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 10) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 10) && (10 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 10 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 11) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '03') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==11) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 11) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 11) && (11 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 11 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 12) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '03') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==12) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 12) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 12) && (12 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 12 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 13) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '03') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==13) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 13) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 13) && (13 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 13 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 14) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '04') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==14) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 14) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 14) && (14 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 14 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 15) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '04') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==15) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 15) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 15) && (15 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 15 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 16) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '04') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==16) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 16) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 16) && (16 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 16 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 17) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '04') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==17) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 17) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 17) && (17 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 17 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 18) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '05') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==18) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 18) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 18) && (18 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 18 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 19) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '05') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==19) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 19) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 19) && (19 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 19 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 20) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '05') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==20) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 20) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 20) && (20 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 20 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 21) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '05') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==21) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 21) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 21) && (21 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 21 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 22) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '06') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==22) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 22) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 22) && (22 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 22 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 23) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '06') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==23) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 23) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 23) && (23 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 23 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 24) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '06') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==24) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 24) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 24) && (24 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 24 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 25) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '06') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==25) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 25) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 25) && (25 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 25 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 26) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '06') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==26) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 26) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 26) && (26 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 26 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 27) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '07') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==27) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 27) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 27) && (27 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 27 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 28) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '07') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==28) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 28) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 28) && (28 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 28 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 29) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '07') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==29) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 29) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 29) && (29 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 29 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 30) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '07') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==30) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 30) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 30) && (30 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 30 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 31) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '08') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==31) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 31) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 31) && (31 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 31 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 32) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '08') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==32) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 32) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 32) && (32 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 32 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 33) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '08') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==33) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 33) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 33) && (33 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 33 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 34) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '08') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==34) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 34) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 34) && (34 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 34 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 35) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '09') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==35) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 35) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 35) && (35 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 35 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 36) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '09') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==36) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 36) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 36) && (36 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 36 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 37) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '09') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==37) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 37) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 37) && (37 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 37 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 38) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '09') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==38) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 38) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 38) && (38 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 38 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 39) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == '09') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==39) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 39) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 39) && (39 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 39 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 40) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == 10) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==40) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 40) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 40) && (40 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 40 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 41) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == 10) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==41) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 41) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 41) && (41 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 41 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 42) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == 10) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==42) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 42) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 42) && (42 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 42 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 43) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == 10) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==43) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 43) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 43) && (43 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 43 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 44) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == 11) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==44) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 44) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 44) && (44 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 44 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 45) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == 11) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==45) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 45) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 45) && (45 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 45 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 46) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == 11) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek == 46) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 46) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 46) && (46 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 46 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 47) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == 11) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek == 47) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 47) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 47) && (47 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 47 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 48) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == 12) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek == 48) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 48) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 48) && (48 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 48 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 49) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == 12) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek == 49) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 49) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 49) && (49 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 59 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 50) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == 12) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek == 50) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 50) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 50) && (50 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 50 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 51) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == 12) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek == 51) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 50) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 51) && (51 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 51 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 52) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == 12) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek == 52) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 52) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 52) && (52 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 52 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
<!--2020-->
          <td align="center" <?php if($por_year == '2020' && $por_week == 1) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '01') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek == 1) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 1) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $nxtwk_shp < 1 && $nxtwk_shp < 1) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 2) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '01') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek == 2) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 2) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $nxtwk_shp < 2 && $nxtwk_shp < 2 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 3) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '01') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek == 3) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 3) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $nxtwk_shp < 3 && $nxtwk_shp < 3 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 4) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '01') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek == 4) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 4) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $nxtwk_shp < 4 && $nxtwk_shp < 4 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 5) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '02') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek == 5) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 5) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $nxtwk_shp < 5 && $nxtwk_shp < 5 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 6) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '02') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek == 6) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 6) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $nxtwk_shp < 6 && $nxtwk_shp < 6 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 7) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '02') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek == 7) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 7) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $nxtwk_shp < 7 && $nxtwk_shp < 7 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 8) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '02') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek == 8) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 8) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2002 & $nxtwk_shp < 8 && $nxtwk_shp < 8 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 9) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '03') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek == 9) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 9) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $nxtwk_shp < 9 && $nxtwk_shp < 9 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 10) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '03') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek == 10) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 10) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $nxtwk_shp < 10 && $nxtwk_shp < 10 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 11) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '03') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek == 11) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 11) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $nxtwk_shp < 11 && $nxtwk_shp < 11 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 12) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '03') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek == 12) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 12) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $nxtwk_shp < 12 && $nxtwk_shp < 12 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 13) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '03') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek == 13) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 13) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $nxtwk_shp < 13 && $nxtwk_shp < 13 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
		</tr>
        <?php } ?>
      </tbody>
    </table>
  <?php } else if($_GET['year'] == '2020') {?>
    <table width="100%" border="0" class="table-bordered table-hover table-striped" style="font-size:11px">
      <tbody>
        <tr style="color:#FFFFFF; background-color:#ffffff">
          <th colspan="9" scope="col"><?php //echo $row_count ?> </th>
          <th colspan="13" bgcolor="#068AC4" scope="col"><div align="center">2019</div></th>
          <th colspan="52" bgcolor="#068AC4" scope="col"><div align="center">2020</div></th>
          <th colspan="13" bgcolor="#068AC4" scope="col"><div align="center">2021</div></th>
        </tr>
        <tr style="color:#FFFFFF; background-color:#95b3d7">
          <th scope="col">PPM</th>
          <th scope="col">Identifier</th>
          <th scope="col">Reg</th>
          <th scope="col">fMrk</th>
          <th scope="col">TLA</th>
          <th scope="col">Facility</th>
          <th scope="col">CR Act</th>
          <th scope="col">Kit Name</th>
          <th scope="col">Qty</th>
          <th colspan="4" scope="col"><div align="center">Oct</div></th>
          <th colspan="4" scope="col"><div align="center">Nov</div></th>
          <th colspan="5" scope="col"><div align="center">Dec</div></th>
          <th colspan="4" scope="col"><div align="center">Jan</div></th>
          <th colspan="4" scope="col"><div align="center">Feb</div></th>
          <th colspan="5" scope="col"><div align="center">Mar</div></th>
          <th colspan="4" scope="col"><div align="center">Apr</div></th>
          <th colspan="4" scope="col"><div align="center">May</div></th>
          <th colspan="5" scope="col"><div align="center">Jun</div></th>
          <th colspan="4" scope="col"><div align="center">Jul</div></th>
          <th colspan="4" scope="col"><div align="center">Aug</div></th>
          <th colspan="5" scope="col"><div align="center">Sep</div></th>
          <th colspan="4" scope="col"><div align="center">Oct</div></th>
          <th colspan="4" scope="col"><div align="center">Nov</div></th>
          <th colspan="5" scope="col"><div align="center">Dec</div></th>
          <th colspan="4" scope="col"><div align="center">Jan</div></th>
          <th colspan="4" scope="col"><div align="center">Feb</div></th>
          <th colspan="5" scope="col"><div align="center">Mar</div></th>
        </tr>
        
        <tr style="font-size:8px; font-family:'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', 'DejaVu Sans', Verdana, sans-serif">
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td width="12" align="center">40</td>
          <td width="12" align="center">41</td>
          <td width="12" align="center">42</td>
          <td width="12" align="center">43</td>
          <td width="12" align="center">44</td>
          <td width="12" align="center">45</td>
          <td width="12" align="center">46</td>
          <td width="12" align="center">47</td>
          <td width="12" align="center">48</td>
          <td width="12" align="center">49</td>
          <td width="12" align="center">50</td>
          <td width="12" align="center">51</td>
          <td width="12" align="center">52</td>
          <td width="12" align="center">1</td>
          <td width="12" align="center">2</td>
          <td width="12" align="center">3</td>
          <td width="12" align="center">4</td>
          <td width="12" align="center">5</td>
          <td width="12" align="center">6</td>
          <td width="12" align="center">7</td>
          <td width="12" align="center">8</td>
          <td width="12" align="center">9</td>
          <td width="12" align="center">10</td>
          <td width="12" align="center">11</td>
          <td width="12" align="center">12</td>
          <td width="12" align="center">13</td>
          <td width="12" align="center">14</td>
          <td width="12" align="center">15</td>
          <td width="12" align="center">16</td>
          <td width="12" align="center">17</td>
          <td width="12" align="center">18</td>
          <td width="12" align="center">19</td>
          <td width="12" align="center">20</td>
          <td width="12" align="center">21</td>
          <td width="12" align="center">22</td>
          <td width="12" align="center">23</td>
          <td width="12" align="center">24</td>
          <td width="12" align="center">25</td>
          <td width="12" align="center">26</td>
          <td width="12" align="center">27</td>
          <td width="12" align="center">28</td>
          <td width="12" align="center">29</td>
          <td width="12" align="center">30</td>
          <td width="12" align="center">31</td>
          <td width="12" align="center">32</td>
          <td width="12" align="center">33</td>
          <td width="12" align="center">34</td>
          <td width="12" align="center">35</td>
          <td width="12" align="center">36</td>
          <td width="12" align="center">37</td>
          <td width="12" align="center">38</td>
          <td width="12" align="center">39</td>
          <td width="12" align="center">40</td>
          <td width="12" align="center">41</td>
          <td width="12" align="center">42</td>
          <td width="12" align="center">43</td>
          <td width="12" align="center">44</td>
          <td width="12" align="center">45</td>
          <td width="12" align="center">46</td>
          <td width="12" align="center">47</td>
          <td width="12" align="center">48</td>
          <td width="12" align="center">49</td>
          <td width="12" align="center">50</td>
          <td width="12" align="center">51</td>
          <td width="12" align="center">52</td>
          <td width="12" align="center">1</td>
          <td width="12" align="center">2</td>
          <td width="12" align="center">3</td>
          <td width="12" align="center">4</td>
          <td width="12" align="center">5</td>
          <td width="12" align="center">6</td>
          <td width="12" align="center">7</td>
          <td width="12" align="center">8</td>
          <td width="12" align="center">9</td>
          <td width="12" align="center">10</td>
          <td width="12" align="center">11</td>
          <td width="12" align="center">12</td>
          <td width="12" align="center">13</td>
        </tr>
        <?php while($row_cfPor = sqlsrv_fetch_array( $stmt_cfPor, SQLSRV_FETCH_ASSOC)) { ?>
        <?php 
			if(is_null($row_cfPor['InitShip_Dt'])) { 
			 $por_year = 0; 
			 $por_week = 0;
			 $nxtwk_shp = 0;
			} else {
			 $por_year = date_format($row_cfPor['InitShip_Dt'], 'Y'); 
			 $por_week = date_format($row_cfPor['InitShip_Dt'], 'W');
			 
			 //Week proceeding Initial Ship Week
			 $nxtwk_shp = $por_week + 1;
			 
			 // Crossing years
			 // Get InitShip Date and convert to week
			 // Get InitActi date and convert to week
			 // if initShip is 2018 and InitActi is 2019 and week number <= 52 then grey
			 //if($por_year == 2018 && $por_acti_year == 2019 & $nxtwk_shp < 50 && $nxtwk_shp <52 ) { echo 'bgcolor="#c1c1c1"'; }
			}
			
			if(is_null($row_cfPor['InitActi_Dt'])) { 
			 $por_acti_year = 0; 
			 $por_acti_week = 0;
			 $por_acti_month = 0;
			 $endweek = 0;
			} else {
			 $por_acti_year = date_format($row_cfPor['InitActi_Dt'], 'Y'); 
			 $por_acti_week = date_format($row_cfPor['InitActi_Dt'], 'W');
			 $por_acti_month = date_format($row_cfPor['InitActi_Dt'], 'm');
			
			//Week prior of month, before activation 
			 $prior_wk = date_format($row_cfPor['InitActi_Dt'], 'Y-m-01');
			 $strtodate = strtotime($prior_wk);
			 $realdate = date('W',$strtodate);
			 $endweek = $realdate - 1;
			 
			}
		?>
        <tr>
          <td><?php //echo $row_cfPor['column_name'] ?></td>
          <td><?php echo $row_cfPor['EquipPlan_Id'] ?></td>
          <td><?php echo $row_cfPor['Region_Abb'] ?></td>
          <td><?php echo $row_cfPor['Market_Abb'] ?></td>
          <td><?php echo $row_cfPor['Location_Abb'] ?></td>
          <td><?php echo $row_cfPor['Location_Cd'] ?></td>
          <td><?php echo  convtimex($row_cfPor['CR_Acti_Dt']) ?></td>
          <td><?php echo $row_cfPor['Kit_Nm'] ?></td>
          <td><?php echo $row_cfPor['InitQty'] ?></td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 40) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == 10) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek == 40) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 40) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $nxtwk_shp < 40 && $nxtwk_shp <52 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 41) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == 10) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek == 41) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 41) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $nxtwk_shp < 41 && $nxtwk_shp <52 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 42) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == 10) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek == 42) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 42) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $nxtwk_shp < 42 && $nxtwk_shp <52 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 43) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == 10) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek == 43) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 43) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $nxtwk_shp < 43 && $nxtwk_shp <52 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 44) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == 11) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek == 44) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 44) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $nxtwk_shp < 44 && $nxtwk_shp <52 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 45) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == 11) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek == 45) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 45) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $nxtwk_shp < 45 && $nxtwk_shp <52 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 46) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == 11) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek == 46) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 46) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $nxtwk_shp < 46 && $nxtwk_shp <52 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 47) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == 11) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek == 47) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 47) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $nxtwk_shp < 47 && $nxtwk_shp <52 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 48) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == 12) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek == 48) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 48) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $nxtwk_shp < 48 && $nxtwk_shp <52 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 49) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == 12) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek == 49) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 49) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $nxtwk_shp < 49 && $nxtwk_shp <52 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 50) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == 12) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek == 50) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 50) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $nxtwk_shp < 50 && $nxtwk_shp <52 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 51) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == 12) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek == 51) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 51) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $nxtwk_shp < 51 && $nxtwk_shp <52 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 52) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2019' && $por_acti_month == 12) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek == 52) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2019' && $nxtwk_shp == 52) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $nxtwk_shp < 52 && $nxtwk_shp <52 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
<!--2020-->
          <td align="center" <?php if($por_year == '2020' && $por_week == 1) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '01') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==1) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 1) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 1) && (1 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 1 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 2) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '01') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==2) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 2) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 2) && (2 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 2 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 3) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '01') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==3) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 3) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 3) && (3 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 3 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 4) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '01') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==4) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 4) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 4) && (4 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 4 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 5) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '02') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==5) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 5) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 5) && (5 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 5 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 6) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '02') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==6) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 6) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 6) && (6 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 6 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 7) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '02') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==7) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 7) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 7) && (7 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 7 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 8) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '02') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==8) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 8) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 8) && (8 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 8 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 9) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '03') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==9) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 9) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 9) && (9 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 9 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 10) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '03') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==10) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 10) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 10) && (10 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 10 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 11) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '03') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==11) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 11) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 11) && (11 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 11 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 12) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '03') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==12) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 12) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 12) && (12 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 12 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 13) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '03') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==13) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 13) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 13) && (13 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 13 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 14) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '04') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==14) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 14) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 14) && (14 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 14 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 15) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '04') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==15) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 15) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 15) && (15 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 15 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 16) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '04') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==16) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 16) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 16) && (16 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 16 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 17) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '04') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==17) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 17) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 17) && (17 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 17 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 18) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '05') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==18) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 18) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 18) && (18 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 18 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 19) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '05') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==19) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 19) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 19) && (19 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 19 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 20) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '05') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==20) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 20) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 20) && (20 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 20 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 21) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '05') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==21) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 21) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 21) && (21 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 21 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 22) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '06') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==22) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 22) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 22) && (22 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 22 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 23) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '06') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==23) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 23) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 23) && (23 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 23 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 24) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '06') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==24) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 24) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 24) && (24 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 24 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 25) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '06') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==25) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 25) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 25) && (25 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 25 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 26) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '06') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==26) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 26) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 26) && (26 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 26 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 27) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '07') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==27) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 27) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 27) && (27 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 27 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 28) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '07') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==28) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 28) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 28) && (28 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 28 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 29) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '07') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==29) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 29) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 29) && (29 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 29 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 30) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '07') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==30) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 30) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 30) && (30 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 30 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 31) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '08') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==31) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 31) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 31) && (31 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 31 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 32) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '08') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==32) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 32) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 32) && (32 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 32 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 33) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '08') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==33) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 33) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 33) && (33 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 33 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 34) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '08') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==34) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 34) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 34) && (34 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 34 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 35) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '09') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==35) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 35) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 35) && (35 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 35 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 36) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '09') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==36) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 36) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 36) && (36 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 36 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 37) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '09') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==37) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 37) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 37) && (37 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 37 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 38) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '09') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==38) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 38) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 38) && (38 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 38 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 39) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == '09') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==39) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 39) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 39) && (39 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 39 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 40) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == 10) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==40) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 40) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 40) && (40 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 40 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 41) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == 10) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==41) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 41) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 41) && (41 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 41 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 42) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == 10) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==42) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 42) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 42) && (42 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 42 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 43) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == 10) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==43) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 43) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 43) && (43 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 43 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 44) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == 11) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==44) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 44) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 44) && (44 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 44 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 45) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == 11) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek ==45) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 45) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 45) && (45 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 45 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 46) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == 11) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek == 46) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 46) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 46) && (46 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 46 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 47) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == 11) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek == 47) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 47) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 47) && (47 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 47 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 48) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == 12) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek == 48) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 48) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 48) && (48 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 48 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 49) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == 12) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek == 49) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 49) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 49) && (49 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 59 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 50) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == 12) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek == 50) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 50) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 50) && (50 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 50 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 51) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == 12) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek == 51) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 50) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 51) && (51 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 51 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 52) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2020' && $por_acti_month == 12) { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek == 52) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2020' && $nxtwk_shp == 52) {  echo 'bgcolor="#c1c1c1"'; } else if(($nxtwk_shp < 52) && (52 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $endweek > 52 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
<!--2021-->
          <td align="center" <?php if($por_year == '2021' && $por_week == 1) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2021' && $por_acti_month == '01') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2021' && $endweek == 1) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2021' && $nxtwk_shp == 1) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2020 && $por_acti_year == 2021 & $nxtwk_shp < 1 && $nxtwk_shp < 1) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2021' && $por_week == 2) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2021' && $por_acti_month == '01') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2021' && $endweek == 2) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2021' && $nxtwk_shp == 2) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2020 && $por_acti_year == 2021 & $nxtwk_shp < 2 && $nxtwk_shp < 2 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2021' && $por_week == 3) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2021' && $por_acti_month == '01') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2021' && $endweek == 3) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2021' && $nxtwk_shp == 3) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2020 && $por_acti_year == 2021 & $nxtwk_shp < 3 && $nxtwk_shp < 3 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2021' && $por_week == 4) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2021' && $por_acti_month == '01') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2021' && $endweek == 4) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2021' && $nxtwk_shp == 4) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2020 && $por_acti_year == 2021 & $nxtwk_shp < 4 && $nxtwk_shp < 4 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2021' && $por_week == 5) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2021' && $por_acti_month == '02') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2021' && $endweek == 5) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2021' && $nxtwk_shp == 5) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2020 && $por_acti_year == 2021 & $nxtwk_shp < 5 && $nxtwk_shp < 5 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2021' && $por_week == 6) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2021' && $por_acti_month == '02') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2021' && $endweek == 6) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2021' && $nxtwk_shp == 6) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2020 && $por_acti_year == 2021 & $nxtwk_shp < 6 && $nxtwk_shp < 6 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2021' && $por_week == 7) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2021' && $por_acti_month == '02') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2021' && $endweek == 7) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2021' && $nxtwk_shp == 7) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2020 && $por_acti_year == 2021 & $nxtwk_shp < 7 && $nxtwk_shp < 7 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2021' && $por_week == 8) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2021' && $por_acti_month == '02') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2021' && $endweek == 8) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2021' && $nxtwk_shp == 8) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2020 && $por_acti_year == 2021 & $nxtwk_shp < 8 && $nxtwk_shp < 8 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2021' && $por_week == 9) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2021' && $por_acti_month == '03') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2021' && $endweek == 9) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2021' && $nxtwk_shp == 9) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2020 && $por_acti_year == 2021 & $nxtwk_shp < 9 && $nxtwk_shp < 9 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2021' && $por_week == 10) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2021' && $por_acti_month == '03') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2021' && $endweek == 10) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2021' && $nxtwk_shp == 10) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2020 && $por_acti_year == 2021 & $nxtwk_shp < 10 && $nxtwk_shp < 10 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2021' && $por_week == 11) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2021' && $por_acti_month == '03') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2021' && $endweek == 11) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2021' && $nxtwk_shp == 11) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2020 && $por_acti_year == 2021 & $nxtwk_shp < 11 && $nxtwk_shp < 11 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2021' && $por_week == 12) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2021' && $por_acti_month == '03') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2021' && $endweek == 12) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2021' && $nxtwk_shp == 12) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2020 && $por_acti_year == 2021 & $nxtwk_shp < 12 && $nxtwk_shp < 12 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2021' && $por_week == 13) { echo 'bgcolor="#fcd12a"'; } else if($por_acti_year == '2021' && $por_acti_month == '03') { echo 'bgcolor="#00aaf5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2021' && $endweek == 13) { echo 'bgcolor="#c1c1c1"';} else if($por_year == '2021' && $nxtwk_shp == 13) {  echo 'bgcolor="#c1c1c1"'; } else if($por_year == 2020 && $por_acti_year == 2021 & $nxtwk_shp < 13 && $nxtwk_shp < 13 ) { echo 'bgcolor="#c1c1c1"'; }?>>&nbsp;</td>
		</tr>
        <?php } ?>
      </tbody>
    </table>

  <?php } ?>

  </div>
</div>
</div>
</body>
</html>