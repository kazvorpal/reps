<?php include ("../includes/functions.php");?>
<?php include ("../db_conf.php");?>
<?php include ("../data/emo_data.php");?>
<?php include ("../sql/update-time.php");?>
<?php include ("cell_function.php");?>
<?php 
$fundingKeyx = '5355';
$crid = 'SN-0989';
$year = '2019';

//$sql_crs = "Select * From dbo.fn_GetCRInformation('$fundingKey')";
//$stmt_crs = sqlsrv_query( $conn_COX_QA, $sql_crs );
//$row_crs = sqlsrv_fetch_array( $stmt_crs, SQLSRV_FETCH_ASSOC);
////echo $row_crs['column_name']
//
//$sql_crsF = "Select * from [dbo].[fn_GetCRFinancialSummary]('$fundingKey')";
//$stmt_crsF = sqlsrv_query( $conn_COX_QA, $sql_crsF );
//$row_crsF = sqlsrv_fetch_array( $stmt_crsF, SQLSRV_FETCH_ASSOC);
////echo $row_crsF['column_name']

// Plan of Record
$sql_cfPor = "SELECT * FROM [PORMgt].[fn_GetListOfPlanChangeForCR]('4526') ORDER BY Program_Nm";
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt_cfPor = sqlsrv_query( $conn_COX_QA, $sql_cfPor, $params, $options);
$row_count = sqlsrv_num_rows( $stmt_cfPor );
//$row_cfPor = sqlsrv_fetch_array( $stmt_cfPor, SQLSRV_FETCH_ASSOC);
//echo $row_cfPor['column_name']

