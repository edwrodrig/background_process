<?php

include_once __DIR__ . '/../vendor/autoload.php';

use edwrodrig\background_process\MailerProcessResultHandler;
use edwrodrig\background_process\Process;

exec('rm -rf /tmp/ABC');
$process = new Process('/tmp', 'ls -la');
$process->setId('ABC');
$process->run();