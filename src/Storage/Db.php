<?php
declare(strict_types=1);

final class Db {
    private \PDO $pdo;

    public function __construct(string $path) {
        $this->pdo = new \PDO('sqlite:' . $path);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->migrate();
    }

    public function pdo(): \PDO { return $this->pdo; }

    private function migrate(): void {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS events (
              id INTEGER PRIMARY KEY AUTOINCREMENT,
              type TEXT NOT NULL,                -- track|engage
              event_name TEXT,                   -- for track
              distinct_id TEXT NOT NULL,
              request_id TEXT NOT NULL,
              ts_ms INTEGER NOT NULL,
              props_json TEXT NOT NULL,
              status TEXT NOT NULL DEFAULT 'captured', -- captured|validated|invalid|replayed|failed
              error TEXT
            );
        ");
        $this->pdo->exec("CREATE INDEX IF NOT EXISTS idx_events_distinct ON events(distinct_id);");
        $this->pdo->exec("CREATE INDEX IF NOT EXISTS idx_events_status ON events(status);");
        $this->pdo->exec("CREATE INDEX IF NOT EXISTS idx_events_req ON events(request_id);");
    }
}