//// Budget Current Plan for the Program
//$sql_budCapCCR = "
//			Select [Category Type], Category
//		   ,[Jan],[Feb], [Mar],[Apr],[May],[Jun],[Jul],[Aug],[Sep],[Oct],[Nov],[Dec]
//			From (
//				   Select [Category Type],[Category],Format([Period],'MMM') As PerMth_Nm,Sum([CCRB]) AS CCRB
//					 From [dbo].[vw_CR_PrjLog_All] 
//					 Where CR_Key=$fundingKey
//					 Group By [Category Type],[Category],Format([Period],'MMM')
//				   ) Src
//			Pivot
//				   ( SUM(CCRB) For PerMth_Nm In ([Jan],[Feb], [Mar],[Apr],[May],[Jun],[Jul],[Aug],[Sep],[Oct],[Nov],[Dec])
//				   ) Pvt
//			where [Category Type] = 'Capex'
//			order by Category desc
//			 ";
//$stmt_budCapCCR = sqlsrv_query( $conn_COXProd, $sql_budCapCCR );
////$row_budCapCCR = sqlsrv_fetch_array( $stmt_budCapCCR, SQLSRV_FETCH_ASSOC); //comment out when looping
////echo $row_budCapCCR['column_name']
//
//// Budget Submitted CR(s) for the Program
//$sql_budSubCCR = "SELECT * FROM [dbo].fn_GetCCRBForProgram($fundingKey) where CAPEX = 1 order by Category desc";
//$stmt_budSubCCR = sqlsrv_query( $conn_COXProd, $sql_budSubCCR );
////$row_budSubCCR = sqlsrv_fetch_array( $stmt_budSubCCR, SQLSRV_FETCH_ASSOC); //comment out when looping
////echo $row_budSubCCR['column_name']
//
//// Budget CCRB + CR
//$sql_budttlCCR = "
//					Select [Category Type], Category
//						   ,Sum(Jan) As Jan,Sum(Feb) As Feb,Sum(Mar) As Mar,Sum(Apr) As Apr,Sum(May) As May,Sum(Jun) As Jun
//						   ,Sum(Jul) As Jul,Sum(Aug) As Aug,Sum(Sep) As Sep,Sum(Oct) As Oct,Sum(Nov) As Nov,Sum(Dec) As Dec
//					From
//					(
//						   Select [Category Type], Category
//									,[Jan],[Feb], [Mar],[Apr],[May],[Jun],[Jul],[Aug],[Sep],[Oct],[Nov],[Dec]
//						   From (
//									Select [Category Type],[Category],Format([Period],'MMM') As PerMth_Nm,Sum([CCRB]) AS CCRB
//										From [dbo].[vw_CR_PrjLog_All] 
//										 Where CR_Key=$fundingKey
//										Group By [Category Type],[Category],Format([Period],'MMM')
//									) Src
//						   Pivot
//									( SUM(CCRB) For PerMth_Nm In ([Jan],[Feb], [Mar],[Apr],[May],[Jun],[Jul],[Aug],[Sep],[Oct],[Nov],[Dec])
//									) Pvt
//						   Union
//						   Select Case When Capex=1 Then 'Capex' Else 'Opex' End  As [Category Type], Category
//						   ,Project_01,Project_02,Project_03,Project_04,Project_05,Project_06,Project_07,Project_08,Project_09,Project_10,Project_11,Project_12
//						   From [dbo].fn_GetCCRBForProgram($fundingKey)
//					) a
//					where [Category Type] = 'Capex'
//					Group By [Category Type], Category
//					Order By [Category Type], Category desc
//				 ";
//$stmt_budttlCCR = sqlsrv_query( $conn_COXProd, $sql_budttlCCR );
////$row_budttlCCR = sqlsrv_fetch_array( $stmt_budttlCCR, SQLSRV_FETCH_ASSOC); //comment out when looping
////echo $row_budttlCCR['column_name']
//
//// Schedule Change section
//$sql_budschChng = "select * from [dbo].[fn_GetCRInformation]($fundingKey)";
//$stmt_budschChng = sqlsrv_query( $conn_COX_QA, $sql_budschChng );
//$row_budschChng = sqlsrv_fetch_array( $stmt_budschChng, SQLSRV_FETCH_ASSOC); //comment out when looping
////echo $row_budschChng['column_name']
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link href="../css/bootstrap-3.3.4.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php echo $sql_cfPor?>
<div class="row">
  <div class="col-lg-12">
    <table width="100%" border="0" class="table-bordered table-hover table-striped" style="font-size:11px">
      <tbody>
        <tr style="color:#FFFFFF; background-color:#ffffff">
          <th colspan="9" scope="col"><?php //echo $row_count ?> </th>
          <th colspan="13" bgcolor="#3B81D5" scope="col"><div align="center">2018</div></th>
          <th colspan="52" bgcolor="#3B81D5" scope="col"><div align="center">2019</div></th>
          <th colspan="13" bgcolor="#3B81D5" scope="col"><div align="center">2020</div></th>
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
			if(is_null($row_cfPor['CR_Ship_Dt'])) { 
			 $por_year = 0; 
			 $por_week = 0;
			 $nxtwk_shp = 0;
			} else {
			 $por_year = date_format($row_cfPor['CR_Ship_Dt'], 'Y'); 
			 $por_week = date_format($row_cfPor['CR_Ship_Dt'], 'W');
			 
			 //Week proceeding Initial Ship Week
			 $nxtwk_shp = $por_week + 1;
			}
			
			if(is_null($row_cfPor['CR_Acti_Dt'])) { 
			 $por_acti_year = 0; 
			 $por_acti_week = 0;
			 $por_acti_month = 0;
			 $endweek = 0;
			} else {
			 $por_acti_year = date_format($row_cfPor['CR_Acti_Dt'], 'Y'); 
			 $por_acti_week = date_format($row_cfPor['CR_Acti_Dt'], 'W');
			 $por_acti_month = date_format($row_cfPor['CR_Acti_Dt'], 'm');
			
			//Week prior of month, before activation 
			 $prior_wk = date_format($row_cfPor['CR_Acti_Dt'], 'Y-m-01');
			 $strtodate = strtotime($prior_wk);
			 $realdate = date('W',$strtodate);
			 $endweek = $realdate - 1;
			 }
			 
		//Before Migration Date InitMig_Dt	
			if(is_null($row_cfPor['BefCR_Ship_Dt'])) { 
			 $por_year_Bef = 0; 
			 $por_week_Bef  = 0;
			 $nxtwk_shp_Bef  = 0;
			} else {
			 $por_year_Bef  = date_format($row_cfPor['BefCR_Ship_Dt'], 'Y'); 
			 $por_week_Bef  = date_format($row_cfPor['BefCR_Ship_Dt'], 'W');
			 
			 //Week proceeding Initial Ship Week
			 $nxtwk_shp_Bef  = $por_week_Bef  + 1;
			}
			
			if(is_null($row_cfPor['BefCR_Acti_Dt'])) { 
			 $por_acti_year_Bef  = 0; 
			 $por_acti_week_Bef  = 0;
			 $por_acti_month_Bef  = 0;
			 $endweek_Bef  = 0;
			} else {
			 $por_acti_year_Bef  = date_format($row_cfPor['BefCR_Acti_Dt'], 'Y'); 
			 $por_acti_week_Bef  = date_format($row_cfPor['BefCR_Acti_Dt'], 'W');
			 $por_acti_month_Bef  = date_format($row_cfPor['BefCR_Acti_Dt'], 'm');
			
			//Week prior of month, before activation 
			 $prior_wk_Bef  = date_format($row_cfPor['BefCR_Acti_Dt'], 'Y-m-01');
			 $strtodate_Bef  = strtotime($prior_wk_Bef );
			 $realdate_Bef  = date('W',$strtodate_Bef );
			 $endweek_Bef  = $realdate_Bef  - 1;
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
          <td align="center" <?php if($por_year == '2018' && $por_week == 40) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2018' && $por_acti_month == 10) { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2018' && $endweek == 40) { echo 'bgcolor="gray"';} else if($por_year == '2018' && $nxtwk_shp == 40) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $nxtwk_shp < 40 && $nxtwk_shp <52 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2018' && $por_week == 41) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2018' && $por_acti_month == 10) { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2018' && $endweek == 41) { echo 'bgcolor="gray"';} else if($por_year == '2018' && $nxtwk_shp == 41) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $nxtwk_shp < 41 && $nxtwk_shp <52 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2018' && $por_week == 42) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2018' && $por_acti_month == 10) { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2018' && $endweek == 42) { echo 'bgcolor="gray"';} else if($por_year == '2018' && $nxtwk_shp == 42) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $nxtwk_shp < 42 && $nxtwk_shp <52 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2018' && $por_week == 43) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2018' && $por_acti_month == 10) { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2018' && $endweek == 43) { echo 'bgcolor="gray"';} else if($por_year == '2018' && $nxtwk_shp == 43) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $nxtwk_shp < 43 && $nxtwk_shp <52 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2018' && $por_week == 44) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2018' && $por_acti_month == 11) { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2018' && $endweek == 44) { echo 'bgcolor="gray"';} else if($por_year == '2018' && $nxtwk_shp == 44) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $nxtwk_shp < 44 && $nxtwk_shp <52 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2018' && $por_week == 45) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2018' && $por_acti_month == 11) { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2018' && $endweek == 45) { echo 'bgcolor="gray"';} else if($por_year == '2018' && $nxtwk_shp == 45) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $nxtwk_shp < 45 && $nxtwk_shp <52 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2018' && $por_week == 46) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2018' && $por_acti_month == 11) { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2018' && $endweek == 46) { echo 'bgcolor="gray"';} else if($por_year == '2018' && $nxtwk_shp == 46) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $nxtwk_shp < 46 && $nxtwk_shp <52 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2018' && $por_week == 47) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2018' && $por_acti_month == 11) { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2018' && $endweek == 47) { echo 'bgcolor="gray"';} else if($por_year == '2018' && $nxtwk_shp == 47) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $nxtwk_shp < 47 && $nxtwk_shp <52 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2018' && $por_week == 48) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2018' && $por_acti_month == 12) { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2018' && $endweek == 48) { echo 'bgcolor="gray"';} else if($por_year == '2018' && $nxtwk_shp == 48) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $nxtwk_shp < 48 && $nxtwk_shp <52 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2018' && $por_week == 49) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2018' && $por_acti_month == 12) { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2018' && $endweek == 49) { echo 'bgcolor="gray"';} else if($por_year == '2018' && $nxtwk_shp == 49) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $nxtwk_shp < 49 && $nxtwk_shp <52 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2018' && $por_week == 50) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2018' && $por_acti_month == 12) { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2018' && $endweek == 50) { echo 'bgcolor="gray"';} else if($por_year == '2018' && $nxtwk_shp == 50) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $nxtwk_shp < 50 && $nxtwk_shp <52 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2018' && $por_week == 51) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2018' && $por_acti_month == 12) { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2018' && $endweek == 51) { echo 'bgcolor="gray"';} else if($por_year == '2018' && $nxtwk_shp == 51) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $nxtwk_shp < 51 && $nxtwk_shp <52 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2018' && $por_week == 52) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2018' && $por_acti_month == 12) { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2018' && $endweek == 52) { echo 'bgcolor="gray"';} else if($por_year == '2018' && $nxtwk_shp == 52) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $nxtwk_shp < 52 && $nxtwk_shp <52 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
