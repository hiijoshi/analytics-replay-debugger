<?php
declare(strict_types=1);

final class Schema {
    // simple rules: event_name => required properties
    public static function requiredProps(): array {
        return [
            'vendor_invite_sent' => ['company_id','email','channel','request_id'],
            'order_completed' => ['order_id','amount','currency','request_id'],
            'account_created' => ['email','request_id'],
        ];
    }
}