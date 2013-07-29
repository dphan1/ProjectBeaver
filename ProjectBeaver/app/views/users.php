<?php
   $queryTotalUsers = DB::select('SELECT COUNT(cust_id) AS total_user FROM users');
   $resultTotalUsers = (array)$queryTotalUsers[0];

   $queryUsers = DB::select('SELECT * FROM users');
   $queryDateRange = DB::select('SELECT (max(timestamp) - min(timestamp)) AS date_range FROM users');

   $queryDateRangeCompetitor = DB::select('SELECT (max(created_at) - min(created_at)) AS date_range FROM user_accounts WHERE competitor_of != 0');
   $queryCompetitors = DB::select('SELECT * FROM user_accounts WHERE competitor_of != 0');      

   $currentEpochTime = time();
   $currentFormattedTime = date('F j, Y', $currentEpochTime);
?>
<html>
   <head>
      <script type="text/javascript" src="https://www.google.com/jsapi"></script>
      <script type="text/javascript">
         // Copy the array result from PHP

         var queryTotalUsers = <?php echo json_encode($queryTotalUsers); ?>; 
         var queryUsers = <?php echo json_encode($queryUsers); ?>;
         var queryDateRange = <?php echo json_encode($queryDateRange); ?>;
         var queryDateRangeCompetitor = <?php echo json_encode($queryDateRangeCompetitor); ?>; 
         var queryCompetitors = <?php echo json_encode($queryCompetitors); ?>;

         // Copy current time
         var currentTime = <?php echo $currentEpochTime; ?>;
         var currentFormattedTime = <?php echo json_encode($currentFormattedTime); ?>;

         // Set google visualization package up
         google.load("visualization", "1", {packages:["corechart"]});
         google.load("visualization", "1", {packages:['table']});
         google.setOnLoadCallback(drawChartUsers);
         google.setOnLoadCallback(drawChartCompetitors);

         // Draw line chart, past 24 hours
         function drawChartUsers() {
             dateRange = queryDateRange[0]['date_range'] / 86400;
             //dateRange = 30;
             var a = new Array();
             a[0] = ['Days', 'Number of Users'];
             for (var i = 1; i < dateRange; i++) {
                var date = new Date((currentTime - (dateRange - i) * 86400) * 1000); // Calculate the x-axis date value
                var month = date.getMonth() + 1;
                var formattedTime = month + "/" + date.getDate() + "/" + date.getFullYear();
   
                a[i] = [formattedTime , getNumberOfUsers(queryUsers, i, currentTime, dateRange)];
             }
             var data = google.visualization.arrayToDataTable(a);

            // Set chart title
            var options = {
               title: 'Signed up per day',

               fontSize: 18
            };

            var chart = new google.visualization.LineChart(document.getElementById('chart_users'));
            chart.draw(data, options); // Draw it!
         }

         function drawChartCompetitors() {
            dateRange = queryDateRangeCompetitor[0]['date_range'] / 86400;

            var a = new Array();
            a[0] = ['Days', 'Number of Competitors'];
            for (var i = 1; i < dateRange; i++) {
               var date = new Date((currentTime - (dateRange - i) * 86400) * 1000);
               var month = date.getMonth() + 1;
               var formattedTime = month + "/" + date.getDate() + "/" + date.getFullYear();

               a[i] = [formattedTime, getNumberOfCompetitors(queryCompetitors, i, currentTime, dateRange)];
            }
            var data = google.visualization.arrayToDataTable(a);

            // Set chart title
            var options = {
               title: 'Competitors added per day',

               fontSize: 18
            };

            var chart = new google.visualization.LineChart(document.getElementById('chart_competitors'));
            chart.draw(data, options); // Draw it!
         }

         /* Calculate the total number of users signed up in ONE specific day
          * If you want to calculate the number of users signed up last 25 days,
          * pastDay would be 25
          */
         function getNumberOfUsers(array, day, currentTime, pastDay) {
            var sum = 0;
            if (day >= pastDay || day < 0) { // Check boundary
               document.write("Invalid day value.");
               return 0;
            } else {
               var lowerBound = currentTime - (pastDay - day) * 86400;
               var upperBound = lowerBound + 86400;

               for (var i = 0; i < array.length; i++) {
                  if ((array[i]['timestamp'] >= lowerBound) && (array[i]['timestamp'] <= upperBound)) {
                     //sum += parseInt(array[i]['calls']);
                     sum += 1;
                  }
               }
            }
            return sum;
         }

         function getNumberOfCompetitors(array, day, currentTime, pastDay) {
            var sum = 0;
            if (day >= pastDay || day < 0) { // Check boundary
               document.write("Invalid day value.");
               return 0;
            } else {
               var lowerBound = currentTime - (pastDay - day) * 86400;
               var upperBound = lowerBound + 86400;

               for (var i = 0; i < array.length; i++) {
                  if ((array[i]['created_at'] >= lowerBound) && (array[i]['created_at'] <= upperBound)) {
                     sum += 1;
                  }
               }
            }
            return sum;
         }

      </script>

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
              <li class="active"><a href="#">Users</a></li>
              <li><a href="http://admin.tailwindapp.com/main/domains">Domains</a></li>
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
              <li class="nav-header">Navigate to</li>
              <li><a href="#UserGraph">User Graph</a></li>
              <li><a href="#CompetitorGraph">Competitor Graph</a></li>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span9">
          <div class="hero-unit">
            <h1>Tailwind Admin Dashboard</h1>
          </div>
          <div class="row-fluid">
            <div class="span8">
              <h3>Total number of users: <?php echo number_format($resultTotalUsers['total_user']); ?></h3>
            </div><!--/span-->
          </div><!--/row-->

          <section id="UserGraph">
          <div class="page-header">

          </div>
          <div class="row-fluid">
            <div class="span10">
              <h2>Graph of Users Signed Up Per Day:</h2>
              <p><div id="chart_users" style="width: 900px; height: 500px;"></div></p>
            </div><!--/span-->
          </div><!--/row-->
          </section>

          <section id="CompetitorGraph">
          <div class="page-header">

          </div>
          <div class="row-fluid">
            <div class="span10">
              <h2>Graph of Competitors Added Per Day:</h2>
              <p><div id="chart_competitors" style="width: 900px; height: 500px;"></div></p>
            </div><!--/span-->
          </div><!--/row-->
          </section>

        </div><!--/span-->
      </div><!--/row-->

      <hr>

      <footer>
        <p>&copy; Tailwind 2013</p>
      </footer>

    </div><!--/.fluid-containter-->
                
   </body>

</html>
