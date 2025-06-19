<?php

namespace Tourze\Workerman\PsrLogger;

use Clue\Hexdump\Hexdump;
use Workerman\Worker;

class LogUtil
{
    private static ?Hexdump $dumper = null;

    public static function init(): void
    {
        if (null === self::$dumper) {
            self::$dumper = new Hexdump();
        }
    }

    public static function debug(string $message, ?string $binaryData = null): void
    {
        self::init();
        if ($binaryData !== null) {
            Worker::log(sprintf("[DEBUG] %s:\n%s", $message, self::$dumper->dump($binaryData)));
        } else {
            Worker::log(sprintf("[DEBUG] %s", $message));
        }
    }

    public static function info(string $message, ?string $binaryData = null): void
    {
        self::init();
        if ($binaryData !== null) {
            Worker::log(sprintf("[INFO] %s:\n%s", $message, self::$dumper->dump($binaryData)));
        } else {
            Worker::log(sprintf("[INFO] %s", $message));
        }
    }

    public static function error(string $message, ?\Throwable $e = null): void
    {
        if (null !== $e) {
            Worker::log(sprintf("[ERROR] %s: %s\n%s", $message, $e->getMessage(), $e->getTraceAsString()));
        } else {
            Worker::log(sprintf("[ERROR] %s", $message));
        }
    }

    public static function warning(string $message, ?string $binaryData = null): void
    {
        self::init();
        if ($binaryData !== null) {
            Worker::log(sprintf("[WARNING] %s:\n%s", $message, self::$dumper->dump($binaryData)));
        } else {
            Worker::log(sprintf("[WARNING] %s", $message));
        }
    }
}
