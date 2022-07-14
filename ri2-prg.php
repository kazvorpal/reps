<?php include ("includes/functions.php");?>
<?php include ("db_conf.php");?>
<?php include ("data/emo_data.php");?>
<?php include ("sql/MS_Users_prg.php");?>
<?php
								//DECLARE
                $uid = $_GET['uid'];
                $ri_region = $_GET['region'];
								$ri_program = $_GET['program'];
								$ri_fscl_yr = $_GET['fscl_year'];
                $ri_proj_nm = $_GET['proj_nm'];
								
								//OPEN PROGRAM RISK AND ISSUES 	
								$sql_risk_issue = "select distinct ProgramRI_key, RI_Nm, ImpactLevel_Nm, Last_Update_Ts, RIDescription_Txt, RiskAndIssue_Key, RIType_Cd
                                    from
                                    (select * from [RI_MGT].[fn_GetListOfRiskAndIssuesForMLMProgram] ($ri_fscl_yr,'$ri_program')
                                    ) a
                                    Where Region_Cd='$ri_region'
                                    ORDER BY RiskAndIssue_Key DESC";
								$stmt_risk_issue = sqlsrv_query( $data_conn, $sql_risk_issue );
//echo $sql_risk_issue . "<br><br>"; 

                //CLOSED PROGRAM RISK AND ISSUES
                $sql_risk_issue_cls = "select distinct RI_Nm, RIType_Cd,RIDescription_Txt, RIClosed_Dt, Last_Update_Ts, RiskAndIssue_Key
                                      from(
                                      select * from RI_MGT.fn_GetListOfAllInactiveRiskAndIssue ('Program') 
                                      where MLMProgram_Nm = '$ri_program' and RIOpen_Flg = 0
                                      ) a
                                      Where MLMRegion_Cd='$ri_region'
                                      order by RiskAndIssue_Key desc";
								$stmt_risk_issue_cls = sqlsrv_query( $data_conn, $sql_risk_issue_cls );

                //USER AUTHORIZATION
                $authUser = strtolower($windowsUser);
                $alias = "";
                  if(!empty($row_winuser_prg['User_UID'])) {
                  $alias = strtolower($row_winuser_prg['User_UID']);
                  }
                $tempID = uniqid();
								//$row_risk_issue = sqlsrv_fetch_array($stmt_risk_issue, SQLSRV_FETCH_ASSOC);
                //echo $sql_risk_issue;

                $uaccess = "false";
                if($alias == $authUser){
                  $uaccess = "true";
                } 

//DEBUG
//echo $sql_risk_issue;
//echo $sql_risk_issue;
//exit;
						
 ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
<link href='http://fonts.googleapis.com/css?family=Mulish' rel='stylesheet' type='text/css'>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"> 
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
    <link rel="stylesheet" href="steps/style.css" type='text/css'> 
    <link rel="stylesheet" href="includes/ri-styles.css" />
    <link rel="stylesheet" href="../colorbox-master/example1/colorbox.css" />
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
				$(".dno").colorbox({iframe:true, width:"75%", height:"90%", scrolling:true});
				$(".mapframe").colorbox({iframe:true, width:"95%", height:"95%", scrolling:true});
				$(".miniframe").colorbox({iframe:true, width:"30%", height:"50%", scrolling:true});
				$(".ocdframe").colorbox({iframe:true, width:"60%", height:"90%", scrolling:true, escKey: false, overlayClose: false});
				$(".miframe").colorbox({iframe:true, width:"1500", height:"650", scrolling:true});
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
</script>  
</head>
<body style="font-family:Mulish, serif;">
<?php // echo $ri_program . '</br>' . $ri_region ?>
<h3 align="center">PROGRAM RISKS & ISSUES</h3>
<!-- DEBUG USERS -->
<!-- <div align="center" class="alert alert-success" style="font-size:10px;">
Logged in as: <?php echo $authUser; ?><br>
Program Manager is: <?php echo $alias; ?>
</div> -->

<div align="center">
<div align="center">

