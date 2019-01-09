<?php

namespace App\Classes;

use Monolog\Formatter\LineFormatter;
class Logger
{
    public function __invoke($logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter($this->getLogFormatter());
        }
    }

    protected function getLogFormatter()
    {
        $format = str_replace(
            '[%datetime%] ',
            sprintf('[%%datetime%%] %s ', bin2hex(random_bytes(16))),
            LineFormatter::SIMPLE_FORMAT
        );

        return new LineFormatter($format, null, true, true);
    }
}

