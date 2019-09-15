<?php


namespace App\Exceptions;


use Exception;

final class TsNotCompiled extends Exception
{
    public static function instance(string $message): self
    {
        return new self($message);
    }
}
