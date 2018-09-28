<?php
declare(strict_types=1);

namespace edwrodrig\background_process;


class Util
{

    public static function getAutoloaderScriptPath() : ?string
    {
        static $autoloader = null;
        if (is_null($autoloader)) {
            $files = get_included_files();
            foreach ($files as $file) {
                if (strpos($file, 'autoload.php') === FALSE) continue;

                $autoloader = $file;
                break;
            }

        }
        return $autoloader;
    }
}