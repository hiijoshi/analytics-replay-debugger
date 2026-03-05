<?php
declare(strict_types=1);
schema_version => "v1"

final class JourneySimulator {
    public static function run(EventStore $store, string $distinctId, string $companyId): void {
        $req = RequestId::new();
        $baseProps = [
            'company_id' => $companyId,
            'request_id' => $req,
            'env' => Config::get('APP_ENV', 'local'),
        ];

        // 1) account_created
        $store->captureTrack('account_created', $distinctId, $req, Time::nowMs(), array_merge($baseProps, [
            'email' => 'himanshu+'.rand(10,999).'@example.com'
        ]));

        usleep(200_000);

        // 2) vendor_invite_sent
        $store->captureTrack('vendor_invite_sent', $distinctId, $req, Time::nowMs(), array_merge($baseProps, [
            'email' => 'vendor@test.com',
            'channel' => 'admin_panel'
        ]));

        usleep(200_000);

        // 3) order_completed (demo)
        $store->captureTrack('order_completed', $distinctId, $req, Time::nowMs(), array_merge($baseProps, [
            'order_id' => 'ORD-' . rand(1000,9999),
            'amount' => rand(500,5000),
            'currency' => 'INR'
        ]));

        // engage profile upsert
        $engageReq = RequestId::new();
        $profile = [
            '$token' => Config::get('MIXPANEL_PROJECT_TOKEN'),
            '$distinct_id' => $distinctId,
            '$set' => [
                'request_id' => $engageReq,
                'company_id' => $companyId,
                'role' => 'simulated_user',
                'country' => 'IN'
            ]
        ];
        $store->captureEngage($distinctId, $engageReq, Time::nowMs(), $profile);

        Logger::info('simulation_done', ['distinct_id'=>$distinctId, 'request_id'=>$req]);
    }
}