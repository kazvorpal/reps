<?php
$monitor_json = file_get_contents('https://catl0dwas11208.corp.cox.com:8443/monitorinfo');
$decoded_json = json_decode($monitor_json,true);

//echo $decoded_json['datetime'] . "<br><br>";


    //foreach($decoded_json['status'][0] as $monitor => $val){
    //    echo $monitor . " " . $val;
    //}

//echo $decoded_json['status'][0]['name'] . $decoded_json['status'][0]['online'];
//echo  "<br>";
//print_r($decoded_json);
//echo  "<br><br>";

//  Scan through outer loop
echo "
    <!--<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css'> -->
    <!--<script type='text/javascript' src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js'></script> -->
    <style>
    .border {border-style: solid; border-width: 1px; border-color: white;  font-family: arial;}
    .h1 {color: white; font-family: arial;}
    </style>
    <div>
        <h3 class='h1'>APPLICATION STATUS</h3>
    </div>
    <div class='border'>
    <table width='100%' style='padding: 5px; color: ffffff;'>
        <tr>
            <td width = '150px' ><b>APPLICATION</b></td><td align='center'><b>STATUS</b></td>
        </tr>
    ";

foreach ($decoded_json as $inner) {

    //  Check type
    
    if (is_array($inner)) {
        //  Scan through inner loop
        echo "<tr><td>Budget Tracker</td>";
        foreach ($inner[0] as $value) {
            if($value == 1){ echo "<td align='center'>Up</td></tr>"; } else if($value == ""){  echo "<td>Down</td></tr>"; }
        }
        echo "<tr><td>CR Generator</td>";
        foreach ($inner[1] as $value) {
            if($value == 1){ echo "<td align='center'>Up</td></tr>"; } else if($value == ""){  echo "<td>Down</td></tr>"; }
        }
        echo "<tr><td>MLM </td>";
        foreach ($inner[2] as $value) {
            if($value == 1){ echo "<td align='center'>Up</td></tr>"; } else if($value == ""){  echo "<td>Down</td></tr>"; }
        }
        echo "<tr><td>POInt </td>";
        foreach ($inner[3] as $value) {
            if($value == 1){ echo "<td align='center'>Up</td></tr>"; } else if($value == ""){  echo "<td>Down</td></tr>"; }
        }
        echo "<tr><td>Risk and Issues </td>";
        foreach ($inner[4] as $value) {
            if($value == 1){ echo "<td align='center'>Up</td></tr>"; } else if($value == ""){  echo "<td>Down</td></tr>"; }          
        }
        echo "<tr><td>RePS </td>";
        foreach ($inner[5] as $value) {
            if($value == 1){ echo "<td align='center'>Up</td></tr>"; } else if($value == ""){  echo "<td>Down</td></tr>"; }
        }
        echo "<tr><td>VA CIFT Tool </td>";
        foreach ($inner[6] as $value) {
            if($value == 1){ echo "<td align='center'>Up</td></tr>"; } else if($value == ""){  echo "<td>Down</td></tr>"; }
        }
    }
}
echo "</table><div>";


?>