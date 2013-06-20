<?php echo Form::open(array('url' => 'main/authenticate')); ?>
   <?php echo Form::label('username', 'Username'); ?>
   <?php echo Form::text('whatup', ''); ?>
  

   <?php echo Form::label('password', 'Password') ?> 
   <?php echo Form::password('password'); ?>

   <?php echo Form::submit('Login'); ?>

   <?php echo Form::close(); ?>
