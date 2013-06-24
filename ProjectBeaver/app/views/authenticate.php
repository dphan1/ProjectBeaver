<?php
   $email = Input::get('email');
   // echo "Your input email is: " . $email . "<br>";

   $password = Input::get('password');
   // echo "Your input password is: " . $password . "<br>";

   $result = DB::select("SELECT password FROM users WHERE email = '" . $email . "'");  
   $hashedPassword = (array) $result[0];
   // echo $hashedPassword['password'] . "<br>";

   if (Hash::check($password, $hashedPassword['password'])) {

      // Authentication successful, routing to profile.php         
      // echo 'Authentication successful!';
      Redirect::to('main/profile');
   } else {

      // Authentication failed, routing to loginfailed.php      
      echo 'Authentiation failed!';
   }

?>
