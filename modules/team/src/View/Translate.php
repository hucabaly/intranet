<?php
namespace Rikkei\Team\View;

/**
 * read and write file translate
 */
class Translate
{
    
    protected static $file = [];
    
    /**
     * write translate word
     * 
     * @param string $wordOrgin
     * @param string $wordTranslate
     * @param string $fileName
     * @param string $lang
     * @throws \Rikkei\Team\View\Exception
     */
    public static function writeWord($wordOrgin, $wordTranslate, $fileName, $lang = 'vi')
    {
        $prefixFile = '<?php' . PHP_EOL. 'return ';
        $suffixFile = ';';
        
        $filePath = RIKKEI_TEAM_PATH . 'resources/lang/' . $lang . '/' . $fileName . '.php';
        if (! file_exists($filePath)) {
            return;
        }
        $data = [];
        if (isset(self::$file[$fileName][$lang])) {
            $data = self::$file[$fileName][$lang];
        } else {
            $data = require $filePath;
            self::$file[$fileName][$lang] = $data;
        }
        $data[$wordOrgin] = $wordTranslate;
        try {
            $dataExport = var_export($data, true);
            $fileTranslate = fopen($filePath, "w");
            $dataExport = $prefixFile . $dataExport . $suffixFile;
            fwrite($fileTranslate, $dataExport);
            fclose($fileTranslate);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
