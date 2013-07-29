<?php
namespace Stack;

use Symfony\Component\HttpFoundation\Request;

class LazyFileHttpKernelTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessage File does not exist.
     */
    public function testRejectNonFile()
    {
        new LazyFileHttpKernel('doesNotExist');
    }

    /**
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Mounted file must return an instance of HttpKernelInterface.
     */
    public function testFileIsNotMountable()
    {
        $kernel = new LazyFileHttpKernel(__DIR__.'/not-mountable.php');
        $kernel->handle(new Request());
    }

    public function testHandle()
    {
        $kernel = new LazyFileHttpKernel(__DIR__.'/mountable.php');
        $response = $kernel->handle(new Request());

        $this->assertEquals('Hello world!', $response->getContent());
    }
}
