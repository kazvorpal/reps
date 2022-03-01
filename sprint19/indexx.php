<?php include ("includes/functions.php");?>
<?php include ("db_conf.php");?>
<?php include ("data/emo_data.php");?>
<?php include ("sql/collapse.php");?>
<?php include ("sql/update-time.php");?>
<?php include ("sql/update-time-all.php");?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Carolino, Gil">
    <title>RePS Reporting - Cox Communications</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/heroes/">

    

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    
    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
    

    
    <!-- Custom styles for this template -->
    <link href="css/heroes.css" rel="stylesheet">
  </head>
  <body>
    
<main>
  <h1 class="visually-hidden">Heroes examples</h1>

  

 

  <div class="bg-dark text-secondary px-4 py-5 text-center">
    <div class="py-5">
      <div><img src="images/rEPS-logo-2021c.png"></div>
      <h1 class="display-5 fw-bold text-white"></h1>
     <!-- <h2 class="display-5 fw-bold text-white">Regional Project Summary</h2> -->
      <div class="col-lg-6 mx-auto">
        <p class="fs-5 mb-4"><?php echo "Updated: " . date_format($row_uptime['Last_Update_Ts'],'Y-m-d @ g:i A ') . 'EST';?></p>
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
          <a href="regional-view.php"><button type="button" class="btn btn-outline-info btn-lg px-4 me-sm-3 fw-bold"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-view-list" viewBox="0 0 16 16">
  <path d="M3 4.5h10a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1H3zM1 2a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13A.5.5 0 0 1 1 2zm0 12a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13A.5.5 0 0 1 1 14z"/>
</svg> Regional View</button></a>

<a href="esp-status-details-index.php"><button type="button" class="btn btn-outline-info btn-lg px-4 me-sm-3 fw-bold"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-view-list" viewBox="0 0 16 16">
  <path d="M3 4.5h10a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1H3zM1 2a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13A.5.5 0 0 1 1 2zm0 12a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13A.5.5 0 0 1 1 14z"/>
</svg> Detailed Phase Report</button></a>

<a href="index_eps_status.php"><button type="button" class="btn btn-outline-info btn-lg px-4 me-sm-3 fw-bold"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-view-list" viewBox="0 0 16 16">
  <path d="M3 4.5h10a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1H3zM1 2a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13A.5.5 0 0 1 1 2zm0 12a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13A.5.5 0 0 1 1 14z"/>
</svg> EPS Status Report</button></a>

<a href="equipment/oh.php"><button type="button" class="btn btn-outline-info btn-lg px-4 me-sm-3 fw-bold"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
  <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
</svg> Equipment Order History </button></a>
        </div>
      </div>
    </div>
<div align='center'>
<h2 class="display-5 fw-bold text-white">Table Refresh Status</h2>
<table class="table" style='color: white; width: 500px;'>
  <thead>
    <tr>
      
      <th scope="col">Tool</th>
      <th scope="col">Date</th>
      <th scope="col">Time</th>
    </tr>
  </thead>
  <tbody>
  <?php while($row_uptime_all = sqlsrv_fetch_array( $stmt_uptime_all, SQLSRV_FETCH_ASSOC)) { ?>
    <tr>
      <td><?php echo $row_uptime_all['Source'] ;?></td>
      <td><?php echo date_format($row_uptime_all['Last_Refresh_Dt'],'Y-m-d');?></td>
      <td><?php echo date_format($row_uptime_all['Last_Refresh_Dt'],' g:i A ') . 'EST';?></td>
    </tr>
    <?php } ;?>
  </tbody>
</table>
  </div>

  </div>
  
  <footer class="footer mt-auto py-3 bg-light" align="center">
    <div class="container">
      <img src="images/cox-clear.png" width="90px">
    </div>
  </footer>
</div>
  
</main>


    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>

      
  </body>
</html>
