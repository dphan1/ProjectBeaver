<?php

   class BeaverController extends BaseController {

      public function getIndex() {
         echo "This is the index page.";
      }

      public function getBootstraptest() {
         return View::make('bootstraptest');
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

      public function getUsers() {
         return View::make('users');
      }

      public function getDomains() {
         return View::make('domains');
      }

      public function getProfiles() {
         return View::make('profiles');
      }

      public function getHome() {
         return View::make('home');
      }
  
      public function showHome() {
         $email = Input::get('email'); // Get email input by user from the login form
         $password = Input::get('password'); // Get password input by user from the login form

         $resultArray = DB::select("SELECT password FROM users WHERE email = '" . $email . "'");
         if (count($resultArray) < 1) { // No record in the result set => Email doesn't exist
            return Redirect::to('main/loginfailed');
         }
         $result = (array) $resultArray[0];
         $hashedPassword = $result['password']; // Get the hashed password from database
 
         if (Hash::check($password, $hashedPassword)) { // Password matched
            return Redirect::to('main/home');
         } else { // Password not matched 
            return Redirect::to('main/loginfailed');
         }
      }
   }
