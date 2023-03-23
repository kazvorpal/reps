const resultcounter = (results) => {
    let r = (typeof results == "object") ? results.length : results;
    // console.trace("r", results);
    // console.log("r")
    // console.log(results)
    // console.table(ridata);
    const s = (r == 1) ? "" : "s";
    document.getElementById("resultcount").innerHTML = `${r} Result${s} Found`;
  }
  
  const ispp = (target) => ["program", "portfolio"].some(value => {return value== target});
      
  const ffpp = (target) => {
    // return all programs for a (portfolio) risk/issue key
    return portfolioprograms.filter(o => {
          return o.RiskAndIssue_Key == target;
        })
  }
  
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
  
  function listri(target, type) {
    // returns a list of risks or issues for a given program, taking program name and type (risk, issue)
    // pre = (target == "Portfolio") 
    //   ? rifiltered.filter(o => o.RIType_Cd == type && o.RILevel_Cd == "Portfolio")
    //   : rifiltered.filter(o => o.RIType_Cd == type && o.MLMProgram_Nm == target && o.RILevel_Cd != "Portfolio"); // list of all data with the right program name and risk or issue
    pre = rifiltered.filter(o => o.RIType_Cd == type && o.MLMProgram_Nm == target); // list of all data with the right program name and risk or issue
    // pre = rifiltered;
    // console.log(target);
    // console.log(pre);
    post = pre.filter(filterfunction); // run it through the filters
    // console.log("post");
    // console.log(post);
    uni = post.map(item => item.RiskAndIssue_Key).filter((value, index, self) => self.indexOf(value) === index);
    return uni;
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

  // Takes a program key and name and returns the row object
  const getprogrambyname = (target) =>  rifiltered.find(o => o.MLMProgram_Nm == target);
  const getprogrambykey = (target, name) =>  rifiltered.find(o => o && o.RiskAndIssue_Key == target && (o.MLMProgram_Nm == name || (name == "Portfolio" && o.MLMProgram_Nm == "Portfolio")));
  const getprogrambykeyonly = (target, name) =>  rifiltered.find(o => o && o.RiskAndIssue_Key == target);
  const getlocationbykey = (key) =>  locationlist.find(o => o.EPSProject_key == key);
  
  const textalign = (field) => (field == null || parseInt(field)==field || (field.indexOf("details.html") && mode == "program")) ? " text-center" : " text-left";


  const initexcel = () => {
    document.workbook = new ExcelJS.Workbook();
    document.workbook.creator = "RePS Website";
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

const trimmer = (target, key) => {
  return (target.length > textlength) 
  ? target.substr(0, textlength) + `<a href="#" class="more" id="more${key}" onclick="document.getElementById('desc${key}').style.display='block';this.style.display='none';return false">[more]</a><span style="display:none;" onclick="this.style.display='none';document.getElementById('more${key}').style.display='inline'" id="desc${key}">${target.substr(21)}<a href="#" onclick="return false">[less]</a></div>`
  : target;
}

var params = new URLSearchParams(window.location.search);
var mode = (params.get("mode") == null) ? "project" : params.get("mode");
var page = params.get("page");
var page = (page > 1) ? page : 1;
var format = params.get("format");
var pagesize = params.get("pagesize");
var pagesize = (pagesize > 0) ? pagesize : 20;
var textlength = params.get("textlength");
var textlength = (textlength == null) ? 100 : textlength;
console.log(textlength);
// mode = (window.location.href.indexOf("program")>=0) ? "program" : 
// (window.location.href.indexOf("portfolio")>=0) ? "portfolio" : "project";
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