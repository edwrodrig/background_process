<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 01-10-18
 * Time: 11:06
 */

namespace edwrodrig\background_process;

use Error;
use Exception;

class ProcessExecution
{
    /**
     * @var Process
     */
    private $process;

    public function __construct(Process $process) {
        $this->process = $process;
    }

    /**
     * Executre the pr
     */
    public function execute() {
        $this->prepareFiles();

        $resource = proc_open(
            $this->process->getCommand(),
            [
                1 => ['file', $this->process->getStdOutFilename(), 'a'],
                2 => ['file', $this->process->getStdErrFilename(), 'a']
            ],
            $pipes
        );

        $this->process->clearPending();

        if (is_resource($resource) ) {
            //this wait for close
            $exit_code = proc_close($resource);

            $this->handleResult($exit_code);
        }
    }

    /**
     * Prepare files for execution
     *
     * Creates the directory if not exists and clear existant files
     * @see ProcessExecution::execute()
     */
    private function prepareFiles() {
        $folder = $this->process->getFolder();
        if ( !file_exists($folder) )
            mkdir($folder, 0777, true);

        $stdout_filename = $this->process->getStdOutFilename();
        if ( file_exists($stdout_filename) )
            unlink($stdout_filename);

        $stderr_filename = $this->process->getStdErrFilename();
        if ( file_exists($stderr_filename) )
            unlink($stderr_filename);
    }

    /**
     * Handle result
     *
     * Call the handler class with the current process
     *
     * @see ProcessExecution::execute()
     * @param int $exit_code
     * @return bool true is success, false if an error
     */
    private function handleResult(int $exit_code) : bool {
        $result_handler_classname = $this->process->getResultHandler();

        if ( !is_subclass_of($result_handler_classname, ProcessResultHandler::class) )
            return false;

        try {
            /**
             * @var $result_handler ProcessResultHandler
             */
            $result_handler = new $result_handler_classname($this->process);
            $result_handler->handleResult($exit_code);
            return true;

        } catch  ( Exception | Error $e ) {
            return false;
        }
    }

}