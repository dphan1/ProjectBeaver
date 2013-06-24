<?php echo Form::open(array('action' => 'BeaverController@showProfile')); ?>
   <?php echo Form::label('emailLabel', 'Email'); ?>
   <?php echo Form::text('email', ''); ?>
  

   <?php echo Form::label('passwordLabel', 'Password') ?> 
   <?php echo Form::password('password'); ?>

   <?php echo '<br>'; ?>
   <?php echo Form::submit('Login'); ?>
   <?php echo Form::close(); ?>
