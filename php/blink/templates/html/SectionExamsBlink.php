<?php

// -- DEPENDENCIES
require_once(dirname(__FILE__)."/../../../api/GradebookApi.php");
require_once(dirname(__FILE__)."/../../../access_layer/Gsi.php");
require_once(dirname(__FILE__)."/../../../access_layer/Section.php");
require_once(dirname(__FILE__)."/../../../access_layer/StatSet.php");
require_once(dirname(__FILE__)."/StatSetBlink.php");
require_once(dirname(__FILE__)."/../../Blink.php");

class SectionExamsBlink implements Blink {

    private
        $gsi,
        $section,
        $exams,
        $sectionStatSet;

    public function __construct($gsi, $section, $exams, $sectionStatSet) {
        $this->gsi = $gsi;
        $this->section = $section;
        $this->exams = $exams;
        $this->sectionStatSet = $sectionStatSet;

        // Sort exams by student name
        usort($this->exams, function($a, $b) {
            return $b->getId() - $a->getId();
        });
    }

    public function forge() {
        $html = $this->genHeaderHtml();
        $html .= $this->genBodyHtml();
        $html .= $this->genTailHtml();
        return $html;
    }

    public function genBodyHtml() {
        // Capture dynamic values for use in html
        $gsi_name = $this->gsi->getFirstName() . " " . $this->gsi->getLastName(); 
        $section_number = $this->section->getSectionNumber();
        $num_exams = count($this->exams);
        $stat_blink = new StatSetBlink($this->sectionStatSet);

        // Construct html
        $html = "<div id='exam-section'>";
        $html .= "<div id='section-number'><h1>Exam Scores for CHEM 125/126 Section {$section_number}</h1></div>";
        $html .= "<a href='".HomeApi::ENDPOINT."'>Back to Class Data</a>";
        $html .= "<div id='gsi-name'><h3>GSI: {$gsi_name}</h3></div>";
        $html .= "<div id='num-exam-fraction'>Number of Exams: ".$num_exams ." / ". $this->section->getNumStudents()."</div>";
        $html .= $stat_blink->forge();
        $html .= $this->genAddExamHtml();

        $html .= "<div id='exam-results'>";
        $html .= "<h3>Exam Scores</h3>";
        $html .= "<table id='exam-results-table'>";
        for ($i = 0; $i < $num_exams; ++$i) {
            $exam = $this->exams[$i];
            $order = $i + 1;
                
            $html .= "<tr class='exam-row'>";
            // $html .= "<td class='order'>{$order}</td>"; 
            $html .= "<td class='p1-score'>".$exam->getP1Score()."</td>";
            $html .= "<td class='p2-score'>".$exam->getP2Score()."</td>";
            $html .= "<td class='p3-score'>".$exam->getP3Score()."</td>";
            $html .= "<td class='p4-score'>".$exam->getP4Score()."</td>";
            $html .= "<td class='total-score'>".$exam->getTotalScore()."</td>";
            $html .= "<td class='delete-exam'>";
            $html .= "<form name='".GradebookApi::REMOVE_FORM."' action='".GradebookApi::genSectionUrl($this->section)."' method='".GradebookApi::REMOVE_HTTP_TYPE."'>";
            $html .= "<input type='hidden' name='".GradebookApi::OP_KEY."' value='".GradebookApi::REMOVE_OP_VALUE."' />";
            $html .= "<input type='hidden' name='".GradebookApi::EXAM_ID_KEY."' value='".$exam->getId()."' />";
            $html .= "<input type='hidden' name='".GradebookApi::SECTION_ID_KEY."' value='".$this->section->getId()."' />";
            $html .= "<input type='submit' value='Remove Exam' />";
            $html .= "</form>";
            $html .= "</tr>";
        }
        $html .= "</ul>";
        $html .= "</div>";
        $html .= "</div>";

        return $html;
    }

    public function genAddExamHtml() {
        $html = "";
        $html .= "<div class='add-new-exam'>";
        $html .= "<h3>Add New Exam</h3>";
        $html .= "<form name='".GradebookApi::ADD_FORM."' action='".GradebookApi::genSectionUrl($this->section)."' method='".GradebookApi::ADD_HTTP_TYPE."'>";
        $html .= "<input type='hidden' name='".GradebookApi::OP_KEY."' value='".GradebookApi::ADD_OP_VALUE."' />";
        $html .= "<input type='hidden' name='".GradebookApi::SECTION_ID_KEY."' value='".$this->section->getId()."' required />";
        $html .= "<label for='p1-input'>P1 Score: </label>";
        $html .= "<input class='score-input' id='p1-input' type='number' min='0' max='25' name='".GradebookApi::P1_KEY."' placeholder='".GradebookApi::P1_PLACEHOLDER."' required />";
        $html .= "<label for='p2-input'>P2 Score: </label>";
        $html .= "<input class='score-input' id='p2-input' type='number' min='0' max='25' name='".GradebookApi::P2_KEY."' placeholder='".GradebookApi::P2_PLACEHOLDER."' required />";
        $html .= "<label for='p3-input'>P3 Score: </label>";
        $html .= "<input class='score-input' id='p3-input' type='number' min='0' max='25' name='".GradebookApi::P3_KEY."' placeholder='".GradebookApi::P3_PLACEHOLDER."' required />";
        $html .= "<label for='p4-input'>P4 Score: </label>";
        $html .= "<input class='score-input' id='p4-input' type='number' min='0' max='25' name='".GradebookApi::P4_KEY."' placeholder='".GradebookApi::P4_PLACEHOLDER."' required />";
        $html .= "<input type='submit' value='Add Exam' />";
        $html .= "</form>";
        $html .= "</div>";
        return $html;
    }

    public function genHeaderHtml() {
        $html = "<html>";
        $html .= "<head>";
        $html .= "<link rel='stylesheet' type='text/css' href='styles.css'>";
        $html .= "</head>";
        $html .= "<body>";
        return $html;
    }

    public function genTailHtml() {
        return "</body></html>";
    }
}
