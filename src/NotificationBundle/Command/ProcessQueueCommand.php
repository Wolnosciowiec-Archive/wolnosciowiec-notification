<?php

namespace NotificationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @package NotificationBundle\Commands
 */
class ProcessQueueCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('queue:process')
            ->setDescription('Process all items from the queue')
            ->addOption('pool', 'p');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getContainer()->get('logger')->info('Processing queue...');

        if ($input->getOption('pool') === true) {
            while (true) {
                sleep(5);
                $this->process();
            }

            return;
        }

        $this->process();
    }

    private function process()
    {
        $this->getContainer()->get('notificationbundle.services.processor.queue')
            ->process();
    }
}