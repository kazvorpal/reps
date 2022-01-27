<?php include ("../includes/functions.php");?>
<?php include ("../db_conf.php");?>
<?php include ("../data/emo_data.php");?>
<?php include ("../sql/update-time.php");?>
<?php include ("../sql/cr.php");?>
<?php include ("trueup_function.php");?>
<?php include ("../includes/big_bro_functions.php");?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>CR Overview</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="shortcut icon" href="../favicon.ico"/>
<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css'>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.10.0/bootstrap-table.min.css'>
<link rel='stylesheet' href='https://rawgit.com/vitalets/x-editable/master/dist/bootstrap3-editable/css/bootstrap-editable.css'>

<link rel="stylesheet" href="https://unpkg.com/jquery-resizable-columns@0.2.3/dist/jquery.resizableColumns.css">
<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.18.0/dist/extensions/sticky-header/bootstrap-table-sticky-header.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/akottr/dragtable@master/dragtable.css">
<script>
  window.console = window.console || function(t) {};
</script>
<script>
  if (document.location.search.match(/type=embed/gi)) {
    window.parent.postMessage("resize", "*");
  }
</script>
<style type="text/css">
    .popover{
        max-width:800px;
    }
</style>
</head>
<body translate="no" onload="myFunction()" style="margin:0;">
<div id="loader"></div>
<div style="display:block;" id="myDiv" class="animate-bottom"> <!--change block to none when not debugging-->

<?php include ("../includes/menu.php");?>
<div align="center">
  <h3>CR Overview</h3>
</div>
<div class="row" align="center">
	<form action="index.php" method="post" id="crform">
		<div class="col-lg-12">
        	<table border="0">
              <tbody>
                <tr class="text-danger" style="font-size:9px">
                  <td>&nbsp;</td>
                  <td><?php //echo $_GET['year']?></td>
                  <td><?php //echo $_GET['fundingKey']?></td>
                  <td><?php //echo $_GET['status']?></td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr class="text-danger" style="font-size:9px">
                  <td>&nbsp;</td>
                  <td>* Required</td>
                  <td>* Required</td>
                  <td>Optional</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td>
                    <select name="year" required class="form-control" id="year">
                      <option value="">Select Year </option>
                      <?php while( $row_fyDrp = sqlsrv_fetch_array( $stmt_fyDrp, SQLSRV_FETCH_ASSOC)){?>
                        <option value="<?php echo $row_fyDrp['Fiscal_Year'] ?>" <?php if(isset($_GET['year'])){  if($_GET['year'] == $row_fyDrp['Fiscal_Year']) { echo 'selected="selected"' ;}  }?>><?php echo $row_fyDrp['Fiscal_Year'] ?></option>
                      <?php } ?>
                  </select></td>
                  <td>
                      <select name="fundingKey" required class="form-control" id="fundingKey">
                        <option value="">Select Funding Category</option>
                        <?php while( $row_crCat = sqlsrv_fetch_array( $stmt_crCat, SQLSRV_FETCH_ASSOC)){?>
                        <option value="<?php echo $row_crCat['PortfolioFundingCat_Key'] ?>" <?php if(isset($_GET['fundingKey'])){  if($_GET['fundingKey'] == $row_crCat['PortfolioFundingCat_Key']) { echo 'selected="selected"' ;}  }?>><?php echo $row_crCat['PortfolioFundingCat_Nm'] ?></option>
                        <?php } ?>
                      </select>
                  </td>
                  <td>
                    <select name="status" id="status" class="form-control">
                      <option value="0" selected="selected" <?php if(!isset($_GET['status'])) { echo 'selected="selected"'; } ?>>Select Status</option>
                      <?php while($row_crDrp = sqlsrv_fetch_array( $stmt_crDrp, SQLSRV_FETCH_ASSOC)) { ?>
                      <option value="<?php echo $row_crDrp['CR_Status_Key'] ?>" <?php if(isset($_GET['status'])) { if($_GET['status'] == $row_crDrp['CR_Status_Key'] ) { echo 'selected="selected"'; } } ?>><?php echo $row_crDrp['CR_Status_Abb'] ?></option>
                      <?php } ?>
                  </select></td>
                  <td><input name="Submit" type="submit" class="btn btn-primary" id="Submit" formaction="index.php" formmethod="GET" value="Submit" onClick="myFunction()"></td>
                  <td></td>
                </tr>
                <tr>
                  <td colspan="6" align="center">&nbsp;</td>
                </tr>
              </tbody>
			</table>
        </div>
    </form>
    </div>
    </div>
    <div class="col-md-4" align="right"></div>
  </div>
