<?php
namespace Merlin\IntrusionDetection\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Merlin\IntrusionDetection\Model\ResourceModel\BlockedIp\CollectionFactory;

class ListBlockedCommand extends Command
{
    protected static $defaultName = 'merlin:id:list-blocked';
    public function __construct(private CollectionFactory $factory, string $name = null) { parent::__construct($name); }

    protected function configure()
    {
        $this->setDescription('List blocked IPs');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $col = $this->factory->create();
        foreach ($col as $item) {
            $output->writeln(sprintf("%s\t%s\t%s", $item->getData('ip'), $item->getData('reason'), $item->getData('expires_at') ?: 'permanent'));
        }
        return Command::SUCCESS;
    }
}
