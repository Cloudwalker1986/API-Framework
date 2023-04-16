<?php

declare(strict_types=1);

namespace ApiCore\Logger\Factory;

use ApiCore\Logger\Config\LoggerConfig;
use ApiCore\Logger\Enum\LogOutput;
use ApiCore\Logger\Writer\NullLoggerWriter;
use ApiCore\Logger\Writer\StdErrWriter;
use ApiCore\Logger\Writer\StdOutWriter;
use ApiCore\Logger\Writer\WriterInterface;

class LoggerFactory
{
    private ?WriterInterface $writer = null;

    public function __construct(private readonly LoggerConfig $loggerConfig)
    {
    }

    public function getWriter(): WriterInterface
    {
        if ($this->writer === null) {
            $format = $this->loggerConfig->getLogFormat();
            $this->writer = match($this->loggerConfig->getOutput()) {
                LogOutput::STDOUT => new StdOutWriter($format),
                LogOutput::STDERR => new StdErrWriter($format),
                LogOutput::NULL => new NullLoggerWriter($format),
                default => throw new \Exception('Unexpected match value')
            };
        }
        return $this->writer;
    }
}
