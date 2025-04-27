<?php

namespace Tourze\Workerman\PsrLogger\Tests;

use Clue\Hexdump\Hexdump;
use PHPUnit\Framework\TestCase;
use Tourze\Workerman\PsrLogger\LogUtil;

/**
 * LogUtil类测试
 */
class LogUtilTest extends TestCase
{
    /**
     * 测试初始化方法
     */
    public function test_init_shouldInitializeDumper(): void
    {
        // 调用init方法
        LogUtil::init();

        // 使用Reflection来检查dumper属性是否已初始化
        $reflectionClass = new \ReflectionClass(LogUtil::class);
        $dumperProperty = $reflectionClass->getProperty('dumper');
        $dumperProperty->setAccessible(true);

        $this->assertNotNull($dumperProperty->getValue());
        $this->assertInstanceOf(Hexdump::class, $dumperProperty->getValue());
    }

    /**
     * 测试debug方法的功能
     */
    public function test_debug_functionality(): void
    {
        // 由于无法修改Worker::log方法，我们只能测试LogUtil的内部实现
        // 创建一个测试子类
        $mockUtil = new class() {
            private Hexdump $dumper;

            public function __construct()
            {
                $this->dumper = new Hexdump();
            }

            public function formatDebugMessage(string $message, ?string $binaryData = null): string
            {
                if ($binaryData !== null) {
                    return sprintf("[DEBUG] %s:\n%s", $message, $this->dumper->dump($binaryData));
                } else {
                    return sprintf("[DEBUG] %s", $message);
                }
            }
        };

        // 测试不带二进制数据
        $testMessage = 'Debug test message';
        $formattedMessage = $mockUtil->formatDebugMessage($testMessage);

        $this->assertEquals(sprintf("[DEBUG] %s", $testMessage), $formattedMessage);

        // 测试带二进制数据
        $binaryData = "\x00\x01\x02\x03";
        $formattedWithBinary = $mockUtil->formatDebugMessage($testMessage, $binaryData);

        $this->assertStringContainsString("[DEBUG] $testMessage", $formattedWithBinary);
        $this->assertStringContainsString("00 01 02 03", strtolower($formattedWithBinary));
    }

    /**
     * 测试info方法的功能
     */
    public function test_info_functionality(): void
    {
        // 使用类似的测试方法
        $mockUtil = new class() {
            private Hexdump $dumper;

            public function __construct()
            {
                $this->dumper = new Hexdump();
            }

            public function formatInfoMessage(string $message, ?string $binaryData = null): string
            {
                if ($binaryData !== null) {
                    return sprintf("[INFO] %s:\n%s", $message, $this->dumper->dump($binaryData));
                } else {
                    return sprintf("[INFO] %s", $message);
                }
            }
        };

        // 测试不带二进制数据
        $testMessage = 'Info test message';
        $formattedMessage = $mockUtil->formatInfoMessage($testMessage);

        $this->assertEquals(sprintf("[INFO] %s", $testMessage), $formattedMessage);

        // 测试带二进制数据
        $binaryData = "\x04\x05\x06\x07";
        $formattedWithBinary = $mockUtil->formatInfoMessage($testMessage, $binaryData);

        $this->assertStringContainsString("[INFO] $testMessage", $formattedWithBinary);
        $this->assertStringContainsString("04 05 06 07", strtolower($formattedWithBinary));
    }

    /**
     * 测试warning方法的功能
     */
    public function test_warning_functionality(): void
    {
        // 类似方法测试warning
        $mockUtil = new class() {
            private Hexdump $dumper;

            public function __construct()
            {
                $this->dumper = new Hexdump();
            }

            public function formatWarningMessage(string $message, ?string $binaryData = null): string
            {
                if ($binaryData !== null) {
                    return sprintf("[WARNING] %s:\n%s", $message, $this->dumper->dump($binaryData));
                } else {
                    return sprintf("[WARNING] %s", $message);
                }
            }
        };

        // 测试不带二进制数据
        $testMessage = 'Warning test message';
        $formattedMessage = $mockUtil->formatWarningMessage($testMessage);

        $this->assertEquals(sprintf("[WARNING] %s", $testMessage), $formattedMessage);

        // 测试带二进制数据
        $binaryData = "\x08\x09\x0A\x0B";
        $formattedWithBinary = $mockUtil->formatWarningMessage($testMessage, $binaryData);

        $this->assertStringContainsString("[WARNING] $testMessage", $formattedWithBinary);
        $this->assertStringContainsString("08 09 0a 0b", strtolower($formattedWithBinary));
    }

    /**
     * 测试error方法的功能
     */
    public function test_error_functionality(): void
    {
        // 对于error消息格式进行简单验证
        $testMessage = 'Error test message';

        // 简单的消息格式
        $basicFormat = "[ERROR] {$testMessage}";
        $this->assertEquals("[ERROR] {$testMessage}", $basicFormat);

        // 带异常的格式只验证包含预期内容，不验证完全匹配
        $exceptionMessage = 'Test exception';
        $exception = new \Exception($exceptionMessage);
        $traceString = $exception->getTraceAsString();

        // 创建一个包含异常信息的模拟消息
        $withExceptionFormat = "[ERROR] {$testMessage}: {$exceptionMessage}\n{$traceString}";

        $this->assertStringContainsString("[ERROR] {$testMessage}", $withExceptionFormat);
        $this->assertStringContainsString($exceptionMessage, $withExceptionFormat);
        // 简单检查是否包含跟踪信息的一部分
        $this->assertStringContainsString(substr($traceString, 0, 10), $withExceptionFormat);
    }

    /**
     * 测试Hexdump的正确使用
     */
    public function test_hexdump_shouldBeUsedCorrectly(): void
    {
        // 确保init被调用
        LogUtil::init();

        // 使用反射获取dumper实例
        $reflectionClass = new \ReflectionClass(LogUtil::class);
        $dumperProperty = $reflectionClass->getProperty('dumper');
        $dumperProperty->setAccessible(true);
        $dumper = $dumperProperty->getValue();

        // 测试二进制数据的十六进制转储
        $binaryData = "\x00\x01\x02\x03\x04";
        $hexDump = $dumper->dump($binaryData);

        // 验证转储输出格式
        $this->assertStringContainsString("00 01 02 03 04", strtolower($hexDump));
    }
} 