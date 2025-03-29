<?php

namespace Tourze\Workerman\PsrLogger;

use Psr\Log\LoggerInterface;
use Workerman\Worker;
use Yiisoft\Json\Json;

class WorkermanLogger implements LoggerInterface
{
    public function emergency(\Stringable|string $message, array $context = []): void
    {
        $this->log('EMERGENCY', $message, $context);
    }

    public function alert(\Stringable|string $message, array $context = []): void
    {
        $this->log('ALERT', $message, $context);
    }

    public function critical(\Stringable|string $message, array $context = []): void
    {
        $this->log('CRITICAL', $message, $context);
    }

    public function error(\Stringable|string $message, array $context = []): void
    {
        $this->log('ERROR', $message, $context);
    }

    public function warning(\Stringable|string $message, array $context = []): void
    {
        $this->log('WARNING', $message, $context);
    }

    public function notice(\Stringable|string $message, array $context = []): void
    {
        $this->log('NOTICE', $message, $context);
    }

    public function info(\Stringable|string $message, array $context = []): void
    {
        $this->log('INFO', $message, $context);
    }

    public function debug(\Stringable|string $message, array $context = []): void
    {
        $this->log('DEBUG', $message, $context);
    }

    private static function format(string $level, \Stringable|string $message, array $context = []): string
    {
        return Json::encode([
            'level' => $level,
            'datetime' => (new \DateTime())->format('Y-m-d H:i:s.u'),
            'message' => $message,
            'context' => $context,
        ]);
    }

    public function log($level, \Stringable|string $message, array $context = []): void
    {
        // 如果没启动，我们不记录
        if (Worker::getStatus() === Worker::STATUS_INITIAL) {
            return;
        }
        Worker::log(self::format($level, $message, $context));
    }
}
