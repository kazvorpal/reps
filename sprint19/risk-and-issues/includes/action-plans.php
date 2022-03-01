<?php include ("../../includes/functions.php");?>
<?php include ("../../db_conf.php");?>
<?php include ("../../data/emo_data.php");?>
<?php // include ("../sql/collapse.php");?>
<?php //include ("../../sql/project_by_id.php");?>
<?php //include ("../../sql/ri_filter_vars.php");?>
<?php //include ("../../sql/ri_filters.php");?>
<?php //include ("../../sql/ri_filtered_data.php");?>
<?php //include ("../../sql/RI_Internal_External.php");
  $username = $_GET['username'];
  $temp_id = $_GET['tempid'];
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<style>
    .box {
    border: 1px solid #BCBCBC;
    border-radius: 5px;
    padding: 5px;
    }
    .finePrint {
    font-size: 9px;  
    }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"> 
  <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">

  
  <script language="javascript">
	$(document).ready(function() {
    	$('#fiscal_year').multiselect({
          includeSelectAllOption: true,
        });
		$('#pStatus').multiselect({
          includeSelectAllOption: true,
        });
		$('#owner').multiselect({
          includeSelectAllOption: true,
        });
		$('#program').multiselect({
          includeSelectAllOption: true,
        });
		$('#subprogram').multiselect({
          includeSelectAllOption: true,
        });
		$('#region').multiselect({
          includeSelectAllOption: true,
        });
		$('#market').multiselect({
          includeSelectAllOption: true,
        });
    	$('#facility').multiselect({
          includeSelectAllOption: true,
        });
		$('#fiscal_year2').multiselect({
          includeSelectAllOption: true,
        });
		$('#program2').multiselect({
          includeSelectAllOption: true,
        });
  });
</script>
<script language="JavaScript">
function toggle(source) {
  checkboxes = document.getElementsByName('proj_select');
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
  }
}
</script>
</head>

<body style="background: #F8F8F8;">


<table width="100%" border="0" cellpadding="5" cellspacing="5">
              <tbody>
                <form action="action-plans-do.php" id="actionPlanForm" name="actionPlanForm" method="post">
                  <tr>
                    <td width="96%">
                          <input type="text" name="ActionPlan" id="ActionPlan" class="form-control" required="required">
                          <input type="hidden" name="user" value="<?php echo $username ?>">
                          <input type="hidden" name="tempID"value="<?php echo $temp_id ?>">
                    </td>
                    <td width="4%"><input type="submit" class="btn btn-primary" value="+ Action Plan"></td>
                  </tr>
                </form>
                <tr>
                  <td>.</td>
                  <td></td>
                </tr>
                <tr>
                  <td colspan="2" align="left">
                  <strong>Action Plan Status Log</strong>  
                    <table width="100%" border="0" cellpadding="5" cellspacing="5" class="table table-bordered table-hover">
                    <tbody>
                      <tr>
                        <th width="24%" bgcolor="#EFEFEF">User</th>
                        <th width="55%" bgcolor="#EFEFEF">Update</th>
                        <th width="21%" bgcolor="#EFEFEF">Timestamp</th>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                    </tbody>
                    </table>

                  </td>
                </tr>
              </tbody>
</table>
</body>
</html>