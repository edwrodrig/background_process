<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 01-10-18
 * Time: 12:14
 */

namespace test\edwrodrig\background_process;


use edwrodrig\background_process\ProcessResultHandler;

class DummyHandler extends ProcessResultHandler
{
    public function handleResult(int $exit_code): bool
    {
        $file = $this->process->getFolder() . DIRECTORY_SEPARATOR . 'mail';
        touch($file);
    }
}