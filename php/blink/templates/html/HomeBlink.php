<?php

// -- DEPENDENCIES
require_once(dirname(__FILE__)."/../../../api/GradebookApi.php");
require_once(dirname(__FILE__)."/../../../access_layer/Gsi.php");
require_once(dirname(__FILE__)."/../../../access_layer/Section.php");
require_once(dirname(__FILE__)."/../../../access_layer/StatSet.php");
require_once(dirname(__FILE__)."/../../Blink.php");
require_once(dirname(__FILE__)."/StatSetBlink.php");

class HomeBlink implements Blink {

    // -- CLASS CONSTANTS
    // -- INSTANCE VARS
    private $gsiSet;

    public function __construct($gsiSet) {
        $this->gsiSet = $gsiSet;
        $this->sortGsiSet($gsiSet);
    }

    public function sortGsiSet($gsi_set) {
        usort($gsi_set, function($a, $b) {
            $a_name = $a->getFirstName() . $a->getLastName();
            $b_name = $b->getFirstName() . $b->getLastName();
            return strcmp($a_name, $b_name);
        }); 
    }

    public function forge() {
        $html = $this->genHeaderHtml();
        $html .= $this->genBodyHtml();
        $html .= $this->genTailHtml();
        return $html;
    }

    public function genHeaderHtml() {
        return "<html><body>";
    }

    public function genTailHtml() {
        return "</body></html>";
    }

    public function genBodyHtml() {
        $html = "";
        
        // Add gradebook header
        $html = "<h1>CHEM 125/126 Exam 1 Score Processor</h1>";

        // Add Section/Gsi forms
        $html .= $this->genAddGsiHtml();
        $html .= $this->genAddSectionHtml();
        $html .= $this->genDisplayStatsHtml();
        $html .= $this->genGsiDisplayHtml($this->gsiSet);

        return $html;
    }

    public function genDisplayStatsHtml() {
        $class_section = Section::fetchClass();
        $class_stats = StatSet::fetchById($class_section->getStatSetId());
        $stat_blink = new StatSetBlink($class_stats); 
        return $stat_blink->forge();
    }

    public function genGsiDisplayHtml($gsi_set) {
        // Render Gsis and Sections
        $html = "";
        $html .= "<h1>Sections</h1>\n";
        foreach ($gsi_set as $gsi) {
            $html .= $this->genGsiHtml($gsi);
        }
        return $html;
    }

    public function genAddGsiHtml() {
        $html = 
            "<div id='add-gsi'>
                <div id='add-gsi-title'>Add New GSI</div>
                <form id='add-gsi-form' name='".HomeApi::ADD_GSI_FORM."' action='".HomeApi::ENDPOINT."' method='".HomeApi::ADD_GSI_METHOD."'>
                    <label class='gsi-first-name'>First Name: </label>           
                    <input type='text' placeholder='".HomeApi::FIRST_NAME_PLACEHOLDER."' name='".HomeApi::GSI_FIRST_NAME_KEY."' required />
                    <label class='gsi-last-name'>Last Name: </label>           
                    <input type='text' placeholder='".HomeApi::LAST_NAME_PLACEHOLDER."' name='".HomeApi::GSI_LAST_NAME_KEY."' required />
                    <input type='hidden' name='".HomeApi::OP_KEY."' value='".HomeApi::ADD_GSI_OP_VALUE."' />
                    <input type='submit' value='Add GSI' />
                </form>
            </div>"; 
        return $html;    
    }

    public function genAddSectionHtml() {
        $gsi_options_html = ""; 
        foreach ($this->gsiSet as $gsi) {
            $first_name = $gsi->getFirstName();
            $last_name = $gsi->getLastName();
            $gsi_id = $gsi->getId();
            $gsi_options_html .= 
                "<option value='{$gsi_id}'>{$first_name} {$last_name}</option>\n";
        }

        $html =
            "<div id='add-section'>
                <div id='add-section-title'>Add New Section</div> 
                <form id='add-section-form' name='".HomeApi::ADD_SECTION_FORM."' action='".HomeApi::ENDPOINT."' method='".HomeApi::ADD_SECTION_METHOD."'>
                    <label class='section-gsi'>GSI: </label>
                    <select name='".HomeApi::GSI_ID_KEY."' required>{$gsi_options_html}</select>
                    <label class='section-number-label'>Section Number: </label>
                    <input type='text' placeholder='".HomeApi::SECTION_NUMBER_PLACEHOLDER."' name='".HomeApi::SECTION_NUMBER_KEY."' required />
                    <label class='num-students-label'>Number of students: </label>
                    <input type='text' placeholder='".HomeApi::NUM_STUDENTS_PLACEHOLDER."' name='".HomeApi::NUM_STUDENTS_KEY."' required />
                    <input type='hidden' name='".HomeApi::OP_KEY."' value='".HomeApi::ADD_SECTION_OP_VALUE."' />
                    <input type='submit' value='Add Section' />
                </form>
            </div>";
        return $gsi_options_html . $html;
    }

    public function genGsiHtml($gsi) {
        // Capture data
        $sections = Section::fetchByGsiId($gsi->getId());
        usort($sections, function($a, $b) {
            return strcmp($a->getSectionNumber(), $b->getSectionNumber());
        });

        $num_sections = count($sections);
        $gsi_name = $gsi->getFirstName() . " " . $gsi->getLastName();
        
        $html = "<div class='gsi'><h3 class='gsi-name-label'>{$gsi_name}</h3>";

        // Remove gsi form
        $html .= "\n<form name='".HomeApi::REMOVE_GSI_FORM."' action='".HomeApi::ENDPOINT."' method='".HomeApi::REMOVE_GSI_METHOD."'>";
        $html .= "\n<input type='hidden' name='".HomeApi::OP_KEY."' value='".HomeApi::REMOVE_GSI_OP_VALUE."' />";
        $html .= "\n<input type='hidden' name='".HomeApi::GSI_ID_KEY."' value='".$gsi->getId()."' />";
        $html .= "\n<input type='submit' value='Remove Gsi' />";
        $html .= "</form>\n";

        // Sections
        for ($i = 0; $i < $num_sections; ++$i) {
            $html .= $this->genSectionHtml($sections[$i]); 
        }    
        $html .= "</div>";
        return $html;
    }

    protected function genSectionHtml($section) {
        $section_url = GradebookApi::genSectionUrl($section);
        $section_number = $section->getSectionNumber();
        $html = "<div class='section'>";
        $html .= "<a href='{$section_url}'>{$section_number}</a>";
        
        // Remove section form
        $html .= "<form name='".HomeApi::REMOVE_SECTION_METHOD."' action='".HomeApi::ENDPOINT."' method='".HomeApi::REMOVE_SECTION_METHOD."'>";
        $html .= "<input type='hidden' name='".HomeApi::SECTION_ID_KEY."' value='".$section->getId()."' />";
        $html .= "<input type='hidden' name='".HomeApi::OP_KEY."' value='".HomeApi::REMOVE_SECTION_OP_VALUE."' />";
        $html .= "<input type='submit' value='Remove Section' />";
        $html .= "</form>";
        $html .= "</div>";
        return $html;
    }
}
