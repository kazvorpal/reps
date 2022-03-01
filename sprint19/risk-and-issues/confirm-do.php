
<?php include ("../includes/functions.php");?>
<?php include ("../db_conf.php");?>
<?php include ("../data/emo_data.php");?>

<?php

    //DECLARE
    $createdfrom = $_POST['createdFrom'];
    $usedButton = $_POST['submit2'];
    $userId = $_POST['userId']; // WINDOWS LOGIN NAME
    $formName = $_POST['formName']; // PRJR, PRJI, PRGI, PRGR
    $formType = $_POST['formType']; // NEW 
    $lrpYear = $_POST['fiscalYer']; // FISCAL YEAR OF THE PROJECT
    $riTypeCode = $_POST['RIType']; // RISK OR ISSUE
    $name = $_POST['name']; // PROJECT NAME
    $drivers = $_POST['drivers'];
    $riLevel = $_POST['RILevel']; // PRJECT OR PROGRAM
    $impactArea = $_POST['impactArea']; 
    $impactLevel = $_POST['impactLevel'];
    $responseStrategy = $_POST['responseStrategy'];
    $assocProject = $_POST['assocProjects'];
    $assocProgram = $_POST['programs']; // USE ONLY FOR PROGRAM RISK OR ISSUE OTHERWISE EMPTY
    $individual = $_POST['individual']; 
    $internalExternal = 1;
    $poc = $_POST['poc']; // POC FROM INDIVIDUAL OR INTERNAL/EXTERNAL
    $pocFlag = $_POST['pocFlag'];
    $descriptor = $_POST['descriptor']; 
    $description = $_POST['description'];
    $actionPlan = $_POST['actionPlan']; 
    $transfer2prgManager = $_POST['transfer2prgManager'];

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

    $dateClosed = $_POST['dateClosed'];
    if ($_POST['dateClosed'] == "NULL") {
        $dateClosed = NULL;
    }
    $riskRealized = 1;

    $region = NULL; // ONLY FOR PROGRAM
    if (!empty($_POST['assocRegions'])){
        $region = $_POST['assocRegions'];
    }

    $closedByDate = $_POST['dateClosed'];
    $closedByUID = NULL; // USE ONLY IF CLOSING OTHERWISE NULL // USE FOR EDIT ONLY
    
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
        array($date, SQLSRV_PARAM_IN), //
        array($dateClosed, SQLSRV_PARAM_IN),
        array($closedByUID, SQLSRV_PARAM_IN),
        array($riskRealized, SQLSRV_PARAM_IN),
        array(&$SPCode, SQLSRV_PARAM_OUT, SQLSRV_PHPTYPE_INT),
        array(&$SPMessage, SQLSRV_PARAM_OUT, null, SQLSRV_SQLTYPE_VARCHAR),
        array(&$SPBatch_Id, SQLSRV_PARAM_OUT, null, SQLSRV_SQLTYPE_VARCHAR)
        );

    //CALL THE PROCEDURE
        $tsql_callSP = "{CALL [RI_MGT].[sp_SaveRiskandIssues](?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)}";

    // DEBUG CODE
    //echo json_encode($params);
    //exit();

   //EXECUTE PROCEDDURE
    $stmt3 = sqlsrv_query( $conn_COX_QA, $tsql_callSP, $params);
    //$results3 = sqlsrv_execute($stmt3);
    //$row = sqlsrv_fetch_array($stmt3);
    
    //echo $row;

    if( $stmt3 === false )
    {
        echo "Error in executing statement 3.\n";
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
<!-- PROGRESS BAR -->
<div class="container">       
            <div class="row bs-wizard" style="border-bottom:0;">
                
                <div class="col-xs-3 bs-wizard-step complete">
                  <div class="text-center bs-wizard-stepnum">Step 1</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="bs-wizard-dot"></a>
                  <div class="bs-wizard-info text-center">Select associated projects</div>
                </div>
                
                <div class="col-xs-3 bs-wizard-step complete"><!-- complete -->
                  <div class="text-center bs-wizard-stepnum">Step 2</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="bs-wizard-dot"></a>
                  <div class="bs-wizard-info text-center">Enter Risk or Issue Details</div>
                </div>
                
                <div class="col-xs-3 bs-wizard-step complete"><!-- complete -->
                  <div class="text-center bs-wizard-stepnum">Step 3</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="bs-wizard-dot"></a>
                  <div class="bs-wizard-info text-center">Confirm your entry</div>
                </div>
                
                <div class="col-xs-3 bs-wizard-step complete"><!-- active -->
                  <div class="text-center bs-wizard-stepnum">Step 4</div>
                  <div class="progress"><div class="progress-bar"></div></div>
                  <a href="#" class="bs-wizard-dot"></a>
                  <div class="bs-wizard-info text-center">Completed</div>
                </div>
            </div>
  </div>
  <!-- END PROGRESS BAR --> ';
    if($SPCode == 0) {
        echo '<br><br><br><h2 align="center">Risk and Issue Created</h2><div align="center">Your Risk/Issue has been created.<br>ID: ' . $SPBatch_Id . '</div>';
    } else {
        echo '<br><br><br><h2 align="center">Risk and Issue Error</h2><div align="center">' . $SPCode . ' = ' . $SPMessage . '<br>BatchID = ' . $SPBatch_Id . '</div>' ;
    }

    //foreach ($_POST as $key => $value)
        //echo $key.'='.$value.'<br />';

    /*Free the statement and connection resources. */
    sqlsrv_free_stmt($stmt3);
    sqlsrv_close($conn);

?>