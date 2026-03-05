<?php
declare(strict_types=1);

final class EventStore {
    public function __construct(private Db $db) {}

    public function captureTrack(string $eventName, string $distinctId, string $requestId, int $tsMs, array $props): int {
        $stmt = $this->db->pdo()->prepare("
            INSERT INTO events(type, event_name, distinct_id, request_id, ts_ms, props_json, status)
            VALUES('track', :event_name, :distinct_id, :request_id, :ts_ms, :props_json, 'captured')
        ");
        $stmt->execute([
            ':event_name' => $eventName,
            ':distinct_id' => $distinctId,
            ':request_id' => $requestId,
            ':ts_ms' => $tsMs,
            ':props_json' => json_encode($props, JSON_UNESCAPED_SLASHES),
        ]);
        return (int)$this->db->pdo()->lastInsertId();
    }

    public function captureEngage(string $distinctId, string $requestId, int $tsMs, array $profilePayload): int {
        $stmt = $this->db->pdo()->prepare("
            INSERT INTO events(type, event_name, distinct_id, request_id, ts_ms, props_json, status)
            VALUES('engage', NULL, :distinct_id, :request_id, :ts_ms, :props_json, 'captured')
        ");
        $stmt->execute([
            ':distinct_id' => $distinctId,
            ':request_id' => $requestId,
            ':ts_ms' => $tsMs,
            ':props_json' => json_encode($profilePayload, JSON_UNESCAPED_SLASHES),
        ]);
        return (int)$this->db->pdo()->lastInsertId();
    }

    public function listByUser(string $distinctId, int $limit = 200): array {
        $stmt = $this->db->pdo()->prepare("SELECT * FROM events WHERE distinct_id = :d ORDER BY ts_ms ASC LIMIT :l");
        $stmt->bindValue(':d', $distinctId, \PDO::PARAM_STR);
        $stmt->bindValue(':l', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function listReplayCandidates(?string $distinctId, int $limit = 100): array {
        if ($distinctId) {
            $stmt = $this->db->pdo()->prepare("
                SELECT * FROM events
                WHERE distinct_id = :d AND status IN ('captured','validated')
                ORDER BY ts_ms ASC LIMIT :l
            ");
            $stmt->bindValue(':d', $distinctId, \PDO::PARAM_STR);
        } else {
            $stmt = $this->db->pdo()->prepare("
                SELECT * FROM events
                WHERE status IN ('captured','validated')
                ORDER BY ts_ms ASC LIMIT :l
            ");
        }
        $stmt->bindValue(':l', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function markStatus(int $id, string $status, ?string $error = null): void {
        $stmt = $this->db->pdo()->prepare("UPDATE events SET status=:s, error=:e WHERE id=:id");
        $stmt->execute([':s'=>$status, ':e'=>$error, ':id'=>$id]);
    }
}