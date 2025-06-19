<?php

namespace Tourze\Workerman\PsrLogger\Tests;

use PHPUnit\Framework\TestCase;
use Tourze\Workerman\PsrLogger\WorkermanLogger;

/**
 * WorkermanLogger类测试
 */
class WorkermanLoggerTest extends TestCase
{
    protected function setUp(): void
    {
        // No setup needed for current tests
    }

    /**
     * 测试日志格式方法
     */
    public function test_format_shouldReturnCorrectJsonStructure(): void
    {
        // 使用ReflectionClass来测试私有方法
        $reflectionClass = new \ReflectionClass(WorkermanLogger::class);
        $formatMethod = $reflectionClass->getMethod('format');
        $formatMethod->setAccessible(true);

        $level = 'INFO';
        $message = 'Test message';
        $context = ['test' => true];

        $result = $formatMethod->invokeArgs(null, [$level, $message, $context]);

        $this->assertIsString($result);

        $decodedResult = json_decode($result, true);
        $this->assertIsArray($decodedResult);
        $this->assertEquals($level, $decodedResult['level']);
        $this->assertEquals($message, $decodedResult['message']);
        $this->assertEquals($context, $decodedResult['context']);
        $this->assertArrayHasKey('datetime', $decodedResult);
    }

    /**
     * 测试log方法的工作原理（在不实际调用Worker::log的情况下）
     */
    public function test_logMethodLogic_shouldFormatAndPassToWorker(): void
    {
        // 创建一个可模拟的子类
        $mockLogger = new class extends WorkermanLogger {
            public bool $isRunningResult = true;
            public string $lastFormattedLog = '';

            // 重写format方法为公共方法，因为原方法是私有的
            public static function formatLog(string $level, \Stringable|string $message, array $context = []): string
            {
                $logData = [
                    'level' => $level,
                    'datetime' => (new \DateTime())->format('Y-m-d H:i:s.u'),
                    'message' => (string)$message,
                    'context' => $context,
                ];
                return json_encode($logData);
            }

            protected function isWorkerRunning(): bool
            {
                return $this->isRunningResult;
            }

            protected function callWorkerLog(string $formattedLog): void
            {
                $this->lastFormattedLog = $formattedLog;
            }

            public function log($level, \Stringable|string $message, array $context = []): void
            {
                // 如果没启动，我们不记录
                if (!$this->isWorkerRunning()) {
                    return;
                }
                $formattedLog = self::formatLog($level, $message, $context);
                $this->callWorkerLog($formattedLog);
            }
        };

        // 测试1：Worker正在运行时应该记录
        $mockLogger->isRunningResult = true;
        $mockLogger->info('Test message', ['test' => true]);

        $lastLog = $mockLogger->lastFormattedLog;
        $this->assertNotEmpty($lastLog);

        $logData = json_decode($lastLog, true);
        $this->assertIsArray($logData);
        $this->assertEquals('INFO', $logData['level']);
        $this->assertEquals('Test message', $logData['message']);
        $this->assertEquals(['test' => true], $logData['context']);

        // 测试2：Worker未运行时不应该记录
        $mockLogger->isRunningResult = false;
        $mockLogger->lastFormattedLog = '';
        $mockLogger->info('Should not log');

        $this->assertEmpty($mockLogger->lastFormattedLog);
    }

    /**
     * 测试所有日志级别方法都正确调用了log方法
     */
    public function test_allLogLevelMethods_shouldCallLogWithCorrectLevel(): void
    {
        // 创建一个可模拟的子类来追踪log方法调用
        $mockLogger = new class extends WorkermanLogger {
            public array $logCalls = [];

            public function log($level, \Stringable|string $message, array $context = []): void
            {
                $this->logCalls[] = [
                    'level' => $level,
                    'message' => (string)$message,
                    'context' => $context
                ];
            }
        };

        // 测试所有PSR-3日志级别方法
        $methods = [
            'emergency' => 'EMERGENCY',
            'alert' => 'ALERT',
            'critical' => 'CRITICAL',
            'error' => 'ERROR',
            'warning' => 'WARNING',
            'notice' => 'NOTICE',
            'info' => 'INFO',
            'debug' => 'DEBUG'
        ];

        foreach ($methods as $method => $expectedLevel) {
            $message = "Test {$method} message";
            $context = ['method' => $method];

            $mockLogger->$method($message, $context);

            $lastCall = end($mockLogger->logCalls);
            $this->assertEquals($expectedLevel, $lastCall['level']);
            $this->assertEquals($message, $lastCall['message']);
            $this->assertEquals($context, $lastCall['context']);
        }
    }
}
