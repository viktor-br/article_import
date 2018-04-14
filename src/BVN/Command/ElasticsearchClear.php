<?php

namespace BVN\Command;

use BVN\Elasticsearch\Cleaner;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class ElasticsearchClear extends AbstractCommand
{
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Do you want to clear elasticsearch indexes? ', false);
        if (!$helper->ask($input, $output, $question)) {
            return;
        }
        $cleaner = $this->container->get(Cleaner::class);
        $cleaner->clean();

        $output->writeln("[OK] ElasticSearch is cleared");
    }
}