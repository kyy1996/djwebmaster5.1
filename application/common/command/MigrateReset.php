<?php

namespace app\common\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;
use think\migration\command\migrate\Rollback;

class MigrateReset extends Command
{
    protected function configure()
    {
        $this->setName('migrate:reset')
             ->addOption('--dry-run', '-x', Option::VALUE_NONE, 'Dump query to standard output instead of executing it')
             ->setDescription('Rollback and reset all your migrations.');
    }

    /**
     * @param Input  $input
     * @param Output $output
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(Input $input, Output $output)
    {
        $argv = ['--date=1970-1-1', '--force'];
        if ($input->getOption('dry-run')) $argv[] = '--dry-run';
        $mockInput = new Input($argv);
        $rollback  = new Rollback();
        $rollback->run($mockInput, $output);
    }
}
