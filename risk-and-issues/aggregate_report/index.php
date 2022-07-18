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
    <title>Program R&I Dashboard</title>
    <link rel="shortcut icon" href="favicon.ico"/>
    <?php 
    include ("../../includes/load.php");
    function fixutf8($target) {
      if (gettype($target) == "string")
      return (utf8_encode($target));
      else 
      return ($target);
    }
    // Get ALL //
    include ("../includes/data.php");
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
    function MM_setTextOfTextfield(objId,x,newText) { //v9.0
      with (document){ if (getElementById){
        var obj = getElementById(objId);} if (obj) obj.value = newText;
      }
    }

    $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    })

      const d1 = <?= $jsonout ?>;  
      const d2 = <?= $closedout ?>;  
      const ridata = d1.concat(d2);
      const mangerlist = <?= $mangerout ?>;
      const driverlist = <?= $driverout ?>;
      const locationlist = <?= $locationout ?>;
      const p4plist = <?= $p4pout ?>;
      const regions = {"California": "CA", "Southwest": "SW", "Central": "CE", "Northeast": "NE", "Virginia": "VA", "Southeast": "SE", "Northwest": "NW", "Corporate": "COR"}
      const projectfields = ["EPSProject_Nm", "Subprogram_nm", "EPSProject_Owner", "MLMRegion_Cd", "Market_Cd", "EPS_Location_Cd"];
      const projectfieldnames = [{name: "Project Name", width: "38"}, {name: "Subprogram", width: "5"}, {name: "Owner", width: "28"}, {name: "Region", width: "9"}, {name: "Market", width: "9"}, {name: "Facility", width: "9"}];
      const finder = (target, objective) => (target.find(o => o.MLMProgram_Nm == objective));
      const hiddenfields = ["AssociatedCR_Key", "MLMRegion_Key", "MLMProgramRI_Key", "TransferredPM_Flg", "Opportunity_Txt", "RiskProbability_Key"];
      const rifields = {"RiskAndIssue_Key": {name: "ID", width: "3"}, "Fiscal_Year": {name: "FY", width: "4"}, "MLMProgram_Nm": {name: "Program", width: "9"}, "MLMRegion_Cd": {name: "Region", width: "6"}, "LastUpdateBy_Nm": {name: "Owner", width: "10"}, "ImpactLevel_Nm": {name: "Impact Level", width: "10"}, "ActionPlanStatus_Cd": {name: "Action Status", width: "27"}, "ForecastedResolution_Dt": {name: "Forecast Res Date", width: "6"}, "ResponseStrategy_Cd": {name: "Response Strategy", width: "5"}, "RIOpen_Hours": {name: "Open Duration", width: "6"}, "RIActive_Flg": {name: "Open", width: "6"}}
      const excelfields = {"Fiscal_Year": "FY",	"RIActive_Flg": "Status", "MLMProgram_Nm": "Program", "subprogram": "Subprogram", "owner": "Owner", "RiskAndIssue_Key": "ID", "RIType_Cd": "Type", "MLMRegion_Cd": "Region", "regioncount": "Reg Count", "category": "Category", "projectcount": "Proj Count", "RI_Nm": "Name", "ScopeDescriptor_Txt": "Descriptor", "RIDescription_Txt": "Description", "driver": "Driver", "ImpactArea_Nm": "Impact Area", "ImpactLevel_Nm": "Impact Level",	"RiskProbability_Nm": "Probability", "ResponseStrategy_Nm": "Response", "POC_Nm": "POC Name", "POC_Department": "POC Group", "ActionPlanStatus_Cd": "Action Plan Status", "ForecastedResolution_Dt": "Resolution Date", "RIOpen_Hours": "Days Open", "AssociatedCR_Key": "CR", "RaidLog_Flg": "Portfolio Notified", "RiskRealized_Flg": "Risk Realized", "RIClosed_Dt": "Date Closed", "Created_Ts": "Creation Date", "LastUpdate_By": "Last Update By", "Last_Update_Ts": "Last Update Date", "quartercreated": "Quarter Created", "quarterclosed": "Quarter Closed", "monthcreated": "Month Created", "monthclosed": "Month Closed", "duration": "Duration"};
    </script>
    <link rel="stylesheet" href="../css/ri.css">
    <style type="text/css">
      </style>
  <script src="../js/ri.js"></script>
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
        <h1><?php if($fiscal_year !=0) {echo $fiscal_year;}?> Program R&I Dashboard</h1>
        <div style="display:inline-block;width:28%;text-align:right;font-size:larger" id="resultcount"></div><div style="display:inline-block;width:20%;text-align:right"><span class="btn btn-primary" onclick="exporter()">Export Results</span><p/></div>
    <?php 
      require '../includes/ri-selectors.php';
      ?>
          <!-- <span class="btn btn-primary" onclick="exporter()">Export Results</span><p/> -->
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
  

  const populate = (rilist) => {
    // The main function that creates everything
    console.log(rilist);
    window.ricount = [];
    const main = document.getElementById("main");
    main.innerHTML = '<div class="header">Program Name (Risks, Issues)</div>';
    initexcel();
    for (loop of rilist) {
      // creates all the programs
      if(loop != null) {
        makerow(loop, listri(loop, "Risk").length, listri(loop, "Issue").length);
      }
    }
    resultcounter(window.ricount);
  }

  var rowcolor = 1;
  const makerow = (target, risks, issues) => {

    // Runs once per Program

    const safename = makesafe(target.MLMProgram_Nm);
    const item = makeelement({"e": "div", "i": "item" + safename, "c": "toppleat accordion-item"});
    const banner = makebanner(safename);
    const collapse = makeelement({e: "div", i: "collapse" + safename, c: "panel-collapse collapse"});
    const body = makeelement({e: "div", i: "body" + safename, c: "accordion-body"});
    const table = makeelement({e: "table", i: "table" + safename, c: "table"});

    banner.appendChild(makeelement({e: "span", i: "program" + safename, c: "a-proj", t: target.MLMProgram_Nm}));
    item.appendChild(banner);
    item.appendChild(collapse).appendChild(body).appendChild(table);
    document.getElementById("main").appendChild(item);
    document.getElementById("banner" + safename).appendChild(document.createTextNode(" ("));
    makeri(target, "Risk");
    makeri(target, "Issue");
    document.getElementById("banner" + safename).appendChild(document.createTextNode(")"));
  }  

  const makebanner = (safename) => {

    // Program Start
    console.log(rowcolor);
    const bannerfields = {"aria-labelledby": "banner" + safename, "data-bs-target": "#collapse" + safename, "data-target": "#collapse" + safename, "data-toggle": "collapse", "aria-controls": "collapse" + safename};
    const banner = document.createElement("div");
    banner.id = "banner" + safename;
    banner.className = "accordion-banner";
    //  (a c).log(bannerfields);
    rowcolor = 1;
    Object.entries(bannerfields).forEach(([key, value]) => banner.setAttribute(key, value));
    banner.ariaExpanded = true;
    return banner;
  }  
  
  const makeri = (ri, type) => {
    // Create a Risk or Issue section
    name = ri.MLMProgram_Nm;
    safename = makesafe(name);
    // program = getribykey(name);
    if (
      (document.getElementById('risk_issue').value == "" || $('#risk_issue').val().includes(type)) &&
      (typeof document.getElementById('impact_level').value != "undefined" || document.getElementById('impact_level').value == "" || $('#impact').val().includes(ri.ImpactLevel_Nm))
      ){
        let list = listri(name, type);
        document.getElementById("banner" + safename).appendChild(document.createTextNode(" " + type.charAt(0).toUpperCase() + ":" + list.length + " "));
        if (list.length != 0) {
          document.getElementById("table"+makesafe(name)).appendChild(makeheader(name, type));
          for (ri of list) {
            window.ricount.push(true);
            rowcolor++;
            makedata(ri, type, name);
          }
        }
      }
    }
    
    const makedata = (id, type, name) => {            
    // return true;

      // Make all the data inside a risk or issue
      const fieldswitch = {
        //    Specific fields that need extra calculation
        //    Add any field to rifields that you want to be a column,
        //    in the format {fieldname: "Human Name"}
        //    If it exists in rifields, it will be populated automatically here.
        //    If, instead, you need to do some calculation to produce it,
        //    add its fieldname to this "switch" object, fieldswitch,
        //    with an anonymous function to handle the changes.
        RiskAndIssue_Key: function() {
          return "<span style='font-weight:900'>" + text + "</span>";
        },
        mangerlist: function() {
          const manger = mangerlist[program.Fiscal_Year + "-" + program.MLMProgram_Key];
          let mangers = [];
          for (man of manger) {
            mangers.push(man.User_Nm);
          }  
          return mangers.join().replace(",", ", ");
        },
        EPSSubprogram_Nm: function() {
          return getlocationbykey(program.EPSProject_Key)
        },
        owner: function() {
          return program.LastUpdateBy_Nm;
        },
        ForecastedResolution_Dt: function() {
          return (program.ForecastedResolution_Dt == null) ? "" : makestringdate(program.ForecastedResolution_Dt);
        },
        RIActive_Flg: function() {
          return (program.RIActive_Flg) ? "Open" : "Closed";
        },
        Created_Ts: function() {
          return makestringdate(program.Created_Ts);
        },
        monthcreated: function() {
          return new Date(program.Created_Ts.date).toLocaleString('default', { month: 'long' });
        },
        monthclosed: function() {
          return new Date(program.Last_Update_Ts.date).toLocaleString('default', { month: 'long' });
        },
        quartercreated: function() {
          const m = new Date(program.Created_Ts.date).getMonth();
          return  (m < 3) ? "Q1" : (m < 3) ? "Q2" : (m < 9) ? "Q3" : "Q4";
        },
        quarterclosed: function() {
          const m = new Date(program.Last_Update_Ts.date).getMonth();
          return  (!program.Status) ? "" : (m < 3) ? "Q1" : (m < 3) ? "Q2" : (m < 9) ? "Q3" : "Q4";
        },
        duration: function() {
          const enddate = (program.RIActive_Flg == 1 || program.Created_Ts.date < program.RIClosed_Dt) ? program.Last_Update_Ts.date : program.RIClosed_Dt;
          const d = Math.floor((new Date(enddate) - new Date(program.Created_Ts.date))/(1000 * 60 * 60 * 24));
          return  d + " days";
        },
        Last_Update_Ts: function() {
          return  makestringdate(program.Last_Update_Ts);
        },
        AssociatedCR_Key: function() {
          return  (program.RiskRealized_Flg) ? "Y" : "";
        },
        MLMRegion_Cd: function() {
          let list = ""
          let counter = 0;
          for(r of ridata) {
              if (r.RI_Nm == program.RI_Nm && r.MLMProgram_Nm == program.MLMProgram_Nm) {
                counter++;
                list += (regions[r.MLMRegion_Cd] != undefined) ? regions[r.MLMRegion_Cd] + ", " : r.MLMRegion_Cd;
              }
            }
          return (list.slice(0, -2));
        },
        regioncount: function() {
          let counter = 0;
          for(r of ridata) {
              if (r.RI_Nm == program.RI_Nm && r.MLMProgram_Nm == program.MLMProgram_Nm) {
                counter++;
              }
            }
          return counter;
        },
        RaidLog_Flg: function() {
          return  (program.RiskRealized_Flg) ? "Y" : "";
        },
        RiskRealized_Flg: function() {
          return  (program.RiskRealized_Flg) ? "Y" : "";
        },
        RIOpen_Hours: function() {
          return Math.floor(program.RIOpen_Hours/24) + " days";
        },
        driver: function() {
          return (driverlist[program.RiskAndIssueLog_Key]) 
          ? (driverlist[program.RiskAndIssueLog_Key][0]) 
          ? driverlist[program.RiskAndIssueLog_Key][0].Driver_Nm : "" : "";
        },
        projectcount: function() {
          let projects = p4plist[program.RiskAndIssue_Key + "-" + program.MLMProgramRI_Key];
          return (projects != undefined && projects.length>0) ? projects.length : "";
        }, 
        subprogram: function() {
          let list = "";
          let prog = p4plist[program.RiskAndIssue_Key + "-" + program.MLMProgramRI_Key];
          if (prog != undefined) {
            for(r of p4plist[program.RiskAndIssue_Key + "-" + program.MLMProgramRI_Key]) {
              list += r.Subprogram_nm + ", ";
            } 
          }
          return (list != "") ? list.slice(0, -2) : "";
        },
        category: function() {
          let projects = p4plist[program.RiskAndIssue_Key + "-" + program.MLMProgramRI_Key];
          return (projects != undefined && projects.length>0) ? "Projects" : "Global";
        }
      };
      
      const program = getprogrambykey(id, name);
      const safename = makesafe(program.MLMProgram_Nm);
      const saferi = makesafe(program.RI_Nm);
      const url = `/risk-and-issues/details-prg.php?au=false&status=1&popup=true&rikey=${program["RiskAndIssue_Key"]}&fscl_year=${program["Fiscal_Year"]}&program=${program.MLMProgram_Nm}&proj_name=null`;
      const text = `<a href='${url}' class='miframe cboxElement'>${program["RiskAndIssue_Key"]}</a>`;
      if (document.getElementById('impact_level').value != "") {
        for (let option of document.getElementById('impact_level').options) {
          if(option.selected) {
            if(option.value == program.ImpactLevel_Nm)
              console.log("match")
            else
              console.log("fail")
          }
        }
      }
      if (document.getElementById('impact_level').value == "" || ($('#impact_level').val()).includes(program.ImpactLevel_Nm)) {
        const trid = "tr" + type + saferi + Math.random();
        let bgclass = (rowcolor % 2 == 0) ? " evenrow" : " oddrow";
        document.getElementById("table" + safename).appendChild(makeelement({e: "tr", i: trid, c: bgclass}));
        const arrow = (p4plist[program.RiskAndIssue_Key + "-" + program.MLMProgramRI_Key] != null ) ? (p4plist[program.RiskAndIssue_Key + "-" + program.MLMProgramRI_Key].length != 0) ? "▶" : "" : "";
        const c = (arrow == "") ? "plainbox" : "namebox";
        const header = makeelement({
          "e": "th", 
          "i": "th" + type + saferi, 
          "t": "<div style='overflow:hidden'>" + program.RI_Nm + "</div>", 
          "c":"p-4 " + c
        });
        const tridobj = document.getElementById(trid);
        if (arrow != "") {
          tridobj.onclick = function() {
            toggler(document.getElementById("projects" + saferi), this.children[0]);
          };
        }
        for (field of Object.keys(rifields)) {
          (function(test) {
            const texter = (typeof fieldswitch[test] != "function") ? program[test] : fieldswitch[test]();
            tridobj.appendChild(makeelement({e: "td", t: texter, c: "p-4 datacell" + textalign(texter)}));
          })(field);
          if (rifields[field].name == "ID") {
            tridobj.appendChild(header);
          }
        }
        var rowValues = [];
        for (field in excelfields) {
          (function(test) {
              const t = (typeof fieldswitch[test] != "function") ? program[test] : fieldswitch[test]();
              rowValues.push((typeof t == "string" && t.indexOf("a href") == 1) ? t.substring((t.indexOf(">")+1), (t.indexOf("</a>"))) :t);
          })(field);
        }
      let newrow = document.worksheet.addRow(rowValues);
      if(arrow != "") {
        makeprojects(p4plist[program.RiskAndIssue_Key + "-" + program.MLMProgramRI_Key], program.MLMProgram_Nm, "table" + safename, saferi);
      }
    }
  }    

  const makeprojects = (projects, programname, tableid, saferi) => {

    // Make the rows of projects inside the program

    document.getElementById(tableid).appendChild(makeelement({e: "tr", i: "projects" + saferi, c: "panel-collapse collapse"}));
    document.getElementById("projects" + saferi).appendChild(makeelement({e: "td", t: "&nbsp;"}));
    document.getElementById("projects" + saferi).appendChild(makeelement({e: "td", i: "td" + saferi, s: 6}));
    if (projects.length != 0) {
      const table = document.createElement("table");
      table.id = "table" + saferi;
      table.className = "projecttable";
      table.appendChild(projectheader());
      document.getElementById("td" + saferi).appendChild(table);
      let p = [];
      for(project of projects) {
        if (!p.includes(project.EPSProject_Key)){
          const tr = document.createElement("tr");
          tr.id = "tr" + project.EPSProject_Key;
          document.getElementById("table" + saferi).appendChild(tr);
          for (field of projectfields) {
            locale = getlocationbykey(project.EPSProject_Key);
            txt = (field == "MLMRegion_Cd" && locale != undefined) ? locale.Region_Cd 
              : (field == "Subprogram" && locale != undefined) ? locale.Subprogram_nm 
              : (field == "Market_Cd" && locale != undefined) ? locale.Market_Cd 
              : (field == "EPS_Location_Cd" && locale != undefined)  ? locale.Facility_Cd 
              : project[field];
            tr.appendChild(makeelement({e: "td", t: txt, c: "p4 datacell"}));
          }
          p.push(project.EPSProject_Key);
        }
      }
    } else {
      let empty = document.createTextNode("No Associated Projects");
      document.getElementById("td" + saferi).appendChild(empty);
    }
  }  

  const projectheader = () => {
    // Make the header row for a project 
    const trri = document.createElement("tr");
    for (field of Object.keys(projectfieldnames)) {
      trri.appendChild(makeelement({e: "th", t: projectfieldnames[field].name, c: "p-4 subtitles", w: projectfieldnames[field].width}));
    }
    return trri;
  }  

  
  const makeheader = (name, type) => {
    
    // Make the header row for a risk or issue

    const safename = makesafe(name);
    const trri = makeelement({"e": "tr", "i": type + safename, "t": "", "c":"p-4"});
    let cells = ["Risk/Issue"];
    rowcolor = 1;
    for (field of Object.keys(rifields)) {
      // classes = (field == "Action Status") ? 
      trri.appendChild(makeelement({"e": "th", "t": rifields[field].name, "c": "p-4 titles", "w": rifields[field].width}));
      cells.push(rifields[field].name);
      if (rifields[field].name == "ID") {
        trri.appendChild(makeelement({"e": "th", "t": type+"s", "c": "p-4 text-center titles", "w": "12"}));
      }
    }
    excelrows();
    return trri;
  }

  // Utility functions

  function listri(target, type) {
    
    // returns a list of risks or issues for a given program, taking program name and type (risk, issue)
    
    pre = ridata.filter(o => o.RILevel_Cd == "Program" && o.RIType_Cd == type && o.MLMProgram_Nm == target);
    post = pre.filter(filterfunction);
    uni = post.map(item => item.RiskAndIssue_Key).filter((value, index, self) => self.indexOf(value) === index);
    return uni;
  }

  const uniques = removenullproperty(getwholeuniques(getwholeuniques(d1, "RiskAndIssue_Key"), "MLMProgram_Nm"), "MLMProgram_Nm");

  const toggler = (target, o) => {
    // Toggles visibility of projects when a given program is clicked
    if (target != null) {
      if (target.className.indexOf("show") != -1) {
        target.className = target.className.replace("show", "");
        // o.children[0].innerHTML = "►";
      } else { 
        target.className += "show";
        // o.children[0].innerHTML = "▼";
       }
    }
  }
  
  
  const splitdate = (datestring) => {
    let newdate = datestring.split(" - ");
    return newdate;
  }  

  const betweendate = (dates, tween) => {
    spanner = splitdate(dates);
    let first = new Date(spanner[0]);
    let middle = new Date(tween);
    let last = new Date(spanner[1]);
    return ((middle >= first && middle <= last))
  }  

  document.getElementById("Go").onclick = function() {
    // filter form button
    populate(filtration())
    return false;
  }  

  populate(uniques);

</script>
</html>