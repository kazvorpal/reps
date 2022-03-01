<?php include ("../includes/functions.php");?>
<?php include ("../db_conf.php");?>
<?php include ("../data/emo_data.php");?>
<?php include ("../sql/master_lists.php");?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Edit Program</title>
<link href="../css/bootstrap-3.3.4.css" rel="stylesheet" type="text/css">
<script src="../bootstrap/js/jquery-1.11.2.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
</head>
<body>

<div align="center"><h3>Edit Program</h3>
<div style="padding:10px; margin-top:-20px">
<?php echo htmlspecialchars($row_ml_program['Program_Nm']);?>
</div>
<form>
<table width="97%" border="0">
  <tbody>
    <tr>
      <th align="left" scope="row">Program Name:</th>
      <td align="left"><input type="text" class="form-control" value="<?php echo htmlspecialchars($row_ml_program['Program_Nm']);?>"></td>
    </tr>
    <tr>
      <th align="left" scope="row">EPS Program Name:</th>
      <td align="left"><input type="text" class="form-control" value="<?php echo htmlspecialchars($row_ml_program['EPS_Program_Nm']);?>"></td>
    </tr>
    <tr>
      <th align="left" scope="row">PDG Lead:</th>
      <td align="left"><input type="text" class="form-control" value="<?php echo htmlspecialchars($row_ml_program['PDGLead_Names']);?>"></td>
    </tr>
    <tr>
      <th align="left" scope="row">Business Owner:</th>
      <td align="left"><input type="text" class="form-control" value="<?php echo htmlspecialchars($row_ml_program['BusinessOwner_Names']);?>"></td>
    </tr>
    <tr>
      <th align="left" scope="row">Program Type:</th>
      <td align="left"><input type="text" class="form-control" value="<?php echo htmlspecialchars($row_ml_program['ProgramType_Cd']);?>"></td>
    </tr>
    <tr>
      <th align="left" scope="row">Program Acronym: </th>
      <td align="left"><input type="text" class="form-control" value="<?php echo htmlspecialchars($row_ml_program['Program_Cd']);?>"></td>
    </tr>
    <tr>
      <th align="left" scope="row">Oracle Acronym:</th>
      <td align="left"><input type="text" class="form-control" value="<?php echo htmlspecialchars($row_ml_program['Oracle_Abb']);?>"></td>
    </tr>
    <tr>
      <th align="left" scope="row">Scope Descriptor:</th>
      <td align="left">
      	<textarea rows="3" class="form-control"><?php echo htmlspecialchars($row_ml_program['ScopeDescriptor_Desc']);?></textarea>
      </td>
    </tr>
    <tr>
      <th align="left" scope="row">Activity Descriptor:</th>
      <td align="left"><input type="text" class="form-control" value="<?php echo htmlspecialchars($row_ml_program['ActivityDescriptor_Lst']);?>"></td>
    </tr>
    <tr>
      <th align="left" scope="row">FTPM:</th>
      <td align="left"><input type="text" class="form-control" value="<?php echo htmlspecialchars($row_ml_program['FTPMUse_Flg']);?>"></td>
    </tr>
    <tr>
      <th align="left" scope="row">WATTS Approval Status:</th>
      <td align="left"><input type="text" class="form-control" value="<?php echo htmlspecialchars($row_ml_program['WATTSApproval_Status']);?>"></td>
    </tr>
    <tr>
      <th align="left" scope="row">WATTS Approval Date:</th>
      <td align="left"><input type="text" class="form-control" value="<?php echo convtimex($row_ml_program['WATTSApproval_Dt']);?>"></td>
    </tr>
    <tr>
      <th align="left" scope="row">Active:</th>
      <td align="left"><input type="text" class="form-control" value="<?php echo htmlspecialchars($row_ml_program['Active_Flg']);?>"></td>
    </tr>
  </tbody>
</table>
		<div style="padding:5px">
        <input type="submit" class="form-control btn-primary">
        </div>
</form>
</div>
</body>
</html>