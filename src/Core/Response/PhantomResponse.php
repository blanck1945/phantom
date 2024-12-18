<?php

namespace Core\Response;

class PhantomResponse
{
    private static array $headers = [];
    private static mixed $body = null;

    /**
     * Add a header to the response.
     *
     * @param string $key
     * @param string $value
     * @return void
     */
    static public function addHeader(string $key, string $value): void
    {
        self::$headers[$key] = $value;
    }

    /**
     * Set the response body.
     *
     * @param mixed $body
     * @return void
     */
    static public function setBody(mixed $body): void
    {
        self::$body = $body;
    }

    /**
     * Send the response to the client.
     *
     * @return void
     */
    static public function send(int $statusCode, string|array $message = ''): void
    {
        // Set the HTTP response code
        http_response_code($statusCode);

        // Send headers
        foreach (self::$headers as $key => $value) {
            header("$key: $value");
        }

        // Send the body
        if (is_array($message) || is_object($message)) {
            // Convert array or object to JSON
            header('Content-Type: application/json');
            echo json_encode($message);
        } else {
            echo $message;
        }

        // Terminate script execution
        exit;
    }

    /**
     * Send a JSON response.
     *
     * @param mixed $data
     * @param int $statusCode
     * @return void
     */
    static public function sendJson(mixed $data, int $statusCode = 200): void
    {
        self::addHeader('Content-Type', 'application/json');
        self::setBody($data);
        self::send($statusCode);
    }

    /**
     * Send an HTML response.
     *
     * @param string $html
     * @param int $statusCode
     * @return void
     */
    static public function sendHtml(string $html, int $statusCode = 200): void
    {
        self::addHeader('Content-Type', 'text/html');
        self::setBody($html);
        self::send($statusCode);
    }

    /**
     * Redirect to a different URL.
     *
     * @param string $url
     * @param int $statusCode
     * @return void
     */
    static public function redirect(string $url, int $statusCode = 302): void
    {
        self::addHeader('Location', $url);
        self::send($statusCode);
    }

    /**
     * Send a 400 response.
     *
     * @return void
     */
    static public function send400(string $errorMessage = "Bad Request"): void
    {
        self::send(400, ['error' => $errorMessage]);
    }

    /**
     * Send a 401 response.
     *
     * @return void
     */
    static public function send401(string $errorMessage = "Unauthorized"): void
    {
        self::send(401, ['error' => $errorMessage]);
    }

    /**
     * Send a 404 response.
     *
     * @return void
     */
    static public function send404(string $errorMessage = "Not Found"): void
    {
        self::send(404, ['error' => $errorMessage]);
    }
}
