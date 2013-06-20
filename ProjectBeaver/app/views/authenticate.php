<!-- Authenticate.php 
   This file serves as authentication purpose, such as checking if email exists, or if password matches the database
-->
<?php
   $email = $_POST['email']; // Get user-input email from login.html
   $password = $_POST['password']; // Get user-input password from login.html
   $pdo = new PDO("mysql:host=localhost;dbname=datastore", 'root', 'root'); // Connect to the database
   $query = "SELECT email, password FROM users WHERE email = '" . $email . "'"; // Get email and password (if any) based on the email provided by the user
   $statement = $pdo->prepare($query); // Prepare the query to be executed
   $statement->execute(); // Execute the statement
   $arrayResult = $statement->fetch(PDO::FETCH_ASSOC); // Fetch result into array

   $fail = 'Location: http://pinleague.frontend.localhost/ProjectBeaver/loginfailed.php';
   $succeed = 'Location: http://pinleague.frontend.localhost/ProjectBeaver/profile.php';

   if (count($arrayResult) == 1) { // Array is empty => No result => Login failed
      header($fail); // Jump to loginfailed.php page
   } else { // Email found!
      if ($password == $arrayResult['password']) { // Password matches database => Login succeeded
         header($succeed); // Jump to profile.php page
      } else { // Password doesn't match => Login failed
         header($fail);
      }
   }
?>
