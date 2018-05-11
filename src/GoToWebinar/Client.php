<?php

/**
 * @file
 */

namespace LogMeIn\GoToWebinar;

use LogMeIn\GoToWebinar\Core\GoToWebinarConstants;
use LogMeIn\GoToWebinar\Http\GoToWebinarRequest;

class Client {

  public $values = array();

  protected $baseUrl;
  protected $apiPrefix;
  protected $accessToken;

  public function __construct($token, $values = array()) {
    $this->baseUrl = GoToWebinarConstants::REST_API_ENDPOINT;
    $this->apiPrefix = GoToWebinarConstants::REST_API_PREFIX;
    $this->accessToken = $token;

    // Set additional values received after token request.
    $this->values = array(
      'accountKey' => isset($values['account_key']) ? $values['account_key'] : NULL,
      'accountType' => isset($values['account_type']) ? $values['account_type'] : NULL,
      'email' => isset($values['email']) ? $values['email'] : NULL,
      'firstName' => isset($values['firstName']) ? $values['firstName'] : NULL,
      'lastName' => isset($values['lastName']) ? $values['lastName'] : NULL,
      'organizerKey' => isset($values['organizer_key']) ? $values['organizer_key'] : NULL,
    );
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
