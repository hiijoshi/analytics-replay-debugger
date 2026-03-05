<?php
declare(strict_types=1);

final class Time {
    public static function nowIso(): string { return gmdate('c'); } // UTC
    public static function nowMs(): int { return (int)floor(microtime(true) * 1000); }
}