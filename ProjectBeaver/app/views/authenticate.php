<?php
   $email = Input::get('email');
 

   $password = Input::get('password');
  
   $result = DB::select("SELECT password FROM users WHERE email = '" . $email . "'");  
   $hashedPassword = (array) $result[0];
 

   if (Hash::check($password, $hashedPassword['password'])) {

      // Authentication successful, routing to profile.php         
   
      header('Location: http://pinleague.frontend.localhost/main/profile');

   } else {

      // Authentication failed, routing to loginfailed.php      
   
   }
?>
