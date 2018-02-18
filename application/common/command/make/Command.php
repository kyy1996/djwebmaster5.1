<?php

namespace app\common\command\make;

use think\console\command\Make;

class Command extends Make
{
    protected $type = "Command";

    protected function configure()
    {
        parent::configure();
        $this->setName('make:command')
             ->setDescription('Create a new command class');
    }

    protected function getStub()
    {
        return __DIR__ . '/stubs/command.stub';
    }

    protected function getNamespace($appNamespace, $module)
    {
        return parent::getNamespace($appNamespace, $module) . '\command';
    }
}
