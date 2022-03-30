
<?php include ("../includes/functions.php");?>
<?php include ("../db_conf.php");?>
<?php include ("../data/emo_data.php");?>
<?php
    //DECLARE
    $userId = 'gcarolin'; // WINDOWS LOGIN NAME
    $lrpYear = 2022; // FISCAL YEAR OF THE PROJECT
    $assocProject = '300,301'; 
    $drivers = '1,2';
    $impactArea = 2; 
    $impactLevel = 2; 
    $responseStrategy = 3; 
    $riskProbability = 2;
    $poc = 'Christophe Depaillat'; 
    $pocFlag = 1 ; //Bit
    $changeLogKey = 4;
    $asscCRKey = NULL;
    $riLevel = 'Project'; // PRJECT OR PROGRAM
    $riTypeCode = 'Risk'; // RISK OR ISSUE
    $opportunity = 'No'; // Yes OR No has to be program
    $description = 'THIS IS THE DESCRIPTION FOR DESCIRPTIOIN';
    $actionPlan = 'THIS IS THE ACTION PAN FOR THE ACTION PLAN'; 
    $closedByUID = NULL;
    $transfer2prgManager = 1;
    $riskRealized = 1;
    $riOpenFlg = 1;
    $date = '2022-03-04'; // FORCASTED RESOLUTION DATE
    $dateClosed = NULL;
    $SPCode = NULL ;
    $SPMessage = NULL ;
    $SPBatch_Id = NULL ;

    $params = array(
        array($userId, SQLSRV_PARAM_IN),
        array($lrpYear, SQLSRV_PARAM_IN),
        array($assocProject, SQLSRV_PARAM_IN),
        array($drivers, SQLSRV_PARAM_IN),
        array($impactArea, SQLSRV_PARAM_IN),
        array($impactLevel, SQLSRV_PARAM_IN),
        array($responseStrategy, SQLSRV_PARAM_IN),
        array($riskProbability, SQLSRV_PARAM_IN),
        array($poc, SQLSRV_PARAM_IN),
        array($pocFlag, SQLSRV_PARAM_IN),
        array($changeLogKey, SQLSRV_PARAM_IN),
        array($asscCRKey, SQLSRV_PARAM_IN),
        array($riLevel, SQLSRV_PARAM_IN),
        array($riTypeCode, SQLSRV_PARAM_IN),
        array($opportunity, SQLSRV_PARAM_IN),
        array($description, SQLSRV_PARAM_IN),
        array($actionPlan, SQLSRV_PARAM_IN),
        array($closedByUID, SQLSRV_PARAM_IN),
        array($transfer2prgManager, SQLSRV_PARAM_IN),
        array($riskRealized, SQLSRV_PARAM_IN),
        array($riOpenFlg, SQLSRV_PARAM_IN),
        array($date, SQLSRV_PARAM_IN), 
        array($dateClosed, SQLSRV_PARAM_IN),
        array(&$SPCode, SQLSRV_PARAM_OUT, SQLSRV_PHPTYPE_INT),
        array(&$SPMessage, SQLSRV_PARAM_OUT, null, SQLSRV_SQLTYPE_VARCHAR),
        array(&$SPBatch_Id, SQLSRV_PARAM_OUT, null, SQLSRV_SQLTYPE_VARCHAR)
        );

    //CALL THE PROCEDURE
        $tsql_callSP = "{CALL [RI_MGT].[sp_UpdateRiskandIssues](?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)}";

    //echo json_encode($params);
    //exit();

   //EXECUTE PROCEDDURE
    $stmt3 = sqlsrv_query( $data_conn, $tsql_callSP, $params);
    //$results3 = sqlsrv_execute($stmt3);
    //$row = sqlsrv_fetch_array($stmt3);
    //echo $row;

    if( $stmt3 === false )
    {
        echo json_encode($params) ."<br><br>";
        echo "SQL ERROR:.\n";
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