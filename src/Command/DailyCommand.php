<?php

namespace App\Command;

use App\Entity\Currency;
use App\Service\ProviderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
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
            ->addOption('currency', null, InputOption::VALUE_OPTIONAL, 'Currency char code')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $date = $input->getOption('date');
        $currency = $input->getOption('currency');

        $result = $this->provider->daily($date, $currency);

        if (!is_array($result)) {
            $result = [$result];
        }
        $table = new Table($output);
        $table->setHeaderTitle(\sprintf('Currency on date %s', $date));
        $table->setHeaders(['Code', 'Name', 'Value', 'Nominal']);
        /** @var Currency $item */
        foreach ($result as $item) {
            $table->addRow([
                $item->getCharCode(),
                $item->getName(),
                $item->getValue(),
                $item->getNominal(),
            ]);
        }
        $table->render();
        $io->success('Success!');

        return 0;
    }
}
