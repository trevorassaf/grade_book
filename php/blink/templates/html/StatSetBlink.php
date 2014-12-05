<?php

// -- DEPENDENCIES
require_once(dirname(__FILE__)."/../../../api/GradebookApi.php");
require_once(dirname(__FILE__)."/../../../access_layer/Gsi.php");
require_once(dirname(__FILE__)."/../../../access_layer/Section.php");
require_once(dirname(__FILE__)."/../../../access_layer/StatSet.php");
require_once(dirname(__FILE__)."/../../Blink.php");


class StatSetBlink implements Blink {


    private $statSet;

    public function __construct($stat_set) {
        $this->statSet = $stat_set;
    }

    public function forge() {
        $html = "<div class='stat-set'>";
        $html .= "<div class='stat-set-label'><h3>Exam Statistics</h3></div>";
        $html .= "<table class='stat-set-table' border='1'>"; 
        // Column labels
        $html .= "<tr class='column-labels'>";    
        $html .= "<td>Problems</td>";
        $html .= "<td class='p1-label'>P1</td>";
        $html .= "<td class='p2-label'>P2</td>";
        $html .= "<td class='p3-label'>P3</td>";
        $html .= "<td class='p4-label'>P4</td>";
        $html .= "<td class='total-label'>Total</td>";
        $html .= "</tr>";

        // Problem Averages
        $html .= "<tr class='problem-avgs'>";
        $html .= "<td class='avg-row-label'>Problem Average</td>";
        $html .= "<td class='p1-avg'>".$this->statSet->getP1Avg()."</td>";
        $html .= "<td class='p2-avg'>".$this->statSet->getP2Avg()."</td>";
        $html .= "<td class='p3-avg'>".$this->statSet->getP3Avg()."</td>";
        $html .= "<td class='p4-avg'>".$this->statSet->getP4Avg()."</td>";
        $html .= "<td class='total-avg'>".$this->statSet->getTotalAvgScore()."</td>";
        $html .= "</tr>"; 

        // Problem Std. Deviations 
        $html .= "<tr class='problem-stdev'>";
        $html .= "<td class='avg-row-label'>Problem Std. Deviations</td>";
        $html .= "<td class='p1-stdev'>".$this->statSet->getP1Std()."</td>";
        $html .= "<td class='p2-stdev'>".$this->statSet->getP2Std()."</td>";
        $html .= "<td class='p3-stdev'>".$this->statSet->getP3Std()."</td>";
        $html .= "<td class='p4-stdev'>".$this->statSet->getP4Std()."</td>";
        $html .= "<td class='total-std'>".$this->statSet->getTotalStdDev()."</td>";
        $html .= "</tr>"; 

        $html .= "</table>";
        $html .= "</div>";
        
        return $html;
    }


}
