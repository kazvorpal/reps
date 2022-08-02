<?php include ("../includes/functions.php");?>
<?php include ("../db_conf.php");?>
<?php include ("../data/emo_data.php");?>
<?php include ("../sql/level2.php");?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>

<link href="../css/bootstrap-3.3.4.css" rel="stylesheet" type="text/css">
<script src="../bootstrap/js/jquery-1.11.2.min.	"></script>
</head>
<body>
	<lable>Show Level</lable>
<div align="center" style="width:200px;">
<form method="post">
  
    <select name="levels" id="levels" title="view" onchange='this.form.submit()' class="form-control">
      
      <option value="0, 1" <?php if(isset($_POST['levels'])) {
								  if($levels == "0, 1") { echo 'selected="selected"' ;
								  } else { 
								  echo '';
								  }
							  }?>
      >Level 1</option>
      <option value="0, 1, 2" <?php if(isset($_POST['levels'])) {
									  if($_POST['levels'] == "0, 1, 2") { echo 'selected="selected"' ;
									  } else {
										  echo '';
									  }
	  							}?>
          >Level 2</option>
      <option value="0, 1, 2, 3" <?php if(isset($_POST['levels'])) {
										  if($_POST['levels'] == "0, 1, 2, 3") { echo 'selected="selected"' ;
										  } else {
											  echo '';
										  }
										  }?>
          >Level 3</option>
      <option value="0, 1, 2, 3, 4" <?php if(isset($_POST['levels'])) {
											  if($_POST['levels'] == "0, 1, 2, 3, 4") { echo 'selected="selected"' ;
											  } else {
												  echo '';
											  }
											  }?>
				  >Level 4</option>
       <option value="0, 1, 2, 3, 4, 5, 6" <?php if(isset($_POST['levels'])) {
												  if($_POST['levels'] == "0, 1, 2, 3, 4, 5, 6") { echo 'selected="selected"' ;
											  } else {
												  echo '';
												  }
													}?>
       >All </option>
    </select> 
    
  
</form>

</div>
<br>

<table width="100%" border="0" class="table-bordered table-hover table-striped" style="font-size:11px" >
  <tbody> 
    <tr style="font-weight:bold; font-size:12px">
      <td>Task Name</td>
      <td>Duration</td>
      <td>Start</td>
      <td>Finish</td>
      <td>Actual Finish</td>
      <td>% Complete</td>
      <td>Hardware</td>
    </tr>
   
    <?php while ($row_l2 = sqlsrv_fetch_array($stmt_l2, SQLSRV_FETCH_ASSOC)){?>
        <?php    
		// Column Indenting
		$column = $row_l2['TASK_OTLN_LVL']*40;
		
		$bold_lvl = $row_l2['TASK_OTLN_LVL'];
		$font_weight = '';
		
		// Level 1 Font size and decoration
		if($bold_lvl == 1) {
		$font_weight = 'font-weight:bold; font-size:12px';
		}
		// Days = Task Duration / 8
		$day_dur = $row_l2['TASK_DUR']/8;
	
		?>
    <tr>
      <td style="<?php echo $font_weight ?>"><span style="padding-left:<?php echo htmlspecialchars($column);?>px"><?php echo htmlspecialchars($row_l2['TASK_NM']); ?></span></td>
      <td style="<?php echo $font_weight ?>"><?php echo htmlspecialchars($day_dur);;?>d</td>
      <td style="<?php echo $font_weight ?>"><?php echo date_format($row_l2['TASK_STRT_DT'], 'm-d-Y'); ?></td>
      <td style="<?php echo $font_weight ?>"><?php echo date_format($row_l2['TASK_FNSH_DT'], 'm-d-Y'); ?></td>
      <td style="<?php echo $font_weight ?>"><?php 
												if($row_l2['TASK_ACTL_FNSH_DT'] != '0000-00-00' ) {				
													if(is_null($row_l2['TASK_ACTL_FNSH_DT'])) {
														echo '';
													} else {
														echo date_format($row_l2['TASK_ACTL_FNSH_DT'], 'm-d-Y'); 
														}
												}
											 ?>
      </td>
                
      <td><?php echo htmlspecialchars($row_l2['TASK_PRCT_CMPLT']); ?>%</td>
      <td><?php echo htmlspecialchars($row_l2['HW_ID']); ?></td>
    </tr>
    <?php } ;?>
  </tbody>
</table>


</body>
</html>