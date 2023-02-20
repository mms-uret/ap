<?php

namespace App;

use App\Publish\PublishMessage;
use App\Repository\ArticleRepository;
use App\Retire\RetireMessage;
use Symfony\Component\Messenger\MessageBusInterface;

class ArticleService
{

    public function __construct(private readonly ArticleRepository $articleRepository, private readonly MessageBusInterface $messageBus)
    {
    }

    public function publish(int $id, string $user)
    {
        // first update the "internal" article and mark it as published
        $article = $this->articleRepository->find($id);

        $isFirstPublication = $article->getPublishedAt() === null && $article->getRetiredAt() === null;

        $article->setPublishedAt(new \DateTimeImmutable());
        $article->setRetiredAt(null);
        $this->articleRepository->save($article);

        $message = new PublishMessage($id, $user, $isFirstPublication);
        $this->messageBus->dispatch($message);
    }

    public function retire(int $id, string $user)
    {
        // first update the "internal" article and mark it as published
        $article = $this->articleRepository->find($id);

        $article->setPublishedAt(null);
        $article->setRetiredAt(new \DateTimeImmutable());
        $this->articleRepository->save($article);

        $message = new RetireMessage($id, $user);
        $this->messageBus->dispatch($message);
    }
}