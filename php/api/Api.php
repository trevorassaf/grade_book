<?php

abstract class Api {

  // -- INSTANCE VARS
  protected 
    $isValidRequest,
    $errorMode;

  public function __construct() {
    $this->validateRequest();
  }

  abstract protected function validateRequest();

  public function isValidRequest() {
    return $this->isValidRequest;
  }

  public function getErrorMode() {
    return $this->errorMode;
  }
}
