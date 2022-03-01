
<?php include ("../includes/functions.php");?>
<?php include ("../db_conf.php");?>
<?php include ("../data/emo_data.php");?>

<?php

    //DECLARE
    //$declaration ="@return_value int, @SPCode int, @SPMessage nvarchar(255), @SPBatch_Id nvarchar(255) ";

    $userId = 'gcarolin'; // WINDOWS LOGIN NAME
    $formName = 'PRJR'; // PRJR, PRJI, PRGI, PRGR
    $formType = 'New'; // NEW 
    $lrpYear = 2018; // FISCAL YEAR OF THE PROJECT
    $riTypeCode = 'Risk'; // RISK OR ISSUE
    $name = 'Test123 from PHP 2_1'; // PROJECT NAME
    $drivers = 'People Resources,Schedule Impact';
    $riLevel = 'Project'; // PRJECT OR PROGRAM
    $impactArea = 1; 
    $impactLevel = 1; 
    $opportunity = ''; // Yes OR No has to be program
    $riskProbability = 1;
    $responseStrategy = 1; 
    $assocProject = 'Arbor COR P6L TMS appliance lab Infinitistream installation 2018,Arbor POR18'; 
    $assocProgram = ''; 
    $individual = 'Bob & Avi'; 
    $internalExternal = 0 ; //Bit
    $descriptor = 'Scope Descriptor Free Field';  // DESCRIPTOR
    $description = 'RI Description: Risk for BWM';
    $actionPlan = 'RI ActionPlan: Risk for BWM'; 
    $transfer2prgManager = 0; //Bit
    $date = '2021-11-29'; // FORCASTED RESOLUTION DATE
    $dateClosed = NULL;
    $riskRealized = 1;
    $region = NULL;
    $closedByDate = NULL;
    $closedByUID = NULL;
    
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
        array($individual, SQLSRV_PARAM_IN),
        array($internalExternal, SQLSRV_PARAM_IN),
        array($descriptor, SQLSRV_PARAM_IN),
        array($description, SQLSRV_PARAM_IN),
        array($actionPlan, SQLSRV_PARAM_IN),
        array($transfer2prgManager, SQLSRV_PARAM_IN),
        array($date, SQLSRV_PARAM_IN),
        array($dateClosed, SQLSRV_PARAM_IN),
        array($closedByUID, SQLSRV_PARAM_IN),
        array($riskRealized, SQLSRV_PARAM_IN),
        array(&$SPCode, SQLSRV_PARAM_OUT, SQLSRV_PHPTYPE_INT),
        array(&$SPMessage, SQLSRV_PARAM_OUT, null, SQLSRV_SQLTYPE_VARCHAR),
        array(&$SPBatch_Id, SQLSRV_PARAM_OUT, null, SQLSRV_SQLTYPE_VARCHAR)
        );

    //CALL THE PROCEDURE
        $tsql_callSP = "{CALL [RI_MGT].[sp_SaveRiskandIssues](?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)}";

    //echo json_encode($params);
    //exit();

   //EXECUTE PROCEDDURE
    $stmt3 = sqlsrv_query( $conn, $tsql_callSP, $params);
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
    echo $SPCode . ' ' . $SPMessage;
    
    /*Free the statement and connection resources. */
    sqlsrv_free_stmt($stmt3);
    sqlsrv_close($conn);

?>