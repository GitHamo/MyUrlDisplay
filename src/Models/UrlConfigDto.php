<?php

declare(strict_types=1);

namespace ILIAS\Plugins\MyUrlDisplay\Models;

class UrlConfigDto
{
    public ?int $id;
    public int $user_id;
    public ?string $protocol;
    public ?string $domain;
    public ?int $port;
    public ?string $path;
    public string $color;

    public function __construct(
        ?int $id,
        int $user_id,
        ?string $protocol,
        ?string $domain,
        ?int $port,
        ?string $path,
        string $color
    ) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->protocol = $protocol;
        $this->domain = $domain;
        $this->port = $port;
        $this->path = $path;
        $this->color = $color;
    }
}
