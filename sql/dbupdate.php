<#1>
<?php

$url_configs_table_name = ilMyUrlDisplayPlugin::PLUGIN_TABLE_URL_CONFIGS;

$id = ilMyUrlDisplayPlugin::PLUGIN_ID;
$version = '0.0.1';

if(!$ilDB->tableExists($url_configs_table_name)) {
    $fields = [
        'id' => [
            'type' => 'integer',
            'length' => 4,
            'notnull' => true,
            'default' => 0
        ],
        'user_id' => [
            'type' => 'integer',
            'length' => 4,
            'notnull' => true,
            'default' => 0
        ],
        'protocol' => [
            'type' => 'text',
            'length' => 10,
            'notnull' => true
        ],
        'domain' => [
            'type' => 'text',
            'length' => 255,
            'notnull' => true
        ],
        'port' => [
            'type' => 'integer',
            'length' => 4,
            'notnull' => false,
            'default' => 80
        ],
        'path' => [
            'type' => 'text',
            'length' => 255,
            'notnull' => false
        ],
        'color' => [
            'type' => 'text',
            'length' => 50,
            'notnull' => true
        ],
    ];

    $ilDB->createTable($url_configs_table_name, $fields);
    $ilDB->addPrimaryKey($url_configs_table_name, ['id']);
    $ilDB->createSequence($url_configs_table_name);
}
?>
