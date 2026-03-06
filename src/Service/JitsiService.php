<?php

namespace App\Service;

/**
 * JitsiService — Génère des liens de visioconférence gratuits via Jitsi Meet.
 * Aucune API key requise. Le lien est unique grâce à un token aléatoire.
 * Exemple : https://meet.jit.si/webinar-dev-web-2025-a3f9c2b1
 */
class JitsiService
{
    private string $domain;

    public function __construct(string $domain = 'meet.jit.si')
    {
        $this->domain = $domain;
    }

    public function generateLink(string $title): string
    {
        $slug  = $this->slugify($title);
        $token = substr(bin2hex(random_bytes(4)), 0, 8);
        return "https://{$this->domain}/webinar-{$slug}-{$token}";
    }

    private function slugify(string $text): string
    {
        $converted = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
        $text = $converted !== false ? $converted : $text;
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        return trim($text, '-');
    }
}
