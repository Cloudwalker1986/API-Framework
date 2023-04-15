<?php

declare(strict_types=1);

namespace ApiCore\Logger\Config;

use ApiCore\Config\Attribute\Configuration;
use ApiCore\Config\Attribute\Value;
use ApiCore\Logger\Enum\LogFormat;
use ApiCore\Logger\Enum\LogOutput;

#[Configuration]
class LoggerConfig
{
    private LogOutput $logOutput;
    private LogFormat $logFormat;

    public function setConfig(
        #[Value('log.output', LogOutput::STDOUT)] LogOutput|string $logOutput,
        #[Value('log.format', LogFormat::JSON)] LogOutput|string $logFormat
    ): void {
        $this->logOutput = $logOutput instanceof LogOutput ? $logOutput : LogOutput::tryFrom($logOutput);
        $this->logFormat = $logFormat instanceof LogFormat ? $logFormat : LogFormat::tryFrom($logFormat);
    }

    public function getOutput(): LogOutput
    {
        return $this->logOutput;
    }

    public function getLogFormat(): LogFormat
    {
        return $this->logFormat;
    }
}
