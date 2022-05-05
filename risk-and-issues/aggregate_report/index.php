<?php include ("../../includes/functions.php");?>
<?php include ("../../db_conf.php");?>
<?php include ("../../data/emo_data.php");?>
<?php include ("../../sql/filter_vars.php");?>
<?php include ("../../sql/filtered_data.php");?>
<?php include ("../../sql/filters.php");?>
<?php include ("../../sql/update-time.php");?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Program R&I Aggregate View</title>
<link rel="shortcut icon" href="favicon.ico"/>
<!-- <link 
  rel="stylesheet"
  href="/css/bootstrap.css" rel="nofollow"
  integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" 
  crossorigin="anonymous"
>
<script 
  src=”https://code.jquery.com/jquery-3.2.1.slim.min.js” 
  integrity=”sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN” 
  crossorigin=”anonymous”>
</script>
<script src=https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js 
  integrity=”sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q” 
  crossorigin=”anonymous”>
</script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
  integrity=”sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl” 
  crossorigin=”anonymous”>
</script> -->
<?php include ("../../includes/load.php");

function fixutf8($target) {
  if (gettype($target) == "string")
  return (utf8_encode($target));
  else 
  return ($target);
}

$sqlstr = "select * from RI_Mgt.fn_GetListOfAllRiskAndIssue(1) where rilevel_cd = 'program'";
ini_set('mssql.charset', 'UTF-8');
$riquery = sqlsrv_query($conn, $sqlstr);
if($riquery === false) {
  if(($error = sqlsrv_errors()) != null) {
    foreach($errors as $error) {
      echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
      echo "code: ".$error[ 'code']."<br />";
      echo "message: ".$error[ 'message']."<br />";
    }
  }
} else {
  $rows = array();
  $count = 1;
  while($row = sqlsrv_fetch_array($riquery, SQLSRV_FETCH_ASSOC)) {
    $rows[] = array_map("fixutf8", $row);
  }
  
  $p4plist = array();
  foreach ($rows as $row)  {
    if($row["ProgramRI_Key"] != '') {
      $sqlstr = "select * from RI_Mgt.fn_GetListOfAssociatedProjectsForProgramRIKey(". $row["RiskAndIssue_Key"] ." ,". $row["ProgramRI_Key"] .")";
      ini_set('mssql.charset', 'UTF-8');
      $p4pquery = sqlsrv_query($conn, $sqlstr);
      if($p4pquery === false) {
        if(($error = sqlsrv_errors()) != null) {
          foreach($errors as $error) {
            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            echo "code: ".$error[ 'code']."<br />";
            echo "message: ".$error[ 'message']."<br />";
          }
        }
        $p4plist[$row["RiskAndIssue_Key"]."-".$row["ProgramRI_Key"]] = $p4prows;
      }
    }
  }
  
  $mangerlist = array();
  foreach ($rows as $row)  {
    if($row["ProgramRI_Key"] != '') {
      $sqlstr = "select * from RI_MGT.fn_GetListOfOwnersInfoForProgram(". $row["Fiscal_Year"] ." ,'". $row["Program_Nm"] ."')";
      ini_set('mssql.charset', 'UTF-8');
      $mangerquery = sqlsrv_query($conn, $sqlstr);
      if($mangerquery === false) {
        if(($error = sqlsrv_errors()) != null) {
          foreach($errors as $error) {
            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            echo "code: ".$error[ 'code']."<br />";
            echo "message: ".$error[ 'message']."<br />";
          }
        } else {
          $count = 1;
          $mangerrows = array();
            while($mangerrow = sqlsrv_fetch_array($mangerquery, SQLSRV_FETCH_ASSOC)) {
              $mangerrows[] = array_map("fixutf8", $mangerrow);
            }
          }
          $mangerlist[$row["Fiscal_Year"]."-".$row["MLMProgram_Key"]] = $mangerrows;
        }
      }
      
    $p4pout = json_encode($p4plist);
    $mangerout = json_encode($mangerlist);
    $jsonout = json_encode($rows);
    
    }

  ?>
    <link rel="stylesheet" href="../../colorbox-master/example1/colorbox.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"> 
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
    <script src="../../colorbox-master/jquery.colorbox.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.0/FileSaver.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>
    
    
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
          $(".dno").colorbox({iframe:true, width:"80%", height:"60%", scrolling:false});
          $(".mapframe").colorbox({iframe:true, width:"95%", height:"95%", scrolling:true});
          $(".miniframe").colorbox({iframe:true, width:"30%", height:"50%", scrolling:true});
          $(".ocdframe").colorbox({iframe:true, width:"75%", height:"90%", scrolling:true});
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
    $('[data-toggle="tooltip"]').tooltip()
  })

  </script>
  <link rel="stylesheet" href="../css/ri.css">
  <style type="text/css">
  </style>
