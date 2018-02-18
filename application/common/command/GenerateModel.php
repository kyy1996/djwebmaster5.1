<?php

namespace app\common\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\Db;

class GenerateModel extends Command
{
    protected function configure()
    {
        $this->setName('generate:models')
             ->setDescription('Generate Models from existed tables.');
    }

    protected function execute(Input $input, Output $output)
    {
        $tables = Db::name('');
    }
}
