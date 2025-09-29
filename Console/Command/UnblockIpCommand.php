<?php
namespace Merlin\IntrusionDetection\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Merlin\IntrusionDetection\Model\BlockService;

class UnblockIpCommand extends Command
{
    protected static $defaultName = 'merlin:id:unblock-ip';
    public function __construct(private BlockService $svc, string $name = null) { parent::__construct($name); }

    protected function configure()
    {
        $this->setDescription('Unblock an IP')
            ->addArgument('ip', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $n = $this->svc->unblock($input->getArgument('ip'));
        $output->writeln("<info>Removed $n block record(s)</info>");
        return Command::SUCCESS;
    }
}
