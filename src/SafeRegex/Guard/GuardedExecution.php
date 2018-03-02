<?php

namespace SafeRegex\Guard;

use CleanRegex\Internal\Arguments;
use Closure;

class GuardedExecution
{
    /**
     * @param string  $methodName
     * @param Closure $callback
     * @return mixed
     * @throws \Exception
     */
    public static function invoke($methodName, Closure $callback)
    {
        $guardedInvoker = new GuardedInvoker($methodName, $callback);
        $invocation = $guardedInvoker->catched();
        if ($invocation->hasException()) {
            throw $invocation->getException();
        }
        return $invocation->getResult();
    }

    /**
     * @param string  $methodName
     * @param Closure $callback
     * @return GuardedInvocation
     */
    public static function catched($methodName, Closure $callback)
    {
        Arguments::string($methodName);

        $guardedInvoker = new GuardedInvoker($methodName, $callback);
        return $guardedInvoker->catched();
    }

    /**
     * @param string  $methodName
     * @param Closure $callback
     * @return bool
     */
    public static function silenced($methodName, Closure $callback)
    {
        Arguments::string($methodName);

        $guardedInvoker = new GuardedInvoker($methodName, $callback);
        return $guardedInvoker->catched()->hasException();
    }
}
