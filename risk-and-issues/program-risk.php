<?php include ("../includes/functions.php");?>
<?php include ("../db_conf.php");?>
<?php include ("../data/emo_data.php");?>
<?php include ("../sql/project_by_id.php");?>
<?php include ("../sql/RI_Internal_External.php");?>
<?php 
  $action = $_GET['action']; //new
  //$temp_id = $_GET['tempid'];
  $user_id = preg_replace("/^.+\\\\/", "", $_SERVER["AUTH_USER"]);
  $ass_project = $row_projID['PROJ_NM'];

  if(!empty($_POST['proj_select'])) { 
    $ass_project_regions = implode("','", $_POST['proj_select']); 
    $ass_project_regionsx = $ass_project_regions; 

    $regionIN = "'" . $ass_project_regions . "','" . $ass_project . "'"; 
      } else { 
    $regionIN = "'" . $ass_project . "'"; 
      }

    $region_rplc_a = str_replace("'"," ",$regionIN);
    $region_display = str_replace(",","<br>",$region_rplc_a);

  //GET REGIONS
  $sql_regions = "SELECT DISTINCT Region_key, Region
                  FROM [EPS].[ProjectStage]
                  JOIN [CR_MGT].[Region] ON [EPS].[ProjectStage].[Region] = [CR_MGT].[Region].[Region_Cd] WHERE PROJ_NM IN ($regionIN)";
  $stmt_regions = sqlsrv_query( $data_conn, $sql_regions );
  //$row_regions = sqlsrv_fetch_array( $stmt_regions, SQLSRV_FETCH_ASSOC);
  //$row_regions['Region'];
  //echo $sql_regions;
  
  //GET REGIONS FOR HIDDEN FIELD
  $sql_regions_f = "SELECT DISTINCT Region_key, Region
  FROM [EPS].[ProjectStage]
  JOIN [CR_MGT].[Region] ON [EPS].[ProjectStage].[Region] = [CR_MGT].[Region].[Region_Cd] WHERE PROJ_NM IN ($regionIN)";
  $stmt_regions_f = sqlsrv_query( $data_conn, $sql_regions_f );
  //$row_region_fs = sqlsrv_fetch_array( $stmt_regions_f, SQLSRV_FETCH_ASSOC);
  //$row_regions_f['Region'];

  //GET ALL REGIONS FOR REGIONS SELECTION
  $sql_regions = "SELECT DISTINCT Region_key, Region
                  FROM [EPS].[ProjectStage]
                  JOIN [CR_MGT].[Region] ON [EPS].[ProjectStage].[Region] = [CR_MGT].[Region].[Region_Cd] WHERE PROJ_NM IN ($regionIN)";
  $stmt_regions = sqlsrv_query( $data_conn, $sql_regions );
  //$row_regions = sqlsrv_fetch_array( $stmt_regions, SQLSRV_FETCH_ASSOC);
  //$row_regions['Region'];

  //SINGLE REGION FOR NAME CONCATINATION
  $sql_region = "SELECT DISTINCT Region_key, Region
                  FROM [EPS].[ProjectStage]
                  JOIN [CR_MGT].[Region] ON [EPS].[ProjectStage].[Region] = [CR_MGT].[Region].[Region_Cd] WHERE PROJ_NM IN ($regionIN)";
  $stmt_region = sqlsrv_query( $data_conn, $sql_region );
  $row_region = sqlsrv_fetch_array( $stmt_region, SQLSRV_FETCH_ASSOC);
  //echo $row_region['Region'];
 

  //MULTI-REGION CONCATINATION - ROW COUNT
  $sql_regions_con = "SELECT Count(DISTINCT Region_key) as numRows
                  FROM [EPS].[ProjectStage]
                  JOIN [CR_MGT].[Region] ON [EPS].[ProjectStage].[Region] = [CR_MGT].[Region].[Region_Cd] WHERE PROJ_NM IN ($regionIN)";
  $stmt_regions_con  = sqlsrv_query( $data_conn, $sql_regions_con );
  $row_regions_con = sqlsrv_fetch_array( $stmt_regions_con, SQLSRV_FETCH_ASSOC );
 //echo $sql_regions_con;

  $numRows = $row_regions_con['numRows'];
  //echo $numRows;
  //exit();

  //GET SUBPROGRAMS
  $sql_subprg_f = "SELECT DISTINCT Sub_Prg FROM [EPS].[ProjectStage] WHERE PROJ_NM IN ($regionIN)";
  $stmt_subprg_f = sqlsrv_query( $data_conn, $sql_subprg_f );
  //$row_subprg_f = sqlsrv_fetch_array( $stmt_subprg_f, SQLSRV_FETCH_ASSOC );
  //$row_subprg_f['Sub_Prg'];


  if($numRows == 7){
      $regionCD = "All";
  } else if($numRows == 1) {
      $regionCode =  $row_region['Region'];
        if($regionCode=='Corporate'){
            $regionCD = 'COR';
        } else if($regionCode=='California'){
            $regionCD = 'CA';
        } else if($regionCode=='Central'){
            $regionCD = 'CE';
        } else if($regionCode=='Northeast'){
            $regionCD = 'NE';
        } else if($regionCode=='Southeast'){
            $regionCD = 'SE';
        } else if($regionCode=='Southwest'){
            $regionCD = 'SW';
        } else if($regionCode=='Virginia'){
            $regionCD = 'VA';
        } 
  } else if($numRows >= 2 && $numRows <= 6){
      $regionCD= "Multi";
  }
  
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Carolino, Gil">
    <title>RePS Reporting - Cox Communications</title>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="../colorbox-master/jquery.colorbox.js"></script>
  <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
  <link rel="stylesheet" href="steps/style.css" type='text/css'> 
  <link rel="stylesheet" href="includes/ri-styles.css" />
  <link rel="stylesheet" href="../colorbox-master/example1/colorbox.css" />
  
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

