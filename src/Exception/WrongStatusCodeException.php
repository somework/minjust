<?php

namespace SomeWork\Minjust\Exception;

use Throwable;

class WrongStatusCodeException extends RuntimeException
{
    public function __construct(int $statusCode, $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf('Не верный код ответа: %s', $statusCode), $code, $previous);
    }
}
