<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 25-09-18
 * Time: 13:59
 */

use edwrodrig\background_process\Util;
use PHPUnit\Framework\TestCase;

class UtilTest extends TestCase
{

    public function testGetAutoloaderScriptPath()
    {
        $autoloader = Util::getAutoloaderScriptPath();
        $this->assertStringEndsWith('/vendor/autoload.php', $autoloader);
        $this->assertFileExists($autoloader);
    }
}
