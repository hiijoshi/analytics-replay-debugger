<?php
declare(strict_types=1);

final class Logger {
    public static function info(string $msg, array $ctx = []): void { self::write('INFO', $msg, $ctx); }
    public static function error(string $msg, array $ctx = []): void { self::write('ERROR', $msg, $ctx); }

    private static function write(string $level, string $msg, array $ctx): void {
        $line = json_encode([
            'ts' => date('c'),
            'level' => $level,
            'msg' => $msg,
            'ctx' => $ctx
        ], JSON_UNESCAPED_SLASHES);
        file_put_contents(__DIR__ . '/../../storage/ard.log', $line . PHP_EOL, FILE_APPEND);
    }
}
