<?php

// -- DEPENDENCIES
require_once(dirname(__FILE__)."/warehouse/DatabaseObject.php");

class StatSet extends DatabaseObject {
    // -- CLASS VARS
    protected static $tableName = "StatSets";

    protected static $primaryKeys = array(self::ID_KEY);


    // -- CONSTANTS
    const ID_KEY = "id";
    const NUM_EXAMS_KEY = "num_exams";
    const TOTAL_AVG_SCORE_KEY = "total_avg_score";
    const TOTAL_STD_KEY = "total_std_dev";
    const P1_AVG_KEY = "p1_avg";
    const P2_AVG_KEY = "p2_avg";
    const P3_AVG_KEY = "p3_avg";
    const P4_AVG_KEY = "p4_avg";
    const P1_STD_KEY = "p1_std_dev";
    const P2_STD_KEY = "p2_std_dev";
    const P3_STD_KEY = "p3_std_dev";
    const P4_STD_KEY = "p4_std_dev";

    const PRECISION = 2;

    private
        $id,
        $numExams,
        $totalAvgScore,
        $totalStdDev,
        $p1Avg,
        $p2Avg,
        $p3Avg,
        $p4Avg,
        $p1Std,
        $p2Std,
        $p3Std,
        $p4Std,

    public static function create(
        $num_exams,
        $total_avg,
        $total_std_dev,
        $p1_avg,
        $p2_avg,
        $p3_avg,
        $p4_avg,
        $p1_std,
        $p2_std,
        $p3_std,
        $p4_std,
    ) {
        return static::createObject(
            array(
                self::NUM_EXAMS_KEY => $num_exams,
                self::TOTAL_AVG_SCORE_KEY => $total_avg,
                self::TOTAL_STD_KEY => $total_std_dev,
                self::P1_AVG_KEY => $p1_avg,
                self::P2_AVG_KEY => $p2_avg,
                self::P3_AVG_KEY => $p3_avg,
                self::P4_AVG_KEY => $p4_avg,
                self::P1_STD_KEY => $p1_std,
                self::P2_STD_KEY => $p2_std,
                self::P3_STD_KEY => $p3_std,
                self::P4_STD_KEY => $p4_std,
            )
        );            
    }

    public static function createEmptySet() {
        return self::create(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    }

    public static function fetchById($id) {
        return static::getObjectByPrimaryKey(
            array(self::ID_KEY => $id)
        );
    }

    public function initInstanceVars($params) {
        $this->id = $params[self::ID_KEY]; 
        $this->numExams = $params[self::NUM_EXAMS_KEY];
        $this->totalAvgScore = $params[self::TOTAL_AVG_SCORE_KEY];
        $this->totalStdDev = $params[self::TOTAL_STD_KEY];
        $this->p1Avg = $params[self::P1_AVG_KEY];
        $this->p2Avg = $params[self::P2_AVG_KEY];
        $this->p3Avg = $params[self::P3_AVG_KEY];
        $this->p4Avg = $params[self::P4_AVG_KEY];
        $this->p1Std = $params[self::P1_STD_KEY];
        $this->p2Std = $params[self::P2_STD_KEY]; 
        $this->p3Std = $params[self::P3_STD_KEY]; 
        $this->p4Std = $params[self::P4_STD_KEY];
    }

    protected function createObjectCallback($init_params) {
        $id = mysql_insert_id();
        $init_params[self::ID_KEY] = $id;
        return $init_params;
    }

    protected function getDbFields() {
        return array(
            self::ID_KEY => $this->id,
            self::NUM_EXAMS_KEY => $this->numExams,
            self::TOTAL_AVG_SCORE_KEY => $this->totalAvgScore,
            self::TOTAL_STD_KEY => $this->totalStdDev,
            self::P1_AVG_KEY => $this->p1Avg,
            self::P2_AVG_KEY => $this->p2Avg,
            self::P3_AVG_KEY => $this->p3Avg,
            self::P4_AVG_KEY => $this->p4Avg,
            self::P1_STD_KEY => $this->p1Std,
            self::P2_STD_KEY => $this->p2Std,
            self::P3_STD_KEY => $this->p3Std,
            self::P4_STD_KEY => $this->p4Std,
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

    public function getNumExams() {
        return $this->numExams;
    }

    public function getTotalAvgScore() {
        return $this->round($this->totalAvgScore);
    }

    public function getTotalStdDev() {
        return $this->round($this->totalStdDev);
    }

    public function getP1Avg() {
        return $this->round($this->p1Avg);
    }

    public function getP2Avg() {
        return $this->round($this->p2Avg);
    }

    public function getP3Avg() {
        return $this->round($this->p3Avg);
    }

    public function getP4Avg() {
        return $this->round($this->p4Avg);
    }

    public function getP1Std() {
        return $this->round($this->p1Std);
    }

    public function getP2Std() {
        return $this->round($this->p2Std);
    }

    public function getP3Std() {
        return $this->round($this->p3Std);
    }

    public function getP4Std() {
        return $this->round($this->p4Std);
    }

    private function round($val) {
        return round($val, self::PRECISION);
    }

    // Setters
    public function setTotalAvgScore($score) {
        $this->totalAvgScore = $score;
    }

    public function setTotalStdDev($std) {
        $this->totalStdDev = $std;
    }

    public function setNumExams($num_exams) {
        $this->numExams = $num_exams;
    }

    public function setP1Avg($p1_avg) {
        $this->p1Avg = $p1_avg;
    }

    public function setP2Avg($p2_avg) {
        $this->p2Avg = $p2_avg;
    }

    public function setP3Avg($p3_avg) {
        $this->p3Avg = $p3_avg;
    }

    public function setP4Avg($p4_avg) {
        $this->p4Avg = $p4_avg;
    }

    public function setP1Std($p1_std) {
        $this->p1Std = $p1_std;
    }

    public function setP2Std($p2_std) {
        $this->p2Std = $p2_std;
    }

    public function setP3Std($p3_std) {
        $this->p3Std = $p3_std;
    }

    public function setP4Std($p4_std) {
        $this->p4Std = $p4_std;
    }
}
