<?php

declare(strict_types=1);

namespace Tests\Unit\ILIAS\Plugins\MyUrlDisplay\Infrastructure;

use ILIAS\Plugins\MyUrlDisplay\Infrastructure\Data\UrlConfigReader;
use ILIAS\Plugins\MyUrlDisplay\Infrastructure\Data\UrlConfigWriter;
use ILIAS\Plugins\MyUrlDisplay\Infrastructure\UrlConfigRepository;
use ILIAS\Plugins\MyUrlDisplay\Models\UrlConfig;
use ILIAS\Plugins\MyUrlDisplay\Models\UrlConfigDto;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UrlConfigRepositoryTest extends TestCase
{
    private UrlConfigRepository $repository;
    /**
     * @var UrlConfigReader|MockObject
     */
    private $readerMock;
    /**
     * @var UrlConfigWriter|MockObject
     */
    private UrlConfigWriter $writerMock;

    protected function setUp(): void
    {
        $this->repository = new UrlConfigRepository(
            $this->readerMock = $this->createMock(UrlConfigReader::class),
            $this->writerMock = $this->createMock(UrlConfigWriter::class)
        );
    }

    public function testUsesComponentsToGetUrlConfigByUser(): void
    {
        $userId = mt_rand();
        $entityId = mt_rand();
        $expected = $this->createMock(UrlConfig::class);

        $this->readerMock->expects(static::once())
            ->method('fetchOneByUser')
            ->with(
                static::identicalTo($userId),
                static::identicalTo($entityId)
            )
            ->willReturn($expected);

        $actual = $this->repository->getByUserId($userId, $entityId);

        static::assertSame($expected, $actual);
    }

    public function testUsesComponentsToCreateNewUrlConfig(): void
    {
        $dto = new UrlConfigDto(
            null,
            123,
            null,
            null,
            null,
            null,
            'foo'
        );

        $this->writerMock->expects(static::once())
            ->method('insert')
            ->with(
                static::identicalTo($dto)
            );
        
        $this->repository->create($dto);
    }

    public function testUsesComponentsToUpdateExistingUrlConfig(): void
    {
        /**
         * @var UrlConfig|MockObject
         */
        $mock = $this->createConfiguredMock(UrlConfig::class, [
            'getId' => $entityId = mt_rand()
        ]);
        $dto = new UrlConfigDto(
            null,
            321,
            null,
            null,
            null,
            null,
            'foo'
        );

        $this->writerMock->expects(static::once())
            ->method('update')
            ->with(
                static::identicalTo($entityId),
                static::identicalTo($dto)
            );
        
        $this->repository->update($mock, $dto);
    }
}
