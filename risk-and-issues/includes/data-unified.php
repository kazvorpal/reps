<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

  function fixutf8($target) {
    return (gettype($target) == "string") ? (utf8_encode($target)) : ($target);
  }  

  $mode = (stripos($_SERVER['REQUEST_URI'], "program")) ? "program" : "project";

  function executequery($data_conn, $sqlstr) {
    ini_set('mssql.charset', 'UTF-8');
    $query = sqlsrv_query($data_conn, $sqlstr);
    if ($query === false) {
      if (($errors = sqlsrv_errors()) != null) {
        foreach ($errors as $error) {
          echo "SQLSTATE: " . $error['SQLSTATE'] . "<br />";
          echo "code: " . $error['code'] . "<br />";
          echo "message: " . $error['message'] . "<br />";
        }
      }
    }
    return $query;
  }

  $mt = [];
  $mt["Start"] = microtime(true);
  $sqlstr = "select * from RI_MGT.fn_GetListOfAllRiskAndIssue(default) order by RiskAndIssue_Key";
  if($riquery = executequery($data_conn, $sqlstr)) {
    $rirows = array();
    $count = 1;
    while($row = sqlsrv_fetch_array($riquery, SQLSRV_FETCH_ASSOC)) {
      if (isset($row['Global_Tag']) && is_string($row['Global_Tag'])) {
        $row['Global_Tag'] = json_decode(stripslashes($row['Global_Tag']), true);
      }
      $rirows[] = array_map("fixutf8", $row);
    }
  }
  $mt[$sqlstr] = microtime(true);
  $out = [];
  // Break the results into Portfolios, Programs, and Projects (via the level code)
  foreach ($rirows as $row) {
    $level = $row['RILevel_Cd'];
    $out[$level][] = $row;
  }
  // Initialize $p4plist with keys from $programrows and empty arrays as values
  $p4plist = array();
  foreach ($out["Program"] as $row) {
    if ($row["MLMProgramRI_Key"] != '') {
      $key = $row["RiskAndIssue_Key"] . "-" . $row["MLMProgramRI_Key"];
      $p4plist[$key] = [];
    }
  }

  $sqlstr = "select * from RI_Mgt.fn_GetListOfAssociatedProjectsForProgramRIKey(default, default, default)";
  if ($p4pquery = executequery($data_conn, $sqlstr)) {
    while ($p4prow = sqlsrv_fetch_array($p4pquery, SQLSRV_FETCH_ASSOC)) {
      $fixedRow = array_map("fixutf8", $p4prow);
      $key = $fixedRow["RiskAndIssue_Key"] . "-" . $fixedRow["ProgramRI_Key"];
      
      if (array_key_exists($key, $p4plist)) {
        $p4plist[$key][] = $fixedRow;
      }
    }
  }
  $mt[$sqlstr] = microtime(true);

  $sublist = array();
  foreach ($out["Program"] as $row)  {
      $sqlstr = "select * from  RI_Mgt.fn_GetListSubProgramsforRIKey(". $row["RiskAndIssue_Key"] ." ,". $row["RIActive_Flg"] .")";
      $subrows = array();
      if($subquery = executequery($data_conn, $sqlstr)) {
        $count = 1;
        $checker = 0;
        while($subrow = sqlsrv_fetch_array($subquery, SQLSRV_FETCH_ASSOC)) {
          $subrows[] = array_map("fixutf8", $subrow);
          $checker = 1;
        }
      }
      $sublist[$row["RiskAndIssue_Key"]] = $subrows;
    }

  $mt[$sqlstr] = microtime(true);
  foreach ($out["Portfolio"] as $row)  {
      $sqlstr = "select * from  RI_Mgt.fn_GetListSubProgramsforRIKey(". $row["RiskAndIssue_Key"] ." ,". $row["RIActive_Flg"] .")";
      $subrows = array();
      if($subquery = executequery($data_conn, $sqlstr)) {
        $count = 1;
        $checker = 0;
        while($subrow = sqlsrv_fetch_array($subquery, SQLSRV_FETCH_ASSOC)) {
          $subrows[] = array_map("fixutf8", $subrow);
          $checker = 1;
        }
      }
      $sublist[$row["RiskAndIssue_Key"]] = $subrows;
  }
  $mt[$sqlstr] = microtime(true);
  $sqlstr = "select * from [RI_MGT].[fn_GetListOfProgramsForPortfolioRI_Key] (-1)";
  if($portfolioprograms = executequery($data_conn, $sqlstr)) {
    $portfolioprogramsrows = array();
    $count = 1;
    while($row = sqlsrv_fetch_array($portfolioprograms, SQLSRV_FETCH_ASSOC)) {
      $portfolioprogramsrows[] = array_map("fixutf8", $row);
    }
  }

  $mt[$sqlstr] = microtime(true);
  $sqlstr = "select * from RI_MGT.fn_GetListOfLocationsForEPSProject(-1)";
  if($locationquery = executequery($data_conn, $sqlstr)) {
    $locationrows = array();
    $count = 1;
    while($locationrow = sqlsrv_fetch_array($locationquery, SQLSRV_FETCH_ASSOC)) {
      $locationrows[] = array_map("fixutf8", $locationrow);
    }
  }
    
  $mt[$sqlstr] = microtime(true);
  $mangerlist = array();
  foreach ($out["Project"] as $row)  {
    if($row["MLMProgramRI_Key"] != '') {
      // Get OWNERS //
      $sqlstr = "select * from RI_MGT.fn_GetListOfOwnersInfoForProgram(". $row["Fiscal_Year"] ." ,'". $row["MLMProgram_Nm"] ."')";
      if($mangerquery = executequery($data_conn, $sqlstr)) {
        $count = 1;
        $mangerrows = array();
        while($mangerrow = sqlsrv_fetch_array($mangerquery, SQLSRV_FETCH_ASSOC)) {
          $mangerrows[] = array_map("fixutf8", $mangerrow);
        }
      }
      $mangerlist[$row["Fiscal_Year"]."-".$row["MLMProgram_Key"]] = $mangerrows;
    }
  }
  
  $mt[$sqlstr] = microtime(true);
  $sqlstr = "select * from RI_MGT.fn_GetListOfDriversForriLogKey(1)";
  if($driverquery = executequery($data_conn, $sqlstr)) {
    $driverrows = array();
    $count = 1;
    while($driverrow = sqlsrv_fetch_array($driverquery, SQLSRV_FETCH_ASSOC)) {
      $driverrows[$driverrow["RiskAndIssueLog_Key"]] = array_map("fixutf8", $driverrow);
    }
  }
  $mt[$sqlstr] = microtime(true);
  // Get Drivers
  $sqlstr = "select * from RI_MGT.fn_GetListOfDriversForriLogKey(0)";
  if($driverquery = executequery($data_conn, $sqlstr)) {
    $count = 1;
    while($driverrow = sqlsrv_fetch_array($driverquery, SQLSRV_FETCH_ASSOC)) {
      $driverrows[$driverrow["RiskAndIssueLog_Key"]] = array_map("fixutf8", $driverrow);
    }
  }
  
  $mt[$sqlstr] = microtime(true);
  // Action Plan Data
  $sqlstr = "select * from RI_Mgt.fn_GetListOfLastUpDtForActionPlanAndPIChangeLogs()
  where [Source] = 'ACTNPlan'
  Order By RiskandIssue_Key DESC";
  if($apquery = executequery($data_conn, $sqlstr)) {
    $aprows = array();
    $count = 1;
    while($aprow = sqlsrv_fetch_array($apquery, SQLSRV_FETCH_ASSOC)) {
      $aprows[$aprow["RiskAndIssue_Key"]] = array_map("fixutf8", $aprow);
    }
  }
  $mt[$sqlstr] = microtime(true);
  // Change Log Data
  $sqlstr = "select * from RI_Mgt.fn_GetListOfLastUpDtForActionPlanAndPIChangeLogs()
  where [Source] = 'PRJILog'
  Order By RiskandIssue_Key DESC";
  if($logquery = executequery($data_conn, $sqlstr)) {
    $logrows = array();
    $count = 1;
    while($logrow = sqlsrv_fetch_array($logquery, SQLSRV_FETCH_ASSOC)) {
      $logrows[$logrow["RiskAndIssue_Key"]] = array_map("fixutf8", $logrow);
    }
  }
    
  $mt[$sqlstr] = microtime(true);

  function echo_bars($mt) {
      $prevKey = "Start";
      
      // Calculate the maximum log time to normalize the bar lengths
      $max_log_time = max(array_map(function($key) use ($mt, $prevKey) {
          if ($key === "Start") return 0;
          return log1p($mt[$key] - $mt[$prevKey]);
      }, array_keys($mt)));
      
      foreach ($mt as $key => $time) {
          if ($key === "Start") continue;  // Skip the initial time

          $interval_time = $time - $mt[$prevKey];
          $total_time = $time - $mt["Start"];
          
          // Calculate the bar length
          $bar_length = intval(log1p($interval_time) / $max_log_time * 50);  // 50 units long bar for max time

          echo "Key:<br/> " . $key . "<br/>";
          echo "<b>" . $interval_time . "</b><br/>";
          echo str_repeat("=", $bar_length) . "<br/>";  // ASCII bar
          echo "<i>" . $total_time . "</i><br/><br/>";
          
          $prevKey = $key;  // Update the previous key for the next iteration
      }
  }

  // echo_bars($mt);

  $riout = json_encode($rirows);
    $logout = json_encode($logrows);
    $apout = json_encode($aprows);
    $subout = json_encode($sublist);
    $p4pout = json_encode($p4plist);
    $mangerout = json_encode($mangerlist);
    $driverout = json_encode($driverrows);
    $locationout = json_encode($locationrows);
    $portfolioprogramsout = json_encode($portfolioprogramsrows);
    $projectout = json_encode($out["Project"]);
    $programout = json_encode($out["Program"]);
    $portfolioout = json_encode($out["Portfolio"]);
  
