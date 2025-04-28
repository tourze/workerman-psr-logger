# Workerman PSR Logger

[![Latest Version](https://img.shields.io/packagist/v/tourze/workerman-psr-logger.svg?style=flat-square)](https://packagist.org/packages/tourze/workerman-psr-logger)
[![Build Status](https://github.com/tourze/workerman-psr-logger/actions/workflows/ci.yml/badge.svg)](https://github.com/tourze/workerman-psr-logger/actions)

A PSR-3 compatible logger that integrates seamlessly with Workerman's built-in logging system and outputs structured JSON logs.

---

## Features

- Implements PSR-3 LoggerInterface
- Integrates with Workerman's built-in logging system
- JSON formatted log output for easy parsing
- Supports all PSR-3 log levels (emergency, alert, critical, error, warning, notice, info, debug)
- Context support for structured logging
- Extra utility for hexdump and binary data logging

---

## Requirements

- PHP >= 8.1
- Workerman >= 5.1
- PSR Log Interface (v1, v2, or v3)

---

## Installation

Install via Composer:

```bash
composer require tourze/workerman-psr-logger
```

---

## Quick Start

```php
use Tourze\Workerman\PsrLogger\WorkermanLogger;

$logger = new WorkermanLogger();

// Basic usage
$logger->info('Server started');

// With context
$logger->error('Connection failed', [
    'ip' => '127.0.0.1',
    'port' => 8080
]);
```

---

## Log Format

Logs are output as JSON with the following structure:

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

---

## Advanced Usage

Use `LogUtil` for hexdump and exception logging:

```php
use Tourze\Workerman\PsrLogger\LogUtil;

LogUtil::debug('Received binary data', $binaryData);
LogUtil::error('Exception occurred', $exception);
```

---

## Contributing

- Pull requests and issues are welcome.
- Please follow PSR code style.
- Ensure tests cover major features.

---

## License

MIT License. Copyright (c) tourze

---

## Changelog

See [CHANGELOG.md] if available.

## License 许可证

MIT
