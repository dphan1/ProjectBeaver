<!-- Authenticate.php 
   This file serves as authentication purpose, such as checking if email exists, or if password matches the database
-->
<?php
   $email = Input::get('email');
   echo "Your input email is: " . $email . "<br>";

   $password = Input::get('password');
   echo "Your input password is: " . $password . "<br>";

   $result = DB::select("SELECT password FROM users WHERE email = '" . $email . "'");  
   $hashedPassword = (array) $result[0];
   echo $hashedPassword['password'] . "<br>";

   if (Hash::check($password, $hashedPassword['password'])) {
      echo Form::open(array('         
   } else {
      
   }
?>
