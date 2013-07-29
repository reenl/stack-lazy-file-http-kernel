<?php
namespace Stack;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;

class LazyFileHttpKernel implements HttpKernelInterface
{
    protected $file;

    protected $app;

    public function __construct($file)
    {
        if (!file_exists($file)) {
            throw new \RuntimeException('File does not exist.');
        }
        $this->file = $file;
    }

    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        return $this->createApp()->handle($request, $type, $catch);
    }

    private function createApp()
    {
        if ($this->app === null) {
            $app = require $this->file;
            if (!$app instanceof HttpKernelInterface) {
                throw new \RuntimeException('Mounted file must return an instance of HttpKernelInterface.');
            }
            $this->app = $app;
        }
        return $this->app;
    }
}
