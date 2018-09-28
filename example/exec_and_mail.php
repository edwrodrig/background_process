<?php

include_once __DIR__ . '/../vendor/autoload.php';

use edwrodrig\background_process\Mailer;
use edwrodrig\background_process\Process;

exec('rm -rf /tmp/ABC');
$process = new Process('/tmp', 'ls -la');
$process->setId('ABC');
$process->setMail('');
$process->run();

sleep(1);

$mailer = new Mailer($process);
$mailer->setMail('edwrodrig@gmail.com');
$mailer->send();
