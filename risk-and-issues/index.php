<?php 
include ("../includes/functions.php");
include ("../db_conf.php");
include ("../data/emo_data.php");
include ("../sql/MS_Users.php");
include ("../sql/update-time.php");
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Risk and Issues</title>
</head>
	
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"> 
  <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> 

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">

<body style="font-family:Mulish, serif;">
<?php include ("../includes/menu.php");?>

<div class="container">
  <div align="center"><h2>RISKS AND ISSUES</h2></div>
  <div align="center">Risks and Issues allows users to create, view, update, and close risks and issues for projects and programs.</div>
  <hr>
  <div class="row row-eq-height">
    <div class="col-md-6" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><span class="glyphicon glyphicon-globe"></span> CREATE GLOBAL RISK OR ISSUE</h3>
        </div>
        <div class="panel-body">
        If a risk or issue <u>is not</u> associated with an active project in EPS, click this link to create a program or portfolio Risk and Issue using the Global forms.
          <hr><div align="center"><a href="/risk-and-issues/global/"><button type="button" class="btn btn-info">Create Risk or Issue</button></a></div>
        </div>
      </div>
    </div>
    <div class="col-md-6" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><span class="glyphicon glyphicon-list-alt"></span> CREATE PROGRAM OR PROJECT RISK & ISSUE via DPR</h3>
        </div>
        <div class="panel-body">
        If a risk or issue <u>is</u> associated with an active project in EPS, click this link to create a program or project Risk and Issue via Detailed Phase Report.
          <hr><div align="center"><a href="../esp-status-details-index.php"><button type="button" class="btn btn-info">Create Risk or Issue</button></a></div>
        </div>
      </div>
    </div>
  </div>
  <!--ROW 2--><hr>
  <div class="row row-eq-height">
    <div class="col-md-4" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><span class="glyphicon glyphicon-dashboard"></span> RAID LOG</h3>
        </div>
        <div class="panel-body">
        A report containing all <u>Program</u> and <u>Portfolio</u> Risk and Issues.
          <hr><div align="center"><a href="/risk-and-issues/dashboard/?portfolio"><button type="button" class="btn btn-info">View Dashboard</button></a></div>
        </div>
      </div>
    </div>
    <div class="col-md-4" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><span class="glyphicon glyphicon-dashboard"></span> PROGRAM RISKS & ISSUES DASHBOARD</h3>
        </div>
        <div class="panel-body">
        A report containing all <u>Program</u> Risk and Issues.<br><br>
          <hr><div align="center"><a href="/risk-and-issues/dashboard/?program"><button type="button" class="btn btn-info">View Dashboard</button></a></div>
        </div>
      </div>
    </div>
    <div class="col-md-4" align="left">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><span class="glyphicon glyphicon-dashboard"></span> PROJECT RISKS & ISSUES DASHBOARD</h3>
        </div>
        <div class="panel-body">
        A report containing all <u>Project</u> Risk and Issues. <br><br>
          <hr><div align="center"><a href="/risk-and-issues/dashboard/"><button type="button" class="btn btn-info">View Dashboard</button></a></div>
        </div>
      </div>
    </div>
    
  </div>
</div>
</body>
</html>