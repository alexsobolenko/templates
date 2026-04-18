<?php

declare(strict_types=1);

namespace App\Core\Http;

final readonly class Request
{
    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';
    public const METHOD_PATCH = 'PATCH';
    public const METHOD_DELETE = 'DELETE';

    /**
     * @var ParameterBag
     */
    public ParameterBag $query;

    /**
     * @var ParameterBag
     */
    public ParameterBag $request;

    /**
     * @var ParameterBag
     */
    public ParameterBag $server;

    /**
     * @var ParameterBag
     */
    public ParameterBag $cookies;

    /**
     * @var ParameterBag
     */
    public ParameterBag $headers;

    /**
     * @var string|null
     */
    private ?string $content;

    /**
     * @param string $method
     * @param string $path
     * @param array $query
     * @param array $request
     * @param array $server
     * @param array $cookies
     * @param array $headers
     * @param string|null $content
     */
    public function __construct(
        public string $method,
        public string $path,
        array $query,
        array $request,
        array $server,
        array $cookies,
        array $headers,
        ?string $content
    ) {
        $this->query = new ParameterBag($query);
        $this->request = new ParameterBag($request);
        $this->server = new ParameterBag($server);
        $this->cookies = new ParameterBag($cookies);
        $this->headers = new ParameterBag($headers);
        $this->content = $content;
    }

    /**
     * @return Request
     */
    public static function fromGlobals(): Request
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? self::METHOD_GET);
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

        return new self(
            $method,
            is_string($path) ? $path : '/',
            $_GET,
            $_POST,
            $_SERVER,
            $_COOKIE,
            self::readHeaders(),
            self::readContent()
        );
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @return string|null
     */
    private static function readContent(): ?string
    {
        $content = file_get_contents('php://input');

        return is_string($content) ? $content : null;
    }

    /**
     * @return array
     */
    private static function readHeaders(): array
    {
        if (function_exists('getallheaders')) {
            return getallheaders() ?: [];
        }

        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (!str_starts_with($name, 'HTTP_')) {
                continue;
            }

            $headerName = str_replace('_', '-', substr($name, 5));
            $headers[$headerName] = $value;
        }

        return $headers;
    }
}
