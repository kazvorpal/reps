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
    <title>Program R&I Dashboard</title>
    <link rel="shortcut icon" href="favicon.ico"/>
    <?php 
    include ("../../includes/load.php");
    function fixutf8($target) {
      if (gettype($target) == "string")
      return (utf8_encode($target));
      else 
      return ($target);
    }
    // Get ALL //
    $sqlstr = "select * from RI_MGT.fn_GetListOfAllRiskAndIssue(1) where riLevel_cd = 'program'";
    print '<!--' . $sqlstr . "<br/> -->";
    ini_set('mssql.charset', 'UTF-8');
    $riquery = sqlsrv_query($data_conn, $sqlstr);
    // print($data_conn);
    if($riquery === false) {
      if(($error = sqlsrv_errors()) != null) {
        foreach($error as $errors) {
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
        // print_r($row);
        // print($row["RiskAndIssueLog_Key"]);
        // print("<br/>");
      }
      
      // print ("<code>");
      // print_r($rows);
      // print ("</code>");

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
          // Get PROJECTS //
          $sqlstr = "select * from RI_Mgt.fn_GetListOfAssociatedProjectsForProgramRIKey(". $row["RiskAndIssue_Key"] ." ,". $row["ProgramRI_Key"] .", 1)";
          ini_set('mssql.charset', 'UTF-8');
          $p4pquery = sqlsrv_query($data_conn, $sqlstr);
          if($p4pquery === false) {
            if(($error = sqlsrv_errors()) != null) {
              print_r($error);
              foreach($error as $errors) {
                echo "SQLSTATE: ".$errors[ 'SQLSTATE']."<br />";
                echo "code: ".$errors[ 'code']."<br />";
                echo "message: ".$errors[ 'message']."<br />";
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
          // Get OWNERS //
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
        
      $driverlist = array();
      foreach ($rows as $row)  {
        if($row["ProgramRI_Key"] != '') {
          // Get OWNERS //
          $sqlstr = "select * from RI_MGT.fn_GetListOfDriversForriLogKey(". $row["RiskAndIssueLog_Key"] ." , 1)";
          // print $sqlstr . "<br>";
          ini_set('mssql.charset', 'UTF-8');
          $driverquery = sqlsrv_query($data_conn, $sqlstr);
          if($driverquery === false) {
            if(($error = sqlsrv_errors()) != null) {
              foreach($errors as $error) {
                echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
                echo "code: ".$error[ 'code']."<br />";
                echo "message: ".$error[ 'message']."<br />";
              }
            }
          } else {
            $count = 1;
            $driverrows = array();
            while($driverrow = sqlsrv_fetch_array($driverquery, SQLSRV_FETCH_ASSOC)) {
              $driverrows[] = array_map("fixutf8", $driverrow);
            }
          }
          $driverlist[$row["RiskAndIssueLog_Key"]] = $driverrows;
        }
      }
        

      $p4pout = json_encode($p4plist);
      $mangerout = json_encode($mangerlist);
      $driverout = json_encode($driverlist);
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
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
    function MM_setTextOfTextfield(objId,x,newText) { //v9.0
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
        <h1><?php if($fiscal_year !=0) {echo $fiscal_year;}?> Program R&I Dashboard</h1>
        <div style="display:inline-block;width:28%;text-align:right;font-size:larger" id="resultcount"></div><div style="display:inline-block;width:20%;text-align:right"><span class="btn btn-primary" onclick="exporter()">Export Results</span><p/></div>
    <?php 
      require '../includes/ri-selectors.php';
      ?>
          <!-- <span class="btn btn-primary" onclick="exporter()">Export Results</span><p/> -->
        <div id="main" class="accordion" >
            <div class="header">
              Program Name (Risks, Issues)
            </div>
        </div>
      
      </div>
    </div>
  </div>
</section>
<section>

</section>
<section></section>
<section>
  <div class="container">
    <div class="row"></div>
  </div>
</section>

<script>
	var myVar;
	
	function myFunction() {
	  myVar = setTimeout(showPage, 1000);
	}
	
	function showPage() {
	  document.getElementById("loader").style.display = "none";
	  document.getElementById("myDiv").style.display = "block";
	}
</script>

</div>
</body>
<script src="../js/ri.js"></script>
<script>
  
  const ridata = <?= $jsonout ?>;  
  const mangerlist = <?= $mangerout ?>;
  const driverlist = <?= $driverout ?>;
  const locationlist = <?= $locationout ?>;
  const p4plist = <?= $p4pout ?>;
  console.log(ridata)
  
  const projectfields = ["EPSProject_Nm", "EPS_Location_Cd", "EPSProject_Owner", "Subprogram_nm"];
  const projectfieldnames = [{name: "Project Name", width: "38"}, {name: "Facility", width: "9"}, {name: "Owner", width: "28"}, {name: "Subprogram", width: "5"}];
  const finder = (target, objective) => (target.find(o => o.Program_Nm == objective));
  
  const rifields = {"RiskAndIssue_Key": {name: "ID", width: "3"}, "Fiscal_Year": {name: "FY", width: "4"}, "Program_Nm": {name: "Program", width: "9"}, "Region_Cd": {name: "Region", width: "6"}, "LastUpdateBy_Nm": {name: "Owner", width: "10"}, "ImpactLevel_Nm": {name: "Impact Level", width: "10"}, "ActionPlanStatus_Cd": {name: "Action Status", width: "27"}, "ForecastedResolution_Dt": {name: "Forecast Resol. Date", width: "6"}, "ResponseStrategy_Cd": {name: "Response Strategy", width: "5"}, "RIOpen_Hours": {name: "Open Duration", width: "6"}}
  const excelfields = {"RiskAndIssue_Key": "ID", "Fiscal_Year": "FY",	"Active_Flg": "Status", "Program_Nm": "Program", "owner": "Owner", "RIType_Cd": "Type", "Region_Cd": "Region", "category": "Category", "projectcount": "Proj Count", "RI_Nm": "Name", "ScopeDescriptor_Txt": "Descriptor", "RIDescription_Txt": "Description", "driver": "Driver (primary)", "ImpactArea_Nm": "Impact Area", "ImpactLevel_Nm": "Impact Level",	"RiskProbability_Nm": "Probability", "ResponseStrategy_Nm": "Response", "POC_Nm": "POC Name", "POC_Department": "POC Group", "ActionPlanStatus_Cd": "Action Plan Status", "ForecastedResolution_Dt": "Resolution Date", "RIOpen_Hours": "Days Open", "AssociatedCR_Key": "CR", "RaidLog_Flg": "Portfolio Notified", "RiskRealized_Flg": "Risk Realized", "RIClosed_Dt": "Date Closed", "Created_Ts": "Creation Date", "LastUpdate_By": "Last Update By", "Last_Update_Ts": "Last Update Date", "quartercreated": "Quarter Created", "quarterclosed": "Quarter Closed", "monthcreated": "Month Created", "monthclosed": "Month Closed", "duration": "Duration"};

  const populate = (rilist) => {
    console.log(rilist);
    document.getElementById("resultcount").innerHTML = (rilist.length-1) + " Results Found"
    // The main function that creates everything
    const main = document.getElementById("main");
    main.innerHTML = '<div class="header">Program Name (Risks, Issues)</div>';
    document.workbook = new ExcelJS.Workbook();
    document.workbook.creator = "RePS Website";
    document.workbook.lastModifiedBy = "Kaz";
    document.workbook.created = new Date();
    document.worksheet = document.workbook.addWorksheet('Program Report',  {properties:{tabColor:{argb:'3355bb'}, headerFooter: "Program Report Spreadsheet", firstFooter: "RePS"}});

    let cols = [];
    for (field in excelfields) {
      cols.push({
        header: excelfields[field],
        key: field,
        width: 16
      })
    }
    document.worksheet.columns = cols;
    // for (field in ridata[0]) {
    //   cols.push({
    //     header: field,
    //     key: field,
    //     width: (ridata[0][field]) ? ridata[0][field].length : 8
    //   })
    // }
    // document.worksheet.columns = cols;

    // document.worksheet.addRow(rowValues);
    for (loop of rilist) {
      // creates all the programs
      if(loop != null) {
        makerow(loop, countri(loop, "Risk"), countri(loop, "Issue"));
      }
    }
  }

  const makearray = (rin) => {
    let r = [];
    let a = getprogrambyname(rin);
    for (field in a) {
      r.push(a[field]);
    }
    return a;
  };

  const exporter = () => {
    document.workbook.xlsx.writeBuffer().then((buf) => {
      saveAs(new Blob([buf]), 'ri-aggregate-' + makedate(new Date()) + '.xlsx');
    });
  }

  function makeoctet(s) { 
    var buf = new ArrayBuffer(s.length); //convert s to arrayBuffer
    var view = new Uint8Array(buf);  //create uint8array as viewer
    for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF; //convert to octet
    return buf;    
  }

  const makerow = (name, risks, issues) => {

    // Runs once per Program

    const safename = makesafe(name);
    const item = makeelement({"e": "div", "i": "item" + safename, "c": "toppleat accordion-item"});
    const banner = makebanner(safename);
    const collapse = makeelement({e: "div", i: "collapse" + safename, c: "panel-collapse collapse"});
    const body = makeelement({e: "div", i: "body" + safename, c: "accordion-body"});
    const table = makeelement({e: "table", i: "table" + safename, c: "table"});

    banner.appendChild(makeelement({e: "span", i: "program" + safename, c: "a-proj", t: name}));
    banner.appendChild(document.createTextNode(" (R:" + risks + " I:" + issues + ")"));
    item.appendChild(banner);
    item.appendChild(collapse).appendChild(body).appendChild(table);
    document.getElementById("main").appendChild(item);

    makeri(name, "Risk");
    makeri(name, "Issue");
  }  

  const makebanner = (safename) => {

    // Program Start

    const bannerfields = {"aria-labelledby": "banner" + safename, "data-bs-target": "#collapse" + safename, "data-target": "#collapse" + safename, "data-toggle": "collapse", "aria-controls": "collapse" + safename};
    const banner = document.createElement("div");
    banner.id = "banner" + safename;
    banner.className = "accordion-banner";
    //  (a c).log(bannerfields);
    Object.entries(bannerfields).forEach(([key, value]) => banner.setAttribute(key, value));
    banner.ariaExpanded = true;
    return banner;
  }  

  const makeri = (name, type) => {
    // Create a Risk or Issue section
    
    program = getprogrambyname(name);
    if (
      (document.getElementById('risk_issue').value == "" || $('#risk_issue').val().includes(type)) &&
      (typeof document.getElementById('impact_level').value != "undefined" || document.getElementById('impact_level').value == "" || $('#impact').val().includes(program.ImpactLevel_Nm))
      ){
        let lr = listri(name, type);
        if (lr.length != 0) {
          document.getElementById("table"+makesafe(name)).appendChild(makeheader(name, type));
          // console.log(ridata)
          for (ri of lr) {
            makedata(ri, type, name);  
          }
        }
      }
    }
    
    const makedata = (id, type, name) => {            
    // return true;

      // Make all the data inside a risk or issue
      const fieldswitch = {
        //    Specific fields that need extra calculation
        mangerlist: function() {
          const manger = mangerlist[program.Fiscal_Year + "-" + program.MLMProgram_Key];
          let mangers = [];
          for (man of manger) {
            mangers.push(man.User_Nm);
          }  
          return mangers.join().replace(",", ", ");
        },
        owner: function() {
          return program.LastUpdateBy_Nm;
        },
        ForecastedResolution_Dt: function() {
          return (program.ForecastedResolution_Dt == null) ? "" : makestringdate(program.ForecastedResolution_Dt);
        },
        Active_Flg: function() {
          return (program.Active_Flg) ? "Open" : "Closed";
        },
        Created_Ts: function() {
          return makestringdate(program.Created_Ts);
        },
        monthcreated: function() {
          return new Date(program.Created_Ts.date).toLocaleString('default', { month: 'long' });
        },
        monthclosed: function() {
          return new Date(program.Last_Update_Ts.date).toLocaleString('default', { month: 'long' });
        },
        quartercreated: function() {
          const m = new Date(program.Created_Ts.date).getMonth();
          return  (m < 3) ? "Q1" : (m < 3) ? "Q2" : (m < 9) ? "Q3" : "Q4";
        },
        quarterclosed: function() {
          const m = new Date(program.Last_Update_Ts.date).getMonth();
          return  (!program.Status) ? "" : (m < 3) ? "Q1" : (m < 3) ? "Q2" : (m < 9) ? "Q3" : "Q4";
        },
        duration: function() {
          const d = Math.floor((new Date(program.Last_Update_Ts.date) - new Date(program.Created_Ts.date))/(1000 * 60 * 60 * 24));
          return  d + " days";
        },
        Last_Update_Ts: function() {
          return  makestringdate(program.Last_Update_Ts);
        },
        AssociatedCR_Key: function() {
          return  (program.RiskRealized_Flg) ? "Y" : "";
        },
        Region_Cd: function() {
          let counter = 0;
          // console.log("in region")
          // console.log(program.RI_Nm);
          // console.log(program.Region_Cd);
          for(r of ridata) {
              // console.log("r");
              // console.log(r);
              if (r.RI_Nm == program.RI_Nm) {
                // console.log(r.Region_Cd);
                counter++;
              }
            }
          // console.log("counter");
          // console.log(counter);
          return (counter < 2) ? program.Region_Cd : "Multiple";
        },
        RaidLog_Flg: function() {
          return  (program.RiskRealized_Flg) ? "Y" : "";
        },
        RiskRealized_Flg: function() {
          return  (program.RiskRealized_Flg) ? "Y" : "";
        },
        RIOpen_Hours: function() {
          return Math.floor(program.RIOpen_Hours/24) + " days";
        },
        driver: function() {
          return (driverlist[program.RiskAndIssueLog_Key]) 
          ? (driverlist[program.RiskAndIssueLog_Key][0]) 
          ? driverlist[program.RiskAndIssueLog_Key][0].Driver_Nm : "" : "";
        },
          projectcount: function() {
            let projects = p4plist[program.RiskAndIssue_Key + "-" + program.ProgramRI_Key];
            return (projects.length>0) ? projects.length : "";
          }, 
          category: function() {
            let projects = p4plist[program.RiskAndIssue_Key + "-" + program.ProgramRI_Key];
            return (projects.length>0) ? "Projects" : "Global";
          }
        };
        
        const program = getprogrambykey(id, name);
        const safename = makesafe(program.Program_Nm);
        const saferi = makesafe(program.RI_Nm);
        console.log(program.ImpactLevel_Nm);
        if (document.getElementById('impact_level').value != "") {
          // console.log($('#impact_level').val());
          // console.log(program.ImpactLevel_Nm);
          // console.log($('#impact_level').val().includes(program.ImpactLevel_Nm));
          // console.log(program.ImpactLevel_Nm);
          // console.log(document.getElementById('impact_level').selectedOptions);
          // console.log(document.getElementById('impact_level').selectedOptions.namedItem(program.ImpactLevel_Nm));
          for (let option of document.getElementById('impact_level').options) {
            if(option.selected) {
              if(option.value == program.ImpactLevel_Nm)
                console.log("match")
              else
                console.log("fail")
            }
          }
        }
        if (document.getElementById('impact_level').value == "" || ($('#impact_level').val()).includes(program.ImpactLevel_Nm)) {
          const trid = "tr" + type + saferi + Math.random();
          document.getElementById("table" + safename).appendChild(makeelement({e: "tr", i: trid, c: "ptr"}));
          const arrow = (p4plist[program.RiskAndIssue_Key + "-" + program.ProgramRI_Key].length != 0) ? "▶" : "";
          const c = (arrow == "") ? "plainbox" : "namebox";
          const header = makeelement({
            "e": "th", 
            "i": "th" + type + saferi, 
            // "t": "<div class='arrows' id='arrow" + saferi + "'> " + arrow + " </div><div style='overflow:hidden'>" + program.RI_Nm + "</div>", 
            "t": "<div style='overflow:hidden'>" + program.RI_Nm + "</div>", 
            "c":"p-4 " + c
          });
          const tridobj = document.getElementById(trid);
          if (arrow != "") {
            tridobj.onclick = function() {
              toggler(document.getElementById("projects" + saferi), this.children[0]);
            };
          }
          for (field of Object.keys(rifields)) {
            (function(test) {
              const texter = (typeof fieldswitch[test] != "function") ? program[test] : fieldswitch[test]();
              tridobj.appendChild(makeelement({e: "td", t: texter, c: "p-4 datacell"}));
            })(field);
            if (rifields[field].name == "ID") {
              tridobj.appendChild(header);
            }
          }
          var rowValues = [];
          // for (field in program) {
            //   (function(test) {
              //     const texter = (typeof fieldswitch[test] != "function") ? program[test] : fieldswitch[test]();
              //     rowValues.push(texter);
              //   })(field);
              // }
              for (field in excelfields) {
        (function(test) {
            const texter = (typeof fieldswitch[test] != "function") ? program[test] : fieldswitch[test]();
            rowValues.push(texter);
        })(field);
      }
      let newrow = document.worksheet.addRow(rowValues);
      if(arrow != "") {
        makeprojects(p4plist[program.RiskAndIssue_Key + "-" + program.ProgramRI_Key], program.Program_Nm, "table" + safename, saferi);
      }
    }
  }    

  const makeprojects = (projects, programname, tableid, saferi) => {

    // Make the rows of projects inside the program

    document.getElementById(tableid).appendChild(makeelement({e: "tr", i: "projects" + saferi, c: "panel-collapse collapse"}));
    document.getElementById("projects" + saferi).appendChild(makeelement({e: "td", t: "&nbsp;"}));
    document.getElementById("projects" + saferi).appendChild(makeelement({e: "td", i: "td" + saferi, s: 4}));
    if (projects.length != 0) {
      const table = document.createElement("table");
      table.id = "table" + saferi;
      table.appendChild(projectheader());
      document.getElementById("td" + saferi).appendChild(table);
      let p = [];
      for(project of projects) {
        if (!p.includes(project.PROJECT_key)){
          const tr = document.createElement("tr");
          tr.id = "tr" + project.PROJECT_key;
          document.getElementById("table" + saferi).appendChild(tr);
          for (field of projectfields) {
            tr.appendChild(makeelement({e: "td", t: project[field], c: "p4 datacell"}));
          }
          p.push(project.PROJECT_key);
        }
      }
    } else {
      let empty = document.createTextNode("No Associated Projects");
      document.getElementById("td" + saferi).appendChild(empty);
    }
  }  

  const projectheader = () => {
    // Make the header row for a project 
    const trri = document.createElement("tr");
    for (field of Object.keys(projectfieldnames)) {
      trri.appendChild(makeelement({e: "th", t: projectfieldnames[field].name, c: "p-4 titles", w: projectfieldnames[field].width}));
    }
    // trri.appendChild(makeelement({e: "th", w: "69"}));
    return trri;
  }  

  
  const makeheader = (name, type) => {
    
    // Make the header row for a risk or issue
    // let cells = [];
    // for (field in rifields) {
    //   cells.push(rifields[field]);
    // }
    // document.worksheet.addRow(cells);
    const safename = makesafe(name);
    const trri = makeelement({"e": "tr", "i": type + safename, "t": "", "c":"p-4"});
    let cells = ["Risk/Issue"];
    for (field of Object.keys(rifields)) {
      // classes = (field == "Action Status") ? 
      trri.appendChild(makeelement({"e": "th", "t": rifields[field].name, "c": "p-4 titles", "w": rifields[field].width}));
      cells.push(rifields[field].name);
      if (rifields[field].name == "ID") {
        trri.appendChild(makeelement({"e": "th", "t": type+"s", "c": "p-4 text-center titles", "w": "12"}));
      }
    }
    // document.worksheet.addRow(cells);
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
    // document.worksheet.getRow(1).font = { name: 'helvetica', family: 4, size: 12, underline: 'double', bold: true};
    return trri;
  }

  // Utility functions

  const todate = (date) => new Date(date).toLocaleString("en-US", {day: "numeric", month: "numeric", year: "numeric"}).replace(/-/g, "/");  

  function countri(target, type) {
    
    // returns count of risks or issues for a given program, taking program name and type (risk, issue)
    
    pre = ridata.filter(o => o.RILevel_Cd == "Program" && o.RIType_Cd == type && o.Program_Nm == target);
    uni = pre.map(item => item.RiskAndIssue_Key).filter((value, index, self) => self.indexOf(value) === index);
    return uni.length;
  }
  function listri(target, type) {
    
    // returns a list of risks or issues for a given program, taking program name and type (risk, issue)
    
    pre = ridata.filter(o => o.RILevel_Cd == "Program" && o.RIType_Cd == type && o.Program_Nm == target);
    uni = pre.map(item => item.RiskAndIssue_Key).filter((value, index, self) => self.indexOf(value) === index);
    return uni;
  }
  
  const getprojectbykey = (target, name) =>  mlm = ridata.find(o => o.RiskAndIssue_Key == target && o.PROJECT_key == name);
  
  const getuniques = (list, field) => {

  }


  const uniques = ridata.map(item => item.Program_Nm).filter((value, index, self) => self.indexOf(value) === index)
  

  const toggler = (target, o) => {
    // Toggles visibility of projects when a given program is clicked
    if (target != null) {
      if (target.className.indexOf("show") != -1) {
        target.className = target.className.replace("show", "");
        o.children[0].innerHTML = "►";
      } else { 
        target.className += "show";
        o.children[0].innerHTML = "▼";
       }
    }
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
</html>