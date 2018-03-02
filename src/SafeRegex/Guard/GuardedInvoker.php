<?php
namespace SafeRegex\Guard;

use CleanRegex\Internal\Arguments;
use Closure;
use SafeRegex\Errors\ErrorsCleaner;
use SafeRegex\ExceptionFactory;

class GuardedInvoker
{
    /** @var callable */
    private $callback;
    /** @var string */
    private $methodName;

    /**
     * @param string  $methodName
     * @param Closure $callback
     */
    public function __construct($methodName, Closure $callback)
    {
        Arguments::string($methodName);

        $this->callback = $callback;
        $this->methodName = $methodName;
    }

    /**
     * @return GuardedInvocation
     */
    public function catched()
    {
        $this->clearObsoleteCompileAndRuntimeErrors();

        $result = call_user_func($this->callback);

        return new GuardedInvocation($result, $this->exception($result));
    }

    private function clearObsoleteCompileAndRuntimeErrors()
    {
        $errorsCleaner = new ErrorsCleaner();
        $errorsCleaner->clear();
    }

    private function exception($result)
    {
        $exceptionFactory = new ExceptionFactory();
        return $exceptionFactory->retrieveGlobals($this->methodName, $result);
    }
}
