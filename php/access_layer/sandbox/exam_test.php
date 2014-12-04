<?php

require_once(dirname(__FILE__)."/../Exam.php");

$exam = Exam::create("Trevor", "Assaf", 0, 20, 20, 20, 20, 20, 100);
var_dump($exam);
