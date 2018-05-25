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
   * Stringify the returned error and message.
   */
  public function __toString() {
    if ($json_data = json_decode($this->message, TRUE)) {
      $this->message = $this->formatJsonError($json_data);
    }

    return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
  }

  /**
   * Formats JSON data into string.
   *
   * @param array $data
   *   Data array.
   *
   * @return string
   *   Formatted message.
   */
  private function formatJsonError($data) {
    $output = '';

    foreach ($data as $key => $value) {
      if (is_array($value)) {
        $value = self::formatJsonError($value);
      }

      $output[] = "{$key}: {$value}";
    }

    return implode('; ', $output);
  }
}
