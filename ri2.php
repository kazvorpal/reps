<?php include ("includes/functions.php");?>
<?php include ("db_conf.php");?>
<?php include ("data/emo_data.php");?>
<?php
								$proj_name = $_GET['prj_name'];
									
								$sql_risk_issue = "SELECT*  
												FROM [COX_QA].[EPS].[RiskAndIssues]
												WHERE [Project Name] LIKE '%$proj_name%' 
												AND ([Status] = 'New' 
												OR [Status] = 'Assigned'
												OR [Status] = 'Under Review')";
								$stmt_risk_issue = sqlsrv_query( $conn_COX_QA, $sql_risk_issue );
								//$row_risk_issue = sqlsrv_fetch_array($stmt_risk_issue, SQLSRV_FETCH_ASSOC);
								// echo $row_risk_issue['Risk_Issue_Name']; 			
								
			$ri_count = $_GET['count'];										
 ?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Untitled Document</title>
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
</head>
<body>

<h3 align="center">Project Risk and Issues Found </h3>
<div align="center">
<table width="98%" border="0" cellpadding="5" class="table-bordered table-hover table-striped">
  <tbody>
    <tr>
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