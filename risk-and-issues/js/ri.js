

//   const mapper = (mode == "project") ? "EPSProject_Key" : "MLMProgram_Nm";
const mapper = "RiskAndIssue_Key";
// const key = (mode == "project") ? "EPSProject_Key" : "EPSProject_Key";

const fieldempty = (field) => (document.getElementById(field).value == '');
const isincluded = (filter, field) => {
  // console.log(filter)
  return ($(filter).val().includes(field));
}

const exporter = () => {
    document.workbook.xlsx.writeBuffer().then((buf) => {
      saveAs(new Blob([buf]), 'ri-' + ((mode == "portfolio") ? "raid-log" : mode) + "-dashboard-" + formatDate(new Date()) + "-" + formattime(new Date()) + '.xlsx');
    });
}

const getuniques = (list, field) => {
  // console.log("sort", sort)
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

function fixEncodingIssues(text) {
  return text
    .replace(/^\uFEFF|Ã¯ » ¿|Ã¯»¿/g, '')
    .replace(/Ã¯.*¿/g, '')
    .replace(/Ã¢â¬Â¢|Ã¢â¬â¢/g, '•')
    .replace(/Â|Ã/g, ' ')
    .replace(/Ã¢ââ¹|Ã¢â¬â/g, '—');
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

document.cbrun = false;
const colorboxschtuff = () => {
  $(".miframe").colorbox({
    iframe:true, 
    width:"80%", 
    height:"70%", 
    scrolling:true,
    fixed: true
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

// Pure function returns either the string without HTML tags in it, or the non-string intact as it was
const striptags = (html) => 
  typeof html == "string" 
  ? ((div) => {
    div.innerHTML = html;
    return div.textContent;
  })(document.createElement("div")) 
  : html;

  const fieldfilter = (ri, test, url) => {
    // console.log("fieldfilter", ri);
    const fieldswitch = {
      //    Specific fields that need extra calculation

      RiskAndIssue_Key: () => {
        return (ispp(mode)) ? `<span style='font-weight:900'>${text}</span>` : ri.RiskAndIssueLog_Key;
      },
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
  return fieldswitch[test];
  }












  // const fieldswitch = {
  //   //    Specific fields that need extra calculation
  //   //    Add any field to rifields that you want to be a column,
  //   //    in the format {fieldname: "Human Name"}
  //   //    If it exists in rifields, it will be populated automatically here.
  //   //    If, instead, you need to do some calculation to produce it,
  //   //    add its fieldname to this "switch" object, fieldswitch,
  //   //    with an anonymous function to handle the changes.
  //   RiskAndIssue_Key: () => {
  //       return `<span style='font-weight:900'>${text}</span>`;
  //   },
  //   mangerlist: () => {
  //     if (ri["MLMProgram_Key"] || mode("project")) {
  //       const manger = mangerlist[ri["Fiscal_Year"] + "-" + ri["MLMProgram_Key"]];
  //       let mangers = [ri["Fiscal_Year"]];
  //       for (man of manger) {
  //           mangers.push(man.User_Nm);
  //       }  
  //       return mangers.join().replace(",", ", ");
  //     } else
  //       return "";
  //   },
  //   global: () => {
  //       return  (ri.Global_Flg) ? "Y" : "N";
  //     },
  //   groupcount: () => {
  //     let gc = 0;
  //     ridata.forEach(o => {
  //       gc += (ri.RIIncrement_Num == o.RIIncrement_Num && ri.RIActive_Flg == o.RIActive_Flg) ? 1 : 0;
  //     })
  //     return gc;
  //   },
  //   category: () => {
  //     return  (ri.Global_Flg) ? "Global" : "Program";
  //   },
  //   EPSSubprogram_Nm: () => {
  //       return getlocationbykey(ri.EPSProject_Key)
  //   },
  //   ForecastedResolution_Dt: () => {
  //       return (ri.ForecastedResolution_Dt == null) ? "Unknown" : makestringdate(ri.ForecastedResolution_Dt);
  //   },
  //   RIActive_Flg: () => {
  //       return (ri.RIActive_Flg) ? "Open" : "Closed";
  //   },
  //   Created_Ts: () => {
  //       return makestringdate(ri.Created_Ts);
  //   },
  //   monthcreated: () => {
  //       return new Date(ri.Created_Ts.date).toLocaleString('default', { month: 'long' });
  //   },
  //   monthclosed: () => {
  //       return (ri.RIClosed_Dt != null) ? new Date(ri.RIClosed_Dt.date).toLocaleString('default', { month: 'long' }) : "";
  //   },
  //   quartercreated: () => {
  //       const m = new Date(ri.Created_Ts.date).getMonth();
  //       return  (m < 3) ? "Q1" : (m < 6) ? "Q2" : (m < 9) ? "Q3" : "Q4";
  //   },
  //   quarterclosed: () => {
  //       const m = (ri.RIClosed_Dt != null) ? new Date(ri.RIClosed_Dt.date).getMonth():"";
  //       return  (ri.RIClosed_Dt == null) ? "" : (m < 3) ? "Q1" : (m < 6) ? "Q2" : (m < 9) ? "Q3" : "Q4";
  //   },
  //   duration: () => {
  //       const enddate = (ri.RIActive_Flg == 1 || ri.Created_Ts.date < ri.RIClosed_Dt) ? ri.Last_Update_Ts.date : ri.RIClosed_Dt;
  //       const d = Math.floor((new Date(enddate) - new Date(ri.Created_Ts.date))/(1000 * 60 * 60 * 24));
  //       return  d + " days";
  //   },
  //   Last_Update_Ts: () => {
  //       return  makestringdate(ri.Last_Update_Ts);
  //   },
  //   RIClosed_Dt: () => {
  //       return  (ri.RIClosed_Dt != null) ? formatDate(new Date(ri.RIClosed_Dt.date)) : "";
  //   },
  //   AssociatedCR_Key: () => {
  //       return  (ri.AssociatedCR_Key) ? "Y" : "N";
  //   },
  //   MLMRegion_Cd: () => {
  //       let list = ""
  //       let counter = 0;
  //       for(r of ridata) {
  //         if (r) {
  //           if (r.RI_Nm == ri.RI_Nm && r.MLMProgram_Nm == ri.MLMProgram_Nm) {
  //             counter++;
  //             list += (!isempty(regions[r.MLMRegion_Cd])) ? regions[r.MLMRegion_Cd] + ", " : (!isempty(regions[r.MLMRegion_Cd])) ? r.MLMRegion_Cd : "";
  //           }
  //         }
  //       }
  //       return (list.slice(0, -2));
  //       // return "";
  //   },
  //   regioncount: () => {
  //       let counter = 0;
  //       for(r of ridata) {
  //           if (!isempty(r) && r.RI_Nm == ri.RI_Nm && r.MLMProgram_Nm == ri.MLMProgram_Nm) {
  //           counter++;
  //           }
  //       }
  //       return counter;
  //   },
  //   RaidLog_Flg: () => {
  //       return  (ri.RaidLog_Flg) ? "Y" : "N";
  //   },
  //   RiskRealized_Flg: () => {
  //       return  (ri.RiskRealized_Flg) ? "Y" : "N";
  //   },
  //   RIOpen_Hours: () => {
  //       return Math.floor(ri.RIOpen_Hours/24) + " days";
  //   },
  //   driver: () => {
  //       return (driverlist[ri.RiskAndIssueLog_Key]) 
  //       ? (driverlist[ri.RiskAndIssueLog_Key]) 
  //       ? driverlist[ri.RiskAndIssueLog_Key].Driver_Nm : "" : "";
  //   },
  //   projectcount: () => {
  //       let projects = p4plist[ri.RiskAndIssue_Key + "-" + ri.MLMProgramRI_Key];
  //       return (projects != undefined && projects.length>0) ? projects.length : "";
  //   }, 
  //   subprogram: () => {
  //     let list = "";
  //     let prog = sublist[ri.RiskAndIssue_Key];
  //     if (prog != undefined) {
  //       for(r of prog) {
  //         let comma = (list.length > 0) ? ", " : "";
  //         list += comma + r.SubProgram_Nm ;
  //       } 
  //     }
  //     // console.log(list)
  //     let ret = (list != "") ? list : ""
  //     // let ret = (list != "") ? list.slice(0, -2) : ""
  //     // console.log("ret", ret);
  //     return ret;
  //   }, 
  //   MLMProgram_Nm: () => {
  //     if (ri.Global_Flg == 1) {
  //       let programs = "";
  //       let portprog = (ri.RIActive_Flg) ? portfolioprograms : portfolioprogramsclosed;
  //       portprog.forEach(o => {
  //         let comma = (programs != "") ? ", " : "";
  //         if (o.RiskAndIssue_Key == ri.RiskAndIssue_Key) {
  //           programs = (programs.indexOf(o.Program_Nm) == -1) ? programs + comma + o.Program_Nm : programs;
  //         }
  //       })
  //       if (programs == "" ) {
  //         programs = ri.MLMProgram_Nm;
  //       }
  //       return (programs);
  //     } else {
  //       return ri.MLMProgram_Nm;
  //     }
  //   },
  //   programcount: () => {
  //       let programs = "";
  //       let pc = 0;
  //       portfolioprograms.forEach((o) => {
  //         let comma = (programs != "") ? ", " : ""
  //         if (o.RiskAndIssue_Key == ri.RiskAndIssue_Key 
  //           && programs.indexOf(o.MLMProgram_Nm) == -1 && programs.indexOf(o.Program_Nm) == -1) {
  //             // console.log(o);
  //           programs = (programs.indexOf(o.Program_Nm) == -1) ? programs + comma + o.Program_Nm : programs;
  //           // programs = programs + comma + o.Program_Nm;
  //           pc++;
  //         } 
  //       });
  //       if (ri.RiskAndIssue_Key == 2181) 
  //         console.log("pc", programs);
  //       if (pc == 0) {
  //         pc = 1;
  //       }
  //       return (pc);
  //   },
  //   age: () => {
  //     let r = (aplist[ri.RiskAndIssue_Key]) ? new Date(aplist[ri.RiskAndIssue_Key].LastUpdate.date) : "";
  //     let d = (r == "") ? "" : (Math.floor((new Date() - r)/(1000 * 60 * 60 * 24))+1) ;
  //     let s = (d == 1) ? " day" : (d == "") ? "" : " days";
  //     return  `${d}${s}`;
  //   },
  //   RIDescription_Txt: () => {
  //     // return ri.RIDescription_Txt
  //     return trimmer(ri.RIDescription_Txt, ri.RiskAndIssue_Key, "desc");
  //   },
  //   ActionPlanStatus_Cd: () => {
  //     // return ri.ActionPlanStatus_Cd;
  //     return trimmer(ri.ActionPlanStatus_Cd, ri.RiskAndIssue_Key, "plan");
  //   },
  //   actionplandate: () => {
  //     let r = (aplist[ri.RiskAndIssue_Key]) ? formatDate(new Date(aplist[ri.RiskAndIssue_Key].LastUpdate.date)) : "";
  //     return(r);
  //   }
  // };
