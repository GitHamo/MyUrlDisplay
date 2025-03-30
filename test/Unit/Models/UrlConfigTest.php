<?php

declare(strict_types=1);

namespace Tests\Unit\ILIAS\Plugins\MyUrlDisplay\Models;

use ILIAS\Plugins\MyUrlDisplay\Models\ProtocolEnum;
use ILIAS\Plugins\MyUrlDisplay\Models\UrlConfig;
use PHPUnit\Framework\TestCase;

class UrlConfigTest extends TestCase
{
    private UrlConfig $urlConfig;
    private int $entityId;
    private string $protocol = ProtocolEnum::HTTP;
    private string $domain = 'example.com';
    private int $port = 1234;
    private string $path = '/?foo=bar';
    private string $color = 'foobarbaz';

    protected function setUp(): void
    {
        $this->urlConfig = new UrlConfig(
            $this->entityId = mt_rand(),
            $this->protocol,
            $this->domain,
            $this->port,
            $this->path,
            $this->color
        );
    }

    public function testHasAccessorForId(): void
    {
        static::assertSame($this->entityId, $this->urlConfig->getId());
    }
    public function testHasAccessorForProtocol(): void
    {
        static::assertSame($this->protocol, $this->urlConfig->getProtocol());
    }

    public function testHasAccessorForDomain(): void
    {
        static::assertSame($this->domain, $this->urlConfig->getDomain());
    }

    public function testHasAccessorForPort(): void
    {
        static::assertSame($this->port, $this->urlConfig->getPort());
    }

    public function testHasAccessorForPath(): void
    {
        static::assertSame($this->path, $this->urlConfig->getPath());
    }

    public function testHasAccessorForColor(): void
    {
        static::assertSame($this->color, $this->urlConfig->getColor());
    }

    public function testCreatesUrlConfigWithoutPort(): void
    {
        $actual = new UrlConfig($this->entityId, $this->protocol, $this->domain, null, $this->path, $this->color);

        static::assertNull($actual->getPort());
    }
    public function testCreatesUrlConfigWithoutPath(): void
    {
        $actual = new UrlConfig($this->entityId, $this->protocol, $this->domain, $this->port, null, $this->color);

        static::assertNull($actual->getPath());
    }

    public function testCastsUrlConfigToString(): void
    {
        $expected = "http://example.com:1234/?foo=bar";

        static::assertSame($expected, (string) $this->urlConfig);
    }

    /**
     * @dataProvider createsUrlConfigWithoutOptionalPropertiesProvider
     */
    public function testCreatesUrlConfigWithoutOptionalProperties(?int $port, ?string $path, string $expected): void
    {
        $actual = new UrlConfig($this->entityId, $this->protocol, $this->domain, $port, $path, $this->color);

        static::assertSame($expected, (string) $actual);
    }

    /**
     * @return array{without any: array<string|null>, without path: array<int|string|null>, without port: array<string|null>}
     */
    public static function createsUrlConfigWithoutOptionalPropertiesProvider(): array
    {
        return [
            'without any' => [null, null, 'http://example.com'],
            'without port' => [null, 'foo', 'http://example.com/foo'],
            'without path' => [123, null, 'http://example.com:123'],
        ];
    }
}
