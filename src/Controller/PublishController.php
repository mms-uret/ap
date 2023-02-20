<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Component\Routing\Annotation\Route;

class PublishController
{
    #[Route('publish/{article}')]
    public function publish(Article $article)
    {

    }
}