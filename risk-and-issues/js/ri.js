  // Takes a program key and name and returns the row object
const getprogrambyname = (target) =>  mlm = ridata.find(o => o.MLMProgram_Nm == target);
const getprogrambykey = (target, name) =>  mlm = ridata.find(o => o.RiskAndIssue_Key == target && o.MLMProgram_Nm == name);
const getlocationbykey = (key) =>  mlm = locationlist.find(o => o.EPSEPSProject_Key == key);
const mode = (window.location.pathname.indexOf("project")>=0) ? "project" : "program";

  // Sanitize a string
  const makesafe = (target) => target.replace(/\s/g,'');
  
  const empty = (o) => {
    o.children[0].innerHTML = "";
  }

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

  function padTo2Digits(num) {
    return num.toString().padStart(2, '0');
  }
  

  function formatDate(date) {
    return [
      padTo2Digits(date.getMonth() + 1),
      padTo2Digits(date.getDate()),
      date.getFullYear(),
    ].join('/');
  }
  

  const makestringdate = (dateobject) => {
    if (dateobject != null) {
      const m = padder(new Date(dateobject.date).getMonth()+1, "0", 2);
      const d = padder(new Date(dateobject.date).getDay()+1, "0", 2);
      const y = (new Date(dateobject.date).getFullYear()).toString().substring(2);
      r = (dateobject == null) ? "" : m + "/" + d + "/" + y;
    //   console.log(r);
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

  const filtration = () => {
    //   console.log("filtering");
    let filtered = ridata.filter(function(o) {
        console.log($("#pStatus").val());
        console.log(o.RIActive_Flg.toString());
        console.log($("#pStatus").val().includes(o.RIActive_Flg.toString()));
        // console.log(mode);
        // console.log(o.ForecastedResolution_Dt);
        // console.log(getlocationbykey(o[key]));
        // console.log($('#owner').val());
        // console.log(($('#pStatus').val()).includes(toString(o.RIActive_Flg)));
        return (
          (document.getElementById("fiscal_year").value == '' || $('#fiscal_year').val().some(s => s == o.Fiscal_Year)) &&
          (document.getElementById("risk_issue").value == '' || $('#risk_issue').val().includes(o.RIType_Cd)) &&
          (document.getElementById("impact_level").value == '' || ($('#impact_level').val() + " Impact").includes(o.ImpactLevel_Nm)) &&
          ((document.getElementById("owner").value == '' || $('#owner').val().includes(o.LastUpdateBy_Nm))) &&
          (document.getElementById("pStatus") == null || document.getElementById("pStatus").value == '' || $("#pStatus").val().includes(o.RIActive_Flg)) &&
          (document.getElementById("program") == null || document.getElementById("program").value == '' || $('#program').val().includes(o.MLMProgram_Nm) || key == "EPSProject_Key") &&
          (mode == "project" || document.getElementById("region").value == '' || $('#region').val().includes(o.MLMRegion_Cd)) &&
          (mode == "program" || (getlocationbykey(o[key]) != undefined) && (document.getElementById("region").value == '' || $('#region').val().includes(getlocationbykey(o[key]).MLMRegion_Cd))) &&
          (mode == "program" || getlocationbykey(o[key]) != undefined && (document.getElementById("market").value == '' || $('#market').val().includes(getlocationbykey(o[key]).Market_Cd))) &&
          (mode == "program" || getlocationbykey(o[key]) != undefined && (document.getElementById("facility").value == '' || $('#facility').val().includes(getlocationbykey(o[key]).Facility_Cd))) &&
          (document.getElementById("dateranger").value == '' || (o.ForecastedResolution_Dt != null && betweendate($('#dateranger').val(), o.ForecastedResolution_Dt.date)))
        );
    });That 
    // if (document.getElementById("owner").value != '') {
    //     const secondpass = [];
    //     for (item of filtered) {
    //       if (item.Fiscal_Year + "-" + item.MLMProgram_Key in mangerlist && mangerlist[item.Fiscal_Year + "-" + item.MLMProgram_Key].length > 0) {
    //         let n = document.getElementById("owner").value;
    //         let name = flipname(n);
    //         if (mangerlist[item.Fiscal_Year + "-" + item.MLMProgram_Key][0].User_Nm.indexOf(name) != -1) {
    //           secondpass.push(item);
    //         }
    //       }
    //     }
    //     filtered = secondpass;
    // }
    //   console.log(document.f = filtered);
    // const results = (mode == "program") ? filtered.map(item => item[mapper]).filter((value, index, self) => self.indexOf(value) === index) : filtered.map(item => item.RiskAndIssue_Key);
    results = (mode == "program") ? removenullproperty(getwholeuniques(filtered, "MLMProgram_Nm"), "MLMProgram_Nm") : getwholeuniques(filtered, "RiskAndIssue_Key");
    // console.log(results);
    return results;
  }  
  const searchproperty = (list, field, value) => {

  }

  const filtrationProgram = () => {
    // filter the programs list using the form
    let filtered = ridata.filter(function(o) {
      return (
        (document.getElementById("fiscal_year").value == '' || $('#fiscal_year').val().some(s => s == o.Fiscal_Year)) &&
        (document.getElementById("risk_issue").value == '' || $('#risk_issue').val().includes(o.RIType_Cd)) &&
        (document.getElementById("impact_level").value == '' || ($('#impact_level').val() + " Impact").includes(o.ImpactLevel_Nm)) &&
        (document.getElementById("pStatus").value == '' || ($('#pStatus').val()).includes(o.pStatus)) &&
        (document.getElementById("program").value == '' || $('#program').val().includes(o.MLMProgram_Nm)) &&
        (document.getElementById("region").value == '' || $('#region').val().includes(o.MLMRegion_Cd)) &&
        (document.getElementById("dateranger").value == '' || betweendate($('#dateranger').val(), o.ForecastedResolution_Dt.date))
      );
    });
    if (document.getElementById("owner").value != '') {
      const secondpass = [];
      for (item of filtered) {
        if (item.Fiscal_Year + "-" + item.MLMProgram_Key in mangerlist && mangerlist[item.Fiscal_Year + "-" + item.MLMProgram_Key].length > 0) {
          let n = document.getElementById("owner").value;
          let name = flipname(n);
          if (mangerlist[item.Fiscal_Year + "-" + item.MLMProgram_Key][0].User_Nm.indexOf(name) != -1) {
            secondpass.push(item);
          }
        }
      }
      filtered = secondpass;
    }
    return filtered.map(item => item.MLMProgram_Nm).filter((value, index, self) => self.indexOf(value) === index)
  }  


  const filtrationProject = () => {
    // filter the programs list using the form
    let filtered = ridata.filter(function(o) {
        return (
          (document.getElementById("fiscal_year").value == '' || $('#fiscal_year').val().some(s => s == o.Fiscal_Year)) &&
          (document.getElementById("risk_issue").value == '' || $('#risk_issue').val().includes(o.RIType_Cd)) &&
          (document.getElementById("impact_level").value == '' || ($('#impact_level').val() + " Impact").includes(o.ImpactLevel_Nm)) &&
          (document.getElementById("program").value == '' || $('#program').val().includes(o.MLMProgram_Nm)) &&
          (document.getElementById("region").value == '' || $('#region').val().includes(o.MLMRegion_Cd)) &&
          (document.getElementById("dateranger").value == '' || betweendate($('#dateranger').val(), o.ForecastedResolution_Dt.date))
        );
    });
    if (document.getElementById("owner").value != '') {
      const secondpass = [];
      for (item of filtered) {
        if (item.Fiscal_Year + "-" + item.MLMProgram_Key in mangerlist && mangerlist[item.Fiscal_Year + "-" + item.MLMProgram_Key].length > 0) {
          let n = document.getElementById("owner").value;
          let name = flipname(n);
          if (mangerlist[item.Fiscal_Year + "-" + item.MLMProgram_Key][0].User_Nm.indexOf(name) != -1) {
            secondpass.push(item);
          }
        }
      }
      filtered = secondpass;
    }
    return filtered.map(item => item.EPSProject_Key).filter((value, index, self) => self.indexOf(value) === index)
  }  

    const reload = () => {
        document.getElementById("formfilter").reset();
    }


    const exporter = () => {
        document.workbook.xlsx.writeBuffer().then((buf) => {
          saveAs(new Blob([buf]), 'ri-' + mode + "-dashboard-" + makedate(new Date()) + '.xlsx');
        });
    }

    const getuniques = (list, field) => {
        return list.map(item => item[field]).filter((value, index, self) => self.indexOf(value) === index);
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

    // document.worksheet.getRow(1).eachCell( function(cell, colNumber){
    //     if(cell.value){
    //       document.worksheet.getRow(1).height = 42;
    //       cell.font = { name: 'helvetica', family: 4, underline: 'none', bold: true, color: {argb: 'FFFFFFFF'}};
    //       cell.alignment = {vertical: 'middle', horizontal: 'center'};
    //       cell.fill = {
    //         type: 'pattern',
    //         pattern:'solid',
    //         bgColor:{argb:'FF5588FF'},
    //         fgColor:{argb: "FF3377AA"},
    //         width: "256",
    //         height: "256"
    //       };
    //     }
    //   });
    //   const borderstyle = "medium";
    //   document.worksheet.columns.forEach(column => {
    //     column.border = {
    //       top: { style: borderstyle },
    //       left: { style: borderstyle },
    //       bottom: { style: borderstyle },
    //       right: { style: borderstyle }
    //     };
    //   });



