<?php

declare(strict_types=1);

namespace ILIAS\Plugins\MyUrlDisplay;

use ILIAS\Plugins\MyUrlDisplay\Models\UrlConfigDto;
use ILIAS\Plugins\MyUrlDisplay\Models\UrlConfig;

interface MyUrlDisplayServiceInterface
{
    public function getByUser(int $userId): ?UrlConfig;

    public function save(UrlConfigDto $urlConfigDto): void;
}
