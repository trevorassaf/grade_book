<?php

// -- DEPENDENCIES
require_once(dirname(__FILE__)."/../api/HomeApi.php");
require_once(dirname(__FILE__)."/../blink/templates/html/HomeBlink.php");
require_once(dirname(__FILE__)."/../access_layer/Exam.php");
require_once(dirname(__FILE__)."/../access_layer/Gsi.php");
require_once(dirname(__FILE__)."/../access_layer/Section.php");
require_once(dirname(__FILE__)."/../scripts/stats.php");

function processReq() {
    $home_api = new HomeApi();
    if (!$home_api->isValidRequest()) {
        die("error :(");
        // TODO handle error
        return;
    }

    if ($home_api->getErrorMode() != HomeApiErrorMode::PAGE_VIEW) { 
        switch ($home_api->getOp()) {
            case HomeApi::ADD_GSI_OP_VALUE:
                processAddGsiOp($home_api);
                break;
            case HomeApi::REMOVE_GSI_OP_VALUE:
                processRemoveGsiOp($home_api);
                break;    
            case HomeApi::ADD_SECTION_OP_VALUE:
                processAddSectionOp($home_api);
                break;
            case HomeApi::REMOVE_SECTION_OP_VALUE:
                processRemoveSectionOp($home_api);
                break;    
            default:
                die("Error, unknown op value");    
                break;
        }
        $home_api->selfRedirect();
    }
    
    recomputeTotalStats();
    $gsi_set = Gsi::fetchAllObjectsFromTable();
    $home_blink = new HomeBlink($gsi_set);
    echo $home_blink->forge();
}

function processAddGsiOp($home_api) {
    GSI::create(
            $home_api->getGsiFirstName(),
            $home_api->getGsiLastName());
}

function processRemoveGsiOp($home_api) {
    $gsi = GSI::fetchById($home_api->getGsiId());
    $gsi->delete();
}

function processAddSectionOp($home_api) {
    Section::create(
        $home_api->getGsiId(),
        $home_api->getSectionNumber(),
        $home_api->getNumberStudents()
    );
}

function processRemoveSectionOp($home_api) {
    $section = Section::fetchById($home_api->getSectionId());
    $section->delete();
}

processReq();
