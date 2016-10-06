<?php

namespace Phive\TaskQueue\Tests\ExecutorAdapter;

use Phive\TaskQueue\CallbackResolver\PimpleCallbackResolver;
use Phive\TaskQueue\ExecutionContext;
use Pimple\Container;

class PimpleCallbackResolverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param @dataProvider provideResolveData
     */
    public function testResolve(array $values, $payload, $result)
    {
        $resolver = new PimpleCallbackResolver(new Container($values));
        $context = $this->getMockBuilder(ExecutionContext::class)->disableOriginalConstructor()->getMock();

        $this->assertSame($result, $resolver->resolve($payload, $context));
    }

    public function provideResolveData()
    {
        $values = ['foo' => 'bar'];

        return [
            [$values, ['foo'], ['bar', []]],
            [$values, ['foo', ['arg1']], ['bar', ['arg1']]],
            [$values, ['service' => 'foo', 'baz' => 'qux'], ['bar', []]],
            [$values, (object)['service' => 'foo', 'baz' => 'qux'], ['bar', []]],
        ];
    }
}
