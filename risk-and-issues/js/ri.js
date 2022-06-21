  // Takes a program key and name and returns the row object
const getprogrambyname = (target) =>  mlm = ridata.find(o => o.Program_Nm == target);
const getprogrambykey = (target, name) =>  mlm = ridata.find(o => o.RiskAndIssue_Key == target && o.Program_Nm == name);
const getlocationbykey = (key) =>  mlm = locationlist.find(o => o.EPSProject_key == key);
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

  const makestringdate = (dateobject) => {
    if (dateobject != null) {
      const m = padder(new Date(dateobject.date).getMonth()+1, "0", 2);
      const d = padder(new Date(dateobject.date).getDay()+1, "0", 2);
      const y = (new Date(dateobject.date).getFullYear()).toString().substring(2);
      return (dateobject == null) ? "" : m + "/" + d + "/" + y;
    } else 
      return "";
  }

  const padder = (target, character, size) => {
    tl = target.toString();
    return character.repeat(size-tl.length) + target.toString();
  }



//   const mapper = (mode == "project") ? "Project_Key" : "Program_Nm";
  const mapper = (mode == "project") ? "Project_Key" : "Program_Nm";
  const key = (mode == "project") ? "Project_Key" : "PROJECT_Key";
  const filtration = () => {
    let filtered = ridata.filter(function(o) {
        console.log(o);
        console.log(o.ForecastedResolution_Dt);
        // console.log(getlocationbykey(o[key]));
        // console.log($('#owner').val());
        // console.log(($('#pStatus').val()).includes(toString(o.Active_Flg)));
        return (
          (document.getElementById("fiscal_year").value == '' || $('#fiscal_year').val().some(s => s == o.Fiscal_Year)) &&
          (document.getElementById("risk_issue").value == '' || $('#risk_issue').val().includes(o.RIType_Cd)) &&
          (document.getElementById("impact_level").value == '' || ($('#impact_level').val() + " Impact").includes(o.ImpactLevel_Nm)) &&
          (o.LastUpdateBy_Nm != null ||document.getElementById("owner").value == '' || ($('#owner').val()).includes(o.LastUpdateBy_Nm)) &&
          (document.getElementById("pStatus") == null || document.getElementById("pStatus").value == '' || document.getElementById("pStatus").value == 1) &&
          (document.getElementById("program") == null || document.getElementById("program").value == '' || $('#program').val().includes(o.Program_Nm) || key == "Project_Key") &&
          (document.getElementById("region").value == '' || $('#region').val().includes(o.Region_Cd)) &&
          (mode == "program" || getlocationbykey(o[key]) != undefined && (document.getElementById("market").value == '' || $('#market').val().includes(getlocationbykey(o[key]).Market_Cd))) &&
          (mode == "program" || getlocationbykey(o[key]) != undefined && (document.getElementById("facility").value == '' || $('#facility').val().includes(getlocationbykey(o[key]).Facility_Cd))) &&
          (document.getElementById("dateranger").value == '' || (o.ForecastedResolution_Dt != null && betweendate($('#dateranger').val(), o.ForecastedResolution_Dt.date)))
        );
    });
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
      console.log(document.f = filtered);
    const results = (mode == "program") ? filtered.map(item => item[mapper]).filter((value, index, self) => self.indexOf(value) === index) : filtered.map(item => item.RiskAndIssue_Key);
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
        (document.getElementById("program").value == '' || $('#program').val().includes(o.Program_Nm)) &&
        (document.getElementById("region").value == '' || $('#region').val().includes(o.Region_Cd)) &&
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
    return filtered.map(item => item.Program_Nm).filter((value, index, self) => self.indexOf(value) === index)
  }  


  const filtrationProject = () => {
    // filter the programs list using the form
    let filtered = ridata.filter(function(o) {
        return (
          (document.getElementById("fiscal_year").value == '' || $('#fiscal_year').val().some(s => s == o.Fiscal_Year)) &&
          (document.getElementById("risk_issue").value == '' || $('#risk_issue').val().includes(o.RIType_Cd)) &&
          (document.getElementById("impact_level").value == '' || ($('#impact_level').val() + " Impact").includes(o.ImpactLevel_Nm)) &&
          (document.getElementById("program").value == '' || $('#program').val().includes(o.Program_Nm)) &&
          (document.getElementById("region").value == '' || $('#region').val().includes(o.Region_Cd)) &&
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
    return filtered.map(item => item.Project_Key).filter((value, index, self) => self.indexOf(value) === index)
  }  



    const getuniques = (list, field) => {
        return list.map(item => item[field]).filter((value, index, self) => self.indexOf(value) === index);
    }

    const getuniqueobjects = (list, field) => {
        return list.map(item => item[field]).filter((value, index, self) => self.indexOf(value) === index);
    }





    const initexcel = () => {
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