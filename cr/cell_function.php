<?php 
function colorme($initshp, $initact) {
	
			if(is_null($initshp)) { 
			 $por_year = 0; 
			 $por_week = 0;
			 $nxtwk_shp = 0;
			} else {
			 $por_year = date_format($initshp, 'Y'); 
			 $por_week = date_format($initshp, 'W');
			 
			 //Week proceeding Initial Ship Week
			 $nxtwk_shp = $por_week + 1;
			}
			
			if(is_null($initact)) { 
			 $por_acti_year = 0; 
			 $por_acti_week = 0;
			 $por_acti_month = 0;
			 $endweek = 0;
			} else {
			 $por_acti_year = date_format($initact, 'Y'); 
			 $por_acti_week = date_format($initact, 'W');
			 $por_acti_month = date_format($initact, 'm');
			
			//Week prior of month, before activation 
			 $prior_wk = date_format($initact, 'Y-m-01');
			 $strtodate = strtotime($prior_wk);
			 $realdate = date('W',$strtodate);
			 $endweek = $realdate - 1;
			}
			
			
//		  if($por_year == '2018' && $por_week == 40) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2018' && $por_acti_month == 10) { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2018' && $por_week == 41) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2018' && $por_acti_month == 10) { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2018' && $por_week == 42) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2018' && $por_acti_month == 10) { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2018' && $por_week == 43) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2018' && $por_acti_month == 10) { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2018' && $por_week == 44) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2018' && $por_acti_month == 11) { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2018' && $por_week == 45) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2018' && $por_acti_month == 11) { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2018' && $por_week == 46) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2018' && $por_acti_month == 11) { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2018' && $por_week == 47) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2018' && $por_acti_month == 11) { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2018' && $por_week == 48) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2018' && $por_acti_month == 12) { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2018' && $por_week == 49) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2018' && $por_acti_month == 12) { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2018' && $por_week == 50) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2018' && $por_acti_month == 12) { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2018' && $por_week == 51) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2018' && $por_acti_month == 12) { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2018' && $por_week == 52) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2018' && $por_acti_month == 12) { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 1) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '01') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 2) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '01') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 3) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '01') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 4) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '01') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 5) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '02') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 6) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '02') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 7) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '02') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 8) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '02') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 9) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '03') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 10) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '03') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 11) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '03') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 12) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '03') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 13) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '03') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 14) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '04') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 15) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '04') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 16) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '04') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 17) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '04') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 18) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '05') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 19) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '05') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 20) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '05') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 21) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '05') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 22) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '06') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 23) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '06') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 24) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '06') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 25) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '06') { echo 'bgcolor="#3B81D5"'; } else if($endweek == 25) { echo 'bgcolor="gray"';} else if($nxtwk_shp == 25) {  echo 'bgcolor="gray"'; }
//          if($por_year == '2019' && $por_week == 26) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '06') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 27) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '07') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 28) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '07') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 29) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '07') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 30) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '07') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 31) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '08') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 32) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '08') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 33) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '08') { echo 'bgcolor="#3B81D5"'; }
          if($por_year == '2019' && $por_week == 34) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '08') { echo 'bgcolor="#3B81D5"'; } else if($endweek == 34) { echo 'bgcolor="gray"';} else if($nxtwk_shp == 34) {  echo 'bgcolor="gray"'; }
//          if($por_year == '2019' && $por_week == 35) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '09') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 36) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '09') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 37) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '09') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 38) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '09') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 39) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == '09') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 40) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == 10) { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 41) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == 10) { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 42) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == 10) { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 43) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == 10) { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 44) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == 11) { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 45) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == 11) { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 46) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == 11) { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 47) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == 11) { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 48) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == 12) { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 49) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == 12) { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 50) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == 12) { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 51) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == 12) { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2019' && $por_week == 52) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2019' && $por_acti_month == 12) { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2020' && $por_week == 1) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2020' && $por_acti_month == '01') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2020' && $por_week == 2) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2020' && $por_acti_month == '01') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2020' && $por_week == 3) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2020' && $por_acti_month == '01') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2020' && $por_week == 4) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2020' && $por_acti_month == '01') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2020' && $por_week == 5) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2020' && $por_acti_month == '02') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2020' && $por_week == 6) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2020' && $por_acti_month == '02') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2020' && $por_week == 7) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2020' && $por_acti_month == '02') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2020' && $por_week == 8) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2020' && $por_acti_month == '02') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2020' && $por_week == 9) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2020' && $por_acti_month == '03') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2020' && $por_week == 10) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2020' && $por_acti_month == '03') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2020' && $por_week == 11) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2020' && $por_acti_month == '03') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2020' && $por_week == 12) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2020' && $por_acti_month == '03') { echo 'bgcolor="#3B81D5"'; }
//          if($por_year == '2020' && $por_week == 13) { echo 'bgcolor="#D0AB0D"'; } else if($por_acti_year == '2020' && $por_acti_month == '03') { echo 'bgcolor="#3B81D5"'; }
	
}
?>