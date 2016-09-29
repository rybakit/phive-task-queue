<?php

namespace Phive\TaskQueue\Tests\RetryStrategy;

use Phive\TaskQueue\RetryStrategy\LinearRetryStrategy;

class LinearRetryStrategyTest extends \PHPUnit_Framework_TestCase
{
    const STEP = 60;

    /**
     * @var LinearRetryStrategy
     */
    private $strategy;

    protected function setUp()
    {
        $this->strategy = new LinearRetryStrategy(self::STEP);
    }

    public function testGetDelay()
    {
        $this->assertSame(self::STEP, $this->strategy->getDelay(1));
        $this->assertSame(self::STEP * 2, $this->strategy->getDelay(2));
    }
}
