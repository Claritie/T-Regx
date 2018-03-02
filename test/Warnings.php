<?php
namespace Test;

class Warnings
{
    public static function causeRuntimeWarning()
    {
        @preg_match('/pattern/u', "\xc3\x28");
    }

    public static function causeCompileWarning()
    {
        @preg_match('/unclosed pattern', '');
    }
}
