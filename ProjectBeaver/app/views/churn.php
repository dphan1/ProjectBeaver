<?php
   $startDate = 0;
   $endDate = 30;

   function getQueryTotalRevenue($startDate, $endDate) {
      return 'SELECT SUM(revenue) AS total_revenue
       FROM
          (SELECT DISTINCT(a1.org_id), plan, count(a1.org_id) * (CASE WHEN plan = 4 THEN 299
                                                                      WHEN plan = 3 THEN 99
                                                                      WHEN plan = 2 THEN 29
                                                                 ELSE 0 END) AS revenue

          FROM

             (SELECT org_id, account_id

             FROM

                (SELECT org_id, min(timestamp) date_created
                FROM users
                GROUP BY org_id
                ) a1 JOIN user_accounts USING(org_id)

                WHERE date_created >= (1375728862 - (86400 * ' . $endDate . '))
                AND date_created <= (1375728862 - (86400 * ' . $startDate . '))
                AND track_type != "orphan"
                ) a1 JOIN user_organizations USING(org_id)

                GROUP BY org_id
        ) b1';
   }

   function getQueryRevenueChange($startDate, $endDate) {
      return 
      'SELECT sum(revenue_change) AS revenue_change

        FROM 

	(SELECT cust_id, u1.type, description, u1.timestamp AS change_time, org_id, 
			(CASE WHEN description = "Change from Free (1) to Basic (2)" THEN 29
	     			WHEN description = "Change from Free (1) to Pro (3)" THEN 99
		  			WHEN description = "Change from Free (1) to Agency (4)" THEN 299
		  			WHEN description = "Change from Basic (2) to Pro (3)" THEN 70
		  			WHEN description = "Change from Basic (2) to Agency (4)" THEN 270
		  			WHEN description = "Change from Pro (3) to Agency (4)" THEN 200
		  			WHEN description = "Change from Agency (4) to Pro (3)" THEN -200
		  			WHEN description = "Change from Agency (4) to Basic (2)" THEN -270
		  			WHEN description = "Change from Agency (4) to Free (1)" THEN -299
		  			WHEN description = "Change from Pro (3) to Basic (2)" THEN -70
		  			WHEN description = "Change from Pro (3) to Free (1)" THEN -99
		  			WHEN description = "Change from Basic (2) to Free (1)" THEN -29	
	 		ELSE 0 END) AS revenue_change
	FROM user_history u1 JOIN users u2 USING (cust_id) 
	JOIN (SELECT org_id, min(timestamp) date_created
			FROM users
			GROUP BY org_id) a2 USING (org_id)
	JOIN user_accounts USING (org_id)
	WHERE a2.date_created >= (1375728862 - (86400 * ' . $endDate . '))
	AND a2.date_created <= (1375728862 - (86400 * ' . $startDate . '))
	AND id >= 1648
	AND u1.type = "change_plan"
        AND track_type != "orphan"
	) a1';
   }

   function getQueryNumberOfOrg($startDate, $endDate) {
      return 'SELECT COUNT(DISTINCT(org_id)) AS org_num

		FROM

			(SELECT org_id, min(timestamp) date_created
			FROM users
			GROUP BY org_id
			) a1 JOIN user_accounts USING(org_id)

		WHERE date_created >= (1375728862 - (86400 * ' . $endDate . '))
		AND date_created <= (1375728862 - (86400 * ' . $startDate . '))
		AND track_type != "orphan"';
   }

   $queryRev030 = DB::select(getQueryTotalRevenue($startDate, $endDate));
   $resultRev030 = (array)$queryRev030[0];

   $queryRevChange030 = DB::select(getQueryRevenueChange($startDate, $endDate));
   $resultRevChange030 = (array)$queryRevChange030[0];

   $queryOrg030 = DB::select(getQueryNumberOfOrg($startDate, $endDate));
   $resultOrg030 = (array)$queryOrg030[0];


   $startDate = 31;
   $endDate = 60;

   $queryRev3160 = DB::select(getQueryTotalRevenue($startDate, $endDate));
   $resultRev3160 = (array)$queryRev3160[0];

   $queryRevChange3160 = DB::select(getQueryRevenueChange($startDate, $endDate));
   $resultRevChange3160 = (array)$queryRevChange3160[0];

   $queryOrg3160 = DB::select(getQueryNumberOfOrg($startDate, $endDate));
   $resultOrg3160 = (array)$queryOrg3160[0];

   $startDate = 61;
   $endDate = 90;

   $queryRev6190 = DB::select(getQueryTotalRevenue($startDate, $endDate));
   $resultRev6190 = (array)$queryRev6190[0];

   $queryRevChange6190 = DB::select(getQueryRevenueChange($startDate, $endDate));
   $resultRevChange6190 = (array)$queryRevChange6190[0];

   $queryOrg6190 = DB::select(getQueryNumberOfOrg($startDate, $endDate));
   $resultOrg6190 = (array)$queryOrg6190[0];

?>
<html>
   <head>

      <script type="text/javascript" src="https://www.google.com/jsapi"></script>
      <script type="text/javascript">
         var resultRev030 = <?php echo json_encode($resultRev030); ?>; 
         var resultRevChange030 = <?php echo json_encode($resultRevChange030); ?>;
         var resultOrg030 = <?php echo json_encode($resultOrg030); ?>;      

         var resultRev3160 = <?php echo json_encode($resultRev3160); ?>;
         var resultRevChange3160 = <?php echo json_encode($resultRevChange3160); ?>;
         var resultOrg3160 = <?php echo json_encode($resultOrg3160); ?>;

         var resultRev6190 = <?php echo json_encode($resultRev6190); ?>;
         var resultRevChange6190 = <?php echo json_encode($resultRevChange6190); ?>;
         var resultOrg6190 = <?php echo json_encode($resultOrg6190); ?>;

         google.load('visualization', '1', {packages:['table']});
         google.setOnLoadCallback(drawTable);
         function drawTable() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Organization Age (days)');
            data.addColumn('string', 'Start');
            data.addColumn('string', 'Change');
            data.addColumn('string', 'Down/Up');
            data.addColumn('string', 'Total Revenue');
            data.addColumn('string', 'Revenue change from changing plans');
            data.addColumn('string', 'Number of organizations');
            data.addColumn('string', '% growth');

            data.addRows([
              ['New (<= 30)', ' ', ' ', ' ', resultRev030['total_revenue'], resultRevChange030['revenue_change'], resultOrg030['org_num'], ' '],             
              ['31 - 60', ' ', ' ', ' ', resultRev3160['total_revenue'], resultRevChange3160['revenue_change'], resultOrg3160['org_num'], ' '],
              ['61 - 90', ' ', ' ', ' ', resultRev6190['total_revenue'], resultRevChange6190['revenue_change'], resultOrg6190['org_num'], ' ']
              //['61 - 90', ' ', ' ', ' ',
              //['91 - 120', ' ', ' ', ' ',
              //['121 - 180', ' ', ' ', ' ',
  
              ]);
            var table = new google.visualization.Table(document.getElementById('table_div'));
            table.draw(data, {showRowNumber: true});

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
              <li><a href="http://admin.tailwindapp.com/main/users">Users</a></li>
              <li><a href="http://admin.tailwindapp.com/main/domains">Domains</a></li>
       	      <li class="active"><a href="http://admin.tailwindapp.com/main/profiles">Profiles</a></li>
              <li class="active"><a href="http://admin.tailwindapp.com/main/churn">Churn Report</a></li>
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
	      <h3><?php echo '31 - 60: ' . $resultOrg030['org_num']; ?><h3>
              <div id='table_div'></div>
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
