  // Takes a program key and name and returns the row object
const getprogrambyname = (target) =>  mlm = rifiltered.find(o => o.MLMProgram_Nm == target);
const getprogrambykey = (target, name) =>  mlm = rifiltered.find(o => o && o.RiskAndIssue_Key == target && o.MLMProgram_Nm == name);
const getlocationbykey = (key) =>  mlm = locationlist.find(o => o.EPSProject_key == key);

  // Sanitize a string
  const makesafe = (target) => {
    return  (target in ["null", null]) ? "whatever" : target.replace(/\s/g,'');
  }
  
  const makeelement = (o) => {

    // o is an (o)bject with these optional properties:
    // o.c is the (i)d
    // o.e is the (e)lement, like "td" or "tr"
    // o.n is the (n)ame of the element
    // o.c is the (c)lasses, separated by spaces like usual
    // o.t is the innerHTML (t)ext or such
    // o.s is the col(s)pan
    // o.w is the (w)idth
    // o.m is whether it's (m)ulti or the like
    // o.m is any necessary (v)alue, like input.value
    // o.j is onclick event code in (j)avascript ((c)lick or at least (e)vent was taken)
    // o.d is (d)efault, or selected, or checked

    const t = document.createElement(o.e);
    t.id = (typeof o.i == "undefined") ? "" : o.i;
    t.name = (typeof o.n == "undefined") ? "" : o.n + "[]";
    t.className = (typeof o.c == "undefined") ? "" : o.c;
    t.innerHTML = (typeof o.t == "undefined") ? "" : o.t;
    t.colSpan = (typeof o.s == "undefined") ? "" : o.s;
    if (typeof o.w != "undefined" && o.w != "") {
      t.width =  o.w + "%";
    }
    t.multiple = (typeof o.m == "undefined") ? "" : o.m;
    t.value = (typeof o.v == "undefined") ? "" : o.v;
    t.selected = (typeof o.d == "undefined") ? "" : o.d;
    if (typeof o.j != "undefined") {
      t.onclick = o.j;
    }
    return t;
  }

  const resultcounter = (results) => {
    let r = (typeof results == "object") ? results.length : results;
    const s = (r == 1) ? "" : "s";
    document.getElementById("resultcount").innerHTML = `${r} Result${s} Found`
  }

  const padTo2Digits = (num) => num.toString().padStart(2, '0');
  
  const textalign = (field) => (field == null || parseInt(field)==field || (field.indexOf("details.html") && mode == "program")) ? " text-center" : " text-left";

  const formatDate = (date) => {
    return [
      padTo2Digits(date.getMonth() + 1),
      padTo2Digits(date.getDate()),
      date.getFullYear(),
    ].join('-');
  }

  const makestringdate = (dateobject) => {
    if (dateobject != null) {
      const m = padder(new Date(dateobject.date).getMonth()+1, "0", 2);
      const d = padder(new Date(dateobject.date).getDate()+1, "0", 2);
      const y = (new Date(dateobject.date).getFullYear()).toString().substring(2);
      r = (dateobject == null) ? "" : m + "-" + d + "-" + y;
      return r;
    } else 
      return "";
  }

  const padder = (target, character, size) => {
    tl = target.toString();
    return character.repeat(size-tl.length) + target.toString();
  }

