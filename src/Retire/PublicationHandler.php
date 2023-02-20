<?php

namespace App\Retire;

use App\Repository\PublicationRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class PublicationHandler
{
    public function __construct(private readonly PublicationRepository $publicationRepository)
    {
    }

    public function __invoke(RetireMessage $message): void
    {
        $publication = $this->publicationRepository->find($message->id);

        $this->publicationRepository->remove($publication, true);
    }
}