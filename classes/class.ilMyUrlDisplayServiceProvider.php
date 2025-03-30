<?php

declare(strict_types=1);

use ILIAS\Plugins\MyUrlDisplay\Infrastructure\Data\UrlConfigReader;
use ILIAS\Plugins\MyUrlDisplay\Infrastructure\Data\UrlConfigWriter;
use ILIAS\Plugins\MyUrlDisplay\Infrastructure\MyUrlDisplayService;
use ILIAS\Plugins\MyUrlDisplay\Infrastructure\UrlConfigRepository;
use ILIAS\Plugins\MyUrlDisplay\MyUrlDisplayServiceInterface;

class ilMyUrlDisplayServiceProvider
{
    public static function getService(): MyUrlDisplayServiceInterface
    {
        /**
         * @global \ILIAS\DI\Container $DIC
         */
        global $DIC;

        $database_connection = $DIC->database();

        return new MyUrlDisplayService(
            new UrlConfigRepository(
                new UrlConfigReader($database_connection),
                new UrlConfigWriter($database_connection),
            )
        );
    }
}
