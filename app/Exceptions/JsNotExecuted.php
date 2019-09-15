<?php


namespace App\Exceptions;


use Exception;

final class JsNotExecuted extends Exception
{
    public static function instance(): self
    {
        return new self('Compiled js is not executed!');
    }
}