<script language="JavaScript">
function toggle(source) {
  checkboxes = document.getElementsByName('proj_select');
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
  }
}
</script>
</head>
<body style="background: #F8F8F8; font-family:Mulish, serif;" onload="Namex.value = NameA.value +' '+NameB.value+' '+Descriptor.value+' '+NameC.value">
<main align="center">
  <!-- PROGRESS BAR -->
<div class="container">       
            <div class="row bs-wizard" style="border-bottom:0;">
                
                <div class="col-xs-3 bs-wizard-step complete">
                  <div class="text-center bs-wizard-stepnum">STEP 1</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="bs-wizard-dot"></a>
                  <div class="bs-wizard-info text-center">Select Associated Projects</div>
                </div>
                
                <div class="col-xs-3 bs-wizard-step active"><!-- complete -->
                  <div class="text-center bs-wizard-stepnum">STEP 2</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="bs-wizard-dot"></a>
                  <div class="bs-wizard-info text-center">Enter Risk or Issue Details</div>
                </div>
                
                <div class="col-xs-3 bs-wizard-step disabled"><!-- complete -->
                  <div class="text-center bs-wizard-stepnum">STEP 3</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="bs-wizard-dot"></a>
                  <div class="bs-wizard-info text-center">Confirm Your Entry</div>
                </div>
                
                <div class="col-xs-3 bs-wizard-step disabled"><!-- active -->
                  <div class="text-center bs-wizard-stepnum">STEP 4</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="bs-wizard-dot"></a>
                  <div class="bs-wizard-info text-center">Completed</div>
                </div>
            </div>
  </div>
  <!-- END PROGRESS BAR -->
<div align="center">
  <h3>PROGRAM RISK</h3>
  Enter the details of your Program Risk
	<!-- <table border="0" cellpadding="5">
	  <tbody>
		<tr>
		  <td align="right" style="padding: 5px;">
        	<a href="#" onclick="myFunction()" class="btn btn-primary">PROJECT RISK</a>
    	</td>
		  <td align="left" style="padding: 5px;">
        	<a href="#" onclick="myFunctionOff()" class="btn btn-primary">PROJECT ISSUE</a>
      </td>
		</tr>
	  </tbody>
	</table> -->
</div>
<div class="finePrint">
<?php  
  //echo "Project UID: " . $row_projID['PROJ_ID'] . "<br>"; 
  //echo "Logged in as: " . $user_id . "<br>"; 
  //echo "Project Owner: " . $row_projID['PROJ_OWNR_NM'] . "<br>"; 
  //echo "Temp ID for Associated: " . $_GET['tempid'];
  //echo "Location Code: " . $row_projID['EPSLocation_Cd']; 