?>
<script>
    let rilist = <?= $riout ?>;
    let projects = <?= $projectout ?>;
    let programs =<?= $programout ?>;
    let portfolios =<?= $portfolioout ?>;
</script>
<!-- this is portfolio closed -->
<script>

    const mangerlist = <?= $mangerout ?>;
    const driverlist = <?= $driverout ?>;
    const locationlist = <?= $locationout ?>;
    const p4plist = <?= $p4pout ?>;
    const sublist = <?= $subout ?>;
    const portfolioprograms = <?= $portfolioprogramsout ?>;
    const aplist = <?= $apout ?>;
    const loglist = <?= $logout ?>;

    defaulttags = ["elvis", "fubar", "moo", "kluge", "sesquipedalian", "concommittal", "magnificat", "wap"]

function getRandomTags() {
    const numOfTags = Math.floor(Math.random() * defaulttags.length) + 1;
    const tags = [];
    for(let i = 0; i < numOfTags; i++) {
        const randomIndex = Math.floor(Math.random() * defaulttags.length);
        const randomTag = defaulttags[randomIndex];
        if (!tags.includes(randomTag)) {
            tags.push(randomTag);
        }
    }
    return tags;
}

const cleandata = (list) => {
    Object.keys(list).forEach(key => {
        if(list[key].RiskAndIssue_Key == "undefined") {
            delete list[key];
        }
    })
    return list;
}

    var ridata, rifiltered;
    const setdata = () => {
      ridata = rifiltered = "";
        if (mode == "program") {
          const localportfolios = portfolios.filter(o => {
              return o.RaidLog_Flg == 0;
            })
            ridata = cleandata(programs);
        } else if (mode == "portfolio") {
            const globalprograms = programs.filter(o => {
              return o.RaidLog_Flg == 1;
            })
            ridata = cleandata(portfolios).concat(globalprograms)
        } else {
            ridata = projects;
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

  rifields = (mode == "program") ? {"RiskAndIssue_Key": {name: "ID", width: 3}, "category": {name: "category", width: 3}, "Fiscal_Year": {name: "FY", width: 4}, "MLMProgram_Nm": {name: "Program", width: 9}, "subprogram": {name: "Subprogram", width: 9}, "MLMRegion_Cd": {name: "Region", width: 6}, "LastUpdateBy_Nm": {name: "Owner", width: 5}, "ImpactLevel_Nm": {name: "Impact Level", width: 5}, ScopeDescriptor_Txt: {name: "Descriptor", width: 6}, "RIDescription_Txt": {name: "Description", width: 17, align: "text-left"}, actionplandate: {name: "Action Plan Date", width: 6}, age: {name: "Age", width: 3}, "ActionPlanStatus_Cd": {name: "Action Plan", width: 17, align: "text-left"}, "ForecastedResolution_Dt": {name: "Forecast Res Date", width: 6}, "ResponseStrategy_Cd": {name: "Response Strategy", width: 3}, "RIOpen_Hours": {name: "Open Duration", width: 5}, Global_Tag: {name: "Tags", width:8}} 
    : (mode == "portfolio") ? {"RiskAndIssue_Key": {name: "ID", width: 3}, "category": {name: "category", width: 3}, "Fiscal_Year": {name: "FY", width: 3}, "MLMProgram_Nm": {name: "Programs", width: 9}, programcount: {name: "Program Count", width: 2}, "subprogram": {name: "Sub-program", width: 9}, "MLMRegion_Cd": {name: "Region", width: 6}, "LastUpdateBy_Nm": {name: "Owner", width: 6}, "ImpactLevel_Nm": {name: "Impact", width: 6}, ScopeDescriptor_Txt: {name: "Descriptor", width: 6}, "RIDescription_Txt": {name: "Description", width: 18, align: "text-left"}, actionplandate: {name: "Action Plan Date", width: 6}, age: {name: "Age", width: 3}, "ActionPlanStatus_Cd": {name: "Action Plan", width: 18, align: "text-left"}, "ForecastedResolution_Dt": {name: "Forecast Res Date", width: 5}, "ResponseStrategy_Nm": {name: "Response Strategy", width: 4}, "RIOpen_Hours": {name: "Open Duration", width: 6}, Global_Tag: {name: "Tags", width:8}}
    : {"RiskAndIssue_Key": "ID", "RI_Nm": "R/I Name", "RIType_Cd": "Type", "EPSProject_Nm": "Project Name", "RIIncrement_Num": "Group ID", "EPSProgram_Nm": "Program", "EPSSubprogram_Nm": "Subprogram", "LastUpdateBy_Nm": "Owner", "Fiscal_Year": "FY", "EPSRegion_Cd": "Region", "EPSMarket_Cd": "Market", "EPSFacility_Cd": "Facility", "ImpactLevel_Nm": "Impact", "RIDescription_Txt": "Description", actionplandate: "Action Plan Date", age: "Age", "ActionPlanStatus_Cd": "Action Plan", "ForecastedResolution_Dt": "Forecast Res Date", "ResponseStrategy_Nm": "Response Strategy", "RIOpen_Hours": "Open Duration"};

  excelfields = (mode == "program") ? {"Fiscal_Year": "FY",	"RIActive_Flg": "Status", "MLMProgram_Nm": "Program", "subprogram": "Subprogram", "LastUpdateBy_Nm": "Owner", "RiskAndIssue_Key": "ID", "RIType_Cd": "Type", "MLMRegion_Cd": "Region", "regioncount": "Reg Count", "category": "Category", "projectcount": "Assoc Proj Count", "RI_Nm": "Name", "ScopeDescriptor_Txt": "Descriptor", "RIDescription_Txt": "Description", "driver": "Driver", "ImpactArea_Nm": "Impact Area", "ImpactLevel_Nm": "Impact Level",	"RiskProbability_Nm": "Probability", "ResponseStrategy_Nm": "Response", "POC_Nm": "POC Name", "POC_Department": "POC Group", age: "Age", actionplandate: "Action Plan Date", "ActionPlanStatus_Cd": "Action Plan", "ForecastedResolution_Dt": "Resolution Date", "RIOpen_Hours": "Duration", "AssociatedCR_Key": "CR", "RaidLog_Flg": "Portfolio Notified", "RiskRealized_Flg": "Risk Realized", "RIClosed_Dt": "Date Closed", "Created_Ts": "Creation Date", "LastUpdateBy_Nm": "Last Update By", "Last_Update_Ts": "Last Update Date", "quartercreated": "Quarter Created", "quarterclosed": "Quarter Closed", "monthcreated": "Month Created", "monthclosed": "Month Closed"} 
    : (mode == "portfolio") ? {"Fiscal_Year": "FY",	"RIActive_Flg": "Status", "MLMProgram_Nm": "Program", programcount: "Program Count", "subprogram": "Subprogram", MLMRegion_Cd: "Regions", "LastUpdateBy_Nm": "Owner", "RiskAndIssue_Key": "ID", "RIType_Cd": "Type", "category": "Category", "projectcount": "Proj Count", "RI_Nm": "Name", "ScopeDescriptor_Txt": "Descriptor", "RIDescription_Txt": "Description", "driver": "Driver", "ImpactArea_Nm": "Impact Area", "ImpactLevel_Nm": "Impact Level",	"RiskProbability_Nm": "Probability", "ResponseStrategy_Nm": "Response", "POC_Nm": "POC Name", "POC_Department": "POC Group", age: "Age", actionplandate: "Action Plan Date", "ActionPlanStatus_Cd": "Action Plan", "ForecastedResolution_Dt": "Resolution Date", "RIOpen_Hours": "Duration", "AssociatedCR_Key": "CR", "RaidLog_Flg": "Portfolio Notified", "RiskRealized_Flg": "Risk Realized", "RIClosed_Dt": "Date Closed", "Created_Ts": "Creation Date", "LastUpdateBy_Nm": "Last Update By", "Last_Update_Ts": "Last Update Date", "quartercreated": "Quarter Created", "quarterclosed": "Quarter Closed", "monthcreated": "Month Created", "monthclosed": "Month Closed"}
    : {"Fiscal_Year": "Fiscal Year", "RiskAndIssue_Key": "ID", "RI_Nm": "Name", "RIType_Cd": "Type", "RIIncrement_Num": "Group ID", groupcount: "Proj Group Count", grouptype: "Group Type", "EPSProject_Nm": "Project Name", "EPSRegion_Abb": "Region", "RIActive_Flg": "Status", "EPSProgram_Nm": "Program", "EPSSubprogram_Nm": "Sub-Program", "ImpactArea_Nm": "Impact Area", "ImpactLevel_Nm": "Impact Level",	"RiskProbability_Nm": "Probability", "LastUpdateBy_Nm": "Owner", "ScopeDescriptor_Txt": "Descriptor", "RIDescription_Txt": "Description", "driver": "Driver", "ResponseStrategy_Nm": "Response", "POC_Nm": "POC Name", "POC_Department": "POC Group", "TransferredPM_Flg": "Transferred to PDM", "AssociatedCR_Key": "CR", "AssociatedCR_Key": "CR", "RiskRealized_Flg": "Risk Realized", age: "Age", actionplandate: "Action Plan Date", "ActionPlanStatus_Cd": "Action Plan", "RIOpen_Hours": "Duration", "Created_Ts": "Creation Date", "quartercreated": "Quarter Created", "monthcreated": "Month Created", "LastUpdateBy_Nm": "Last Update By", "Last_Update_Ts": "Last Update Date", "ForecastedResolution_Dt": "Resolution Date", "RIClosed_Dt": "Date Closed", "quarterclosed": "Quarter Closed", "monthclosed": "Month Closed"};
  centerfield = ["Fiscal_Year", "ID", "regioncount", "projectcount", "RIIncrement_Num"];
  
  changelog = {"changelogdate": "Change Log Requested Date", "RiskAndIssue_Key": "Risk ID", "RI_Nm": "Risk Name", "EPSProject_Nm": "Project Name", "EPSProgram_Nm": "Program", "EPSRegion_Abb": "Region", "EPSMarket_Cd": "Market", "EPSFacility_Cd": "Facility", "ImpactLevel_Nm": "Impact", LastUpdateBy_Nm: "Requestor", RequestAction_Nm: "Requested Action", Reason_Txt: "Reason", "PRJI_Estimated_Act_Ts": "Estimated Activation Date", "PRJI_Estimated_Mig_Ts": "Estimated Migration Date", EPSProgramManager: "Program Manager"};
  
}

</script>