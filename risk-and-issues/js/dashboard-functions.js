const resultcounter = (results) => {
    let r = (typeof results == "object") ? results.length : results;
    (firstload) && r--;
    const s = (r == 1) ? "" : "s";
    document.getElementById("resultcount").innerHTML = `${r} Result${s} Found`;
  }
  
  const ispp = (target) => ["program", "portfolio"].some(value => {return value== target});
      
  // return a new array with all programs for a (portfolio) risk/issue key
  const ffpp = (target) => portfolioprograms.filter(o => o.RiskAndIssue_Key == target)
  
  const syncportfolio = (target) => {
    // Add MLMProgram_Key to portfolios, so the same code can run with it as with other programs
    // Yes, a kluge
    for (key in target) {
      if(!target[key].MLMProgram_Nm) {
        let ps = "";
        for (lokey in portfolioprograms) {
          if (portfolioprograms[lokey].RiskAndIssue_Key == target[key].RiskAndIssue_Key) {
            ps += (ps == "") ? portfolioprograms[lokey].RiskAndIssue_Key : ", " + (portfolioprograms[lokey].RiskAndIssue_Key);
          }
          target[key].MLMProgram_Nm = ps;
        }
        let pp = ffpp(target[key].RiskAndIssue_Key);
        if (typeof pp[0] != 'undefined') {
          target[key].MLMProgram_Nm = pp[0].Program_Nm;
          target[key].MLMProgram_Key = pp[0].Program_Key;
        } else if(!target[key].MLMProgram_Nm) 
          delete target[key];
      }
    }
    return target;
  }
  
  // returns a list of risks or issues for a given program, taking program name and type (risk, issue)
  const listri = (target, type) => 
    // list of all data with the right program name and risk or issue
    rifiltered.filter(o => o.RIType_Cd == type && o.MLMProgram_Nm == target)
      // run it through the filters
      .filter(filterfunction) 
      .map(item => item.RiskAndIssue_Key).filter((value, index, self) => self.indexOf(value) === index);


// Toggles visibility of projects when a given program is clicked
const toggler = (target, o) => 
    (target != null) 
      ? (target.className.indexOf("show") != -1) 
        ? target.className = target.className.replace("show", "") 
        : target.className += "show" 
        : "";

  // Takes a program key and name and returns the row object
  const getprogrambyname = (target) =>  rifiltered.find(o => o.MLMProgram_Nm == target);
  const getprogrambykey = (target, name) =>  rifiltered.find(o => o && o.RiskAndIssue_Key == target && (o.MLMProgram_Nm == name || (name == "Portfolio" && o.MLMProgram_Nm == "Portfolio")));
  const getprogrambykeyonly = (target, name) =>  rifiltered.find(o => o && o.RiskAndIssue_Key == target);
  const getlocationbykey = (key) =>  locationlist.find(o => o.EPSProject_key == key) ?? "";
  
  const textalign = (field) => (rifields[field].align) || "text-center";
  // const textalign = (field) => (field == null || parseInt(field)==field || (field.indexOf("details.html") && mode == "program")) ? " text-center" : " text-left";


  const initexcel = () => {
    document.workbook = new ExcelJS.Workbook();
    document.workbook.creator = "R&I Dashboard Export";
    document.workbook.lastModifiedBy = "Kaz";
    document.workbook.created = new Date();
    const Mode = (mode == "portfolio") ? "Raid Log" : capitalize(mode);
    document.worksheet = document.workbook.addWorksheet(Mode + ' Report',  {properties:{tabColor:{argb:'3355bb'}, headerFooter: Mode + " Report Spreadsheet", firstFooter: "RePS"}});
    document.changelog = (mode == "project") ? document.workbook.addWorksheet("Change Log Request", {properties:{tabColor:{argb:'3355bb'}, headerFooter: "Change Log Request", firstFooter: "RePS"}}) : ""; 

    let cols = [];
    for (field in excelfields) {
        cols.push({
            header: excelfields[field],
            key: field,
            width: 16,
            hidden: hiddenfields.includes(field),
        })
    }
    document.worksheet.columns = cols;
    let logcols = [];
    for (field in changelog) {
        logcols.push({
            header: changelog[field],
            key: field,
            width: 16,
            hidden: hiddenfields.includes(field),
        })
    }
    document.changelog.columns = logcols;
}
const processcells = () => {
  document.worksheet.eachRow(function(row, rowNumber) {
    if (row._number != 1) {
      row.eachCell(function(cell, colNumber) {
          // let alignment = (cell.value == (cell.value*1)) ? "center" : "left";
          cell.alignment = {"horizontal": (cell.value == (cell.value*1)) ? "center" : "left"}
      })
    }
  })
  if (mode == "project") {
    document.changelog.eachRow(function(row, rowNumber) {
      if (row._number != 1) {
        row.eachCell(function(cell, colNumber) {
          // let alignment = (cell.value == (cell.value*1)) ? "center" : "left";
          cell.alignment = {"horizontal": (cell.value == (cell.value*1)) ? "center" : "left"}
        })
      }
    });
  }
}


