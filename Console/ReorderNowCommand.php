<?php

namespace Drd\Subscribe\Console;

use Magento\Framework\Console\Cli;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Drd\Subscribe\Model\SubscriptionReorderProcessor;
use Magento\Framework\App\State;

class ReorderNowCommand extends Command
{
    private SubscriptionReorderProcessor $processor;
    private State $appState;

    /**
     * @param SubscriptionReorderProcessor $processor
     * @param State $appState
     */
    public function __construct(
        SubscriptionReorderProcessor $processor,
        State $appState
    )
    {
        parent::__construct();
        $this->processor = $processor;
        $this->appState = $appState;
    }

    protected function configure()
    {
        $this->setName('drd:subscribe:reorder-now')
            ->setDescription('Manually run subscription reorder process');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->appState->setAreaCode('frontend');
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            // Area code is already set, ignore
        }

        $this->processor->process();
        $output->writeln('<info>Subscription reorders processed.</info>');
        return Cli::RETURN_SUCCESS;
    }
}
