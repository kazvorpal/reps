<?php
// format number with no decimals and comma
	function FmtNum($rawNum) {
		if(is_null($rawNum)) {
			echo '0';
		} else {
			$cnvNum = number_format($rawNum);
			echo $cnvNum;
		}
	}


//  POR Manual Version Date Function
	function PORDate() {
		$monday = strtotime('next monday', strtotime('previous sunday'));
		$friday = strtotime('previous friday', strtotime('previous sunday'));
		
		echo "POR Version " . date("m/d/y", $monday) . " (Includes approved CR's as of " .date("m/d/y", $friday) . ")";
	}

// Time conversion sql to php

	function  convtimex($tx) {
		if (is_null($tx)) {
			echo '---';
		} else { 
			$timex = date_format($tx, 'm-d-Y');
			echo $timex;
		}
	}
	
	//convtimex("2019-09-23");
	//convtimex("2019-09-23 08:05:06.310");
	
// DATE CONVERSION FOR DPR PHASE DATES

	function  convtimeDPR($tdpr) {
		if (is_null($tdpr)) {
			echo '---';
		} else { 
			$timedpr = date_format($tdpr, 'm-d-y');
			echo $timedpr;
		}
	}
	

//encryption class used for password
class UnsafeCrypto
{
    const METHOD = 'aes-256-ctr';

    /**
     * Encrypts (but does not authenticate) a message
     * 
     * @param string $message - plaintext message
     * @param string $key - encryption key (raw binary expected)
     * @param boolean $encode - set to TRUE to return a base64-encoded 
     * @return string (raw binary)
     */
    public static function encrypt($message, $key, $encode = false)
    {
        $nonceSize = openssl_cipher_iv_length(self::METHOD);
        $nonce = openssl_random_pseudo_bytes($nonceSize);

        $ciphertext = openssl_encrypt(
            $message,
            self::METHOD,
            $key,
            OPENSSL_RAW_DATA,
            $nonce
        );

        // Now let's pack the IV and the ciphertext together
        // Naively, we can just concatenate
        if ($encode) {
            return base64_encode($nonce.$ciphertext);
        }
        return $nonce.$ciphertext;
    }

    /**
     * Decrypts (but does not verify) a message
     * 
     * @param string $message - ciphertext message
     * @param string $key - encryption key (raw binary expected)
     * @param boolean $encoded - are we expecting an encoded string?
     * @return string
     */
    public static function decrypt($message, $key, $encoded = false)
    {
        if ($encoded) {
            $message = base64_decode($message, true);
            if ($message === false) {
                throw new Exception('Encryption failure');
            }
        }

        $nonceSize = openssl_cipher_iv_length(self::METHOD);
        $nonce = mb_substr($message, 0, $nonceSize, '8bit');
        $ciphertext = mb_substr($message, $nonceSize, null, '8bit');

        $plaintext = openssl_decrypt(
            $ciphertext,
            self::METHOD,
            $key,
            OPENSSL_RAW_DATA,
            $nonce
        );

        return $plaintext;
    }
}

// SHOW DATE OR NOT
Function dateshw($CPlanDT, $CPhase, $CActDT, $Cuxid, $Cept) {
	if($CActDT == '') { 
	$actual = '--';
	} else {
	$actual = date_format($CActDT, 'm-d-y');
	}
	
	if($CPlanDT == '') { 
	$plan = '--';
	} else {
	$plan = date_format($CPlanDT, 'm-d-y');
	}
	
	//$plan = convtimeDPR($CPlanDT);
	
	if($CPlanDT == '' || $CPhase == '01 Proposed' || $CPhase == '02 Allocated' || $CPhase == '03 Released' || $Cept == 'Reporting Only') {
		echo "--";
	}else if($CActDT != ''){	
		echo "<a style='color:#FFFFFF' href='l2-frame-2021.php?uid=X" . urlencode($Cuxid) . "' class='dno'>" . $actual . "</a>";
	}else{
		echo "<a style='color:#FFFFFF' href='l2-frame-2021.php?uid=X" . urlencode($Cuxid) . "' class='dno'>" . $plan . "</a>";
	}
}

// SHOW DATE OR NOT, NO LINK
Function dateshwNL($CPlanDT, $CPhase, $CActDT, $Cuxid, $Cept) {
	if($CActDT == '') { 
	$actual = '--';
	} else {
	$actual = date_format($CActDT, 'm-d-y');
	}
	
	if($CPlanDT == '') { 
	$plan = '--';
	} else {
	$plan = date_format($CPlanDT, 'm-d-y');
	}
	
	//$plan = convtimeDPR($CPlanDT);
	
	if($CPlanDT == '' || $CPhase == '01 Proposed' || $CPhase == '02 Allocated' || $CPhase == '03 Released' || $Cept == 'Reporting Only') {
		echo "--";
	}else if($CActDT != ''){	
		echo $actual ;
	}else{
		echo $plan;
	}
}
// SHOW DATE OR NOT - FOR EXPORT
Function dateshwX($CPlanDT, $CPhase, $CActDT, $Cuxid, $Cept) {
	if($CActDT == '') { 
	$actual = '--';
	} else {
	$actual = date_format($CActDT, 'm-d-y');
	}
	
	if($CPlanDT == '') { 
	$plan = '--';
	} else {
	$plan = date_format($CPlanDT, 'm-d-y');
	}
	
	//$plan = convtimeDPR($CPlanDT);
	
	if($CPlanDT == '' || $CPhase == '01 Proposed' || $CPhase == '02 Allocated' || $CPhase == '03 Released' || $Cept == 'Reporting Only') {
		echo "--";
	}else if($CActDT != ''){	
		echo $actual ;
	}else{
		echo $plan ;
	}
}

