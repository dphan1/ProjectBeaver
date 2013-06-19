<html>
   <?php
      // Insert a row, with encrypted password
      DB::insert('INSERT INTO users (cust_id, email, password) VALUES (?, ?, ?)', array(9942, 'dat@pinleague.com', Hash::make('beaver')));

      // Retrieve the row that was previously added
      $results = DB::select('SELECT * FROM users WHERE cust_id = 9942');

      // $results[i] is a 'generic empty class'. I used array typecasting so I can access to its element
      $details = (array) $results[0];

      if (Hash::check('beaver', $details['password'])) {
         echo 'Yay!!!!!!!!';
      }
      
   ?>
   <head>
      <title>Welcome To My First Page!</title>
   </head>

   <body>
      <h1>Holla!</h1>
      <p>This is a Laravel authentication test case.</p>
   </body>
</html>