<?php if($alias == $authUser){ ?> 
<div style="padding:5px;">
  <a href="risk-and-issues/includes/associated_prj.php?uid=<?php echo $uid?>&fiscal_year=<?php echo $ri_fscl_yr?>&ri_type=risk&ri_level=prg&action=new&tempid=<?php echo $tempID?>&proj_name<?php echo $ri_proj_nm;?>&action=new&program=<?php echo $ri_program ?>" title="Risk and Issues"><span class="btn btn-primary">CREATE PROGRAM RISK</span></a>
  <a href="risk-and-issues/includes/associated_prj.php?uid=<?php echo $uid?>&fiscal_year=<?php echo $ri_fscl_yr?>&ri_type=issue&ri_level=prg&action=new&tempid=<?php echo $tempID?>&proj_name<?php echo $ri_proj_nm;?>&action=new&program=<?php echo $ri_program ?>" title="Risk and Issues"><span class="btn btn-primary">CREATE PROGRAM ISSUE</span></a>
</div>
<?php } else { ?>
  <div style="padding:5px;">
    <button class="btn btn-primary" disabled>CREATE PROGRAM RISK</button>
    <button class="btn btn-primary" disabled>CREATE PROGRAM ISSUE</button>
  </div>
<?php } ?>
<br>
<?php //if($_GET['count'] == 0){ //TURNED OFF.  SHOULD BE != 0 ?>
  <div align="center" class="alert alert-success"><b>OPEN RISK & ISSUES</b></div>
  <table width="98%" border="0" cellpadding="5" class="table table-bordered table-striped table-hover">
    <tbody>
    <tr>
      <th width="35%"><strong>Program Risk or Issue Name</strong></th>
      <th><strong>Type</strong></th>
      <th width="35%"><strong>Description</strong></th>
      <th><strong>Impact</strong></th>
      <th><strong>Created On</strong></th>
      <th><div align="center"><strong>Action Plan</strong></div></th>
        <?php if($alias == $authUser){ ?> 
        <th><div align="center"><strong>Assoc Projects</strong></div></th>
        <?php } ?>
      <th align="center"><strong>Details</strong></th>
    </tr>
    <?php while ($row_risk_issue = sqlsrv_fetch_array($stmt_risk_issue, SQLSRV_FETCH_ASSOC)){ ?>
      <tr>
      <td><?php echo $row_risk_issue['RI_Nm']; ?></td>
      <td><?php echo $row_risk_issue['RIType_Cd']; ?></td>
      <td><?php echo $row_risk_issue['RIDescription_Txt']; ?></td>
      <td><?php echo $row_risk_issue['ImpactLevel_Nm']; ?></td>
      <td><?php echo date_format($row_risk_issue['Last_Update_Ts'], 'm-d-Y'); ?></td>
      <td align="center"><a href="risk-and-issues/action_plan.php?rikey=<?php echo $row_risk_issue['RiskAndIssue_Key']?>" class="iframe"><span class="glyphicon glyphicon-calendar"></span></a></td>
      <?php if($alias == $authUser){ ?> 
      <td align="center">
        <a title="Add Associated Project" href="risk-and-issues/includes/associated_prj_manage_prg.php?action=update&ri_level=prg&prg_nm=<?php echo $ri_program;?>&progRIKey=<?php echo $row_risk_issue['ProgramRI_key'];?>&fiscal_year=<?php echo $ri_fscl_yr;?>&name=<?php echo $row_risk_issue['RI_Nm'];?>&proj_name=<?php echo $ri_proj_nm;?>&ri_type=<?php echo $row_risk_issue['RIType_Cd'];?>&rikey=<?php echo $row_risk_issue['RiskAndIssue_Key']; ?>&status=1&uid=<?php echo $uid;?>"><span class="glyphicon glyphicon-edit"></span></a>   
      </td>
      <?php } ?>
      <td align="center"><a href="risk-and-issues/details-prg.php?au=<?php echo $uaccess ?>&rikey=<?php echo $row_risk_issue['RiskAndIssue_Key'];?>&prg_nm=<?php echo $ri_program;?>&fscl_year=<?php echo $ri_fscl_yr;?>&proj_name=<?php echo $ri_proj_nm;?>&uid=<?php echo $uid; ?>&status=1&popup=false"><span class="glyphicon glyphicon-zoom-in" style="font-size:12px;"></span></a></td>
  </tr>
    <?php } ?>
  </tbody>
</table>
<?php //} else { ?>
<!--There are no Program Risk or Issues found -->
<?php //}?>
</div>
<div align="center" class="alert alert-success"><b>CLOSED RISK & ISSUES</b></div>
<table width="98%" border="0" class="table table-bordered table-striped table-hover">
  <tbody>
    <tr cellpadding="5px">
      <th width="35%"><strong>Project Risk or Issue Name</strong></th>
      <th><strong>Type</strong></th>
      <th width="35%"><strong>Description</strong></th>
      <th><strong>Closed Date</strong></th>
      <th><strong>Last Update</strong></th>
      <th><div align="center"><strong>Action Plan</strong></div></th>
      <th align="center"><strong>Details</strong></th>
    </tr>
    <?php while ($row_risk_issue_cls = sqlsrv_fetch_array($stmt_risk_issue_cls, SQLSRV_FETCH_ASSOC)){ ?>
    <tr>
      <td><?php echo $row_risk_issue_cls['RI_Nm']; ?></td>
      <td><?php echo $row_risk_issue_cls['RIType_Cd']; ?></td>
      <td><?php echo $row_risk_issue_cls['RIDescription_Txt']; ?></td>
      <td><?php if(!empty($row_risk_issue_cls['RIClosed_Dt'])) { echo date_format($row_risk_issue_cls['RIClosed_Dt'], 'm-d-Y'); } ?></td>
      <td><?php if(!empty($row_risk_issue_cls['Last_Update_Ts'])) { echo date_format($row_risk_issue_cls['Last_Update_Ts'], 'm-d-Y'); } ?></td>
      <td align="center"><a href="risk-and-issues/action_plan.php?rikey=<?php echo $row_risk_issue_cls['RiskAndIssue_Key']?>" class="iframe"><span class="glyphicon glyphicon-calendar"></span></a></td>
      <td align="center"><a href="risk-and-issues/details-prg.php?au=<?php echo $uaccess ?>&rikey=<?php echo $row_risk_issue_cls['RiskAndIssue_Key'];?>&prg_nm=<?php echo $ri_program;?>&fscl_year=<?php echo $ri_fscl_yr;?>&proj_name=<?php echo $ri_proj_nm;?>&uid=<?php echo $uid; ?>&status=0&popup=false"><span class="glyphicon glyphicon-zoom-in" style="font-size:12px;"></span></a></td>
    </tr>
    <?php } ?>
  </tbody>
</table>
</div>
</body>
</html>