//   const mapper = (mode == "project") ? "EPSProject_Key" : "MLMProgram_Nm";
  const mapper = "RiskAndIssue_Key";
  const key = (mode == "project") ? "EPSProject_Key" : "EPSProject_Key";

  const filterfunction = (o) => {
    return (
        (document.getElementById("fiscal_year").value == '' || $('#fiscal_year').val().some(s => s == o.Fiscal_Year)) &&
        (document.getElementById("risk_issue").value == '' || $('#risk_issue').val().includes(o.RIType_Cd)) &&
        ((["project", "portfolio"].includes(mode)) || document.getElementById("category").value == '' || ($('#category').val().includes((typeof p4plist[o.RiskAndIssue_Key + "-" + o.MLMProgramRI_Key] != "undefined" && typeof p4plist[o.RiskAndIssue_Key + "-" + o.MLMProgramRI_Key][0] != "undefined") ? '1' : '0'))) &&
        (document.getElementById("impact_level").value == '' || ($('#impact_level').val() + " Impact").includes(o.ImpactLevel_Nm)) &&
        ((document.getElementById("owner").value == '' || $('#owner').val().includes(o.LastUpdateBy_Nm))) &&
        ((document.getElementById("pStatus").value == null && o.RIActive_Flg == '1') || (document.getElementById("pStatus").value == '' && o.RIActive_Flg == '1') || ($("#pStatus").val() != null && $("#pStatus").val().includes(o.RIActive_Flg.toString()))) &&
        (document.getElementById("program") == null || document.getElementById("program").value == '' || $('#program').val().includes(o.MLMProgram_Nm) || $('#program').val().includes(o.EPSProgram_Nm)) && 
        ((["portfolio"].includes(mode)) || document.getElementById("subprogram") == null || document.getElementById("subprogram").value == '' 
          || ((typeof p4plist[o.RiskAndIssue_Key + "-" + o.MLMProgramRI_Key] != "undefined" && typeof p4plist[o.RiskAndIssue_Key + "-" + o.MLMProgramRI_Key][0] != "undefined") && $('#subprogram').val().includes(p4plist[o.RiskAndIssue_Key + "-" + o.MLMProgramRI_Key][0].Subprogram_nm)) 
          || $('#subprogram').val().includes(o.EPSSubprogram_Nm)) &&
        (mode == "project" || mode == "portfolio" || document.getElementById("region").value == '' || $('#region').val().includes(o.MLMRegion_Cd)) &&
        (ispp(mode) || (document.getElementById("region").value == '' || $('#region').val().includes(o.EPSRegion_Cd))) &&
        ((ispp(mode) || document.getElementById("market").value == '' || ($('#market').val().includes(o.Market_Cd) || $('#market').val().includes(o.EPSMarket_Cd)))) &&
        ((ispp(mode) || document.getElementById("facility").value == '' || ($('#facility').val().includes(o.Facility_Cd) || $('#facility').val().includes(o.EPSFacility_Cd)))) &&
        (mode == "portfolio" || (document.getElementById("dateranger").value == '' || (o.ForecastedResolution_Dt != null && betweendate($('#dateranger').val(), o.ForecastedResolution_Dt.date))))
    );
  }


  const filtration = (data) => {
    let filtered = data.filter(filterfunction);
    results = (mode == "program") ? filtered 
      : (mode == "portfolio") ? filtered
      : getwholeuniques(filtered, "RiskAndIssue_Key");
    return results;
  }  

  const reload = () => {
      document.getElementById("formfilter").reset();
  }


    const exporter = () => {
        document.workbook.xlsx.writeBuffer().then((buf) => {
          saveAs(new Blob([buf]), 'ri-' + mode + "-dashboard-" + formatDate(new Date()) + '.xlsx');
        });
    }

    const getuniques = (list, field) => {
        return list.map(item => item[field]).filter((value, index, self) => self.indexOf(value) === index).sort();
    }
    const getwholeuniques = (list, field) => {
        return list.filter((value, index, self) => {
            return Object.values(self).findIndex(v => v[field] === value[field]) === index;
        })
    }
     
    const removenullproperty = (list, field) => {
        return list.filter(function(value) {
            return value[field] != null;
        })
    }

    const getuniqueobjects = (list, field) => {
        const objectlist = list.map(item => item[field]).filter((value, index, self) => self.indexOf(value) === index);
        let returnlist = [];
        for (item in objectlist) {
            if (objectlist[item].MLMProgram_Nm != null) {
                let hold = getribykey(objectlist[item])
                returnlist.push(hold);
            }
        }
        return(returnlist);
    }

    const initexcel = () => {
        document.workbook = new ExcelJS.Workbook();
        document.workbook.creator = "RePS Website";
        document.workbook.lastModifiedBy = "Kaz";
        document.workbook.created = new Date();
        const Mode = capitalize(mode);
        document.worksheet = document.workbook.addWorksheet(Mode + ' Report',  {properties:{tabColor:{argb:'3355bb'}, headerFooter: Mode + " Report Spreadsheet", firstFooter: "RePS"}});


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
    }
    document.cbrun = false;
    const colorboxschtuff = () => {
      $(".miframe").colorbox({
        iframe:true, 
        width:"80%", 
        height:"70%", 
        scrolling:true
      });
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
      if (!document.cbrun) {
        var originalClose = $.colorbox.close;
        $.colorbox.close = function(){
          if (confirm('You are about to close this window.  Incomplete Risks/Issues will not be saved.')) {
            originalClose();
          }
        };
      }
      document.cbrun = true;
    }
    const setlists = () => {
      projectfields = (mode == "program") ? ["EPSProject_Nm", "Subprogram_nm", "EPSProject_Owner", "MLMRegion_Cd", "Market_Cd", "EPS_Location_Cd"]
         : ["EPSProject_Nm", "EPS_Location_Cd", "EPSProject_Owner", "SubMLMProgram_Nm"];
      projectfieldnames = (mode == "program") ? [{name: "Project Name", width: "38"}, {name: "Subprogram", width: "5"}, {name: "Owner", width: "28"}, {name: "Region", width: "9"}, {name: "Market", width: "9"}, {name: "Facility", width: "9"}]
         : ["Project Name", "Facility", "Owner", "Subprogram"];
      rifields = (mode == "program") ? {"RiskAndIssue_Key": {name: "ID", width: "3"}, "category": {name: "category", width: "3"}, "Fiscal_Year": {name: "FY", width: "4"}, "MLMProgram_Nm": {name: "Program", width: "9"}, "subprogram": {name: "Subprogram", width: "9"}, "MLMRegion_Cd": {name: "Region", width: "6"}, "LastUpdateBy_Nm": {name: "Owner", width: "10"}, "ImpactLevel_Nm": {name: "Impact Level", width: "10"}, "ActionPlanStatus_Cd": {name: "Action Plan", width: "27"}, "ForecastedResolution_Dt": {name: "Forecast Res Date", width: "6"}, "ResponseStrategy_Cd": {name: "Response Strategy", width: "5"}, "RIOpen_Hours": {name: "Open Duration", width: "6"}, "RIActive_Flg": {name: "Open", width: "6"}} 
        : (mode == "portfolio") ? {"Fiscal_Year": "FY", "RIType_Cd": "Type", "RiskAndIssue_Key": "ID", "RI_Nm": "R/I Name", "MLMProgram_Nm": "Program", "LastUpdateBy_Nm": "Owner", "ImpactLevel_Nm": "Impact", "RIDescription_Txt": "Description", "ResponseStrategy_Nm": "Response Strategy", "ActionPlanStatus_Cd": "Action Plan", "RIActive_Flg": "Status", "ForecastedResolution_Dt": "Forecast Res Date", "RIOpen_Hours": "Open Duration"}
        : {"RiskAndIssue_Key": "ID", "RI_Nm": "R/I Name", "RIType_Cd": "Type", "EPSProject_Nm": "Project Name", "RIIncrement_Num": "Group ID", "EPSProgram_Nm": "Program", "EPSSubprogram_Nm": "Subprogram", "LastUpdateBy_Nm": "Owner", "Fiscal_Year": "FY", "EPSRegion_Cd": "Region", "EPSMarket_Cd": "Market", "EPSFacility_Cd": "Facility", "ImpactLevel_Nm": "Impact", "ActionPlanStatus_Cd": "Action Plan", "ForecastedResolution_Dt": "Forecast Res Date", "ResponseStrategy_Nm": "Response Strategy", "RIOpen_Hours": "Open Duration"};
      excelfields = (mode == "program") ? {"Fiscal_Year": "FY",	"RIActive_Flg": "Status", "MLMProgram_Nm": "Program", "subprogram": "Subprogram", "owner": "Owner", "RiskAndIssue_Key": "ID", "RIType_Cd": "Type", "MLMRegion_Cd": "Region", "regioncount": "Reg Count", "category": "Category", "projectcount": "Proj Count", "RI_Nm": "Name", "ScopeDescriptor_Txt": "Descriptor", "RIDescription_Txt": "Description", "driver": "Driver", "ImpactArea_Nm": "Impact Area", "ImpactLevel_Nm": "Impact Level",	"RiskProbability_Nm": "Probability", "ResponseStrategy_Nm": "Response", "POC_Nm": "POC Name", "POC_Department": "POC Group", "ActionPlanStatus_Cd": "Action Plan", "ForecastedResolution_Dt": "Resolution Date", "RIOpen_Hours": "Duration", "AssociatedCR_Key": "CR", "RaidLog_Flg": "Portfolio Notified", "RiskRealized_Flg": "Risk Realized", "RIClosed_Dt": "Date Closed", "Created_Ts": "Creation Date", "LastUpdate_By": "Last Update By", "Last_Update_Ts": "Last Update Date", "quartercreated": "Quarter Created", "quarterclosed": "Quarter Closed", "monthcreated": "Month Created", "monthclosed": "Month Closed"} :
      (mode == "portfolio") ? {"Fiscal_Year": "FY",	"RIActive_Flg": "Status", "MLMProgram_Nm": "Program", "subprogram": "Subprogram", "owner": "Owner", "RiskAndIssue_Key": "ID", "RIType_Cd": "Type", "category": "Category", "projectcount": "Proj Count", "RI_Nm": "Name", "ScopeDescriptor_Txt": "Descriptor", "RIDescription_Txt": "Description", "driver": "Driver", "ImpactArea_Nm": "Impact Area", "ImpactLevel_Nm": "Impact Level",	"RiskProbability_Nm": "Probability", "ResponseStrategy_Nm": "Response", "POC_Nm": "POC Name", "POC_Department": "POC Group", "ActionPlanStatus_Cd": "Action Plan", "ForecastedResolution_Dt": "Resolution Date", "RIOpen_Hours": "Duration", "AssociatedCR_Key": "CR", "RaidLog_Flg": "Portfolio Notified", "RiskRealized_Flg": "Risk Realized", "RIClosed_Dt": "Date Closed", "Created_Ts": "Creation Date", "LastUpdate_By": "Last Update By", "Last_Update_Ts": "Last Update Date", "quartercreated": "Quarter Created", "quarterclosed": "Quarter Closed", "monthcreated": "Month Created", "monthclosed": "Month Closed"}
      : {"Fiscal_Year": "Fiscal Year", "RIActive_Flg": "Status", "EPSProgram_Nm": "Program", "EPSSubprogram_Nm": "Sub-Program", "owner": "Owner", "RiskAndIssue_Key": "ID", "RIType_Cd": "Type", "EPSRegion_Abb": "Region", "regioncount": "Region Count", "category": "Category", "projectcount": "Proj Count", "RI_Nm": "Name", "EPSProject_Nm": "Project Name", "RIIncrement_Num": "Group ID", "ScopeDescriptor_Txt": "Descriptor", "RIDescription_Txt": "Description", "driver": "Driver", "ImpactArea_Nm": "Impact Area", "ImpactLevel_Nm": "Impact Level",	"RiskProbability_Nm": "Probability", "ResponseStrategy_Nm": "Response", "POC_Nm": "POC Name", "POC_Department": "POC Group", "ActionPlanStatus_Cd": "Action Plan", "ForecastedResolution_Dt": "Resolution Date", "RIOpen_Hours": "Duration", "TransferredPM_Flg": "Transferred to PDM", "AssociatedCR_Key": "CR", "AssociatedCR_Key": "CR", "RiskRealized_Flg": "Risk Realized", "RIClosed_Dt": "Date Closed", "Created_Ts": "Creation Date", "LastUpdate_By": "Last Update By", "Last_Update_Ts": "Last Update Date", "quartercreated": "Quarter Created", "quarterclosed": "Quarter Closed", "monthcreated": "Month Created", "monthclosed": "Month Closed"};
      centerfield = ["Fiscal_Year", "ID", "regioncount", "projectcount", "RIIncrement_Num"];
 }