const excelrows = () => {
  document.worksheet.getRow(1).eachCell( function(cell, colNumber){
    if(cell.value){
      document.worksheet.getRow(1).height = 42;
      document.worksheet.getRow(1).getCell(colNumber).font = { name: 'helvetica', family: 4, underline: 'none', bold: true, color: {argb: 'FFFFFFFF'}};
      document.worksheet.getRow(1).getCell(colNumber).alignment = {vertical: 'middle', horizontal: 'center'};
      document.worksheet.getRow(1).getCell(colNumber).fill = {
        type: 'pattern',
        pattern:'solid',
        bgColor:{argb:'FF5588FF'},
        fgColor:{argb: "FF3377AA"},
        width: "256",
        height: "256"
      };
    }
  });
  const borderstyle = "medium";
  document.worksheet.columns.forEach(column => {
    column.border = {
      top: { style: borderstyle },
      left: { style: borderstyle },
      bottom: { style: borderstyle },
      right: { style: borderstyle }
    };
  });
  if (mode == "project") {
    document.changelog.getRow(1).eachCell( function(cell, colNumber){
          if(cell.value){
            document.changelog.getRow(1).height = 42;
            document.changelog.getRow(1).getCell(colNumber).font = { name: 'helvetica', family: 4, underline: 'none', bold: false, color: {argb: 'ff000000'}};
            document.changelog.getRow(1).getCell(colNumber).alignment = {vertical: 'middle', horizontal: 'center'};
            document.changelog.getRow(1).getCell(colNumber).fill = {
                            type: 'pattern',
                            pattern:'solid',
                            bgColor: {argb: "FF3377AA"},
                            fgColor: {argb: "FFddeeFF"},
                            width: "256",
                            height: "256"
                          };
          }
        });
        document.changelog.columns.forEach(column => {
          column.border = {
            top: { style: borderstyle },
            left: { style: borderstyle },
            bottom: { style: borderstyle },
            right: { style: borderstyle }
          };
      });
      document.changelog.insertRow(1, "TEST");
      document.changelog.mergeCells('A1:M1');
      document.changelog.getCell('A1').value = "Requestor Section";
      document.changelog.getCell('A1').font = { name: 'helvetica', family: 4, size: 18, underline: 'none', bold: false, color: {argb: 'ff000000'}};;
      document.changelog.getCell('A1').alignment = {horizontal: 'center', vertical: 'middle'};
      document.changelog.getCell('A1').fill = {
        type: 'pattern',
        pattern:'solid',
        bgColor: {argb: "FF3377AA"},
        fgColor: {argb: "FF22dd66"},
        width: "256",
        height: "256"
      };
  }
}

const trimmer = (target, key, kind) => {
  let cleaner = document.createElement("div");
  cleaner.innerHTML = fixEncodingIssues(target);
  return (cleaner.textContent.length > textlength) 
  ? `<span id="clean${kind}${key}">` + cleaner.textContent.substr(0, textlength) + `<a href="#" class="more" id="more${kind}${key}" onclick="document.getElementById('desc${kind}${key}').style.display='block';document.getElementById('clean${kind}${key}').style.display='none';return false">[more]</a></span><span style="display:none;" onclick="this.style.display='none';document.getElementById('clean${kind}${key}').style.display='inline'" id="desc${kind}${key}">${target}<a href="#" onclick="return false">[less]</a></div>`
  : cleaner.innerHTML;
}

