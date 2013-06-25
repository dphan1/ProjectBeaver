<html>
   <?php
      // DB::insert('INSERT INTO users (cust_id, email, password) VALUES (?, ?, ?)', array(9942, 'dat@pinleague.com', Hash::make('beaver')));

      $results = DB::select('SELECT * FROM users WHERE cust_id = 9942');
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
      <p>This is the welcome action of the account controller.</p>
   </body>
</html>
