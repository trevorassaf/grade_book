<?php

// -- DEPENDENCIES
require_once(dirname(__FILE__)."/../access_layer/Photo.php");

// -- CONSTANTS
defined("ALBUM_EDIT_DELETE_OPERATION_KEY") ? null : define("ALBUM_EDIT_DELETE_OPERATION_KEY", "op");
defined("ALBUM_EDIT_DELETE_OPERATION_VALUE") ? null : define("ALBUM_EDIT_DELETE_OPERATION_VALUE", "delete");
defined("ALBUM_EDIT_DELETE_ALBUMID_KEY") ? null : define("ALBUM_EDIT_DELETE_ALBUMID_KEY", "albumid");
defined("ALBUM_EDIT_DELETE_PICID_KEY") ? null : define("ALBUM_EDIT_DELETE_PICID_KEY", "picid");

// -- MAIN
// Validate post request
$invalid_req = false;
$error_message = "";
if (!isset($_POST[ALBUM_EDIT_DELETE_OPERATION_KEY])) {
  $invalid_req = true;
  $error_message = "ERROR: delete script invocation missing 'op : delete' POST param";
}
