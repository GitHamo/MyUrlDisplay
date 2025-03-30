<?php

declare(strict_types=1);

namespace ILIAS\Plugins\MyUrlDisplay\Infrastructure;

use ILIAS\Plugins\MyUrlDisplay\Models\ProtocolEnum;
use ILIAS\Plugins\MyUrlDisplay\Models\UrlConfigDto;
use ILIAS\Plugins\MyUrlDisplay\Models\UrlConfig;
use ILIAS\Plugins\MyUrlDisplay\MyUrlDisplayServiceInterface;
use ILIAS\Plugins\MyUrlDisplay\UrlConfigRepositoryInterface;
use InvalidArgumentException;

final class MyUrlDisplayService implements MyUrlDisplayServiceInterface
{
    private UrlConfigRepositoryInterface $repository;


    public function __construct(UrlConfigRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getByUser(int $userId): ?UrlConfig
    {
        return $this->repository->getByUserId($userId);
    }

    public function save(UrlConfigDto $urlConfigDto): void
    {
        $protocol = $urlConfigDto->protocol;

        if(empty($protocol)) {
            throw new InvalidArgumentException('Protocol is required');
        }

        if(empty($urlConfigDto->domain)) {
            throw new InvalidArgumentException('Domain is required');
        }

        $allowedProtocols = ProtocolEnum::CASES;

        if(false === in_array(strtolower($protocol), $allowedProtocols, true)) {
            throw new InvalidArgumentException('Invalid protocol provided. Supported protocols: ' . implode(', ', $allowedProtocols));
        }
        
        if(!empty($urlConfigDto->id)) {
            $urlConfig = $this->repository->getByUserId($urlConfigDto->user_id, $urlConfigDto->id);
            if(null === $urlConfig) {
                // prevent any malicious form input manipulation
                throw new InvalidArgumentException('Url configuration with specified id and user id was not found');
            }

            $this->repository->update($urlConfig, $urlConfigDto);

            return;
        }

        $this->repository->create($urlConfigDto);
    }
}
