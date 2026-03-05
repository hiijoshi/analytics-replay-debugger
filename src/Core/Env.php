<?php
declare(strict_types=1);

final class Env {
    public static function load(string $path): void {
        if (!file_exists($path)) return;
        $lines = file($path, FILE_IGNORE_NEW_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) continue;
            $parts = explode('=', $line, 2);
            if (count($parts) !== 2) continue;
            $k = trim($parts[0]);
            $v = trim($parts[1]);
            $v = trim($v, "\"'");
            putenv($k.'='.$v);
        }
    }
}