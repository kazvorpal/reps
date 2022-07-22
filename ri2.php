<?php include ("includes/functions.php");?>
<?php include ("db_conf.php");?>
<?php include ("data/emo_data.php");?>
<?php include ("sql/MS_Users.php");?>
<?php include ("sql/project_by_id.php");?>
<?php 
session_start();
$_SESSION["homebase"] = $_SERVER["REQUEST_URI"];

								//FIND PROJECT RISK AND ISSUES FUNCTION 1.26.2022
                $uid = $_GET['uid'];
                $proj_name = $_GET['prj_name'];
                $fscl_year = $_GET['fscl_year'];
									
								$sql_risk_issue = "select * from RI_MGT.fn_getlistofallriskandissue(1) where EPSProject_Nm = '$proj_name' order by RiskAndIssue_Key desc";
								$stmt_risk_issue = sqlsrv_query( $data_conn, $sql_risk_issue );
								//echo $row_risk_issue['Risk_Issue_Name']; 	
                //echo $sql_risk_issue;
                //exit();		

                //GET CLOSED RISK AND ISSUES
                $sql_closed_ri = "select * from RI_MGT.fn_getlistofallriskandissue(0) where EPSProject_Nm = '$proj_name' order by RiskAndIssue_Key desc";
								$stmt_closed_ri = sqlsrv_query( $data_conn, $sql_closed_ri );
                //$row_closed_ri = sqlsrv_fetch_array( $stmt_closed_ri, SQLSRV_FETCH_ASSOC);
                //echo $sql_closed_ri;
              
                // CHECK IF THE USER AND OWNER MATCH
                $ri_count = $_GET['count'];	//COUNTS ARE CURRENTLY WRONG. THIS WILL BE FIXED WHEN AVI ADDS THE COUNTS TO THE DPR		
                $authUser = trim($_GET['winuser']);
                $alias = trim($row_winuser['CCI_Alias']);
                $tempID = uniqid();
                $projectOwner = $row_projID['PROJ_OWNR_NM'];

                $sql_authorize = "SELECT [CCI_Alias], [PROJ_OWNR_NM], [PROJ_NM], [PROJ_ID],[RI_MGT].[RiskandIssues_Users].[Username]
                from [RI_MGT].[RiskandIssues_Users]
                left join [EPS].[ProjectStage] on [PROJ_OWNR_NM] = [CCI_Alias]
                Where [RI_MGT].[RiskandIssues_Users].[Username] = '$windowsUser'and [PROJ_ID] = '$projID'";

								$stmt_authorize = sqlsrv_query( $data_conn, $sql_authorize );
                $row_authorize = sqlsrv_fetch_array( $stmt_authorize, SQLSRV_FETCH_ASSOC);

                $authorized = "";
                if(!is_null($row_authorize)) {
                $authorized = $row_authorize['PROJ_OWNR_NM'];
                }
                
                //ACCESS 
                if($authorized != ''){ 
                  $access = "true";
                } else { 
                  $access = "false";}
                //PRINT USER SQL TO SCREEN FOR DEBUG
                //echo $sql_authorize;
                
 ?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Project Risk or Issue</title>
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
<style type="text/css">
        .popover{
            max-width:600px;
        }
        /* To change position of close button to Top Right Corner */
        #colorbox #cboxClose
        {
        top: 0;
        right: 0;
        }
        #cboxLoadedContent{
        margin-top:28px;
        margin-bottom:0;
        }
    </style>             
</head>
<body style="font-family:Mulish, serif;">

<h3 align="center">PROJECT RISKS & ISSUES</h3>
<h4 align="center"><?php echo  $proj_name ?></h4>
<!--<div align="center" class="alert alert-success" style="font-size:10px;">
<h5>FOR DEV ONLY</h5>
CC-Alias from users table <?php echo $alias; //from users table?><br>
User from Querystring: <?php echo $authUser; //from querystring?><br>
Windows Username: <?php echo $windowsUser; //try joining to project?><br>
Project Owner from Project Table: <?php echo $projectOwner; //from Project Table?><br>
ProjectID: <?php echo $projID?>

