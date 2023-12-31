<?php

  function fixutf8($target) {
    return (gettype($target) == "string") ? (utf8_encode($target)) : ($target);
  }  

  $mode = (stripos($_SERVER['REQUEST_URI'], "project")) ? "project" : "program";

  $sqlstr = "select * from RI_MGT.fn_GetListOfAllRiskAndIssue(1) where riLevel_cd = '$mode'";
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
        
    $sqlstr = "select * from RI_MGT.fn_GetListOfAllRiskAndIssue(0) where riLevel_cd = '$mode'";
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


    $p4plist = array();
    foreach ($rows as $row)  {
      if($row["MLMProgramRI_Key"] != '') {
        // Get PROJECTS //
        $sqlstr = "select * from RI_Mgt.fn_GetListOfAssociatedProjectsForProgramRIKey(". $row["RiskAndIssue_Key"] ." ,". $row["MLMProgramRI_Key"] .", -1)";
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
        $p4plist[$row["RiskAndIssue_Key"]."-".$row["MLMProgramRI_Key"]] = $p4prows;
      }
    }

    $mangerlist = array();
    foreach ($rows as $row)  {
      if($row["MLMProgramRI_Key"] != '') {
        // Get OWNERS //
        $programname = ($mode=="program") ? "MLMProgram_Nm" : "EPSProgram_Nm";
        $sqlstr = "select * from RI_MGT.fn_GetListOfOwnersInfoForProgram(". $row["Fiscal_Year"] ." ,'". $programname ."')";
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
        $driverrows[] = array_map("fixutf8", $driverrow);
      }
    }

    $p4pout = json_encode($p4plist);
    $mangerout = json_encode($mangerlist);
    $driverout = json_encode($driverrows);
    $locationout = json_encode($locationrows);
    $jsonout = json_encode($rows);
    $closedout = json_encode($closedrows);
  
  }

?>