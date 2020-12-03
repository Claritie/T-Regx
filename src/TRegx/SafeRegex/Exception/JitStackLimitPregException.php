<?php
namespace TRegx\SafeRegex\Exception;

class JitStackLimitPregException extends RuntimePregException
{
    public function __construct(string $message, $pattern, string $methodName, int $errorCode, string $errorName)
    {
        parent::__construct($message, $pattern, $methodName, $errorCode, $errorName);
    }
}
