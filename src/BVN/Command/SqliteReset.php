<?php

namespace BVN\Command;

use BVN\Sqlite\Resetter;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class SqliteReset extends AbstractCommand
{
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Do you want to reset sqlite data? ', false);
        if (!$helper->ask($input, $output, $question)) {
            return;
        }

        try {
            $this->container->get(Resetter::class)->reset();

            $output->writeln("[OK] Sqlite db reset");
        } catch (\Exception $ex) {
            $output->writeln('[FAIL]');
            $output->writeln($ex->getMessage());
        }

    }
}