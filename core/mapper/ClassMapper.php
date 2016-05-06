<?php
namespace Seshat\Mapper;

define("DOCUMENT_ROOT", $_SERVER["DOCUMENT_ROOT"]);

class ClassMapper
{

    private static $directory;

    public function __construct($directory = '')
    {
        if (is_string($directory))
            self::$directory = $directory;
        spl_autoload_register("\\self::autoload");
    }

    public static function autoload($className)
    {
        if (! is_string($className))
            throw new \Exception();
        self::requireClasses($className);
    }

    private function requireClasses($className)
    {
        $map = self::getSetupFile();
        foreach ($map as $class => $file) {
            if ($class == $className) {
                try {
                    require_once DOCUMENT_ROOT . "/{$file}";
                } catch (\Exception $e) {
                    echo 'Error: ', $e->getMessage();
                }
            }
        }
    }

    private function getSetupFile()
    {
        return json_decode(file_get_contents(str_replace("\\", "/", DOCUMENT_ROOT . self::$directory . "/classmap.json")));
    }

    public function __toString()
    {
        return get_class($this);
    }
}