<?php if($authorized != ''){ 
    echo "<br>Match: True";
  } else { 
    echo "<br>Match: False";}?><br>
</div>
-->
<div align="center">
  
<?php if($authorized != ''){  ?> 
  
  <div style="padding:5px;">
    <a href="risk-and-issues/includes/associated_prj.php?uid=<?php echo $uid?>&ri_level=prj&ri_type=risk&action=new&fiscal_year=<?php echo $_GET['fscl_year']?>&tempid=<?php echo $tempID?>" title="Something that hasn’t happened yet but has some probability of occurring"><span class="btn btn-primary">CREATE PROJECT RISK</span></a>
    <!--<a href="risk-and-issues/project-risk.php?uid=<?php echo $uid?>&ri_type=risk&action=new&fiscal_year=<?php echo $_GET['fscl_year']?>&tempid=<?php echo $tempID?>" title="Risk and Issues"><span class="btn btn-primary">Create Project Risk</span></a> -->
    <a href="risk-and-issues/includes/associated_prj.php?uid=<?php echo $uid?>&ri_level=prj&ri_type=issue&action=new&fiscal_year=<?php echo $_GET['fscl_year']?>&tempid=<?php echo $tempID?>" title="Something that has happened "><span class="btn btn-primary">CREATE PROJECT ISSUE</span></a>
  </div>
<?php } else {?>
  <div style="padding:5px;">
    <button class="btn btn-primary" disabled>Create Project Risk</button>
    <button class="btn btn-primary" disabled>Create Project Issue</button>
  </div>
<?php } ?>
<br>
<?php //if($_GET['count'] != 0){ //TURNED OFF.  SHOULD BE != 0 ?>
<div class="alert alert-success"><b>OPEN RISK & ISSUES</b></div>
<table width="98%" border="0" class="table table-bordered table-striped table-hover">
  <tbody>
    <tr cellpadding="5px">
      <th><strong>ID</strong></th>
      <!--<th><strong>GID</strong></th>-->
      <th width="35%"><strong>Project Risk or Issue Name</strong></th>
      <th><strong>Type</strong></th>
      <th width="35%"><strong>Description</strong></th>
      <th width="7%"><strong>Impact</strong></th>
      <th><strong>Created On</strong></th>
      <th><div align="center"><strong>Action<br>Plan</strong></div></th>
        <?php if($authorized != ''){  ?> 
        <th><div align="center"><strong>Assoc<br>Projects</strong></div></th>
        <?php } ?>
      <th><div align="center"><strong>Details<br>Update</strong></div></th>
    </tr>
    <?php while ($row_risk_issue = sqlsrv_fetch_array($stmt_risk_issue, SQLSRV_FETCH_ASSOC)){ ?>
    <tr>
      <td align="center"><?php echo $row_risk_issue['RiskAndIssue_Key']; ?></td>
      <!--<td align="center"><?php//echo $row_risk_issue['RIIncrement_Num']; ?></td>-->
      <td><?php echo $row_risk_issue['RI_Nm']; ?></td>
      <td><?php echo $row_risk_issue['RIType_Cd']; ?></td>
      <td><?php echo $row_risk_issue['RIDescription_Txt']; ?></td>
      <td><?php echo $row_risk_issue['ImpactLevel_Nm']; ?></td>
      <td><?php echo date_format($row_risk_issue['Created_Ts'], 'm-d-Y'); ?></td>
      <td align="center">
        <a title="Action Plan History" href="risk-and-issues/action_plan.php?rikey=<?php echo $row_risk_issue['RiskAndIssue_Key']?>" class="iframe"><span class="glyphicon glyphicon-calendar"></span></a>
      </td>
      <?php if($authorized != ''){  ?> 
        <td align="center">
          <a title="Add Associated Project" href="risk-and-issues/includes/associated_prj_manage.php?ri_level=prj&fscl_year=<?php echo $fscl_year;?>&name=<?php echo $row_risk_issue['RI_Nm'];?>&proj_name=<?php echo $proj_name;?>&ri_type=<?php echo $row_risk_issue['RIType_Cd'];?>&rikey=<?php echo $row_risk_issue['RiskAndIssue_Key']; ?>&status=1&uid=<?php echo $uid;?>&action=update&inc=<?php echo $row_risk_issue['RIIncrement_Num']; ?>"><span class="glyphicon glyphicon-plus"></span></a> | 
          <a title="Remove Associated Project" href="risk-and-issues/includes/associated_prj_manage_remove.php?ri_level=prj&fscl_year=<?php echo $fscl_year;?>&name=<?php echo $row_risk_issue['RI_Nm'];?>&proj_name=<?php echo $proj_name;?>&ri_type=<?php echo $row_risk_issue['RIType_Cd'];?>&rikey=<?php echo $row_risk_issue['RiskAndIssue_Key']; ?>&status=1&uid=<?php echo $uid;?>&action=update&inc=<?php echo $row_risk_issue['RIIncrement_Num']; ?>"><span class="glyphicon glyphicon-minus"></span></a>      
        </td>
      <?php } ?>
      <td align="center">
        <a title="View Detials | Update | Close" href="risk-and-issues/details.php?au=<?php echo $access?>&rikey=<?php echo $row_risk_issue['RiskAndIssue_Key'];?>&fscl_year=<?php echo $fscl_year;?>&proj_name=<?php echo $proj_name;?>&status=1&popup=false"><span class="glyphicon glyphicon-zoom-in" ></span></a>
      </td>
    </tr>
    <?php } ?>
  </tbody>