</div>

<!-- Start DataTable-->
  <table 
    class="table-responsive table-striped" 
    id="table" 
    data-toggle="table"
      <?php if(isset($_GET['year'])){ 
        echo 'data-search="true"';
        }
      ?>
    data-filter-control="true" 
    data-show-export="true" 
    data-click-to-select="true" 
    data-toolbar=".toolbar" 
    data-pagination="true" 
    data-height="600"
    data-show-columns="true"
    data-show-columns-toggle-all="true"
    data-resizable="true"
    data-reorderable-columns="true"
    data-page-size="50" 
    data-buttons-class="primary"
    data-show-refresh="false"
    data-escape="false" 
    >
    <?php if(isset($_GET['year'])){ 
    echo '<div align="right" style="padding-right:125px; font-size:11px" class="text-danger" >*Keyword search your filtered results</div>';
    }
    ?>
    
    <thead style="font-size:11px; background-color:#00aaf5; color:#FFFFFF">
        <tr>  
            <th data-field="crid" data-filter-control="select" data-sortable="true" data-title-tooltip="" data-formatter="nameFormatter" data-escape="false">CR ID</th>
          	<th data-field="crstatus" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="">CR STATUS</span></th>
            <th data-field="statusdate" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="">STATUS DATE</span></th>
            <th data-field="crtype" data-filter-control="select" data-sortable="true" data-title-tooltip="">CR TYPE</th>
            <th data-field="crimpact" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="">CR IMPACT</span></th>
            <th data-field="crname" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="">CR NAME</span></th>
            <th data-field="program" data-filter-control="select" data-sortable="true" data-title-tooltip="">PROGRAM</th>
            <th data-field="crcreation" data-filter-control="select" data-sortable="true" data-title-tooltip="">CR CREATION</th>
            <th data-field="crpm" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="">CR PM</span></th>
            <th data-field="dollarchange" data-filter-control="select" data-sortable="true" data-title-tooltip="">$ CHANGE</th>
            <th data-field="planchange" data-filter-control="select" data-sortable="true" data-title-tooltip="">PLAN CHANGE</th>
            <th data-field="crdesc" data-filter-control="select" data-sortable="true" data-title-tooltip="">CR DESC</th>
            <th data-field="trupcapex" data-filter-control="select" data-sortable="true"><span data-toggle="tooltip" data-placement="top" title="">CR CAPEX</span></th>
            <th data-field="trupopex" data-filter-control="select" data-sortable="true" data-title-tooltip="">CR OPEX</th>
            <th data-field="capexos" data-filter-control="select" data-sortable="true" data-title-tooltip="">CAPEX OS</th>
            <th data-field="opexos" data-filter-control="select" data-sortable="true" data-title-tooltip="">OPEX OS</th>
           </tr>
    </thead>
    <tbody style="font-size:11px">
    <?php while( $row_crs = sqlsrv_fetch_array( $stmt_crs, SQLSRV_FETCH_ASSOC)){?>
    		<tr style="font-size:11px">
          <td align="center" style="padding:3px">
            <a href="details.php?sn=<?php echo urlencode($row_crs['CR_Id']) ?>&fk=<?php echo urlencode($row_crs['CR_Key']) ?>&year=<?php echo urlencode($_GET['year']) ?>" class="iframe" target="_blank">
              <?php echo htmlspecialchars($row_crs['CR_Id'])?>
            </a>
          </td>
          <td align="left" style="padding:3px"><?php echo htmlspecialchars($row_crs['CR_Status_Abb'])?></td>
          <td align="center" style="padding:3px"><?php echo htmlspecialchars($row_crs['EffectiveDate'])?></td>
          <td align="left" style="padding:3px"><?php echo htmlspecialchars($row_crs['CR_Type_Des'])?></td>
          <td style="padding:3px"><?php echo htmlspecialchars($row_crs['CR_Impact_Cd'])?></td>
          <td width="500" style="padding:3px"><?php echo htmlspecialchars($row_crs['CR_ShortDesc'])?></td>
          <td style="padding:3px"><?php echo htmlspecialchars($row_crs['Program_Nm'])?></td>
          <td align="center" style="padding:3px"><?php echo htmlspecialchars($row_crs['Creation_Date'])?></td>
          <td align="left" style="padding:3px"><?php echo htmlspecialchars($row_crs['CR_PM'])?></td>
          <td align="center" style="padding:3px"><?php if($row_crs['Capex_Amt'] <> 0 || $row_crs['Opex_Amt'] <> 0 ) { echo 'Yes'; } else { echo 'No';}?></td>
          <td align="center" style="padding:3px"><?php if($row_crs['PlanChg'] <> 0) { echo 'Yes'; } else { echo 'No' ;}?></td>
          <td align="left" style="padding:3px"><?php echo htmlspecialchars($row_crs['CR_Description'])?></td>
          <td align="right" style="padding:3px">
          <?php
          //echo '$' . number_format($row_capex['capex'],2)
          //echo truCPX($row_crs['CR_Key'], $year, $fundingKey );
              ?>
          <?php echo htmlspecialchars(number_format($row_crs['Capex_Amt'],2))?>
          </td>
          <td align="right" style="padding:3px">
          <?php 
          //echo '$' . number_format($row_opex['capex'],2)
          //echo truOPX($row_crs['CR_Key'], $year, $fundingKey );
          ?>
          <?php echo htmlspecialchars(number_format($row_crs['Opex_Amt'],2))?>
          </td>
          <td align="right" style="padding:3px"><?php echo htmlspecialchars(number_format($row_crs['Capex_OS_Amt'],2))?></td>
          <td align="right" style="padding:3px"><?php echo htmlspecialchars(number_format($row_crs['Opex_OS_Amt'],2))?></td>
        	</tr>
    <?php } ?>
    </tbody>
