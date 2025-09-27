<?php

namespace app\components\smspilot;

use RuntimeException;

final class SmsClientException extends RuntimeException
{
    /**
     * @param string $message
     */
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    /**
     * @param int $statusCode
     * @return self
     */
    public static function invalidStatusCode(int $statusCode): self
    {
        return new self(printf('Invalid status code: %s', $statusCode));
    }

    /**
     * @param string $description
     * @param string $code
     * @return self
     */
    public static function apiError(string $description, string $code): self
    {
        return new self(printf('Api error[%s]: %s', $code, $description));
    }

    /**
     * @param string $content
     * @return self
     */
    public static function invalidData(string $content): self
    {
        return new self(printf('Invalid data: %s', $content));
    }

    /**
     * @param string $message
     * @return self
     */
    public static function clientError(string $message): self
    {
        return new self(printf('Client error: %s'. $message));
    }
}
