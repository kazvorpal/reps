<?php 
//echo str_replace('  ', '&nbsp; ', nl2br(print_r($_POST, true)));
include ("../includes/functions.php");
include ("../db_conf.php");
include ("../data/emo_data.php");
include ("../sql/MS_Users.php");
include ("../sql/MS_Users_prg.php");
include ("../sql/update-time.php");

session_start();

$backhome= "";
if(isset($_SESSION["homebase"])) {
$backhome = $_SESSION["homebase"];
}

$unframe = "";
if(isset($_SESSION['unframe'])) {
$unframe = $_SESSION['unframe'];
}

    //DECLARE
    $global = $_POST['global'];

    $changeLogKey = (int)$_POST['changeLogKey'];
     if ($changeLogKey == 1){
        $changeLogName = "Initialize";
     } else if ($changeLogKey == 2) {
        $changeLogName = "Created";
     } else if ($changeLogKey == 3) {
        $changeLogName = "Closed";
     } else if ($changeLogKey == 4) {
        $changeLogName = "Updated";
     } else if ($changeLogKey == 5) {
        $changeLogName = "Deleted";
     }
    $userEmail = $row_winuser['Email'];
    $userName = $row_winuser['CCI_Alias'];
    $createdfrom = $_POST['createdFrom'];
    $usedButton = $_POST['submit2'];
    $userId = $_POST['userId']; // WINDOWS LOGIN NAME
    $formName = $_POST['formName']; // PRJR, PRJI, PRGI, PRGR 
    $formType = $_POST['formType']; // CREATE/UPDATE
    $lrpYear = (int)$_POST['fiscalYer']; // FISCAL YEAR OF THE PROJECT/PROGRAM
    $riTypeCode = $_POST['RIType']; // RISK OR ISSUE
    $name = $_POST['name']; // PROJECT NAME
    $drivers = $_POST['drivers'];
    $riLevel = $_POST['RILevel']; // PROJECT OR PROGRAM
    $impactArea = (int)$_POST['impactArea']; 
    $impactLevel = (int)$_POST['impactLevel'];
    $responseStrategy = $_POST['responseStrategy'];
    $assocProject = substr_replace($_POST['assocProjects'],"",-4); // MULTI 
        $emailAssocProj = str_replace(", ", ",",$drivers);//ASSOCIATED PROJECTS LIST FOR EMAIL // THIS IS DEAD
    $assocProgram = $_POST['program']; // USE ONLY FOR PROGRAM RISK OR ISSUE OTHERWISE EMPTY // MULTI AS OF 4/19
    $individual = $_POST['individual']; 
    $internalExternal = $_POST['internalExternal']; 
    $poc = $_POST['poc']; // POC Individual
    $pocFlag = (int)$_POST['pocFlag'];
    $descriptor = $_POST['descriptor']; 
    $description = $_POST['description'];
    $actionPlan = $_POST['actionPlan']; 
    $transfer2prgManager = $_POST['transfer2prgManager'];
    //ASSC CR KEY NULL IF EMPTY 3.1.2023 
    $asscCRKey = $_POST['assCRID']; 
    $project_nm = $_POST['project_nm'];

    $status = 1;
    if($changeLogKey ==3) {
        $status = 0;
    }

    $riOpenFlg = 1;
    if($changeLogKey == 3 || $changeLogKey == 5 ){
        $riOpenFlg = 0;
    }

    $riskProbability = NULL; // FOR RISK ONLY
    if(!empty($_POST['RiskProbability'])){
        $riskProbability = $_POST['RiskProbability'];
    }

    $opportunity = NULL; // Yes OR No FOR PROGRAM
    if ($riLevel == 'Program') {
        $opportunity = $_POST['opportunity'];
    }   

    $date = $_POST['date']; // FORCASTED RESOLUTION DATE NULL IF UNKNOWN
    if ($_POST['unknown'] == 'on') {
        $date = NULL;
    }

    $DateClosed = $_POST['DateClosed'];
    if (empty($_POST['DateClosed'])) {
        $DateClosed = NULL;
    }

    $riskRealized = $_POST['riskRealized'];

    $region = NULL; // ONLY FOR PROGRAM
    if (!empty($_POST['assocRegions'])){
        $region = $_POST['assocRegions'];
    }

    $closedByDate = $_POST['DateClosed'];

    $closedByUID = NULL; // USE ONLY IF CLOSING OTHERWISE NULL // USE FOR EDIT ONLY
    if($changeLogKey == 3 || $changeLogKey == 5){
        $closedByUID = $userId;
    }

    $raidLogx = $_POST['raidLog'];
    if($raidLogx == "No" || $raidLogx == "0"){
        $raidLog = 0;
    } 
    if($raidLogx == "Yes" || $raidLogx == "1"){
        $raidLog = 1;
    } 

    $assocProjectsKeys = $_POST['assocProjectsKeys']; //mutiple keys DONE
    $regionKeys = $_POST['regionKeys']; //multiple keys
    if($global == 1){
        $regionKeys = $_POST['assocRegions'];
    }
    
    //echo $regionKeys . "<br> . $assocProjectsKeys . <br>"; 
    $programs = $_POST['programs']; //program name

    $programKeys = $_POST['programKeys']; //single key
    if($global == 1) {
        $programKeys = $_POST['program'];
    }

    //CHANGE LOG REQUEST INFO
    $changeLogActionVal = NULL;
    $ChangeToPIChangeLog = 0;
    if(!empty($_POST['changeLogActionVal'])) {
        $changeLogActionVal = $_POST['changeLogActionVal'];
        $ChangeToPIChangeLog = 1;
    }
    $changeLogReason = $_POST['changeLogReason'];

    $EstActiveDate = NULL;
    if($_POST['EstActiveDate'] != ""){
    $EstActiveDate = $_POST['EstActiveDate'];
    }
    
    $EstMigrateDate = NULL;
    if($_POST['EstMigrateDate'] != ""){
    $EstMigrateDate = $_POST['EstMigrateDate'];
    }
    
    $riKeys = $_POST['RiskAndIssue_Key']; //Multiple keys seperated by comma
    $firstRIkeyx = explode(",",$_POST['RiskAndIssue_Key']); //create array for keys
    $firstRIkey = array_values($firstRIkeyx)[0]; //call first position of arrra
    //echo $firstRIkey; exit();

    $subprogram = $_POST['subprogram']; // array keys
    $portfolioType_Key = $_POST['portfolioType_Key'];

    if($riLevel == "Program") {
        $detailPage = "details-prg";
    } else {
        $detailPage = "details";
    }

    //LOOK UP KEY VALUES 
    // GET UID FROM PROJECT NAME
    $assocProjects_sql = explode(",", $assocProject);
    $asscProjIN = $assocProjects_sql[0];

    $sql_in = "SELECT* FROM [EPS].[ProjectStage] WHERE PROJ_NM IN ('$asscProjIN')";
    $stmt_in  = sqlsrv_query( $data_conn, $sql_in  ); 
    $row_in  = sqlsrv_fetch_array( $stmt_in , SQLSRV_FETCH_ASSOC);
        $uid = "";
        if(!empty($row_in['PROJ_ID'])) {
        $uid = $row_in['PROJ_ID'];
        }

    // IMPACT AREA
    $sql_imp_area = "SELECT* FROM RI_MGT.Impact_Area WHERE ImpactArea_Key = $impactArea";
    $stmt_imp_area  = sqlsrv_query( $data_conn, $sql_imp_area  ); 
    $row_imp_area  = sqlsrv_fetch_array( $stmt_imp_area , SQLSRV_FETCH_ASSOC);
    $impactArea2 = $row_imp_area['ImpactArea_Nm'];

    //IMPACT LEVEL
    $sql_imp_lvl = "SELECT* FROM RI_MGT.Impact_Level WHERE ImpactLevel_Key = $impactLevel";
    $stmt_imp_lvl = sqlsrv_query( $data_conn, $sql_imp_lvl );  
    $row_imp_lvl = sqlsrv_fetch_array( $stmt_imp_lvl, SQLSRV_FETCH_ASSOC);
    $impactLevel2 = $row_imp_lvl['ImpactLevel_Nm'];

    //RESPONSE STRATEGY
    $sql_resp_strg = "SELECT* FROM RI_MGT.Response_Strategy WHERE ResponseStrategy_Key = $responseStrategy";
    $stmt_resp_strg = sqlsrv_query( $data_conn, $sql_resp_strg );  
    $row_resp_strg = sqlsrv_fetch_array( $stmt_resp_strg, SQLSRV_FETCH_ASSOC);
    $responseStrategy2 = $row_resp_strg['ResponseStrategy_Nm'];

    //GET DRIVERS FROM ID'S
    $sql_risk_issue_driver = "SELECT Driver_Nm FROM [RI_MGT].[Driver] where Driver_Key in ($drivers)";
    $stmt_risk_issue_driver = sqlsrv_query( $data_conn, $sql_risk_issue_driver );
    $row_risk_issue_driver = sqlsrv_fetch_array( $stmt_risk_issue_driver, SQLSRV_FETCH_ASSOC);
    $emailDrivers = $row_risk_issue_driver['Driver_Nm'];

        //CREATES JSON ARRAY FOR MULTIPLE DRIVER - NOT IN USE
        //$json_array =  array();
        //while($row_risk_issue_driver = sqlsrv_fetch_array($stmt_risk_issue_driver, SQLSRV_FETCH_ASSOC)) {
        //    $json_array[] = $row_risk_issue_driver;
        //}
            //print(json_encode($json_array));
        //    $emailDrivers = json_encode($json_array) ; // you left off here 3.16.22 still not done
        //   $jd = json_decode($emailDrivers);

    //GET POC EMAIL
    $sql_poc = "SELECT TOP(1) * FROM [RI_MGT].[fn_GetListOfCurrentTaskPOC] (1) WHERE POC_Nm = '$individual'";
    $stmt_poc  = sqlsrv_query( $data_conn, $sql_poc  );  
    $row_poc  = sqlsrv_fetch_array( $stmt_poc , SQLSRV_FETCH_ASSOC);

    $pocEmail = "";
    if(!empty($row_poc ['POC_Email'])){
    $pocEmail = $row_poc ['POC_Email'];
    }

    $SPCode = NULL ;
    $SPMessage = NULL ;
    $SPBatch_Id = NULL ;

    $params = array(
        array($userId, SQLSRV_PARAM_IN),
        array($lrpYear, SQLSRV_PARAM_IN),
        array($riKeys, SQLSRV_PARAM_IN), //new - list of keys
        array($drivers, SQLSRV_PARAM_IN),
        array($regionKeys, SQLSRV_PARAM_IN), //new - list of keys
        array($programKeys, SQLSRV_PARAM_IN), //new - list of keys
        array($assocProjectsKeys, SQLSRV_PARAM_IN),// project key list / not for global
        array($impactArea, SQLSRV_PARAM_IN),
        array($impactLevel, SQLSRV_PARAM_IN),
        array($responseStrategy, SQLSRV_PARAM_IN),
        array($riskProbability, SQLSRV_PARAM_IN),
        array($individual, SQLSRV_PARAM_IN),
        array($internalExternal, SQLSRV_PARAM_IN),
        array($pocFlag, SQLSRV_PARAM_IN),
        array($changeLogKey, SQLSRV_PARAM_IN),
        array($asscCRKey, SQLSRV_PARAM_IN),
        array($riLevel, SQLSRV_PARAM_IN),
        array($riTypeCode, SQLSRV_PARAM_IN),
        array($opportunity, SQLSRV_PARAM_IN),
        array($description, SQLSRV_PARAM_IN),
        array($actionPlan, SQLSRV_PARAM_IN),
        array($closedByUID, SQLSRV_PARAM_IN), //user id if closed
        array($transfer2prgManager, SQLSRV_PARAM_IN),
        array($riskRealized, SQLSRV_PARAM_IN),
        array($riOpenFlg, SQLSRV_PARAM_IN),// 0 for closed
        array($raidLog, SQLSRV_PARAM_IN),
        array($date, SQLSRV_PARAM_IN), //forcasted resolution date
        array($DateClosed, SQLSRV_PARAM_IN),
        array($EstActiveDate, SQLSRV_PARAM_IN),
        array($EstMigrateDate, SQLSRV_PARAM_IN),
        array($subprogram, SQLSRV_PARAM_IN),
        array($global, SQLSRV_PARAM_IN), 
        array($portfolioType_Key, SQLSRV_PARAM_IN), 
        array($changeLogActionVal, SQLSRV_PARAM_IN),
        array($changeLogReason, SQLSRV_PARAM_IN),
        array($ChangeToPIChangeLog, SQLSRV_PARAM_IN),
        array(&$SPCode, SQLSRV_PARAM_OUT, SQLSRV_PHPTYPE_INT),
        array(&$SPMessage, SQLSRV_PARAM_OUT, null, SQLSRV_SQLTYPE_VARCHAR),
        array(&$SPBatch_Id, SQLSRV_PARAM_OUT, null, SQLSRV_SQLTYPE_VARCHAR)
        );

         // DEBUG CODE
            //echo "<br><br>";
            //echo str_replace("],[","]<br>[", json_encode($params)) ;
            //echo "<br><br>";
            //exit();

        //CALL THE PROCEDURE
        $tsql_callSP = "{CALL [RI_MGT].[sp_UpdateRiskandIssues](?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)}";

      //EXECUTE PROCEDDURE
    $stmt3 = sqlsrv_query( $data_conn, $tsql_callSP, $params);
    //$results3 = sqlsrv_execute($stmt3);
    //$row = sqlsrv_fetch_array($stmt3);
    
    //echo $row;

    if( $stmt3 === false )
    {
        echo "Error in executing statement 3.\n";
        die( print_r( sqlsrv_errors(), true));
    }

    if($global == 1 && $unframe == "0") {
        include ("../includes/menu.php");
    } 
    
    //$row_pcount = sqlsrv_fetch_array( $stmt3, SQLSRV_FETCH_ASSOC);
    //echo $stmt3['SPMessage'];

    sqlsrv_next_result($stmt3);
    echo '
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"> 
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 
  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
    <link rel="stylesheet" href="steps/style.css" type="text/css"> 
    
    <div class="container">       
            <div class="row bs-wizard" style="border-bottom:0;">
                <div class="col-xs-3 bs-wizard-step complete">
                  <div class="text-center bs-wizard-stepnum">STEP 1</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="bs-wizard-dot"></a>
                  <div class="bs-wizard-info text-center">Select Associated Projects</div>
                </div>
                
                <div class="col-xs-3 bs-wizard-step complete"><!-- complete -->
                  <div class="text-center bs-wizard-stepnum">STEP 2</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="bs-wizard-dot"></a>
                  <div class="bs-wizard-info text-center">Enter Risk or Issue Details</div>
                </div>
                
                <div class="col-xs-3 bs-wizard-step complete"><!-- complete -->
                  <div class="text-center bs-wizard-stepnum">STEP 3</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="bs-wizard-dot"></a>
                  <div class="bs-wizard-info text-center">Confirm Your Entry</div>
                </div>
                
                <div class="col-xs-3 bs-wizard-step complete"><!-- active -->
                  <div class="text-center bs-wizard-stepnum">STEP 4</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="bs-wizard-dot"></a>
                  <div class="bs-wizard-info text-center">Completed</div>
                </div>
            </div>
    </div>
    ';
