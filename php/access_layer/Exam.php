<?php

// -- DEPENDENCIES
require_once(dirname(__FILE__)."/GbDbObject.php");

class Exam extends GbDbObject {
    // -- CLASS VARS
    protected static $tableName = "Exams";

    protected static $primaryKeys = array(self::ID_KEY);

    // -- CONSTANTS
    const ID_KEY = "id";
    const FIRST_NAME_KEY = "first_name";
    const LAST_NAME_KEY = "last_name";
    const SECTION_ID_KEY = "section_id";
    const P1_SCORE_KEY = "p1_score";
    const P2_SCORE_KEY = "p2_score";
    const P3_SCORE_KEY = "p3_score";
    const P4_SCORE_KEY = "p4_score";
    const P5_SCORE_KEY = "p5_score";
    const TOTAL_SCORE_KEY = "total_score";

    // -- INSTANCE VARS
    private
        $id,
        $firstName,
        $lastName,
        $sectionId,
        $p1Score,
        $p2Score,
        $p3Score,
        $p4Score,
        $p5Score,
        $totalScore;

    public static function create(
        $first_name,
        $last_name,
        $section_id,
        $p1_score,
        $p2_score,
        $p3_score,
        $p4_score,
        $p5_score,
        $total_score
    ) {
        return static::createObject(
            array(
                self::FIRST_NAME_KEY => $first_name,
                self::LAST_NAME_KEY => $last_name,
                self::SECTION_ID_KEY => $section_id,
                self::P1_SCORE_KEY => $p1_score,
                self::P2_SCORE_KEY => $p2_score,
                self::P3_SCORE_KEY => $p3_score,
                self::P4_SCORE_KEY => $p4_score,
                self::P5_SCORE_KEY => $p5_score,
                self::TOTAL_SCORE_KEY => $total_score
            )
        );
    }

    public static function fetchById($id) {
        return static::getObjectByPrimaryKey(
            array(self::ID_KEY => $id)
        );
    }

    public static function fetchBySectionId($id) {
        return static::getObjectsByParams(
            array(self::SECTION_ID_KEY => $id)
        );
    }

    public function initInstanceVars($params) {
        $this->id = $params[self::ID_KEY];
        $this->firstName = $params[self::FIRST_NAME_KEY];
        $this->lastName = $params[self::LAST_NAME_KEY];
        $this->sectionId = $params[self::SECTION_ID_KEY];
        $this->p1Score = $params[self::P1_SCORE_KEY];
        $this->p2Score = $params[self::P2_SCORE_KEY];
        $this->p3Score = $params[self::P3_SCORE_KEY];
        $this->p4Score = $params[self::P4_SCORE_KEY];
        $this->p5Score = $params[self::P5_SCORE_KEY];
        $this->totalScore = $params[self::TOTAL_SCORE_KEY];
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
            self::SECTION_ID_KEY => $this->sectionId,
            self::P1_SCORE_KEY => $this->p1Score,
            self::P2_SCORE_KEY => $this->p2Score,
            self::P3_SCORE_KEY => $this->p3Score,
            self::P4_SCORE_KEY => $this->p4Score,
            self::P5_SCORE_KEY => $this->p5Score,
            self::TOTAL_SCORE_KEY => $this->totalScore,
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

    public function getSectionId() {
        return $this->sectionId;
    }

    public function getP1Score() {
        return $this->p1Score;
    }
    public function getP2Score() {
        return $this->p2Score;
    }
    public function getP3Score() {
        return $this->p3Score;
    }
    public function getP4Score() {
        return $this->p4Score;
    }
    public function getP5Score() {
        return $this->p5Score;
    }

    public function getTotalScore() {
        return $this->totalScore;
    }

}