?>
</div>
<div style="padding: 20px;">
  <form action="confirm.php" method="post" id="programRisk"  oninput="Namex.value = NameA.value +' '+NameB.value+' '+Descriptor.value+' '+NameC.value">

  <input name="changeLogKey" type="hidden" id="changeLogKey" value="2">
  <input name="programs" type="hidden" id="programs" value="<?php echo $row_projID['PRGM'] ?>">
  <input name="userId" type="hidden" id="userId " value="<?php echo $user_id ?>">
  <input name="formName" type="hidden" id="formName" value="PRGR">
  <input name="formType" type="hidden" id="formType" value="New">
  <input name="fiscalYer" type="hidden" id="fiscalYer" value="<?php echo $row_projID['FISCL_PLAN_YR'] ?>">
  <input name="RIType" type="hidden" id="RIType" value="Risk">
  <input name="RILevel" type="hidden" id="RILevel" value="Program">
  <input name="assocProjects" type="hidden" id="assocProjects" value="<?php echo $row_projID['PROJ_NM'] ?>">
  <!--<input name="Descriptor" type="hidden" id="Descriptor" value="">-->
  <input name="CreatedFrom" type="hidden" id="Created From" value="">
  <input name="TransfertoProgramManager" type="hidden" id="Created From" value="">
  <input name="program" type="hidden" id="program" value='<?php echo $row_projID['PRGM']; ?>'> <!-- EPS PROGRAM -->
  <input name="RIName" type="hidden" id="RIName" value="">
  <input type="hidden" name="Region" id="Region" value="<?php while($row_regions_f = sqlsrv_fetch_array( $stmt_regions_f, SQLSRV_FETCH_ASSOC)) { echo $row_regions_f['Region'] . "," ; } ?>">
  <input name="assocProjects" type="hidden" id="assocProjects" value="<?php if(!empty($_POST['proj_select'])) { $proj_select = implode(',', $_POST['proj_select']); $proj_selectx = $proj_select; echo $ass_project . "," . $proj_selectx; } else { echo $ass_project; }?>">
  <input name="assocProjectsKeys" type="hidden" id="assocProjectsKeys" value="">
  <input name="CreatedFrom" type="hidden" class="form-control" id="CreatedFrom" value="">
  <input name="riskRealized" type="hidden" class="form-control" id="riskRealized" value="0">
  <input name="DateClosed" type="hidden" id="DateClosed" value="">
  
    <table width="100%" border="0" cellpadding="10" cellspacing="10">
      <tbody>
        <tr>
          <th width="50%" align="left">
            <div id="myRisk">
              <h4 style="color: #00aaf5">PROGRAM RISK</h4>
            </div></th>
          <th align="left"><h4 style="color: #00aaf5">REGIONS</h4>
          </th>
        </tr>
        <tr>
          <td align="left" valign="top"><div class="box">
			<table width="100%" border="0" cellpadding="10px" cellspacing="10">
            <tbody>
              <tr>
                <td width="50%"><label for="Created From">Name</label>
                <br>
                <input name="Namex" type="text" readonly required="required" class="form-control" id="Namex" >
                <input name="NameA" type="hidden" id="NameA" value="<?php echo $row_projID['PRGM'];?>">
                <input name="NameA1" type="hidden" id="NameA1" value="<?php echo $row_projID['SCOP_DESC'];?>">
                <input name="NameB" type="hidden" id="NameB" value="<?php echo $regionCD; ?>"> <!-- Region -->
                <input name="NameC" type="hidden" id="NameC" value="<?php echo "POR" . substr($row_projID['FISCL_PLAN_YR'], -2) ?>"></td>
              </tr>
              <tr>
                <td></br><label for="Descriptor">Risk Descriptor * <a href="includes/definitions.php?tooltipkey=1" class="dno"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
  <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
</svg></a><br>
                  </label>
                  <input name="Descriptor" type="text" required="required" class="form-control" id="Descriptor" maxlength="30" onChange="updatebox()">  
                </td>
              </tr>
              <tr>
                <td></br><label for="Description">Description *  <a href="includes/definitions.php?tooltipkey=5" class="dno"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
  <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
