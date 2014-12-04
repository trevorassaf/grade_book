<?php

// -- DEPENDENCIES
require_once(dirname(__FILE__)."/Api.php");

class GradebookErrorMode {
    
    const PAGE_VIEW = 0x00;
    const REQ = 0x01;
    const UNSET_PARAM = 0x03;
    const UNKNOWN_OP = 0x04;
}

class GradebookApi extends Api {

    // -- CLASS CONSTANTS
    // Ops
    const OP_KEY = "op";
    const ADD_OP_VALUE = "add";
    const REMOVE_OP_VALUE = "remove-exam";

    // Add op
    const FIRST_NAME_KEY = "first_name";
    const LAST_NAME_KEY = "last_name";
    const P1_KEY = "p1";
    const P2_KEY = "p2";
    const P3_KEY = "p3";
    const P4_KEY = "p4";
    const P5_KEY = "p5";

    // Add Placeholders
    const FIRST_NAME_PLACEHOLDER = "First name...";
    const LAST_NAME_PLACEHOLDER = "Last name...";
    const P1_PLACEHOLDER = "P1 score...";
    const P2_PLACEHOLDER = "P2 score...";
    const P3_PLACEHOLDER = "P3 score...";
    const P4_PLACEHOLDER = "P4 score...";
    const P5_PLACEHOLDER = "P5 score...";

    // Remove op
    const EXAM_ID_KEY = "id";

    // Page View
    const SECTION_ID_KEY = "sid";

    // Form names
    const ADD_FORM = "add-exam";
    const REMOVE_FORM = "remove-exam";

    // Http types
    const ADD_HTTP_TYPE = "POST";
    const REMOVE_HTTP_TYPE = "POST";
    const PAGE_VIEW_TYPE = "GET";

    // Endpoint
    const ENDPOINT = "http://organicdump.com/projects/gb/php/endpoints/section.php";

    private
        $op,
        $firstName,
        $lastName,
        $p1,
        $p2,
        $p3,
        $p4,
        $p5,
        $sectionId,
        $examId;

    public static function genSectionUrl($section) {
        return self::encodeGetRequest(self::ENDPOINT, array(
            self::SECTION_ID_KEY => $section->getId(),
        ));
    }
  private static function encodeGetRequest($url, $params) {
    if ($params == null || empty($params)) {
      return $url;
    }
    $url .= "?";
    foreach ($params as $key => $value) {
      $url .= urlencode($key)."=".urlencode($value)."&";
    }  
    return substr($url, 0, -1);
  }

    public function selfRedirect() {
        $url = self::encodeGetRequest(self::ENDPOINT, array(
            self::SECTION_ID_KEY => $this->sectionId,
        ));
        
        header($url);
    }

    protected function validateRequest() {
        if (!isset($_POST[self::OP_KEY])) {
            if (isset($_GET[self::SECTION_ID_KEY])) {
                $this->sectionId = $_GET[self::SECTION_ID_KEY];
                $this->errorMode = GradebookErrorMode::PAGE_VIEW;
                $this->isValidRequest = true;
            } else {
                $this->errorMode = GradebookErrorMode::UNSET_PARAM;
                $this->isValidRequest = false;
            }
            return;
        }

        $this->op = $_POST[self::OP_KEY];

        switch ($this->op) {
            case self::ADD_OP_VALUE:
                if (!isset($_POST[self::P1_KEY]) ||
                        !isset($_POST[self::FIRST_NAME_KEY]) ||
                        !isset($_POST[self::LAST_NAME_KEY]) ||
                        !isset($_GET[self::SECTION_ID_KEY]) ||
                        !isset($_POST[self::P2_KEY]) ||
                        !isset($_POST[self::P3_KEY]) ||
                        !isset($_POST[self::P4_KEY]) ||
                        !isset($_POST[self::P5_KEY])) {
                    $this->errorMode = GradebookErrorMode::UNSET_PARAM;
                    $this->isValidRequest = false;
                    return;
                }

                $this->sectionId = (int)$_GET[self::SECTION_ID_KEY];
                $this->firstName = $_POST[self::FIRST_NAME_KEY];
                $this->lastName = $_POST[self::LAST_NAME_KEY];
                $this->p1 = $_POST[self::P1_KEY];
                $this->p2 = $_POST[self::P2_KEY];
                $this->p3 = $_POST[self::P3_KEY];
                $this->p4 = $_POST[self::P4_KEY];
                $this->p5 = $_POST[self::P5_KEY];
                $this->errorMode = GradebookErrorMode::REQ;
                $this->isValidRequest = true;
                break;
           case self::REMOVE_OP_VALUE:
               if (!isset($_POST[self::EXAM_ID_KEY]) || !isset($_GET[self::SECTION_ID_KEY])) {
                    $this->errorMode = GradebookErrorMode::UNSET_PARAM;
                    $this->isValidRequest = false;
                    return;
               }
                
               $this->sectionId = (int)$_GET[self::SECTION_ID_KEY];
               $this->examId = (int)$_POST[self::EXAM_ID_KEY];
               $this->errorMode = GradebookErrorMode::REQ;
               $this->isValidRequest = true;
               break;      
           default:
               $this->errorMode = GradebookErrorMode::UNKNOWN_OP_VALUE;
               $this->isValidRequest = false;
               break;    
        }
    }

    public function getOp() {
        return $this->op;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getP1() {
        return $this->p1;
    }

    public function getP2() {
        return $this->p2;
    }

    public function getP3() {
        return $this->p3;
    }

    public function getP4() {
        return $this->p4;
    }

    public function getP5() {
        return $this->p5;
    }

    public function getSectionId() {
        return $this->sectionId;
    }

    public function getExamId() {
        return $this->examId;
    }
}
