<?php

namespace App\Command;

use App\Service\ProviderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DailyCommand extends Command
{
    protected static $defaultName = 'app:daily';
    /**
     * @var ProviderInterface
     */
    private ProviderInterface $provider;

    public function __construct(ProviderInterface $provider,string $name = null)
    {
        parent::__construct($name);
        $this->provider = $provider;
    }

    protected function configure()
    {
        $this
            ->setDescription('Get currency value for date')
            ->addOption('date', null, InputOption::VALUE_OPTIONAL, 'Date in format d/m/Y')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $date = $input->getOption('date');

        dd($this->provider->daily($date));

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return 0;
    }
}
