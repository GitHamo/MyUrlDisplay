<?php

declare(strict_types=1);

namespace ILIAS\Plugins\MyUrlDisplay\Infrastructure;

use ILIAS\Plugins\MyUrlDisplay\Models\UrlConfigDto;
use ILIAS\Plugins\MyUrlDisplay\Models\UrlConfig;
use ILIAS\Plugins\MyUrlDisplay\Infrastructure\Data\UrlConfigReader;
use ILIAS\Plugins\MyUrlDisplay\Infrastructure\Data\UrlConfigWriter;
use ILIAS\Plugins\MyUrlDisplay\UrlConfigRepositoryInterface;

class UrlConfigRepository implements UrlConfigRepositoryInterface
{
    private UrlConfigReader $reader;
    private UrlConfigWriter $writer;

    public function __construct(UrlConfigReader $reader, UrlConfigWriter $writer)
    {
        $this->reader = $reader;
        $this->writer = $writer;
    }

    public function getByUserId(int $userId, ?int $id = null): ?UrlConfig
    {
        return $this->reader->fetchOneByUser($userId, $id);
    }

    public function create(UrlConfigDto $myUrlConfig): void
    {
        $this->writer->insert($myUrlConfig);
    }

    public function update(UrlConfig $myUrlConfig, UrlConfigDto $myUrlConfigDto): void
    {
        $this->writer->update($myUrlConfig->getId(), $myUrlConfigDto);
    }
}
