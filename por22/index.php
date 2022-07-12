<?php include ("../includes/functions.php");?>
<?php include ("../db_conf.php");?>
<?php include ("../data/emo_data.php");?>
<?php include ("../sql/update-time.php");?>
<!doctype html>
<html>

   <head>
   	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <script type="text/javascript" src="GantChartController.js"></script>
      <link rel="stylesheet" href="GrantChartStyle.css">
      <link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.css">
      <script type="text/javascript" src="../bootstrap/js/jquery-1.11.2.min.js"></script>
      <script type="text/javascript" src="../bootstrap/js/bootstrap.min.js"></script>
      <script type="text/javascript">
function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
      </script>
   </head>
   
   <body onload="createGantChart()">
      <!--menu-->
      <?php include ("../includes/menu.php");?>

      <!-- Title  -->
      <div align="center"><h2>Plan of Record 2022</h2></div>
      <div align="center">
<?php // PORDate(); ?>
POR Version 07/05/2022 (Includes approved CRs as of 07/01/2022) 
</div>

      <!-- Master Container -->
      <div class="main">

         <!-- Loader  -->
            <div id="loader"></div>    

         <!-- Filter Bar's Container and Break after for spacing-->
            <div class="filterBar" align = "center">                
               <div id="grid-filter-container"></div>
            </div>
            <br>
               
         <!-- Clear Selection and Download Buttons Container  -->
            <div class="downloadReset" align = "center" >
               <button class="buttons" onclick="onResetFilterClick()">Clear Selection</button>
               <!--<button class="buttons" onclick="fnExcelReport()"> Download Excel </button>-->
               <button class="buttons" onClick="MM_goToURL('parent','https://coxcomminc.sharepoint.com/teams/engmgmtoffice/Strat%20Plat%20POR/Forms/AllItems.aspx?csf=1&web=1&e=bjwhTA&cid=49946fbb%2Da778%2D46da%2D96fe%2D2845389029d6&FolderCTID=0x01200028DF62F3FE18AB4891ED32786E841668&viewid=20101ba1%2D097b%2D4809%2Db6a4%2D8e0a95502ded&id=%2Fteams%2Fengmgmtoffice%2FStrat%20Plat%20POR%2FPOR22');return document.MM_returnValue"> Link to Download Excel File </button>
            </div>
                  
         <!-- Adding Legends and Line Breaks in the beginning and End -->
            <br>
               <div align="center">
                  <table cellspacing="1" cellpadding="3" style="font-size:9px" class="table-bordered">
                     <tr>
                        <td width="150" align="center" bgcolor="#fcd12a">Need by Date</td>
                        <td width="120" align="center" bgcolor="#c1c1c1" >Range of Shipping to Activation</td>
                        <td width="120" align="center" bgcolor="#00aaf5" >To Activation Date</td>
                        <td width="120" align="center" bgcolor="#00d257" >To Migration Date</td>
                        <td width="120" align="center" >&#10687; Initial Date from Shipping to Activation</td>
                     </tr>
                  </table>
               </div>
            <br>

         <!-- Main Table Body Container -->
            <div class ="Table-Body-Container" align="center">
               <table width="98%" class="table-bordered table-hover table-striped gant_table" id="tablestyle">
                  <thead>
                     <tr bgcolor="#00aaf5" class = "gant_table__header" style="font-size:8px; color:white">
                        <th>Reg</th>
                        <th>Mrk</th>
                        <th>Fac</th>
                        <th>Program</th>
                        <th>Prog Group</th>
                        <th>Subprogram</th>
                        <th>Project</th>
                        <th>Equipment ID</th>
                        <th>Equipment Type</th>
                        <th>Equipment Name</th>
                        <th>Qty</th>
                        <th>Need By Date</th>
                        <th>Activation Date</th>
                        <th>Migrate<br>Date</th>
                        <th>CR Num</th>
                        <th>EPA CD</th>

                        <th colspan="4" width="30"><div align="center">Oct 21</div></th>
                        <th colspan="5" width="30"><div align="center">Nov 21</div></th>
                        <th colspan="4" width="30"><div align="center">Dec 21</div></th>
                        <th colspan="4" width="30"><div align="center">Jan 22</div></th>
                        <th colspan="5" width="30"><div align="center">Feb 22</div></th>
                        <th colspan="4" width="30"><div align="center">Mar 22</div></th>
                        <th colspan="4" width="30"><div align="center">Apr 22</div></th>
                        <th colspan="5" width="30"><div align="center">May 22</div></th>
                        <th colspan="4" width="30"><div align="center">Jun 22</div></th>
                        <th colspan="4" width="30"><div align="center">Jul 22</div></th>
                        <th colspan="5" width="30"><div align="center">Aug 22</div></th>
                        <th colspan="4" width="30"><div align="center">Sep 22</div></th>
                        <th colspan="4" width="30"><div align="center">Oct 22</div></th>
                        <th colspan="5" width="30"><div align="center">Nov 22</div></th>
                        <th colspan="4" width="30"><div align="center">Dec 22</div></th>
                        <th colspan="4" width="30"><div align="center">Jan 23</div></th>
                        <th colspan="5" width="30"><div align="center">Feb 23</div></th>
                        <th colspan="4" width="30"><div align="center">Mar 23</div></th>
                     </tr>
                     <tr bgcolor="#DCDBDB" class = "gant_table__second_header" style="font-size:5px">
                        <th colspan="16"></th>

                           <th width="6" align="center">40</th>
                           <th width="6" align="center">41</th>
                           <th width="6" align="center">42</th>
                           <th width="6" align="center">43</th>
                                 <th width="8" align="center">44</th>
                                 <th width="8" align="center">45</th>
                           		<th width="8" align="center">46</th>
                           		<th width="8" align="center">47</th>
                                 <th width="6" align="center">48</th>
                           <th width="6" align="center">49</th>
                           <th width="6" align="center">50</th>
                           <th width="6" align="center">51</th>
                           <th width="6" align="center">52</th>
                           	  	<th width="8" align="center">1</th>
                              	<th width="8" align="center">2</th>
                           		<th width="8" align="center">3</th>
                           		<th width="8" align="center">4</th>
                                <th width="8" align="center">5</th>
                                <th width="8" align="center">6</th>
                                <th width="8" align="center">7</th>
                                <th width="8" align="center">8</th>
                    	   <th width="6" align="center">9</th>
                           <th width="6" align="center">10</th>
                           <th width="6" align="center">11</th>
                           <th width="6" align="center">12</th>
                           <th width="6" align="center">13</th>
                               <th width="8" align="center">14</th>
                               <th width="8" align="center">15</th>
                               <th width="8" align="center">16</th>
                               <th width="8" align="center">17</th>
                               <th width="8" align="center">18</th>
                               <th width="8" align="center">19</th>
                               <th width="8" align="center">20</th>
                           	   <th width="8" align="center">21</th>
                           <th width="6" align="center">22</th>
                           <th width="6" align="center">23</th>
                           <th width="6" align="center">24</th>
                           <th width="6" align="center">25</th>
                           <th width="6" align="center">26</th>
                               <th width="8" align="center">27</th>
                               <th width="8" align="center">28</th>
                               <th width="8" align="center">29</th>
                           	   <th width="8" align="center">30</th>
                               <th width="8" align="center">31</th>
                               <th width="8" align="center">32</th>
                               <th width="8" align="center">33</th>
                               <th width="8" align="center">34</th>
                           <th width="6" align="center">35</th>
                           <th width="6" align="center">36</th>
                           <th width="6" align="center">37</th>
                           <th width="6" align="center">38</th>
                           <th width="6" align="center">39</th>
                               <th width="8" align="center">40</th>
                               <th width="8" align="center">41</th>
                               <th width="8" align="center">42</th>
                               <th width="8" align="center">43</th>
                               <th width="8" align="center">44</th>
                               <th width="8" align="center">45</th>
                               <th width="8" align="center">46</th>
                               <th width="8" align="center">47</th>
                           <th width="6" align="center">48</th>
                           <th width="6" align="center">49</th>
                           <th width="6" align="center">50</th>
                           <th width="6" align="center">51</th>
                           <th width="6" align="center">52</th>
                               <th width="8" align="center">1</th>
                               <th width="8" align="center">2</th>
                               <th width="8" align="center">3</th>
                               <th width="8" align="center">4</th>
                               <th width="8" align="center">5</th>
                               <th width="8" align="center">6</th>
                               <th width="8" align="center">7</th>
                               <th width="8" align="center">8</th>
                           <th width="6" align="center">9</th>
                           <th width="6" align="center">10</th>
                           <th width="6" align="center">11</th>
                           <th width="6" align="center">12</th>
                           <th width="6" align="center">13</th>
                           

                     </tr>
                  </thead>
                  
                  <!-- Output from JSON  -->
                  <tbody id="gridTableContainer" style="font-size:7px"></tbody>

               </table>
            </div>
      </div>
     <!-- <script src="../js/bootstrap-3.3.4.js" type="text/javascript"></script> -->
   </body>
</html>