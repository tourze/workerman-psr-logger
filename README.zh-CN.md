# Workerman PSR Logger

一个与 Workerman 内置日志系统集成的 PSR Logger 实现，支持 PSR-3 日志接口，日志以 JSON 格式输出，适用于所有 Workerman 应用。

---

## 功能特性

- 实现 PSR-3 Logger 接口，兼容 PSR-3 标准
- 与 Workerman 内置日志系统无缝集成
- 日志输出为结构化 JSON 格式，便于机器解析
- 支持全部 PSR-3 日志级别（emergency、alert、critical、error、warning、notice、info、debug）
- 支持上下文信息传递
- 额外提供十六进制数据日志辅助工具

---

## 安装说明

- PHP >= 8.1
- Workerman >= 5.1
- PSR Log Interface (v1, v2, v3)
- 建议通过 Composer 安装：

```bash
composer require tourze/workerman-psr-logger
```

---

## 快速开始

```php
use Tourze\Workerman\PsrLogger\WorkermanLogger;

$logger = new WorkermanLogger();

// 基本用法
$logger->info('服务启动');

// 带上下文
$logger->error('连接失败', [
    'ip' => '127.0.0.1',
    'port' => 8080
]);
```

---

## 日志格式

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

---

## 进阶用法

- 通过 `LogUtil` 工具类，可输出十六进制数据日志、异常堆栈等，便于调试二进制内容或复杂错误。

```php
use Tourze\Workerman\PsrLogger\LogUtil;

LogUtil::debug('收到二进制数据', $binaryData);
LogUtil::error('发生异常', $exception);
```

---

## 贡献指南

- 欢迎提交 Issue 和 PR
- 代码风格遵循 PSR 标准
- 测试用例需覆盖主要功能

---

## 许可协议

- MIT License
- 作者：tourze

---

## 更新日志

详见 [CHANGELOG.md]（如有）
