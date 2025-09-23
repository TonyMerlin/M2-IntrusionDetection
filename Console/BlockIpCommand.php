<?php
namespace Merlin\IntrusionDetection\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Merlin\IntrusionDetection\Model\BlockService;

class BlockIpCommand extends Command
{
    protected static $defaultName = 'merlin:id:block-ip';
    public function __construct(private BlockService $svc, string $name = null) { parent::__construct($name); }

    protected function configure()
    {
        $this->setDescription('Block an IP')
            ->addArgument('ip', InputArgument::REQUIRED)
            ->addArgument('minutes', InputArgument::OPTIONAL, 'Temporary block minutes', 60)
            ->addArgument('reason', InputArgument::OPTIONAL, 'Reason', 'manual');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->svc->block($input->getArgument('ip'), $input->getArgument('reason'), (int)$input->getArgument('minutes'));
        $output->writeln('<info>Blocked</info>');
        return Command::SUCCESS;
    }
}