</svg></a><br>
                  </label>
                  <textarea name="Description" cols="120" rows="5" required="required" class="form-control" id="Description"></textarea>  </td>
              </tr>
            </tbody>
          </table>
		</div></td>
          <td align="left" valign="top">
          <div style="padding-left: 10px">  
          <div class="box">
			    <table width="100%" border="0" cellpadding="10px" cellspacing="10">
            <tbody>
              <tr>
                <td>
                <div style="padding: 0px 0px 0px 30px">
                    <p><strong>Regions Select
                      </strong><br>
                    <?php
                        while($row_regions = sqlsrv_fetch_array( $stmt_regions, SQLSRV_FETCH_ASSOC)) {
                          echo "<label><input type='checkbox' name='Region[]' value='" . $row_regions['Region'] . "' id='Region_" . $row_regions['Region_key'] . "' checked disabled> " . $row_regions['Region'] . "</label><br>";
                        }
                    ?>
                    </p>
                    </div>
                </td>
              </tr>
            </tbody>
          </table>
		  </div>
      <br>
      <div class="box">
          <table width="100%" border="0" cellpadding="10px" cellspacing="10">
            <tbody>
              <tr>
                <td>
                <div style="padding: 0px 0px 0px 30px">
                    <p><strong>Associated Subprograms
                      </strong><br>
                    <?php
                        while($row_subprg_f = sqlsrv_fetch_array( $stmt_subprg_f, SQLSRV_FETCH_ASSOC)) {
                          echo $row_subprg_f['Sub_Prg'] . "<br>";
                        }
                    ?>
                    </p>
                    </div>
                </td>
              </tr>
            </tbody>
          </table>
      </div>
      </div>
        </td>
        </tr>
        <tr>
          <td colspan="2" align="left"><h4 style="color: #00aaf5">DRIVERS *  <a href="includes/definitions.php?tooltipkey=15" class="dno"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
  <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
</svg></a></h4>
            <div class="box subscriber">
            <table width="100%" border="0">
                <tr>
                  <td width="50%"><label>
                    <input type="radio" name="Drivers[]" value="Material Delay"  id="Drivers_0" class="required_group" required>
                    Material Delay</label></td>
                    <td><label>
                    <input type="radio" name="Drivers[]" value="Project Dependency" id="Drivers_1" class="required_group" required>
                    Project Dependency</label></td>
                </tr>
                <tr>
                    <td width="49%"><label>
                    <input type="radio" name="Drivers[]" value="Shipping/Receiving Delay" id="Drivers_10" class="required_group" required>
                    Shipping/Receiving Delay</label></td>
                  <td><label>
                    <input type="radio" name="Drivers[]" value="Budget/Funding" id="Drivers_6" class="required_group" required>
                    Budget/Funding</label></td>
                </tr>
                <tr>
                  <td><label>
                    <input type="radio" name="Drivers[]" value="Ordering Error" id="Drivers_2" class="required_group" required>
                    Ordering Error</label></td>
                    <td><label>
                    <input type="radio" name="Drivers[]" value="Design/Scope Change" id="Drivers_7" class="required_group" required>
                    Design/Scope Change</label></td>
                </tr>
                <tr>
                  <td><label>
                    <input type="radio" name="Drivers[]" value="People Resource" id="Drivers_3" class="required_group" required>
                    People Resource</label></td>
                  <td><label title="">
                    <input type="radio" name="Drivers[]" value="Admin Error" id="Drivers_8" class="required_group" required>
                    Admin Error</label></td>
                  </tr>
                <tr>
                  <td><label title="">
                    <input type="radio" name="Drivers[]" value="3PL Resource" id="Drivers_4" class="required_group" required>
                    3PL Resource</label></td>
                  <td><label title="">
                    <input type="radio" name="Drivers[]" value="External Forces" id="Drivers_9" class="required_group" required>
                    External Forces</label></td>
                </tr>
                </table>
              </div>
          </td>
          </tr>
        <tr>
          <td colspan="2" align="left"></td>
        </tr>
        <tr>
          <td align="left"><h4  style="color: #00aaf5">IMPACT</h4></td>
          <td align="left">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" align="left">
			<div class="box"> 
			<table width="100%" border="0">
            <tbody>

              <tr>
                <td width="33%"></td>
                <td width="33%"></td>
                <td width="33%"></td>
                <td width="1%"></td>
              </tr>

              <tr>
                <td  valign="top">
                  <table width="200" border="0">
                  <tr>
                  <strong>Impacted Area * </strong>
                  <a href="includes/definitions.php?tooltipkey=17" class="dno"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
  <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
