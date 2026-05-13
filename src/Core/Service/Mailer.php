<?php

declare(strict_types=1);

namespace App\Core\Service;

use App\Exception\Mail\MailerException;

final readonly class Mailer
{
    /**
     * @param ViewRenderer $viewRenderer
     * @param string $dsn
     * @param string $fromAddress
     * @param string|null $fromName
     */
    public function __construct(
        private ViewRenderer $viewRenderer,
        private string $dsn,
        private string $fromAddress,
        private ?string $fromName = null
    ) {}

    /**
     * @param array $toAddresses
     * @param string $subject
     * @param string $body
     * @return void
     */
    public function send(array $toAddresses, string $subject, string $body): void
    {
        $this->sendMessage($toAddresses, $subject, $body, 'text/plain; charset=UTF-8');
    }

    /**
     * @param array $toAddresses
     * @param string $subject
     * @param string $template
     * @param array $context
     * @return void
     */
    public function sendTemplate(array $toAddresses, string $subject, string $template, array $context = []): void
    {
        $message = $this->viewRenderer->renderContent($template, $context, 'layout/mail');
        $this->sendMessage($toAddresses, $subject, $message, 'text/html; charset=UTF-8');
    }

    /**
     * @param array $toAddresses
     * @param string $subject
     * @param string $body
     * @param string $contentType
     * @return void
     */
    private function sendMessage(array $toAddresses, string $subject, string $body, string $contentType): void
    {
        if ($toAddresses === []) {
            throw new MailerException('Mailer requires at least one recipient address.');
        }

        if ($this->fromAddress === '') {
            throw new MailerException('MAIL_FROM_ADDRESS is not configured.');
        }

        if ($this->dsn === '') {
            throw new MailerException('MAILER_DSN is not configured.');
        }

        $parsedDsn = parse_url($this->dsn);
        if ($parsedDsn === false) {
            throw new MailerException('MAILER_DSN is invalid.');
        }

        $scheme = $parsedDsn['scheme'] ?? null;
        $host = $parsedDsn['host'] ?? null;
        $port = $parsedDsn['port'] ?? null;

        if (!is_string($scheme) || !in_array($scheme, ['smtp', 'tcp'], true)) {
            throw new MailerException('MAILER_DSN must use smtp:// or tcp:// scheme.');
        }

        if (!is_string($host) || $host === '') {
            throw new MailerException('MAILER_DSN must contain a host.');
        }

        $socket = @stream_socket_client(
            sprintf('tcp://%s:%d', $host, is_int($port) ? $port : 25),
            $errorCode,
            $errorMessage,
            5
        );

        if (!is_resource($socket)) {
            throw new MailerException(
                sprintf('Failed to connect to mail server: [%d] %s', $errorCode, $errorMessage)
            );
        }

        stream_set_timeout($socket, 5);

        try {
            $this->assertResponseCode($socket, [220]);
            $this->writeCommand($socket, 'EHLO localhost');
            $this->assertResponseCode($socket, [250]);

            $this->writeCommand($socket, 'MAIL FROM:<' . $this->fromAddress . '>');
            $this->assertResponseCode($socket, [250]);

            foreach ($toAddresses as $toAddress) {
                $this->writeCommand($socket, 'RCPT TO:<' . $toAddress . '>');
                $this->assertResponseCode($socket, [250, 251]);
            }

            $fromHeader = empty($this->fromName)
                ? $this->fromAddress
                : sprintf('%s <%s>', $this->encodeHeader($this->fromName), $this->fromAddress);
            $headers = [
                'From: ' . $fromHeader,
                'To: ' . implode(', ', $toAddresses),
                'Subject: ' . $this->encodeHeader($subject),
                'MIME-Version: 1.0',
                'Content-Type: ' . $contentType,
                'Content-Transfer-Encoding: 8bit',
            ];

            $this->writeCommand($socket, 'DATA');
            $this->assertResponseCode($socket, [354]);
            fwrite(
                $socket,
                implode("\r\n", $headers) . "\r\n\r\n" . $this->escapeBody($body) . "\r\n.\r\n"
            );
            $this->assertResponseCode($socket, [250]);

            $this->writeCommand($socket, 'QUIT');
        } finally {
            fclose($socket);
        }
    }

    /**
     * @param resource $socket
     * @param string $command
     * @return void
     */
    private function writeCommand($socket, string $command): void
    {
        fwrite($socket, $command . "\r\n");
    }

    /**
     * @param resource $socket
     * @param array $allowedCodes
     * @return void
     */
    private function assertResponseCode($socket, array $allowedCodes): void
    {
        $response = $this->readResponse($socket);
        $code = (int) substr($response, 0, 3);
        if (!in_array($code, $allowedCodes, true)) {
            throw new MailerException('Unexpected SMTP response: ' . trim($response));
        }
    }

    /**
     * @param resource $socket
     * @return string
     */
    private function readResponse($socket): string
    {
        $response = '';
        while (($line = fgets($socket)) !== false) {
            $response .= $line;
            if (strlen($line) < 4 || $line[3] !== '-') {
                break;
            }
        }

        return $response;
    }

    /**
     * @param string $value
     * @return string
     */
    private function encodeHeader(string $value): string
    {
        if (preg_match('/^[\x20-\x7E]*$/', $value) === 1) {
            return $value;
        }

        return sprintf('=?UTF-8?B?%s?=', base64_encode($value));
    }

    /**
     * @param string $body
     * @return string
     */
    private function escapeBody(string $body): string
    {
        $normalized = str_replace(["\r\n", "\r"], "\n", $body);
        $lines = explode("\n", $normalized);
        foreach ($lines as &$line) {
            if (str_starts_with($line, '.')) {
                $line = '.' . $line;
            }
        }

        return implode("\r\n", $lines);
    }
}
