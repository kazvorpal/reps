<?php include_once ("../../includes/functions.php");?>
<?php include_once ("../../db_conf.php");?>
<?php include_once ("../../data/emo_data.php");?>
<?php include_once ("../../sql/filter_vars.php");?>
<?php include_once ("../../sql/filtered_data.php");?>
<?php include_once ("../../sql/filters.php");?>
<?php include_once ("../../sql/update-time.php");?>
<?php 
// Any variables/settings

$project = (strpos($_SERVER["REQUEST_URI"], "project"))


?>



<script>
    //This detects whether various JavaScript libraries are already loaded, or if this include needs to do it
const bs = `
    <!-- Emergency loading of bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"> 
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"><\/script>
`;

const jq = `
<!-- Emergency loading of jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"><\/script>
<script src="../../colorbox-master/jquery.colorbox.js"><\/script>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"><\/script>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"><\/script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
`;

// window.jQuery || document.write(jq);


// typeof $().emulateTransitionEnd == 'function' || document.write(bs);

</script>


        <!-- <h5><?php echo $row_da_count['daCount']?> Risks and Issues Found </h5> -->
        <form action="" method="post" class="navbar-form navbar-center" id="formfilter">
          <div class="form-group">
            <div id="filterpanel" class="container-fluid"><div class="row" id="row"></div></div>
            <br>
 <table cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </tbody>
</table>

</div>
	</form>

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
<style>
</style>
<script type="text/javascript">
  //PHP Notice: Trying to access array offset on value of type null in C:\inetpub\wwwroot\includes\menu.php on line 79 PHP Warning: date_format() expects parameter 1 to be DateTimeInterface, null given in C:\inetpub\wwwroot\includes\menu.php on line 79
  const makeselect = (o) => {
    const td = makeelement({e: "div", c: "filtercol", t: o.t});
    const select = makeelement(o);
    if (o.i == "pStatus") {
      select.appendChild(makeelement({e: "option", v: 1, t: "Open"}));
      select.appendChild(makeelement({e: "option", v: 0, t: "Closed"}));
    } else if (o.i == "category") {
      select.appendChild(makeelement({e: "option", v: 1, t: "Project Association"}));
      select.appendChild(makeelement({e: "option", v: 0, t: "No Project Association"}));
    } else if (o.i == "program") {
      for (option in o.l) 
        if(o.l[option] != ""&& o.l[option] != null)
          select.appendChild(makeelement({e: "option", v: o.l[option], t: o.l[option]}));
    } else {
      const list = getuniques(o.l, o.f);
      for (option in list) 
        if(list[option] != ""&& list[option] != null)
          select.appendChild(makeelement({e: "option", v: list[option], t: list[option]}));
    }
    td.appendChild(select);
    document.getElementById("row").appendChild(td);
  }

  const makefilters = () => {
    document.getElementById("row").innerHTML = "";
    const programnames = (ispp(mode)) ? getuniques(ridata, "MLMProgram_Nm") : getuniques(ridata, "EPSProgram_Nm");
    console.log(programnames)
    const menuitems = {};

    selectors = {fiscalyear: {l: ridata, f: "Fiscal_Year", i: "fiscal_year", n: "fiscal_year", t: "Fiscal Year<br/>", e: "select", c: "form-control", m: "multiple"}, riskissue: {l: ridata, f: "RIType_Cd", i: "risk_issue", n: "risk_issue", t: "Risk/Issue<br/>", e: "select", c: "form-control", m: "multiple"}, impactlevel: {l: ridata, f: "ImpactLevel_Nm", i: "impact_level", n: "impact_level", t: "Impact&nbsp;Level<br/>", e: "select", c: "form-control", m: "multiple"}, "resolutiondate": function() {document.getElementById("row").appendChild(makeelement({e: "div", t: "Resolution&nbsp;Date&nbsp;Range<br/><input type='text' id='dateranger' class='daterange form-control' />", c: "filtercol"}))} }

    makeselect({l: ridata, f: "Fiscal_Year", i: "fiscal_year", n: "fiscal_year", t: "Fiscal Year<br/>", e: "select", c: "form-control", m: "multiple"});
    makeselect({l: ridata, f: "RIType_Cd", i: "risk_issue", n: "risk_issue", t: "Risk/Issue<br/>", e: "select", c: "form-control", m: "multiple"});
    makeselect({l: ridata, f: "ImpactLevel_Nm", i: "impact_level", n: "impact_level", t: "Impact&nbsp;Level<br/>", e: "select", c: "form-control", m: "multiple"});
    document.getElementById("row").appendChild(makeelement({e: "div", t: "Resolution&nbsp;Date&nbsp;Range<br/><input type='text' id='dateranger' class='daterange form-control' />", c: "filtercol"}));
    makeselect({l: ridata, f: "RIActive_Flg", i: "pStatus", n: "pStatus", t: "Status<br/>", e: "select", c: "form-control", m: "multiple"});
    if (ispp(mode)) {
      makeselect({l: ridata, f: "MLMRegion_Cd", i: "region", n: "region", t: "Region<br/>", e: "select", c: "form-control", m: "multiple"});
    }
    if (mode == "program") {
      makeselect({l: ridata, f: "category", i: "category", n: "category", t: "Category<br/>", e: "select", c: "form-control", m: "multiple"});
    }
    makeselect({l: ridata, f: "LastUpdateBy_Nm", i: "owner", n: "Owner", t: "Owner<br/>", e: "select", c: "form-control", m: "multiple"});
    makeselect({l: programnames, f: "Program_Cd", i: "program", n: "program", t: "Program<br/>", e: "select", c: "form-control", m: "multiple"});
    if (mode == "project") {
      makeselect({l: locationlist, f: "Region_Cd", i: "region", n: "region", t: "Region<br/>", e: "select", c: "form-control", m: "multiple"});
      makeselect({l: locationlist, f: "Market_Cd", i: "market", n: "market", t: "Market<br/>", e: "select", c: "form-control", m: "multiple"});
      makeselect({l: locationlist, f: "Facility_Cd", i: "facility", n: "facility", t: "Facility<br/>", e: "select", c: " selectpicker", m: "multiple"});
    }
    // $('select').selectpicker();
    document.getElementById("row").appendChild(makeelement({e: "div", t: '&nbsp;<br/><input name="Go" type="submit" id="Go" form="formfilter" value="Submit" class="btn btn-primary">', c: "filtercol"}));
    document.getElementById("row").appendChild(makeelement({e: "div", t: '&nbsp;<br/><a href="." onclick="reload()" title="Clear all filters"><span class="btn btn-default">Clear</span></a>', c: "filtercol"}));
    
    $('.daterange').daterangepicker({
      autoUpdateInput: false,
        locale: {
          cancelLabel: 'Clear'
        }
      }); 
      $('.daterange').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });
    $('.daterange').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
      });
  }
  makefilters();

  const dofilters = () => {
    $('#fiscal_year').multiselect({
          includeSelectAllOption: true,
        });
		$('#pStatus').multiselect({
          includeSelectAllOption: true,
        });
		$('#category').multiselect({
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
		$('#risk_issue').multiselect({
          includeSelectAllOption: true,
        });
		$('#impact_level').multiselect({
          includeSelectAllOption: true,
        });
        document.getElementById("Go").onclick = function() {
      // filter form button
      populate(filtration(ridata));
      colorboxschtuff();
      return false;
    }  
  }
  
</script>
<script language="javascript">
	$(document).ready(function() {
    dofilters();
  });
</script>
