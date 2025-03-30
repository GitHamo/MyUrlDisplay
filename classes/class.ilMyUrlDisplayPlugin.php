<?php

declare(strict_types=1);

class ilMyUrlDisplayPlugin extends ilUserInterfaceHookPlugin
{
    public const PLUGIN_CLASS_NAME = self::class;
    public const PLUGIN_NAME = 'MyUrlDisplay';
    public const PLUGIN_ID = 'myurldisplay';
    public const PLUGIN_TABLE_URL_CONFIGS = 'mudp_url_configs';

    public function getPluginName(): string
    {
        return self::PLUGIN_NAME;
    }

    final protected function beforeUninstall(): bool
    {
        /**
         * @var $ilDB ilDBInterface
         */
        global $ilDB;

        if($ilDB->tableExists(self::PLUGIN_TABLE_URL_CONFIGS)) {
            $ilDB->dropTable(self::PLUGIN_TABLE_URL_CONFIGS);
        }

        if($ilDB->sequenceExists(self::PLUGIN_TABLE_URL_CONFIGS)) {
            $ilDB->dropSequence(self::PLUGIN_TABLE_URL_CONFIGS);
        }

        return true;
    }
}
