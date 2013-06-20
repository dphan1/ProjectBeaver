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
   }