var params = new URLSearchParams(window.location.search);
var mode = params.get("mode") ?? "project";
var page = params.get("page");
var page = (page > 1) ? page : 1;
var format = params.get("format");
var prepage = params.get("pagesize");
var pagesize = (prepage > 0) ? prepage : 8192;
var textlength = params.get("textlength") ?? 8192;
alt = (mode == "project") ? "program" : "project";
document.title = capitalize(mode) + " R&I Dashboard";
var sort = "RiskAndIssue_Key";
var reverse = false;
const uniques = () => (mode == "program") 
  ? removenullproperty(getwholeuniques(getwholeuniques(d1, "RiskAndIssue_Key"), "MLMProgram_Nm"), "MLMProgram_Nm") 
  : (mode == "portfolio") ? removenullproperty(getwholeuniques(getwholeuniques(d1, "RiskAndIssue_Key"), "MLMProgram_Nm"), "MLMProgram_Nm") 
  : getwholeuniques(d1, "RiskAndIssue_Key");

  const modes = ["project", "program", "portfolio"];
var togglegrid = () => {
  format = (format == "grid") ? "accordion" : "grid";
  let url = new URL(window.location);
  url.searchParams.set("format", format);
  window.history.pushState({}, '', url);
  // populate(ridata);
  init(mode);
}

const tagger = (t) => {
  console.log(searchtag = t);
  var delay = 100;
  setTimeout(() => {
    (ispp(mode) && format != "grid") ? toggleall(false) : "";
  }, 1000);
  processfilters();
}

