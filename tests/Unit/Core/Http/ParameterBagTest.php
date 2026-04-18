<?php

declare(strict_types=1);

namespace Tests\Unit\Core\Http;

use App\Core\Http\ParameterBag;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
#[Group('http')]
final class ParameterBagTest extends TestCase
{
    public function testReturnsExistingValue(): void
    {
        $bag = new ParameterBag([
            'title' => 'Test task',
        ]);

        self::assertSame('Test task', $bag->get('title'));
    }

    public function testReturnsDefaultValueWhenKeyIsMissing(): void
    {
        $bag = new ParameterBag();

        self::assertSame('fallback', $bag->get('title', 'fallback'));
    }
}
