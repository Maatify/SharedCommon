<?php

declare(strict_types=1);

namespace Maatify\SharedCommon\Tests\Infrastructure;

use DateTimeImmutable;
use DateTimeZone;
use Maatify\SharedCommon\Infrastructure\SystemClock;
use PHPUnit\Framework\TestCase;

final class SystemClockTest extends TestCase
{
    public function test_now_returns_datetimeimmutable(): void
    {
        $clock = new SystemClock(new DateTimeZone('UTC'));

        $now = $clock->now();

        $this->assertInstanceOf(DateTimeImmutable::class, $now);
    }

    public function test_timezone_is_preserved(): void
    {
        $tz = new DateTimeZone('Europe/Berlin');

        $clock = new SystemClock($tz);

        $this->assertEquals($tz, $clock->getTimezone());
    }
}
