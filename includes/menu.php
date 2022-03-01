<nav class="navbar navbar-default" style="width:100%"> <!-- Brand and toggle get grouped for better mobile display -->
  <div class="navbar-header" style="padding-top:5px" >
    <a class="navbar-brandx" href="<?php echo $menu_root?>">
      <img src="<?php echo $menu_root?>/images/rEPS-logo-2021-sm.png" width="90" alt=""/>
    </a> 
  </div>
  <!-- Collect the nav links, forms, and other content for toggling -->
  <div class="collapse navbar-collapse" id="myDefaultNavbar1">
    <ul class="nav navbar-nav">
      <li><a href="<?php echo $menu_root?>/regional/">Regional View<span class="sr-only">(current)</span></a>
      	<ul class="dropdown-menu" role="menu">
        	
        	<li><a href="<?php echo $menu_root?>/index_b.php">Risks & Issues Only</a></li>
        </ul>
      
      </li> 
      <li><a href="<?php echo $menu_root?>/esp-status-details-index.php">Detailed Phase Report<span class="sr-only">(current)</span></a></li>
      <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">POR<span class="caret"></span><span class="sr-only">(current)</span></a>
      		<ul class="dropdown-menu" role="menu">
              <li><a href="<?php echo $menu_root?>/por20">2020</a></li>
              <li><a href="<?php echo $menu_root?>/por21">2021</a></li>
              <li><a href="<?php echo $menu_root?>/por22">2022</a></li>
              
<!--              <li><a href="#">Something else here</a></li>
              <li class="divider"></li>
              <li><a href="#">Separated link</a></li>
              <li class="divider"></li>
              <li><a href="#">One more separated link</a></li>-->
           </ul>
           
            <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Equipment Order History<span class="caret"></span><span class="sr-only">(current)</span></a>
      		<ul class="dropdown-menu" role="menu">
              <!--<li><a href="<?php echo $menu_root?>/por">2019</a></li>-->
              <li><a href="<?php echo $menu_root?>/equipment/oh.php">Equipment Search</a></li>
              <li><a href="<?php echo $menu_root?>/equipment/eq_history_search_bo.php">Back Order Search</a></li>
<!--              <li><a href="#">Something else here</a></li>
              <li class="divider"></li>
              <li><a href="#">Separated link</a></li>
              <li class="divider"></li>
              <li><a href="#">One more separated link</a></li>-->
           </ul>
          
      
      </li>	

      <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Risks and Issues<span class="caret"></span><span class="sr-only">(current)</span></a>
        <ul class="dropdown-menu" role="menu">
          <li><a href="<?php echo $menu_root?>/risk-and-issues/proj_prog_report">Program/Project</a></li>
          <li><a href="<?php echo $menu_root?>/risk-and-issues/aggregate_report">Aggregate</a></li>
        </ul>
      </li>


      <li><a href="<?php echo $menu_root?>/index_eps_status.php">EPS Status Report<span class="sr-only">(current)</span></a></li>
      <li><a href="<?php echo $menu_root?>/cr/">CR Overview<span class="sr-only">(current)</span></a></li>
      <li><a href="https://coxcomminc.sharepoint.com/teams/engmgmtoffice/Lists/EPS%20Support%20%20Enhancement%20Portal/AllItems.aspx" target="_blank">Support Portal<span class="sr-only">(current)</span></a></li>

        
<!--      <li class="dropdown"> <a href="https://coxcomminc.sharepoint.com/sites/pwaeng/Lists/Risk%20and%20Issues/AllItems.aspx" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">RISK & ISSUES <span class="caret"></span></a>
        <ul class="dropdown-menu" role="menu">
          <li><a href="#">Action</a></li>
          <li><a href="#">Another action</a></li>
          <li><a href="#">Something else here</a></li>
          <li class="divider"></li>
          <li><a href="#">Separated link</a></li>
          <li class="divider"></li>
          <li><a href="#">One more separated link</a></li>
        </ul>
      </li>-->
    </ul>
   <!-- <form action="index.php" class="navbar-form navbar-right" id="risk_form" role="search">
      <div class="form-group">
        <input name="key" type="text" class="form-control" id="key" placeholder="Search">
      </div>
      <button type="submit" class="btn btn-default">Submit</button>
    </form>-->
    <div align="right" style="font-size:10px;">Updated: <?php echo date_format($row_uptime['Last_Update_Ts'],'Y-m-d @ g:i A ') . 'EST <br> Logged in as: ' . preg_replace("/^.+\\\\/", "", $_SERVER["AUTH_USER"]);?> </div>
</div>
  <!-- /.navbar-collapse -->  <!-- /.container-fluid --> 
</nav>