</head>

<body onload="myFunction()" style="margin:0;">
<!--LOADER-->
<div id="loader"></div>
<div style="display:block;" id="myDiv" class="animate-bottom"><!--change none to block when developing-->
<!--FOR DEV ONLY - show sql-->
<div class="alert-danger">
</div>
<?php
  $planxls = 'export-dpr-2021-plan.php?fiscalYear=' . $fiscal_year . '&status=' . $pStatus . '&owner=' . $owner . '&prog=' . $program_d . '&subprogram=' . $subprogram . '&region=' . $region . '&market=' . $market . '&facility=' . $facility ;
  $planxlsEN = $planxls;
  ?>
<!--menu-->
<?php include ("../../includes/menu.php");?>
<section>
  <div class="row" align="center">
    <div style="width:98%">
      <div class="col-xs-12 text-center">
        <h1><?php if($fiscal_year !=0) {echo $fiscal_year;}?> Program R&I Aggregate View </h1>
    <?php 
      require '../includes/ri-selectors.php';
      ?>
          <span class="btn btn-primary" onclick="exporter()">Export Results</span><p/>
        <div id="main" class="accordion" >
            <div class="header">
              Program Name (Risks, Issues)
            </div>
        </div>
      
      </div>
    </div>
  </div>
</section>
<section>

</section>
<section></section>
<section>
  <div class="container">
    <div class="row"></div>
  </div>
</section>

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

