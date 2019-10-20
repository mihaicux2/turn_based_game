<?php

namespace Game\Loggers;

/**
 * Abstract class to serve as a base for the future Logger objects.
 * 
 * @author Mihail Cuculici <mihai.cuculici@gmail.com>
 * @version 0.8
 * @since 17 Oct 2019
 * @license https://www.gnu.org/licenses/gpl-3.0.txt
 */
abstract class AbstractLogger
{    
    const NONE = "[NONE]";
    const NOTICE = "[NOTICE]";
    const WARNING = "[WARNING]";
    const ERROR = "[ERROR]";
    const FATAL = "[FATAL]";
    
    /**
     * How each message's timestamp should be stored
     * @var string
     */
    protected $timeFormat = "d-m-Y H:i:s";
    
    /**
     * Returns the current timestamp, in the predefined format ( + microseconds )
     * @return string
     */
    public function getDate(): string{
        
        $micro_date = microtime();
        $date_array = explode(" ",$micro_date);
        $date = date($this->timeFormat,$date_array[1]);
        
        return date($date.$date_array[0]);
    }
    
    /**
     * This method should be implemented by extending classes.
     * It should be used to store the messages in a local file, a table from a given database, etc.
     */
    abstract public function log(string $message, string $logLevel = AbstractLogger::NOTICE): bool;
    
}