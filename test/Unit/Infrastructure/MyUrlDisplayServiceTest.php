<?php

declare(strict_types=1);

namespace Tests\Unit\ILIAS\Plugins\MyUrlDisplay\Infrastructure;

use ILIAS\Plugins\MyUrlDisplay\Infrastructure\MyUrlDisplayService;
use ILIAS\Plugins\MyUrlDisplay\Models\ProtocolEnum;
use ILIAS\Plugins\MyUrlDisplay\Models\UrlConfig;
use ILIAS\Plugins\MyUrlDisplay\Models\UrlConfigDto;
use ILIAS\Plugins\MyUrlDisplay\UrlConfigRepositoryInterface;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MyUrlDisplayServiceTest extends TestCase
{
    private MyUrlDisplayService $service;
    /**
     * @var UrlConfigRepositoryInterface|MockObject
     */
    private $repositoryMock;

    protected function setUp(): void
    {
        $this->service = new MyUrlDisplayService(
            $this->repositoryMock = $this->createMock(UrlConfigRepositoryInterface::class)
        );
    }

    public function testUsesComponentsToGetUrlConfigByUser(): void
    {
        $userId = mt_rand();
        $expected = $this->createMock(UrlConfig::class);

        $this->repositoryMock->expects(static::once())
            ->method('getByUserId')
            ->with(
                static::identicalTo($userId),

            )
            ->willReturn($expected);

        $actual = $this->service->getByUser($userId);

        static::assertSame($expected, $actual);
    }

    public function testCreatesNewUrlConfig(): void
    {
        $dto = new UrlConfigDto(
            null,
            123,
            ProtocolEnum::HTTP,
            'foo',
            null,
            null,
            'bar',
        );
    
        $this->repositoryMock->expects(static::once())
            ->method('create')
            ->with(
                static::identicalTo($dto)
            );
    
        $this->repositoryMock->expects(static::never())->method('getByUserId');
        $this->repositoryMock->expects(static::never())->method('update');

        $this->service->save($dto);
    }

    public function testUpdatesExistingUrlConfig(): void
    {
        /**
         * @var UrlConfig|MockObject
         */
        $mock = $this->createMock(UrlConfig::class);
        $dto = new UrlConfigDto(
            $entityId = mt_rand(),
            $userId = 123,
            ProtocolEnum::HTTP,
            'foo',
            null,
            null,
            'bar',
        );

        $this->repositoryMock->expects(static::once())
            ->method('getByUserId')
            ->with(
                static::identicalTo($userId),
                static::identicalTo($entityId)
            )
            ->willReturn($mock);
    
        $this->repositoryMock->expects(static::once())
            ->method('update')
            ->with(
                static::identicalTo($mock),
                static::identicalTo($dto)
            );

        $this->repositoryMock->expects(static::never())->method('create');

        $this->service->save($dto);
    }

    public function testThrowsExceptionIfMandatoryProtocolIsMissing(): void
    {
        $dto = new UrlConfigDto(
            null,
            123,
            null,
            'foo',
            null,
            null,
            'bar',
        );

        static::expectException(InvalidArgumentException::class);

        $this->service->save($dto);
    }

    public function testThrowsExceptionIfMandatoryDomainIsMissing(): void
    {
        $dto = new UrlConfigDto(
            null,
            123,
            ProtocolEnum::HTTP,
            '',
            null,
            null,
            'foo',
        );

        static::expectException(InvalidArgumentException::class);

        $this->service->save($dto);
    }

    public function testThrowsExceptionIfInvalidProtocolProvided(): void
    {
        $dto = new UrlConfigDto(
            null,
            123,
            'invalid',
            'foo',
            null,
            null,
            'bar',
        );

        static::expectException(InvalidArgumentException::class);

        $this->service->save($dto);
    }

    public function testThrowsExceptionInCaseOfUrlConfigInvalidIdProvided(): void
    {
        $dto = new UrlConfigDto(
            123,
            321,
            ProtocolEnum::HTTP,
            'foo',
            null,
            null,
            'bar',
        );

        $this->repositoryMock->expects(static::once())
            ->method('getByUserId')
            ->willReturn(null);
    
        $this->repositoryMock->expects(static::never())->method('update');
        $this->repositoryMock->expects(static::never())->method('create');

        static::expectException(InvalidArgumentException::class);

        $this->service->save($dto);
    }
}
