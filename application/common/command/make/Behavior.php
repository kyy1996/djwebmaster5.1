<?php

namespace app\common\command\make;

use think\console\command\Make;

class Behavior extends Make
{

    protected $type = "Behavior";

    protected function configure()
    {
        parent::configure();
        $this->setName('make:behavior')
             ->setDescription('Create a new behavior class');
    }

    protected function getStub()
    {
        return __DIR__ . '/stubs/behavior.stub';
    }

    protected function getClassName($name)
    {
        return parent::getClassName($name);
    }

    protected function getNamespace($appNamespace, $module)
    {
        return parent::getNamespace($appNamespace, $module) . '\behavior';
    }

}
