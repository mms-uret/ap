<?php

namespace App\Publish;

use App\Entity\Publication;
use App\Entity\Version;
use App\Repository\ArticleRepository;
use App\Repository\PublicationRepository;
use App\Repository\VersionRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class VersionHandler
{
    public function __construct(private readonly ArticleRepository $articleRepository, private readonly VersionRepository $versionRepository)
    {
    }

    public function __invoke(PublishMessage $message): void
    {
        $article = $this->articleRepository->find($message->id);

        $versionNumber = $this->versionRepository->nextVersionNumber($message->id);

        $version = new Version($message->id, $versionNumber);

        $version->setContent(['topElement' => $article->getTme(), 'text' => $article->getText()]);
        $version->setInfo(['id' => $message->id, 'kicker' => $article->getKicker(), 'title' => $article->getTitle()]);
        $version->setState('published');
        $version->setPublishedBy($message->user);

        $this->versionRepository->save($version, true);
    }
}