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
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>  
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Risk & Issues Dashboard 2.0</title>
    <link rel="shortcut icon" href="favicon.ico"/>
    <script src="../js/universal-functions.js"></script>
    <script src="../js/dashboard-functions.js"></script>
    <script src="../js/reactfunctions.js"></script>
    <?php 
        $mode = (stripos($_SERVER['REQUEST_URI'], "program")) ? "program" : "project";
        include ("../includes/cdns.php");
        include ("../../includes/load.php");
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
  <?php include ("../../includes/menu-4.php");
  ?>
  <section>
    <div class="row" align="center">
      <div style="width:98%">
        <div class="col-lg-12 text-center" style="padding: 30px">
          <h1 id="title">R&I Dashboard 2.0</h1>
          <div style="display:inline-block;width:20%;text-align:right"><span class="btn btn-primary oldbutton" onclick="exporter()">Export Results</span></div> <div style="display:inline-block;padding:4px;text-align:center;font-size:larger;" id="resultcount"></div> <div id="modebuttons" style="display:inline-block;width:20%;text-align:left"> Switch To: <p><p/><p/></div>
          
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
      portfoliocount = 0;
      rilist = (ispp(mode) && format != "grid") ? risort(rilist, "MLMProgram_Nm") : rilist;
      
      resultcounter(rilist);
      result = 0;
      window.ricount = [];
      pagestart = (page*pagesize) - pagesize;
      ps = (mode == "project" || format == "grid") ? (page*pagesize) : 100000;
      pagestop = (rilist.length < pagesize) ? rilist.length : ps;

      const main = document.getElementById("main");
      initexcel();
      main.innerHTML = (ispp(mode)) ? ` <div width="100%" align="left"><button value="" class="btn btn-default oldbuttonclear" id="gridbutton">${(format == "grid") ? "Accordion Mode" : "Grid Mode"}</a></div>` :  '';
      let gridButton = document.getElementById("gridbutton");
      gridButton && gridButton.addEventListener("click", () => {
          togglegrid();
      });
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
      c1 = c2 = 0;
      rilist.forEach((value, key) => {
        // excelrow(value, key);
        c1++;
        let newrow = document.worksheet.addRow(makeexcel(value));
        // console.log({value})
        logger(value);
        if (key > pagestart-1 && key < pagestop && key != null && typeof rilist[key] != "undefined") {
          (ispp(mode) && format != "grid") ? makerow(rilist[key], listri(rilist[key].MLMProgram_Nm, "Risk").length, listri(rilist[key].MLMProgram_Nm, "Issue").length) : mt.appendChild(createrow(rilist[key], false));
          c2++;
        }
      });
      console.log({c1}, {c2});
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
          ri = getprogrambykeyonly(rikey, programname);
          createrow(ri, type);
          program = getprogrambykeyonly(rikey);
            portfoliocount += (program.RI_Nm.toLowerCase().indexOf("portfolio")>-1) ? 1 : 0;
            projectcount += (p4plist[program.RiskAndIssue_Key + "-" + program.MLMProgramRI_Key] != null ) ? (p4plist[program.RiskAndIssue_Key + "-" + program.MLMProgramRI_Key].length != 0) : 0;
          }
      }
    }
    var c;
    const richtextfields = ["RIDescription_Txt", "ActionPlanStatus_Cd"];

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
              // p.push(project.EPSProject_Key);
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

    const createHeaderCell = (key, name, title, sortFunction) => {
  return makeelement({
    "e": "th",
    "t": name,
    "c": `p-1 titles align-middle${sortable(key) ? " active" : ""}`,
    "a": title,
    "j": function() { sortFunction(key, this); }
  });
};

const addSortableInfo = (name, sort, reverse) => {
  let direction = sortable(sort) ? (!reverse) ? "&nbsp;↓" : "&nbsp;↑" : "";
  let b1 = sortable(sort) ? "<u>" : "";
  let b2 = sortable(sort) ? "</u>" : "";
  return `${b1}${name}${b2}${direction}`;
};

