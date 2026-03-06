<?php

namespace App\Service;

/**
 * WebinarStorageService — Remplace la base de données SQL pour ce prototype.
 *
 * Les webinaires sont stockés dans var/webinars/ sous forme de fichiers JSON.
 * Exemple : var/webinars/abc123.json
 */
class WebinarStorageService
{
    private string $storageDir;

    public function __construct(string $projectDir)
    {
        $this->storageDir = $projectDir . '/var/webinars';
        if (!is_dir($this->storageDir)) {
            mkdir($this->storageDir, 0755, true);
        }
    }

    public function save(array $webinar): void
    {
        $filePath = $this->storageDir . '/' . $webinar['id'] . '.json';
        $result = file_put_contents($filePath, json_encode($webinar, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        if ($result === false) {
            throw new \RuntimeException("Failed to write webinar file: {$filePath}");
        }
    }

    public function find(string $id): ?array
    {
        $filePath = $this->storageDir . '/' . $id . '.json';
        if (!file_exists($filePath)) {
            return null;
        }
        $content = file_get_contents($filePath);
        if ($content === false) {
            return null;
        }
        $data = json_decode($content, true);
        return is_array($data) ? $data : null;
    }

    public function findAll(): array
    {
        $webinars = [];
        foreach (glob($this->storageDir . '/*.json') as $file) {
            $content = file_get_contents($file);
            if ($content === false) {
                continue;
            }
            $data = json_decode($content, true);
            if (is_array($data)) {
                $webinars[] = $data;
            }
        }
        usort($webinars, fn($a, $b) => strcmp($b['startAt'], $a['startAt']));
        return $webinars;
    }

    public function addParticipant(string $webinarId, string $email, string $name): bool
    {
        $webinar = $this->find($webinarId);
        if (!$webinar) {
            return false;
        }
        if (count($webinar['participants']) >= $webinar['maxParticipants']) {
            return false;
        }
        foreach ($webinar['participants'] as $p) {
            if ($p['email'] === $email) {
                return false;
            }
        }
        $webinar['participants'][] = [
            'name'         => $name,
            'email'        => $email,
            'registeredAt' => (new \DateTime())->format('Y-m-d H:i:s'),
        ];
        $this->save($webinar);
        return true;
    }
}
