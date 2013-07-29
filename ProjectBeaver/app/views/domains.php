<?php
   $queryTotalDomains = DB::select('SELECT COUNT(DISTINCT(domain)) AS total_domain FROM user_accounts_domains');
   $resultTotalDomains = (array)$queryTotalDomains[0];
?>
<html>
   <head>
      <script>var totalDomains = <?php echo json_encode($queryTotalDomains); ?>; </script>
      <link href="<?php echo asset('bootstrap.css'); ?>" rel="stylesheet" media="screen">
      <link href="<?php echo asset('bootstrap-responsive.css'); ?>" rel="stylesheet">

      <style type="text/css">
         body {
           padding-top: 60px;
           padding-bottom: 40px;
         }
         .sidebar-nav {
           padding: 9px 0;
         }

         @media (max-width: 980px) {
           /* Enable use of floated navbar text */
           .navbar-text.pull-right {
             float: none;
             padding-left: 5px;
             padding-right: 5px;
           }
         }
      </style> 

   </head>
   <body>
      <style>
         body {
            //background-color: A5C6E8;
         }
         h1 {
            //color: 8A0714;
            //color: 3A1DA3;
            //color: 9E2424;
         }
         p {
            
         }
      </style>

      <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="/main/home">Project Beaver</a>
          <div class="nav-collapse collapse">
            <p class="navbar-text pull-right">
              Logged in as <a href="#" class="navbar-link">Username</a>
            </p>
            <ul class="nav">
              <li><a href="http://admin.tailwindapp.com/">Home</a></li>
              <li><a href="http://admin.tailwindapp.com/main/users">Users</a></li>
              <li class="active"><a href="http://admin.tailwindapp.com/main/domains">Domains</a></li>
       	      <li><a href="http://admin.tailwindapp.com/main/profiles">Profiles</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row-fluid">

        <div class="span3">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">

            </ul>
          </div><!--/.well -->
        </div><!--/span-->

        <div class="span9">
          <div class="hero-unit">
            <h1>Tailwind Admin Dashboard</h1>
          </div>
          <div class="row-fluid">
            <div class="span10">
              <h3>Total number of domains: <?php echo number_format($resultTotalDomains['total_domain']); ?></h3>
            </div><!--/span-->
          </div><!--/row-->
          <div class="row-fluid">
            <div class="span10">

            </div><!--/span-->
          </div><!--/row-->
          <div class="row-fluid">
            <div class="span10">

            </div><!--/span-->         
          </div><!--/row-->
          <div class="row-fluid">
            <div class="span10">

            </div><!--/span-->
          </div><!--/row-->
        </div><!--/span-->
      </div><!--/row-->

      <hr>

      <footer>
        <p>&copy; Tailwind 2013</p>
      </footer>

    </div><!--/.fluid-containter-->
         
            <!--<div class="pos_left" id="chart_div" style="width: 900px; height: 500px;"></div><!-- Displaying chart -->
            
            <!--<div class="pos_left" id="another_chart_div" style="width: 900px; height: 500px;"></div>  
   </body>

</html>
