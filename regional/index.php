<?php include ("../includes/functions.php");?>
<?php include ("../db_conf.php");?>
<?php include ("../data/emo_data.php");?>
<?php include ("../sql/collapse.php");?>
<?php include ("../sql/update-time.php");?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<title>RePS - Regional Project Summary</title>
<link rel="shortcut icon" href="favicon.ico"/>
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
				$(".iframe").colorbox({iframe:true, width:"900", height:"600", scrolling:false});
				$(".dno").colorbox({iframe:true, width:"60%", height:"50%", scrolling:true});
				$(".mapframe").colorbox({iframe:true, width:"90%", height:"75%", scrolling:true});
				$(".eqframe").colorbox({iframe:true, width:"97%", height:"90%", scrolling:true});
				$(".miframe").colorbox({iframe:true, width:"1500", height:"650", scrolling:false});
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

$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});
</script>
	<style type="text/css">
        .popover{
            max-width:600px;
        }
    </style>
</head>

<body onload="myFunction()" style="margin:0;">
<!--loader-->
<div id="loader"></div>
<div style="display:block;" id="myDiv" class="animate-bottom"> <!-- make 'none' for production mode-->
<!--menu-->
<?php include ("../includes/menu.php");?>
<section>
  <div class="row" align="center">
    <div style="width:98%">
      <div class="col-xs-12 text-center"> 
        <h3>Regional View for <?php echo htmlspecialchars($fsyear); ?> </h3>
        <div align="center">
        <form method="post">
            <table width="175" border="0">
              <tbody>
                <tr align="center">
                  <td>        
                      <select name="fsyear" id="fsyear" class="form-control" onchange='this.form.submit()'>
						              <option value="2024" <?php if($fsyear == '2024'){ echo 'selected="selected"'; } ?>>Fiscal Year 2024</option>
                          <option value="2023" <?php if($fsyear == '2023'){ echo 'selected="selected"'; } ?>>Fiscal Year 2023</option>
                          <option value="2022" <?php if($fsyear == '2022'){ echo 'selected="selected"'; } ?>>Fiscal Year 2022</option>
                          <option value="2021" <?php if($fsyear == '2021'){ echo 'selected="selected"'; } ?>>Fiscal Year 2021</option>
                          <option value="2020" <?php if($fsyear == '2020'){ echo 'selected="selected"'; } ?>>Fiscal Year 2020</option>
                      </select>
                  </td>
                </tr>
              </tbody>
            </table>
		</form>
        </div>
        <h5><?php echo $row_pcount['pcount'];?> Projects Found </h5>
        <div align="right" class="form-group">
	<?php include ("../legend.php");?>
</div> 
<?php 
if($fsyear >= '2021') {
	include ("2021.php");
} 
if($fsyear <= '2020') {
	include ("2020.php");
} 
?>
</div>
    </div>
  </div>
</section>
<section>
  <div class="container" align="left">
    <div class="row">
    </div>
  </div>
</section>
<footer class="footer">
  <div class="container" align="center">
    <div class="row">
      <div class="col-xs-12"><br>
      	<img src="../images/emo.png" width="225" height="40" alt=""/>
<p>Copyright© 2019. Cox Communications. All rights reserved.</p>
        <span style="font-size:9px" >This site contains confidential information intended solely for the use of authorized users of Cox Communications, Engineering Management Office. If you are not authorized to view this site, you
should exit immediately and are hereby notified that disclosure, copying, distribution, or reuse of this message or any information contained therein by any other person is strictly prohibited.</span> </div>
    </div>
  </div>
</footer>
</div>
<script src="../js/bootstrap-3.3.4.js" type="text/javascript"></script>
<script>
	var myVar;
	
	function myFunction() {
	  myVar = setTimeout(showPage, 1000);
	}
	
	function showPage() {
	  document.getElementById("loader").style.display = "none";
	  document.getElementById("myDiv").style.display = "block";
	}
</script>
<script>
  $(function(){
    $('[data-toggle="popover"]').popover({ 
      html : true, 
      content: function() {
        return $('#popover_content').html();
      }
    });
  });
</script>
</body>
</html>