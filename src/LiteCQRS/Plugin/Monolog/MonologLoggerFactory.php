<?php

namespace LiteCQRS\Plugin\Monolog;

use Monolog\Logger;
use Psr\Log\LoggerInterface;

class MonologLoggerFactory
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke($handler)
    {
        return new MonologDebugLogger($handler, $this->logger);
    }
}
