<!-- This is the login page -->
<html>
   <head>
       <title>Login Page</title>
   </head>
   <body>
      <form id="login" action="authenticate.php" method="POST"> 
         <h1 style="font-size: 20px; color:blue">Login to your analytic page!</h1> <!-- Header -->
         <strong>Email:</strong> <input type="text" name="email" /> <br> <!-- Email input box -->
         <strong>Password:</strong> <input type="password" name="password" /> <br> <!-- Password input box -->
         <input type="submit" name="login" value="Login" /> <br> <!-- Login button -->
         <a href="http://pinleague.frontend.localhost/ProjectBeaver/forgot.php">Forgot your password?</a> <!-- Forgot password link -->
      </form>
   </body>

</html>