<!--2019-->
          <td align="center" <?php if($por_year == '2019' && $por_week == 1) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '01') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==1) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 1) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 1) && (1 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 1 ) { echo 'bgcolor="gray"'; }?>><?php if($por_year_Bef == '2019' && $por_week_Bef == 1) { echo '&#8226;'; } else if($por_acti_year_Bef == '2019' && $por_acti_month_Bef == '01') { echo '&#8226;'; } else if($nxtwk_shp_Bef <> 0 && $por_acti_year_Bef == '2019' && $endweek_Bef ==1) { echo '&#8226;';} else if($por_year_Bef == '2019' && $nxtwk_shp_Bef == 1) {  echo '&#8226;'; } else if(($nxtwk_shp_Bef < 1) && (1 < $endweek_Bef) && ($nxtwk_shp_Bef != 0)) {  echo '&#8226;'; } else if($por_year_Bef == 2018 && $por_acti_year_Bef == 2019 & $endweek_Bef > 1 ) { echo '&#8226;'; }?></td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 2) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '01') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==2) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 2) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 2) && (2 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 2 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 3) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '01') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==3) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 3) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 3) && (3 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 3 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 4) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '01') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==4) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 4) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 4) && (4 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 4 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 5) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '02') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==5) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 5) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 5) && (5 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 5 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 6) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '02') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==6) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 6) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 6) && (6 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 6 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 7) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '02') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==7) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 7) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 7) && (7 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 7 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 8) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '02') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==8) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 8) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 8) && (8 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 8 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 9) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '03') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==9) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 9) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 9) && (9 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 9 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 10) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '03') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==10) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 10) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 10) && (10 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 10 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 11) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '03') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==11) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 11) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 11) && (11 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 11 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 12) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '03') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==12) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 12) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 12) && (12 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 12 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 13) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '03') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==13) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 13) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 13) && (13 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 13 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 14) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '04') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==14) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 14) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 14) && (14 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 14 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 15) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '04') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==15) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 15) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 15) && (15 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 15 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 16) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '04') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==16) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 16) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 16) && (16 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 16 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 17) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '04') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==17) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 17) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 17) && (17 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 17 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 18) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '05') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==18) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 18) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 18) && (18 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 18 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 19) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '05') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==19) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 19) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 19) && (19 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 19 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 20) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '05') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==20) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 20) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 20) && (20 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 20 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 21) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '05') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==21) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 21) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 21) && (21 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 21 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 22) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '06') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==22) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 22) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 22) && (22 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 22 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 23) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '06') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==23) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 23) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 23) && (23 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 23 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 24) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '06') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==24) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 24) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 24) && (24 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 24 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 25) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '06') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==25) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 25) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 25) && (25 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 25 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 26) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '06') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==26) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 26) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 26) && (26 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 26 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 27) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '07') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==27) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 27) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 27) && (27 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 27 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 28) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '07') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==28) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 28) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 28) && (28 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 28 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 29) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '07') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==29) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 29) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 29) && (29 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 29 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 30) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '07') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==30) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 30) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 30) && (30 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 30 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 31) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '08') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==31) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 31) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 31) && (31 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 31 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 32) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '08') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==32) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 32) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 32) && (32 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 32 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 33) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '08') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==33) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 33) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 33) && (33 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 33 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 34) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '08') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==34) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 34) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 34) && (34 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 34 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 35) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '09') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==35) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 35) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 35) && (35 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 35 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 36) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '09') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==36) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 36) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 36) && (36 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 36 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 37) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '09') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==37) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 37) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 37) && (37 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 37 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 38) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '09') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==38) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 38) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 38) && (38 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 38 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 39) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '09') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==39) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 39) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 39) && (39 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 39 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 40) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == 10) { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==40) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 40) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 40) && (40 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 40 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 41) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == 10) { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==41) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 41) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 41) && (41 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 41 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 42) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == 10) { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==42) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 42) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 42) && (42 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 42 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 43) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == 10) { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==43) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 43) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 43) && (43 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 43 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 44) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == 11) { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==44) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 44) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 44) && (44 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 44 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 45) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == 11) { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek ==45) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 45) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 45) && (45 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 45 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 46) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == 11) { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek == 46) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 46) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 46) && (46 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 46 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 47) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == 11) { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek == 47) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 47) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 47) && (47 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 47 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 48) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == 12) { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek == 48) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 48) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 48) && (48 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 48 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 49) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == 12) { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek == 49) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 49) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 49) && (49 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 59 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 50) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == 12) { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek == 50) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 50) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 50) && (50 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 50 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 51) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == 12) { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek == 51) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 50) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 51) && (51 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 51 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2019' && $por_week == 52) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == 12) { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2019' && $endweek == 52) { echo 'bgcolor="gray"';} else if($por_year == '2019' && $nxtwk_shp == 52) {  echo 'bgcolor="gray"'; } else if(($nxtwk_shp < 52) && (52 < $endweek) && ($nxtwk_shp != 0)) {  echo 'bgcolor="gray"'; } else if($por_year == 2018 && $por_acti_year == 2019 & $endweek > 52 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
<!--2020-->
          <td align="center" <?php if($por_year == '2020' && $por_week == 1) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2020' && $por_acti_month == '01') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek == 1) { echo 'bgcolor="gray"';} else if($por_year == '2020' && $nxtwk_shp == 1) {  echo 'bgcolor="gray"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $nxtwk_shp < 1 && $nxtwk_shp < 1) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 2) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2020' && $por_acti_month == '01') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek == 2) { echo 'bgcolor="gray"';} else if($por_year == '2020' && $nxtwk_shp == 2) {  echo 'bgcolor="gray"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $nxtwk_shp < 2 && $nxtwk_shp < 2 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 3) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2020' && $por_acti_month == '01') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek == 3) { echo 'bgcolor="gray"';} else if($por_year == '2020' && $nxtwk_shp == 3) {  echo 'bgcolor="gray"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $nxtwk_shp < 3 && $nxtwk_shp < 3 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 4) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2020' && $por_acti_month == '01') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek == 4) { echo 'bgcolor="gray"';} else if($por_year == '2020' && $nxtwk_shp == 4) {  echo 'bgcolor="gray"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $nxtwk_shp < 4 && $nxtwk_shp < 4 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 5) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2020' && $por_acti_month == '02') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek == 5) { echo 'bgcolor="gray"';} else if($por_year == '2020' && $nxtwk_shp == 5) {  echo 'bgcolor="gray"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $nxtwk_shp < 5 && $nxtwk_shp < 5 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 6) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2020' && $por_acti_month == '02') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek == 6) { echo 'bgcolor="gray"';} else if($por_year == '2020' && $nxtwk_shp == 6) {  echo 'bgcolor="gray"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $nxtwk_shp < 6 && $nxtwk_shp < 6 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 7) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2020' && $por_acti_month == '02') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek == 7) { echo 'bgcolor="gray"';} else if($por_year == '2020' && $nxtwk_shp == 7) {  echo 'bgcolor="gray"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $nxtwk_shp < 7 && $nxtwk_shp < 7 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 8) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2020' && $por_acti_month == '02') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek == 8) { echo 'bgcolor="gray"';} else if($por_year == '2020' && $nxtwk_shp == 8) {  echo 'bgcolor="gray"'; } else if($por_year == 2019 && $por_acti_year == 2002 & $nxtwk_shp < 8 && $nxtwk_shp < 8 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 9) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2020' && $por_acti_month == '03') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek == 9) { echo 'bgcolor="gray"';} else if($por_year == '2020' && $nxtwk_shp == 9) {  echo 'bgcolor="gray"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $nxtwk_shp < 9 && $nxtwk_shp < 9 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 10) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2020' && $por_acti_month == '03') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek == 10) { echo 'bgcolor="gray"';} else if($por_year == '2020' && $nxtwk_shp == 10) {  echo 'bgcolor="gray"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $nxtwk_shp < 10 && $nxtwk_shp < 10 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 11) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2020' && $por_acti_month == '03') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek == 11) { echo 'bgcolor="gray"';} else if($por_year == '2020' && $nxtwk_shp == 11) {  echo 'bgcolor="gray"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $nxtwk_shp < 11 && $nxtwk_shp < 11 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 12) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2020' && $por_acti_month == '03') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek == 12) { echo 'bgcolor="gray"';} else if($por_year == '2020' && $nxtwk_shp == 12) {  echo 'bgcolor="gray"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $nxtwk_shp < 12 && $nxtwk_shp < 12 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
          <td align="center" <?php if($por_year == '2020' && $por_week == 13) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2020' && $por_acti_month == '03') { echo 'bgcolor="#3B81D5"'; } else if($nxtwk_shp <> 0 && $por_acti_year == '2020' && $endweek == 13) { echo 'bgcolor="gray"';} else if($por_year == '2020' && $nxtwk_shp == 13) {  echo 'bgcolor="gray"'; } else if($por_year == 2019 && $por_acti_year == 2020 & $nxtwk_shp < 13 && $nxtwk_shp < 13 ) { echo 'bgcolor="gray"'; }?>>&nbsp;</td>
		</tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>