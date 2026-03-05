<?php
declare(strict_types=1);

final class Validator {
    public function validateTrack(string $eventName, array $props): array {
        $rules = Schema::requiredProps();
        $required = $rules[$eventName] ?? ['request_id'];

        $missing = [];
        foreach ($required as $k) {
            if (!array_key_exists($k, $props) || $props[$k] === '' || $props[$k] === null) {
                $missing[] = $k;
            }
        }

        if ($missing) {
            return ['ok' => false, 'error' => 'Missing props: ' . implode(', ', $missing)];
        }
        return ['ok' => true, 'error' => null];
    }

    public function validateEngage(array $payload): array {
        // must include $distinct_id and $set
        if (!isset($payload['$distinct_id']) || $payload['$distinct_id'] === '') {
            return ['ok'=>false,'error'=>'Missing $distinct_id'];
        }
        if (!isset($payload['$set']) || !is_array($payload['$set'])) {
            return ['ok'=>false,'error'=>'Missing $set'];
        }
        if (!isset($payload['$set']['request_id'])) {
            return ['ok'=>false,'error'=>'Missing request_id in $set'];
        }
        return ['ok'=>true,'error'=>null];
    }
}