</table>
<?php //} else { ?>
<!--There are no Project Risk or Issues found -->
<?php //}?>
</div>
<div>
<div align="center" class="alert alert-success"><b>CLOSED RISK & ISSUES</b></div>
<table width="98%" border="0" class="table table-bordered table-striped table-hover">
  <tbody>
    <tr cellpadding="5px">
      <th><strong>ID</strong></th>
      <th width="35%"><strong>Project Risk or Issue Name</strong></th>
      <th><strong>Type</strong></th>
      <th width="35%"><strong>Description</strong></th>
      <th width="7%"><strong>Impact</strong></th>
      <th><strong>Created On</strong></th>
      <th><div align="center"><strong>Action Plan</strong></div></th>
      <th align="center"><strong>Details</strong></th>
    </tr>
    <?php while ($row_closed_ri = sqlsrv_fetch_array($stmt_closed_ri, SQLSRV_FETCH_ASSOC)){ ?>
    <tr>
      <td><?php echo $row_closed_ri['RiskAndIssue_Key']; ?></td>  
      <td><?php echo $row_closed_ri['RI_Nm']; ?></td>
      <td><?php echo $row_closed_ri['RIType_Cd']; ?></td>
      <td><?php echo $row_closed_ri['RIDescription_Txt']; ?></td>
      <td><?php echo $row_closed_ri['ImpactLevel_Nm']; ?></td>
      <td><?php echo date_format($row_closed_ri['Created_Ts'], 'm-d-Y'); ?></td>
      <td align="center"><a href="risk-and-issues/action_plan.php?rikey=<?php echo $row_closed_ri['RiskAndIssue_Key']?>" class="iframe"><span class="glyphicon glyphicon-calendar"></span></a></td>
      <td align="center"><a href="risk-and-issues/details.php?au=<?php echo $access?>&rikey=<?php echo $row_closed_ri['RiskAndIssue_Key'];?>&fscl_year=<?php echo $fscl_year;?>&proj_name=<?php echo $proj_name;?>&status=0&popup=false"><span class="glyphicon glyphicon-zoom-in" style="font-size:12px;"></span></a></td>
    </tr>
    <?php } ?>
  </tbody>
</table>
</div>
</body>
</html>