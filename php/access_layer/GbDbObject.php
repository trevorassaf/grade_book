<?php
// -- DEPENDENCIES
require_once(dirname(__FILE__)."/warehouse/DatabaseObject.php");

// -- CONSTANTS
defined("DB_NAME_GB") ? null : define("DB_NAME_GB", "gb");

abstract class GbDbObject extends DatabaseObject {
  // -- CLASS VARIABLES
  protected static $dbName = DB_NAME_GB;

  /**
   * Maps containers to ivars. Child-bearing subclasses should override this method.
   *
   * @Override: DatabaseObjectParent
   */
  protected function initChildren() {}

} 
