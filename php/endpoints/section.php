<?php

// -- DEPENDENCIES
require_once(dirname(__FILE__)."/../api/HomeApi.php");
require_once(dirname(__FILE__)."/../blink/templates/html/HomeBlink.php");
require_once(dirname(__FILE__)."/../blink/templates/html/SectionExamsBlink.php");
require_once(dirname(__FILE__)."/../access_layer/Exam.php");
require_once(dirname(__FILE__)."/../access_layer/Gsi.php");
require_once(dirname(__FILE__)."/../access_layer/Section.php");
require_once(dirname(__FILE__)."/../scripts/stats.php");

function processReq() {
    $gb_api = new GradebookApi();

    if (!$gb_api->isValidRequest()) {
        die('ERROR: bad request');
    }

    if ($gb_api->getErrorMode() != GradebookErrorMode::PAGE_VIEW) {
        switch ($gb_api->getOp()) {
            case GradebookApi::ADD_OP_VALUE:
                processAddOp($gb_api);
                $gb_api->selfRedirect();
                break;
            case GradebookApi::REMOVE_OP_VALUE:
                error_log("REMOVE");
                processRemoveOp($gb_api);
                $gb_api->selfRedirect();
                break;
            default:
                die('ERROR: bad op value');
                break;    
        }
    }

    renderHtml($gb_api);
}

function renderHtml($gb_api) {
    // Recompute section stats
    recomputeSectionStats($gb_api->getSectionId());
    recomputeTotalStats();

    $section = Section::fetchById($gb_api->getSectionId());
    $gsi = Gsi::fetchById($section->getGsiId()); 
    $exams = Exam::fetchBySectionId($section->getId());
    $section_stat_set = StatSet::fetchById($section->getStatSetId());
    
    $gb_blink = new SectionExamsBlink(
        $gsi,
        $section,
        $exams,
        $section_stat_set);
    echo $gb_blink->forge();
}

function processAddOp($gb_api) {
    // Scores
    $p1_score = $gb_api->getP1();
    $p2_score = $gb_api->getP2();
    $p3_score = $gb_api->getP3();
    $p4_score = $gb_api->getP4();

    $total = $p1_score
        + $p2_score
        + $p3_score
        + $p4_score;

    $exam = Exam::create(
        $gb_api->getSectionId(),
        $p1_score,
        $p2_score,
        $p3_score,
        $p4_score,
        $total
    );
}

function processRemoveOp($gb_api) {
    $exam = Exam::fetchById($gb_api->getExamId());
    $exam->delete();
}

function main() {
    processReq();
}

// -- MAIN
main();
