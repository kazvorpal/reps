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
    <title>Risk & Issues Dashboard 2.0</title>
    <link rel="shortcut icon" href="favicon.ico"/>
    <script src="../js/universal-functions.js"></script>
    <script src="../js/dashboard-functions.js"></script>
    <script src="../js/reactfunctions.js"></script>
    <?php 
        $mode = (stripos($_SERVER['REQUEST_URI'], "program")) ? "program" : "project";
        include ("../../includes/load.php");
        include ("../includes/cdns.php");
        include ("../includes/data-unified.php");
    ?>
<link rel="stylesheet" title="ri" href="../css/ri.css">
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
        <div class="col-lg-12 text-center">
        <h1 id="title">R&I Dashboard 2.0</h1>
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
      console.log("rilist", rilist);
      // rilist.forEach(o => console.log(typeof o[sort], o[sort], o, sort));
      portfoliocount = 0;

      rilist = (ispp(mode) && format != "grid") ? risort(rilist, "MLMProgram_Nm") : rilist;
      
      resultcounter(rilist);
      result = 0;
      window.ricount = [];
      pagestart = (page*pagesize) - pagesize;
      ps = (mode == "project" || format == "grid") ? (page*pagesize) : 100000;
      maxpages = 2;
      pagestop = (rilist.length < pagesize) ? rilist.length : ps;

      const main = document.getElementById("main");
      initexcel();
      main.innerHTML = (ispp(mode)) ? ` <div width="100%" align="left"><button value="" class="btn btn-default" onclick="togglegrid()" id="gridbutton">${(format == "grid") ? "Accordion Mode" : "Grid Mode"}</a></div>` :  '';
      if (ispp(mode) && format != "grid") {
        if (mode == "portfolio") {
          p = ", Portfolio";
          n = "RAID Log";
        } else {
          p = ", Programs";
          n = capitalize(mode);
        }
        sa = makeelement({e: "button", v: "", c: "btn btn-default", j: () => {toggleall(openval) }, i: "allbutton", t: "Expand All"});
        bn = makeelement({e: "div", c: "header", t: `${capitalize(mode)} Name (Risks, Issues${p}) `});
        main.appendChild(bn).appendChild(sa);
      } else {
        main.appendChild(makeelement({e: "table", i: "maintable", c: "table"}));
        var mt = document.getElementById("maintable");
        mt.appendChild(makeheader("projects"));
      }
      if (mode == "project" || format == "grid") 
        rilist.forEach(o => { createrow(o, true); })
      for (loop = pagestart; loop < pagestop; loop++ ) {
          // This loop creates the programs/portfolios (makerow) or projects (createrow), based on the  mode. 
        if(loop != null && typeof rilist[loop] != "undefined") {
          (ispp(mode) && format != "grid") ? makerow(rilist[loop], listri(rilist[loop].MLMProgram_Nm, "Risk").length, listri(rilist[loop].MLMProgram_Nm, "Issue").length) : mt.appendChild(createrow(rilist[loop], false));
        }
      }
      (ispp(mode) && format != "grid") && resultcounter(result);
      pages = Math.ceil(rilist.length/pagesize);
      if (pages > 0 && page > pages) {
        page = 1;
        let url = new URL(window.location);
        url.searchParams.set("mode", mode);
        url.searchParams.set("page", page);
        url.searchParams.set("pagesize", pagesize);
        window.history.pushState({}, '', url);
        init(mode);
      }
      if (mode == "project" || format == "grid") {
        ttt = "";
        paginator = makeelement({e: "div", i: "pagination", t: ttt, c: "pagination"})
        paginator.innerHTML += (page > 1) ? `<a href='/risk-and-issues/dashboard/?mode=${mode}&page=${parseInt(page)-1}&pagesize=${pagesize}' onclick='pager(${parseInt(page)-1});return false';> < </a>` : "";
        for (loop = 1; loop < pages+1; loop++) {
          if (loop == 1 || ((loop + maxpages) >= page && (loop-maxpages) <= page) || loop == (parseInt(pages))) {
            let url = (page == loop) ? "<a class='selectedpage pages'>" : `<a href='/risk-and-issues/dashboard/?mode=${mode}&page=${loop}&pagesize=${pagesize}' class="pages" onclick='pager(${loop});return false';>`;
            paginator.innerHTML += url + loop + "</a>";
          } else {
             paginator.innerHTML += "…";
            loop = (loop > page) ? parseInt(pages)-1 : page - maxpages - 1;
          }
        }
        paginator.innerHTML += (page < pages) ? `<a href='/risk-and-issues/dashboard/?mode=${mode}&page=${parseInt(page)+1}&pagesize=${pagesize}' onclick='pager(${parseInt(page)+1});return false';> > </a>` : "";
         main.appendChild(paginator);
      }
    }
    const pager = (target) => {
      page = target;
      init(mode);
    }

    const makerow = (target, risks, issues) => {
      // Runs once per Program
      if (typeof target == null) {
        return false;
      }
      // console.log("makerow: ");
      // console.log(target);
      if (target.MLMProgram_Nm == null || target.MLMProgram_Nm == "null" || (risks == 0 && issues == 0)) return false;
      const safename = makesafe(target.MLMProgram_Nm);
      const item = makeelement({"e": "div", "i": "item" + safename, "c": "toppleat accordion-item"});
      const banner = makebanner(safename);
      const collapse = makeelement({e: "div", i: "row" + safename, c: "panel-collapse collapse"});
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
      let p = (mode == "program") ? ` <span title="Project Count">P: ${projectcount}</span>` : ``;
      document.getElementById("banner" + safename).innerHTML += p + ' )';
    }
      
    const makebanner = (safename) => {
      
      // Program Start
      const bannerfields = {"aria-labelledby": "banner" + safename, "data-bs-target": "#row" + safename, "data-target": "#row" + safename, "data-toggle": "collapse", "aria-controls": "row" + safename};
      const banner = document.createElement("div");
      banner.id = "banner" + safename;
      banner.className = "accordion-banner";
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
      document.getElementById("banner" + safename).innerHTML += `  <span title="${capitalize(type)} Count">${type.charAt(0).toUpperCase()}:${list.length}</span>`;
      if (list.length != 0) {
        document.getElementById("table"+makesafe(programname)).appendChild(makeheader(programname, type));
        for (rikey of list) {
          result++;
          window.ricount.push(true);
          rowcolor++;
          makedata(rikey, type, programname);
          program = getprogrambykeyonly(rikey);
            portfoliocount += (program.RI_Nm.toLowerCase().indexOf("portfolio")>-1) ? 1 : 0;
            projectcount += (p4plist[program.RiskAndIssue_Key + "-" + program.MLMProgramRI_Key] != null ) ? (p4plist[program.RiskAndIssue_Key + "-" + program.MLMProgramRI_Key].length != 0) : 0;
          }
      } else {
      }
    }
    const richtextfields = ["RIDescription_Txt", "ActionPlanStatus_Cd"];
    const makedata = (id, type, programname) => {            
        // Make all the data inside a risk or issue, Program and Portfolio

        const programlist = () => {
          // programs = ri.MLMProgram_Nm;
          programs = ri.Program_Nm;
          portfolioprograms.forEach((o) => {
            let comma = (programs != "") ? ", " : ""
            if (o.RiskAndIssue_Key == ri.RiskAndIssue_Key 
              && programs.indexOf(o.MLMProgram_Nm) == -1
              && programs.indexOf(o.Program_Nm) == -1) {
                console.log(o);
              programs = programs + comma + o.Program_Nm;
            } 
          });
        }
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
            if (ri["MLMProgram_Key"] || mode("project")) {
              const manger = mangerlist[ri["Fiscal_Year"] + "-" + ri["MLMProgram_Key"]];
              let mangers = [ri["Fiscal_Year"]];
              for (man of manger) {
                  mangers.push(man.User_Nm);
              }  
              return mangers.join().replace(",", ", ");
            } else
              return "";
          },
          global: () => {
              return  (ri.Global_Flg) ? "Y" : "N";
            },
          groupcount: () => {
            let gc = 0;
            ridata.forEach(o => {
              gc += (ri.RIIncrement_Num == o.RIIncrement_Num && ri.RIActive_Flg == o.RIActive_Flg) ? 1 : 0;
            })
            return gc;
          },
          category: () => {
            return  (ri.Global_Flg) ? "Global" : "Program";
          },
          EPSSubprogram_Nm: () => {
              return getlocationbykey(ri.EPSProject_Key)
          },
          ForecastedResolution_Dt: () => {
              return (ri.ForecastedResolution_Dt == null) ? "Unknown" : makestringdate(ri.ForecastedResolution_Dt);
          },
          RIActive_Flg: () => {
              return (ri.RIActive_Flg) ? "Open" : "Closed";
          },
          Created_Ts: () => {
              return makestringdate(ri.Created_Ts);
          },
          monthcreated: () => {
              return new Date(ri.Created_Ts.date).toLocaleString('default', { month: 'long' });
          },
          monthclosed: () => {
              return (ri.RIClosed_Dt != null) ? new Date(ri.RIClosed_Dt.date).toLocaleString('default', { month: 'long' }) : "";
          },
          quartercreated: () => {
              const m = new Date(ri.Created_Ts.date).getMonth();
              return  (m < 3) ? "Q1" : (m < 6) ? "Q2" : (m < 9) ? "Q3" : "Q4";
          },
          quarterclosed: () => {
              const m = (ri.RIClosed_Dt != null) ? new Date(ri.RIClosed_Dt.date).getMonth():"";
              return  (ri.RIClosed_Dt == null) ? "" : (m < 3) ? "Q1" : (m < 6) ? "Q2" : (m < 9) ? "Q3" : "Q4";
          },
          duration: () => {
              const enddate = (ri.RIActive_Flg == 1 || ri.Created_Ts.date < ri.RIClosed_Dt) ? ri.Last_Update_Ts.date : ri.RIClosed_Dt;
              const d = Math.floor((new Date(enddate) - new Date(ri.Created_Ts.date))/(1000 * 60 * 60 * 24));
              return  d + " days";
          },
          Last_Update_Ts: () => {
              return  makestringdate(ri.Last_Update_Ts);
          },
          RIClosed_Dt: () => {
              return  (ri.RIClosed_Dt != null) ? formatDate(new Date(ri.RIClosed_Dt.date)) : "";
          },
          AssociatedCR_Key: () => {
              return  (ri.AssociatedCR_Key) ? "Y" : "N";
          },
          MLMRegion_Cd: () => {
              let list = ""
              let counter = 0;
              for(r of ridata) {
                if (r) {
                  if (r.RI_Nm == ri.RI_Nm && r.MLMProgram_Nm == ri.MLMProgram_Nm) {
                    counter++;
                    list += (!isempty(regions[r.MLMRegion_Cd])) ? regions[r.MLMRegion_Cd] + ", " : (!isempty(regions[r.MLMRegion_Cd])) ? r.MLMRegion_Cd : "";
                  }
                }
              }
              return (list.slice(0, -2));
              // return "";
          },
          regioncount: () => {
              let counter = 0;
              for(r of ridata) {
                  if (!isempty(r) && r.RI_Nm == ri.RI_Nm && r.MLMProgram_Nm == ri.MLMProgram_Nm) {
                  counter++;
                  }
              }
              return counter;
          },
          RaidLog_Flg: () => {
              return  (ri.RaidLog_Flg) ? "Y" : "N";
          },
          RiskRealized_Flg: () => {
              return  (ri.RiskRealized_Flg) ? "Y" : "N";
          },
          RIOpen_Hours: () => {
              return Math.floor(ri.RIOpen_Hours/24) + " days";
          },
          driver: () => {
              return (driverlist[ri.RiskAndIssueLog_Key]) 
              ? (driverlist[ri.RiskAndIssueLog_Key]) 
              ? driverlist[ri.RiskAndIssueLog_Key].Driver_Nm : "" : "";
          },
          projectcount: () => {
              let projects = p4plist[ri.RiskAndIssue_Key + "-" + ri.MLMProgramRI_Key];
              return (projects != undefined && projects.length>0) ? projects.length : "";
          }, 
          subprogram: () => {
            let list = "";
            let prog = sublist[ri.RiskAndIssue_Key];
            if (prog != undefined) {
              for(r of prog) {
                let comma = (list.length > 0) ? ", " : "";
                list += comma + r.SubProgram_Nm ;
              } 
            }
            console.log(list)
            let ret = (list != "") ? list : ""
            // let ret = (list != "") ? list.slice(0, -2) : ""
            console.log("ret", ret);
            return ret;
          }, 
          MLMProgram_Nm: () => {
            if (ri.Global_Flg == 1) {
              let programs = "";
              let portprog = (ri.RIActive_Flg) ? portfolioprograms : portfolioprogramsclosed;
              portprog.forEach(o => {
                let comma = (programs != "") ? ", " : "";
                if (o.RiskAndIssue_Key == ri.RiskAndIssue_Key) {
                  programs = (programs.indexOf(o.Program_Nm) == -1) ? programs + comma + o.Program_Nm : programs;
                }
              })
              if (programs == "" ) {
                programs = ri.MLMProgram_Nm;
              }
              return (programs);
            } else {
              return ri.MLMProgram_Nm;
            }
          },
          programcount: () => {
              let programs = "";
              let pc = 0;
              portfolioprograms.forEach((o) => {
                let comma = (programs != "") ? ", " : ""
                if (o.RiskAndIssue_Key == ri.RiskAndIssue_Key 
                  && programs.indexOf(o.MLMProgram_Nm) == -1 && programs.indexOf(o.Program_Nm) == -1) {
                    console.log(o);
                  programs = (programs.indexOf(o.Program_Nm) == -1) ? programs + comma + o.Program_Nm : programs;
                  // programs = programs + comma + o.Program_Nm;
                  pc++;
                } 
              });
              if (ri.RiskAndIssue_Key == 2181) 
                console.log("pc", programs);
              if (pc == 0) {
                pc = 1;
              }
              return (pc);
          },
          age: () => {
            let r = (aplist[ri.RiskAndIssue_Key]) ? new Date(aplist[ri.RiskAndIssue_Key].LastUpdate.date) : "";
            let d = (r == "") ? "" : (Math.floor((new Date() - r)/(1000 * 60 * 60 * 24))+1) ;
            let s = (d == 1) ? " day" : (d == "") ? "" : " days";
            return  `${d}${s}`;
          },
          RIDescription_Txt: () => {
            // return ri.RIDescription_Txt
            return trimmer(ri.RIDescription_Txt, ri.RiskAndIssue_Key, "desc");
          },
          ActionPlanStatus_Cd: () => {
            // return ri.ActionPlanStatus_Cd;
            return trimmer(ri.ActionPlanStatus_Cd, ri.RiskAndIssue_Key, "plan");
          },
          actionplandate: () => {
            let r = (aplist[ri.RiskAndIssue_Key]) ? formatDate(new Date(aplist[ri.RiskAndIssue_Key].LastUpdate.date)) : "";
            return(r);
          }
        };
        const ri = getprogrambykeyonly(id, programname);
        // const safename = (ri.RILevel_Cd == "Portfolio") ? "Portfolio" : makesafe(ri.MLMProgram_Nm);
        const safename = makesafe(ri.MLMProgram_Nm);
        const saferi = makesafe(ri.RI_Nm);
        let url = text = "";
        const trid = "tr" + type + saferi + Math.random();
        let bgclass = (rowcolor % 2 == 0) ? " evenrow" : " oddrow";
        // console.log("table" + safename, document.getElementById("table" + safename))
        document.getElementById("table" + safename).appendChild(makeelement({e: "tr", i: trid, c: bgclass}));
        const arrow = (p4plist[ri.RiskAndIssue_Key + "-" + ri.MLMProgramRI_Key] != null ) 
          ? (p4plist[ri.RiskAndIssue_Key + "-" + ri.MLMProgramRI_Key].length != 0) 
          ? "▶" : "" : "";
        const file = (ri.Global_Flg) ? "global/details.php" : "details-prg.php";
        url = `/risk-and-issues/${file}?au=false&status=${ri["RIActive_Flg"]}&popup=true&rikey=${ri["RiskAndIssue_Key"]}&fscl_year=${ri["Fiscal_Year"]}&program=${ri.MLMProgram_Nm}&proj_name=null&unframe=false`;
        text = `<a href='${url}' class='miframe cboxElement'>${ri["RiskAndIssue_Key"]}</a>`;
        const c = (arrow == "" || mode == "portfolio") ? "plainbox" : "namebox";
        const w = (mode == "portfolio") ? "" : "";
        const header = makeelement({
          e: "th", 
          i: "th" + type + saferi, 
          t: "<div style='overflow:hidden'>" + ri.RI_Nm + "</div>", 
          c:"p-1 " + c,
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
              let texter = (typeof fieldswitch[test] != "function") ? ri[test] : fieldswitch[test]();
              let bgcolor = ((test == "ForecastedResolution_Dt" && (Date.parse(texter)+86400000) < Date.parse(new Date()))
                              || (checkage(29, test, texter))) ? " hilite"
                               : (checkage(14, test, texter)) ? " blulite" : "";
              let wrapping = (richtextfields.includes(test)) ? " overflow-everything" : "";
              tridobj.appendChild(makeelement({e: "td", t: texter, c: "p-1 datacell align-middle " + wrapping + textalign(texter) + bgcolor, w: w}));
            })(field);
            if (rifields[field].name == "ID") {
              tridobj.appendChild(header);
            }
        }
        var rowValues = [];
        for (field in excelfields) {
            (function(test) {
                let t = (typeof fieldswitch[test] != "function") ? ri[test] : fieldswitch[test]();
                t = ((typeof t == "string" && t.indexOf("span") == 1) ? t.substring((t.indexOf(">")+1), (t.indexOf("</span>"))) :t);
                rowValues.push((typeof t == "string" && t.indexOf("a href") == 1) ? t.substring((t.indexOf(">")+1), (t.indexOf("</a>"))) :t);
            })(field);
        }
        let newrow = document.worksheet.addRow(rowValues);
        processcells();
        if(arrow != "") {
          makeprojects(p4plist[ri.RiskAndIssue_Key + "-" + ri.MLMProgramRI_Key], ri.MLMProgram_Nm, "table" + safename, saferi);
        }
    }    

    const checkage = (limit, test, texter) => ("age" == test && texter.replace(/\D/g, '') > limit); // check age of test against number of days in limit

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
              const tr = document.createElement("tr");
              tr.id = "tr" + project.PROJECT_key;
              for (field of projectfields) {
                  locale = getlocationbykey(project.PROJECT_key);
                  txt = (field == "MLMRegion_Cd" && locale != undefined) ? locale.Region_Cd 
                  : (field == "Subprogram" && locale != undefined) ? locale.Subprogram_nm 
                  : (field == "Market_Cd" && locale != undefined) ? locale.Market_Cd 
                  : (field == "EPS_Location_Cd" && locale != undefined)  ? locale.Facility_Cd 
                  : project[field];
                  tr.appendChild(makeelement({e: "td", t: txt, c: "p4 datacell align-middle"}));
              }
              document.getElementById("table" + saferi).appendChild(tr);
              p.push(project.EPSProject_Key);
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
            trri.appendChild(makeelement({e: "th", t: projectfieldnames[field].name, c: "p-1 subtitles", w: projectfieldnames[field].width}));
        }
        return trri;
    }

    const sortcol = (key, target) => {
      if (sortable(key)) {
        console.log(target.innerHTML)
        if (target.innerHTML.indexOf("↓") != -1) {
          reverse = true;
        } else {
          reverse = false;
        }
        sort = key;
        init(mode);
      }
    }

    const sortable = (key) => {
      return ((format == "grid" || mode == "project") && !(key in fieldmap));
    }

    const makeheader = (name, type) => {
      
      // Make the header for Grids
        const sortthisstring = "click here to sort by this field";
        const safename = makesafe(name);
        const trri = makeelement({"e": "tr", "i": type + safename, "t": "", "c":"p-1<?= $headerposition ?>"});
        if (ispp(mode)) {
            let namefield = (format == "grid") ? "R/I Name" : type;
            let cells = ["Risk/Issue"];
            rowcolor = 1;
            Object.entries(rifields).forEach(([key, value]) => {
              let direction = b1 = b2 = "";
              if (sort == key) {
                direction = sortable(key) ? (!reverse) ? "&nbsp;↓" : "&nbsp;↑" : "";
                b1 = sortable(key) ? "<u>" : "";
                b2 = sortable(key) ? "</u>" : "";
              }
              const c = sortable(key) ? " active" : (type == "Risk" || format == "grid") ? "  " : " issueheader ";
              const title = sortable(key) ? sortthisstring : "Sorry, sorting by this column is not yet supported";
              trri.appendChild(makeelement({"e": "th", "t": b1 + value.name + b2 + direction, "c": "p-1 titles align-middle" + c, a: title, "j": function() {
                  sortcol(key, this)
              }}));
              cells.push(rifields[key].name);
              if (rifields[key].name == "ID") {
                if (sort == "RI_Nm") {
                  direction = sortable(key) ? (!reverse) ? "&nbsp;↓" : "&nbsp;↑" : "";
                  b1 = sortable(key) ? "<u>" : "";
                  b2 = sortable(key) ? "</u>" : "";
                } else direction = b1 = b2 = "";
                const c = sortable("RI_Nm") ? " active" : (type == "Risk" || format == "grid") ? "  " : " issueheader ";
                trri.appendChild(makeelement({"e": "th", "t": b1 + namefield + b2 + direction, "c": "p-1 text-center titles align-middle" + c, "w": "12", a: sortthisstring, "j": function() {
                  sortcol("RI_Nm", this)
                }}));
                if (sortable(key)) {
                  if (sort == "RIType_Cd") {
                    direction = sortable(key) ? (!reverse) ? "&nbsp;↓" : "&nbsp;↑" : "";
                    b1 = sortable(key) ? "<u>" : "";
                    b2 = sortable(key) ? "</u>" : "";
                  } else direction = b1 = b2 = "";
                  const c = sortable("RIType_Cd") ? " active" : (type == "Risk" || format == "grid") ? "  " : " issueheader ";
                  trri.appendChild(makeelement({"e": "th", "t": b1 + "Type" + b2 + direction, "c": "text-center titles align-middle typecolumn " + c, "w": "12", a: sortthisstring, "j": function() {
                    sortcol("RIType_Cd", this)
                  }}));
                }
              }
            })
          } else {
              let cells = [];
              // console.log(rifields)
              Object.entries(rifields).forEach(([key, value]) => {
                let direction = b1 = b2 = "";
                if (sort == key) {
                  direction = sortable(key) ? (!reverse) ? "&nbsp;↓" : "&nbsp;↑" : "";
                  b1 = sortable(key) ? "<u>" : "";
                  b2 = sortable(key) ? "</u>" : "";
              }
              let name = (typeof value == "object") ? value.name : value;
              let c = sortable(key) ? " active" : "";
              const title = sortable(key) ? sortthisstring : "Sorry, sorting by this column is not yet supported";
              trri.appendChild(makeelement({"e": "td", "t": b1 + name + b2 + direction, "c": "p-1 titles align-middle" + c, a: title, "j": function() {
                sortcol(key, this)
              }}));
              cells.push(value);
            })
        }
        excelrows();
        return trri;
    }

    const createrow = (ri, excel) => {
      // Create a row in the Project table
      if (typeof ri == "undefined") 
        return false ;
      const name = ri.RI_Nm;
      const safename = makesafe(ri["RI_Nm"]);
      const trri = makeelement({"e": "tr", "i": "row" + safename, "t": "", "c":"p-1 datarow"});
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
          RiskAndIssue_Key: () => {
            let status = (ispp(mode)) ? `<a href='${url}' class='miframe cboxElement'>${ri["RiskAndIssue_Key"]}</a>` : (ri.RIActive_Flg == 1) ? `${ri["RiskAndIssue_Key"]} <span title='Status: Open' style='color:#080;font-size:xx-small'>Open</span>` : `${ri["RiskAndIssue_Key"]} <span title='Status: Closed' style='color:#800;font-size:xx-small'>Closed</span>`;
            return status;
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
          },
          RI_Nm: () => {
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
            return (ispp(mode) && format == "grid") ? (ri.Global_Flg) ? "Global" : "Program" :(counter > 1) ? "Associated" : "Single";
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
          programcount: () => {
              let programs = "";
              let pc = 0;
              portfolioprograms.forEach((o) => {
                let comma = (programs != "") ? ", " : ""
                if (o.RiskAndIssue_Key == ri.RiskAndIssue_Key 
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
          subprogram: () => {
            if (ri.MLMProgramRI_Key != null) {
              p4plist[ri.RiskAndIssue_Key + "-" + ri.MLMProgram_Key];
            }
            let list = "";
            let prog = sublist[ri.RiskAndIssue_Key];
            if (prog != undefined) {
              for(r of prog) {
                let comma = (list.length > 0) ? ", " : "";
                list += comma + r.SubProgram_Nm ;
              } 
            }
            // let ret = (list != "") ? list.slice(0, -2) : ""
            // console.log("ret");
            // console.log(ret);
            return list;
          }, 
        age: () => {
          let r = (aplist[ri.RiskAndIssue_Key]) ? new Date(aplist[ri.RiskAndIssue_Key].LastUpdate.date) : "";
          const d = (r == "") ? "" : (Math.floor((new Date() - r)/(1000 * 60 * 60 * 24)));
          let s = (d == 1) ? "&nbsp;day" : (d == "") ? "" : "&nbsp;days";
          return  `${d}${s}`;
        },
        actionplandate: () => {
          return (aplist[ri.RiskAndIssue_Key]) ? formatDate(new Date(aplist[ri.RiskAndIssue_Key].LastUpdate.date)) : "";
        },
        changelogdate: () => {
          return (loglist[ri.RiskAndIssue_Key]) ? formatDate(new Date(loglist[ri.RiskAndIssue_Key].LastUpdate.date)) : "";
        },
        RIDescription_Txt: () => {
          // return ri.RIDescription_Txt;
          return trimmer(ri.RIDescription_Txt, ri.RiskAndIssue_Key, "desc");
        },
        ActionPlanStatus_Cd: () => {
          let plan = ri.ActionPlanStatus_Cd;
          let key = ri.RiskAndIssue_Key;
          // return plan;
          return trimmer(plan, key, "plan");
        },
        programmanager: () => {
          let r = (loglist[ri.RiskAndIssue_Key]) ? ri.LastUpdateBy_Nm  : "";
          return(r);
        }, 
        PRJI_Estimated_Act_Ts: () => (ri.PRJI_Estimated_Act_Ts != null) ? formatDate(new Date(ri.PRJI_Estimated_Act_Ts.date)) : "", 
        PRJI_Estimated_Mig_Ts: () => {
          return (ri.PRJI_Estimated_Mig_Ts != null ) ? formatDate(new Date(ri.PRJI_Estimated_Mig_Ts.date)) : "";
        }
      };
      // console.log(ri.Global_Flg, ri);
      const file = (ri.Global_Flg) ? "global/details.php" : (ri.RILevel_Cd == "Program") ? "details-prg.php" : "details.php"
      const url = (["Program", "Portfolio"].includes(ri.RILevel_Cd)) 
        ? `/risk-and-issues/${file}?au=false&status=${ri["RIActive_Flg"]}&popup=true&rikey=${ri["RiskAndIssue_Key"]}&fscl_year=${ri["Fiscal_Year"]}&program=${ri.MLMProgram_Nm}&proj_name=null&unframe=false`
        : `/risk-and-issues/${file}?au=false&status=${ri["RIActive_Flg"]}&popup=true&rikey=${ri["RiskAndIssue_Key"]}&fscl_year=${ri["Fiscal_Year"]}&proj_name=${ri["EPSProject_Nm"]}&uid=${ri["EPSProject_Id"]}&unframe=false`;
      const rowValues = [];
      const saferi = makesafe(ri.RI_Nm);
      let c = "plainbox";
      const header = makeelement({
          e: "th", 
          i: "th" + "type" + saferi, 
          t: "<div style='overflow:hidden'>" + ri.RI_Nm + "</div>", 
          c:"p-1 " + c,
      });
      const type = makeelement({
          e: "th", 
          i: "th" + "type" + saferi, 
          t: "<div style='overflow:hidden'>" + ri.RIType_Cd + "</div>", 
          c:"p-1 " + c,
      });
      if (excel) {
        for (field in excelfields) {
          (function(test) {
              let t = (typeof fieldswitch[test] != "function") ? ri[test] : fieldswitch[test]();
              t = striptags(t);
              t = (test == "RiskAndIssue_Key") ? t.replace(/Open|Closed/g, '') : t;
              rowValues.push((typeof t == "string" && t.indexOf("a href") == 1) ? t.substring((t.indexOf(">")+1), (t.indexOf("</a>"))) : t);
            })(field);
          }
          let newrow = document.worksheet.addRow(rowValues);
          const logValues = [];
          if (mode == "project" && ri.RIType_Cd == "Issue" && (!isempty(ri.RequestedAction_Nm) || !isempty(ri.Reason_Txt))) {
            for (field in changelog) {
              (function(test) {
                t = (typeof fieldswitch[test] != "function") ? ri[test] : fieldswitch[test]();
                t = striptags(t);
                t = (test == "RiskAndIssue_Key") ? t.replace(/Open|Closed/g, '') : t;
              logValues.push((typeof t == "string" && t.indexOf("a href") == 1) ? t.substring((t.indexOf(">")+1), (t.indexOf("</a>"))) : t);
            })(field);
          }
          let newlog = document.changelog.addRow(logValues);
        }
        processcells();
      } else {
        for(field in rifields) {
            (function(test) {
              let texter = (typeof fieldswitch[test] != "function") ? ri[test] : fieldswitch[test]();
              let bgcolor = (("ForecastedResolution_Dt" == test && (Date.parse(texter)+86400000) < Date.parse(new Date()))
                              || (checkage(29, test, texter))) ? " hilite" : 
                              (checkage(14, test, texter)) ? " blulite" : "";
              let wrapping = (richtextfields.includes(test)) ? " overflow-everything" : "";
              trri.appendChild(makeelement({"e": "td", "t": texter, "c": "p-1 datacell align-middle" + wrapping + textalign(texter) + bgcolor }));
            })(field);
            if (rifields[field].name == "ID") {
              // console.log("addons")
              trri.appendChild(header);
              trri.appendChild(type);
            };
        }
        return trri;
      }
    }  
    var modebutton = (target) => {
        let url = `<a href='/risk-and-issues/dashboard/?mode=${target}&page=${page}' style='color:#fff' onclick='return false';>`;
        let rest = (target == "portfolio") ? "RAID Log" : capitalize(target);
        return makeelement({"i": target + "mode", "t": url + rest + "</a>", "e": "div", "c": "btn btn-primary ml-1","j": function() {
            console.log("changing mode to " + target);
            init(target, true);
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
      document.title = document.getElementById("title").innerHTML = (mode == "portfolio") ? "RAID Log 2.0" : `${capitalize(mode)} R&I Dashboard 2.0`;
    }
    const fixcollapse = () => {
      document.querySelectorAll(".collapse").forEach(o => {
        o.style.overflow = "initial";
      })
    }

    const fieldmap = {
      "subprogram": "",
      "actionplandate": "", 
      "age": "aplist[list.RiskAndIssue_Key].LastUpdate.date", 
      "ForecastedResolution_Dt": "ForecastedResolution_Dt.date",
      "RIDescription_Txt": "",
      "ActionPlanStatus_Cd": "",
      programcount: ""
  }

//   const risort = (list, field) => {
//   field = (field === "category") ? "Global_Flg" : field;

//   const getSortableValue = (val) => {
//     if (val === null) return -Infinity;
//     return val;
//   }

//   const sortedList = list.sort((a, b) => {
//     const aValue = getSortableValue(a[field]);
//     const bValue = getSortableValue(b[field]);

//     return aValue < bValue ? -1 : aValue > bValue ? 1 : 0;
//   });

//   return !reverse ? sortedList : sortedList.reverse();
// }

  const risort = (list, field) => {
    field = (field === "category") ? "Global_Flg" : (field == "LastUpdateBy_Nm") ? "LastUpdate_By" : field;
    let qs = list.sort((a, b) => {
      const aValue = a[field] === null ? "ZZZZ" : (typeof a[field] === "boolean" ? (a[field] ? 1 : 0) : a[field]);
      const bValue = b[field] === null ? "ZZZZ" : (typeof b[field] === "boolean" ? (b[field] ? 1 : 0) : b[field]);
      // console.log(aValue, (aValue < bValue) ? "<" : ">", bValue)

      return aValue === bValue ? 0 : (aValue < bValue ? -1 : 1);
    });
    return !reverse ? qs : qs.reverse();
  };
    // const risort = (list, field) => {
    //   // new Date(aplist[ri.RiskAndIssue_Key].LastUpdate.date)
    //   field = (field == "category") ? "Global_Flg" : field;
    //   let qs = list.sort((a, b) => {
    //     // e = (!b[field] || a[field] < b[field]) ? -1 : (!a[field] || a[field] > b[field]) ? 1 : 0;
    //     e = a[field] === b[field] ? 0 : a[field] < b[field] ? -1 : 1;
    //     console.log(a[field], e ? "<" : ">", b[field])
    //     console.log(field)
    //     return e;
    //   });
    //   return (!reverse) ? qs : qs.reverse();
    // }
    // const fieldMap = {
    //   "age": "aplist[list.RiskAndIssue_Key].LastUpdate.date", 
    //   "ForecastedResolution_Dt": "ForecastedResolution_Dt.date"
    // };
    // const risort = (list, field) => {
    //   console.log(aplist, list)
    //   let qs = list.sort((a, b) => {
    //     let aField = fieldMap[field] || field;
    //     let bField = fieldMap[field] || field;
    //     let aVal = (aField.includes(".")) ? aField.split(".").reduce((obj, key) => obj[key], a) : a[aField];
    //     let bVal = (bField.includes(".")) ? bField.split(".").reduce((obj, key) => obj[key], b) : b[bField];
    //     console.log(aVal, bVal, aField, bField)
    //     return (aVal < bVal) ? -1 : (aVal > bVal) ? 1 : 0;
    //   });
    //   return qs;
    // };

    const init = (target, modechange) => {
      mode = target;
      firstload = (typeof firstload == "undefined") ? true : false;
      let url = new URL(window.location);
      url.searchParams.set("mode", mode);
      url.searchParams.set("page", page);
      url.searchParams.set("pagesize", pagesize);
      window.history.pushState({}, '', url);
      setdata();
      // console.log("default ridata:", ridata);
      setlists();
      setTimeout(function(){
        if(modechange) {
          console.log("mode change");
          makefilters();
          dofilters();
        } else console.log("Not changing mode")
        rifiltered = risort(filtration(ridata), sort);
        let riseed = (ispp(mode)) ? getwholeuniques(rifiltered, "MLMProgram_Nm") : rifiltered;
        if (ispp(mode) && format == "grid") {
          let extralist = [];
          ["Risk", "Issue"].forEach(x => {
            for (loop of riseed) {
              templist = (listri(loop.MLMProgram_Nm, x));
              templist.forEach(o => {
                extralist.push(getprogrambykeyonly(o));
              });
            }
          });
          // console.log("riseed", sort)
          riseed = getwholeuniques(riseed.concat(extralist), "RiskAndIssue_Key");
          // riseed.forEach(o => console.log(o[sort]));
          riseed = risort(riseed, sort);
          resultcounter(riseed.length);
          setTimeout(function() {
            // console.log("two")
            // riseed.forEach(o => console.log(o[sort]));
            populate(riseed);
          });
        } else {
          setTimeout(function() {
            populate(riseed);
          });
        }
        // ridata = riseed;
      });
      makeheadline();
      setTimeout(colorboxschtuff, 2000);
      makemodebuttons(mode);
      setTimeout(fixcollapse, 1000);
    }
      
    init(mode);
    setInterval(colorboxschtuff, 2000);
   
  </script>
  <!-- <div id="config-lightbox-container"></div>
  <button onclick="renderLightbox()">Open Lightbox</button> -->

  </body>
</html>