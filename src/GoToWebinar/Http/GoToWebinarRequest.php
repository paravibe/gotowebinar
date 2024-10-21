<?php

namespace LogMeIn\GoToWebinar\Http;

use GuzzleHttp\Client as HttpClient;
use LogMeIn\GoToWebinar\Core\GoToWebinarConstants;
use LogMeIn\GoToWebinar\Exception\GoToWebinarException;

class GoToWebinarRequest {

  protected $requestType;
  protected $endpoint;
  protected $accessToken;
  protected $baseUrl;
  protected $apiPrefix;
  protected $timeout;
  protected $requestBody;
  protected $returnsStream;
  protected $sink;
  protected $urlQuery;
  protected $headers;

  public function __construct($requestType, $endpoint, $accessToken, $baseUrl, $apiPrefix) {
    $this->requestType = $requestType;
    $this->endpoint = $endpoint;
    $this->accessToken = $accessToken;

    if (!$this->accessToken) {
      throw new GoToWebinarException(GoToWebinarConstants::ERROR_ACCESS_TOKEN);
    }

    $this->baseUrl = $baseUrl;
    $this->apiPrefix = $apiPrefix;
    $this->timeout = 0;
    $this->headers = $this->getDefaultHeaders();
  }

  public function execute($client = NULL) {
    if (is_null($client)) {
      $client = $this->createHttpClient();
    }

    try {
      $result = $client->request(
        $this->requestType,
        $this->getRequestUrl(),
        [
          'body' => $this->requestBody,
          'stream' => $this->returnsStream,
          'timeout' => $this->timeout,
          'sink' => $this->sink,
        ]
      );
    }
    catch (\Exception $e) {
      $result = $e->getResponse();
      $error = new GoToWebinarResponse($result);

      throw new GoToWebinarException($error->getBody(), $error->getHttpStatusCode());
    }

    return new GoToWebinarResponse($result);
  }

  /**
   * Adds custom headers to the request.
   *
   * @param array $headers
   *   An array of custom headers.
   *
   * @return \LogMeIn\GoToWebinar\Http\GoToWebinarRequest
   *   GoToWebinarRequest object.
   */
  public function addHeaders($headers) {
    $this->headers = array_merge($this->headers, $headers);

    return $this;
  }

  /**
   * Attach a body to the request.
   */
  public function attachBody($data) {
    $this->requestBody = $data;

    if (!is_string($data) || !is_a($data, 'GuzzleHttp\\Psr7\\Stream')) {
      $this->requestBody = json_encode($data);
    }

    return $this;
  }

  /**
   * Sets URL query parameters.
   */
  public function setUrlQuery($query) {
    $this->urlQuery = $this->buildUrlQuery($query);

    return $this;
  }

  /**
   * Sets the timeout limit of the Guzzle request.
   *
   * @param string $timeout
   *   The request timeout in ms.
   *
   * @return \LogMeIn\GoToWebinar\Http\GoToWebinarRequest
   *   GoToWebinarRequest object
   */
  public function setTimeout($timeout) {
    $this->timeout = $timeout;

    return $this;
  }

  /**
   * Sets sink for the Guzzle request.
   *
   * @param string $path
   *   Sink path for the HTTP request.
   *
   * @return \LogMeIn\GoToWebinar\Http\GoToWebinarRequest
   *   GoToWebinarRequest object
   */
  public function setSink($path) {
    $this->sink = $path;

    return $this;
  }

  /**
   * Get the proper request URL.
   *
   * @return string
   *   Request URL.
   */
  private function getRequestUrl() {
    $url = $this->apiPrefix . '/' . $this->endpoint;

    if (!empty($this->urlQuery)) {
      $url .= '?' . $this->urlQuery;
    }

    return $url;
  }

  /**
   * Returns default request headers.
   */
  private function getDefaultHeaders() {
    $headers = [
      'Accept' => 'application/json',
      'Content-Type' => 'application/json',
      'Authorization' => 'Bearer ' . $this->accessToken,
    ];

    return $headers;
  }

  private function buildUrlQuery(array $query, $parent = '') {
    $params = [];

    foreach ($query as $key => $value) {
      $key = $parent ? $parent . rawurlencode('[' . $key . ']') : rawurlencode($key);

      // Recurse into children.
      if (is_array($value)) {
        $params[] = $this->buildUrlQuery($value, $key);
      }
      // If a query parameter value is NULL, only append its key.
      elseif (!isset($value)) {
        $params[] = $key;
      }
      else {
        // For better readability of paths in query strings, we decode slashes.
        $params[] = $key . '=' . str_replace('%2F', '/', rawurlencode($value));
      }
    }

    return implode('&', $params);
  }

  /**
   * Creates a new http client.
   */
  protected function createHttpClient() {
    $client = new HttpClient(
      [
        'base_uri' => $this->baseUrl,
        'headers' => $this->headers,
      ]
    );

    return $client;
  }

}
