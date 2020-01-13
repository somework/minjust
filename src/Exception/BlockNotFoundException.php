<?php

namespace SomeWork\Minjust\Exception;

use Throwable;

/**
 * Вызывается при отсуствии необходимого HTML блока в ответе
 */
class BlockNotFoundException extends RuntimeException
{
    public function __construct(string $path, $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf('Блок с путем: %s не найден', $path), $code, $previous);
    }
}
