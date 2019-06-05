<?php
/**
* Simple autoloader, so we don't need Composer just for this.
*/
class Autoloader {
    public static function register() {
        spl_autoload_register(function ($class) {

            $file = dirname(dirname(dirname(__FILE__))) .'/lib/'. str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';
            if (file_exists($file)) {
                require $file;
                return true;
            }

            $file = dirname(dirname(__FILE__)) .'/'. str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';
            if (file_exists($file)) {
                require $file;
                return true;
            }

            $file = dirname(dirname(__FILE__)) .'/models/'. str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';
            if (file_exists($file)) {
                require $file;
                return true;
            }

            return false;
        });
    }
}
Autoloader::register();