</svg></a>
                  </tr>
                  <?php while($row_impArea= sqlsrv_fetch_array( $stmt_impArea , SQLSRV_FETCH_ASSOC)) { ?>
                    <tr>
                    <td><label>
                      <input type="radio" name="ImpactArea" value="<?php echo $row_impArea['ImpactArea_Key'] ?>" id="ImpactArea_<?php echo $row_impArea['ImpactArea_Key'] ?>" required>
                      <?php echo $row_impArea['ImpactArea_Nm'] ?></label></td>
                    </tr>
                  <?php } ?>
                  </table></td>
                <td valign="top">
                  <table width="200" border="0">
                    <tr>
                      <strong>Impact Level * </strong>
                      <a href="includes/definitions.php?tooltipkey=19" class="dno"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
  <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
</svg></a>
                    </tr>
                    <?php while($row_imLevel = sqlsrv_fetch_array( $stmt_imLevel , SQLSRV_FETCH_ASSOC)) { ?>
                    <tr>
                      <td><label>
                        <input name="ImpactLevel" type="radio" id="ImpactLevel_<?php echo $row_imLevel['ImpactLevel_Key'] ?>" value="<?php echo $row_imLevel['ImpactLevel_Key'] ?>" required>
                        <?php echo $row_imLevel['ImpactLevel_Nm'] ?></label></td>
                      </tr>
                    <?php } ?>                    
                    </table>
                  </td>
                <td valign="top">
				<div id="myDIV2">
                    <table width="200" border="0">
                        <tr>
                          <td>
                            <strong>Risk Probability Score *</strong>
                          </td>
                        <?php while($row_probability= sqlsrv_fetch_array( $stmt_probability , SQLSRV_FETCH_ASSOC)) { ?>
                        <tr>
                        <td><label>
                          <input name="RiskProbability" type="radio" id="RiskProbability_<?php echo $row_probability['RiskProbability_Key'] ?>" value="<?php echo $row_probability['RiskProbability_Key'] ?>" required>
                          <?php echo $row_probability['RiskProbability_Nm'] ?></label></td>
                        </tr>
                        <?php } ?>
                    </table>
                </div>
				</td>
                <td>
                
				        </td>
                </tr>
              </tbody>
          </table>
		</div> 
        </td>
        </tr>
        <tr>
          <td colspan="2" align="left"></td>
        </tr>
        <tr>
          <td align="left"><h4 style="color: #00aaf5">CURRENT TASK POC <a href="includes/definitions.php?tooltipkey=2" class="dno"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
  <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
</svg></a></h4></td>
          <td align="left">
			  
		  </td>
        </tr>
        <tr>
          <td colspan="2" align="left">
          <div class="box">
              <label for="Individual">Individual POC *<br>
                </label>
              
              <input type="text" list="Individual" name="Individual" class="form-control" id="indy" required/>
              
                <datalist id="Individual">
                  <?php while($row_internal  = sqlsrv_fetch_array( $stmt_internal , SQLSRV_FETCH_ASSOC)) { ?>
                    <option value="<?php echo $row_internal['POC_Nm'] . " : " . $row_internal['POC_Department'] ;?>"><span style="font-size:8px;"> <?php echo $row_internal['POC_Department'];?></span>
                  <?php } ?>
                </datalist>

              <label for="Individual3">Team/Group POC *<br>
                </label>
              <input type="text" name="InternalExternal" class="form-control" id="InternalExternal" onclick="myFunction()" required/>
          </div>
          </td>
          </tr>
        <tr>
          <td colspan="2" align="left"></hr></td>
        </tr>
        <tr>
          <td colspan="2" align="left"></td>
        </tr>
        <tr>
          <td colspan="2" align="left">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" align="left"><div class="box">
              <label for="date">Forecasted Resolution Date *  <a href="includes/definitions.php?tooltipkey=6" class="dno"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
  <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