</table>
<!-- End DataTable-->
</div>
<script src="https://static.codepen.io/assets/common/stopExecutionOnTimeout-30d18ea41045577cdb11c797602d08e0b9c2fa407c8b81058b1c422053ad8041.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.10.0/bootstrap-table.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.9.1/extensions/editable/bootstrap-table-editable.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.9.1/extensions/export/bootstrap-table-export.js'></script>
<script src='https://rawgit.com/hhurz/tableExport.jquery.plugin/master/tableExport.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.9.1/extensions/filter-control/bootstrap-table-filter-control.js'></script>

<script src="https://unpkg.com/jquery-resizable-columns@0.2.3/dist/jquery.resizableColumns.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.0/dist/extensions/resizable/bootstrap-table-resizable.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.0/dist/extensions/sticky-header/bootstrap-table-sticky-header.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/akottr/dragtable@master/jquery.dragtable.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.0/dist/extensions/reorder-columns/bootstrap-table-reorder-columns.min.js"></script>

<script id="rendered-js">
//Tool Bar
var $table = $('#table');
$(function () {
  $('#toolbar').find('select').change(function () {
    $table.bootstrapTable('refreshOptions', {
      exportDataType: $(this).val(),
       });

  }).trigger('change') ;
});

var trBoldBlue = $("table");
$(trBoldBlue).on("click", "tr", function () {
  $(this).toggleClass("bold-blue");
});


//Tool Tip
  $(function(){
    $('[data-toggle="popover"]').popover({ 
      html : true, 
      content: function() {
        return $('#popover_content').html();
      }
    });
  });

//Tool Tip Info Icon
$(function(){
    $('[data-toggle="info"]').popover({ 
      html : true, 
      content: function() {
        return $('#popover_info_content').html();
      }
    });
  });


//Show Bootstrap Table
  $(function() {
    $('#table').bootstrapTable()
  })

// Name Formatter
  function nameFormatter(value, row) {
    var icon = row.id % 2 === 0 ? 'fa-star' : 'fa-star-and-crescent'
    return '<i class="fa ' + icon + '"></i> ' + value
  }


//Load Spinner
	var myVar;
	
	function myFunction() {
	  myVar = setTimeout(showPage, 1000);
	}
	
	function showPage() {
	  document.getElementById("loader").style.display = "none";
	  document.getElementById("myDiv").style.display = "block";
	}
</script>
</body>
</html>