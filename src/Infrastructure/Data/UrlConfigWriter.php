<?php

declare(strict_types=1);

namespace ILIAS\Plugins\MyUrlDisplay\Infrastructure\Data;

use ilDBInterface;
use ILIAS\Plugins\MyUrlDisplay\Models\UrlConfigDto;
use ilMyUrlDisplayPlugin;

/**
 * @codeCoverageIgnore Need integration test, could not finish in time.
 */
class UrlConfigWriter
{
    private const TABLE_NAME = ilMyUrlDisplayPlugin::PLUGIN_TABLE_URL_CONFIGS;

    private ilDBInterface $db;

    public function __construct(ilDBInterface $db)
    {
        $this->db = $db;
    }

    public function insert(UrlConfigDto $dto): int
    {
        $id = $this->db->nextID(self::TABLE_NAME);

        $sql = sprintf(
            "INSERT INTO %s (id, user_id, protocol, domain, port, path, color) VALUES (%d, %d, '%s', '%s', %s, '%s', '%s')",
            self::TABLE_NAME,
            $id,
            $dto->user_id,
            $dto->protocol,
            $dto->domain,
            is_null($dto->port) ? 'NULL' : $dto->port,
            is_null($dto->path) ? 'NULL' : $dto->path,
            $dto->color
        );

        return $this->db->manipulate($sql);
    }

    public function update(int $id, UrlConfigDto $dto): int
    {
        $sql = sprintf(
            "UPDATE %s SET user_id = %d, protocol = '%s', domain = '%s', port = %s, path = '%s', color = '%s' WHERE id = %d",
            self::TABLE_NAME,
            $dto->user_id,
            $dto->protocol,
            $dto->domain,
            is_null($dto->port) ? 'NULL' : $dto->port,
            is_null($dto->path) ? 'NULL' : $dto->path,
            $dto->color,
            $id
        );

        return $this->db->manipulate($sql);
    }
}
