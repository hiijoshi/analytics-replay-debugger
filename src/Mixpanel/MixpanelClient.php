<?php
declare(strict_types=1);

final class MixpanelClient {
    public function __construct(
        private string $token,
        private string $host
    ) {}

    public function track(array $event): bool {
        return $this->post('/track', $event);
    }

    public function trackBatch(array $events): bool {
        if (!$events) return true;
        $ok = $this->post('/track', $events);
        if ($ok) return true;
        foreach ($events as $e) if (!$this->track($e)) return false;
        return true;
    }

    public function engage(array $profile): bool {
        return $this->post('/engage', $profile);
    }

    public function engageBatch(array $profiles): bool {
        if (!$profiles) return true;
        $ok = $this->post('/engage', $profiles);
        if ($ok) return true;
        foreach ($profiles as $p) if (!$this->engage($p)) return false;
        return true;
    }

    private function post(string $path, array $body): bool {
        $url = rtrim($this->host, '/') . $path;
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => json_encode($body),
            CURLOPT_TIMEOUT => 10,
        ]);
        $resp = curl_exec($ch);
        $err  = curl_error($ch);
        $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($err) {
            Logger::error('mixpanel_http_error', ['err'=>$err,'url'=>$url]);
            return false;
        }
        if ($code < 200 || $code >= 300) {
            Logger::error('mixpanel_http_non2xx', ['code'=>$code,'resp'=>$resp,'url'=>$url]);
            return false;
        }
        return true;
    }
}