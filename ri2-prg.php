<?php include ("includes/functions.php");?>
<?php include ("db_conf.php");?>
<?php include ("data/emo_data.php");?>
<?php
								$ri_region = $_GET['region'];
								$ri_program = $_GET['program'];
								$ri_fscl_yr = $_GET['fscl_year'];
								
								$sql_risk_issue = "SELECT Program, Created, [Disposition Status], ID, [Risk / Issue Name], [Fiscal Year]
													FROM [COX_QA].[EPS].[RiskAndIssues]
													WHERE [Program] LIKE '%$ri_program%' 
													AND [Risk or Issue Level] = 'Program'
													AND [Region] LIKE '%$ri_region%' 
													AND [Fiscal Year] = '$ri_fscl_yr'
													AND ([Status] = 'New' 
													OR [Status] = 'Assigned'
													OR [Status] = 'Under Review') ";
								$stmt_risk_issue = sqlsrv_query( $conn_COX_QA, $sql_risk_issue );
								//$row_risk_issue = sqlsrv_fetch_array($stmt_risk_issue, SQLSRV_FETCH_ASSOC);
//								echo $sql_risk_issue;
//								exit;
						
 ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
</head>

<body>
<?php // echo $ri_program . '</br>' . $ri_region ?>
<h3 align="center">Program Risks and Issues Found </h3>
<div align="center">
  <table width="98%" border="0" cellpadding="5" class="table-bordered table-hover table-striped">
    <tbody>
    <tr style="background-color:#00aaf5">
      <td bgcolor="#E7E5E5"><strong>Name</strong></td>
      <td bgcolor="#E7E5E5"><strong>Created</strong></td>
      <td bgcolor="#E7E5E5"><strong>Disposition</strong></td>
    </tr>
    <?php while ($row_risk_issue = sqlsrv_fetch_array($stmt_risk_issue, SQLSRV_FETCH_ASSOC)){ ?>
    <tr>
      <td><a href="https://coxcomminc.sharepoint.com/sites/pwaeng/Lists/Risk%20and%20Issues/DispForm.aspx?ID=<?php echo $row_risk_issue['ID']; ?>" target="_blank"><?php echo $row_risk_issue['Risk / Issue Name']; ?></a></td>
      <td><?php echo date_format($row_risk_issue['Created'], 'm-d-Y'); ?></td>
      <td><?php echo $row_risk_issue['Disposition Status']; ?></td>
    </tr>
    <?php } ?>
  </tbody>
</table>
</div>
<p>&nbsp;</p>
</body>
</html>