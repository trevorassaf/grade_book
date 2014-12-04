<?php

// -- DEPENDENCIES
require_once(dirname(__FILE__)."/Api.php");

class HomeApiErrorMode {

    const PAGE_VIEW = 0x00;
    const PREQ = 0x01;
    const UNSET_PARAM = 0x05;
}

class HomeApi extends Api {

    // -- CLASS CONSTANTS
    // Ops
    const OP_KEY = "op";
    const ADD_GSI_OP_VALUE = "add-gsi";
    const REMOVE_GSI_OP_VALUE = "remove-gsi";
    const ADD_SECTION_OP_VALUE = "add-section";
    const REMOVE_SECTION_OP_VALUE = "remove-section";

    // Add Gsi
    const GSI_FIRST_NAME_KEY = "gsi-first-name";
    const GSI_LAST_NAME_KEY = "gsi-last-name";

    // Remove Gsi
    const GSI_ID_KEY = "gsi-id";

    // Add Section
    const SECTION_NUMBER_KEY = "sec-num";
    const NUM_STUDENTS_KEY = "num-students";

    // Remove Section
    const SECTION_ID_KEY = "sid";    

    // Form names
    const ADD_GSI_FORM = "add-gsi";
    const REMOVE_GSI_FORM = "remove-gsi";
    const ADD_SECTION_FORM = "add-section";
    const REMOVE_SECTION_FORM = "remove-section";

    // Http types
    const ADD_GSI_METHOD = "POST";
    const REMOVE_GSI_METHOD = "POST";
    const ADD_SECTION_METHOD = "POST";
    const REMOVE_SECTION_METHOD = "POST";

    // Placeholder
    const FIRST_NAME_PLACEHOLDER = "First name...";
    const LAST_NAME_PLACEHOLDER = "Last name...";
    const SECTION_NUMBER_PLACEHOLDER = "Section number...";
    const NUM_STUDENTS_PLACEHOLDER = "Number of students...";

    const ENDPOINT = "http://organicdump.com/projects/gb/php/endpoints/index.php";

    private
        $op,
        $gsiFirstName,
        $gsiLastName,
        $gsiId,
        $sectionNumber,
        $numberStudents,
        $sectionId;

    public function selfRedirect() {
        header(self::ENDPOINT);
    }

    protected function validateRequest() {
        if (!isset($_POST[self::OP_KEY])) {
            error_log("HomeApi::PageView");
            $this->errorMode = HomeApiErrorMode::PAGE_VIEW;
            $this->isValidRequest = true;
            return;
        }

        $this->op = $_POST[self::OP_KEY];
        switch ($this->op) {
            case self::ADD_GSI_OP_VALUE:
                if (!isset($_POST[self::GSI_FIRST_NAME_KEY]) 
                        || !isset($_POST[self::GSI_FIRST_NAME_KEY])) {
                    $this->errorMode = HomeApiErrorMode::UNSET_PARAM;
                    $this->isValidRequest = false; 
                    return;
                }

                $this->gsiFirstName = $_POST[self::GSI_FIRST_NAME_KEY];
                $this->gsiLastName = $_POST[self::GSI_LAST_NAME_KEY];
                break;
            case self::REMOVE_GSI_OP_VALUE:
                if (!isset($_POST[self::GSI_ID_KEY])) {
                    $this->errorMode = HomeApiErrorMode::UNSET_PARAM;
                    $this->isValidRequest = false; 
                    return;
                }

                $this->gsiId = $_POST[self::GSI_ID_KEY];
                break;
            case self::ADD_SECTION_OP_VALUE:
                if (!isset($_POST[self::SECTION_NUMBER_KEY]) 
                        || !isset($_POST[self::NUM_STUDENTS_KEY]) 
                        || !isset($_POST[self::GSI_ID_KEY])) {
                    $this->errorMode = HomeApiErrorMode::UNSET_PARAM;
                    $this->isValidRequest = false; 
                    return;
                }

                $this->sectionNumber = $_POST[self::SECTION_NUMBER_KEY];
                $this->numberStudents = $_POST[self::NUM_STUDENTS_KEY];
                $this->gsiId = $_POST[self::GSI_ID_KEY];
                break;
            case self::REMOVE_SECTION_OP_VALUE:
                if (!isset($_POST[self::SECTION_ID_KEY])) {
                    $this->errorMode = HomeApiErrorMode::UNSET_PARAM;
                    $this->isValidRequest = false; 
                    return; 
                }

                $this->sectionId = $_POST[self::SECTION_ID_KEY];
                break;
            default:
                $this->errorMode = HomeApiErrorMode::UNSET_PARAM;
                $this->isValidRequest = false; 
                return;
        }
        $this->errorMode = HomeApiErrorMode::PREQ;
        $this->isValidRequest = true;
    }

    public function getOp() {
        return $this->op;
    }

    public function getGsiFirstName() {
        return $this->gsiFirstName;
    }

    public function getGsiLastName() {
        return $this->gsiLastName;
    }

    public function getGsiId() {
        return $this->gsiId;
    }

    public function getSectionNumber() {
        return $this->sectionNumber;
    }

    public function getNumberStudents() {
        return $this->numberStudents;
    }

    public function getSectionId() {
        return $this->sectionId;
    }
}
