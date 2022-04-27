
<?php 
include ("../includes/functions.php");
include ("../db_conf.php");
include ("../data/emo_data.php");
include ("../sql/MS_Users.php");
include ("../sql/MS_Users_prg.php");
//print_r($_POST);
//echo "<br><br>";
    //DECLARE
    $changeLogKey = (int)$_POST['changeLogKey'];
    if ($changeLogKey == 1){
        $changeLogName = "Initialize";
    } else if ($changeLogKey == 2) {
        $changeLogName = "Created";
    } else if ($changeLogKey == 3) {
        $changeLogName = "Closed";
    } else if ($changeLogKey == 4) {
        $changeLogName = "Updated";
    }
    $userEmail = $row_winuser['Email'];
    $createdfrom = $_POST['createdFrom'];
    $usedButton = $_POST['submit2'];
    $userId = $_POST['userId']; // WINDOWS LOGIN NAME
    $formName = $_POST['formName']; // PRJR, PRJI, PRGI, PRGR
    $formType = $_POST['formType']; // NEW 
    $lrpYear = (int)$_POST['fiscalYer']; // FISCAL YEAR OF THE PROJECT
    $riTypeCode = $_POST['RIType']; // RISK OR ISSUE
    $name = $_POST['name']; // PROJECT NAME
    $drivers = $_POST['drivers'];
        $emailDrivers = str_replace(",", ", ",$drivers);//DRIVER LIST FOR EMAIL
    $riLevel = $_POST['RILevel']; // PRJECT OR PROGRAM
    $impactArea = (int)$_POST['impactArea']; 
    $impactLevel = (int)$_POST['impactLevel'];
    $responseStrategy = (int)$_POST['responseStrategy'];
    $assocProject = $_POST['assocProjects'];
    $assocProgram = $_POST['program']; // USE ONLY FOR PROGRAM RISK OR ISSUE OTHERWISE EMPTY
        $emailAssocProj = str_replace(",", ", ",$drivers);//ASSOCIATED PROJECTS LIST FOR EMAIL
    $individual = $_POST['individual']; 
    $internalExternal = 1;
    $poc = $_POST['poc']; // POC FROM INDIVIDUAL OR INTERNAL/EXTERNAL
    $pocFlag = (int)$_POST['pocFlag'];
    $descriptor = $_POST['descriptor']; 
    $description = $_POST['description'];
    $actionPlan = $_POST['actionPlan']; 
    $transfer2prgManager = (int)$_POST['transfer2prgManager'];

    $riskProbability = NULL; // FOR RISK ONLY
    if(!empty($_POST['RiskProbability'])){
        $riskProbability = (int)$_POST['RiskProbability'];
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
    if ($_POST['DateClosed'] == NULL) {
        $DateClosed = NULL;
    }
    $riskRealized = 1; //THIS NEEDS TO BE CAPTURED FROM FORM

    $region = NULL; // ONLY FOR PROGRAM
    if (!empty($_POST['assocRegions'])){
        $region = $_POST['assocRegions'];
    }

    $closedByDate = $_POST['DateClosed'];
    $closedByUID = NULL; // USE ONLY IF CLOSING OTHERWISE NULL // USE FOR EDIT ONLY
    $raidLog = $_POST['raidLog'];

    //print_r($_POST);
    //exit();

//LOOK UP KEY VALUES 
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

//RESPONSE STRATIGY
$sql_resp_strg = "SELECT* FROM RI_MGT.Response_Strategy WHERE ResponseStrategy_Key = $responseStrategy";
$stmt_resp_strg = sqlsrv_query( $data_conn, $sql_resp_strg );  
$row_resp_strg = sqlsrv_fetch_array( $stmt_resp_strg, SQLSRV_FETCH_ASSOC);
$responseStrategy2 = $row_resp_strg['ResponseStrategy_Nm'];
    
    //$unknown = $_POST['Unknown']; // IF UNKNOWN IS CHECKED SEND NULL TO FORCASTED RESOLUTION DATE
    //$createdFrom = $_POST['CreatedFrom']; // THE RISK THE ISSUE WAS CREATED FROM - FOR ISSUE ONLY
    //$SPCode = '@SPCode' ;
    //$SPMessage = '@SPMessage' ;
    //$SPBatch_Id = '@SPBatch_Id' ;
    
    $SPCode = NULL ;
    $SPMessage = NULL ;
    $SPBatch_Id = NULL ;

    $params = array(
        array($userId, SQLSRV_PARAM_IN),
        array($changeLogKey, SQLSRV_PARAM_IN),
        array($formName, SQLSRV_PARAM_IN),
        array($formType, SQLSRV_PARAM_IN),
        array($lrpYear, SQLSRV_PARAM_IN),
        array($riTypeCode, SQLSRV_PARAM_IN),
        array($name, SQLSRV_PARAM_IN),
        array($riLevel, SQLSRV_PARAM_IN),
        array($region, SQLSRV_PARAM_IN),
        array($impactArea, SQLSRV_PARAM_IN),
        array($impactLevel, SQLSRV_PARAM_IN),
        array($drivers, SQLSRV_PARAM_IN),
        array($opportunity, SQLSRV_PARAM_IN),
        array($riskProbability, SQLSRV_PARAM_IN),
        array($responseStrategy, SQLSRV_PARAM_IN),
        array($assocProject, SQLSRV_PARAM_IN),
        array($assocProgram, SQLSRV_PARAM_IN),
        array($poc, SQLSRV_PARAM_IN),
        array($pocFlag, SQLSRV_PARAM_IN),
        array($descriptor, SQLSRV_PARAM_IN),
        array($description, SQLSRV_PARAM_IN),
        array($actionPlan, SQLSRV_PARAM_IN),
        array($transfer2prgManager, SQLSRV_PARAM_IN),
        array($date, SQLSRV_PARAM_IN), 
        array($DateClosed, SQLSRV_PARAM_IN),
        array($closedByUID, SQLSRV_PARAM_IN),
        array($riskRealized, SQLSRV_PARAM_IN),
        array(&$SPCode, SQLSRV_PARAM_OUT, SQLSRV_PHPTYPE_INT),
        array(&$SPMessage, SQLSRV_PARAM_OUT, null, SQLSRV_SQLTYPE_VARCHAR),
        array(&$SPBatch_Id, SQLSRV_PARAM_OUT, null, SQLSRV_SQLTYPE_VARCHAR)
        );

    //CALL THE PROCEDURE
        $tsql_callSP = "{CALL [RI_MGT].[sp_InsertRiskAndIssue](?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)}";

    // DEBUG CODE
    //echo json_encode($params);
    //exit();

   //EXECUTE PROCEDDURE
    $stmt3 = sqlsrv_query( $data_conn, $tsql_callSP, $params);
    //$results3 = sqlsrv_execute($stmt3);
    //$row = sqlsrv_fetch_array($stmt3);
    
    //echo $row;

    if( $stmt3 === false )
    {
        echo "MY ERROR.\n";
        die( print_r( sqlsrv_errors(), true));
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

//EXECUTE IF RI IS SUCCESSFULLY CREATED
    if($SPCode == 0) { 
        echo '<br><br><br><h2 align="center">Risk and Issue Created</h2><div align="center">Your Risk/Issue has been created.<br>ID: ' . $SPBatch_Id . '</div>';
        
        //EMAIL PM AND RI CREATOR
            //DISTRO
            $to = $userEmail .",Kirsten.DeWitty@cox.com,alec.flores@cox.com";
            $subject = $riLevel . " " .$riTypeCode . ' Created';
            $from = 'CCI-EESolutionsTeam@cox.com';

            // BUILD HEADER CONTENT
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

            // BUILD HEADERS
            $headers .= 'From: '.$from."\r\n".
                'Reply-To: '.$from."\r\n" .
                'X-Mailer: PHP/' . phpversion();
                

            // BUILD EMAIL BODY
            $message = "<p>A new " . $riLevel . " " .$riTypeCode . " has been created.  Below are the details.</p>";
            $message .="<br><b>" . $riLevel . " " . $riTypeCode . " Name: </b>"; $message .= $name ; 
            $message .="<br><b>Type: </b>"; $message .= $riLevel . " " . $riTypeCode  ; 
            $message .="<br><b>Issue Descriptor: </b>"; $message .= $descriptor ;
            $message .="<br><b>Description: </b>"; $message .= $description ;
            $message .="<br><b>Drivers: </b>"; $message .= $emailDrivers ;
            $message .="<br><b>Impact Area: </b>"; $message .= $impactArea2 ;
            $message .="<br><b>Impact Level: </b>"; $message .= $impactLevel2 ;
            $message .="<br><b>POC Group/Name: </b>"; $message .= $poc ;
            $message .="<br><b>Response Strategy: </b>"; $message .= $responseStrategy2 ;
            $message .="<br><b>Forecasted Resolution Date: </b>"; $message .= $date ;
            $message .="<br><b>Associated Projects: </b>"; $message .= $emailAssocProj ;
            $message .="<br><b>Action Plan: </b>"; $message .= $actionPlan ;
            $message .="<br><b>Date Closed: </b>"; $message .= $DateClosed ;
            
            // SEND EMAIL USING MAIL FUNCION 
                if(mail($to, $subject, $message, $headers)){
                    echo '<div align="center">An email was sent on your behalf to the Program and Project Managers.</div>';
                } else {
                    echo 'Unable to send email. Please contact EE Solutions.';
                }
        //END - EMAIL TO PM AND RI CREATOR

        //START - EMAIL RAID ADMIN
        if($raidLog == "Yes") {
            $to = "gilbert.carolino@cox.com,Kirsten.DeWitty@cox.com";
            $subject = "New Risk/Issue Flagged for RAID Log";
            $from = 'CCI-EESolutionsTeam@cox.com';

            // BUILD HEADER CONTENT
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

            // BUILD HEADERS
            $headers .= 'From: '.$from."\r\n".
                'Reply-To: '.$from."\r\n" .
                'X-Mailer: PHP/' . phpversion();
                

            // BUILD EMAIL BODY
            $message = "<p>A new " . $riLevel . " " .$riTypeCode . " has been flagged for RAID Log.</p>";
            $message .="<br><b>" . $riLevel . " " . $riTypeCode . " Name: </b>"; $message .= $name ; 
            $message .="<br><b>Type: </b>"; $message .= $riLevel . " " . $riTypeCode  ; 
            $message .="<br><b>Issue Descriptor: </b>"; $message .= $descriptor ;
            $message .="<br><b>Description: </b>"; $message .= $description ;
            $message .="<br><b>Drivers: </b>"; $message .= $emailDrivers ;
            $message .="<br><b>Impact Area: </b>"; $message .= $impactArea2 ;
            $message .="<br><b>Impact Level: </b>"; $message .= $impactLevel2 ;
            $message .="<br><b>POC Group/Name: </b>"; $message .= $poc ;
            $message .="<br><b>Response Strategy: </b>"; $message .= $responseStrategy2 ;
            $message .="<br><b>Forecasted Resolution Date: </b>"; $message .= $date ;
            $message .="<br><b>Associated Projects: </b>"; $message .= $emailAssocProj ;
            $message .="<br><b>Action Plan: </b>"; $message .= $actionPlan ;
            $message .="<br><b>Date Closed: </b>"; $message .= $DateClosed ;
            
            // SEND EMAIL USING MAIL FUNCION 
                if(mail($to, $subject, $message, $headers)){
                    echo '<div align="center">An email was sent on your behalf to the RAID Log Admin.</div>';
                } else {
                    echo 'Unable to send email. Please contact EE Solutions.';
                }
            }

        //END - EMAIL RIAD ADMIN

    } else {
        echo '<br><br><br><h2 align="center">Risk and Issue Error</h2><div align="center">' . $SPCode . ' = ' . $SPMessage . '<br>BatchID = ' . $SPBatch_Id . '</div><br><div align="center">
        <a href="javascript:history.back()"  class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span> Edit </a>
        </div>' ;
    }

    /*Free the statement and connection resources. */
    sqlsrv_free_stmt($stmt3);
    sqlsrv_close($conn);

?>