</div>
</body>
<script>
  
  const ridata = <?= $jsonout ?>;  
  const mangerlist = <?= $mangerout ?>;
  const p4plist = <?= $p4pout ?>;
  
  console.log(ridata);
  const fieldlist = ["Program", "Region", "Program Manager", "ID #", "Impact Level", "Action Status", "Forecast Resol. Date", "Current Task POC", "Response Strat", "Open Duration"];
  const projectfields = ["EPSProject_Nm", "EPS_Location_Cd", "EPSProject_Owner", "Subprogram_nm"];
  const projectfieldnames = ["Project Name", "Facility", "Owner", "Subprogram"];
  // console.log(ridata)
  const finder = (target, objective) => (target.find(o => o.Program_Nm == objective));
  
  // Names of Data for program fields
  const fieldlist = ["Program", "Region", "R/I Creator", "ID #", "Impact Level", "Action Status", "Forecast Resol. Date", "Current Task POC", "Response Strat", "Open Duration", "Subprograms"];
  const datafields = ["Program_Nm", "Region_Cd", "LastUpdateBy_Nm", "RiskAndIssue_Key", "ImpactLevel_Nm", "ActionPlanStatus_Cd", "ForecastedResolution_Dt", "POC_Nm", "ResponseStrategy_Nm", "RIOpen_Hours", "subs"];
  const rifields = {"RiskAndIssue_Key": "Key", "RI_Nm": "R/I Name", "RIType_Cd": "Type", "Program_Nm": "Program", "subprogram": "Sub-Pro", "Project": "Project Name", "owner": "Owner", "Fiscal_Year": "FY", "Region_Cd": "Region Code", "mar": "Mar", "facility": "Facility", "imp": "Imp", "ActionPlanStatus_Cd": "Action Status", "ForecastedResolution_Dt": "FRD", "Current": "Current Toe?", "ResponseStrategy_Cd": "Response Strategy", "Raid": "Raid L", "RIOpen_Hours": "Open Duration"}


  const populate = (rilist) => {
    console.log(ridata);
    const main = document.getElementById("main");
    main.innerHTML = '<div class="header">Program Name (Risks, Issues)</div>';
    document.workbook = new ExcelJS.Workbook();
    document.worksheet = document.workbook.addWorksheet('ExampleWS');
    let cols = [];
    for (field in ridata[0]) {
      cols.push({
        header: field,
        key: field,
        width: (ridata[0][field]) ? ridata[0][field].length : 8
      })
    }
    document.worksheet.columns = cols;
    // document.worksheet.addRow(rowValues);
    for (loop of rilist) {
      // creates all the programs
      if(loop != null) {
        makerow(loop, countri(loop, "Risk"), countri(loop, "Issue"));
      }
    }
  }

  const makearray = (rin) => {
    let r = [];
    let a = getprogrambyname(rin);
    for (field in a) {
      r.push(a[field]);
    }
    return a;
  };

  const exporter = () => {
    document.workbook.xlsx.writeBuffer().then((buf) => {
      saveAs(new Blob([buf]), 'ri-aggregate-' + makedate(new Date()) + '.xlsx');
      // other stuffs
    });
    // saveAs(new Blob([makeoctet(document.rixl)], {type: "application/octet-stream"}), "riaggreate.xlsx");
  }

  function makeoctet(s) { 
                var buf = new ArrayBuffer(s.length); //convert s to arrayBuffer
                var view = new Uint8Array(buf);  //create uint8array as viewer
                for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF; //convert to octet
                return buf;    
  }

  const makerow = (name, risks, issues) => {

    // Runs once per Program

    const safename = makesafe(name);
    const item = document.createElement("div");
    item.id = "item" + safename;
    item.className = "toppleat accordion-item";
    const banner = makebanner(safename);
    const collapse = makeelement({e: "div", i: "collapse" + safename, c: "panel-collapse collapse"});
    const body = makeelement({e: "div", i: "body" + safename, c: "accordion-body"});
    const table = makeelement({e: "table", i: "table" + safename, c: "table"});

    const program = document.createElement("span");
    program.id = "program" + safename;
    program.className = "a-proj";
    program.innerHTML = name;
    const counts = document.createTextNode(" (R:" + risks + " I:" + issues + ")");

    const collapse = document.createElement("div");
    collapse.id = "collapse" + safename;
    collapse.className = "panel-collapse collapse";
    const body = document.createElement("div");
    body.id = "body" + safename;
    body.className = "accordion-body";
    const table = document.createElement("table");
    table.className = "table";
    table.id = "table" + safename;

    banner.appendChild(program);
    banner.appendChild(counts);
    item.appendChild(banner);
    item.appendChild(collapse).appendChild(body).appendChild(table);
    document.getElementById("main").appendChild(item);
    // document.getElementById("banner" + safename).onclick = toggler(document.getElementById("collapse" + safename));

    makeri(name, "Risk");
    makeri(name, "Issue");
  }  

  const makebanner = (safename) => {

    // Program Start

    const bannerfields = {"aria-labelledby": "banner" + safename, "data-bs-target": "#collapse" + safename, "data-target": "#collapse" + safename, "data-toggle": "collapse", "aria-controls": "collapse" + safename};
    const banner = document.createElement("div");
    banner.id = "banner" + safename;
    banner.className = "accordion-banner";
    //  (a c).log(bannerfields);
    Object.entries(bannerfields).forEach(([key, value]) => banner.setAttribute(key, value));
    banner.ariaExpanded = true;
    banner.setAttribute("aria-controls", "collapse" + safename);
    return banner;
  }  

  const makeri = (name, type) => {
    document.getElementById("table"+makesafe(name)).appendChild(makeheader(name, type));
    program = getprogrambyname(name);
    let lr = listri(name, type);
    for (ri of lr) {
      makedata(ri, type, name);  
    }
  }    

  const rirow = ["Program_Nm", "Region_Cd", null, "RiskAndIssue_Key", "ImpactLevel_Nm", "ActionPlanStatus_Cd", 
                function() {"ForecastedResolution_Dt"}];

  const toggler = (target) => {
    // Toggles visibility
    (target != null)
    target.className = (target.className.indexOf("show") == -1) ? target.className += "show" : target.className.replace("show", "");
  }
  
  const makedata = (id, type, name) => {            

    // Make all the data inside a risk or issue

    const fieldswitch = {
    //    Specific fields that need extra calculation
      mangerlist: function() {
        const manger = mangerlist[program.Fiscal_Year + "-" + program.MLMProgram_Key];
        let mangers = [];
        for (man of manger) {
          mangers.push(man.User_Nm);
        }  
        return mangers.join().replace(",", ", ");
      },
      ForecastedResolution_Dt: function() {
        const fr = (program.ForecastedResolution_Dt == null) ? "" : program.ForecastedResolution_Dt.date.substring(0,10);
        return fr;
      },
      RIOpen_Hours: function() {
        return Math.floor(program.RIOpen_Hours/24);
      },
      subs: function() {
        // console.log("p4plist");
        // console.log(program);
        // console.log(program.RiskAndIssue_Key + "-" + program.ProgramRI_Key);
        // console.log(p4plist[program.RiskAndIssue_Key + "-" + program.ProgramRI_Key]);
      }
    };

    const program = getprogrambykey(id, name);
    const safename = makesafe(program.Program_Nm);
    const saferi = makesafe(program.RI_Nm);
    if (document.getElementById('impact_level').value == "" || ($('#impact_level').val()).includes(program.ImpactLevel_Nm)) {
      const trid = "tr" + type + saferi + Math.random();
      document.getElementById("table" + safename).appendChild(makeelement({e: "tr", i: trid, c: "ptr"}));
      const arrow = (p4plist[program.RiskAndIssue_Key + "-" + program.ProgramRI_Key].length != 0) ? "▶" : "";
      const header = makeelement({
        "e": "th", 
        "i": "th" + type + saferi, 
        "t": "<div class='arrows' id='arrow" + saferi + "'> " + arrow + " </div><div style='overflow:hidden'>" + program.RI_Nm + "</div>", 
        "c":"p-4 namebox"
      });
      // console.log(program.RI_Nm);
      const tridobj = document.getElementById(trid);
      if (arrow != "") {
        tridobj.onclick = function() {
          toggler(document.getElementById("projects" + saferi), this.children[0]);
        };
      }
      tridobj.appendChild(header);
      // console.log(program);
      // console.log(program.RiskAndIssue_Key + "-" + program.ProgramRI_Key)
      for (field of datafields) {
        (function(test) {
          const texter = (typeof fieldswitch[test] != "function") ? program[test] : fieldswitch[test]();
          tridobj.appendChild(makeelement({e: "td", t: texter, c: "p-4 databox"}));
        })(field);
      }
      var rowValues = [];
      for (field in program) {
        (function(test) {
          const texter = (typeof fieldswitch[test] != "function") ? program[test] : fieldswitch[test]();
          rowValues.push(texter);
        })(field);
      }
      let newrow = document.worksheet.addRow(rowValues);
      if(arrow != "") {
        makeprojects(p4plist[program.RiskAndIssue_Key + "-" + program.ProgramRI_Key], program.Program_Nm, "table" + safename, saferi);
      }
    }
  }    

  const makeprojects = (projects, programname, tableid, saferi) => {

    // Make the rows of projects inside the program

    document.getElementById(tableid).appendChild(makeelement({e: "tr", i: "projects" + saferi, c: "panel-collapse collapse"}));
    document.getElementById("projects" + saferi).appendChild(makeelement({e: "td", t: "&nbsp;"}));
    document.getElementById("projects" + saferi).appendChild(makeelement({e: "td", i: "td" + saferi, s: 10}));
    console.log(projects)
    if (projects.length != 0) {
      const table = document.createElement("table");
      table.id = "table" + saferi;
      table.appendChild(projectheader());
      document.getElementById("td" + saferi).appendChild(table);
      let p = [];
      for(project of projects) {
        if (!p.includes(project.PROJECT_key)){
          const tr = document.createElement("tr");
          tr.id = "tr" + project.PROJECT_key;
          document.getElementById("table" + saferi).appendChild(tr);
          for (field of projectfields) {
            tr.appendChild(makeelement({e: "td", t: project[field], c: "p4 databox"}));
          }
          p.push(project.PROJECT_key);
        }
      }
    } else {

      // document.getElementById("arrow" + saferi).innerHTML = "";
      let empty = document.createTextNode("No Associated Projects");
      document.getElementById("td" + saferi).appendChild(empty);
    }
  }  

  const projectheader = () => {
    // Make the header row for a project 
    const trri = document.createElement("tr");
    for (field of projectfieldnames) {
      trri.appendChild(makeelement({e: "th", t: field, c: "p-4 headbox"}));
    }
    return trri;
  }  

  
  const makeheader = (name, type) => {
    
    // Make the header row for a risk or issue
    // let cells = [];
    // for (field in rifields) {
    //   cells.push(rifields[field]);
    // }
    // document.worksheet.addRow(cells);
    const safename = makesafe(name);
    const trri = makeelement({"e": "tr", "i": type + safename, "t": "", "c":"p-4"});
    trri.appendChild(makeelement({"e": "th", "t": type+"s", "c": "p-4 text-center headbox"}));
    let cells = ["Risk/Issue"];
    for (field of fieldlist) {
      trri.appendChild(makeelement({"e": "th", "t": field, "c": "p-4 headbox"}));
      cells.push(field);
    }
    // document.worksheet.addRow(cells);
    document.worksheet.getRow(1).font = { name: 'helvetica', family: 4, size: 12, underline: 'double', bold: true };
    return trri;
  }  

  const maketableelement = (o) => {

// o is an (o)bject with these optional properties:
// e.e is the (e)lement, like "td" or "tr"
// e.c is the (c)lasses, separated by spaces like usual
// e.s is the innerHTML (t)ext
// e.s is the col(s)pan

    const t = document.createElement(o.e);
    t.id = o.e;
    t.className = o.c;
    t.innerHTML = o.t;
    t.colSpan = o.s;
    return t;
  }

  // Utility functions

  const todate = (date) => new Date(date).toLocaleString("en-US", {day: "numeric", month: "numeric", year: "numeric"}).replace(/-/g, "/");  

  function countri(target, type) {
    
    // returns count of risks or issues for a given program, taking program name and type (risk, issue)
    
    pre = ridata.filter(o => o.RILevel_Cd == "Program" && o.RIType_Cd == type && o.Program_Nm == target);
    // uni = pre.filter((value, index, self) => self.indexOf(value) === index);
    uni = pre.map(item => item.RiskAndIssue_Key).filter((value, index, self) => self.indexOf(value) === index);
    return uni.length;
    // counter = ridata.map(item => item.RiskAndIssue_Key).filter((value, index, self) => self.indexOf(value) === index);
  }
  function listri(target, type) {
    
    // returns a list of risks or issues for a given program, taking program name and type (risk, issue)
    
    pre = ridata.filter(o => o.RILevel_Cd == "Program" && o.RIType_Cd == type && o.Program_Nm == target);
    uni = pre.map(item => item.RiskAndIssue_Key).filter((value, index, self) => self.indexOf(value) === index);
    return uni;
  }
  
  // Takes a program name and returns the row object
  const getprogrambyname = (target) =>  mlm = ridata.find(o => o.Program_Nm == target);
  
  // Takes a program key and name and returns the row object
  const getprogrambykey = (target, name) =>  mlm = ridata.find(o => o.RiskAndIssue_Key == target && o.Program_Nm == name);
  const getprojectbykey = (target, name) =>  mlm = ridata.find(o => o.RiskAndIssue_Key == target && o.PROJECT_key == name);
  
  const uniques = ridata.map(item => item.Program_Nm).filter((value, index, self) => self.indexOf(value) === index)
  
  // Sanitize a string
  const makesafe = (target) => target.replace(/\s/g,'');
  
  const empty = (o) => {
    o.children[0].innerHTML = "";
  }

  const toggler = (target, o) => {
    // Toggles visibility of projects when a given program is clicked
    if (target != null) {
      if (target.className.indexOf("show") != -1) {
        target.className = target.className.replace("show", "");
        o.children[0].innerHTML = "►";
      } else { 
        target.className += "show";
        o.children[0].innerHTML = "▼";
       }
    }
  }
  
  const filtration = () => {
    // filter the programs list using the form
    let filtered = ridata.filter(function(o) {
      return (
          o.Fiscal_Year == document.getElementById("fiscal_year").value &&
          (document.getElementById("risk_issue").value == '' || $('#risk_issue').val().includes(o.RIType_Cd)) &&
          (document.getElementById("impact_level").value == '' || $('#impact_level').val().includes(o.ImpactLevel_Nm)) &&
          (document.getElementById("program").value == '' || $('#program').val().includes(o.Program_Nm)) &&
          (document.getElementById("region").value == '' || $('#region').val().includes(o.Region_Cd)) &&
          (document.getElementById("dateranger").value == '' || betweendate($('#dateranger').val(), o.ForecastedResolution_Dt.date))
        );
      });
    if (document.getElementById("owner").value != '') {
      // console.log("owner");
      const secondpass = [];
      for (item of filtered) {
        if (item.Fiscal_Year + "-" + item.MLMProgram_Key in mangerlist && mangerlist[item.Fiscal_Year + "-" + item.MLMProgram_Key].length > 0) {
          let n = document.getElementById("owner").value
          let name = flipname(n);
          if (mangerlist[item.Fiscal_Year + "-" + item.MLMProgram_Key][0].User_Nm.indexOf(name) != -1) {
            secondpass.push(item);
          }
        }
      }
      filtered = secondpass;
    }
    console.log(filtered.length)
    console.log(filtered)
    return filtered.map(item => item.Program_Nm).filter((value, index, self) => self.indexOf(value) === index)
  }  
  
  const dateformat = (target)  => {
    // console.log(target)
    return target.getDate() + "-" + (target.getMonth()+1) + "-" + target.getFullYear();
  }

  const splitdate = (datestring) => {
    // console.log(datestring);
    let newdate = datestring.split(" - ");
    // console.log(typeof newdate[0])
    return newdate;
  }  

  const betweendate = (dates, tween) => {
    // console.log(tween.date);
    spanner = splitdate(dates);
    let first = new Date(spanner[0]);
    let middle = new Date(tween);
    let last = new Date(spanner[1]);
    // console.log(first);
    // console.log((first) + ">");
    // console.log((middle));
    // console.log(">" + (last));
    return ((middle >= first && middle <= last))
  }  

  const makedate = (dateobject) => {
    return dateobject.getFullYear() + "-" + (dateobject.getMonth()+1) + "-" + dateobject.getDate();
  }

  const flipname = (name) => {
    let fn = name.substring(name.indexOf(";")+2, name.indexOf("("));
    let ln = name.substring(0, name.indexOf(";"))
    return(fn + ln);
  }  


  const ranger = (daterange) => {
    // get start and end date from a date range set via Bootstrap date range picker
    const dates = {};
    dates.start = daterange.substring(0, daterange.indexOf(" - ")+1);
    dates.end = daterange.substring(daterange.indexOf(" - ")+4);
    return dates;
  }  

  document.getElementById("Go").onclick = function() {
    // filter form button
    populate(filtration())
    return false;
  }  

  const populate = (rilist) => {
    const main = document.getElementById("main");
    main.innerHTML = '<div class="header">Program Name (Risks, Issues)</div>';
    console.log(rilist);
    for (loop of rilist) {
      // creates all the programs
      if(loop != null) {
        createrow(loop, countri(loop, "Risk"), countri(loop, "Issue"));
      }
    }
  }
  populate(uniques);

  $("document").ready(function() {
    console.log(document.getElementById("myDefaultNavbar1"));
    document.getElementById("myDefaultNavbar1").style.display = "block !important";
  })

</script>
</html>