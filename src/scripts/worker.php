<?php

$autoload_script = $argv[1];

/** @noinspection PhpIncludeInspection */
include_once $autoload_script;

use edwrodrig\background_process\Process;
use edwrodrig\background_process\ProcessExecution;

/** @var $command The command to execute */
$command = $argv[2];

/** @var $base_folder The base folder where everything will be executed */
$base_folder = $argv[3];

/** @var $result_handler_class */
$result_handler_class = $argv[4] ?? '';

$process = new Process($base_folder, $command);
$process->setResultHandler($result_handler_class);

do {
    $process->setId($process->getPendingId());

    $executor = new ProcessExecution($process);
    $executor->execute();

} while ( $process->hasPending() );

$process->setRunning(false);