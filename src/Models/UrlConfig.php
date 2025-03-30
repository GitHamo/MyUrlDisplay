<?php

declare(strict_types=1);

namespace ILIAS\Plugins\MyUrlDisplay\Models;

class UrlConfig
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $protocol;

    /**
     * @var string
     */
    private $domain;

    /**
     * @var int|null
     */
    private $port;

    /**
     * @var string|null
     */
    private $path;

    /**
     * @var string
     */
    private $color;

    public function __construct(
        int $id,
        string $protocol,
        string $domain,
        ?int $port,
        ?string $path,
        string $color
    ) {
        $this->id = $id;
        $this->protocol = $protocol;
        $this->domain = $domain;
        $this->port = $port;
        $this->path = $path;
        $this->color = $color;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getProtocol(): string
    {
        return $this->protocol;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * Allows the object to be used as a string.
     */
    public function __toString(): string
    {
        return $this->buildUrl();
    }

    /**
     * Private method to generate the URL string.
     */
    private function buildUrl(): string
    {
        $portPart = $this->port !== null ? ":{$this->port}" : '';
        $pathPart = $this->path !== null ? '/' . ltrim($this->path, '/') : '';

        return "{$this->protocol}://{$this->domain}{$portPart}{$pathPart}";
    }
}
