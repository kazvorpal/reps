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
    <title>Risk & Issues Dashboard</title>
    <link rel="shortcut icon" href="favicon.ico"/>
    <script src="../js/universal-functions.js"></script>
    <script src="../js/dashboard-functions.js"></script>
    <?php 
        $mode = (stripos($_SERVER['REQUEST_URI'], "program")) ? "program" : "project";
        include ("../../includes/load.php");
        include ("../includes/cdns.php");
        include ("../includes/data-unified.php");
    ?>
    <script>


    </script>

<link rel="stylesheet" title="ri" href="../css/ri.css">
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
      
      // const modes = ["project", "program", "portfolio"];

</script>
  <?php include ("../../includes/menu.php");?>
  <section>
    <div class="row" align="center">
      <div style="width:98%">
        <div class="col-xs-12 text-center">
        <h1 id="title">R&I Dashboard</h1>
        <div style="display:inline-block;width:20%;text-align:right"><span class="btn btn-primary" onclick="exporter()">Export Results</span></div> <div style="display:inline-block;padding:4px;text-align:center;font-size:larger;" id="resultcount"></div> <div id="modebuttons" style="display:inline-block;width:20%;text-align:left"> Switch To: <p><p/><p/></div>

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
    rifiltered = filtration(ridata);

    const populate = (rilist) => {
      console.log("rilist");
      console.log(rilist);
      resultcounter(rilist);
      result = 0;
      window.ricount = [];
      const main = document.getElementById("main");
      initexcel();
      if (ispp(mode)) {
        if (mode == "portfolio") {
            p = ", Portfolio";
            n = "RAID Log";
        } else {
            p = ", Projects";
            n = capitalize(mode);
        }
        main.innerHTML = `<div class="header">${capitalize(mode)} Name (Risks, Issues${p})</div>`;
      } else {
          main.innerHTML = '';
          main.appendChild(makeelement({e: "table", i: "maintable", c: "table"}));
          var mt = document.getElementById("maintable");
          mt.appendChild(makeheader("projects"));
      }
      for (loop of rilist) {
        // This loop creates the programs/portfolios (makerow) or projects (createrow), based on what mode. 
        if(loop != null) {
            (ispp(mode)) ? makerow(loop, listri(loop, "Risk").length, listri(loop, "Issue").length) : mt.appendChild(createrow(loop));
        }
        resultcounter((ispp(mode)) ? result : rilist);
      }
    }

    const makerow = (target, risks, issues) => {

        // Runs once per Program
        if (typeof target == null) {
          return false;
        }
        if (target.MLMProgram_Nm == null || target.MLMProgram_Nm == "null") return false;
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
        projectcount = portfoliocount = 0;
        makeri(target, "Risk");
        makeri(target, "Issue");
        let p = (mode == "program") ? ` <span title="Project Count">P: ${projectcount}</span>` : ` <span title="Project Count">Portfolio: ${portfoliocount}</span>`
        document.getElementById("banner" + safename).innerHTML += p + ' )';
    }  

    const makebanner = (safename) => {

        // Program Start
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
        programname = ri.MLMProgram_Nm;
        if (programname == "null" || programname == null) return false;
        safename = makesafe(programname);
            let list = listri(programname, type);
            document.getElementById("banner" + safename).innerHTML += `  <span title="${capitalize(type)} Count">` + type.charAt(0).toUpperCase() + ":" + list.length + "</span> ";
            if (list.length != 0) {
                document.getElementById("table"+makesafe(programname)).appendChild(makeheader(programname, type));
                for (ri of list) {
                  result++;
                  window.ricount.push(true);
                  rowcolor++;
                  makedata(ri, type, programname);
                  program = getprogrambykey(ri, programname);
                  portfoliocount += (program.RI_Nm.toLowerCase().indexOf("portfolio")>-1) ? 1 : 0;
                  projectcount += (p4plist[program.RiskAndIssue_Key + "-" + program.MLMProgramRI_Key] != null ) ? (p4plist[program.RiskAndIssue_Key + "-" + program.MLMProgramRI_Key].length != 0) : 0;
                }
            }
        // }
    }

    const makedata = (id, type, programname) => {            
        
        // Make all the data inside a risk or issue, Program and Portfolio

        const fieldswitch = {
          //    Specific fields that need extra calculation
          //    Add any field to rifields that you want to be a column,
          //    in the format {fieldname: "Human Name"}
          //    If it exists in rifields, it will be populated automatically here.
          //    If, instead, you need to do some calculation to produce it,
          //    add its fieldname to this "switch" object, fieldswitch,
          //    with an anonymous function to handle the changes.
          RiskAndIssue_Key: () => {
              return `<span style='font-weight:900'>${text}</span>`;
          },
          mangerlist: () => {
              const manger = mangerlist[program.Fiscal_Year + "-" + program.MLMProgram_Key];
              let mangers = [];
              for (man of manger) {
              mangers.push(man.User_Nm);
              }  
              return mangers.join().replace(",", ", ");
          },
          global: () => {
              return  (program.Global_Flg) ? "Y" : "N";
            },
          category: () => {
            // let projects = p4plist[program.RiskAndIssue_Key + "-" + program.MLMProgramRI_Key];
            // return (projects != undefined && projects.length>0) ? "Program" : "Global";
            return  (program.Global_Flg) ? "Global" : "Program";
          },
          EPSSubprogram_Nm: () => {
              return getlocationbykey(program.EPSProject_Key)
          },
          ForecastedResolution_Dt: () => {
              return (program.ForecastedResolution_Dt == null) ? "Unknown" : makestringdate(program.ForecastedResolution_Dt);
          },
          RIActive_Flg: () => {
              return (program.RIActive_Flg) ? "Open" : "Closed";
          },
          Created_Ts: () => {
              return makestringdate(program.Created_Ts);
          },
          monthcreated: () => {
              return new Date(program.Created_Ts.date).toLocaleString('default', { month: 'long' });
          },
          monthclosed: () => {
              return (program.RIClosed_Dt != null) ? new Date(program.RIClosed_Dt.date).toLocaleString('default', { month: 'long' }) : "";
          },
          quartercreated: () => {
              const m = new Date(program.Created_Ts.date).getMonth();
              return  (m < 3) ? "Q1" : (m < 6) ? "Q2" : (m < 9) ? "Q3" : "Q4";
          },
          quarterclosed: () => {
              const m = (program.RIClosed_Dt != null) ? new Date(program.RIClosed_Dt.date).getMonth():"";
              return  (program.RIClosed_Dt == null) ? "" : (m < 3) ? "Q1" : (m < 6) ? "Q2" : (m < 9) ? "Q3" : "Q4";
          },
          duration: () => {
              const enddate = (program.RIActive_Flg == 1 || program.Created_Ts.date < program.RIClosed_Dt) ? program.Last_Update_Ts.date : program.RIClosed_Dt;
              const d = Math.floor((new Date(enddate) - new Date(program.Created_Ts.date))/(1000 * 60 * 60 * 24));
              return  d + " days";
          },
          Last_Update_Ts: () => {
              return  makestringdate(program.Last_Update_Ts);
          },
          RIClosed_Dt: () => {
              return  (program.RIClosed_Dt != null) ? formatDate(new Date(program.RIClosed_Dt.date)) : "";
          },
          AssociatedCR_Key: () => {
              return  (program.AssociatedCR_Key) ? "Y" : "N";
          },
          MLMRegion_Cd: () => {
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
          regioncount: () => {
              let counter = 0;
              for(r of ridata) {
                  if (typeof r != "undefined" && r.RI_Nm == program.RI_Nm && r.MLMProgram_Nm == program.MLMProgram_Nm) {
                  counter++;
                  }
              }
              return counter;
          },
          RaidLog_Flg: () => {
              return  (program.RaidLog_Flg) ? "Y" : "N";
          },
          RiskRealized_Flg: () => {
              return  (program.RiskRealized_Flg) ? "Y" : "N";
          },
          RIOpen_Hours: () => {
              return Math.floor(program.RIOpen_Hours/24) + " days";
          },
          driver: () => {
              return (driverlist[program.RiskAndIssueLog_Key]) 
              ? (driverlist[program.RiskAndIssueLog_Key]) 
              ? driverlist[program.RiskAndIssueLog_Key].Driver_Nm : "" : "";
          },
          projectcount: () => {
              let projects = p4plist[program.RiskAndIssue_Key + "-" + program.MLMProgramRI_Key];
              return (projects != undefined && projects.length>0) ? projects.length : "";
          }, 
          subprogram: () => {
              let list = "";
              let prog = (program.Global_Flg) ? sublist[program.RiskAndIssue_Key] : p4plist[program.RiskAndIssue_Key + "-" + program.MLMProgramRI_Key];
              if (prog != undefined) {
                for(r of prog) {
                  // console.log((program.Global_Flg)?true:false)
                    list += (program.Global_Flg) ? r.SubProgram_Nm + ", " : r.Subprogram_nm + ", ";
                } 
              }
              let ret = (list != "") ? list.slice(0, -2) : ""
              return ret;
          }, 
          MLMProgram_Nm: () => {
            if (program.Global_Flg == 1) {
              let programs = "";
              portfolioprograms.forEach(o => {
                let comma = (programs != "") ? ", " : "";
                if (o.RiskAndIssue_Key == program.RiskAndIssue_Key) {
                  // console.log(o.RiskAndIssue_Key)
                  programs = programs + comma + o.Program_Nm;
                }
              })
              if (programs == "" ) {
                programs = program.MLMProgram_Nm;
              }
              return (programs);
            } else {
              return program.MLMProgram_Nm;
            }
          },
          programcount: () => {
              let programs = "";
              let pc = 0;
              portfolioprograms.forEach((o) => {
                let comma = (programs != "") ? ", " : ""
                if (o.RiskAndIssue_Key == program.RiskAndIssue_Key 
                  && programs.indexOf(o.MLMProgram_Nm) == -1) {
                  programs = programs + comma + o.Program_Nm;
                  pc++;
                } 
              })
              if (pc == 0) {
                pc = 1;
              }
              return (pc);
          },
          age: () => {
            let r = (aplist[program.RiskAndIssue_Key]) ? new Date(aplist[program.RiskAndIssue_Key].LastUpdate.date) : "";
            let d = (r == "") ? "" : (Math.floor((new Date() - r)/(1000 * 60 * 60 * 24))+1) ;
            let s = (d == 1) ? " day" : (d == "") ? "" : " days";
            return  `${d}${s}`;
          },
          actionplandate: () => {
            let r = (aplist[program.RiskAndIssue_Key]) ? formatDate(new Date(aplist[program.RiskAndIssue_Key].LastUpdate.date)) : "";
            return(r);
          }//, 
          // MLMProgram_Nm: () => {

          // }
        };
        const program = getprogrambykey(id, programname);
        const safename = makesafe(program.MLMProgram_Nm);
        const saferi = makesafe(program.RI_Nm);
        let url = text = "";
        // if (document.getElementById('impact_level').value == "" || ($('#impact_level').val()).includes(program.ImpactLevel_Nm)) {
        const trid = "tr" + type + saferi + Math.random();
        let bgclass = (rowcolor % 2 == 0) ? " evenrow" : " oddrow";
        document.getElementById("table" + safename).appendChild(makeelement({e: "tr", i: trid, c: bgclass}));
        const arrow = (p4plist[program.RiskAndIssue_Key + "-" + program.MLMProgramRI_Key] != null ) 
          ? (p4plist[program.RiskAndIssue_Key + "-" + program.MLMProgramRI_Key].length != 0) 
          ? "▶" : "" : "";
          // console.log("arrow")
          // console.log(arrow)
        const file = (program.Global_Flg) ? "global/details.php" : "details-prg.php";
        url = `/risk-and-issues/${file}?au=false&status=${program["RIActive_Flg"]}&popup=true&rikey=${program["RiskAndIssue_Key"]}&fscl_year=${program["Fiscal_Year"]}&program=${program.MLMProgram_Nm}&proj_name=null&unframe=false`;
        text = `<a href='${url}' class='miframe cboxElement'>${program["RiskAndIssue_Key"]}</a>`;
        const c = (arrow == "" || mode == "portfolio") ? "plainbox" : "namebox";
        const w = (mode == "portfolio") ? "" : "";
        const header = makeelement({
          e: "th", 
          i: "th" + type + saferi, 
          t: "<div style='overflow:hidden'>" + program.RI_Nm + "</div>", 
          c:"p-4 " + c,
        });
        const tridobj = document.getElementById(trid);
        if (arrow != "") {
            if (mode == "program") {  // Disable Portfolio associated programs, remove to re-enable for a future feature
              tridobj.onclick = (e) => {
                toggler(document.getElementById("projects" + saferi), e.target.children[0]);
              };
            }
        }
        for (field of Object.keys(rifields)) {
            (function(test) {
              const texter = (typeof fieldswitch[test] != "function") ? program[test] : fieldswitch[test]();
              // if (test == "ForecastedResolution_Dt") {console.log((Date.parse(texter)+86400000) +"<"+ Date.parse(new Date()))}
              let bgcolor = (test == "ForecastedResolution_Dt" && (Date.parse(texter)+86400000) < Date.parse(new Date())) ? " hilite" : "";
              tridobj.appendChild(makeelement({e: "td", t: texter, c: "p-4 datacell" + textalign(texter) + bgcolor, w: w}));
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
        processcells();
        if(arrow != "") {
          makeprojects(p4plist[program.RiskAndIssue_Key + "-" + program.MLMProgramRI_Key], program.MLMProgram_Nm, "table" + safename, saferi);
        }
        // }
    }    


    const makeprojects = (projects, programname, tableid, saferi) => {

        // Make the rows of projects inside the program
        // console.log(projects)
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
              const tr = document.createElement("tr");
              tr.id = "tr" + project.PROJECT_key;
              for (field of projectfields) {
                  locale = getlocationbykey(project.PROJECT_key);
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

    const makeheader = (name, type) => {
      
      // Make the header for Projects
      
        const safename = makesafe(name);
        const trri = makeelement({"e": "tr", "i": type + safename, "t": "", "c":"p-4<?= $headerposition ?>"});
        if (ispp(mode)) {
            let cells = ["Risk/Issue"];
            rowcolor = 1;
            for (field of Object.keys(rifields)) {
                trri.appendChild(makeelement({"e": "th", "t": rifields[field].name, "c": "p-4 titles", "w": rifields[field].width}));
                cells.push(rifields[field].name);
                if (rifields[field].name == "ID") {
                    trri.appendChild(makeelement({"e": "th", "t": type+"s", "c": "p-4 text-center titles", "w": "12"}));
                }
            }
        } else {
            let cells = [];
            Object.entries(rifields).forEach(([key, value]) => {
                trri.appendChild(makeelement({"e": "td", "t": value, "c": "p-4 titles"}));
                cells.push(value);
            })
        }
        excelrows();
        return trri;
    }

    const createrow = (ri) => {
      // Create a row in the Project table
      const name = ri.RI_Nm;
      const safename = makesafe(ri["RI_Nm"]);
      const trri = makeelement({"e": "tr", "i": "row" + safename, "t": "", "c":"p-4 datarow"});
      const fieldswitch = {
          //    Specific fields that need extra calculation
          mangerlist: () => {
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
          groupcount: () => {
            let gc = 0;
            ridata.forEach(o => {
              gc += (ri.RIIncrement_Num == o.RIIncrement_Num && ri.RIActive_Flg == o.RIActive_Flg) ? 1 : 0;
            })
            return gc;
          },
          grouptype: () => {
            let gc = 0;
            ridata.forEach(o => {
              gc += (ri.RIIncrement_Num == o.RIIncrement_Num) ? 1 : 0;
            })
            return (gc > 1) ? "Multi" : "Single";
          },
          RIActive_Flg: () => {
            return (ri.RIActive_Flg) ? "Open" : "Closed";
          },
          ForecastedResolution_Dt: () => {
            if (ri.ForecastedResolution_Dt != undefined)
              return formatDate(new Date(ri.ForecastedResolution_Dt.date));
            else 
              return "Unknown";
          },
          Created_Ts: () => {
            return  formatDate(new Date(ri.Created_Ts.date));
          },
          Last_Update_Ts: () => {
            return  formatDate(new Date(ri.Last_Update_Ts.date));
          },
          RIClosed_Dt: () => {
            return  (ri.RIClosed_Dt != null) ? (new Date(ri.RIClosed_Dt.date)) : "";
          },
          RiskRealized_Flg: () => {
            return  (ri.RiskRealized_Flg) ? "Y" : "N";
          },
          RaidLog_Flg: () => {
            return  (ri.RaidLog_Flg) ? "Y" : "N";
          },
          RIOpen_Hours: () => {
            let d = Math.floor(ri.RIOpen_Hours/24);
            let s = (d == 1) ? " day" : (d === "") ? "" : " days";
            return  `${d}${s}`;
            // let s = (d == 1) ? "s" : "";
            // return  `${d} day${s}`;
          },
          market: () => {
            const m = getlocationbykey(ri.EPSProject_Key);
            return (m != undefined) ? m.Market_Cd : "";
          },
          facility: () => {
            const f = getlocationbykey(ri.EPSProject_Key);
            return (f != undefined) ? f.Facility_Cd : "";
          },
          EPSRegion_Cd: () => {
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
          regioncount: () => {
            let counter = 0;
            for(r of ridata) {
              if (r.RI_Nm == ri.RI_Nm) {
                counter++;
              }
            }
            return counter;
          },
          monthcreated: () => {
            return new Date(ri.Created_Ts.date).toLocaleString('default', { month: 'long' });
          },
          monthclosed: () => {
            return (ri.RIClosed_Dt != null) ? new Date(ri.Last_Update_Ts.date).toLocaleString('default', { month: 'long' }) : "";
          },
          RIIncrement_Num: () => {
            return (ri.RIIncrement_Num) ? ri.RIIncrement_Num : "";
          },
          quartercreated: () => {
            const m = new Date(ri.Created_Ts.date).getMonth();
            return (m < 3) ? "Q1" : (m < 3) ? "Q2" : (m < 9) ? "Q3" : "Q4";
          },
          quarterclosed: () => {
            const m = (ri.RIClosed_Dt != null) ? new Date(ri.RIClosed_Dt.date).getMonth():"";
            mx = (ri.RIClosed_Dt == null) ? "" : (m < 3) ? "Q1" : (m < 6) ? "Q2" : (m < 9) ? "Q3" : "Q4";
            return mx;
          },
          duration: () => {
            const d = Math.floor((new Date(ri.Last_Update_Ts.date) - new Date(ri.Created_Ts.date))/(1000 * 60 * 60 * 24));
            let s = (d == 1) ? " day" : (d == "") ? "" : " days";
            return  `${d}${s}`;
            // let s = (d == 1) ? "s" : "";
            // return  `${d} day${s}`;
          },
          RI_Nm: () => {
              const url = `/risk-and-issues/details.php?au=false&status=${ri["RIActive_Flg"]}&popup=true&rikey=${ri["RiskAndIssue_Key"]}&fscl_year=${ri["Fiscal_Year"]}&proj_name=${ri["EPSProject_Nm"]}&uid=${ri["EPSProject_Id"]}`;
              return `<a href='${url}' onclickD='details(this);return(false)' class='miframe cboxElement'>${ri["RI_Nm"]}</a>`;
          },
          EPSProject_Nm: () => {
              const url = `/ri2.php?prj_name=${ri.EPSProject_Nm}&count=2&uid=${ri.EPSProject_Id}&fscl_year=${ri.Fiscal_Year}`;
              return "<a href='" + url + "' class='miframe cboxElement'>" + ri.EPSProject_Nm + "</a>";
          },
          driver: () => {
            return (driverlist[ri.RiskAndIssueLog_Key]) 
            ? (driverlist[ri.RiskAndIssueLog_Key]) 
            ? driverlist[ri.RiskAndIssueLog_Key].Driver_Nm : "" : "";
          },
          category: () => {
            let counter = 0;
            for(r of ridata) {
              if (r.EPSProject_Nm == ri.EPSProject_Nm) {
                counter++;
              }
            }
            return (counter > 1) ? "Associated" : "Single";
          },
          projectcount: () => {
            let counter = 0;
            for(r of ridata) {
              if (r.EPSProject_Nm == ri.EPSProject_Nm) {
                counter++;
              }
            }
            return counter;
          },
          subprogram: () => {
            if (ri.MLMProgramRI_Key != null) {
              p4plist[ri.RiskAndIssue_Key + "-" + ri.MLMProgram_Key];
            }
          }, 
        age: () => {
          let r = (aplist[ri.RiskAndIssue_Key]) ? new Date(aplist[ri.RiskAndIssue_Key].LastUpdate.date) : "";
          const d = (r == "") ? "" : (Math.floor((new Date() - r)/(1000 * 60 * 60 * 24)));
          let s = (d == 1) ? "&nbsp;day" : (d == "") ? "" : "&nbsp;days";
          return  `${d}${s}`;
        },
        actionplandate: () => {
          let r = (aplist[ri.RiskAndIssue_Key]) ? formatDate(new Date(aplist[ri.RiskAndIssue_Key].LastUpdate.date)) : "";
          return(r);
        },
        changelogdate: () => {
          let r = (loglist[ri.RiskAndIssue_Key]) ? formatDate(new Date(loglist[ri.RiskAndIssue_Key].LastUpdate.date)) : "";
          return(r);
        },
        // requestor: () => {
        //   let r = (loglist[ri.RiskAndIssue_Key]) ? "loglist[ri.RiskAndIssue_Key]" : "";
        //   return(r);
        // },
        // requestedaction: () => {
        //   // let r = (loglist[ri.RiskAndIssue_Key]) ? loglist[ri.RiskAndIssue_Key].RequestAction_Nm : "";
        //   let r = (loglist[ri.RiskAndIssue_Key]) ? loglist[ri.RiskAndIssue_Key].RequestAction_Nm : "";
        //   return(ri.RequestedAction_Nm);;
        // },
        // reason: () => {
        //   let r = (loglist[ri.RiskAndIssue_Key]) ? loglist[ri.RiskAndIssue_Key].Reason_Txt : "";
        //   return(r);
        // },
        programmanager: () => {
          let r = (loglist[ri.RiskAndIssue_Key]) ? ri.LastUpdateBy_Nm  : "";
          return(r);
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
      const logValues = [];
      if (mode == "project" && ri.RIType_Cd == "Issue" && (ri.RequestedAction_Nm != null || ri.Reason_Txt != "")) {
        for (field in changelog) {
          (function(test) {
              const t = (typeof fieldswitch[test] != "function") ? ri[test] : fieldswitch[test]();
              logValues.push((typeof t == "string" && t.indexOf("a href") == 1) ? t.substring((t.indexOf(">")+1), (t.indexOf("</a>"))) : t);
          })(field);
        }
        let newlog = document.changelog.addRow(logValues);
      }
      processcells();
      for(field in rifields) {
          (function(test) {
            const texter = (typeof fieldswitch[test] != "function") ? ri[test] : fieldswitch[test]();
            if (test == "actionplandate") {
              // console.log(Date.parse(texter));
              then = new Date(texter);
              now = new Date();
              console.log(Math.abs((then.getTime()-now.getTime()))/(24*60*60*1000));
            }
            // let bgcolor = (test == "ForecastedResolution_Dt" && Date.parse(texter) < (new Date()+1)) ? " hilite" : "";
            let bgcolor = (("ForecastedResolution_Dt" == test && (Date.parse(texter)+86400000) < Date.parse(new Date()))
                            || (["actionplandate"] == test && (Date.parse(texter)+86400000+2592000000) < Date.parse(new Date()))) ? " hilite" : 
                            (["actionplandate"] == test && (Date.parse(texter)+86400000+1296000000) < Date.parse(new Date())) ? " blulite" : "";
            // console.log(bgcolor)
            trri.appendChild(makeelement({"e": "td", "t": texter, "c": "p-4 datacell" + textalign(texter) + bgcolor }));
          })(field);
      }
      return trri;
    }  
    var modebutton = (target) => {
        let url = "<a href='" +"/risk-and-issues/dashboard/?mode=" + target + "' style='color:#fff' onclick='return false';>";
        let rest = (target == "portfolio") ? "RAID Log" : capitalize(target);
        return makeelement({"i": target + "mode", "t": url + rest + "</a>", "e": "div", "c": "btn btn-primary ml-3","j": function() {
            console.log("changing mode to " + target);
            init(target);
        }})
    }
    const makemodebuttons = () => {
        let m = document.getElementById("modebuttons");
        while (m.firstChild) {
            m.removeChild(m.lastChild);
        }
        modes.forEach(element => {
          let e = (element != mode) ? modebutton(element) :"";
            (e != "") ? m.appendChild(e):"";
            m.appendChild(document.createTextNode(" "));
        });
    }  
    const makeheadline = () => {
      document.title = document.getElementById("title").innerHTML = (mode == "portfolio") ? "RAID Log" : `${capitalize(mode)} R&I Dashboard`;
        
    }
    const fixcollapse = () => {
      document.querySelectorAll(".collapse").forEach(o => {
        o.style.overflow = "initial";
      })
    }

    const init = (target) => {
        mode = target;
        let url = new URL(window.location);
        url.searchParams.set("mode", mode);
        window.history.pushState({}, '', url);
        setdata();
        setlists();
        setTimeout(function(){
          makefilters();
          dofilters();
          rifiltered = filtration(ridata);
          let riseed = (ispp(mode)) ? getwholeuniques(rifiltered, "MLMProgram_Nm") : rifiltered;
          setTimeout(function() {
            populate(riseed);
          });
        });
        makeheadline();
        setTimeout(colorboxschtuff, 2000);
        makemodebuttons(mode);
        setTimeout(fixcollapse, 1000);
      }
      
      init(mode);
      setInterval(colorboxschtuff, 2000);
   
  </script>
  </body>
</html>