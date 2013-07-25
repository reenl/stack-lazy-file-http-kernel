<?php
namespace Stack\Mount;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;

class FileMountHttpKernel implements HttpKernelInterface
{
    protected $file;

    public function __construct($file)
    {
        if (!file_exists($file)) {
            throw new \RuntimeException('File does not exist.');
        }
        $this->file = $file;
    }

    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        $app = require $this->file;
        if (!$app instanceof HttpKernelInterface) {
            throw new \RuntimeException('Mounted file must return an instance of HttpKernelInterface.');
        }
        return $app->handle($request, $type, $catch);
    }
}
