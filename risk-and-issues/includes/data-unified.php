<?php

  function fixutf8($target) {
    return (gettype($target) == "string") ? (utf8_encode($target)) : ($target);
  }  

  $mode = (stripos($_SERVER['REQUEST_URI'], "program")) ? "program" : "project";


  $sqlportfolioprogramsclosed = <<<PPC
  WITH CTE_LastInactiveRILogs AS (
    select  max(riskandissuelog_key) as RILogs
    from [RI_MGT].[fn_GetListOfProgramsForPortfolioRI_Key] (-1)
    where Active_Flg = 0
    group by RiskAndIssue_Key
)
SELECT a.*
FROM [RI_MGT].[fn_GetListOfProgramsForPortfolioRI_Key] (-1) a
INNER JOIN CTE_LastInactiveRILogs b on b.RILogs = a.RiskAndIssueLog_Key
PPC;



  $sqlstr = "select * from RI_MGT.fn_GetListOfAllRiskAndIssue(1) where riLevel_cd = 'program'";
  // print '<!--' . $sqlstr . "<br/> -->";
  ini_set('mssql.charset', 'UTF-8');
  $programquery = sqlsrv_query($data_conn, $sqlstr);
  // print($data_conn);
  if($programquery === false) {
    if(($error = sqlsrv_errors()) != null) {
      foreach($error as $errors) {
        echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
        echo "code: ".$error[ 'code']."<br />";
        echo "message: ".$error[ 'message']."<br />";
      }
    }
  }  else {
    $programrows = array();
    $count = 1;
    while($programrow = sqlsrv_fetch_array($programquery, SQLSRV_FETCH_ASSOC)) {
      $programrows[] = array_map("fixutf8", $programrow);
    }

    $sqlstr = "select * from RI_MGT.fn_GetListOfAllRiskAndIssue(0) where riLevel_cd = 'program'";
    ini_set('mssql.charset', 'UTF-8');
    $closedprogram = sqlsrv_query($data_conn, $sqlstr);
    if($closedprogram === false) {
      if(($error = sqlsrv_errors()) != null) {
        foreach($errors as $error) {
          echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
          echo "code: ".$error[ 'code']."<br />";
          echo "message: ".$error[ 'message']."<br />";
        }
      }
    } else {
      $closedprogramrows = array();
      $count = 1;
      while($row = sqlsrv_fetch_array($closedprogram, SQLSRV_FETCH_ASSOC)) {
        $closedprogramrows[] = array_map("fixutf8", $row);
      }
    }
    $p4plist = array();
    foreach ($programrows as $row)  {
      if($row["MLMProgramRI_Key"] != '') {
        // echo "IN";
        // Get PROJECTS for programs //
        $sqlstr = "select * from RI_Mgt.fn_GetListOfAssociatedProjectsForProgramRIKey(". $row["RiskAndIssue_Key"] ." ,". $row["MLMProgramRI_Key"] .", -1)";
        //  echo $sqlstr . "<br/>";
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
          // echo "OUT";
          $count = 1;
          $p4prows = array();
          $checker = 0;
          while($p4prow = sqlsrv_fetch_array($p4pquery, SQLSRV_FETCH_ASSOC)) {
            $p4prows[] = array_map("fixutf8", $p4prow);
            $checker = 1;
          }
        }
        $p4plist[$row["RiskAndIssue_Key"]."-".$row["MLMProgramRI_Key"]] = $p4prows;
      }
    }
    $sublist = array();
    foreach ($programrows as $row)  {
      // if($row["MLMProgramRI_Key"] != '') {
        // echo "IN";
        // Get subprogram for global programs] //
        $sqlstr = "select * from  RI_Mgt.fn_GetListSubProgramsforRIKey(". $row["RiskAndIssue_Key"] ." ,". $row["RIActive_Flg"] .")";
        //  echo $sqlstr . "<br/>";
        ini_set('mssql.charset', 'UTF-8');
        $subquery = sqlsrv_query($data_conn, $sqlstr);
        $subrows = array();
        if($subquery === false) {
          if(($error = sqlsrv_errors()) != null) {
            print_r($error);
            foreach($error as $errors) {
              echo "SQLSTATE: ".$errors[ 'SQLSTATE']."<br />";
              echo "code: ".$errors[ 'code']."<br />";
              echo "message: ".$errors[ 'message']."<br />";
            }
          }
        } else {
          // echo "OUT";
          $count = 1;
          $checker = 0;
          while($subrow = sqlsrv_fetch_array($subquery, SQLSRV_FETCH_ASSOC)) {
            $subrows[] = array_map("fixutf8", $subrow);
            $checker = 1;
          }
        }
        $sublist[$row["RiskAndIssue_Key"]] = $subrows;
      // }
    }
  }
  


  $sqlstr = "select * from RI_MGT.fn_GetListOfAllRiskAndIssue(1) where riLevel_cd in ('portfolio')";
  // print '<!--' . $sqlstr . "<br/> -->";
  ini_set('mssql.charset', 'UTF-8');
  $portfolioquery = sqlsrv_query($data_conn, $sqlstr);
  // print($data_conn);
  if($portfolioquery === false) {
    if(($error = sqlsrv_errors()) != null) {
      foreach($error as $errors) {
        echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
        echo "code: ".$error[ 'code']."<br />";
        echo "message: ".$error[ 'message']."<br />";
      }
    }
  }  else {
    $portfoliorows = array();
    $count = 1;
    while($portfoliorow = sqlsrv_fetch_array($portfolioquery, SQLSRV_FETCH_ASSOC)) {
      $portfoliorows[] = array_map("fixutf8", $portfoliorow);
    }

    $sqlstr = "select * from RI_MGT.fn_GetListOfAllRiskAndIssue(0) where riLevel_cd in ('portfolio')";
    // echo $sqlstr;
    ini_set('mssql.charset', 'UTF-8');
    $closedportfolio = sqlsrv_query($data_conn, $sqlstr);
    if($closedportfolio === false) {
      if(($error = sqlsrv_errors()) != null) {
        foreach($errors as $error) {
          echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
          echo "code: ".$error[ 'code']."<br />";
          echo "message: ".$error[ 'message']."<br />";
        }
      }
    } else {
      $closedportfoliorows = array();
      $count = 1;
      while($row = sqlsrv_fetch_array($closedportfolio, SQLSRV_FETCH_ASSOC)) {
        $closedportfoliorows[] = array_map("fixutf8", $row);
      }
    }
    foreach ($portfoliorows as $row)  {
      // if($row["MLMProgramRI_Key"] != '') {
        // echo "IN";
        // Get subprogram for global programs] //
        $sqlstr = "select * from  RI_Mgt.fn_GetListSubProgramsforRIKey(". $row["RiskAndIssue_Key"] ." ,". $row["RIActive_Flg"] .")";
        //  echo $sqlstr . "<br/>";
        ini_set('mssql.charset', 'UTF-8');
        $subquery = sqlsrv_query($data_conn, $sqlstr);
        $subrows = array();
        if($subquery === false) {
          if(($error = sqlsrv_errors()) != null) {
            print_r($error);
            foreach($error as $errors) {
              echo "SQLSTATE: ".$errors[ 'SQLSTATE']."<br />";
              echo "code: ".$errors[ 'code']."<br />";
              echo "message: ".$errors[ 'message']."<br />";
            }
          }
        } else {
          // echo "OUT";
          $count = 1;
          $checker = 0;
          while($subrow = sqlsrv_fetch_array($subquery, SQLSRV_FETCH_ASSOC)) {
            $subrows[] = array_map("fixutf8", $subrow);
            $checker = 1;
          }
        }
        $sublist[$row["RiskAndIssue_Key"]] = $subrows;
      // }
    }
    $portfolioprogramsstr = "select * from [RI_MGT].[fn_GetListOfProgramsForPortfolioRI_Key] (-1)";
    ini_set('mssql.charset', 'UTF-8');
    $portfolioprograms = sqlsrv_query($data_conn, $portfolioprogramsstr);
    if($portfolioprograms === false) {
      if(($error = sqlsrv_errors()) != null) {
        foreach($errors as $error) {
          echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
          echo "code: ".$error[ 'code']."<br />";
          echo "message: ".$error[ 'message']."<br />";
        }
      }
    } else {
      $portfolioprogramsrows = array();
      $count = 1;
      while($row = sqlsrv_fetch_array($portfolioprograms, SQLSRV_FETCH_ASSOC)) {
        $portfolioprogramsrows[] = array_map("fixutf8", $row);
      }
    }
     // portfolio programs, closed
    ini_set('mssql.charset', 'UTF-8');
    $portfolioprogramsclosed = sqlsrv_query($data_conn, $sqlportfolioprogramsclosed);
    if($portfolioprogramsclosed === false) {
      if(($error = sqlsrv_errors()) != null) {
        foreach($errors as $error) {
          echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
          echo "code: ".$error[ 'code']."<br />";
          echo "message: ".$error[ 'message']."<br />";
        }
      }
    } else {
      $portfolioprogramsclosedrows = array();
      $count = 1;
      while($row = sqlsrv_fetch_array($portfolioprogramsclosed, SQLSRV_FETCH_ASSOC)) {
        $portfolioprogramsclosedrows[] = array_map("fixutf8", $row);
      }
    }
  }

  $sqlstr = "select * from RI_MGT.fn_GetListOfAllRiskAndIssue(1) where riLevel_cd = 'project'";
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
    }
        
    $sqlstr = "select * from RI_MGT.fn_GetListOfAllRiskAndIssue(0) where riLevel_cd = 'project'";
    print '<!--' . $sqlstr . "<br/>-->";
    ini_set('mssql.charset', 'UTF-8');
    $closedquery = sqlsrv_query($data_conn, $sqlstr);
    if($closedquery === false) {
      if(($error = sqlsrv_errors()) != null) {
        foreach($errors as $error) {
          echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
          echo "code: ".$error[ 'code']."<br />";
          echo "message: ".$error[ 'message']."<br />";
        }
      }
    } else {
      $closedrows = array();
      $count = 1;
      while($row = sqlsrv_fetch_array($closedquery, SQLSRV_FETCH_ASSOC)) {
        $closedrows[] = array_map("fixutf8", $row);
      }
    }

    $sqlstr = "select * from RI_MGT.fn_GetListOfLocationsForEPSProject(-1)";
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
      }
    }



    $mangerlist = array();
    foreach ($rows as $row)  {
      if($row["MLMProgramRI_Key"] != '') {
        // Get OWNERS //
        $sqlstr = "select * from RI_MGT.fn_GetListOfOwnersInfoForProgram(". $row["Fiscal_Year"] ." ,'". $row["MLMProgram_Nm"] ."')";
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
    
    $sqlstr = "select * from RI_MGT.fn_GetListOfDriversForriLogKey(1)";
    $driverquery = sqlsrv_query($data_conn, $sqlstr);
    if($driverquery === false) {
      if(($error = sqlsrv_errors()) != null) {
        foreach($error as $errors) {
          echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
          echo "code: ".$error[ 'code']."<br />";
          echo "message: ".$error[ 'message']."<br />";
        }
      }
    } else {
      $driverrows = array();
      $count = 1;
      while($driverrow = sqlsrv_fetch_array($driverquery, SQLSRV_FETCH_ASSOC)) {
        $driverrows[$driverrow["RiskAndIssueLog_Key"]] = array_map("fixutf8", $driverrow);
        // $driverrows[] = array_map("fixutf8", $driverrow);
      }
    }
      // Get Drivers
    $sqlstr = "select * from RI_MGT.fn_GetListOfDriversForriLogKey(0)";
    // print '<!--' . $sqlstr . "<br/> -->";
    $driverquery = sqlsrv_query($data_conn, $sqlstr);
    if($driverquery === false) {
      if(($error = sqlsrv_errors()) != null) {
        foreach($error as $errors) {
          echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
          echo "code: ".$error[ 'code']."<br />";
          echo "message: ".$error[ 'message']."<br />";
        }
      }
    } else {
      $count = 1;
      while($driverrow = sqlsrv_fetch_array($driverquery, SQLSRV_FETCH_ASSOC)) {
        $driverrows[$driverrow["RiskAndIssueLog_Key"]] = array_map("fixutf8", $driverrow);
      }
    }

    // Action Plan Data
    $sqlap = "select * from RI_Mgt.fn_GetListOfLastUpDtForActionPlanAndPIChangeLogs()
    where [Source] = 'ACTNPlan'
    Order By RiskandIssue_Key DESC";
    // print '<!--' . $sqlstr . "<br/> -->";
    ini_set('mssql.charset', 'UTF-8');
    $apquery = sqlsrv_query($data_conn, $sqlap);
    // print($data_conn);
    if($apquery === false) {
      if(($error = sqlsrv_errors()) != null) {
        foreach($error as $errors) {
          echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
          echo "code: ".$error[ 'code']."<br />";
          echo "message: ".$error[ 'message']."<br />";
        }
      }
    }  else {
      $aprows = array();
      $count = 1;
      while($aprow = sqlsrv_fetch_array($apquery, SQLSRV_FETCH_ASSOC)) {
        $aprows[$aprow["RiskAndIssue_Key"]] = array_map("fixutf8", $aprow);
      }
    }
      // Change Log Data
    $sqllog = "select * from RI_Mgt.fn_GetListOfLastUpDtForActionPlanAndPIChangeLogs()
    where [Source] = 'PRJILog'
    Order By RiskandIssue_Key DESC";
    // print '<!--' . $sqlstr . "<br/> -->";
    ini_set('mssql.charset', 'UTF-8');
    $logquery = sqlsrv_query($data_conn, $sqllog);
    // print($data_conn);
    if($logquery === false) {
      if(($error = sqlsrv_errors()) != null) {
        foreach($error as $errors) {
          echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
          echo "code: ".$error[ 'code']."<br />";
          echo "message: ".$error[ 'message']."<br />";
        }
      }
    }  else {
      $logrows = array();
      $count = 1;
      while($logrow = sqlsrv_fetch_array($logquery, SQLSRV_FETCH_ASSOC)) {
        $logrows[$logrow["RiskAndIssue_Key"]] = array_map("fixutf8", $logrow);
      }
    }
  

    $logout = json_encode($logrows);
    $apout = json_encode($aprows);
    $subout = json_encode($sublist);
    $p4pout = json_encode($p4plist);
    $mangerout = json_encode($mangerlist);
    $driverout = json_encode($driverrows);
    $locationout = json_encode($locationrows);
    $portfolioprogramsout = json_encode($portfolioprogramsrows);
    $portfolioprogramsclosedout = json_encode($portfolioprogramsclosedrows);
    $projectout = json_encode($rows);
    $closedout = json_encode($closedrows);
    $programout = json_encode($programrows);
    $closedprogramout = json_encode($closedprogramrows);
    $portfolioout = json_encode($portfoliorows);
    $closedportfolioout = json_encode($closedportfoliorows, JSON_PRETTY_PRINT);
    // echo $closedportfolioout;
  
  
  }

?>
<script>

let projectopen = <?= $projectout ?>;  
    projectopen = sortby(projectopen, "RiskAndIssue_Key");
    let projectclosed = <?= $closedout ?>;  
    projectclosed = sortby(projectclosed, "RiskAndIssue_Key");
    let projectfull = projectopen.concat(projectclosed);  
    projectfull = sortby(projectfull, "RiskAndIssue_Key");
    let programopen =<?= $programout ?>;
    programopen = sortby(programopen, "RiskAndIssue_Key");
    let programclosed =<?= $closedprogramout ?>;
    programclosed = sortby(programclosed, "RiskAndIssue_Key");
    let programfull = programopen.concat(programclosed);  
    programfull = sortby(programfull, "RiskAndIssue_Key");
    let portfolioopen =<?= $portfolioout ?>;
    portfolioopen = sortby(portfolioopen, "RiskAndIssue_Key");
</script>
<!-- this is portfolio closed -->
<script>

let portfolioclosed = <?= $closedportfolioout ?>;
    // Closing line
    // portfolioclosed = sortby(portfolioclosed, "RiskAndIssue_Key");
    var placeholder = [];
    portfolioclosed.forEach(o => { 
      placeholder.push(o)
    });
    // portfolioclosed = placeholder;
    const mangerlist = <?= $mangerout ?>;
    const driverlist = <?= $driverout ?>;
    const locationlist = <?= $locationout ?>;
    const p4plist = <?= $p4pout ?>;
    const sublist = <?= $subout ?>;
    const portfolioprograms = <?= $portfolioprogramsout ?>;
    const portfolioprogramsclosed = <?= $portfolioprogramsclosedout ?>;
    const aplist = <?= $apout ?>;
    const loglist = <?= $logout ?>;

    let portfoliofull = syncportfolio(portfolioopen).concat(syncportfolio(placeholder));
    portfoliofull = sortby(portfoliofull, "RiskAndIssue_Key");

    const cleandata = (list) => {
      Object.keys(list).forEach(key => {
        if(list[key].RiskAndIssue_Key == "undefined") {
          delete list[key];
        }
      })
      return(list);
    }

      var ridata, d1, d1, rifiltered;
      const setdata = () => {
        ridata = d1 = d1 = rifiltered = "";
          if (mode == "program") {
            const localportfolios = portfoliofull.filter(o => {
                return o.RaidLog_Flg == 0;
              })
              ridata = programfull;
              d1 = programopen;
              d2 = programclosed;
          } else if (mode == "portfolio") {
              const globalprograms = programfull.filter(o => {
                return o.RaidLog_Flg == 1;
              })
              ridata = cleandata(portfoliofull).concat(globalprograms)
              d1 = portfolioopen;
              d2 = portfolioclosed;
          } else {
              ridata = projectfull;
              d1 = projectopen;
              d2 = projectclosed;
          }
      }
      setdata();

      $(document).ready(function(){
          //Examples of how to assign the Colorbox event to elements
          showPage();
          colorboxschtuff();
      });
      $(function () {
        $('[data-toggle="tooltip"]').tooltip()
      })
      
      // Names of Data for program fields
      const regions = {"California": "CA", "Southwest": "SW", "Central": "CE", "Northeast": "NE", "Virginia": "VA", "Southeast": "SE", "Northwest": "NW", "Corporate": "COR"}
      const fieldlist = ["Program", "Region", "Program Manager", "ID", "Impact Level", "Action Plan", "Forecast Resol. Date", "Response Strat", "Open Duration"];
      const datafields = ["MLMProgram_Nm", "MLMRegion_Cd", "mangerlist", "RiskAndIssue_Key", "ImpactLevel_Nm", "ActionPlanStatus_Cd", "ForecastedResolution_Dt", "POC_Nm", "ResponseStrategy_Cd", "RIOpen_Hours"];
      const hiddenfields = ["AssociatedCR_Key", "MLMRegion_Key", "MLMProgramRI_Key", "TransferredPM_Flg", "Opportunity_Txt", "RiskProbability_Key", "POC_Nm", "POC_Department"];
      var projectfields, projectfieldnames, rifields, excelfields, centerfields;

      const setlists = () => {
  projectfields = (mode == "program") ? ["EPSProject_Nm", "Subprogram_nm", "EPSProject_Owner", "MLMRegion_Cd", "Market_Cd", "EPS_Location_Cd"]
      : ["EPSProject_Nm", "EPS_Location_Cd", "EPSProject_Owner", "SubMLMProgram_Nm"];
  projectfieldnames = (mode == "program") ? [{name: "Project Name", width: "38"}, {name: "Subprogram", width: "5"}, {name: "Owner", width: "28"}, {name: "Region", width: "9"}, {name: "Market", width: "9"}, {name: "Facility", width: "9"}]
      : ["Project Name", "Facility", "Owner", "Subprogram"];

  rifields = (mode == "program") ? {"RiskAndIssue_Key": {name: "ID", width: 3}, "category": {name: "category", width: 3}, "Fiscal_Year": {name: "FY", width: 4}, "MLMProgram_Nm": {name: "Program", width: 9}, "subprogram": {name: "Subprogram", width: 9}, "MLMRegion_Cd": {name: "Region", width: 6}, "LastUpdateBy_Nm": {name: "Owner", width: 5}, "ImpactLevel_Nm": {name: "Impact Level", width: 5}, "RIDescription_Txt": {name: "Description", width: 17}, actionplandate: {name: "Action Plan Date", width: 6}, age: {name: "Age", width: 3}, "ActionPlanStatus_Cd": {name: "Action Plan", width: 17}, "ForecastedResolution_Dt": {name: "Forecast Res Date", width: 6}, "ResponseStrategy_Cd": {name: "Response Strategy", width: 3}, "RIOpen_Hours": {name: "Open Duration", width: 5}, "RIActive_Flg": {name: "Status", width: 5}} 
    : (mode == "portfolio") ? {"RiskAndIssue_Key": {name: "ID", width: 3}, "category": {name: "category", width: 3}, "Fiscal_Year": {name: "FY", width: 3}, "MLMProgram_Nm": {name: "Programs", width: 9}, programcount: {name: "Program Count", width: 2}, "subprogram": {name: "Sub-program", width: 9}, "MLMRegion_Cd": {name: "Region", width: 6}, "LastUpdateBy_Nm": {name: "Owner", width: 6}, "ImpactLevel_Nm": {name: "Impact", width: 6}, "RIDescription_Txt": {name: "Description", width: 18}, actionplandate: {name: "Action Plan Date", width: 6}, age: {name: "Age", width: 3}, "ActionPlanStatus_Cd": {name: "Action Plan", width: 18}, "ForecastedResolution_Dt": {name: "Forecast Res Date", width: 5}, "ResponseStrategy_Nm": {name: "Response Strategy", width: 4}, "RIOpen_Hours": {name: "Open Duration", width: 6}, "RIActive_Flg": {name: "Status", width: 4}}
    : {"RiskAndIssue_Key": "ID", "RI_Nm": "R/I Name", "RIType_Cd": "Type", "EPSProject_Nm": "Project Name", "RIIncrement_Num": "Group ID", "EPSProgram_Nm": "Program", "EPSSubprogram_Nm": "Subprogram", "LastUpdateBy_Nm": "Owner", "Fiscal_Year": "FY", "EPSRegion_Cd": "Region", "EPSMarket_Cd": "Market", "EPSFacility_Cd": "Facility", "ImpactLevel_Nm": "Impact", "RIDescription_Txt": "Description", actionplandate: "Action Plan Date", age: "Age", "ActionPlanStatus_Cd": "Action Plan", "ForecastedResolution_Dt": "Forecast Res Date", "ResponseStrategy_Nm": "Response Strategy", "RIOpen_Hours": "Open Duration"};

  excelfields = (mode == "program") ? {"Fiscal_Year": "FY",	"RIActive_Flg": "Status", "MLMProgram_Nm": "Program", "subprogram": "Subprogram", "LastUpdateBy_Nm": "Owner", "RiskAndIssue_Key": "ID", "RIType_Cd": "Type", "MLMRegion_Cd": "Region", "regioncount": "Reg Count", "category": "Category", "projectcount": "Assoc Proj Count", "RI_Nm": "Name", "ScopeDescriptor_Txt": "Descriptor", "RIDescription_Txt": "Description", "driver": "Driver", "ImpactArea_Nm": "Impact Area", "ImpactLevel_Nm": "Impact Level",	"RiskProbability_Nm": "Probability", "ResponseStrategy_Nm": "Response", "POC_Nm": "POC Name", "POC_Department": "POC Group", age: "Age", actionplandate: "Action Plan Date", "ActionPlanStatus_Cd": "Action Plan", "ForecastedResolution_Dt": "Resolution Date", "RIOpen_Hours": "Duration", "AssociatedCR_Key": "CR", "RaidLog_Flg": "Portfolio Notified", "RiskRealized_Flg": "Risk Realized", "RIClosed_Dt": "Date Closed", "Created_Ts": "Creation Date", "LastUpdateBy_Nm": "Last Update By", "Last_Update_Ts": "Last Update Date", "quartercreated": "Quarter Created", "quarterclosed": "Quarter Closed", "monthcreated": "Month Created", "monthclosed": "Month Closed"} 
    : (mode == "portfolio") ? {"Fiscal_Year": "FY",	"RIActive_Flg": "Status", "MLMProgram_Nm": "Program", programcount: "Program Count", "subprogram": "Subprogram", MLMRegion_Cd: "Regions", "LastUpdateBy_Nm": "Owner", "RiskAndIssue_Key": "ID", "RIType_Cd": "Type", "category": "Category", "projectcount": "Proj Count", "RI_Nm": "Name", "ScopeDescriptor_Txt": "Descriptor", "RIDescription_Txt": "Description", "driver": "Driver", "ImpactArea_Nm": "Impact Area", "ImpactLevel_Nm": "Impact Level",	"RiskProbability_Nm": "Probability", "ResponseStrategy_Nm": "Response", "POC_Nm": "POC Name", "POC_Department": "POC Group", age: "Age", actionplandate: "Action Plan Date", "ActionPlanStatus_Cd": "Action Plan", "ForecastedResolution_Dt": "Resolution Date", "RIOpen_Hours": "Duration", "AssociatedCR_Key": "CR", "RaidLog_Flg": "Portfolio Notified", "RiskRealized_Flg": "Risk Realized", "RIClosed_Dt": "Date Closed", "Created_Ts": "Creation Date", "LastUpdateBy_Nm": "Last Update By", "Last_Update_Ts": "Last Update Date", "quartercreated": "Quarter Created", "quarterclosed": "Quarter Closed", "monthcreated": "Month Created", "monthclosed": "Month Closed"}
    : {"Fiscal_Year": "Fiscal Year", "RiskAndIssue_Key": "ID", "RI_Nm": "Name", "RIType_Cd": "Type", "RIIncrement_Num": "Group ID", groupcount: "Proj Group Count", grouptype: "Group Type", "EPSProject_Nm": "Project Name", "EPSRegion_Abb": "Region", "RIActive_Flg": "Status", "EPSProgram_Nm": "Program", "EPSSubprogram_Nm": "Sub-Program", "ImpactArea_Nm": "Impact Area", "ImpactLevel_Nm": "Impact Level",	"RiskProbability_Nm": "Probability", "LastUpdateBy_Nm": "Owner", "ScopeDescriptor_Txt": "Descriptor", "RIDescription_Txt": "Description", "driver": "Driver", "ResponseStrategy_Nm": "Response", "POC_Nm": "POC Name", "POC_Department": "POC Group", "TransferredPM_Flg": "Transferred to PDM", "AssociatedCR_Key": "CR", "AssociatedCR_Key": "CR", "RiskRealized_Flg": "Risk Realized", age: "Age", actionplandate: "Action Plan Date", "ActionPlanStatus_Cd": "Action Plan", "RIOpen_Hours": "Duration", "Created_Ts": "Creation Date", "quartercreated": "Quarter Created", "monthcreated": "Month Created", "LastUpdateBy_Nm": "Last Update By", "Last_Update_Ts": "Last Update Date", "ForecastedResolution_Dt": "Resolution Date", "RIClosed_Dt": "Date Closed", "quarterclosed": "Quarter Closed", "monthclosed": "Month Closed"};
  centerfield = ["Fiscal_Year", "ID", "regioncount", "projectcount", "RIIncrement_Num"];
  
  changelog = {"changelogdate": "Change Log Requested Date", "RiskAndIssue_Key": "Risk ID", "RI_Nm": "Risk Name", "EPSProject_Nm": "Project Name", "EPSProgram_Nm": "Program", "EPSRegion_Abb": "Region", "EPSMarket_Cd": "Market", "EPSFacility_Cd": "Facility", "ImpactLevel_Nm": "Impact", LastUpdateBy_Nm: "Requestor", RequestAction_Nm: "Requested Action", Reason_Txt: "Reason", "PRJI_Estimated_Act_Ts": "Estimated Activation Date", "PRJI_Estimated_Mig_Ts": "Estimated Migration Date", EPSProgramManager: "Program Manager"};
  
}



</script>