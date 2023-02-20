<?php

namespace App\Publish;

use App\Entity\Publication;
use App\Repository\ArticleRepository;
use App\Repository\PublicationRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class PublicationHandler
{
    public function __construct(private readonly ArticleRepository $articleRepository, private readonly PublicationRepository $publicationRepository)
    {
    }

    public function __invoke(PublishMessage $message): void
    {
        $article = $this->articleRepository->find($message->id);

        $publication = $this->publicationRepository->find($message->id);
        if (!$publication) {
            $publication = new Publication($message->id);
        }

        // here we start a build process for turning article into publication
        $publication->setContent(['topElement' => $article->getTme(), 'text' => $article->getText()]);
        $publication->setInfo(['id' => $message->id, 'kicker' => $article->getKicker(), 'title' => $article->getTitle()]);
        $publication->setUpdated(!$message->firstPublication);

        $this->publicationRepository->save($publication, true);
    }
}