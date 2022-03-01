<?php include ("../includes/functions.php");?>
<?php include ("../db_conf.php");?>
<?php include ("../data/emo_data.php");?>
<?php include ("../sql/master_lists.php");?>
<?php include ("../sql/update-time.php");?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>EMO Programs</title>
<!-- <link href="../css/bootstrap.css" rel="stylesheet" type="text/css"> -->
<link href="../css/bootstrap-3.3.4.css" rel="stylesheet" type="text/css">
<script src="../bootstrap/js/jquery-1.11.2.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="../colorbox-master/example1/colorbox.css">
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<link href="../css/bootstrap-3.3.4.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../css/custom.css">

<script src="../colorbox-master/jquery.colorbox.js"></script>
<script>
$(document).ready(function(){
				//Examples of how to assign the Colorbox event to elements
				$(".group1").colorbox({rel:'group1'});
				$(".group2").colorbox({rel:'group2', transition:"fade"});
				$(".group3").colorbox({rel:'group3', transition:"none", width:"75%", height:"75%"});
				$(".group4").colorbox({rel:'group4', slideshow:true});
				$(".ajax").colorbox();
				$(".youtube").colorbox({iframe:true, innerWidth:640, innerHeight:390});
				$(".vimeo").colorbox({iframe:true, innerWidth:500, innerHeight:409});
				$(".iframe").colorbox({iframe:true, width:"560", height:"800", scrolling:false});
				$(".dno").colorbox({iframe:true, width:"90%", height:"50%", scrolling:false});
				$(".mapframe").colorbox({iframe:true, width:"90%", height:"98%", scrolling:true});
				$(".miframe").colorbox({iframe:true, width:"560", height:"650", scrolling:false});
				$(".inline").colorbox({inline:true, width:"50%"});
				$(".callbacks").colorbox({
					onOpen:function(){ alert('onOpen: colorbox is about to open'); },
					onLoad:function(){ alert('onLoad: colorbox has started to load the targeted content'); },
					onComplete:function(){ alert('onComplete: colorbox has displayed the loaded content'); },
					onCleanup:function(){ alert('onCleanup: colorbox has begun the close process'); },
					onClosed:function(){ alert('onClosed: colorbox has completely closed'); }
				});

				$('.non-retina').colorbox({rel:'group5', transition:'none'})
				$('.retina').colorbox({rel:'group5', transition:'none', retinaImage:true, retinaUrl:true});
				
				//Example of preserving a JavaScript event for inline calls.
				$("#click").click(function(){ 
					$('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("Open this window again and this message will still be here.");
					return false;
				});
			});
function MM_setTextOfTextfield(objId,x,newText) { //v9.0
  with (document){ if (getElementById){
    var obj = getElementById(objId);} if (obj) obj.value = newText;
  }
}
        </script>
</head>
<body>
<!--Menu-->
<?php include ("../includes/menu.php");?>
<!--Title-->
<div align="center">
<h3>EMO Programs</h3>
</div>
<div align="center">
<table width="97%" class="table-bordered table-hover table-striped" style="font-size:10px">
    <thead>
    <tr style="font-size:10px; background-color:#00aaf5; color:#FFFFFF" class="sticky">
      <th style="padding:3px" class="sticky">Program Name</th>
      <th style="padding:3px" class="sticky">EPS Program</th>
      <th style="padding:3px" class="sticky">PDG Lead</th>
      <th style="padding:3px" class="sticky">Business Owner</th>
      <th style="padding:3px" class="sticky">Program type</th>
      <th style="padding:3px" class="sticky">Program Acronym</th>
      <th style="padding:3px" class="sticky">Oracle acronym</th>
      <th style="padding:3px" width="500" class="sticky">Scope Descriptor</th>
      <th style="padding:3px" class="sticky">Activity Descriptor</th>
      <th style="padding:3px" class="sticky">FTPM - Y or N?</th>
      <th style="padding:3px" class="sticky">WATTS Approval Status</th>
      <th style="padding:3px" class="sticky">WATTS Approval Date</th>
      <th style="padding:3px" class="sticky">Edit</th>
    </tr>
    </thead>
    <tbody>
    <?php while( $row_ml_program = sqlsrv_fetch_array( $stmt_ml_program, SQLSRV_FETCH_ASSOC)) {?>
    <tr>
      <td style="padding:3px"><?php echo htmlspecialchars($row_ml_program['Program_Nm']);?></td>
      <td style="padding:3px"><?php echo htmlspecialchars($row_ml_program['EPS_Program_Nm']);?></td>
      <td style="padding:3px"><?php echo htmlspecialchars($row_ml_program['PDGLead_Names']);?></td>
      <td style="padding:3px"><?php echo htmlspecialchars($row_ml_program['BusinessOwner_Names']);?></td>
      <td style="padding:3px"><?php echo htmlspecialchars($row_ml_program['ProgramType_Cd']);?></td>
      <td style="padding:3px"><?php echo htmlspecialchars($row_ml_program['Program_Cd']);?></td>
      <td style="padding:3px"><?php echo htmlspecialchars($row_ml_program['Oracle_Abb']);?></td>
      <td style="padding:3px"><?php echo htmlspecialchars($row_ml_program['ScopeDescriptor_Desc']);?></td>
      <td style="padding:3px"><?php echo htmlspecialchars($row_ml_program['ActivityDescriptor_Lst']);?></td>
      <td style="padding:3px"><?php echo htmlspecialchars($row_ml_program['FTPMUse_Flg']);?></td>
      <td style="padding:3px"><?php echo htmlspecialchars($row_ml_program['WATTSApproval_Status']);?></td>
      <td style="padding:3px"><?php echo convtimex($row_ml_program['WATTSApproval_Dt']);?></td>
      <td style="padding:3px" align="center"><a href="emo_prog_e.php" class="iframe"><span class="glyphicon glyphicon-edit" style="z-index:0; position:relative"></span></a></td>
    </tr>
    <?php } ?>
    </tbody>
</table>
</div>
</body>
</html>