<?php

// -- DEPENDENCIES
require_once(dirname(__FILE__)."/../api/HomeApi.php");
require_once(dirname(__FILE__)."/../blink/templates/html/HomeBlink.php");
require_once(dirname(__FILE__)."/../blink/templates/html/SectionExamsBlink.php");
require_once(dirname(__FILE__)."/../access_layer/Exam.php");
require_once(dirname(__FILE__)."/../access_layer/Gsi.php");
require_once(dirname(__FILE__)."/../access_layer/Section.php");

function recomputeSectionStats($section_id) {
    $section = Section::fetchById($section_id);
    $section_stat_set = StatSet::fetchById($section->getStatSetId());
    $section_exams = Exam::fetchBySectionId($section_id);
    computeStats($section_stat_set, $section_exams);
}

function recomputeTotalStats() {
    $class_section = Section::fetchClass(); 
    $class_stat_set = StatSet::fetchById($class_section->getStatSetId());
    $all_exams = Exam::fetchAllObjectsFromTable();
    computeStats($class_stat_set, $all_exams);
}

function computeStats($stat_set, $exams) {
    $total_scores = array();
    $p1_scores = array();
    $p2_scores = array();
    $p3_scores = array();
    $p4_scores = array();
    $p5_scores = array();
    foreach ($exams as $exam) {
        $total_scores[] = $exam->getTotalScore();
        $p1_scores[] = $exam->getP1Score();
        $p2_scores[] = $exam->getP2Score();
        $p3_scores[] = $exam->getP3Score();
        $p4_scores[] = $exam->getP4Score();
        $p5_scores[] = $exam->getP5Score();
    } 
    
    $num_exams = count($exams);
    $total_avg = computeAvg($total_scores);
    $total_std = stats_standard_deviation($total_scores);
    $p1_avg = computeAvg($p1_scores);
    $p1_std = stats_standard_deviation($p1_scores);
    $p2_avg = computeAvg($p2_scores);
    $p2_std = stats_standard_deviation($p2_scores);
    $p3_avg = computeAvg($p3_scores);
    $p3_std = stats_standard_deviation($p3_scores);
    $p4_avg = computeAvg($p4_scores);
    $p4_std = stats_standard_deviation($p4_scores);
    $p5_avg = computeAvg($p5_scores);
    $p5_std = stats_standard_deviation($p5_scores);

    $stat_set->setNumExams($num_exams);
    error_log($total_avg);
    $stat_set->setTotalAvgScore($total_avg);
    $stat_set->setTotalStdDev($total_std);
    
    $stat_set->setP1Avg($p1_avg);
    $stat_set->setP2Avg($p2_avg);
    $stat_set->setP3Avg($p3_avg);
    $stat_set->setP4Avg($p4_avg);
    $stat_set->setP5Avg($p5_avg);
    
    $stat_set->setP1Std($p1_std);
    $stat_set->setP2Std($p2_std);
    $stat_set->setP3Std($p3_std);
    $stat_set->setP4Std($p4_std);
    $stat_set->setP5Std($p5_std);
    
    $stat_set->save();
}

function computeAvg($arr) {
    if (count($arr) == 0) {
        return 0;
    }

    return array_sum($arr) / count($arr);
}

function stats_standard_deviation(array $a, $sample = false) {
    $n = count($a);
    if ($n === 0) {
        return 0;
    }
    if ($sample && $n === 1) {
        return 0;
    }
    $mean = array_sum($a) / $n;
    $carry = 0.0;
    foreach ($a as $val) {
        $d = ((double) $val) - $mean;
        $carry += $d * $d;
    };
    if ($sample) {
        --$n;
    }
    return sqrt($carry / $n);
}

