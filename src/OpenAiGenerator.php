<?php
namespace SZ;

use SZ\Settings;
use SZ\AbstractGenerator;
use WP_Error;

class OpenAiGenerator extends AbstractGenerator {
    public function generate(string $prompt): string
    {
        $apiKey   = Settings::get('api_key');
        $model    = Settings::get('model', 'gpt-4o-mini');
        $temp     = (float) Settings::get('temperature', 0.7);
        $maxTok   = (int) Settings::get('max_tokens', 1000);
        $endpoint = Settings::get('custom_api_url')
            ?: 'https://api.openai.com/v1/chat/completions';

        if (empty($apiKey)) {
            throw new \Exception('OpenAI API key not configured.');
        }

        $body = [
            'model'       => $model,
            'messages'    => [['role' => 'user', 'content' => $prompt]],
            'temperature' => $temp,
            'max_tokens'  => $maxTok,
        ];

        $resp = wp_remote_post($endpoint, [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
            ],
            'body'    => wp_json_encode($body),
            'timeout' => 60,
        ]);

        if (is_wp_error($resp)) {
            throw new \Exception($resp->get_error_message());
        }

        $code = wp_remote_retrieve_response_code($resp);
        if ($code !== 200) {
            throw new \Exception("OpenAI error: HTTP {$code}");
        }

        $data = json_decode(wp_remote_retrieve_body($resp), true);
        if (empty($data['choices'][0]['message']['content'])) {
            throw new \Exception('Empty response from OpenAI.');
        }

        return trim($data['choices'][0]['message']['content']);
    }
}