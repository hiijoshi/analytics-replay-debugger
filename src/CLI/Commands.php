<?php
declare(strict_types=1);

final class Commands {
    /**
     * Display the manual for the CLI tool.
     */
    public static function help(): void {
        echo "Analytics Replay & Debugger (ARD)\n\n";
        echo "Usage:\n";
        echo "  php bin/ard.php <command> [--user=<id>] [--company=<id>] [--limit=<n>]\n\n";
        echo "Commands:\n";
        echo "  simulate  Create a mock user journey (random user/company if flags omitted)\n";
        echo "  timeline  Show raw event logs for a specific user\n";
        echo "  validate  Check events against schema and update status\n";
        echo "  replay    Send pending events to the external analytics provider\n";
        echo "  inspect   Generate a summary report for a user\n";
        echo "  funnel    Check progress through the standard conversion funnel\n";
        echo "  graph     Display a visual flow of the user's journey\n";
    }

    /**
     * Main entry point for the command line application.
     */
    public static function run(array $argv): void {
        $cmd = $argv[1] ?? 'help';
        $args = self::parseArgs($argv);

        // Dependency setup (assuming standard project structure)
        $dbPath = __DIR__ . '/../../storage/events.sqlite';
        $store = new EventStore(new Db($dbPath));

        switch ($cmd) {
            case 'simulate':
                $user = $args['user'] ?? 'user_' . rand(100, 999);
                $company = $args['company'] ?? 'c_' . rand(100, 999);
                JourneySimulator::run($store, $user, $company);
                echo "✅ Simulated journey for $user\n";
                break;

            case 'timeline':
                $user = $args['user'] ?? null;
                if (!$user) { echo "❌ Missing --user\n"; return; }
                self::timeline($store, (string)$user);
                break;

            case 'validate':
                $user = $args['user'] ?? null;
                if (!$user) { echo "❌ Missing --user\n"; return; }
                self::validate($store, (string)$user);
                break;

            case 'replay':
                $user = $args['user'] ?? null;
                $limit = (int)($args['limit'] ?? 50);
                self::replay($store, $user ? (string)$user : null, $limit);
                break;

            case 'inspect':
                $user = $args['user'] ?? null;
                if (!$user) { echo "❌ User required\n"; return; }
                self::inspect($store, (string)$user);
                break;

            case 'funnel':
                $user = $args['user'] ?? null;
                if (!$user) { echo "❌ User required\n"; return; }
                self::funnel($store, (string)$user);
                break;

            case 'graph':
                $user = $args['user'] ?? null;
                if (!$user) { echo "❌ User required\n"; return; }
                self::graph($store, (string)$user);
                break;

            default:
                self::help();
                break;
        }
    }

    private static function timeline(EventStore $store, string $user): void {
        $rows = $store->listByUser($user, 200);
        if (!$rows) { echo "No events found for $user.\n"; return; }

        echo "Timeline for $user\n";
        foreach ($rows as $r) {
            $when = date('Y-m-d H:i:s', (int)(($r['ts_ms'] ?? 0) / 1000));
            $type = $r['type'];
            $name = $r['event_name'] ?? '(engage)';
            $status = $r['status'] ?? 'pending';
            echo "  [$when] $type  $name  (status=$status, request_id={$r['request_id']})\n";
        }
    }

    private static function inspect(EventStore $store, string $user): void {
        $events = $store->listByUser($user, 500);
        
        echo "\nUSER ANALYTICS REPORT\n";
        echo "======================\n";
        echo "User: $user\n";
        echo "Total Events: " . count($events) . "\n\n";

        echo "Timeline\n";
        echo "--------\n";

        $i = 1;
        foreach ($events as $e) {
            echo $i++ . ". " . ($e['event_name'] ?? $e['type']) . "\n";
        }
        echo "\nStatus: Analysis Complete\n";
    }

    private static function funnel(EventStore $store, string $user): void {
        $events = $store->listByUser($user, 500);
        $steps = ["account_created", "vendor_invite_sent", "order_completed"];
        $userEvents = array_column($events, 'event_name');

        echo "\nFUNNEL ANALYSIS\n";
        echo "----------------\n";

        foreach ($steps as $step) {
            $check = in_array($step, $userEvents) ? "✅" : "❌";
            echo sprintf("%-20s %s\n", $step, $check);
        }
    }

    private static function graph(EventStore $store, string $user): void {
        $events = $store->listByUser($user, 500);
        
        echo "\nUSER JOURNEY GRAPH\n";
        echo "------------------\n\n";

        $i = 0;
        foreach ($events as $e) {
            if ($i > 0) {
                echo "      ↓\n";
            }
            echo ($e['event_name'] ?? $e['type']) . "\n";
            $i++;
        }
        echo "\n";
    }

    private static function validate(EventStore $store, string $user): void {
        $rows = $store->listByUser($user, 200);
        $v = new Validator();
        $okCount = 0; $badCount = 0;

        foreach ($rows as $r) {
            $id = (int)$r['id'];
            $props = json_decode($r['props_json'], true) ?: [];
            
            $res = ($r['type'] === 'track') 
                ? $v->validateTrack((string)$r['event_name'], $props)
                : $v->validateEngage($props);

            if ($res['ok']) {
                $store->markStatus($id, 'validated', null);
                $okCount++;
            } else {
                $store->markStatus($id, 'invalid', $res['error']);
                $badCount++;
            }
        }

        echo "✅ Valid: $okCount | ❌ Invalid: $badCount\n";
        if ($badCount > 0) echo "Tip: run 'timeline' to see error details.\n";
    }

    private static function replay(EventStore $store, ?string $user, int $limit): void {
        if (!Config::bool('ANALYTICS_ENABLED', true)) {
            echo "❌ ANALYTICS_ENABLED=false\n";
            return;
        }

        $client = new MixpanelClient(
            token: Config::get('MIXPANEL_PROJECT_TOKEN'),
            host: Config::get('MIXPANEL_API_HOST', 'https://api.mixpanel.com')
        );

        $rows = $store->listReplayCandidates($user, $limit);
        if (!$rows) { echo "No replay candidates available.\n"; return; }

        $track = [];
        $engage = [];

        foreach ($rows as $r) {
            $props = json_decode($r['props_json'], true) ?: [];
            if ($r['type'] === 'track') {
                $track[] = [
                    'event' => $r['event_name'],
                    'properties' => array_merge(
                        ['token' => Config::get('MIXPANEL_PROJECT_TOKEN')],
                        ['distinct_id' => $r['distinct_id']],
                        $props
                    )
                ];
            } else {
                $engage[] = $props;
            }
        }

        $ok1 = empty($track) || $client->trackBatch($track);
        $ok2 = empty($engage) || $client->engageBatch($engage);

        foreach ($rows as $r) {
            $status = ($ok1 && $ok2) ? 'replayed' : 'failed';
            $error = ($ok1 && $ok2) ? null : 'replay_failed';
            $store->markStatus((int)$r['id'], $status, $error);
        }

        echo "Replay done: track=".count($track)." engage=".count($engage)." status=".(($ok1 && $ok2) ? "OK" : "FAILED")."\n";
    }

    private static function parseArgs(array $argv): array {
        $out = [];
        foreach ($argv as $a) {
            if (str_starts_with($a, '--')) {
                $p = explode('=', substr($a, 2), 2);
                $out[$p[0]] = $p[1] ?? true;
            }
        }
        return $out;
    }
}