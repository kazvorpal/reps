<?php 
  //ini_set("display_errors", "On");
  //ini_set("error_log", "C:\\tmp\\log\\error.log");
  // phpinfo();
  // if(isset($_GET["tester"]) && $_GET['tester'] == "menu") {
    $menuposition = "position:fixed;z-index:100000";
    $headerposition = " fixedheader";
    $spacer = "<div id='spacey' style='height:64px;'>&nbsp&</div>";
  // } else {
  //   $menuposition = $headerposition = $spacer = "";
  // }
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light" style="width:100%; <?= $menuposition ?>"> <!-- Changed to navbar-expand-lg for Bootstrap 4 -->
    <div class="navbar-header" style="padding-top:5px">
        <a class="navbar-brand" href="<?php echo $menu_root?>"> <!-- Removed "navbar-brandx" and made it "navbar-brand" -->
            <img src="<?php echo $menu_root?>/images/rEPS-logo-2021-sm.png" width="90" alt="" />
        </a>

        <!-- The button below is used to collapse the navbar content on smaller screens -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="navbarContent">
        <ul class="navbar-nav mr-auto"> <!-- Changed "nav navbar-nav" to "navbar-nav mr-auto" -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $menu_root ?>/regional/">Regional View</a>
            </li>
            <li>
              <a href="<?php echo $menu_root?>/esp-status-details-index.php" class="nav-link">Detailed Phase Report<span class="sr-only">(current)</span></a>
            </li>
            <!-- More list items here, similar to the one above -->
            <!-- Note: For dropdowns, use the structure below -->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                POR
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="<?php echo $menu_root ?>/por/?fiscalyear=2023">2023</a>
                <a class="dropdown-item" href="<?php echo $menu_root ?>/por/?fiscalyear=2024">2024</a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Equipment Order History
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a href="<?php echo $menu_root?>/equipment/oh.php" class="dropdown-item">Equipment Search</a>
                <a href="<?php echo $menu_root?>/equipment/eq_history_search_bo.php" class="dropdown-item">Back Order Search</a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Risks and Issues
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="<?php echo $menu_root ?>/risk-and-issues/">Risk and Issues Home</a>
                <div class="dropdown-divider"></div> <!-- Separator -->
                <a class="dropdown-item" href="<?php echo $menu_root ?>/risk-and-issues/global/">Create Global Risk or Issue</a>
                <a class="dropdown-item" href="<?php echo $menu_root ?>/esp-status-details-index.php">Create Project R/I via DPR</a>
                <div class="dropdown-divider"></div> <!-- Separator -->
                <a class="dropdown-item" href="<?php echo $menu_root ?>/risk-and-issues/dashboard/?mode=portfolio">RAID Log</a>
                <a class="dropdown-item" href="<?php echo $menu_root?>/risk-and-issues/dashboard/?mode=program">Program Dashboard</a>
                <a class="dropdown-item" href="<?php echo $menu_root?>/risk-and-issues/dashboard/">Project Dashboard</a>
              </div>
            </li>
            <li>
              <a class="nav-link" href="<?php echo $menu_root?>/index_eps_status.php">EPS Status Report<span class="sr-only">(current)</span></a>
            </li>
            <li><a class="nav-link" href="<?php echo $menu_root?>/cr/">CR Overview<span class="sr-only">(current)</span></a></li>
            <li><a class="nav-link" href="https://coxcomminc.sharepoint.com/teams/engmgmtoffice/Lists/EPS%20Support%20%20Enhancement%20Portal/AllItems.aspx" target="_blank">Support Portal<span class="sr-only">(current)</span></a></li>
            <!-- More list items and dropdowns here -->
        </ul>
        <div align="right" style="font-size:10px;">Updated: <?php echo date_format($row_uptime['Last_Update_Ts'],'Y-m-d @ g:i A ') . 'EST <br> Logged in as: ' . preg_replace("/^.+\\\\/", "", $_SERVER["AUTH_USER"]);?> </div>
        </div>
    </div>
</nav>
<script>
  const findnamedclass = (target) => {
    // This finds a class in a named style sheet, and returns it
    // target should be an object with properties:
    // target.sheetname = title you gave the style sheet in the <link rel> tag
    // target.selector = the queryselector you want to find, probably a class name

    for (o in document.styleSheets) {
        if(document.styleSheets[o].title == target.sheetname) {
            for (x in document.styleSheets[o].cssRules) {
                if (document.styleSheets[o].cssRules[x].selectorText == target.selector) {
                    return(document.styleSheets[o].cssRules[x]);
                }
            };
            console.log("selector not found");
            return false;
        }
    }
    // console.log("sheetname not found");
    return false;
  }

  var menustats;
  setTimeout(()=>{window.scrollTo(0,0);}, 1000);
  const menuhandler = () => {
    menustats = window.getComputedStyle(document.getElementById("navbarContent").parentElement);
    // console.log(menustats)
    mh = (menustats.height == "auto") ? "53px" : menustats.height;
    // console.log(mh);
    document.querySelector("#spacey").style.height = mh;
    fh = findnamedclass({sheetname: "ri", selector: ".fixedheader"});
    if (fh) {
      fh.style.top = mh;
    }
  }
  setInterval(menuhandler, 1000);
</script>
<?= $spacer ?>