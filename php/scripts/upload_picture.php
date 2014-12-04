<?php
/**
 * TODO:
 *  - specifiy picture dir path
 *  - get album/user info
 */

// -- DEPENDENCIES
// -- CONSTANTS
defined("PICTURES_DIR_PATH") ? null : define("PICTURES_DIR_PATH", "...");
defined("ALBUM_EDIT_ADD_OPERATION_KEY") ? null : define("ALBUM_EDIT_ADD_OPERATION_KEY", "add");
defined("ALBUM_EDIT_ALBUMID_KEY") ? null : define("ALBUM_EDIT_ALBUMID_KEY", "albumid");
defined("ALBUM_EDIT_CAPTION_KEY") ? null : define("ALBUM_EDIT_CAPTION_KEY", "caption");

// -- MAIN: taken from http://php.net/manual/en/features.file-upload.php
header('Content-Type: text/plain; charset=utf-8');

try {
  $file = $_FILES['file'];

  // Undefined | Multiple Files | $_FILES Corruption Attack
  // If this request falls under any of them, treat it invalid.
  if (!isset($file['error']) || is_array($file['error'])) {
    throw new RuntimeException('Invalid parameters.');
  }

  // Check $_FILES['upfile']['error'] value.
  switch ($file['error']) {
    case UPLOAD_ERR_OK:
      break;
    case UPLOAD_ERR_NO_FILE:
      throw new RuntimeException('No file sent.');
    case UPLOAD_ERR_INI_SIZE:
    case UPLOAD_ERR_FORM_SIZE:
      throw new RuntimeException('Exceeded filesize limit.');
    default:
      throw new RuntimeException('Unknown errors.');
  }

  // You should also check filesize here. 
  if ($file['size'] > 1000000) {
    throw new RuntimeException('Exceeded filesize limit.');
  }

  // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
  // Check MIME Type by yourself.
  $finfo = new finfo(FILEINFO_MIME_TYPE);
  if (false === $image_type = array_search(
    $finfo->file($file['tmp_name']),
    array(
      'jpg' => 'image/jpeg',
      'png' => 'image/png',
      'gif' => 'image/gif',
    ),
    true
  )) {
    throw new RuntimeException('Invalid file format.');
  }

  // Validate post request
  $invalid_post_req = false;
  $error_message = "";
  if (!isset($_POST[ALBUM_EDIT_ADD_OPERATION_KEY]) {
    $invalid_post_req = true;
    $error_message = "ERROR: photo add script called but add operation is not specified."; 
  } else if (!isset($_POST[ALBUM_EDIT_ALBUMID_KEY])) {
    $invalid_post_req = true;
    $error_message = "ERROR: photo add script called but album is not specified.";
  } else if (!isset($_POST[ALBUM_EDIT_CAPTION_KEY])) {
    $invalid_post_req = true;
    $error_message = "ERROR: photo add script called without caption post param."  
  }

  if ($invalid_post_req) {
    unlink($file['tmp_name']);
    die($error_message);
  }

  // Fetch user/album data
  $albumid = $_POST[ALBUM_EDIT_ALBUMID_KEY];
  $album = Album::fetchByAlbumId($albumid);
  $album_name = $album->getTitle();
  $user_name = $album->getUserName();

  // Create picture hash
  $pic_name = $file['name'];
  $hashed_pic_str_input = $pic_name . $album_name . $user_name;
  $hashed_pic_str = sha1($hashed_pic_str_input);

  // Create new picture path
  $uploaded_pic_file = $hashed_pic_str . "." . $image_type;
  $uploaded_pic_copy_path = PICTURES_DIR_PATH . "/" . $uploaded_pic_file;

  // Move uploaded pic to pictures dir
  $pic_moved_successfully = move_uploaded_file(
    $file['tmp_name'],
    $uploaded_pic_copy_path
  );

  // Validate pic move
  if (!$pic_moved_successfully) {
    throw new RuntimeException('Failed to move uploaded file.');
  }

  // Get timestamp uploaded file
  // $php_date_time = new DateTime();
  // $unix_timestamp = $php_date_time->getTimestamp();
  // $uploaded_date = date('Y-m-d', $unix_timestamp);
  $uploaded_date = Session::getCurrentDate();

  // Add picture to db album
  $photo = Photo::create(
    $picid,
    $uploaded_pic_file,
    $image_type,
    $uploded_date
  );

  // Add new contains assoc
  $contains = Contain::create(
    $album->getAlbumId(),
    $hashed_pic_str,
    $_POST[ALBUM_EDIT_CAPTION_KEY]
  );

  // Update album last updated time
  $album->setLastUpdated($uploaded_date);
  $album->save();

} catch (RuntimeException $e) {
  die($e->getMessage());
}


