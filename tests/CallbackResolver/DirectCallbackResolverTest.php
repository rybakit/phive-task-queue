<?php

namespace Phive\TaskQueue\Tests\ExecutorAdapter;

use Phive\TaskQueue\CallbackResolver\DirectCallbackResolver;
use Phive\TaskQueue\ExecutionContext;

class DirectCallbackResolverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param @dataProvider provideResolveData
     */
    public function testResolve(callable $callback, $payload, $result)
    {
        $resolver = new DirectCallbackResolver($callback);
        $context = $this->getMockBuilder(ExecutionContext::class)->disableOriginalConstructor()->getMock();

        $this->assertSame($result, $resolver->resolve($payload, $context));
    }

    public function provideResolveData()
    {
        $callback = function ($arg) { return $arg; };

        return [
            [$callback, 'foo', [$callback, ['foo']]],
            [$callback, null, [$callback, [null]]],
            ['strlen', 'foo', ['strlen', ['foo']]],
        ];
    }
}
