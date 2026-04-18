<?php

declare(strict_types=1);

namespace App\Core\Http;

final readonly class Response
{
    public const HTTP_OK = 200;
    public const HTTP_CREATED = 201;
    public const HTTP_FOUND = 302;
    public const HTTP_METHOD_NOT_ALLOWED = 405;
    public const HTTP_NOT_FOUND = 404;
    public const HTTP_UNPROCESSABLE_ENTITY = 422;
    public const HTTP_INTERNAL_SERVER_ERROR = 500;

    /**
     * @param string $content
     * @param int $status
     * @param array $headers
     */
    public function __construct(
        private string $content = '',
        private int $status = self::HTTP_OK,
        private array $headers = [],
    ) {}

    /**
     * @param array $data
     * @param int $status
     * @return Response
     */
    public static function json(array $data, int $status = self::HTTP_OK): Response
    {
        return new self(
            json_encode($data, JSON_THROW_ON_ERROR),
            $status,
            ['Content-Type' => 'application/json; charset=utf-8']
        );
    }

    public function send(): void
    {
        http_response_code($this->status);
        foreach ($this->headers as $name => $value) {
            header($name . ': ' . $value);
        }

        echo $this->content;
    }
}
