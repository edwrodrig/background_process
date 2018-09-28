<?php

$autoload_script = $argv[1];

/** @noinspection PhpIncludeInspection */
include_once $autoload_script;

use edwrodrig\background_process\Process;
use Nette\Mail\Message;

/** @var $command The command to execute */
$command = $argv[2];

/** @var $base_folder The base folder where everything will be executed */
$base_folder = $argv[3];

/** @var $mail */
$mail = $argv[4] ?? null;
if ( empty($mail) ) $mail = null;

$process = new Process($base_folder, $command);


do {
    $process->setId($process->getPendingId());

    $folder = $process->getFolder();
    if ( !file_exists($folder) )
        mkdir($folder, 0777, true);

    $stdout_filename = $process->getStdOutFilename();
    $stderr_filename = $process->getStdErrFilename();

    if ( file_exists($stdout_filename) )
        unlink($stdout_filename);

    if ( file_exists($stderr_filename) )
        unlink($stderr_filename);

    $resource = proc_open(
        $command,
        [
            1 => ['file', $stdout_filename, 'a'],
            2 => ['file', $stderr_filename, 'a']
        ],
        $pipes
    );

    $process->clearPending();

    if (is_resource($resource) ) {

        //this wait for close
        $exit_code = proc_close($resource);

        if ( !is_null($mail) ) {
            try {
                $mail = new Message;
                $mail->setFrom('John <john@example.com>')
                    ->addTo($mail)
                    ->setSubject('Generation end')
                    ->setBody("Hello, Your order has been accepted.");
                $mail->addAttachment($stdout_filename);
                $mail->addAttachment($stderr_filename);

                $mailer = new Nette\Mail\SmtpMailer([
                    'host' => 'smtp.gmail.com',
                    'username' => 'no.responder@imo-chile.cl',
                    'password' => '******',
                    'secure' => 'ssl'
                ]);
                $mailer->send($mail);
            } catch ( \Exception | \Error $e ) {

            }
        }
    }

} while ( $process->hasPending() );

$process->setRunning(false);