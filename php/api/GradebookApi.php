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

    // Page view
    const SECTION_ID_KEY = "sid";

    // Add op
    const P1_KEY = "p1";
    const P2_KEY = "p2";
    const P3_KEY = "p3";
    const P4_KEY = "p4";

    // Add Placeholders
    const P1_PLACEHOLDER = "P1 score...";
    const P2_PLACEHOLDER = "P2 score...";
    const P3_PLACEHOLDER = "P3 score...";
    const P4_PLACEHOLDER = "P4 score...";

    // Remove op
    const EXAM_ID_KEY = "id";

    // Form names
    const ADD_FORM = "add-exam";
    const REMOVE_FORM = "remove-exam";

    // Http types
    const ADD_HTTP_TYPE = "POST";
    const REMOVE_HTTP_TYPE = "POST";
    const PAGE_VIEW_TYPE = "GET";

    // Endpoint
    const ENDPOINT = "http://organicdump.com/section.php";

    private
        $op,
        $p1,
        $p2,
        $p3,
        $p4,
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
        
        header('Location: '.$url);
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
                        !isset($_GET[self::SECTION_ID_KEY]) ||
                        !isset($_POST[self::P2_KEY]) ||
                        !isset($_POST[self::P3_KEY]) ||
                        !isset($_POST[self::P4_KEY])) {
                    $this->errorMode = GradebookErrorMode::UNSET_PARAM;
                    $this->isValidRequest = false;
                    return;
                }

                $this->sectionId = (int)$_GET[self::SECTION_ID_KEY];
                $this->p1 = $_POST[self::P1_KEY];
                $this->p2 = $_POST[self::P2_KEY];
                $this->p3 = $_POST[self::P3_KEY];
                $this->p4 = $_POST[self::P4_KEY];
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

    public function getSectionId() {
        return $this->sectionId;
    }

    public function getExamId() {
        return $this->examId;
    }
}
