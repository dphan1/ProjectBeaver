<?php

   class BeaverController extends BaseController {

      public function getIndex() {
         echo "This is the index page.";
      }

      public function getAuthenticate() {
         return View::make('authenticate');
      }

      public function getForgot() {
         return View::make('forgot');
      }

      public function getLogin() {
         return View::make('login');
      }
   
      public function getLoginfailed() {
         return View::make('loginfailed');
      }

      public function getProfile() {
         return View::make('profile');
      }
  
      public function showProfile() {
         $email = Input::get('email'); // Get email input by user from the login form
         $password = Input::get('password'); // Get password input by user from the login form

         $resultArray = DB::select("SELECT password FROM users WHERE email = '" . $email . "'");
         $result = (array) $resultArray[0];
         $hashedPassword = $result['password']; // Get the hashed password from database
 
         if (Hash::check($password, $hashedPassword)) { // Password matched
            return Redirect::to('main/profile');
         } else { // Password not matched 
            return Redirect::to('main/loginfailed');
         }
      }
   }
