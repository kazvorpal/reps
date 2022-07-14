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
    <title>Project R&I Dashboard</title>
    <link rel="shortcut icon" href="favicon.ico"/>
    <?php 
    // print phpinfo();
    include ("../../includes/load.php");
    function fixutf8($target) {
      if (gettype($target) == "string")
        return (utf8_encode($target));
      else 
        return ($target);
    }  
    include ("../includes/data.php");
    ?>
    <script>

      const ridata = <?= $jsonout ?>;  
      const closeddata = <?= $closedout ?>;  
      const mangerlist = <?= $mangerout ?>;
      const driverlist = <?= $driverout ?>;
      const locationlist = <?= $locationout ?>;
      const p4plist = <?= $p4pout ?>;
      
      const projectfields = ["EPSProject_Nm", "EPS_Location_Cd", "EPSProject_Owner", "SubMLMProgram_Nm"];
      const projectfieldnames = ["Project Name", "Facility", "Owner", "Subprogram"];
      const finder = (target, objective) => (target.find(o => o.MLMProgram_Nm == objective));
      
      // Names of Data for program fields
      const fieldlist = ["Program", "Region", "Program Manager", "ID", "Impact Level", "Action Status", "Forecast Resol. Date", "Response Strat", "Open Duration"];
      const datafields = ["MLMProgram_Nm", "MLMRegion_Cd", "mangerlist", "RiskAndIssue_Key", "ImpactLevel_Nm", "ActionPlanStatus_Cd", "ForecastedResolution_Dt", "POC_Nm", "ResponseStrategy_Cd", "RIOpen_Hours"];
      const rifields = {"RiskAndIssue_Key": "ID", "RI_Nm": "R/I Name", "RIType_Cd": "Type", "EPSProject_Nm": "Project Name", "RIIncrement_Num": "Group ID", "EPSProgram_Nm": "Program", "EPSSubprogram_Nm": "Subprogram", "LastUpdateBy_Nm": "Owner", "Fiscal_Year": "FY", "EPSRegion_Cd": "Region", "EPSMarket_Cd": "Market", "EPSFacility_Cd": "Facility", "ImpactLevel_Nm": "Impact", "ActionPlanStatus_Cd": "Action Status", "ForecastedResolution_Dt": "Forecast Res Date", "ResponseStrategy_Nm": "Response Strategy", "RIOpen_Hours": "Open Duration"};
      const hiddenfields = ["AssociatedCR_Key", "MLMRegion_Key", "MLMProgramRI_Key", "TransferredPM_Flg", "Opportunity_Txt", "RiskProbability_Key"];
      const excelfields = {"Fiscal_Year": "Fiscal Year", "RIActive_Flg": "Status", "EPSProgram_Nm": "Program", "EPSSubprogram_Nm": "Sub-Program", "owner": "Owner", "RiskAndIssue_Key": "ID", "RIType_Cd": "Type", "EPSRegion_Abb": "Region", "regioncount": "Region Count", "category": "Category", "projectcount": "Proj Count", "RI_Nm": "Name", "EPSProject_Nm": "Project Name", "RIIncrement_Num": "Group ID", "ScopeDescriptor_Txt": "Descriptor", "RIDescription_Txt": "Description", "driver": "Driver", "ImpactArea_Nm": "Impact Area", "ImpactLevel_Nm": "Impact Level",	"RiskProbability_Nm": "Probability", "ResponseStrategy_Nm": "Response", "POC_Nm": "POC Name", "POC_Department": "POC Group", "ActionPlanStatus_Cd": "Action Plan Status", "ForecastedResolution_Dt": "Resolution Date", "RIOpen_Hours": "Days Open", "TransferredPM_Flg": "Transferred to PDM", "AssociatedCR_Key": "CR", "AssociatedCR_Key": "CR", "RaidLog_Flg": "Portfolio Notified", "RiskRealized_Flg": "Risk Realized", "RIClosed_Dt": "Date Closed", "Created_Ts": "Creation Date", "LastUpdate_By": "Last Update By", "Last_Update_Ts": "Last Update Date", "quartercreated": "Quarter Created", "quarterclosed": "Quarter Closed", "monthcreated": "Month Created", "monthclosed": "Month Closed", "duration": "Duration"};

      console.log(ridata);


    </script>
    <link rel="stylesheet" href="../../colorbox-master/example1/colorbox.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"> 
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
    <script src="../../colorbox-master/jquery.colorbox.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.0/FileSaver.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>
    <script>
      $(document).ready(function(){
          //Examples of how to assign the Colorbox event to elements
          $(".group1").colorbox({rel:'group1'});
          $(".group2").colorbox({rel:'group2', transition:"fade"});
          $(".group3").colorbox({rel:'group3', transition:"none", width:"75%", height:"75%"});
          $(".group4").colorbox({rel:'group4', slideshow:true});
          $(".ajax").colorbox();
          $(".youtube").colorbox({iframe:true, innerWidth:640, innerHeight:390});
          $(".vimeo").colorbox({iframe:true, innerWidth:500, innerHeight:409});
          $(".iframe").colorbox({iframe:true, width:"900", height:"600", scrolling:false});
          $(".dno").colorbox({iframe:true, width:"80%", height:"60%", scrolling:false});
          $(".mapframe").colorbox({iframe:true, width:"95%", height:"95%", scrolling:true});
          $(".miniframe").colorbox({iframe:true, width:"30%", height:"50%", scrolling:true});
          $(".ocdframe").colorbox({iframe:true, width:"75%", height:"90%", scrolling:true});
          $(".miframe").colorbox({iframe:true, width:"80%", height:"70%", scrolling:true});
          $(".inline").colorbox({inline:true, width:"50%"});
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
      });
      const MM_setTextOfTextfield = (objId,x,newText) => { //v9.0
        with (document){ if (getElementById){
          var obj = getElementById(objId);} if (obj) obj.value = newText;
        }
      }
      
      $(function () {
        $('[data-toggle="tooltip"]').tooltip()
      })
      
      </script>

