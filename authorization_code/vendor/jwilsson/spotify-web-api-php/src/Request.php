<?php

declare(strict_types=1);

namespace SpotifyWebAPI;

class Request
{
    public const ACCOUNT_URL = 'https://accounts.spotify.com';
    public const API_URL = 'https://api.spotify.com';

    protected $lastResponse = [];
    protected $options = [
        'curl_options' => [],
        'return_assoc' => false,
    ];

    /**
     * Constructor
     * Set options.
     *
     * @param array|object $options Optional. Options to set.
     */
    public function __construct($options = [])
    {
        $this->setOptions($options);
    }

    /**
     * Handle response errors.
     *
     * @param string $body The raw, unparsed response body.
     * @param int $status The HTTP status code, passed along to any exceptions thrown.
     *
     * @throws SpotifyWebAPIException
     * @throws SpotifyWebAPIAuthException
     *
     * @return void
     */
    protected function handleResponseError($body, $status)
    {
        $parsedBody = json_decode($body);
        $error = $parsedBody->error ?? null;

        if (isset($error->message) && isset($error->status)) {
            // It's an API call error
            $exception = new SpotifyWebAPIException($error->message, $error->status);

            if (isset($error->reason)) {
                $exception->setReason($error->reason);
            }

            throw $exception;
        } elseif (isset($parsedBody->error_description)) {
            // It's an auth call error
            throw new SpotifyWebAPIAuthException($parsedBody->error_description, $status);
        } elseif ($body) {
            // Something else went wrong, try to give at least some info
            throw new SpotifyWebAPIException($body, $status);
        } else {
            // Something went really wrong, we don't know what
            throw new SpotifyWebAPIException('An unknown error occurred.', $status);
        }
    }

    /**
     * Parse HTTP response headers and normalize names.
     *
     * @param string $headers The raw, unparsed response headers.
     *
     * @return array Headers as key–value pairs.
     */
    protected function parseHeaders($headers)
    {
        $headers = str_replace("\r\n", "\n", $headers);
        $headers = explode("\n", $headers);

        array_shift($headers);

        $parsedHeaders = [];
        foreach ($headers as $header) {
            [$key, $value] = explode(':', $header, 2);

            $key = strtolower($key);
            $parsedHeaders[$key] = trim($value);
        }

        return $parsedHeaders;
    }

    /**
     * Make a request to the "account" endpoint.
     *
     * @param string $method The HTTP method to use.
     * @param string $uri The URI to request.
     * @param array $parameters Optional. Query string parameters or HTTP body, depending on $method.
     * @param array $headers Optional. HTTP headers.
     *
     * @throws SpotifyWebAPIException
     * @throws SpotifyWebAPIAuthException
     *
     * @return array Response data.
     * - array|object body The response body. Type is controlled by the `return_assoc` option.
     * - array headers Response headers.
     * - int status HTTP status code.
     * - string url The requested URL.
     */
    public function account($method, $uri, $parameters = [], $headers = [])
    {
        return $this->send($method, self::ACCOUNT_URL . $uri, $parameters, $headers);
    }

    /**
     * Make a request to the "api" endpoint.
     *
     * @param string $method The HTTP method to use.
     * @param string $uri The URI to request.
     * @param array $parameters Optional. Query string parameters or HTTP body, depending on $method.
     * @param array $headers Optional. HTTP headers.
     *
     * @throws SpotifyWebAPIException
     * @throws SpotifyWebAPIAuthException
     *
     * @return array Response data.
     * - array|object body The response body. Type is controlled by the `return_assoc` option.
     * - array headers Response headers.
     * - int status HTTP status code.
     * - string url The requested URL.
     */
    public function api($method, $uri, $parameters = [], $headers = [])
    {
        return $this->send($method, self::API_URL . $uri, $parameters, $headers);
    }

