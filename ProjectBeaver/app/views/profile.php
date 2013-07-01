<?php
   
   echo 'Login succeeded!' . "<br><br>";

   /*
    * Reference: http://www.codular.com/curl-with-php
    */
   $curl = curl_init(); // Instantiate an instance of cURL, which returns a cURL resource
   curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => 'http://www.leagueofbeavers.com'));
   $result = curl_exec($curl); // Sending request
   echo 'Analytic website status: ' . curl_error($curl);
   
   curl_close($curl); // Close request
   
   // Select statement         
   $laravelResults = DB::select('SELECT SUM(CASE WHEN track_type = "migrated" THEN 1 ELSE 0 END) AS migrated,
                           SUM(CASE WHEN track_type = "existed" THEN 1 ELSE 0 END) AS existed,
                           SUM(CASE WHEN track_type = "fail" THEN 1 ELSE 0 END) AS fail,
                           SUM(CASE WHEN track_type = "profile_not_found" THEN 1 ELSE 0 END) AS profile,
                           SUM(CASE WHEN track_type != "migrated" THEN 1 ELSE 0 END) AS not_migrated,
                           SUM(CASE WHEN track_type != "existed" THEN 1 ELSE 0 END) AS not_existed,
                           SUM(CASE WHEN track_type != "fail" THEN 1 ELSE 0 END) AS not_fail,
                           SUM(CASE WHEN track_type != "profile_not_found" THEN 1 ELSE 0 END) AS not_profile
                     FROM legacy_profiles');
   $laravelDetails = (array)$laravelResults[0]; // Convert result into an array, $laravelResults[0] is an stdClass object, not an array object

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

         // Copy current time
         var currentTime = <?php echo $currentEpochTime; ?>;

         // Copy all other results to be used for graph and chart          
         var profileNotExisted = <?php echo $laravelDetails['not_existed']; ?>;
         var profileNotMigrated = <?php echo $laravelDetails['not_migrated']; ?>;
         var profileNotFailed = <?php echo $laravelDetails['not_fail']; ?>;
         var profileNotNotFound = <?php echo $laravelDetails['not_profile']; ?>;

         var profileExisted = <?php echo $laravelDetails['existed']; ?>;
         var profileMigrated = <?php echo $laravelDetails['migrated']; ?>;
         var profileFailed = <?php echo $laravelDetails['fail']; ?>;
         var profileNotFound = <?php echo $laravelDetails['profile']; ?>;
         
         // Set google visualization package up
         google.load("visualization", "1", {packages:["corechart"]});
         google.load("visualization", "1", {packages:['table']});
         google.setOnLoadCallback(drawChart24);
         google.setOnLoadCallback(drawChart48);
         google.setOnLoadCallback(drawChartTrackTypeNot);
         google.setOnLoadCallback(drawChartTrackType);
         google.setOnLoadCallback(drawChartPercentageFail);
         google.setOnLoadCallback(drawChartPercentageNotFound);

         // Draw line chart, past 24 hours
         function drawChart24() {
            // Set up data
            var data = google.visualization.arrayToDataTable([
               ['Hour', 'API Calls'],
               [  0   ,     getAPICalls(result24, 0, currentTime, 24)],
               [  1   ,     getAPICalls(result24, 1, currentTime, 24)],
               [  2   ,     getAPICalls(result24, 2, currentTime, 24)],
               [  3   ,     getAPICalls(result24, 3, currentTime, 24)],
               [  4   ,     getAPICalls(result24, 4, currentTime, 24)],
               [  5   ,     getAPICalls(result24, 5, currentTime, 24)],
               [  6   ,     getAPICalls(result24, 6, currentTime, 24)],
               [  7   ,     getAPICalls(result24, 7, currentTime, 24)],
               [  8   ,     getAPICalls(result24, 8, currentTime, 24)],
               [  9   ,     getAPICalls(result24, 9, currentTime, 24)],
               [  10  ,     getAPICalls(result24, 10, currentTime, 24)],
               [  11  ,     getAPICalls(result24, 11, currentTime, 24)],
               [  12  ,     getAPICalls(result24, 12, currentTime, 24)],
               [  13  ,     getAPICalls(result24, 13, currentTime, 24)],
               [  14  ,     getAPICalls(result24, 14, currentTime, 24)],
               [  15  ,     getAPICalls(result24, 15, currentTime, 24)],
               [  16  ,     getAPICalls(result24, 16, currentTime, 24)],
               [  17  ,     getAPICalls(result24, 17, currentTime, 24)],
               [  18  ,     getAPICalls(result24, 18, currentTime, 24)],
               [  19  ,     getAPICalls(result24, 19, currentTime, 24)],
               [  20  ,     getAPICalls(result24, 20, currentTime, 24)],
               [  21  ,     getAPICalls(result24, 21, currentTime, 24)],
               [  22  ,     getAPICalls(result24, 22, currentTime, 24)],
               [  23  ,     getAPICalls(result24, 23, currentTime, 24)]
            ]);
            
            // Set chart title
            var options = {
               title: 'Number of API Calls Past 24 Hours'
            };
          
            var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
            chart.draw(data, options); // Draw it!
         }

         // Draw line chart, past 48 hours
         function drawChart48() {
            var data = google.visualization.arrayToDataTable([
               ['Hour', 'API Calls'],
               [  0   ,     getAPICalls(result48, 0, currentTime, 48)],
               [  1   ,     getAPICalls(result48, 1, currentTime, 48)],
               [  2   ,     getAPICalls(result48, 2, currentTime, 48)],
               [  3   ,     getAPICalls(result48, 3, currentTime, 48)],
               [  4   ,     getAPICalls(result48, 4, currentTime, 48)],
               [  5   ,     getAPICalls(result48, 5, currentTime, 48)],
               [  6   ,     getAPICalls(result48, 6, currentTime, 48)],
               [  7   ,     getAPICalls(result48, 7, currentTime, 48)],
               [  8   ,     getAPICalls(result48, 8, currentTime, 48)],
               [  9   ,     getAPICalls(result48, 9, currentTime, 48)],
               [  10  ,     getAPICalls(result48, 10, currentTime, 48)],
               [  11  ,     getAPICalls(result48, 11, currentTime, 48)],
               [  12  ,     getAPICalls(result48, 12, currentTime, 48)],
               [  13  ,     getAPICalls(result48, 13, currentTime, 48)],
               [  14  ,     getAPICalls(result48, 14, currentTime, 48)],
               [  15  ,     getAPICalls(result48, 15, currentTime, 48)],
               [  16  ,     getAPICalls(result48, 16, currentTime, 48)],
               [  17  ,     getAPICalls(result48, 17, currentTime, 48)],
               [  18  ,     getAPICalls(result48, 18, currentTime, 48)],
               [  19  ,     getAPICalls(result48, 19, currentTime, 48)],
               [  20  ,     getAPICalls(result48, 20, currentTime, 48)],
               [  21  ,     getAPICalls(result48, 21, currentTime, 48)],
               [  22  ,     getAPICalls(result48, 22, currentTime, 48)],
               [  23  ,     getAPICalls(result48, 23, currentTime, 48)],
               [  24  ,     getAPICalls(result48, 24, currentTime, 48)],
               [  25  ,     getAPICalls(result48, 25, currentTime, 48)],
               [  26  ,     getAPICalls(result48, 26, currentTime, 48)],
               [  27  ,     getAPICalls(result48, 27, currentTime, 48)],
               [  28  ,     getAPICalls(result48, 28, currentTime, 48)],
               [  29  ,     getAPICalls(result48, 29, currentTime, 48)],
               [  30  ,     getAPICalls(result48, 30, currentTime, 48)],
               [  31  ,     getAPICalls(result48, 31, currentTime, 48)],
               [  32  ,     getAPICalls(result48, 32, currentTime, 48)],
               [  33  ,     getAPICalls(result48, 33, currentTime, 48)],
               [  34  ,     getAPICalls(result48, 34, currentTime, 48)],
               [  35  ,     getAPICalls(result48, 35, currentTime, 48)],
               [  36  ,     getAPICalls(result48, 36, currentTime, 48)],
               [  37  ,     getAPICalls(result48, 37, currentTime, 48)],
               [  38  ,     getAPICalls(result48, 38, currentTime, 48)],
               [  39  ,     getAPICalls(result48, 39, currentTime, 48)],
               [  40  ,     getAPICalls(result48, 40, currentTime, 48)],
               [  41  ,     getAPICalls(result48, 41, currentTime, 48)],
               [  42  ,     getAPICalls(result48, 42, currentTime, 48)],
               [  43  ,     getAPICalls(result48, 43, currentTime, 48)],
               [  44  ,     getAPICalls(result48, 44, currentTime, 48)],
               [  45  ,     getAPICalls(result48, 45, currentTime, 48)],
               [  46  ,     getAPICalls(result48, 46, currentTime, 48)],
               [  47  ,     getAPICalls(result48, 47, currentTime, 48)]
            ]);
           
            var options = {
               title: 'Number of API Calls Past 48 Hours'
            };
 
            var chart = new google.visualization.LineChart(document.getElementById('another_chart_div'));
            chart.draw(data, options);
         }
         
         function drawChartTrackTypeNot() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Track Type That Is NOT:');
            data.addColumn('number', 'Number');
            data.addRows([
              ['Existed', profileNotExisted],
              ['Migrated', profileNotMigrated],
              ['Failed', profileNotFailed],
              ['Profile_not_found', profileNotNotFound]
            ]);

            var table = new google.visualization.Table(document.getElementById('chart_trackTypeNot'));
            table.draw(data, {showRowNumber: true});   
         }

	 /* Draw a pie chart with 4 different values:
          * track_type that is: existed, migrated, fail, or profile_not_found
          */
         function drawChartTrackType() {
            var data = google.visualization.arrayToDataTable([
               ['Profile', 'Number'],
               ['Existed', profileExisted],
               ['Migrated', profileMigrated],
               ['Fail', profileFailed],
               ['Profile_not_found', profileNotFound]
            ]);

            var options = {
               title: 'Track Type Classification'
            };

            var chart = new google.visualization.PieChart(document.getElementById('chart_trackType'));
            chart.draw(data, options);
         }
        
         function drawChartPercentageFail() {
            var data = google.visualization.arrayToDataTable([
              ['Legacy Profiles', 'Failed', 'Migrated'],
              ['', profileFailed / profileMigrated, 1]
            ]);

            var options = {
              title: 'Percentage of profiles that have failed',
              hAxis: {title: 'Percentage', format: '##%'}
            };

            var chart = new google.visualization.BarChart(document.getElementById('chart_percentageFail'));
            chart.draw(data, options);
         }
          
         /* Draw a percentage bar chart with two values: migrated, profile_not_found
          * migrated is always 100%
          */
         function drawChartPercentageNotFound() {
            var data = google.visualization.arrayToDataTable([
              ['Legacy Profiles', 'Not Found', 'Migrated'],
              ['', profileNotFound / profileMigrated, 1]
            ]);

            var options = {
              title: 'Percentage of profiles not found',
              hAxis: {title: 'Percentage', format: '##%'}
            };

            var chart = new google.visualization.BarChart(document.getElementById('chart_percentageNotFound'));
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
   </head>
   <body>

      <!-- Display charts on browser -->
      <div id="chart_div" style="width: 900px; height: 500px;"></div> <!-- Displaying chart -->
      <?php echo "<br>" . 'Total Pinterest calls past 24 hours: ' . $totalCallsPast24Hours; ?>

      <div id="another_chart_div" style="width: 900px; height: 500px;"></div>  
      <div id="chart_trackTypeNot"></div>
      <div id="chart_trackType" style="width: 900px; height: 500px;"></div>
      <div id="chart_percentageFail" style="width: 900px; height: 400px;"></div>
      <div id="chart_percentageNotFound" style="width: 900px; height: 400px;"></div>
     
   </body>
</html>
