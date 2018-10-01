<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 25-09-18
 * Time: 11:34
 */

namespace edwrodrig\background_process;

use DateTime;

class Process
{
    private $base_folder;

    /**
     * @var string
     */
    private $command;

    /**
     * @var string|null
     */
    private $id = null;

    /**
     * @var string|null
     */
    private $process_result_handler_classname = '';


    public function __construct(string $base_folder, string $command) {
        $this->base_folder = $base_folder;
        $this->command = $command;
    }

    public function setId(string $id) {
        $this->id = $id;
    }

    public function setResultHandler(string $process_result_handler_classname) {
        $this->process_result_handler_classname = $process_result_handler_classname;

        if ( !is_subclass_of($process_result_handler_classname, ProcessResultHandler::class) )
            $this->process_result_handler_classname = '';
    }

    public function getId() : string {
        if ( is_null($this->id)) {
            $now = new DateTime();
            $this->id = $now->format('Y-m-d_H-i-s-u');
        }
        return $this->id;
    }

    public function getCommand() : string {
        return $this->command;
    }

    public function setRunning(bool $running) {
        $this->setFlagFile('running', $running);
    }

    public function isRunning() : bool {
        return $this->hasFlagFile('running');
    }

    public function hasPending() : bool {
        return $this->hasFlagFile('pending');
    }


    public function getPendingId() : string {
        $filename = $this->base_folder . DIRECTORY_SEPARATOR . 'pending';
        return file_get_contents($filename);
    }

    public function clearPending() {
        $filename = $this->base_folder . DIRECTORY_SEPARATOR . 'pending';
        if ( file_exists($filename) )
            unlink($filename);
    }

    public function setPending(string $pending_id) {
        $filename = $this->base_folder . DIRECTORY_SEPARATOR . 'pending';
        file_put_contents($filename, $pending_id);
    }

    public function getFilename($filename) : string {
        return $this->getFolder() . DIRECTORY_SEPARATOR . $filename;
    }

    public function getFolder() : string {
        return $this->base_folder . DIRECTORY_SEPARATOR . $this->id;
    }

    public function getStdOutFilename() : string {
        return $this->getFilename('stdout');
    }

    public function getResultHandler() : string {
        return $this->process_result_handler_classname;
    }

    public function getStdErrFilename() : string {
        return $this->getFilename('stderr');
    }

    public function getStdOut() : string {
        return file_get_contents($this->getStdOutFilename());
    }

    public function getStdErr() : string {
        return file_get_contents($this->getStdErrFilename());
    }

    public function getNohupCommand() : string {
        return sprintf(
            'nohup php %s %s %s %s %s >/dev/null 2>&1 &',
            escapeshellarg(__DIR__ . '/scripts/worker.php'),
            escapeshellarg(Util::getAutoloaderScriptPath()),
            escapeshellarg($this->command),
            escapeshellarg($this->base_folder),
            escapeshellarg($this->getResultHandler())
        );
    }


    public function run() : ?string {
        if ( !file_exists($this->base_folder) )
            mkdir($this->base_folder, 0777, true);

        if ( $this->isRunning() ) {
            if ( !$this->hasPending() ) {
                $this->setPending($this->getId());
                return $this->getId();
            } else {
                $pending_id = $this->getPendingId();
                $this->setId($pending_id);
                return $pending_id;
            }
        } else {
            $this->setRunning(true);
            $this->setPending($this->getId());

            shell_exec($this->getNohupCommand());
            return $this->getId();
        }
    }

    private function hasFlagFile(string $name) : bool {
        $filename = $this->base_folder . DIRECTORY_SEPARATOR . $name;
        return file_exists($filename);
    }

    private function setFlagFile(string $name, bool $value) {
        $filename = $this->base_folder . DIRECTORY_SEPARATOR . $name;
        if ( $value )
            touch($filename);
        else {
            if ( file_exists($filename) )
                unlink($filename);
        }
    }
}