// CONVERT TO PERCENT
Function prcnt($numbr) {
	$pcntNmb = $numbr*100;
	echo $pcntNmb . "%";
}

// FIX OWNER NAME
Function OwnNm($a) {
	$ownName = $a;
	$ownNameShort = str_replace("(","<br>(",$ownName);
	echo $ownNameShort;
}

// SHOW OR NOT SHOW PERCENTAGE
Function pcntshw($pcnt, $CPlanDT, $CPhase, $CActDT, $Cuxid, $Cept) {
	if($CPlanDT == '' || $CPhase == '01 Proposed' || $CPhase == '02 Allocated' || $CPhase == '03 Released' || $Cept == 'Reporting Only') {
		echo "--";
	}else if($CActDT != ''){	
		echo "<div>" . $pcnt . "%</div>";
	}else{
		echo "<div>" . $pcnt . "%</div>";
	}
}


// % COMPLETE COLOR

// $a = %complete
// $b = EPT Phase

Function pcntComp($a, $b) {
	if($a > 0 && ($b == '01 Proposed' || $b == '02 Allocated' || $b == '03 Released')) { //ftpm started working and EPT Phaseis not in execute make yellow
	echo "#fcd12a";
	} else if($a == 1.00 && ($b == '01 Proposed' || $b == '02 Allocated' || $b == '03 Released' || $b == '04 Execute')) { // if 100% complete and EPT Phase not closed make yellow
	echo "#fcd12a";
	// } else if ($a == 1.00 && ($b == '05 Closing' || $b == '06 Archived')) { //make green is ept is reporting and %complete is 100% // adding to all projects and project must be in closing or archived
	// echo "#00d257";
	} else {
	echo "";
	}
}

// FILTER SET COLOR
Function fltrSet($a) {
	if(isset($a) && $a != '') {
		echo 'style="background-color:#ededed"';
	}
}

// SHOW FINISH DATE

// $a = Phase
// $b = Plan Finish Date
// $c = Fiscal Year

Function fnshdt($a, $b, $c) {
	if($a == '01 Proposed' || $a == '02 Allocated' || $a == '03 Released'){  // if in execute or closing or archived show ---
		echo '---'; 
		} else { 
			if($c >= '2021') {
				echo date_format($b, 'm-d-y');
			} else {
				echo date_format($b, 'm-d-Y');
			}
		} 
}

// COLOR LOGIC
							// Convert this into a function when you have time
							
							// <Stage name>_Flg                     Stage Flag, 0 for project has no stage, 1 has a stage
							// <Stage name>_Pln_Dt                  Stage Plan Date
							// <Stage name>_Act_Dt                  Stage Actual Date
							// <Stage name>_Late_Flg                Stage Late flag (0 for No, 1 for Late, Null for no stage)
							
							// $a = $row_por['PHASE_NAME']
							// $b = $row_por['ENTRPRS_PROJ_TYPE_NM']
							// $c = $row_por['Executing_Flg']
							// $d = $row_por['Executing_Act_Dt']
							// $e = $row_por['Executing_Pln_Dt']
							// $f = $row_por['Executing_Late_Flg']
							
							//
							
							// FOR ALL NOT IN EXECUTE OR CLOSING
							Function statusColor($a, $b, $c, $d, $e, $f) {
								$grey_stages = 1;
								if($a == '01 Proposed' || $a == '02 Allocated' || $a == '03 Released' || $a == 'Cancelled'|| $b == 'Reporting Only' || $b == '' ) {
								$grey_stages = 0;
								}
															
								// execute prep cell color
								$exe = '#00d257'; // Cox Green
								if($c != 1 || $d == ''){ // 0 = has no stage | 1 = has stage
									$exe = '#00aaf5'; //Cox Blue
									echo $exe;
								}
								
									 // red logic fixed; do not show red on day of exec prep
									if(is_null($e)){ 
									
										if($f == 1){ // 0 = not late | 1 = late c1c1c1
										$exe = 'red'; // Red
										echo $exe;
										}
	
									} else {
	
										$execPDx = $e;
										$execPD = date_format($execPDx, 'm-d-Y');
										$execTD = date('m-d-Y');
	
										if($f == 1 && $execPD != $execTD){ //if exec prep late flag = 1 and exec prep plan date is not equal to today
										$exe = 'red'; // Red
										echo $exe;
										}
									}
	
								if($grey_stages == 0 || $c != 1){ // if reporting only and not in Execute Prep then turn grey
									$exe = '#c1c1c1'; 
									echo $exe;
							}
							}
?>



