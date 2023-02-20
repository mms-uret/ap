<?php

namespace App\Retire;

use App\Repository\VersionRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class VersionHandler
{
    public function __construct(private readonly VersionRepository $versionRepository)
    {
    }

    public function __invoke(RetireMessage $message): void
    {
        $version = $this->versionRepository->findLatestVersion($message->id);
        $version->setState('retired');

        $this->versionRepository->save($version, true);
    }
}