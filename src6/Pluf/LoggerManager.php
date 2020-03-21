<?php
namespace Pluf;

use Psr\Log\AbstractLogger;
use Pluf;

class LoggerManager extends AbstractLogger
{

    /**
     * Logger key
     *
     * @var string
     */
    private ?string $key = null;

    /**
     * Log formater
     *
     * @var LoggerFormatter
     */
    private ?LoggerFormatter $loggerFormater = null;

    /**
     * Logger appender
     *
     * @var LoggerAppender
     */
    private ?LoggerAppender $loggerAppender = null;

    /**
     * Log level of the logger
     *
     * @var int
     */
    private int $levelMark = Logger::OFF;

    /**
     * Buffers all messages
     *
     * @var array
     */
    private array $stack = [];

    /**
     * Create new instance of logger manager
     *
     * @param string $key
     * @param LoggerFormatter $loggerFormater
     * @param LoggerAppender $loggerAppender
     */
    public function __construct(string $key, LoggerFormatter $loggerFormater, LoggerAppender $loggerAppender)
    {
        $this->key = $key;
        $this->loggerFormater = $loggerFormater;
        $this->loggerAppender = $loggerAppender;

        $this->levelMark = Logger::toLevelMarker(Pluf::f('log_level', 'off'));
    }

    /**
     * Gets key of the logger
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * write a log
     *
     * {@inheritdoc}
     * @see \Psr\Log\LoggerInterface::log()
     */
    public function log($level, $message, array $context = [])
    {
        // check log leverl
        if ($this->levelMark > Logger::toLevelMarker($level)) {
            return;
        }

        // generate new message
        $strMessage = $this->loggerFormater->format($this, $level, $message, $context);

        // check if should write now
        if (! Pluf::f('log_delayed', false)) {
            $this->loggerAppender->write($strMessage);
        }

        // push message to buffer
        $this->stack[] = $strMessage;
    }

    /**
     * Flush the data to the writer.
     *
     * This reset the stack.
     */
    public function flush()
    {
        if (count($this->stack) == 0) {
            return;
        }

        // write all messages
        foreach ($this->stack as $strMessage) {
            $this->loggerAppender->write($strMessage);
        }
        $this->stack = array();
    }
}