//  const uniques = () => (mode == "program") 
//     ? removenullproperty(getwholeuniques(getwholeuniques(d1, "RiskAndIssue_Key"), "MLMProgram_Nm"), "MLMProgram_Nm") 
//     : (mode == "portfolio") ? removenullproperty(getwholeuniques(getwholeuniques(d1, "RiskAndIssue_Key"), "MLMProgram_Nm"), "MLMProgram_Nm") 
//     : getwholeuniques(d1, "RiskAndIssue_Key");

 const uniques = () => (mode == "program") 
    ? removenullproperty(getwholeuniques(getwholeuniques(d1, "RiskAndIssue_Key"), "MLMProgram_Nm"), "MLMProgram_Nm") 
    : (mode == "portfolio") ? removenullproperty(getwholeuniques(getwholeuniques(d1, "RiskAndIssue_Key"), "MLMProgram_Nm"), "MLMProgram_Nm") 
    : getwholeuniques(d1, "RiskAndIssue_Key");




//  const uniques = () => (mode == "program") ? removenullproperty(getwholeuniques(getwholeuniques(d1, "RiskAndIssue_Key"), "MLMProgram_Nm"), "MLMProgram_Nm")
                      //  : (mode == "portfolio") ? getwholeuniques(d1, "RiskAndIssue_Key") 
                      //  : getwholeuniques(d1, "RiskAndIssue_Key");



 const splitdate = (datestring) => {
   let newdate = datestring.split(" - ");
   return newdate;
 }  

 const betweendate = (dates, tween) => {
   let s = splitdate(dates);
   let m = new Date(tween)
   let first = new Date(s[0]);
   let middle = new Date(m.setDate(m.getDate()+1));
   let last = new Date(s[1]);
   r = ((middle >= first && middle <= last));
   return r;
 }  

 const makedate = (dateobject) => {
   return dateobject.getFullYear() + "-" + (dateobject.getMonth()+1) + "-" + dateobject.getDate();
 }

 const ranger = (daterange) => {
   // get start and end date from a date range set via Bootstrap date range picker
   const dates = {};
   dates.start = daterange.substring(0, daterange.indexOf(" - ")+1);
   dates.end = daterange.substring(daterange.indexOf(" - ")+4);
   return dates; } 


