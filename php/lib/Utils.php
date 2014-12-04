<?php
class Utils {
  const NUM_BYTES_INT_X64 = 8;
  const NUM_BYTES_INT_X32 = 4;
  
  // -- CLASS VARS
  private static $PHP_FILE_EXT = "php";
  private static $OMITTED_REQUIRED_FILES = array(
    ".",
    "..",
    ".git",
    "sandbox",
  );

  // -- PUBLIC FUNCTIONS
  public static function rrec($path) {
    // Check path validity
    if (!file_exists($path)) {
      throw new Exception("Invalid path specified in Utils.rrec: $path");
    }
    // Require files
    self::rrecHelper($path);
  }

  private static function rrecHelper($path) {
    // Check if null
    if (is_file($path) 
        && pathinfo($path, PATHINFO_EXTENSION) == self::$PHP_FILE_EXT) {
      require_once($path);
    } else if (is_dir($path)){
      $files = scandir($path);
      foreach ($files as $f) {
        // Skip directory if ommitted file
        $omitted = false;
        foreach (self::$OMITTED_REQUIRED_FILES as $of) {
          if ($f == $of) {
            $omitted = true;
            break;
          }
        }
        if (!$omitted) {
          $dir_path = $path."/".$f;
          self::rrecHelper($dir_path);  
        }
      }
    }
  }
  
  public static function isX32() {
    return PHP_INT_SIZE == self::NUM_BYTES_INT_X32;
  }

  public static function isX64() {
    return PHP_INT_SIZE == self::NUM_BYTES_INT_X64;
  }
}
?>
