<?php 
$monday = strtotime('next monday', strtotime('previous sunday'));
$friday = strtotime('previous friday', strtotime('previous sunday'));
echo "POR Version " . date("m/d/y", $monday) . " (Includes approved CR's as of " .date("m/d/y", $friday) . ")";


?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
</body>
</html>