<?php

namespace LogMeIn\GoToWebinar;

use LogMeIn\GoToWebinar\Core\GoToWebinarConstants;
use LogMeIn\GoToWebinar\Http\GoToWebinarRequest;

class Client {

  public $values = [];

  protected $baseUrl;
  protected $apiPrefix;
  protected $accessToken;

  public function __construct($token, $values = []) {
    $this->baseUrl = GoToWebinarConstants::REST_API_ENDPOINT;
    $this->apiPrefix = GoToWebinarConstants::REST_API_PREFIX;
    $this->accessToken = $token;

    // Set additional values received after token request.
    $this->values = [
      'accountKey' => $values['account_key'] ?? NULL,
      'accountType' => $values['account_type'] ?? NULL,
      'email' => $values['email'] ?? NULL,
      'firstName' => $values['firstName'] ?? NULL,
      'lastName' => $values['lastName'] ?? NULL,
      'organizerKey' => $values['organizer_key'] ?? NULL,
    ];
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
