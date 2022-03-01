<?php include ("../includes/functions.php");?>
<?php include ("../db_conf.php");?>
<?php include ("../data/emo_data.php");?>
<?php // include ("../sql/collapse.php");?>
<?php include ("../sql/project_by_id.php");?>
<?php include ("../sql/ri_filter_vars.php");?>
<?php include ("../sql/ri_filters.php");?>
<?php include ("../sql/ri_filtered_data.php");?>
<?php include ("../sql/RI_Internal_External.php");?>
<html>
<head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script>
//select all checkboxes
$("#select_all").change(function(){  //"select all" change 
	var status = this.checked; // "select all" checked status
	$('.checkbox').each(function(){ //iterate all listed checkbox items
		this.checked = status; //change ".checkbox" checked status
	});
});

$('.checkbox').change(function(){ //".checkbox" change 
	//uncheck "select all", if one of the listed checkbox item is unchecked
	if(this.checked == false){ //if this item is unchecked
		$("#select_all")[0].checked = false; //change "select all" checked status to false
	}
	
	//check "select all" if all checkbox items are checked
	if ($('.checkbox:checked').length == $('.checkbox').length ){ 
		$("#select_all")[0].checked = true; //change "select all" checked status to true
	}
});
</script>

<script>
$(function(){
	$("table").on('click', ".selectProperty, .selectedProperty", function() { 
		if($(this).hasClass('selectProperty'))
			var newTd= 'selectedProperty', newTbl='selectedPropsTable';
		else
			var newTd= 'selectProperty', newTbl='searchTable'; 
		$(this).prop('checked', false).attr('class', newTd);
		var tr = $(this).closest('tr');
		$('table.'+newTbl).find("tbody").append(tr.clone()); 
		tr.remove(); 
	});
});
</script>
</head>
<body>
<style>table{border:2px solid black;}</style>

<table class="searchTable table table-bordered table-hover table-striped"> 
	<tbody>
    <tr> 
            <th><input type="checkbox" name="select_all" id="select_all"> Project</th>
            <th>Region</th>
            <th>Market</td>
            <th>Facility</th>
    </tr>
	<tr> 
            <td><input type="checkbox" class="selectProperty checkbox">
            <input type="hidden" name="assProjID[]" id="assProjID" value="RPCP VA HRD CHS R PHY CCAP Deployment POR21"> RPCP VA HRD CHS R PHY CCAP Deployment POR21</td>
            <td>Virginia</td>
            <td>Hampton Roads</td>
            <td>Chesapeake</td>
            
    </tr>
	<tr> 
            <td><input type="checkbox" class="selectProperty checkbox">
            <input type="hidden" name="assProjID[]" id="assProjID" value="RPCP VA HRD PRA R PHY CCAP Deployment POR21"> RPCP VA HRD PRA R PHY CCAP Deployment POR21</td>
            <td>Virginia</td>
            <td>Hampton Roads</td>
            <td>Princess Anne</td>
            
    </tr>
	</tbody>
</table>
<form action="" method="post" id="assProjects" name="assProjects">
<table class="selectedPropsTable table table-bordered table-hover table-striped"> 
	<tbody>
	<tr> 
            <th><input type="checkbox" class=""> Project</th>
            <th>Region</th>
            <th>Market</th>
            <th>Facility</th>
    </tr>	
    <tr> 
            <td><input type="hidden" name="assProjID[]" id="assProjID" value="RPCP VA HRD NRF R PHY CCAP Deployment POR21"> RPCP VA HRD NRF R PHY CCAP Deployment POR21</td>
            <td>Virginia</td>
            <td>Hampton Roads</td>
            <td>Princess Anne</td>
            
        </tr>
		
	</tbody>
</table>
<div align="center">
    <input type="submit" name="submit2" id="submit2" value="Submit" class="btn btn-primary">
</div>
</form>
<?php
$assx = "";
if(!empty($_POST['assProjID'])) {
$ass = implode(',<br>', $_POST['assProjID']);
$assx = $ass;
echo $ass;
}
?>
</body>

</html>