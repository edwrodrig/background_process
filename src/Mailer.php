<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 25-09-18
 * Time: 17:24
 */

namespace edwrodrig\background_process;


use Nette\Mail\Message;
use Nette\Mail\SmtpMailer;

class Mailer
{
    /**
     * @var Process
     */
    private $process;
    private $mail;

    public function __construct(Process $process) {
        $this->process = $process;
    }

    public function setMail(string $mail) {
        $this->mail = $mail;
    }

    public function send() {
        $mail = new Message;
        $mail->setFrom('John <john@example.com>')
            ->addTo($this->mail)
            ->setSubject('Generation end')
            ->setBody("Hello, Your order has been accepted.");
        $mail->addAttachment($this->process->getStdoutFilename());
        $mail->addAttachment($this->process->getStdErrFilename());

        $mailer = new SmtpMailer([
            'host' => 'smtp.gmail.com',
            'username' => 'no.responder@imo-chile.cl',
            'password' => '*******',
            'secure' => 'ssl'
        ]);
        $mailer->send($mail);
    }
}