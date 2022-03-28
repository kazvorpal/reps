<?php
//GET DOMIAN
$domain = $_SERVER['SERVER_NAME'];
$menu_root = "https://" . $domain;

// FORCE HTTPS
if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}


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

// DATE FORMAT FOR RISK AND ISSUES

	function  convDate($tx) {
		if (is_null($tx)) {
			echo '---';
		} else { 
			$riDate = date_format($tx, 'Y-m-d');
			echo $riDate;
		}
	}
	
// DATE CONVERSION FOR DPR PHASE DATES

	function  convtimeDPR($tdpr) {
		if (is_null($tdpr)) {
			echo '---';
		} else { 
			$timedpr = date_format($tdpr, 'm-d-y');
			echo $timedpr;
		}
	}

	// CONVERT DATE TO MONTH YEAR

	function  monthYear($a) {
		if (is_null($a)) {
			echo '---';
		} else { 
			$mY = date_format($a, 'm-Y');
			echo $mY;
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
		//echo "<a style='color:#FFFFFF' href='l2-frame-2021.php?uid=" . urlencode($Cuxid) . "' class='dno'><u>" . $actual . "</u></a>";
		echo  $actual;
	}else{
		//echo "<a style='color:#FFFFFF' href='l2-frame-2021.php?uid=" . urlencode($Cuxid) . "' class='dno'><u>" . $plan . "</u></a>";
		echo  $plan;
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
		echo $actual;
	}else{
		echo $plan;
	}
}

// SHOW DATE OR NOT, NO LINK
Function dateshwNLR($CPlanDT, $CPhase, $CActDT, $Cuxid, $Cept) {
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
	
	if($CPlanDT == '' || $CPhase == '01 Proposed' || $CPhase == '02 Allocated' || $Cept == 'Reporting Only') {
		echo "--";
	}else if($CActDT != ''){	
		echo $actual;
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

// SHOW OR NOT SHOW PERCENTAGE FOR INITIATING AND PLANNING TASK
Function pcntshwR($pcnt, $CPlanDT, $CPhase, $CActDT, $Cuxid, $Cept) {
	if($CPlanDT == '' || $CPhase == '01 Proposed' || $CPhase == '02 Allocated' || $Cept == 'Reporting Only') {
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
	if($a > 0 && ($b == '01 Proposed' || $b == '02 Allocated' || $b == '03 Released')) { //ftpm started working and EPT Phases not in execute make yellow
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
// PHASE BACKGROUND COLOR
		// $a Executing_Flg
		// $b Executing_Act_Dt
		// $c Executing_Pln_Dt
		// $d Executing_Late_Flg
		// $e PHASE_NAME
		// $f ENTRPRS_PROJ_TYPE_NM

Function phaseColor($a,$b,$c,$d,$e,$f) {

	$grey_stages = 1;
	if($e == '01 Proposed' || $e == '02 Allocated' || $e == '03 Released' || /*(*/$f == 'Reporting Only' /*&& $row_por['FISCL_PLAN_YR'] != '2021' )*/ || $f == '' || $e == 'Cancelled') {
	$grey_stages = 0;
	}
								
	// execute prep cell color
	$exe = '#00d257'; // Cox Green
	if ($a != 1 || $b == ''){ // 0 = has no stage | 1 = has stage
		$exe = '#00aaf5'; //Cox Blue
	}

		// red logic fixed; do not show red on day of exec prep
		if(is_null($c)){ 
		
			if($d == 1){ // 0 = not late | 1 = late c1c1c1
			$exe = 'red'; // Red
			}

		} else {

			$execPDx = $c;
			$execPD = date_format($execPDx, 'm-d-Y');
			$execTD = date('m-d-Y');

			if($d == 1 && $execPD != $execTD){ //if exec prep late flag = 1 and exec prep plan date is not equal to today
			$exe = 'red'; // Red
			}
		}

	if($grey_stages == 0 || $a != 1){ // if reporting only and not in Execute Prep then turn grey
		$exe = '#c1c1c1'; 
	}
}

// WATTS UNDERSCORE FIX

function wattsRepl($a) {
	$watts = str_replace("_", "-", $a);
	echo $watts;
}

// OVERALL HEALTH COLOR INDICATOR // THIS CAN USE SOME WORK

function OV_health($a) {
	if($a == 'Not Defined'){
		echo '#c1c1c1';
	} // Not Defined - Turn Grey and show Health Status Date
	else if($a == 'Red'){
		echo 'red';
	} // Red - Turn Red and show Health Status Date
	else if($a == 'Green'){
		echo '#00d257';
	} // Green - Turn Green and show Health Status Date
	else if($a == 'Yellow')	{
		echo '#fcd12a';
	} // Yellow - Turn Yellow and show Health Status Date
	//else if($a == 'Purple' || $a == 'Black'){
		//echo '#800080';
	else {
		echo '#c1c1c1';
	} // Purple - Turn Purple and show Health Status Date (Currently Black in EPS)
}

//The function returns the no. of business days between two dates and it skips the holidays
function getWorkingDays($startDate,$endDate,$holidays){
    // do strtotime calculations just once
    $endDate = strtotime($endDate);
    $startDate = strtotime($startDate);


    //The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
    //We add one to inlude both dates in the interval.
    $days = ($endDate - $startDate) / 86400 + 1;

    $no_full_weeks = floor($days / 7);
    $no_remaining_days = fmod($days, 7);

    //It will return 1 if it's Monday,.. ,7 for Sunday
    $the_first_day_of_week = date("N", $startDate);
    $the_last_day_of_week = date("N", $endDate);

    //---->The two can be equal in leap years when february has 29 days, the equal sign is added here
    //In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
    if ($the_first_day_of_week <= $the_last_day_of_week) {
        if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;
        if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;
    }
    else {
        // (edit by Tokes to fix an edge case where the start day was a Sunday
        // and the end day was NOT a Saturday)

        // the day of the week for start is later than the day of the week for end
        if ($the_first_day_of_week == 7) {
            // if the start date is a Sunday, then we definitely subtract 1 day
            $no_remaining_days--;

            if ($the_last_day_of_week == 6) {
                // if the end date is a Saturday, then we subtract another day
                $no_remaining_days--;
            }
        }
        else {
            // the start date was a Saturday (or earlier), and the end date was (Mon..Fri)
            // so we skip an entire weekend and subtract 2 days
            $no_remaining_days -= 2;
        }
    }

    //The no. of business days is: (number of weeks between the two dates) * (5 working days) + the remainder
//---->february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it
   $workingDays = $no_full_weeks * 5;
    if ($no_remaining_days > 0 )
    {
      $workingDays += $no_remaining_days;
    }

    //We subtract the holidays
    foreach($holidays as $holiday){
        $time_stamp=strtotime($holiday);
        //If the holiday doesn't fall in weekend
        if ($startDate <= $time_stamp && $time_stamp <= $endDate && date("N",$time_stamp) != 6 && date("N",$time_stamp) != 7)
            $workingDays--;
    }

    return $workingDays;
}

//Example:

// $holidays=array("2021-12-25","2021-12-26","2021-01-01");

// echo getWorkingDays("2008-12-22","2009-01-02",$holidays)
// => will return 7
?>


