<?php

// -- DEPENDENCIES
require_once(dirname(__FILE__)."/../lib/Import.php");
require_once(dirname(__FILE__)."/../api/EditUserApi.php");

// -- FUNCTIONS
function processRequest() {
  // Redirect to login page if invalid session
  if (!Session::isLoggedIn()) {
    EditUserApi::redirectToLoginAndExit(); 
  }
  // Process delete user request
  $username = Session::getUsername();
  $user = User::fetchByUsername($username);
  $user->delete();
  Session::logout(); 

  // Redirect to home page
  Import::redirect(Import::HOME_PAGE_PATH); 
}

function main() {
  processRequest();
}

// -- MAIN
main();

