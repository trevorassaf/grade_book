<?php

/**
 * Interface for providing templated strings with injected values.
 */
interface Blink {

  /**
   * Returns templated string with injected values.
   *
   * @return string : template with values
   */
  public function forge();
}
