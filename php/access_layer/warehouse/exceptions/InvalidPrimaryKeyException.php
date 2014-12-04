<?php

// -- CONSTANTS
defined("COLLIDING_PRIMARY_KEY_MESSAGE") 
  ? null : define("COLLIDING_PRIMARY_KEY_MESSAGE", "Value for primary key already exists in table");
defined("NONEXISTANT_PRIMARY_KEY_MESSAGE")
  ? null : define("NONEXISTANT_PRIMARY_KEY_MESSAGE", "Specified primary key does not exist");

class InvalidPrimaryKeyException extends Exception {

  // -- INSTANCE VARS 
  private
    $key,
    $value,
    $errorMessage;

  public function __construct($key, $value, $errorMessage) {
    $this->key = $key;
    $this->value = $value;
    $this->errorMessage = $errorMessage;
  }

  public static function createForCollidingPrimaryKey($key, $value) {
    return new static($key, $value, COLLIDING_PRIMARY_KEY_MESSAGE); 
  }

  public static function createForNonexistantPrimaryKey($key, $value) {
    return new static($key, $value, NONEXISTANT_PRIMARY_KEY_MESSAGE);
  }

  public function __toString() {
    return "ERROR: " . $this->errorMessage . " <key: $this->key, value: $this->value>";
  }
}
