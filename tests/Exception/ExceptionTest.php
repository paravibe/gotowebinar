<?php

/**
 * @file
 * PHPUnit tests for Exception.
 */

use PHPUnit\Framework\TestCase;
use LogMeIn\GoToWebinar\Exception\GoToWebinarException;

class ExceptionTest extends TestCase {

    public function testToString() {
      $exception = new GoToWebinarException('error', '500');
      $this->assertEquals("LogMeIn\GoToWebinar\Exception\GoToWebinarException: [500]: error\n", $exception->__toString());
    }

}
