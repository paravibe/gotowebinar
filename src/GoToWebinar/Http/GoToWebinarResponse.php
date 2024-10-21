<?php

namespace LogMeIn\GoToWebinar\Http;

class GoToWebinarResponse {

  protected $body;
  protected $httpStatusCode;
  protected $httpReasonPhrase;
  protected $headers;
  protected $decodedBody;

  public function __construct($request) {
    $this->body = (string) $request->getBody();
    $this->httpStatusCode = $request->getStatusCode();
    $this->httpReasonPhrase = $request->getReasonPhrase();
    $this->headers = $request->getHeaders();
    $this->decodedBody = $this->decodeBody();
  }

  public function getBody() {
    return $this->body;
  }

  public function getHttpStatusCode() {
    return $this->httpStatusCode;
  }

  public function getHttpReasonPhrase() {
    return $this->httpReasonPhrase;
  }

  public function getHeaders() {
    return $this->headers;
  }

  public function getDecodedBody() {
    return $this->decodedBody;
  }

  /**
   * Decode the JSON response into an array.
   *
   * @return array
   *   Decoded response.
   */
  private function decodeBody() {
    $decodedBody = json_decode($this->body, TRUE);

    if ($decodedBody === NULL) {
      $decodedBody = [];
    }

    return $decodedBody;
  }

}
