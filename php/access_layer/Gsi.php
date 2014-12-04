<?php

// -- DEPENDENCIES
require_once(dirname(__FILE__)."/GbDbObject.php");

class Gsi extends GbDbObject {

    // -- CONSTANTS
    const ID_KEY = "id";
    const FIRST_NAME_KEY = "first_name";
    const LAST_NAME_KEY = "last_name";
    
    // -- CLASS VARS
    protected static $tableName = "Gsis";

    protected static $primaryKeys = array(self::ID_KEY);

    private
        $id,
        $firstName,
        $lastName;

    public static function create(
        $first_name,
        $last_name
    ) {
        return static::createObject(
            array(
                self::FIRST_NAME_KEY => $first_name,
                self::LAST_NAME_KEY => $last_name,
            )
        );
        
    }

    public static function fetchById($id) {
        return static::getObjectByPrimaryKey(
            array(
                self::ID_KEY => $id,
            )
        );
    }
    
    public function initInstanceVars($params) {
        $this->id = $params[self::ID_KEY];
        $this->firstName = $params[self::FIRST_NAME_KEY];
        $this->lastName = $params[self::LAST_NAME_KEY];
    }

    public function delete() {
        // Delete children
        $sections = Section::fetchByGsiId($this->id);
        foreach ($sections as $section) {
            $section->delete();
        }

        parent::delete();
    }

    protected function createObjectCallback($init_params) {
        $id = mysql_insert_id();
        $init_params[self::ID_KEY] = $id;
        return $init_params;
    }

    protected function getDbFields() {
        return array(
            self::ID_KEY => $this->id,
            self::FIRST_NAME_KEY => $this->firstName,
            self::LAST_NAME_KEY => $this->lastName,
        );
    }

    protected function getPrimaryKeys() {
        return array(
            self::ID_KEY => $this->id,
        );
    }

    public function getId() {
        return $this->id;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }
} 
