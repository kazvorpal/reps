<?php

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
        // print_r($row);
        // print($row["RiskAndIssueLog_Key"]);
        // print("<br/>");
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
          // print_r($row);
          // print($row["RiskAndIssueLog_Key"]);
          // print("<br/>");
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

      // $p4pclosedlist = array();
      // foreach ($rows as $row)  {
      //   if($row["MLMProgramRI_Key"] != '') {
      //     // Get PROJECTS //
      //     $sqlstr = "select * from RI_Mgt.fn_GetListOfAssociatedProjectsForProgramRIKey(". $row["RiskAndIssue_Key"] ." ,". $row["MLMProgramRI_Key"] .", 0)";
      //     ini_set('mssql.charset', 'UTF-8');
      //     $p4pclosedquery = sqlsrv_query($data_conn, $sqlstr);
      //     if($p4pclosedquery === false) {
      //       if(($error = sqlsrv_errors()) != null) {
      //         print_r($error);
      //         foreach($error as $errors) {
      //           echo "SQLSTATE: ".$errors[ 'SQLSTATE']."<br />";
      //           echo "code: ".$errors[ 'code']."<br />";
      //           echo "message: ".$errors[ 'message']."<br />";
      //         }
      //       }
      //     } else {
      //       $count = 1;
      //       $p4pclosedrows = array();
      //       $checker = 0;
      //       while($p4pclosedrow = sqlsrv_fetch_array($p4pclosedquery, SQLSRV_FETCH_ASSOC)) {
      //         $p4pclosedrows[] = array_map("fixutf8", $p4pclosedrow);
      //         $checker = 1;
      //       }
      //     }
      //     $p4pclosedlist[$row["RiskAndIssue_Key"]."-".$row["MLMProgramRI_Key"]] = $p4pclosedrows;
      //   }
      // }

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
          $driverrows[] = array_map("fixutf8", $driverrow);
        }
      }
    

      // $driverlist = array();
      // foreach ($rows as $row)  {
      //   if($row["MLMProgramRI_Key"] != '') {
      //     // Get OWNERS //
      //     $sqlstr = "select * from RI_MGT.fn_GetListOfDriversForriLogKey(". $row["RiskAndIssueLog_Key"] ." , " . $row["RIActive_Flg"] . ")";
      //     // print $sqlstr . "<br>";
      //     ini_set('mssql.charset', 'UTF-8');
      //     $driverquery = sqlsrv_query($data_conn, $sqlstr);
      //     if($driverquery === false) {
      //       if(($error = sqlsrv_errors()) != null) {
      //         foreach($errors as $error) {
      //           echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
      //           echo "code: ".$error[ 'code']."<br />";
      //           echo "message: ".$error[ 'message']."<br />";
      //         }
      //       }
      //     } else {
      //       $count = 1;
      //       $driverrows = array();
      //       while($driverrow = sqlsrv_fetch_array($driverquery, SQLSRV_FETCH_ASSOC)) {
      //         $driverrows[] = array_map("fixutf8", $driverrow);
      //       }
      //     }
      //     $driverlist[$row["RiskAndIssueLog_Key"]] = $driverrows;
      //   }
      // }
        

      $p4pout = json_encode($p4plist);
      $mangerout = json_encode($mangerlist);
      $driverout = json_encode($driverrows);
      $locationout = json_encode($locationrows);
      $jsonout = json_encode($rows);
      $closedout = json_encode($closedrows);
      
      }

?>