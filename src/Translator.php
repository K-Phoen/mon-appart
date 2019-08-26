<?php

declare(strict_types=1);

namespace App;

use Google\Cloud\Translate\TranslateClient;

class Translator
{
    private $translator;

    public function __construct(string $key)
    {
        $this->translator = new TranslateClient([
            'keyFile' => json_decode(base64_decode($key), true),
        ]);
    }

    public function translate(string $from, string $to, string $content): string
    {
        $result = $this->translator->translate($content, [
            'source' => $from,
            'target' => $to,
        ]);

        return $result['text'] ?? '';
    }
}
