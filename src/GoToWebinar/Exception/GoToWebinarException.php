<?php

/**
 * @file
 * Exceptions for GoToWebinar.
 */

namespace LogMeIn\GoToWebinar\Exception;

class GoToWebinarException extends \Exception {

  /**
   * Construct the exception handler.
   */
  public function __construct($message, $code = 0, $previous = NULL) {
    parent::__construct($message, $code, $previous);
  }

  /**
   * Stringify the returned error and message.e
   */
  public function __toString() {
    return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
  }
}