const makeheader = (name, type) => {
  const sortthisstring = "click here to sort by this field";
  const safename = makesafe(name);
  const trri = makeelement({"e": "tr", "i": `${type}${safename}`, "t": "", "c": `p-1<?= $headerposition ?>`});

  const isPPMode = ispp(mode);
  rowcolor = 1;

  Object.entries(rifields).forEach(([key, value]) => {
    let cellName = (typeof value === "object") ? value.name : value;
    cellName = addSortableInfo(cellName, sort, reverse);

    const title = sortable(key) ? sortthisstring : "Sorry, sorting by this column is not yet supported";
    trri.appendChild(createHeaderCell(key, cellName, title, sortcol));

    if (isPPMode && rifields[key].name === "ID") {
      let namefield = (format === "grid") ? "R/I Name" : type;
      let direction = addSortableInfo(namefield, "RI_Nm", reverse);
      trri.appendChild(createHeaderCell("RI_Nm", direction, sortthisstring, sortcol));

      if (sortable(key)) {
        direction = addSortableInfo("Type", "RIType_Cd", reverse);
        trri.appendChild(createHeaderCell("RIType_Cd", direction, sortthisstring, sortcol));
      }
    }
  });

  excelrows();
  return trri;
};

    const createrow = (ri, type) => {
      // Create a risk or issue row
      if (typeof ri == "undefined") 
        return false ;
      const safename = griddy() ? makesafe(ri["RI_Nm"]) : makesafe(ri.MLMProgram_Nm);
      const saferi = makesafe(ri.RI_Nm);
      const trri = makeelement({"e": "tr", "i": "row" + safename, "t": "", "c":"p-1 datarow"});
      const trid = "tr" + saferi + Math.random();
      if (!griddy())
        bgclass = (rowcolor % 2 == 0) ? " evenrow" : " oddrow";
      griddy() || document.getElementById("table" + safename).appendChild(makeelement({e: "tr", i: trid, c: bgclass}));
      const file = (ri.Global_Flg) ? "global/details.php" : (ri.RILevel_Cd == "Program") ? "details-prg.php" : "details.php"
      const arrow = (p4plist[ri.RiskAndIssue_Key + "-" + ri.MLMProgramRI_Key] != null ) 
          ? (p4plist[ri.RiskAndIssue_Key + "-" + ri.MLMProgramRI_Key].length != 0) 
          ? "▶" : "" : "";
      const url = (["Program", "Portfolio"].includes(ri.RILevel_Cd)) 
        ? `/risk-and-issues/${file}?au=false&status=${ri["RIActive_Flg"]}&popup=true&rikey=${ri["RiskAndIssue_Key"]}&fscl_year=${ri["Fiscal_Year"]}&program=${ri.MLMProgram_Nm}&proj_name=null&unframe=false`
        : `/risk-and-issues/${file}?au=false&status=${ri["RIActive_Flg"]}&popup=true&rikey=${ri["RiskAndIssue_Key"]}&fscl_year=${ri["Fiscal_Year"]}&proj_name=${ri["EPSProject_Nm"]}&uid=${ri["EPSProject_Id"]}&unframe=false`;
      const rowValues = [];
      text = `<a href='${url}' class='miframe cboxElement'>${ri["RiskAndIssue_Key"]}</a>`;
      c = griddy() ? "plainbox" : (arrow == "" || mode == "portfolio") ? "plainbox" : "namebox";
      const w = (mode == "portfolio") ? "" : "";
      const header = makeelement(etemp(saferi, ri.RI_Nm));
      if (griddy()) 
        type = makeelement(etemp(saferi, ri.RIType_Cd));
      const tridobj = document.getElementById(trid);
      if (!griddy() && arrow != "") {
          if (mode == "program") {  // Disable Portfolio associated programs, remove to re-enable for a future feature
            tridobj.onclick = (e) => {
              toggler(document.getElementById("projects" + saferi), e.target.children[0]);
            };
          }
      }
    processcells();
    if (griddy()) {
      return fieldprocessor(ri, url, trri, header, type);
    } else {
      fieldprocessor(ri, url, tridobj, header, false);
      if(arrow != "") {
        makeprojects(p4plist[ri.RiskAndIssue_Key + "-" + ri.MLMProgramRI_Key], ri.MLMProgram_Nm, "table" + safename, saferi);
      }
    }
  }  
  var modebutton = (target) => {
      let url = `<a href='/risk-and-issues/dashboard/?mode=${target}&page=${page}' style='color:#fff' onclick='return false';>`;
      let rest = (target == "portfolio") ? "RAID Log" : capitalize(target);
      return makeelement({"i": target + "mode", "t": url + rest + "</a>", "e": "div", "c": "btn btn-primary oldbutton ml-1","j": function() {
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

  // Exclude from sorting
  const fieldmap = {
    "subprogram": "",
    "programcount": ""
  }
  const apfields = {
    "actionplandate": "LastUpdate", 
    "age": "LastUpdate", 
    "ActionPlanStatus_Cd": "ActionPlanStatus_Cd",
  }

  const risort = (list, field) => {
    field = (field === "category") ? "Global_Flg" : (field == "LastUpdateBy_Nm") ? "LastUpdate_By" : field;
    let qs = list.sort((a, b) => {
      apfields.hasOwnProperty(field) && (a[field] = aplist[a.RiskAndIssue_Key][apfields[field]]);
      apfields.hasOwnProperty(field) && (b[field] = aplist[b.RiskAndIssue_Key][apfields[field]]);
      const aValue = a[field] === null ? "ZZZZ" 
        : (typeof a[field] === "boolean" ? (a[field] ? 1 : 0) 
        : (typeof a[field] != "undefined" && typeof a[field].date != "undefined") ? a[field].date 
        : a[field]);
      const bValue = b[field] === null ? "ZZZZ" 
        : (typeof b[field] === "boolean" ? (b[field] ? 1 : 0) 
        : (typeof b[field] != "undefined" && typeof b[field].date != "undefined") ? b[field].date 
        : b[field]);
      // console.log(aValue, (aValue < bValue) ? "<" : ">", bValue)

      return aValue === bValue ? 0 : (aValue < bValue ? -1 : 1);
    });
    return !reverse ? qs : qs.reverse();
  };

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
      setTimeout(() => {
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
          riseed = getwholeuniques(riseed.concat(extralist), "RiskAndIssue_Key");
          riseed = risort(riseed, sort);
          resultcounter(riseed.length);
          setTimeout(function() {
            populate(riseed);
          });
        } else {
          setTimeout(function() {
            populate(riseed);
          });
        }
      });
      makeheadline();
      setTimeout(colorboxschtuff, 2000);
      makemodebuttons(mode);
      setTimeout(fixcollapse, 1000);
    }
      
    init(mode);
    setInterval(colorboxschtuff, 2000);
   
  </script>
  <div id="lightbox" style="display:none"></div>
  <div id="config-lightbox-container"></div>
  <button id="settingsbutton" style="display:none" onclick="renderLightbox()">Open Lightbox</button>

  </body>
</html>