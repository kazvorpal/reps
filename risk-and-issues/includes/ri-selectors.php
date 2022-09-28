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

  var subp = [];
  for (item in p4plist) {
    // console.log(item)
    p4plist[item].forEach(e => {
      if (!subp.includes(e.Subprogram_nm)) {
        subp.push(e.Subprogram_nm);
      }
    })
  }

  const makeselect = (o) => {
    const td = makeelement({e: "div", c: "filtercol", t: o.t});
    o.e = "select"
    const select = makeelement(o);
    if (o.i == "pStatus") {
      select.appendChild(makeelement({e: "option", v: 1, t: "Open", d: true}));
      select.appendChild(makeelement({e: "option", v: 0, t: "Closed"}));
    } else if (o.i == "category") {
      select.appendChild(makeelement({e: "option", v: 1, t: "Project Association"}));
      select.appendChild(makeelement({e: "option", v: 0, t: "No Project Association"}));
    } else if (o.i == "risk_issue") {
      select.appendChild(makeelement({e: "option", v: "Risk", t: "Risk"}));
      select.appendChild(makeelement({e: "option", v: "Issue", t: "Issue"}));
    } else if (o.i == "program" || o.i == "subprogram") {
      for (option in o.l) 
        if(o.l[option] != ""&& o.l[option] != null)
          select.appendChild(makeelement({e: "option", v: o.l[option], t: o.l[option]}));
    } else {
      const list = getuniques(o.l, o.f);
      // console.log(list)
      // console.log(o.i)
      for (option in list) 
        if(list[option] != ""&& list[option] != null)
          select.appendChild(makeelement({e: "option", v: list[option], t: list[option]}));
    }
    td.appendChild(select);
    document.getElementById("row").appendChild(td);
  }

  var programnames;
  const makefilters = () => {
    document.getElementById("row").innerHTML = "";
    programnames = (ispp(mode)) ? getuniques(ridata, "MLMProgram_Nm") : getuniques(ridata, "EPSProgram_Nm");
    // console.log(programnames)
    const menuitems = {};
    dc = "form-control";
    dm = "multiple";

    selectors = {
      Fiscal_Year: {l: ridata, i: "fiscal_year", n: "fiscal_year", t: "Fiscal Year<br/>", c: dc, m: dm}, 
      RIType_Cd: {l: ridata, i: "risk_issue", n: "risk_issue", t: "Risk/Issue<br/>", c: dc, m: dm}, 
      ImpactLevel_Nm: {l: ridata, i: "impact_level", n: "impact_level", t: "Impact&nbsp;Level<br/>", c: dc, m: dm}, 
      }


    makeselect({l: ridata, f: "Fiscal_Year", i: "fiscal_year", n: "fiscal_year", t: "Fiscal Year<br/>", c: dc, m: dm});
    makeselect({l: ridata, f: "RIType_Cd", i: "risk_issue", n: "risk_issue", t: "Risk/Issue<br/>", c: dc, m: dm});
    makeselect({l: ridata, f: "ImpactLevel_Nm", i: "impact_level", n: "impact_level", t: "Impact&nbsp;Level<br/>", c: dc, m: dm});
    if (mode != "portfolio") 
      document.getElementById("row").appendChild(makeelement({e: "div", t: "Resolution&nbsp;Date&nbsp;Range<br/><input type='text' id='dateranger' class='daterange form-control' />", c: "filtercol"}));
    makeselect({l: ridata, f: "RIActive_Flg", i: "pStatus", n: "pStatus", t: "Status<br/>", c: dc, m: dm});
    if (mode == "program") 
      makeselect({l: ridata, f: "MLMRegion_Cd", i: "region", n: "region", t: "Region<br/>", c: dc, m: dm});
    if (mode == "program")
      makeselect({l: ridata, f: "category", i: "category", n: "category", t: "Category<br/>", c: dc, m: dm});
    makeselect({l: ridata, f: "LastUpdateBy_Nm", i: "owner", n: "Owner", t: "Owner<br/>", c: dc, m: dm});
    makeselect({l: programnames, f: "Program_Cd", i: "program", n: "program", t: "Program<br/>", c: dc, m: dm});
    if (mode != "portfolio") 
      makeselect({l: subp, f: "Subprogram_Nm", i: "subprogram", n: "subprogram", t: "Subprogram<br/>", c: dc, m: dm});
    if (mode == "project") {
      makeselect({l: locationlist, f: "Region_Cd", i: "region", n: "region", t: "Region<br/>", c: dc, m: dm});
      makeselect({l: locationlist, f: "Market_Cd", i: "market", n: "market", t: "Market<br/>", c: dc, m: dm});
      makeselect({l: locationlist, f: "Facility_Cd", i: "facility", n: "facility", t: "Facility<br/>", c: " selectpicker", m: dm});
    }
    // $('select').selectpicker();
    document.getElementById("row").appendChild(makeelement({e: "div", t: '&nbsp;<br/><input name="Go" type="submit" id="Go" form="formfilter" value="Submit" class="btn btn-primary">', c: "filtercol"}));
    document.getElementById("row").appendChild(makeelement({e: "div", t: '&nbsp;<br/><a href="." onclick="resetform();return false" title="Clear all filters"><span class="btn btn-default">Clear</span></a>', c: "filtercol"}));
    
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
    let prop = {includeSelectAllOption: true}
    $('#fiscal_year').val(new Date().getFullYear()).multiselect({
      includeSelectAllOption: true,
    });
    $("#pStatus").val(1).multiselect("destroy").multiselect();
		$('#category').multiselect(prop);
		$('#owner').multiselect(prop);
		$('#program').multiselect(prop);
		$('#subprogram').multiselect(prop);
		$('#region').multiselect(prop);
		$('#market').multiselect(prop);
    $('#facility').multiselect(prop);
		$('#risk_issue').multiselect(prop);
		$('#impact_level').multiselect(prop);
    document.getElementById("Go").onclick = function() {
      // filter form button
      // let riseed = (ispp(mode)) ? getwholeuniques(ridata, "MLMProgram_Nm") : ridata;
      // populate(filtration(ridata));
      // colorboxschtuff();
      rifiltered = filtration(ridata);
      let riseed = (ispp(mode)) ? getwholeuniques(rifiltered, "MLMProgram_Nm") : rifiltered;
      setTimeout(function() {
        populate(riseed);
        setTimeout(() => {
          colorboxschtuff
        }, 2000);
      });
      return false;
    }  
  }

  const resetform = () => {
    document.getElementById("formfilter").reset();
    init(mode);
    // location.href = window.location.href.split('?')[0] + "?mode=" + mode;
    // document.getElementById("formfilter").reset();
    // document.getElementById("fiscal_year").value = new Date().getFullYear();
    // $("select").multiselect("rebuild");
  }
</script>
<script language="javascript">
	$(document).ready(function() {
    dofilters();
  });
</script>
