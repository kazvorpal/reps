  // Takes a program key and name and returns the row object
const getprogrambyname = (target) =>  mlm = ridata.find(o => o.MLMProgram_Nm == target);
const getprogrambykey = (target, name) =>  mlm = ridata.find(o => o.RiskAndIssue_Key == target && o.MLMProgram_Nm == name);
const getlocationbykey = (key) =>  mlm = locationlist.find(o => o.EPSProject_Key == key);
const mode = (window.location.pathname.indexOf("project")>=0) ? "project" : "program";

  // Sanitize a string
  const makesafe = (target) => target.replace(/\s/g,'');
  
  const makeelement = (o) => {

    // o is an (o)bject with these optional properties:
    // o.e is the (e)lement, like "td" or "tr"
    // o.c is the (i)d
    // o.c is the (c)lasses, separated by spaces like usual
    // o.t is the innerHTML (t)ext
    // o.s is the col(s)pan

    const t = document.createElement(o.e);
    t.id = (typeof o.i == "undefined") ? "" : o.i;
    t.name = (typeof o.n == "undefined") ? "" : o.n + "[]";
    t.className = (typeof o.c == "undefined") ? "" : o.c;
    t.innerHTML = (typeof o.t == "undefined") ? "" : o.t;
    t.colSpan = (typeof o.s == "undefined") ? "" : o.s;
    t.width = (typeof o.w == "undefined") ? "" : o.w + "%";
    t.multiple = (typeof o.m == "undefined") ? "" : o.m;
    t.value = (typeof o.v == "undefined") ? "" : o.v;
    return t;
  }

  const resultcounter = (results) => {
    const s = (results.length == 1) ? "" : "s";
    document.getElementById("resultcount").innerHTML = `${results.length} Result${s} Found`
  }

  const padTo2Digits = (num) => num.toString().padStart(2, '0');
  
  const textalign = (field) => (parseInt(field)==field || (field.indexOf("details.html") && mode == "program")) ? " text-center" : " text-left";

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
      const d = padder(new Date(dateobject.date).getDay()+1, "0", 2);
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
      console.log(o);
    //   console.log(o.RIActive_Flg);
    //   console.log(o.Facility_Cd);
    //   console.log($('#facility').val().includes(o.Facility_Cd));
    //   console.log(($("#pStatus").val() != null) ? $("#pStatus").val().includes(o.RIActive_Flg.toString()): "bah");
    return (
        (document.getElementById("fiscal_year").value == '' || $('#fiscal_year').val().some(s => s == o.Fiscal_Year)) &&
        (document.getElementById("risk_issue").value == '' || $('#risk_issue').val().includes(o.RIType_Cd)) &&
        (document.getElementById("impact_level").value == '' || ($('#impact_level').val() + " Impact").includes(o.ImpactLevel_Nm)) &&
        ((document.getElementById("owner").value == '' || $('#owner').val().includes(o.LastUpdateBy_Nm))) &&
        ((document.getElementById("pStatus") == null && o.RIActive_Flg == '1') || (document.getElementById("pStatus").value == '' && o.RIActive_Flg == '1') || ($("#pStatus").val() != null && $("#pStatus").val().includes(o.RIActive_Flg.toString()))) &&
        (document.getElementById("program") == null || document.getElementById("program").value == '' || $('#program').val().includes(o.MLMProgram_Nm) || $('#program').val().includes(o.EPSProgram_Nm)) &&
        (mode == "project" || document.getElementById("region").value == '' || $('#region').val().includes(o.MLMRegion_Cd)) &&
        (mode == "program" || (document.getElementById("region").value == '' || $('#region').val().includes(o.EPSRegion_Cd))) &&
        ((mode == "program" || document.getElementById("market").value == '' || ($('#market').val().includes(o.Market_Cd) || $('#market').val().includes(o.EPSMarket_Cd)))) &&
        ((mode == "program" || document.getElementById("facility").value == '' || ($('#facility').val().includes(o.Facility_Cd) || $('#facility').val().includes(o.EPSFacility_Cd)))) &&
        (document.getElementById("dateranger").value == '' || (o.ForecastedResolution_Dt != null && betweendate($('#dateranger').val(), o.ForecastedResolution_Dt.date)))
    );
}


  const filtration = () => {
    let filtered = ridata.filter(filterfunction);
    console.log(filtered)
    results = (mode == "program") ? removenullproperty(getwholeuniques(filtered, "MLMProgram_Nm"), "MLMProgram_Nm") : getwholeuniques(filtered, "RiskAndIssue_Key");
    return results;
  }  
  const searchproperty = (list, field, value) => {

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
            return self.findIndex(v => v[field] === value[field]) === index;
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
        const Mode = mode.charAt(0).toUpperCase() + mode.slice(1);
        document.worksheet = document.workbook.addWorksheet(Mode + ' Report',  {properties:{tabColor:{argb:'3355bb'}, headerFooter: Mode + " Report Spreadsheet", firstFooter: "RePS"}});


        let cols = [];
        for (field in excelfields) {
            (hiddenfields.includes(field))
            cols.push({
                header: excelfields[field],
                key: field,
                width: 16,
                hidden: hiddenfields.includes(field)
            })
        }
        document.worksheet.columns = cols;
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