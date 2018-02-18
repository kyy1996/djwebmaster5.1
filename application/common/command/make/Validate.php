<?php

namespace app\common\command\make;

use think\console\command\Make;

class Validate extends Make
{
    protected $type = "Validate";

    protected function configure()
    {
        parent::configure();
        $this->setName('make:validate')
             ->setDescription('Create a new validate class');
    }

    protected function getStub()
    {
        return __DIR__ . '/stubs/validate.stub';
    }

    protected function getNamespace($appNamespace, $module)
    {
        return parent::getNamespace($appNamespace, $module) . '\validate';
    }
}