</svg></a></label>
			  <div id="dateUnknown">
              <input name="date" 
                  type="date"
                  class="form-control" 
                  id="date" 
                  value="2022-01-01"
                  onChange="forCastedX()"  
                  oninvalid="this.setCustomValidity('You must select a date or check Unknown ')"
                  oninput="this.setCustomValidity('')">
          </div>
          <div id="forcastedDate">
              <input type="checkbox" 
                  name="Unknown" 
                  id="Unknown" 
                  onChange="unKnownX()">
              <label for="Unknown">Unknown</label> - Overrides Resolution Date
          </div>
          </div></td>
        </tr>
        <tr>
          <td colspan="2" align="left"><h4 style="color: #00aaf5">RESPONSE STRATEGY * <a href="includes/definitions.php?tooltipkey=13" class="dno"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
  <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
</svg></a></h4></td>
        </tr>
        <tr>
          <td colspan="2" align="left"><div class="box">
            <table width="246" border="0" cellpadding="5" cellspacing="5">
              <tr>
                <td>&nbsp;</td>
                <td><label>
                  <input type="radio" name="ResponseStrategy" value="1" id="Response_Strategy_0" required>
                  Avoid</label></td>
                </tr>
              <tr>
                <td>&nbsp;</td>
                <td><label>
                  <input type="radio" name="ResponseStrategy" value="2" id="Response_Strategy_1" required>
                  Mitigate</label></td>
                </tr>
              <tr>
                <td width="16">&nbsp;</td>
                <td width="195"><label>
                  <input type="radio" name="ResponseStrategy" value="3" id="Response_Strategy_2" required>
                  Transfer</label></td>
                </tr>
              <tr>
                <td>&nbsp;</td>
                <td><label>
                  <input type="radio" name="ResponseStrategy" value="4" id="Response_Strategy_3" required>
                  Accept</label></td>
                </tr>
              <!--<tr>
              <td>&nbsp;</td>
              <td><label>
                <input type="radio" name="ResponseStrategy" value="5" id="Response_Strategy_3" required>
                Under Review</label></td>
              </tr>  -->
              </table>
          </div>			</td>
        </tr>
        <tr>
          <td colspan="2" align="left"><h4 style="color: #00aaf5">ACTION PLAN * <a href="includes/definitions.php?tooltipkey=9" class="dno"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
            <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
            </svg></a></h4>
          
          <div class="box">  
            <table width="100%" border="0" cellpadding="5" cellspacing="5">
              <tbody>
                
                  <tr>
                    <td width="100%">
                       <textarea name="ActionPlan" cols="120" required="required" class="form-control" id="ActionPlan"></textarea></td>
                  </tr>
              </tbody>
            </table>
          <div>

          </td>
        </tr>
        <tr>
          <td colspan="2" align="left"></td>
        </tr>
        <tr>
        <td colspan="2" align="left"><h4 style="color: #00aaf5">PROJECT ASSOCIATION*  <a href="includes/definitions.php?tooltipkey=11" class="dno"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
            <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
            </svg></a></h4></td>
        </tr>
        <tr>
          <td colspan="2" align="left">
            <div class="box">
              <?php echo $region_display ?>
            </div>
          </td>
        </tr>
        <!--<tr>
          <td colspan="2" align="left"><h4 style="color: #00aaf5">RISK REALIZED</h4></td>
        </tr>
        <tr>
          <td colspan="2" align="left">
            <div class="box">
              <table width="50%" border="0">
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  </tr>
                <tr>
                  <td colspan="2"><strong>Risk Realized?</strong></td>
                  </tr>
                <tr>
                  <td><label>
                    <input type="radio" name="riskRealized" value="Yes" id="RiskRelized_0">
                    Yes</label></td>
                  <td><label>
                    <input type="radio" name="riskRealized" value="No" id="RiskRelized_1" checked>
                    No</label></td>
                  </tr>
                </table>
              </div>
            
          </td>
        </tr>
        <tr>
          <td colspan="2" align="left"></td>
        </tr>
        <tr>
          <td colspan="2" align="left">
            <br>
            <div class="box">
              <label for="Created From">Associated CR ID</label>
              <input name="CreatedFrom" type="text" class="form-control" id="Created From">
            </div>
          </td>
      </tr>-->
      <tr>
        <td colspan="3" align="left"><h4 style="color: #00aaf5">RAID LOG <a href="includes/definitions.php?tooltipkey=10" class="dno"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
            <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
            </svg></a></h4></td>
			  </tr>
        <tr>
          <td colspan="3" align="left">
            <div class="box">
              <table width="50%" border="0">
                  <td colspan="2"><strong>Notify Portfolio Team *</strong></td>
                  </tr>
                <tr>
                  <td><label>
                    <input type="radio" name="raidLog" value="Yes" id="raid_0">
                    Yes</label></td>
                  <td><label>
                    <input type="radio" name="raidLog" value="No" id="raid_1" checked>
                    No</label></td>
                  </tr>
                </table>
              </div>
			    </td>
        </tr>
