<?php

declare(strict_types=1);

namespace ILIAS\Plugins\MyUrlDisplay\Infrastructure\Data;

use ilDBInterface;
use ILIAS\Plugins\MyUrlDisplay\Exceptions\TooManyResultsException;
use ILIAS\Plugins\MyUrlDisplay\Models\UrlConfig;
use ilMyUrlDisplayPlugin;

/**
 * @codeCoverageIgnore Need integration test, could not finish in time.
 */
class UrlConfigReader
{
    private const TABLE_NAME = ilMyUrlDisplayPlugin::PLUGIN_TABLE_URL_CONFIGS;

    private ilDBInterface $db;

    public function __construct(ilDBInterface $db)
    {
        $this->db = $db;
    }

    public function fetchOneByUser(int $user_id, ?int $id): ?UrlConfig
    {
        $query = sprintf(
            "SELECT %s FROM %s WHERE user_id = %d",
            implode(", ", ['id', 'protocol', 'domain', 'port', 'path', 'color']),
            self::TABLE_NAME,
            $this->db->quote($user_id, "integer")
        );

        if($id) {
            $query .= sprintf(" AND id = %d", $this->db->quote($id, "integer"));
        }

        $result = $this->db->query($query);

        $fetched = $result->numRows();

        if ($fetched === 0) {
            return null;
        }

        if ($fetched > 1) {
            throw new TooManyResultsException("Data error: Too many entries found by one user.");
        }

        $data = $result->fetchAssoc();

        return new UrlConfig(
            (int) $data['id'],
            $data['protocol'],
            $data['domain'],
            $data['port'] ? (int) $data['port'] : null,
            $data['path'] ?? null,
            $data['color']
        );
    }
}
