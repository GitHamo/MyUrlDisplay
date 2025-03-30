<?php

declare(strict_types=1);

namespace ILIAS\Plugins\MyUrlDisplay\Models;

interface ProtocolEnum
{
    public const HTTP = 'http';
    public const HTTPS = 'https';
    public const FTP = 'ftp';
    public const SMTP = 'smtp';

    /**
     * @var string[]
     */
    public const CASES = [
        self::HTTP,
        self::HTTPS,
    ];
}
