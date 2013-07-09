<?php   

   $currentEpochTime = time(); // Get current time in epoch time 

   // Query to pull all results past 24 hours
   $query24 = DB::select('SELECT datetime, calls 
                    FROM status_api_calls
                    WHERE datetime <= ' . $currentEpochTime . 
                    ' AND datetime > ' . ($currentEpochTime - (24 * 3600)));

   // Query to pull all results past 48 hours
   $query48 = DB::select('SELECT datetime, calls 
                    FROM status_api_calls
                    WHERE datetime <= ' . $currentEpochTime . 
                    ' AND datetime > ' . ($currentEpochTime - (48 * 3600))); 

   $queryAPIErrors = DB::select('SELECT SUM(CASE WHEN message = "API method not found." THEN 1 ELSE 0 END) AS method_not_found, 
       			SUM(CASE WHEN message = "Authentication failed." THEN 1 ELSE 0 END) AS auth_fail,
       			SUM(CASE WHEN message = "Bookmark not found." THEN 1 ELSE 0 END) AS bookmark_not_found,
       			SUM(CASE WHEN message = "Domain not found." THEN 1 ELSE 0 END) AS domain_not_found,
       			SUM(CASE WHEN message = "Pin not found." THEN 1 ELSE 0 END) AS pin_not_found,
       			SUM(CASE WHEN message = "Something went wrong on our end. Sorry about that." THEN 1 ELSE 0 END) AS back_end,
       			SUM(CASE WHEN message = "The request didn\'t complete in time." THEN 1 ELSE 0 END) AS request_incomplete,
       			SUM(CASE WHEN message = "User not found." THEN 1 ELSE 0 END) AS user_not_found,
                        SUM(CASE WHEN message = " " THEN 1 ELSE 0 END) AS no_message,
                        count(message) AS total
       		    FROM status_api_errors');
         
   // Compute total number of API calls for past 24 hours
   $totalCallsPast24Hours = 0;
   for ($i = 0; $i < count($query24); $i++) {
      $result = (array)$query24[$i];
      $totalCallsPast24Hours += $result['calls'];
   }
?>
<html>
   <head>

      <!-- Javascript code -->
      <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
      <script type="text/javascript">
         // Copy the array result from PHP
        
         var result24 = <?php echo json_encode($query24); ?>;
         var result48 = <?php echo json_encode($query48); ?>;  
         var queryAPIErrors = <?php echo json_encode($queryAPIErrors); ?>;

         // Copy current time
         var currentTime = <?php echo $currentEpochTime; ?>;         

         // Set google visualization package up
         google.load("visualization", "1", {packages:["corechart"]});
         google.load("visualization", "1", {packages:['table']});
         google.setOnLoadCallback(drawChart24);
         google.setOnLoadCallback(drawChart48); 
         google.setOnLoadCallback(drawChartAPIErrors);
         // Draw line chart, past 24 hours
         function drawChart24() {
            
            // Find max calls
            var maxCalls = getAPICalls(result24, 0, currentTime, 24);
            var limitCalls = 75000;
            for (var i = 1; i < 23; i++) {
               if (maxCalls < getAPICalls(result24, i, currentTime, 24)) {
                  maxCalls = getAPICalls(result24, i, currentTime, 24);
               }
            }
           
            // Set up data
            var a = new Array();
            a[0] = ['Hour', 'API Calls', 'Max Calls', 'Max Calls Limit'];
            for (var i = 0; i < 23; i++) {
               a[i + 1] = [i, getAPICalls(result24, i, currentTime, 24), maxCalls, limitCalls];  
            }
            var data = google.visualization.arrayToDataTable(a);

            // Set chart title
            var options = {
               title: 'Number of API Calls Past 24 Hours',
               
               fontSize: 18
            };
          
            var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
            chart.draw(data, options); // Draw it!
         }

         // Draw line chart, past 48 hours
         function drawChart48() {
            var maxCalls = getAPICalls(result48, 0, currentTime, 48);
            var limitCalls = 75000;
       	    for	(var i = 1; i < 47; i++) {
       	       if (maxCalls < getAPICalls(result48, i, currentTime, 48)) {
                  maxCalls = getAPICalls(result48, i, currentTime, 48);
       	       }
       	    }

            var a = new Array();

            a[0] = ['Hour', 'API Calls', 'Max Calls', 'Max Calls Limit'];
            for (var i = 0; i < 47; i++) {
               a[i + 1] = [i, getAPICalls(result48, i, currentTime, 48), maxCalls, limitCalls];
            }

       	    var	data = google.visualization.arrayToDataTable(a);
   
            var options = {
               title: 'Number of API Calls Past 48 Hours',
               fontSize: 18
            };
 
            var chart = new google.visualization.LineChart(document.getElementById('another_chart_div'));
            chart.draw(data, options);
         }

         function drawChartAPIErrors() {
            var data = google.visualization.arrayToDataTable([
               ['Error message', 'Number of messages'],
               ['API method not found', parseInt(queryAPIErrors[0]['method_not_found'])],
               ['Authentication failed', parseInt(queryAPIErrors[0]['auth_fail'])],
               ['Bookmark not found', parseInt(queryAPIErrors[0]['bookmark_not_found'])],
               ['Domain not found', parseInt(queryAPIErrors[0]['domain_not_found'])],
               ['Pin not found', parseInt(queryAPIErrors[0]['pin_not_found'])],
               ['Backend problems', parseInt(queryAPIErrors[0]['back_end'])],
               ['Request not complete in time', parseInt(queryAPIErrors[0]['request_incomplete'])],
               ['User not found', parseInt(queryAPIErrors[0]['user_not_found'])],
               ['No message', parseInt(queryAPIErrors[0]['no_message'])]
            ]);
            var options = {
               title: 'Summary of API Errors',
               fontSize: 18
            };
 
            var chart = new google.visualization.PieChart(document.getElementById('chart_APIErrors'));
            chart.draw(data, options);
         }
         

         // Calculate the total number of API calls during ONE specific hour
         function getAPICalls(array, hour, currentTime, pastHour) {
            var sum = 0;
            if (hour >= pastHour || hour < 0) { // Check boundary
               document.write("Invalid hour value.");
               return 0;
            } else {
               var lowerBound = currentTime - (pastHour - hour) * 3600;
               var upperBound = lowerBound + 3600;
     
               for (var i = 0; i < array.length; i++) {
                  if ((array[i]['datetime'] >= lowerBound) && (array[i]['datetime'] <= upperBound)) {
                     sum += parseInt(array[i]['calls']);
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
          <a class="brand" href="#">Project Beaver</a>
          <div class="nav-collapse collapse">
            <p class="navbar-text pull-right">
              Logged in as <a href="#" class="navbar-link">Username</a>
            </p>
            <ul class="nav">
              <li class="active"><a href="http://admin.tailwindapp.com/">Home</a></li>
              <li class=""><a href="http://admin.tailwindapp.com/main/users">Users</a></li>
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
              <li class="nav-header">Sidebar</li>
              <li class="active"><a href="#">Link</a></li>
              <li><a href="#users">Users Link</a></li>
              <li><a href="#">Link</a></li>
              <li class="nav-header">Sidebar</li>
              <li><a href="#">Link</a></li>
              <li><a href="#">Link</a></li>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span9">
          <div class="hero-unit">
            <h1>Tailwind Admin Dashboard</h1>
            <p>I don't know what to write here.</p>
            <p><a href="#" class="btn btn-primary btn-large">Learn more &raquo;</a></p>
          </div>
          <div class="row-fluid">
            <div class="span10">
              <h3 text-align: center>Total pinterest calls last 24 hours: <?php echo $totalCallsPast24Hours; ?> </h3>
            </div><!--/span-->
          </div><!--/row-->
          <div class="row-fluid">
            <div class="span10">
              <h2>API Calls last 24 hrs:</h2>
              <p><div id="chart_div" style="width: 900px; height: 500px;"></div></p>
              <p><a class="btn" href="#">View details &raquo;</a></p>
            </div><!--/span-->
          </div><!--/row-->
          <div class="row-fluid">
            <div class="span10">
              <h2>API Calls last 48 hrs:</h2>
              <p><div id="another_chart_div" style="width: 900px; height: 500px;"></div></p>
              <p><a class="btn" href="#">View details &raquo;</a></p>
            </div><!--/span-->         
          </div><!--/row-->
          <div class="row-fluid">
            <div class="span10">
              <h2>API Errors Chart:</h2>
              <p><div id="chart_APIErrors" style="width: 900px; height: 500px;"></div></p>
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
