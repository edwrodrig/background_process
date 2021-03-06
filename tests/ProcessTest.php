<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 25-09-18
 * Time: 14:38
 */

use edwrodrig\background_process\Process;
use PHPUnit\Framework\TestCase;
use test\edwrodrig\background_process\DummyHandler;

class ProcessTest extends TestCase
{

    public $test_folder = '/tmp/test_background_process';

    public function setUp() {
        exec('rm -rf ' . $this->test_folder);
    }

    public function tearDown() {
        exec('rm -rf ' . $this->test_folder);
    }

    public function testResultHandler() {
        $process = new Process($this->test_folder, 'ls -la');
        $process->setResultHandler('NotExistantClass');
        $this->assertEquals('', $process->getResultHandler());

        $process->setResultHandler(DummyHandler::class);
        $this->assertEquals(DummyHandler::class, $process->getResultHandler());
    }

    public function testGetNohupCommand()
    {
        $process = new Process($this->test_folder, 'ls -la');
        $process->setId('process_id');
        $process->setResultHandler('Class');
        $command = $process->getNohupCommand();
        $this->assertStringStartsWith("nohup php '", $command);
        $this->assertContains("src/scripts/worker.php'", $command);
        $this->assertStringEndsWith("/vendor/autoload.php' 'ls -la' '".  $this->test_folder . "' '' >/dev/null 2>&1 &", $command);
    }

    public function testRun() {

        $process = new Process($this->test_folder, 'ls -la ' . $this->test_folder . '/ABC');
        $process->setId('ABC');
        $process->setResultHandler(DummyHandler::class);
        $process->run();

        while ( $process->isRunning() );
        $this->assertFalse($process->isRunning());

        $this->assertEmpty($process->getStdErr());
        $stdout = $process->getStdOut();
        $this->assertContains('stdout',$stdout);
        $this->assertContains('stderr',$stdout);
        $this->assertFileExists($process->getFolder() . DIRECTORY_SEPARATOR . 'mail');
    }

    public function testRunQueue() {

        $process = new Process($this->test_folder, 'echo hola; sleep 3; echo chao');
        $process->setId('ABC');
        $process->setResultHandler('');
        $id = $process->run();

        $this->assertEquals('ABC', $id);
        $this->assertTrue($process->isRunning());

        while ( $process->hasPending() );
        $process->setId('CDE');
        $id = $process->run();
        $this->assertEquals('CDE', $id);
        $this->assertTrue($process->hasPending());


        while ( $process->isRunning() );
        $this->assertFalse($process->isRunning());

        $this->assertEmpty($process->getStdErr());
        $stdout = $process->getStdOut();
        $this->assertContains('hola',$stdout);
        $this->assertContains('chao',$stdout);
    }

}
