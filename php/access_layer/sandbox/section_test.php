<?php

require_once(dirname(__FILE__)."/../Section.php");

$section = Section::create(
    0,
    "212/213",
    24,
    9 
);
var_dump($section);
