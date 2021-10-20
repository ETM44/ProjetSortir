<?php

namespace App\Command;

use App\Bll\EtatUpdate;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class EtatUpdateCommand extends Command
{
    private $etatUpdate;

    public function __construct(EtatUpdate $etatUpdate)
    {
        $this->etatUpdate = $etatUpdate;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:etat:update')
            ->setDescription("Met à jour l'état des sortie")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->etatUpdate->listenAndUpdate();

        $text = 'Hello';

        $output->writeln($text);

        return 0;
    }
}
