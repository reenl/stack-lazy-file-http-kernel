<?php
namespace Stack\Mount;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;

class CallableMountHttpKernel implements HttpKernelInterface
{
    protected $callable;

    public function __construct($callable)
    {
        if (!is_callable($callable)) {
            throw new \RuntimeException('First argument should be callable.');
        }
        $this->callable = $callable;
    }

    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        $app = call_user_func($this->callable);
        if (!$app instanceof HttpKernelInterface) {
            throw new \RuntimeException('The callable did not return an HttpKernelInterface.');
        }
        return $app->handle($request, $type, $catch);
    }
}
