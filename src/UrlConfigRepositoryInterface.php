<?php

declare(strict_types=1);

namespace ILIAS\Plugins\MyUrlDisplay;

use ILIAS\Plugins\MyUrlDisplay\Models\UrlConfigDto;
use ILIAS\Plugins\MyUrlDisplay\Models\UrlConfig;

interface UrlConfigRepositoryInterface
{
    public function getByUserId(int $userId, ?int $id = null): ?UrlConfig;

    public function create(UrlConfigDto $myUrlConfig): void;

    public function update(UrlConfig $myUrlConfig, UrlConfigDto $myUrlConfigDto): void;
}