const fieldfilter = (ri, test, url) => {
  const groupcount = () => ridata.reduce((gc, o) => gc + ((ri.RIIncrement_Num == o.RIIncrement_Num && ri.RIActive_Flg == o.RIActive_Flg) ? 1 : 0), 0);
  const location = getlocationbykey(ri.EPSProject_Key);
  const getobjectdate = (o) => o?.date ? formatDate(new Date(o.date)) : "";


  // This will end up returning either a function value via fieldswitch, 
  // or else just the built-in value of the field in question, 
  // if there isn't a specific function for it.
  const fieldswitch = {
    //    Specific fields that need extra calculation

    RiskAndIssue_Key: () => (ispp(mode)) ? `<span style='font-weight:900'>${text}</span>` : ri.RiskAndIssueLog_Key,
    RIActive_Flg: () => ri.RIActive_Flg ? "Open" : "Closed",
    Created_Ts: () => getobjectdate(ri.Created_Ts),
    Last_Update_Ts: () => getobjectdate(ri.Last_Update_Ts),
    RIClosed_Dt: () => getobjectdate(ri.RIClosed_Dt),
    RiskRealized_Flg: () => ri.RiskRealized_Flg ? "Y" : "N",
    RaidLog_Flg: () => (ri.RaidLog_Flg) ? "Y" : "N",
    actionplandate: () => aplist[ri.RiskAndIssue_Key] ? getobjectdate(aplist[ri.RiskAndIssue_Key].LastUpdate) : "",
    changelogdate: () => loglist[ri.RiskAndIssue_Key] ? getobjectdate(loglist[ri.RiskAndIssue_Key].LastUpdate) : "",
    RIDescription_Txt: () => trimmer(ri.RIDescription_Txt, ri.RiskAndIssue_Key, "desc"),
    monthcreated: () => new Date(ri.Created_Ts.date).toLocaleString('default', { month: 'long' }),
    monthclosed: () => ri.RIClosed_Dt?.date ? new Date(ri.Last_Update_Ts.date).toLocaleString('default', { month: 'long' }) : "",
    RIIncrement_Num: () => (ri.RIIncrement_Num) ? ri.RIIncrement_Num : "",
    programmanager: () => (loglist[ri.RiskAndIssue_Key]) ? ri.LastUpdateBy_Nm  : "", 
    PRJI_Estimated_Act_Ts: () => ri.PRJI_Estimated_Act_Ts ? getDateFromObject(ri.PRJI_Estimated_Act_Ts) : "N/A",
    PRJI_Estimated_Mig_Ts: () => ri.PRJI_Estimated_Mig_Ts ? getDateFromObject(ri.PRJI_Estimated_Mig_Ts) : "N/A",
    RI_Nm: () => `<a href='${url}' onclickD='details(this);return(false)' class='miframe cboxElement'>${ri["RI_Nm"]}</a>`,
    groupcount: () => groupcount(),
    LastUpdateBy_Nm: () => (ri.Global_Flg && ri.RI_Owner) ? ri.RI_Owner : "*" + ri.LastUpdateBy_Nm,
    grouptype: () => (groupcount() > 1) ? "Multi" : "Single",
    ForecastedResolution_Dt: () => ri.ForecastedResolution_Dt != undefined ? formatDate(new Date(ri.ForecastedResolution_Dt.date)) : "Unknown",
    Global_Tag: () => (!ri.Global_Tag || typeof ri.Global_Tag == "string") ? "" 
        : ri.Global_Tag.map(target => `<a href="#" onclick="tagger('${target}');">${target}</a>`).join(", "),
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
    RiskAndIssue_Key: () => {
      let oc = (ri.RIActive_Flg == 1) ? "<br/>Open" : "<br/>Close";
      const color = ri.RIActive_Flg == 1 ? '#080' : '#800';
      return (ispp(mode)) 
        ? `<a href='${url}' class='miframe cboxElement'>${ri["RiskAndIssue_Key"]}</a><span title='Status: ${oc}' style='color:${color};font-size:xx-small'>${oc}</span>` 
        : `${ri["RiskAndIssue_Key"]} <span title='Status: ${oc}' style='color:${color};font-size:xx-small'>${oc}</span>`;
    },
    RIOpen_Hours: () => {
      let d = Math.floor(ri.RIOpen_Hours/24);
      let s = (d == 1) ? " day" : (d === "") ? "" : " days";
      return  `${d}${s}`;
    },
    market: () => location.Market_Cd,
    facility: () => location.Facility_Cd,
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
      return (ispp(mode)) ? (ri.Global_Flg) ? "Global" : "Program" :(counter > 1) ? "Associated" : "Single";
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
    ActionPlanStatus_Cd: () => {
      let plan = ri.ActionPlanStatus_Cd;
      let key = ri.RiskAndIssue_Key;
      // return plan;
      return trimmer(plan, key, "plan");
    }
  };
  return fieldswitch[test];
}

const makeexcel = (ri) => {
  let rowvalues = [];
  for (field in excelfields) {
    (function(test) {
      let t = striptags((typeof fieldfilter(ri, test) != "function") ? ri[test] : fieldfilter(ri, test)());
      t = (test == "RiskAndIssue_Key") ? t.replace(/Open|Closed/g, '') : t;
      rowvalues.push(t);
    })(field);
  }
  return rowvalues;
};

const etemp = (n, t) => {
  return {
    e: "th", 
    i: "th" + n, 
    t: "<div style='overflow:hidden'>" + t + "</div>", 
    c: "p-1 " + c,
  }
}

const fieldprocessor = (ri, url, trri, header, type) => {
  // console.log(type)
  for(field in rifields) {
    (function(test) {
      let texter = (typeof fieldfilter(ri, test, url) != "function") ? ri[test] : fieldfilter(ri, test, url)();
      let bgcolor = (("ForecastedResolution_Dt" == test && (Date.parse(texter)+86400000) < Date.parse(new Date()))
                      || (checkage(29, test, texter))) ? " hilite" : 
                      (checkage(14, test, texter)) ? " blulite" : "";
      let wrapping = (richtextfields.includes(test)) ? " overflow-everything" : "";
      trri.appendChild(makeelement({"e": "td", "t": texter, "c": "p-1 datacell align-middle" + wrapping +  " " + textalign(field) + bgcolor }));
    })(field);
    if (rifields[field].name == "ID") {
      // console.log("addons")
      trri.appendChild(header);
      (type) && trri.appendChild(type);
    };
  }
  return trri;
}

const griddy = () => (mode == "project" || format == "grid" );