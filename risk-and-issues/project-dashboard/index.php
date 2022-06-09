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
    <title>Project R&I Aggregate View</title>
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
    $sqlstr = "select * from RI_MGT.fn_GetListOfAllRiskAndIssue(1) where riLevel_cd = 'project'";
    print '<!--' . $sqlstr . "<br/>-->";
    ini_set('mssql.charset', 'UTF-8');
    $riquery = sqlsrv_query($data_conn, $sqlstr);
    if($riquery === false) {
      if(($error = sqlsrv_errors()) != null) {
        foreach($errors as $error) {
          echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
          echo "code: ".$error[ 'code']."<br />";
          echo "message: ".$error[ 'message']."<br />";
        }
      }
    } else {
      $rows = array();
      $count = 1;
      while($row = sqlsrv_fetch_array($riquery, SQLSRV_FETCH_ASSOC)) {
        $rows[] = array_map("fixutf8", $row);
      }
      
      $sqlstr = "select * from RI_MGT.fn_GetListOfLocationsForEPSProject(1)";
      // print '<!--' . $sqlstr . "<br/> -->";
      $locationquery = sqlsrv_query($data_conn, $sqlstr);
      if($locationquery === false) {
        if(($error = sqlsrv_errors()) != null) {
          foreach($error as $errors) {
            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            echo "code: ".$error[ 'code']."<br />";
            echo "message: ".$error[ 'message']."<br />";
          }
        }
      } else {
        $locationrows = array();
        $count = 1;
        while($locationrow = sqlsrv_fetch_array($locationquery, SQLSRV_FETCH_ASSOC)) {
          $locationrows[] = array_map("fixutf8", $locationrow);
          // print_r($row);
          // print($row["RiskAndIssueLog_Key"]);
          // print("<br/>");
        }
      }


      $p4plist = array();
      foreach ($rows as $row)  {
        if($row["ProgramRI_Key"] != '') {
          $sqlstr = "select * from RI_Mgt.fn_GetListOfAssociatedProjectsForProgramRIKey(". $row["RiskAndIssue_Key"] ." ,". $row["ProgramRI_Key"] .")";
          ini_set('mssql.charset', 'UTF-8');
          $p4pquery = sqlsrv_query($data_conn, $sqlstr);
          if($p4pquery === false) {
            if(($error = sqlsrv_errors()) != null) {
              // print_r($error);
              foreach($errors as $error) {
                echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
                echo "code: ".$error[ 'code']."<br />";
                echo "message: ".$error[ 'message']."<br />";
              }
            }
          } else {
            $count = 1;
            $p4prows = array();
            $checker = 0;
            while($p4prow = sqlsrv_fetch_array($p4pquery, SQLSRV_FETCH_ASSOC)) {
              $p4prows[] = array_map("fixutf8", $p4prow);
              $checker = 1;
            }
          }
          $p4plist[$row["RiskAndIssue_Key"]."-".$row["ProgramRI_Key"]] = $p4prows;
        }
      }
      
      $mangerlist = array();
      foreach ($rows as $row)  {
        if($row["ProgramRI_Key"] != '') {
          $sqlstr = "select * from RI_MGT.fn_GetListOfOwnersInfoForProgram(". $row["Fiscal_Year"] ." ,'". $row["Program_Nm"] ."')";
          ini_set('mssql.charset', 'UTF-8');
          $mangerquery = sqlsrv_query($data_conn, $sqlstr);
          if($mangerquery === false) {
            if(($error = sqlsrv_errors()) != null) {
              foreach($errors as $error) {
                echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
                echo "code: ".$error[ 'code']."<br />";
                echo "message: ".$error[ 'message']."<br />";
              }
            }
          } else {
            $count = 1;
            $mangerrows = array();
              while($mangerrow = sqlsrv_fetch_array($mangerquery, SQLSRV_FETCH_ASSOC)) {
                $mangerrows[] = array_map("fixutf8", $mangerrow);
              }
            }
            $mangerlist[$row["Fiscal_Year"]."-".$row["MLMProgram_Key"]] = $mangerrows;
          }
        }
        $p4pout = json_encode($p4plist);
        $mangerout = json_encode($mangerlist);
        $locationout = json_encode($locationrows);
        $jsonout = json_encode($rows);
      }

    ?>
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
          $(".miframe").colorbox({iframe:true, width:"1500", height:"650", scrolling:false});
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
  <div id="lightbox" style="position:fixed;left:0;top:0;width:100%;height:100%;background-color:rgba(0, 0, 0, .3);display:none;cursor:pointer" onclick="hider();"></div>
  <iframe id="details" onclick="this.style.display= 'none'" onblur="this.style.display= 'none'" style="position:fixed;top:10%;left:10%;width:80vw;height:70vh;background-color:#000;display:none;"></iframe>
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
  <script src="../js/ri.js"></script>
  <script>
    
    const ridata = <?= $jsonout ?>;  
    const mangerlist = <?= $mangerout ?>;
    const locationlist = <?= $locationout ?>;
    const p4plist = <?= $p4pout ?>;
    
    const projectfields = ["EPSProject_Nm", "EPS_Location_Cd", "EPSProject_Owner", "Subprogram_nm"];
    const projectfieldnames = ["Project Name", "Facility", "Owner", "Subprogram"];
    const finder = (target, objective) => (target.find(o => o.Program_Nm == objective));
    
    // Names of Data for program fields
    const fieldlist = ["Program", "Region", "Program Manager", "ID", "Impact Level", "Action Status", "Forecast Resol. Date", "Response Strat", "Open Duration"];
    const datafields = ["Program_Nm", "Region_Cd", "mangerlist", "RiskAndIssue_Key", "ImpactLevel_Nm", "ActionPlanStatus_Cd", "ForecastedResolution_Dt", "POC_Nm", "ResponseStrategy_Cd", "RIOpen_Hours"];
    const rifields = {"RiskAndIssue_Key": "ID", "RI_Nm": "R/I Name", "RIType_Cd": "Type", "Proj_Nm": "Project Name", "LastUpdateBy_Nm": "Owner", "Fiscal_Year": "FY", "Region_Cd": "Region", "market": "Market", "facility": "Facility", "ImpactLevel_Nm": "Impact", "ActionPlanStatus_Cd": "Action Status", "ForecastedResolution_Dt": "Forecast Res Date", "ResponseStrategy_Nm": "Response Strategy", "RIOpen_Hours": "Open Duration"};
    const hiddenfields = ["AssociatedCR_Key", "Region_Key", "ProgramRI_Key", "TransferredPM_Flg", "Opportunity_Txt", "RiskProbability_Key"];
    const excelfields = {"Fiscal_Year": "FY",	"Active_Flg": "Status", "RiskAndIssue_Key": "ID", "RIType_Cd": "Type", "Region_Cd": "Region", "RI_Nm": "Name", "Proj_Nm": "Project Name", "ScopeDescriptor_Txt": "Descriptor", "RIDescription_Txt": "Description", "ImpactArea_Nm": "Impact Area", "ImpactLevel_Nm": "Impact Level",	"RiskProbability_Nm": "Probability", "ResponseStrategy_Nm": "Response", "POC_Nm": "POC Name", "ActionPlanStatus_Cd": "Action Plan Status", "ForecastedResolution_Dt": "Resolution Date", "RIOpen_Hours": "Days Open", "AssociatedCR_Key": "CR", "RaidLog_Flg": "Portfolio Notified", "RiskRealized_Flg": "Risk Realized", "RIClosed_Dt": "Date Closed", "Created_Ts": "Creation Date", "LastUpdate_By": "Last Update By", "Last_Update_Ts": "Last Update Date"};
    console.log(ridata);

    const populate = (rilist) => {
      console.log(ridata);
      document.getElementById("resultcount").innerHTML = rilist.length + " Results Found"
      const main = document.getElementById("main");
      main.innerHTML = '';
      document.workbook = new ExcelJS.Workbook();
      document.workbook.creator = "RePS Website";
      document.workbook.lastModifiedBy = "Kaz";
      document.workbook.created = new Date();
      document.worksheet = document.workbook.addWorksheet('Project Report',  {properties:{tabColor:{argb:'3355bb'}, headerFooter: "Project Report Spreadsheet", firstFooter: "RePS"}});

      // let cols = []
      // for (field in ridata[0]) {
      //   (hiddenfields.includes(field))
      //   cols.push({
      //     header: field,
      //     key: field,
      //     width: (ridata[0][field]) ? ridata[0][field].length : 8,
      //     hidden: hiddenfields.includes(field)
      //   })
      // }
      // document.worksheet.columns = cols;

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
      // document.worksheet.addRow(cells);
      // document.worksheet.getRow(1).font = { name: 'helvetica', family: 4, size: 12, underline: 'double', bold: true };

      document.worksheet.getRow(1).eachCell( function(cell, colNumber){
        if(cell.value){
          document.worksheet.getRow(1).height = 42;
          cell.font = { name: 'helvetica', family: 4, underline: 'none', bold: true, color: {argb: 'FFFFFFFF'}};
          cell.alignment = {vertical: 'middle', horizontal: 'center'};
          cell.fill = {
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

      return trri;
    }

    const hider = () => {
      document.getElementById('lightbox').style.display = 'none';
      document.getElementById('details').style.display='none';
    }

    const details = (target) => {
        const d = document.getElementById("details");
        const l = document.getElementById("lightbox");
        l.style.display = "block";
        d.style.display = "block";
        d.src = target.href;
    }

    const createrow = (name) => {
      
      // Create a row in the table
      const ri = getprojectbykey(name);
      // console.log(ri)
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
          ForecastedResolution_Dt: function() {
            return makestringdate(ri.ForecastedResolution_Dt);
          },
          Created_Ts: function() {
            return  makestringdate(ri.Created_Ts);
          },
          Last_Update_Ts: function() {
            return  makestringdate(ri.Last_Update_Ts);
          },
          RiskRealized_Flg: function() {
            return  (ri.RiskRealized_Flg) ? "Y" : "N";
          },
          RIOpen_Hours: function() {
            return Math.floor(ri.RIOpen_Hours/24);
          },
          market: function() {
            const m = getlocationbykey(ri.Project_Key);
            return (m != undefined) ? m.Market_Cd : "";
          },
          facility: function() {
            const f = getlocationbykey(ri.Project_Key);
            return (f != undefined) ? f.Facility_Cd : "";
          },
          Region_Cd: function() {
            const r = getlocationbykey(ri.Project_Key);
            return (r != undefined) ? r.Region_Cd : "";
          },
          RI_Nm: function() {
              const url = "/risk-and-issues/details.php?au=false&status=1&popup=true&rikey=" + ri["RiskAndIssue_Key"]  + "&fscl_year=" + ri["Fiscal_Year"] + "&proj_name=" + ri["Proj_Nm"];
              return "<a href='" + url + "' onclick='details(this);return(false)'>" + ri["RI_Nm"] + "</a>";
          },
          subs: function() {
              // console.log("p4plist");
              // console.log(program);
              // console.log(program.RiskAndIssue_Key + "-" + program.ProgramRI_Key);
              // console.log(p4plist[program.RiskAndIssue_Key + "-" + program.ProgramRI_Key]);
          }
      };
      const rowValues = [];
      // console.log("ri");
      // console.log(ri);
      // for (field in excelfields) {
      //     (function(test) {
      //         const t = (typeof fieldswitch[test] != "function") ? ri[test] : fieldswitch[test]();
      //         rowValues.push((typeof t == "string" && t.indexOf("a href") == 1) ? t.substring((t.indexOf(">")+1), (t.indexOf("</a>"))) : t);
      //     })(field);
      // }
      // let newrow = document.worksheet.addRow(rowValues);

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
            trri.appendChild(makeelement({"e": "td", "t": texter, "c": "p-4 datacell" }));
          })(field);
      }
      return trri;
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
      t.className = (typeof o.c == "undefined") ? "" : o.c;
      t.innerHTML = (typeof o.t == "undefined") ? "" : o.t;
      t.colSpan = (typeof o.s == "undefined") ? "" : o.s;
      return t;
    }

    function listri(target, type) {
      
      // returns a list of risks or issues for a given program, taking program name and type (risk, issue)
      
      pre = ridata.filter(o => o.RILevel_Cd == "Program" && o.RIType_Cd == type && o.Program_Nm == target);
      uni = pre.map(item => item.RiskAndIssue_Key).filter((value, index, self) => self.indexOf(value) === index);
      return uni;
    }
    
    const getprojectbykey = (target, name) =>  mlm = ridata.find(o => o.Project_Key == target && o.Program_Nm == name);
    const uniques = ridata.map(item => item.Project_Key).filter((value, index, self) => self.indexOf(value) === index)
    
    
    const exporter = () => {
      document.workbook.xlsx.writeBuffer().then((buf) => {
        saveAs(new Blob([buf]), 'ri-project-dashboard-' + makedate(new Date()) + '.xlsx');
        // other stuffs
      });
    }

    
    const splitdate = (datestring) => {
      let newdate = datestring.split(" - ");
      return newdate;
    }  

    const betweendate = (dates, tween) => {
      spanner = splitdate(dates);
      let first = new Date(spanner[0]);
      let middle = new Date(tween);
      let last = new Date(spanner[1]);
      return ((middle >= first && middle <= last))
    }  

    const makedate = (dateobject) => {
      return dateobject.getFullYear() + "-" + (dateobject.getMonth()+1) + "-" + dateobject.getDate();
    }

      const flipname = (name) => {
      let fn = name.substring(name.indexOf(";")+2, name.indexOf("("));
      let ln = name.substring(0, name.indexOf(";"))
      return(fn + ln);
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