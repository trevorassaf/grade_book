<?php

// -- CONSTANTS
defined("SESSION_STORAGE_PATH") ? null : define("SESSION_STORAGE_PATH", dirname(__FILE__)."/../sessions");
session_save_path(SESSION_STORAGE_PATH);
//session_save_path(".");

abstract class Session {

  // Session keys
  const USERNAME_KEY = "username";
  const LAST_ACTIVE_TIME_KEY = "last-active-time"; 
  const REQUIRES_REDIRECT_CALLBACK_KEY = "requires-redirect";
  const IS_LOGGED_IN_KEY = "is-logged-in";
  const REDIRECT_CALLBACK_PARAMS_KEY = "redirect-params-key";
  const REDIRECT_CALLBACK_URL_KEY = "redirect-url-key";

  // Expiration duration
  const SESSION_EXPIRATION_DURATION_IN_MINUTES = 5;

  // Session Path
  const SESSION_PATH = SESSION_STORAGE_PATH;

  /**
   * Creates new session. Overwrites existing session to default state.
   */
  public static function doesSessionExist() {
    // Create fresh session
    session_start();
    $did_session_exist = true;
    if (!isset($_SESSION[self::IS_LOGGED_IN_KEY])) {
      $_SESSION[self::IS_LOGGED_IN_KEY] = false;
      $did_session_exist = false;
    }
    if (!isset($_SESSION[self::REQUIRES_REDIRECT_CALLBACK_KEY])) {
      $_SESSION[self::REQUIRES_REDIRECT_CALLBACK_KEY] = false;
      $did_session_exist = false;
    }
    return $did_session_exist;
  }

  public static function logInUser($username) {
    self::doesSessionExist();
    $_SESSION[self::USERNAME_KEY] = $username;
    $_SESSION[self::LAST_ACTIVE_TIME_KEY] = self::getCurrentTime();
    $_SESSION[self::IS_LOGGED_IN_KEY] = true;
  }

  public static function isUserLoggedIn() {
    // Return false if session doesn't exist or user is not logged in
    if (!self::doesSessionExist()) {
      return false;
    }
    // Check if user is logged out
    if (self::isLoggedOut()) {
      return false;
    }

    // Logout user if session has expired
    if (self::isSessionExpired()) {
      self::logout(); 
      return false;
    }

    // Update the last active timestamp
    $_SESSION[self::LAST_ACTIVE_TIME_KEY] = self::getCurrentTime();
    return true;
  }

  /**
   *
   * @precondition session exists
   */
  private static function isLoggedOut() {
    return !$_SESSION[self::IS_LOGGED_IN_KEY];
  }

  public static function logout() {
    self::doesSessionExist(); // defaults to logged out state
    $_SESSION[self::IS_LOGGED_IN_KEY] = false;
  }

  public static function redirectWithCallback($url, $callback_url, $callback_params = null) {
    // Create session, if nonexistent
    self::doesSessionExist();

    self::setRedirectCallbackRequest($callback_url, $callback_params);
    self::executeRedirect($url);
  }

  public static function redirect($url) {
    self::executeRedirect($url);
  }

  private static function executeRedirect($url) {
    header("Location: ".$url);
    exit();
  }

  public static function executeRedirectCallback() {
    if (!self::shouldRedirect()) { // initializes session
      return;
    }

    $_SESSION[self::REQUIRES_REDIRECT_CALLBACK_KEY] = false;
    $callback_url = $_SESSION[self::REDIRECT_CALLBACK_URL_KEY];
    $callback_params = $_SESSION[self::REDIRECT_CALLBACK_PARAMS_KEY];
    $redirect_url = self::encodeGetRequest($callback_url, $callback_params);
    self::executeRedirect($redirect_url);
  }

  /**
   * @precondition session exists and user is logged in
   */
  private static function setRedirectCallbackRequest($url, $params) {
    // Enable redirect
    $_SESSION[self::REQUIRES_REDIRECT_CALLBACK_KEY] = true;
    $_SESSION[self::REDIRECT_CALLBACK_URL_KEY] = $url;
    $_SESSION[self::REDIRECT_CALLBACK_PARAMS_KEY] = $params;
  }
  
  public static function encodeGetRequest($url, $params) {
    if ($params == null || empty($params)) {
      return $url;
    }
    $url .= "?";
    foreach ($params as $key => $value) {
      $url .= urlencode($key)."=".urlencode($value)."&";
    }  
    return substr($url, 0, -1);
  }

  /**
   * @prevondition user has valid session. Must check session before invoking this functoin. 
   */
  public static function getUsername() {
    if (!self::isUserLoggedIn()) {
      throw new Exception("user has no session, can't get user name. shouldn't happen");
    }
    return $_SESSION[self::USERNAME_KEY];
  }

  public static function shouldRedirect() {
    if (!self::doesSessionExist()) {
      return false;
    }

    return $_SESSION[self::REQUIRES_REDIRECT_CALLBACK_KEY];
  }
  
  public static function getCurrentTime() {
    self::ensureTimezone();
    return date('Y-m-d G:i:s');
  }

  public static function getCurrentDate() {
    self::ensureTimezone();
    return date('Y-m-d');
  }

  private static function ensureTimezone() {
    date_default_timezone_set("America/Chicago");
  }

  /**
   * Returns true if session is expired, also updates the corresponding sesion vars.
   *
   * @precondition session exists
   */ 
  private static function isSessionExpired() {
    $prev_timestamp_str = $_SESSION[self::LAST_ACTIVE_TIME_KEY];
    $curr_timestamp_str = self::getCurrentTime();
    $difference_in_minutes = self::differenceInMinutes($prev_timestamp_str, $curr_timestamp_str);

    return $difference_in_minutes > self::SESSION_EXPIRATION_DURATION_IN_MINUTES;
  }

  private static function differenceInMinutes($prev_timestamp_str, $curr_timestamp_str) {
    $prev_timestamp = strtotime($prev_timestamp_str);
    $curr_timestamp = strtotime($curr_timestamp_str);
    return ($curr_timestamp - $prev_timestamp) / 60;
  }
}