// echo $SPCode;
    if($SPCode == 0) {
        $globalportbutton = '<a href="dashboard/?portfolio=&mode=portfolio" class="btn btn-primary" target="_parent">RAID Log</a>';
        $globalprogbutton = '<a href="dashboard/?program=&mode=program" class="btn btn-primary" target="_parent">Program Dashboard</a>';
        $globalprogportbutton = '<a href="dashboard/?program=&mode=program" class="btn btn-primary" target="_parent">Program Dashboard</a>  <a href="dashboard/?portfolio=&mode=portfolio" class="btn btn-primary" target="_parent">RAID Log</a>';
        $listbutton = '<a href=" ' . $backhome . '" class="btn btn-primary">Back to List</a>';

        echo '<br><br><br><h2 align="center">Risk and Issue ' . $changeLogName . '</h2><div align="center">Your Risk/Issue has been ' . $changeLogName. '<br>Risk and Issue ID: ' . $riKeys . '<br><br></div>';
        echo '<div align="center">';
            if($backhome != "" && $global != 1) {
                echo $listbutton . " ";
                session_destroy();
            }
        
            if($global==1 && $riLevel == "Portfolio"){
                echo $globalportbutton . " ";
            }
            
            if($riLevel == "Program"){
                echo $globalprogbutton . " ";
            }
        echo '</div>';
        //if($global==1 && $riLevel == "Program"){
            //echo $globalprogbutton;
        //}
            

        //EMAIL PM AND RI CREATOR
        if($changeLogKey == 3 || $changeLogKey == 4){
            //DISTRO
            $to = $userEmail; // . ",". $pocEmail;
            $subject = 'Risk and Issue ' . $changeLogName;
            $from = 'CCI-EESolutionsTeam@cox.com';

            // BUILD HEADER CONTENT
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

            // BUILD HEADERS
            $headers .= 'From: '.$from."\r\n".
                'Reply-To: '.$from."\r\n" .
                'X-Mailer: PHP/' . phpversion();

            // BUILD EMAIL BODY
            $message = "<p>A " . $riLevel . " " .$riTypeCode . "  has been " . $changeLogName . ".  Below are the details.</p>";
            $message .="<br><b>ID: </b>" . $riKeys ;
            $message .="<br><b>Owner Name: </b>" . $userName;
            $message .="<br><b>" . $riLevel . " " . $riTypeCode . " Name: </b>"; $message .= $name ; 
            $message .="<br><b>Type: </b>"; $message .= $riLevel . " " . $riTypeCode  ; 
            $message .="<br><b>Issue Descriptor: </b>"; $message .= $descriptor ;
            $message .="<br><b>Description: </b>"; $message .= $description ;
            $message .="<br><b>Drivers: </b>"; $message .= $emailDrivers ;
            $message .="<br><b>Impact Area: </b>"; $message .= $impactArea2 ;
            $message .="<br><b>Impact Level: </b>"; $message .= $impactLevel2 ;
            //$message .="<br><b>POC Group/Name: </b>"; $message .= $poc ;
            $message .="<br><b>Response Strategy: </b>"; $message .= $responseStrategy2 ;
            $message .="<br><b>Forecasted Resolution Date: </b>"; $message .= $date ;
            if($formName == "PRJR" || $formName == "PRJI"){$message .="<br><b>Associated Projects: </b>"; $message .= $assocProject;}
            $message .="<br><b>Action Plan: </b>"; $message .= $actionPlan ;
            $message .="<br><b>Date Closed: </b>"; $message .= $DateClosed ;
            if($global == 1) {
                $message .="<br><b>Link: </b>"; $message .= $menu_root . "/risk-and-issues/global/details.php?status=" . $riOpenFlg . "&rikey=" . $riKeys;
            } else {
                $message .="<br><b>Link: </b>"; $message .= $menu_root . "/risk-and-issues/" . $detailPage . ".php?au=true&rikey=" . $firstRIkey ."&fscl_year=" . $lrpYear . "&proj_name=" . urlencode($project_nm) . "&status=" . $status . "&popup=true&uid=" . $uid ;
            }
                           
            // SEND EMAIL USING MAIL FUNCION 
                if(mail($to, $subject, $message, $headers)){
                    //echo '<div align="center">An email was sent on your behalf to the Program and Project Managers. </div>';
                } else {
                    echo 'Unable to send email. Please contact EE Solutions.';
        }
        //END - EMAIL TO PM AND RI CREATOR

        //START - EMAIL RAID ADMIN
        if($raidLog == 1) {
            $to = "CCI-EngineeringPortfolioManagement@cox.com";
            $subject = "Updated Risk/Issue Flagged for RAID Log";
            $from = 'CCI-EESolutionsTeam@cox.com';

            // BUILD HEADER CONTENT
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

            // BUILD HEADERS
            $headers .= 'From: '.$from."\r\n".
                'Reply-To: '.$from."\r\n" .
                'X-Mailer: PHP/' . phpversion();
                

            // BUILD EMAIL BODY
            $message = "<p>There was an update to a " . $riLevel . " " .$riTypeCode . " that has been flagged for RAID Log.</p>";
            $message .="<br><b>ID: </b>" . $riKeys ;
            $message .="<br><b>Owner Name: </b>" . $userName;
            $message .="<br><b>" . $riLevel . " " . $riTypeCode . " Name: </b>"; $message .= $name ; 
            $message .="<br><b>Type: </b>"; $message .= $riLevel . " " . $riTypeCode  ; 
            $message .="<br><b>Issue Descriptor: </b>"; $message .= $descriptor ;
            $message .="<br><b>Description: </b>"; $message .= $description ;
            $message .="<br><b>Drivers: </b>"; $message .= $emailDrivers ;
            $message .="<br><b>Impact Area: </b>"; $message .= $impactArea2 ;
            $message .="<br><b>Impact Level: </b>"; $message .= $impactLevel2 ;
            //$message .="<br><b>POC Group/Name: </b>"; $message .= $poc ;
            $message .="<br><b>Response Strategy: </b>"; $message .= $responseStrategy2 ;
            $message .="<br><b>Forecasted Resolution Date: </b>"; $message .= $date ;
            if($formName == "PRJR" || $formName == "PRJI"){$message .="<br><b>Associated Projects: </b>"; $message .= $assocProject;}
            $message .="<br><b>Action Plan: </b>"; $message .= $actionPlan ;
            $message .="<br><b>Date Closed: </b>"; $message .= $DateClosed ;
            if($global == 1) {
                $message .="<br><b>Link: </b>"; $message .= $menu_root . "/risk-and-issues/global/details.php?status=" . $riOpenFlg . "&rikey=" . $riKeys;
            } else {
                $message .="<br><b>Link: </b>"; $message .= $menu_root . "/risk-and-issues/details.php?au=true&rikey=" . $firstRIkey ."&fscl_year=" . $lrpYear . "&proj_name=" . urlencode($project_nm) . "&status=" . $status . "&popup=true&uid=" . $uid ;
            }
            // SEND EMAIL USING MAIL FUNCION 
                if(mail($to, $subject, $message, $headers)){
                    //echo '<div align="center">An email was sent on your behalf to the RAID Log Admin.</div>';
                } else {
                    echo 'Unable to send email. Please contact EE Solutions.';
                }
            }

        //END - EMAIL RIAD ADMIN
            }
    } else {
        echo '<br><br><br><h2 align="center">Risk and Issue Error</h2><div align="center">' . $SPCode . ' = ' . $SPMessage . '<br>BatchID = ' . $SPBatch_Id . '</div><br><div align="center">
        <a href="javascript:history.go(-2)"  class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span> Edit </a>
        </div>' ;
    }

    /*Free the statement and connection resources. */
    sqlsrv_free_stmt($stmt3);
    sqlsrv_close($conn);

?>