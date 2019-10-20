<?php

namespace Game\Loggers;

/**
 * Singleton class to be used as a simple file logging system
 * 
 * @author Mihail Cuculici <mihai.cuculici@gmail.com>
 * @version 0.8
 * @since 17 Oct 2019
 * @license https://www.gnu.org/licenses/gpl-3.0.txt
 */
class FileLogger extends AbstractLogger{
    
    public static $_instance;
    
    /**
     * 
     * @var string
     */
    protected $fileName;

    /**
     * Use this to get an instance of the FileLogger class
     * @return FileLogger
     */
    public static function getInstance(): AbstractLogger{
        if (is_null(static::$_instance)){
            $logName = "logs_".date("Y-m-d").".txt";
            static::$_instance = new FileLogger($logName);
        }
        return static::$_instance;
    }
    
    /**
     * Private constructor to ensure that new instances cannot be created
     * @param string $fileName The path for the output file
     */
    private function __construct(string $fileName) {
        $this->fileName = $fileName;
    }
    
    /**
     * Write messages to a predefined file
     * @param string $message
     * @param string $logLevel
     * @return bool Always true
     */
    public function log(string $message, string $logLevel = AbstractLogger::NOTICE): bool{
        if ($logLevel != AbstractLogger::NONE){
            file_put_contents($this->fileName, "[".$this->getDate()."] ".$logLevel.": ".$message.PHP_EOL, FILE_APPEND | LOCK_EX);
        }
        return true;
    }
    
}