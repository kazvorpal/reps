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
    <title>Project R&I Dashboard</title>
    <link rel="shortcut icon" href="favicon.ico"/>
    <?php 
        $mode = (stripos($_SERVER['REQUEST_URI'], "program")) ? "program" : "project";
        include ("../../includes/load.php");
        include ("../includes/data-unified.php");
        include ("../includes/cdns.php");
    ?>
    <script>

     mode = (window.location.href.indexOf("program")>=0) ? "program" : "project";
     alt = (mode == "project") ? "program" : "project";
     document.title = mode.charAt(0).toUpperCase() + mode.slice(1) + " R&I Dashboard";
      const projectopen = <?= $projectout ?>;  
      const projectclosed = <?= $closedout ?>;  
      const projectfull = projectopen.concat(projectclosed);  
      const programopen =<?= $programout ?>;
      const programclosed =<?= $closedprogramout ?>;
      const programfull = programopen.concat(programclosed);  
      const mangerlist = <?= $mangerout ?>;
      const driverlist = <?= $driverout ?>;
      const locationlist = <?= $locationout ?>;
      const p4plist = <?= $p4pout ?>;
      if (mode == "program") {
        var ridata = programfull;
        var d1 = programopen;
        var d2 = programclosed;
      } else {
        var ridata = projectfull;
        var d1 = projectopen;
        var d2 = projectclosed;
      }
      $(document).ready(function(){
          //Examples of how to assign the Colorbox event to elements
          showPage();
          colorboxschtuff();
      });
      const MM_setTextOfTextfield = (objId,x,newText) => { //v9.0
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
  <body>
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
  <script src="../js/ri.js"></script>
  <script>
          const finder = (target, objective) => (target.find(o => o.MLMProgram_Nm == objective));
      
      // Names of Data for program fields

      const regions = {"California": "CA", "Southwest": "SW", "Central": "CE", "Northeast": "NE", "Virginia": "VA", "Southeast": "SE", "Northwest": "NW", "Corporate": "COR"}
      const fieldlist = ["Program", "Region", "Program Manager", "ID", "Impact Level", "Action Plan", "Forecast Resol. Date", "Response Strat", "Open Duration"];
      const datafields = ["MLMProgram_Nm", "MLMRegion_Cd", "mangerlist", "RiskAndIssue_Key", "ImpactLevel_Nm", "ActionPlanStatus_Cd", "ForecastedResolution_Dt", "POC_Nm", "ResponseStrategy_Cd", "RIOpen_Hours"];
      const hiddenfields = ["AssociatedCR_Key", "MLMRegion_Key", "MLMProgramRI_Key", "TransferredPM_Flg", "Opportunity_Txt", "RiskProbability_Key"];

      var projectfields, projectfieldnames, rifields, excelfields;

  </script>
  <?php include ("../../includes/menu.php");?>
  <section>
    <div class="row" align="center">
      <div style="width:98%">
        <div class="col-xs-12 text-center">
        <h1 id="title"><?= ucfirst($mode) ?> R&I Dashboard</h1>
        <div style="display:inline-block;width:28%;text-align:right;font-size:larger" id="resultcount"></div><div style="display:inline-block;width:20%;text-align:right"><span class="btn btn-primary" onclick="exporter()">Export Results</span> <span class="btn btn-primary" onclick="chooser()" id="chooser">Switch to Program</span><p/><p/></div>

      <?php 
        require '../includes/ri-selectors.php';
        ?>
        <div style="width:100%;text-align:center;margin: top m 18px;px">
            
        </div>
                <div id="main" class="accordion" >
              <!-- <div class="header">
                Program Name (Risks, Issues)
              </div> -->
          </div>
        
        </div>
      </div>
    </div>
  </section>
  </body>
  <script>
    const showPage = () => {
      document.getElementById("loader").style.display = "none";
    //   document.getElementById("myDiv").style.display = "block";
    }
    const populate = (rilist) => {
      console.log(rilist);
      resultcounter(rilist);
      window.ricount = [];
      const main = document.getElementById("main");
      initexcel();
      if (mode == "program") {
        console.log("program")
        main.innerHTML = '<div class="header">Program Name (Risks, Issues)</div>';
      } else {
          console.log("project")
          main.innerHTML = '';
          main.appendChild(makeelement({e: "table", i: "maintable", c: "table"}));
          var mt = document.getElementById("maintable");
          mt.appendChild(makeheader("projects"));
      }
      for (loop of rilist) {
        // creates all the programs
        if(loop != null) {
            (mode == "program") ? makerow(loop, listri(loop, "Risk").length, listri(loop, "Issue").length) : mt.appendChild(createrow(loop));
        }
      }
    }

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
        document.getElementById("banner" + safename).innerHTML += " (";
        projectcount = 0;
        makeri(target, "Risk");
        makeri(target, "Issue");
        document.getElementById("banner" + safename).innerHTML += ` <span title="Project Count">P: ${projectcount} )</span>`;
    }  

    function listri(target, type) {
    
        // returns a list of risks or issues for a given program, taking program name and type (risk, issue)
        
        pre = ridata.filter(o => o.RILevel_Cd == "Program" && o.RIType_Cd == type && o.MLMProgram_Nm == target);
        post = pre.filter(filterfunction);
        uni = post.map(item => item.RiskAndIssue_Key).filter((value, index, self) => self.indexOf(value) === index);
        return uni;
    }

    const makebanner = (safename) => {

        // Program Start
        // console.log(rowcolor);
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
            document.getElementById("banner" + safename).innerHTML += `  <span title="${type.charAt(0).toUpperCase() + type.slice(1)} Count">` + type.charAt(0).toUpperCase() + ":" + list.length + "</span> ";
            if (list.length != 0) {
                document.getElementById("table"+makesafe(name)).appendChild(makeheader(name, type));
                for (ri of list) {
                    window.ricount.push(true);
                    rowcolor++;
                    makedata(ri, type, name);
                    program = getprogrambykey(ri, name);
                    projectcount += (p4plist[program.RiskAndIssue_Key + "-" + program.MLMProgramRI_Key] != null ) ? (p4plist[program.RiskAndIssue_Key + "-" + program.MLMProgramRI_Key].length != 0) : 0;
                }
            }
        }
    }

    const makedata = (id, type, name) => {            

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
            return (program.ForecastedResolution_Dt == null) ? "Unknown" : makestringdate(program.ForecastedResolution_Dt);
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
            return (program.RIClosed_Dt != null) ? Date(program.RIClosed_Dt.date).toLocaleString('default', { month: 'long' }) : "";
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
        RIClosed_Dt: function() {
            // console.log(program.RIClosed_Dt);
            return  (program.RIClosed_Dt != null) ? formatDate(new Date(program.RIClosed_Dt.date)) : "";
        },
        AssociatedCR_Key: function() {
            return  (program.AssociatedCR_Key) ? "Y" : "N";
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
            return  (program.RaidLog_Flg) ? "Y" : "N";
        },
        RiskRealized_Flg: function() {
            return  (program.RiskRealized_Flg) ? "Y" : "N";
        },
        RIOpen_Hours: function() {
            return Math.floor(program.RIOpen_Hours/24) + " days";
        },
        driver: function() {
            // console.log(driverlist[program.RiskAndIssueLog_Key]);
            return (driverlist[program.RiskAndIssueLog_Key]) 
            ? (driverlist[program.RiskAndIssueLog_Key]) 
            ? driverlist[program.RiskAndIssueLog_Key].Driver_Nm : "" : "";
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
        if (document.getElementById('impact_level').value == "" || ($('#impact_level').val()).includes(program.ImpactLevel_Nm)) {
        const trid = "tr" + type + saferi + Math.random();
        let bgclass = (rowcolor % 2 == 0) ? " evenrow" : " oddrow";
        document.getElementById("table" + safename).appendChild(makeelement({e: "tr", i: trid, c: bgclass}));
        const arrow = (p4plist[program.RiskAndIssue_Key + "-" + program.MLMProgramRI_Key] != null ) ? (p4plist[program.RiskAndIssue_Key + "-" + program.MLMProgramRI_Key].length != 0) ? "▶" : "" : "";
        console.log("arrow");
        console.log("p4plist['" + program.RiskAndIssue_Key + "-" + program.MLMProgramRI_Key+"']");
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
                let t = (typeof fieldswitch[test] != "function") ? program[test] : fieldswitch[test]();
                t = ((typeof t == "string" && t.indexOf("span") == 1) ? t.substring((t.indexOf(">")+1), (t.indexOf("</span>"))) :t);
                rowValues.push((typeof t == "string" && t.indexOf("a href") == 1) ? t.substring((t.indexOf(">")+1), (t.indexOf("</a>"))) :t);
            })(field);
        }
        let newrow = document.worksheet.addRow(rowValues);
        if(arrow != "") {
        // console.log("p4plist['" + program.RiskAndIssue_Key + "-" + program.MLMProgramRI_Key + "']");
        // console.log(p4plist[program.RiskAndIssue_Key + "-" + program.MLMProgramRI_Key]);
        makeprojects(p4pslist[program.RiskAndIssue_Key + "-" + program.MLMProgramRI_Key], program.MLMProgram_Nm, "table" + safename, saferi);
            }
        }
    }    


    const makeprojects = (projects, programname, tableid, saferi) => {

        // Make the rows of projects inside the program
        console.log(projects);
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
            // if (!p.includes(project.EPSProject_Key)){
            console.log(project)
            const tr = document.createElement("tr");
            tr.id = "tr" + project.PROJECT_key;
            for (field of projectfields) {
                console.log(field)
                // console.log("getlocationbykey(" + project.PROJECT_key + ")");
                locale = getlocationbykey(project.PROJECT_key);
                // console.log(locale);
                txt = (field == "MLMRegion_Cd" && locale != undefined) ? locale.Region_Cd 
                : (field == "Subprogram" && locale != undefined) ? locale.Subprogram_nm 
                : (field == "Market_Cd" && locale != undefined) ? locale.Market_Cd 
                : (field == "EPS_Location_Cd" && locale != undefined)  ? locale.Facility_Cd 
                : project[field];
                tr.appendChild(makeelement({e: "td", t: txt, c: "p4 datacell"}));
            }
            document.getElementById("table" + saferi).appendChild(tr);
            p.push(project.EPSProject_Key);
            // }
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
  

    const makeheader = (name, type) => {
      
      // Make the header. Duh.
      
        //   const trri = makeelement({"e": "tr", "i": "headrow", "t": "", "c":"p-4"});
        // console.log("rifields");
        // console.log(rifields);
        const safename = makesafe(name);
        const trri = makeelement({"e": "tr", "i": type + safename, "t": "", "c":"p-4"});
        if (mode == "program") {
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
        } else {
            let cells = [];
            // console.log(rifields)
            Object.entries(rifields).forEach(([key, value]) => {
                // console.log(value);
                trri.appendChild(makeelement({"e": "td", "t": value, "c": "p-4 titles"}));
                cells.push(value);
            })
        }
        excelrows();
        return trri;
    }

    const createrow = (ri) => {
      // Create a row in the table
      const name = ri.RI_Nm;
      const safename = makesafe(ri["RI_Nm"]);
      const trri = makeelement({"e": "tr", "i": "row" + safename, "t": "", "c":"p-4 datarow"});
      const fieldswitch = {
          //    Specific fields that need extra calculation
          mangerlist: function() {
              if (ri["MLMProgram_Key"]) {
                  const manger = mangerlist[ri["Fiscal_Year"] + "-" + ri["MLMProgram_Key"]];
                  let mangers = [ri["Fiscal_Year"]];
                  for (man of manger) {
                      mangers.push(man.User_Nm);
                  }  
                  return mangers.join().replace(",", ", ");
              } else
              return "";
          },
          RIActive_Flg: function() {
            return (ri.RIActive_Flg) ? "Open" : "Closed";
          },
          owner: function() {
            return ri.LastUpdateBy_Nm;
          },
          ForecastedResolution_Dt: function() {
            if (ri.ForecastedResolution_Dt != undefined)
              return formatDate(new Date(ri.ForecastedResolution_Dt.date));
            else 
              return "Unknown";
          },
          Created_Ts: function() {
            return  formatDate(new Date(ri.Created_Ts.date));
          },
          Last_Update_Ts: function() {
            return  formatDate(new Date(ri.Last_Update_Ts.date));
          },
          RIClosed_Dt: function() {
            return  (ri.RIClosed_Dt != null) ? (new Date(ri.RIClosed_Dt.date)) : "";
          },
          RiskRealized_Flg: function() {
            return  (ri.RiskRealized_Flg) ? "Y" : "N";
          },
          RaidLog_Flg: function() {
            return  (ri.RaidLog_Flg) ? "Y" : "N";
          },
          RIOpen_Hours: function() {
            return Math.floor(ri.RIOpen_Hours/24) + " days";
          },
          market: function() {
            const m = getlocationbykey(ri.EPSProject_Key);
            return (m != undefined) ? m.Market_Cd : "";
          },
          facility: function() {
            const f = getlocationbykey(ri.EPSProject_Key);
            return (f != undefined) ? f.Facility_Cd : "";
          },
          EPSRegion_Cd: function() {
            let counter = 0;
            let list = "";
            for(rr of ridata) {
              if (rr.RI_Nm == ri.RI_Nm) {
                list += rr.EPSRegion_Abb + ", ";
                counter++;
              }
            }
            return ri.EPSRegion_Cd;
            return list.slice(0, -2);
          },
          regioncount: function() {
            let counter = 0;
            for(r of ridata) {
              if (r.RI_Nm == ri.RI_Nm) {
                counter++;
              }
            }
            return counter;
          },
          monthcreated: function() {
            return new Date(ri.Created_Ts.date).toLocaleString('default', { month: 'long' });
          },
          monthclosed: function() {
            return (ri.RIClosed_Dt != null) ? new Date(ri.Last_Update_Ts.date).toLocaleString('default', { month: 'long' }) : "";
          },
          RIIncrement_Num: function() {
            return (ri.RIIncrement_Num) ? ri.RIIncrement_Num : "";
          },
          quartercreated: function() {
            const m = new Date(ri.Created_Ts.date).getMonth();
            return (m < 3) ? "Q1" : (m < 3) ? "Q2" : (m < 9) ? "Q3" : "Q4";
          },
          quarterclosed: function() {
            const m = new Date(ri.Last_Update_Ts.date).getMonth();
            return (!program.Status) ? "" : (m < 3) ? "Q1" : (m < 3) ? "Q2" : (m < 9) ? "Q3" : "Q4";
          },
          duration: function() {
            const d = Math.floor((new Date(ri.Last_Update_Ts.date) - new Date(ri.Created_Ts.date))/(1000 * 60 * 60 * 24));
            return  d + " days";
          },
          RI_Nm: function() {
              const url = "/risk-and-issues/details.php?au=false&status=1&popup=true&rikey=" + ri["RiskAndIssue_Key"]  + "&fscl_year=" + ri["Fiscal_Year"] + "&proj_name=" + ri["EPSProject_Nm"];
              return "<a href='" + url + "' onclickD='details(this);return(false)' class='miframe cboxElement'>" + ri["RI_Nm"] + "</a>";
          },
          EPSProject_Nm: function() {
              const url = "https://coxcomminc.sharepoint.com/sites/pwaeng/project%20detail%20pages/schedule.aspx?projuid=" + ri.EPSProject_Id;
              return "<a href='" + url + "'>" + ri.EPSProject_Nm + "</a>";
          },
          driver: function() {
            return (driverlist[ri.RiskAndIssueLog_Key]) 
            ? (driverlist[ri.RiskAndIssueLog_Key]) 
            ? driverlist[ri.RiskAndIssueLog_Key].Driver_Nm : "" : "";
          },
          category: function() {
            let counter = 0;
            for(r of ridata) {
              if (r.EPSProject_Nm == ri.EPSProject_Nm) {
                counter++;
              }
            }
            return (counter > 1) ? "Associated" : "Single";
          },
          projectcount: function() {
            let counter = 0;
            for(r of ridata) {
              if (r.EPSProject_Nm == ri.EPSProject_Nm) {
                counter++;
              }
            }
            return counter;
          },
          subprogram: function() {
            if (ri.MLMProgramRI_Key != null) {
              p4plist[ri.RiskAndIssue_Key + "-" + ri.MLMProgram_Key]
            }
          }
      };
      const rowValues = [];
      for (field in excelfields) {
        (function(test) {
            const t = (typeof fieldswitch[test] != "function") ? ri[test] : fieldswitch[test]();
            rowValues.push((typeof t == "string" && t.indexOf("a href") == 1) ? t.substring((t.indexOf(">")+1), (t.indexOf("</a>"))) : t);
        })(field);
      }
      let newrow = document.worksheet.addRow(rowValues);
      for(field in rifields) {
        // console.log(rifields);
        // console.log(field);
          (function(test) {
            const texter = (typeof fieldswitch[test] != "function") ? ri[test] : fieldswitch[test]();
            trri.appendChild(makeelement({"e": "td", "t": texter, "c": "p-4 datacell" + textalign(texter) }));
          })(field);
      }
      return trri;
    }  


    const chooser = () => {
        [mode, alt] = [alt, mode];
        init();
    }
    const init = () => {
        setlists();
        makefilters();
        dofilters();
        d1 = (mode == "program") ? programopen : projectopen;
        d2 = (mode == "program") ? programclosed : projectclosed;
        ridata = d1.concat(d1);
        document.getElementById("title").innerHTML = document.title = mode.charAt(0).toUpperCase() + mode.slice(1) + " R&I Dashboard";
        document.getElementById("chooser").innerHTML = "Switch to " +alt.charAt(0).toUpperCase() + alt.slice(1);
        populate(uniques());
        console.log("uniques()");
        console.log(uniques());
        colorboxschtuff();
    }

    init();
    // setTimeout(function(){populate(uniques)}, 5000);
  </script>
  </body>
</html>