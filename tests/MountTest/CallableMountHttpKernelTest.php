<?php
namespace MountTest;

use Stack\Mount\CallableMountHttpKernel;
use Symfony\Component\HttpFoundation\Request;

class CallableMountHttpKernelTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessage First argument should be callable.
     */
    public function testRejectNonCallable()
    {
        new CallableMountHttpKernel('doesNotExist');
    }

    /**
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessage The callable did not return an HttpKernelInterface.
     */
    public function testCallableDoesNotReturnKernel()
    {
        $kernel = new CallableMountHttpKernel(function() {
            return 'thisIsNoKernel';
        });
        $kernel->handle(new Request());
    }

    public function testHandle()
    {
        $kernel = new CallableMountHttpKernel(function() {
            return require __DIR__.'/mountable.php';
        });
        $response = $kernel->handle(new Request());

        $this->assertEquals('Hello world!', $response->getContent());
    }
}
