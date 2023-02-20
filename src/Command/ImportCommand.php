<?php

namespace App\Command;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'import',
    description: 'Imports a article from a json representation',
)]
class ImportCommand extends Command
{
    public function __construct(private readonly HttpClientInterface $httpClient, private readonly ArticleRepository $articleRepository)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('url', InputArgument::REQUIRED, 'Url to JSON representation of article')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $url = $input->getArgument('url');

        $response = $this->httpClient->request('GET', $url);
        $article = $response->toArray();

        $result = new Article($article['info']['id']);
        $result->setKicker($article['info']['kicker']);
        $result->setTitle($article['info']['kicker']);
        $result->setTme($article['content']['topElement']);
        $result->setText($article['content']['text']);

        $this->articleRepository->save($result, true);

        $io->success('Imported article with title ' . $result->getTitle());

        return Command::SUCCESS;
    }
}
