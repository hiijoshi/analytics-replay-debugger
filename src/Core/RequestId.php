<?php
declare(strict_types=1);

final class RequestId {
    public static function new(): string {
        return 'req_' . bin2hex(random_bytes(8));
    }
}