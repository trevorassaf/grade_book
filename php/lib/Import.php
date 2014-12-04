<?php

// -- DEPENDENCIES
require_once(dirname(__FILE__)."/Utils.php");
// Utils::rrec(dirname(__FILE__)."/../access_layer/");
// Utils::rrec(dirname(__FILE__)."/../blink/templates/html/");
// Utils::rrec(dirname(__FILE__)."/../api/");
// Utils::rrec(dirname(__FILE__)."/../lib/");

defined("ENDPIONT_WEBSERVER_RELATIVE_PATH") ? null : define("ENDPIONT_WEBSERVER_RELATIVE_PATH", "/qnqdhu/pa2");
defined("USER_EDIT_FILE_NAME") ? null : define("USER_EDIT_FILE_NAME", "/user/edit.php");
defined("HOME_PAGE_FILE") ? null : define("HOME_PAGE_FILE", "/index.php");
defined("VIEW_ALBUMS_FILE") ? null : define("VIEW_ALBUMS_FILE", "/albums.php");
defined("LOGOUT_FILE") ? null : define("LOGOUT_FILE", "/logout.php");
defined("CREATE_USER_FILE") ? null : define("CREATE_USER_FILE", "/user.php");
defined("USER_LOGIN_FILE") ? null : define("USER_LOGIN_FILE", "/login.php");
defined("EDIT_ALBUMS_FILE") ? null : define("EDIT_ALBUMS_FILE", "/albums/edit.php");
defined("ALBUM_EDIT_FILE") ? null : define("ALBUM_EDIT_FILE", "/album/edit.php");
defined("PHOTO_FILE") ? null : define("PHOTO_FILE", "/pic.php");
defined("DELETE_USER_FILE") ? null : define("DELETE_USER_FILE", "/delete_user.php");
defined("VIEW_ALBUM_FILE") ? null : define("VIEW_ALBUM_FILE", "/album.php");
defined("EMAIL_LOGIN_FILE") ? null : define("EMAIL_LOGIN_FILE", "/login_with_link.php");
defined("PASSWORD_RESET_FILE") ? null : define("PASSWORD_RESET_FILE", "/password_reset.php");

defined("USER_EDIT_PATH") ? null : define("USER_EDIT_PATH", ENDPIONT_WEBSERVER_RELATIVE_PATH . USER_EDIT_FILE_NAME);
defined("HOME_PAGE_PATH") ? null : define("HOME_PAGE_PATH", ENDPIONT_WEBSERVER_RELATIVE_PATH .HOME_PAGE_FILE);
defined("VIEW_ALBUMS_PATH") ? null : define("VIEW_ALBUMS_PATH", ENDPIONT_WEBSERVER_RELATIVE_PATH .VIEW_ALBUMS_FILE );
defined("LOGOUT_PATH") ? null : define("LOGOUT_PATH", ENDPIONT_WEBSERVER_RELATIVE_PATH .LOGOUT_FILE);
defined("CREATE_USER_PATH") ? null : define("CREATE_USER_PATH", ENDPIONT_WEBSERVER_RELATIVE_PATH .CREATE_USER_FILE);
defined("USER_LOGIN_PATH") ? null : define("USER_LOGIN_PATH", ENDPIONT_WEBSERVER_RELATIVE_PATH .USER_LOGIN_FILE);
defined("ALBUM_EDIT_PATH") ? null : define("ALBUM_EDIT_PATH", ENDPIONT_WEBSERVER_RELATIVE_PATH .ALBUM_EDIT_FILE);
defined("PHOTO_PATH") ? null : define("PHOTO_PATH", ENDPIONT_WEBSERVER_RELATIVE_PATH .PHOTO_FILE);
defined("EDIT_ALBUMS_PATH") ? null : define("EDIT_ALBUMS_PATH", ENDPIONT_WEBSERVER_RELATIVE_PATH . EDIT_ALBUMS_FILE);
defined("VIEW_ALBUM_PATH") ? null : define("VIEW_ALBUM_PATH", ENDPIONT_WEBSERVER_RELATIVE_PATH . VIEW_ALBUM_FILE);
defined("EMAIL_LOGIN_PATH") ? null : define("EMAIL_LOGIN_PATH", ENDPIONT_WEBSERVER_RELATIVE_PATH . EMAIL_LOGIN_FILE);
defined("DELETE_USER_PATH") ? null : define("DELETE_USER_PATH", ENDPIONT_WEBSERVER_RELATIVE_PATH . "/delete_user.php");
defined("PASSWORD_RESET_PATH") ? null : define("PASSWORD_RESET_PATH", ENDPIONT_WEBSERVER_RELATIVE_PATH . PASSWORD_RESET_FILE);

defined("SERVER_PORT") ? null : define("SERVER_PORT", "5804");
defined("WEBSERVER_EXTERNAL_URL") ? null : define("WEBSERVER_EXTERNAL_URL", 'http://eecs485-02.eecs.umich.edu:' . SERVER_PORT);
defined("EMAIL_LOGIN_EXTERNAL_URL") ? null : define("EMAIL_LOGIN_EXTERNAL_URL", WEBSERVER_EXTERNAL_URL . ENDPIONT_WEBSERVER_RELATIVE_PATH . EMAIL_LOGIN_FILE);

abstract class Import {

  // -- CLASS CONSTANTS
  // Endpoint webserver-local path
  const USER_EDIT_PATH = USER_EDIT_PATH;
  const HOME_PAGE_PATH = HOME_PAGE_PATH; 
  const VIEW_ALBUMS_PATH = VIEW_ALBUMS_PATH;
  const LOGOUT_PATH = LOGOUT_PATH;
  const CREATE_USER_PATH = CREATE_USER_PATH;
  const USER_LOGIN_PATH = USER_LOGIN_PATH;
  const EDIT_ALBUMS_PATH = EDIT_ALBUMS_PATH;
  const VIEW_ALBUM_PATH = VIEW_ALBUM_PATH;
  const ALBUM_EDIT_PATH = ALBUM_EDIT_PATH;
  const PHOTO_PATH = PHOTO_PATH;
  const DELETE_USER_PATH = DELETE_USER_PATH;
  const LOGIN_WITH_EMAIL_PATH = EMAIL_LOGIN_PATH;
  const LOGIN_WITH_EMAIL_EXTERNAL_URL = EMAIL_LOGIN_EXTERNAL_URL;
  const PASSWORD_RESET_PATH = PASSWORD_RESET_PATH;
}
