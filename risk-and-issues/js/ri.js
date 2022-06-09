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



  const key = (mode == "project") ? "Project_Key" : "Program_Nm";
  const filtration = () => {
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
    return filtered.map(item => item[key]).filter((value, index, self) => self.indexOf(value) === index)
  }  


//   const filtration = () => {
//     let filtered = ridata.filter(function(o) {
//         return (
//           (document.getElementById("fiscal_year").value == '' || $('#fiscal_year').val().some(s => s == o.Fiscal_Year)) &&
//           (document.getElementById("risk_issue").value == '' || $('#risk_issue').val().includes(o.RIType_Cd)) &&
//           (document.getElementById("impact_level").value == '' || ($('#impact_level').val() + " Impact").includes(o.ImpactLevel_Nm)) &&
//           (document.getElementById("program").value == '' || $('#program').val().includes(o.Program_Nm)) &&
//           (document.getElementById("region").value == '' || $('#region').val().includes(o.Region_Cd)) &&
//           (document.getElementById("dateranger").value == '' || betweendate($('#dateranger').val(), o.ForecastedResolution_Dt.date))
//         );
//     });
//     if (document.getElementById("owner").value != '') {
//       const secondpass = [];
//       for (item of filtered) {
//         if (item.Fiscal_Year + "-" + item.MLMProgram_Key in mangerlist && mangerlist[item.Fiscal_Year + "-" + item.MLMProgram_Key].length > 0) {
//           let n = document.getElementById("owner").value;
//           let name = flipname(n);
//           if (mangerlist[item.Fiscal_Year + "-" + item.MLMProgram_Key][0].User_Nm.indexOf(name) != -1) {
//             secondpass.push(item);
//           }
//         }
//       }
//       filtered = secondpass;
//     }
//     return filtered.map(item => item.Project_Key).filter((value, index, self) => self.indexOf(value) === index)
//   }  
