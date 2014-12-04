<?php

// -- DEPENDENCIES
require_once(dirname(__FILE__)."/GbDbObject.php");
require_once(dirname(__FILE__). "/StatSet.php");

class Section extends GbDbObject {
    // -- CLASS VARS
    protected static $tableName = "Sections";

    protected static $primaryKeys = array(self::ID_KEY);

    // -- CONSTANTS
    const ID_KEY = "id";
    const GSI_ID_KEY = "gsi_id";
    const SECTION_NUMBER_KEY = "section_number";
    const NUM_STUDENTS_KEY = "num_students";    
    const STAT_SET_ID_KEY = "stat_set_id";

    const CLASS_SECTION_ID = 1; 
    const CLASS_GSI_KEY = -1;
    const CLASS_SECTION_NUMBER = "CHEM 125/126 F14";
    const CLASS_SIZE = 12001;
    
    private
        $id,
        $gsiId,
        $sectionNumber,
        $numStudents,
        $statSetId;

    public static function create(
        $gsi_id,
        $section_number,
        $num_students
    ) {

        $stat_set = StatSet::createEmptySet();

        return static::createObject(
            array(
                self::GSI_ID_KEY => $gsi_id,
                self::SECTION_NUMBER_KEY => $section_number,
                self::NUM_STUDENTS_KEY => $num_students,
                self::STAT_SET_ID_KEY => $stat_set->getId(),
            )
        );
    } 

    public static function createClass() {
        $class = self::create(
            self::CLASS_GSI_KEY,
            self::CLASS_SECTION_NUMBER,
            self::CLASS_SIZE
        );

        $class->setId(self::CLASS_SECTION_ID);
        $class->save();
        return $class;
    }

    public static function fetchClass() {
        $class = self::fetchById(self::CLASS_SECTION_ID);
        if (!isset($class)) {
            $class = self::createClass();
        }
        return $class;
    }

    public static function fetchById($id) {
        return static::getObjectByPrimaryKey(
            array(self::ID_KEY => $id)
        );
    }

    public static function fetchByGsiId($id) {
        return static::getObjectsByParams(
            array(self::GSI_ID_KEY => $id)
        );
    }

    public function initInstanceVars($params) {
        $this->id = $params[self::ID_KEY];
        $this->gsiId = $params[self::GSI_ID_KEY];
        $this->sectionNumber = $params[self::SECTION_NUMBER_KEY]; 
        $this->numStudents = $params[self::NUM_STUDENTS_KEY];
        $this->statSetId = $params[self::STAT_SET_ID_KEY];
    }

    public function delete() {
        $exams = Exam::fetchBySectionId($this->id);
        $stat_set = StatSet::fetchById($this->statSetId);
        $stat_set->delete();
        foreach ($exams as $exam) {
            $exam->delete();
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
            self::GSI_ID_KEY => $this->gsiId,
            self::SECTION_NUMBER_KEY => $this->sectionNumber,
            self::NUM_STUDENTS_KEY => $this->numStudents,
            self::STAT_SET_ID_KEY => $this->statSetId,
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

    public function getGsiId() {
        return $this->gsiId;
    }

    public function getSectionNumber() {
        return $this->sectionNumber;
    }

    public function getNumStudents() {
        return $this->numStudents;
    }

    public function getStatSetId() {
        return $this->statSetId;
    }

    public function setId($id) {
        $this->id = $id;
    }
}    