<!--
        <tr>
          <td colspan="3" align="left"><h4 style="color: #00aaf5">DATE CLOSED</h4></td>
        </tr>
        <tr>
          <td colspan="2" align="left">
			  <div class="box">
			<label for="DateClosed">Date Closed:</label>
            <input type="date" name="DateClosed" id="DateClosed" class="form-control">
			</div>
		  </td>
        </tr>
                  -->
        <tr>
          <td colspan="2" align="right" valign="middle">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" align="right" valign="middle">
            <button class="btn btn-primary" onclick="myConfirmation()"><span class="glyphicon glyphicon-step-backward"></span> Back </button>
            <button type="submit" class="btn btn-primary" onmouseover="myFunction(); Namex.value = NameA.value +' '+ Descriptor.value  + ' ' +NameC.value">Review <span class="glyphicon glyphicon-step-forward"></span></button>
          </td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
</main>

<script>
function myFunction() {
  var x = document.getElementById("myDIV");
  if (x.style.display === "none") {
    x.style.display = "none";
  } else {
    x.style.display = "none";
  }
  
  var y = document.getElementById("myDIV2");
  if (y.style.display === "none") {
    y.style.display = "block";
  } else {
    y.style.display = "block";
  }

  var z = document.getElementById("myIssue");
  if (z.style.display === "none") {
    z.style.display = "none";
  } else {
    z.style.display = "none";
  }

  var w = document.getElementById("myRisk");
  if (w.style.display === "none") {
    w.style.display = "block";
  } else {
    w.style.display = "block";
  }

}

function myFunctionOff() {
  var x = document.getElementById("myDIV");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "block";
  }
  
  var y = document.getElementById("myDIV2");
  if (y.style.display === "none") {
    y.style.display = "none";
  } else {
    y.style.display = "none";
  }
  
  var z = document.getElementById("myIssue");
  if (z.style.display === "none") {
    z.style.display = "block";
  } else {
    z.style.display = "block";
  }

  var w = document.getElementById("myRisk");
  if (w.style.display === "none") {
    w.style.display = "none";
  } else {
    w.style.display = "none";
  }

}

</script>
<script>
function forCasted() {
  var x = document.getElementById("forcastedDate");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}
</script>
<script>
function unKnown() {
  var x = document.getElementById("dateUnknown");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}

jQuery(function ($) {
    var $inputs = $('input[name=date],input[name=unknown]');
    $inputs.on('input', function () {
        // Set the required property of the other input to false if this input is not empty.
        $inputs.not(this).prop('required', !$(this).val().length);
    });
});
</script>

<script>
function validateGrp() {
  let things = document.querySelectorAll('.required_group')
  let checked = 0;
  for (let thing of things) {
    thing.checked && checked++
  }
  if (checked) {
    things[things.length - 1].setCustomValidity("");
    document.getElementById('checkGroup').submit();
  } else {
    things[things.length - 1].setCustomValidity("You must select at least one Driver");
    things[things.length - 1].reportValidity();
  }
}

document.querySelector('[name=submit]').addEventListener('click', () => {
  validateGrp()
});
</script>
<script>
  var date = new Date();
  var day = date.getDate();
  var month = date.getMonth() + 1;
  var year = date.getFullYear();

  if (month < 10) month = "0" + month;
  if (day < 10) day = "0" + day;

  var today = year + "-" + month + "-" + day;

  document.getElementById('date').value = today;
  </script>
  <script>
  document.getElementById("indy").addEventListener("change", function(){
  const v = this.value.split(" : ");
  this.value = v[0];
  document.getElementById("InternalExternal").value = v[1];
  });
</script>
<script>
$('.subscriber :checkbox').change(function () {
    var $cs = $(this).closest('.subscriber').find(':checkbox:checked');
    if ($cs.length > 3) {
        this.checked = false;
    }
});
</script> 
<script src="includes/ri-functions.js"></script>
</body>
</html>