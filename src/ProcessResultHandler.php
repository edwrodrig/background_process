<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 01-10-18
 * Time: 10:46
 */

namespace edwrodrig\background_process;

/**
 * Class ProcessResultHandler
 *
 * This is the base class to handle every
 * @see ProcessExecution::handleResult()
 * @package edwrodrig\background_process
 *
 */
abstract class ProcessResultHandler
{
    /**
     * @var Process
     */
    protected $process;

    /**
     * ProcessResultHandler constructor.
     *
     * Need to process
     * @param Process $process
     */
    public function __construct(Process $process) {
        $this->process = $process;
    }

    /**
     * The method that is executed when the process finishes
     *
     * There are some useful ways to implement it
     * ```
     * $this->process->getStdErrFilename()
     * $this->process->getStdOutFilename()
     * ```
     * @see ProcessResultHandler::$process
     * @see Process::getStdErrFilename()
     * @see Process::getStdOutFilename()
     * @param int $exit_code
     * @return bool if it is successful
     */
    public abstract function handleResult(int $exit_code) : bool;
}