<link rel="stylesheet" href="../css/ri.css">
<style type="text/css">
  </style>
  </head>
  <body onload="myFunction()" style="margin:0;">
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
  <?php include ("../../includes/menu.php");?>
  <section>
    <div class="row" align="center">
      <div style="width:98%">
        <div class="col-xs-12 text-center">
        <h1><?php if($fiscal_year !=0) {echo $fiscal_year;}?> Project R&I Dashboard</h1>
        <div style="display:inline-block;width:28%;text-align:right;font-size:larger" id="resultcount"></div><div style="display:inline-block;width:20%;text-align:right"><span class="btn btn-primary" onclick="exporter()">Export Results</span><p/></div>

      <?php 
        require '../includes/ri-selectors.php';
        ?>
            
                <div id="main" class="accordion" >
              <!-- <div class="header">
                Program Name (Risks, Issues)
              </div> -->
          </div>
        
        </div>
      </div>
    </div>
  </section>
  <div id="cboxOverlay" class="lightbox" styleD="" onclick="hider(this);"></div>
  <iframe id="details" onclick="this.style.display= 'none'" onblur="this.style.display= 'none'" style="position:fixed;top:10%;left:10%;width:80vw;height:70vh;background-color:#000;display:none;z-index:100000"></iframe>
  <section>

  </section>
  <script>
    var myVar;
    
    const myFunction = () => {
      myVar = setTimeout(showPage, 1000);
    }
    
    const showPage = () => {
      document.getElementById("loader").style.display = "none";
      document.getElementById("myDiv").style.display = "block";
    }
  </script>
  </body>
  <script>
    const populate = (rilist) => {
      console.log(rilist);
      resultcounter(rilist);
      const main = document.getElementById("main");
      main.innerHTML = '';

      initexcel();

      main.appendChild(makeelement({e: "table", i: "maintable", c: "table"}));
      const mt = document.getElementById("maintable");
      mt.appendChild(makeheader());
      for (loop of rilist) {
        // creates all the programs
        if(loop != null) {
          mt.appendChild(createrow(loop));
        }
      }
    }

    const makeheader = () => {
      
      // Make the header. Duh.
      
      const trri = makeelement({"e": "tr", "i": "headrow", "t": "", "c":"p-4"});
      let cells = [];
      Object.entries(rifields).forEach(([key, value]) => {
        trri.appendChild(makeelement({"e": "td", "t": value, "c": "p-4 titles"}));
        cells.push(value);
      })
      excelrows();
      return trri;
    }

    const hider = (target) => {
      document.getElementById('cboxOverlay').style.display = 'none';
      document.getElementById('details').style.display='none';
    }

    const createrow = (ri) => {
      // Create a row in the table
      const name = ri.RI_Nm;
      const safename = makesafe(ri["RI_Nm"]);
      const trri = makeelement({"e": "tr", "i": "row" + safename, "t": "", "c":"p-4 datarow"});
      const fieldswitch = {
          //    Specific fields that need extra calculation
          mangerlist: function() {
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
          RIActive_Flg: function() {
            return (ri.RIActive_Flg) ? "Open" : "Closed";
          },
          owner: function() {
            return ri.LastUpdateBy_Nm;
          },
          ForecastedResolution_Dt: function() {
            if (ri.ForecastedResolution_Dt != undefined)
              return formatDate(new Date(ri.ForecastedResolution_Dt.date));
            else 
              return "";
          },
          Created_Ts: function() {
            return  formatDate(new Date(ri.Created_Ts.date));
          },
          Last_Update_Ts: function() {
            return  formatDate(new Date(ri.Last_Update_Ts.date));
          },
          RiskRealized_Flg: function() {
            return  (ri.RiskRealized_Flg) ? "Y" : "N";
          },
          RIOpen_Hours: function() {
            return Math.floor(ri.RIOpen_Hours/24);
          },
          market: function() {
            const m = getlocationbykey(ri.EPSProject_Key);
            return (m != undefined) ? m.Market_Cd : "";
          },
          facility: function() {
            const f = getlocationbykey(ri.EPSProject_Key);
            return (f != undefined) ? f.Facility_Cd : "";
          },
          EPSRegion_Cd: function() {
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
          regioncount: function() {
            let counter = 0;
            for(r of ridata) {
              if (r.RI_Nm == ri.RI_Nm) {
                counter++;
              }
            }
            return counter;
          },
          monthcreated: function() {
            return new Date(ri.Created_Ts.date).toLocaleString('default', { month: 'long' });
          },
          monthclosed: function() {
            return new Date(ri.Last_Update_Ts.date).toLocaleString('default', { month: 'long' });
          },
          RIIncrement_Num: function() {
            return (ri.RIIncrement_Num) ? ri.RIIncrement_Num : "";
          },
          quartercreated: function() {
            const m = new Date(ri.Created_Ts.date).getMonth();
            return (m < 3) ? "Q1" : (m < 3) ? "Q2" : (m < 9) ? "Q3" : "Q4";
          },
          quarterclosed: function() {
            const m = new Date(ri.Last_Update_Ts.date).getMonth();
            return (!program.Status) ? "" : (m < 3) ? "Q1" : (m < 3) ? "Q2" : (m < 9) ? "Q3" : "Q4";
          },
          duration: function() {
            const d = Math.floor((new Date(ri.Last_Update_Ts.date) - new Date(ri.Created_Ts.date))/(1000 * 60 * 60 * 24));
            return  d + " days";
          },
          RI_Nm: function() {
              const url = "/risk-and-issues/details.php?au=false&status=1&popup=true&rikey=" + ri["RiskAndIssue_Key"]  + "&fscl_year=" + ri["Fiscal_Year"] + "&proj_name=" + ri["EPSProject_Nm"];
              return "<a href='" + url + "' onclickD='details(this);return(false)' class='miframe cboxElement'>" + ri["RI_Nm"] + "</a>";
          },
          driver: function() {
            return (driverlist[ri.RiskAndIssueLog_Key]) 
            ? (driverlist[ri.RiskAndIssueLog_Key][0]) 
            ? driverlist[ri.RiskAndIssueLog_Key][0].Driver_Nm : "" : "";
          },
          category: function() {
            let counter = 0;
            for(r of ridata) {
              if (r.EPSProject_Nm == ri.EPSProject_Nm) {
                counter++;
              }
            }
            return (counter > 1) ? "Associated" : "Single";
          },
          projectcount: function() {
            let counter = 0;
            for(r of ridata) {
              if (r.EPSProject_Nm == ri.EPSProject_Nm) {
                counter++;
              }
            }
            return counter;
          },
          subprogram: function() {
            if (ri.MLMProgramRI_Key != null) {
              p4plist[ri.RiskAndIssue_Key + "-" + ri.MLMProgram_Key]
            }
          }
      };
      const rowValues = [];
      for (field in excelfields) {
        (function(test) {
            const t = (typeof fieldswitch[test] != "function") ? ri[test] : fieldswitch[test]();
            rowValues.push((typeof t == "string" && t.indexOf("a href") == 1) ? t.substring((t.indexOf(">")+1), (t.indexOf("</a>"))) : t);
        })(field);
      }
      let newrow = document.worksheet.addRow(rowValues);

      for(field in rifields) {
          (function(test) {
            const texter = (typeof fieldswitch[test] != "function") ? ri[test] : fieldswitch[test]();
            trri.appendChild(makeelement({"e": "td", "t": texter, "c": "p-4 datacell" + textalign(texter) }));
          })(field);
      }
      return trri;
    }  

    const uniques = getwholeuniques(ridata, "RiskAndIssue_Key");

    const splitdate = (datestring) => {
      let newdate = datestring.split(" - ");
      return newdate;
    }  

    const betweendate = (dates, tween) => {
      spanner = splitdate(dates);
      console.log(spanner);
      let first = new Date(spanner[0]);
      let middle = new Date(tween);
      console.log(middle);
      let last = new Date(spanner[1]);
      r = ((middle >= first && middle <= last));
      console.log(r);
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
      return dates;
    } 



    document.getElementById("Go").onclick = function() {
      // filter form button
      populate(filtration())
      return false;
    }  
    populate(uniques);
  </script>
  </body>
</html>