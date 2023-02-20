<?php

namespace App\Command;

use App\ArticleService;
use App\Entity\Article;
use App\Publish\PublishMessage;
use App\Repository\ArticleRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'publish',
    description: 'Publishes an article',
)]
class PublishCommand extends Command
{
    public function __construct(private readonly ArticleService $articleService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('id', InputArgument::REQUIRED, 'Id of the article to publish')
            ->addArgument('user', InputArgument::OPTIONAL, 'User name to use for publishing', 'Hanspeter');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $id = $input->getArgument('id');
        $user = $input->getArgument('user');

        $this->articleService->publish($id, $user);

        $io->success('Published article with id ' . $id);

        return Command::SUCCESS;
    }
}
