<?php include ("includes/functions.php");?>
<?php include ("db_conf.php");?>
<?php include ("data/emo_data.php");?>
<?php
								$prj_name = $_GET['proj_name'];
//								
//								mysql_select_db($database_data, $data);
//								$query_risk_issue = "SELECT* 
//								FROM risk_issues WHERE risk_issues.Project_Name = '$prj_name' AND risk_issues.Status <> 'Resolved' ";
//								$risk_issue = mysql_query($query_risk_issue, $data) or die(mysql_error());
//								$row_risk_issue = mysql_fetch_assoc($risk_issue);
//								$totalRows_risk_issue = mysql_num_rows($risk_issue);
								$sql_risk_issue = "SELECT*  
												FROM [EPS].[RiskAndIssues]
												WHERE [Project Name] LIKE '%$prj_name%' 
												AND ([Status] = 'New' 
												OR [Status] = 'Assigned'
												OR [Status] = 'Review')";
								$stmt_risk_issue = sqlsrv_query( $conn_ODS, $sql_risk_issue );												

								// echo $row_risk_issue['Risk_Issue_Name']; 								
 ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
</head>

<body>

<h3 align="center">Project Risk and Issues</h3>
<div align="center">
<table width="98%" border="0" cellpadding="5" class="table-bordered table-hover table-striped">
  <tbody>
    <tr style="font-size:12px">
      <td bgcolor="#E7E5E5"><strong>Name</strong></td>
      <td width="250" bgcolor="#E7E5E5"><strong>Description</strong></td>
      <td bgcolor="#E7E5E5"><strong>Prog Mgr</strong></td>
      <td bgcolor="#E7E5E5"><strong>Created By</strong></td>
      <td bgcolor="#E7E5E5"><strong>Proj Name</strong></td>
      <td bgcolor="#E7E5E5"><strong>Project Mgr.</strong></td>
      <td bgcolor="#E7E5E5"><strong>Owner</strong></td>
      <td bgcolor="#E7E5E5"><strong>Status</strong></td>
      <td bgcolor="#E7E5E5"><strong>Probability</strong></td>
      <td bgcolor="#E7E5E5"><strong>Dispo</strong></td>
      <td bgcolor="#E7E5E5"><strong>Created</strong></td>
      <td bgcolor="#E7E5E5"><strong>Impact</strong></td>
      <td bgcolor="#E7E5E5"><strong>Impacted</strong></td>
      <td bgcolor="#E7E5E5"><strong>Program</strong></td>
      <td bgcolor="#E7E5E5"><strong>Reg</strong></td>
      <td bgcolor="#E7E5E5"><strong>Mark</strong></td>
      <td bgcolor="#E7E5E5"><strong>Fac</strong></td>
      <td bgcolor="#E7E5E5"><strong>Action Plan</strong></td>
      <td bgcolor="#E7E5E5"><strong>Resolution</strong></td>
    </tr>
    <?php while ($row_risk_issue = sqlsrv_fetch_array($stmt_risk_issue, SQLSRV_FETCH_ASSOC)){ ?>
    <tr valign="top" style="font-size:10px">
      <td><?php echo $row_risk_issue['Risk / Issue Name']; ?></td>
      <td><?php echo $row_risk_issue['Description']; ?></td>
      <td><?php echo $row_risk_issue['Program Manager']; ?></td>
      <td><?php echo $row_risk_issue['Created By']; ?></td>
      <td><?php echo $row_risk_issue['Project Name']; ?></td>
      <td><?php echo $row_risk_issue['Project Manager']; ?></td>
      <td><?php echo $row_risk_issue['Owner (EMO)']; ?></td>
      <td><?php echo $row_risk_issue['Status']; ?></td>
      <td><?php echo $row_risk_issue['Probability (Risk Only)']; ?></td>
      <td><?php echo $row_risk_issue['Disposition Status']; ?></td>
      <td><?php echo date_format($row_risk_issue['Created'], 'Y-m-d'); ?></td>
      <td><?php echo $row_risk_issue['Impact']; ?></td>
      <td><?php echo $row_risk_issue['Impacted Areas']; ?></td>
      <td><?php echo $row_risk_issue['Program']; ?></td>
      <td><?php echo $row_risk_issue['Region']; ?></td>
      <td><?php echo $row_risk_issue['Market']; ?></td>
      <td><?php echo $row_risk_issue['Facility']; ?></td>
      <td><?php echo $row_risk_issue['Action Plan']; ?></td>
      <td><?php echo $row_risk_issue['Resolution']; ?></td>
    </tr>
    <?php } ?>
  </tbody>
</table>
</div>
<p>&nbsp;</p>
</body>
</html>