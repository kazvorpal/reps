<?php

  function fixutf8($target) {
    return (gettype($target) == "string") ? (utf8_encode($target)) : ($target);
  }  

  $mode = (stripos($_SERVER['REQUEST_URI'], "program")) ? "program" : "project";


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
        // Get PROJECTS //
        $sqlstr = "select * from RI_Mgt.fn_GetListOfAssociatedProjectsForProgramRIKey(". $row["RiskAndIssue_Key"] ." ,". $row["MLMProgramRI_Key"] .", -1)";
         //echo $sqlstr . "<br/>";
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
  }

  //    $sqlstr = "select * from RI_MGT.fn_GetListOfAllRiskAndIssue(1) where riLevel_cd = 'portfolio'";
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

    //     $sqlstr = "select * from RI_MGT.fn_GetListOfAllRiskAndIssue(0) where riLevel_cd = 'portfolio'";
    $sqlstr = "select * from RI_MGT.fn_GetListOfAllRiskAndIssue(0) where riLevel_cd in ('portfolio')";
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
      $driverrows = array();
      $count = 1;
      while($driverrow = sqlsrv_fetch_array($driverquery, SQLSRV_FETCH_ASSOC)) {
        $driverrows[$driverrow["RiskAndIssueLog_Key"]] = array_map("fixutf8", $driverrow);
        // $driverrows[] = array_map("fixutf8", $driverrow);
      }
    }

    $p4pout = json_encode($p4plist);
    $mangerout = json_encode($mangerlist);
    $driverout = json_encode($driverrows);
    $locationout = json_encode($locationrows);
    $portfolioprogramsout = json_encode($portfolioprogramsrows);
    $projectout = json_encode($rows);
    $closedout = json_encode($closedrows);
    $programout = json_encode($programrows);
    $closedprogramout = json_encode($closedprogramrows);
    $portfolioout = json_encode($portfoliorows);
    $closedportfolioout = json_encode($closedportfoliorows);
  
  
  }

?>