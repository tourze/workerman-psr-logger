# Workerman PSR Logger

A PSR Logger implementation that integrates with Workerman's built-in logging system.

一个与 Workerman 内置日志系统集成的 PSR Logger 实现。

## Features 特性

- Implements PSR-3 Logger Interface 实现 PSR-3 日志接口
- Integrates with Workerman's built-in logging system 与 Workerman 内置日志系统集成
- JSON formatted log output JSON 格式的日志输出
- Support for all PSR-3 log levels 支持所有 PSR-3 日志级别
- Context support 支持上下文信息

## Requirements 要求

- PHP >= 8.1
- Workerman >= 5.1
- PSR Log Interface (v1, v2, or v3)

## Installation 安装

```bash
composer require tourze/workerman-psr-logger
```

## Usage 使用方法

```php
use Tourze\Workerman\PsrLogger\WorkermanLogger;

$logger = new WorkermanLogger();

// Basic usage 基本使用
$logger->info('Server started');

// With context 带上下文
$logger->error('Connection failed', [
    'ip' => '127.0.0.1',
    'port' => 8080
]);
```

## Log Format 日志格式

The logger outputs JSON formatted logs with the following structure:

日志以 JSON 格式输出，结构如下：

```json
{
    "level": "INFO",
    "datetime": "2024-03-24 10:30:45.123456",
    "message": "Server started",
    "context": {
        "ip": "127.0.0.1",
        "port": 8080
    }
}
```

## License 许可证

MIT
