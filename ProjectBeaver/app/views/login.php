<?php 
   echo Form::open(array('action' => 'BeaverController@showProfile')); 
   echo Form::label('emailLabel', 'Email'); 
   echo Form::text('email', ''); 
  

   echo Form::label('passwordLabel', 'Password');
   echo Form::password('password'); 
   echo '<br>'; 

   echo Form::submit('Login'); 
   echo HTML::link('main/forgot', 'Forgot your password?');

   echo Form::close(); 
?>
