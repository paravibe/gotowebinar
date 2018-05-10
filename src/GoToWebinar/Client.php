<?php

/**
 * @file
 */

namespace LogMeIn\GoToWebinar;

use LogMeIn\GoToWebinar\Core\GoToWebinarConstants;
use LogMeIn\GoToWebinar\Http\GoToWebinarRequest;

class Client {

  protected $baseUrl;
  protected $apiPrefix;

  public function __construct($token) {
    $this->baseUrl = GoToWebinarConstants::REST_API_ENDPOINT;
    $this->apiPrefix = GoToWebinarConstants::REST_API_PREFIX;
    $this->accessToken = $token;
  }

  public function createRequest($requestType, $endpoint) {
    return new GoToWebinarRequest(
      $requestType,
      $endpoint,
      $this->accessToken,
      $this->baseUrl,
      $this->apiPrefix
    );
  }

}
