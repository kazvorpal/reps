<?php 
include ("../../includes/functions.php");
include ("../../db_conf.php");
include ("../../data/emo_data.php");
include_once("../../sql/toolTip.php");
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Tool Tips</title>
</head>
<style>
.fineFont {
  font-size: 12px;
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"> 
  <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 

<body>
<div class="fineFont">
<table cellpadding="0" cellspacing="0" valign="top" title="" summary="" class="table table-striped">
    <tr>
      <th><?php echo $row_tooltip['ToolTip_Nm'] ?></th>
    </tr>
    <tr>
      <td>
        <?php echo $row_tooltip['ToolTip_Desc'] ?>
      </td>
    </tr>
  </table>
</div>
</body>
</html>