    /**
     * Get the latest full response from the Spotify API.
     *
     * @return array Response data.
     * - array|object body The response body. Type is controlled by the `return_assoc` option.
     * - array headers Response headers.
     * - int status HTTP status code.
     * - string url The requested URL.
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    /**
     * Make a request to Spotify.
     * You'll probably want to use one of the convenience methods instead.
     *
     * @param string $method The HTTP method to use.
     * @param string $url The URL to request.
     * @param array $parameters Optional. Query string parameters or HTTP body, depending on $method.
     * @param array $headers Optional. HTTP headers.
     *
     * @throws SpotifyWebAPIException
     * @throws SpotifyWebAPIAuthException
     *
     * @return array Response data.
     * - array|object body The response body. Type is controlled by the `return_assoc` option.
     * - array headers Response headers.
     * - int status HTTP status code.
     * - string url The requested URL.
     */
    public function send($method, $url, $parameters = [], $headers = [])
    {
        // Reset any old responses
        $this->lastResponse = [];

        // Sometimes a stringified JSON object is passed
        if (is_array($parameters) || is_object($parameters)) {
            $parameters = http_build_query($parameters, '', '&');
        }

        $options = [
            CURLOPT_CAINFO => __DIR__ . '/cacert.pem',
            CURLOPT_ENCODING => '',
            CURLOPT_HEADER => true,
            CURLOPT_HTTPHEADER => [],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => rtrim($url, '/'),
        ];

        foreach ($headers as $key => $val) {
            $options[CURLOPT_HTTPHEADER][] = "$key: $val";
        }

        $method = strtoupper($method);

        switch ($method) {
            case 'DELETE': // No break
            case 'PUT':
                $options[CURLOPT_CUSTOMREQUEST] = $method;
                $options[CURLOPT_POSTFIELDS] = $parameters;

                break;
            case 'POST':
                $options[CURLOPT_POST] = true;
                $options[CURLOPT_POSTFIELDS] = $parameters;

                break;
            default:
                $options[CURLOPT_CUSTOMREQUEST] = $method;

                if ($parameters) {
                    $options[CURLOPT_URL] .= '/?' . $parameters;
                }

                break;
        }

        $ch = curl_init();

        curl_setopt_array($ch, array_replace($options, $this->options['curl_options']));

        $response = curl_exec($ch);

        if (curl_error($ch)) {
            $error = curl_error($ch);
            $errno = curl_errno($ch);
            curl_close($ch);

            throw new SpotifyWebAPIException('cURL transport error: ' . $errno . ' ' . $error);
        }

        [$headers, $body] = $this->splitResponse($response);

        $parsedBody = json_decode($body, $this->options['return_assoc']);
        $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $parsedHeaders = $this->parseHeaders($headers);

        $this->lastResponse = [
            'body' => $parsedBody,
            'headers' => $parsedHeaders,
            'status' => $status,
            'url' => $url,
        ];

        curl_close($ch);

        if ($status >= 400) {
            $this->handleResponseError($body, $status);
        }

        return $this->lastResponse;
    }

    /**
     * Set options
     *
     * @param array|object $options Options to set.
     *
     * @return void
     */
    public function setOptions($options)
    {
        $this->options = array_merge($this->options, (array) $options);
    }

    /**
     * Split response into headers and body, taking proxy response headers etc. into account.
     *
     * @param string $response The complete response.
     *
     * @return array An array consisting of two elements, headers and body.
     */
    protected function splitResponse($response)
    {
        $parts = explode("\r\n\r\n", $response, 3);

        // Skip first set of headers for proxied requests etc.
        if (
            preg_match('/^HTTP\/1.\d 100 Continue/', $parts[0]) ||
            preg_match('/^HTTP\/1.\d 200 Connection established/', $parts[0]) ||
            preg_match('/^HTTP\/1.\d 200 Tunnel established/', $parts[0])
        ) {
            return [
                $parts[1],
                $parts[2],
            ];
        }

        return [
            $parts[0],
            $parts[1],
        ];
    }
}
