<?php
declare(strict_types=1);

final class Config {
    public static function get(string $key, ?string $default = null): string {
        $val = getenv($key);
        if ($val === false || $val === '') return $default ?? '';
        return $val;
    }

    public static function bool(string $key, bool $default = false): bool {
        $v = strtolower(self::get($key, $default ? 'true' : 'false'));
        return in_array($v, ['1','true','yes','on'], true);
    }
}