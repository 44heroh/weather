<?php

namespace App\Command;

use App\Service\ImportWeatherService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Psr\Log\LoggerInterface;

#[AsCommand(
    name: 'app:import-weather',
    description: 'Add a short description for your command',
)]
class ImportWeatherCommand extends Command
{
    /**
     * @var ImportWeatherService
     */
    private $importWeatherService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * ImportWeatherCommand constructor.
     * @param ImportWeatherService $importWeatherService
     * @param LoggerInterface $logger
     */
    public function __construct(ImportWeatherService $importWeatherService, LoggerInterface $logger)
    {
        $this->importWeatherService = $importWeatherService;
        $this->logger = $logger;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        try {
            $result = $this->importWeatherService->import();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
