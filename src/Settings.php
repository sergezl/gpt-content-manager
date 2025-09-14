<?php
namespace SZ;

class Settings {

    private const OPTION_NAME = 'gcm_settings';

    public static function init(): void {
        add_action('admin_init', [self::class, 'register']);
    }

    public static function register(): void {
        register_setting(
            'gcm_settings_group',
            self::OPTION_NAME,
            ['sanitize_callback' => [self::class, 'sanitize']]
        );

        add_settings_section(
            'gcm_main_section',
            'API Configuration',
            function () {
                echo '<p>Configure your GPT integration below.</p>';
            },
            'gpt_content_manager'
        );

        $fields = self::get_fields();
        foreach ($fields as $field) {
            add_settings_field(
                $field['id'],
                $field['title'],
                fn() => self::render_field($field),
                'gpt_content_manager',
                'gcm_main_section'
            );
        }
    }

    private static function get_fields(): array {
        return [
            [
                'id'    => 'api_key',
                'title' => 'OpenAI API Key',
                'type'  => 'password',
                'desc'  => 'Enter your OpenAI API key.',
                'default' => '',
            ],
            [
                'id'    => 'model',
                'title' => 'Default Model',
                'type'  => 'text',
                'desc'  => 'E.g. gpt-4o-mini',
                'default' => 'gpt-4o-mini',
            ],
            [
                'id'    => 'temperature',
                'title' => 'Temperature',
                'type'  => 'number',
                'desc'  => '0.0 to 1.0',
                'default' => 0.7,
                'step'  => 0.1,
                'min'   => 0,
                'max'   => 1,
            ],
            [
                'id'    => 'max_tokens',
                'title' => 'Max Tokens',
                'type'  => 'number',
                'desc'  => 'Max tokens to generate',
                'default' => 1000,
                'min'   => 1,
            ],
            [
                'id'    => 'enable_caching',
                'title' => 'Enable Caching',
                'type'  => 'checkbox',
                'desc'  => 'Cache generated content',
                'default' => 1,
            ],
            [
                'id'    => 'custom_api_url',
                'title' => 'Custom API URL',
                'type'  => 'url',
                'desc'  => 'Optional custom endpoint',
                'default' => '',
            ],
            [
                'id'    => 'cache_expiration',
                'title' => 'Cache Expiration (seconds)',
                'type'  => 'number',
                'desc'  => 'Default 3600',
                'default' => 3600,
                'min'   => 0,
            ],
            [
                'id'      => 'system_prompt',
                'title'   => 'System Prompt',
                'type'    => 'textarea',
                'desc'    => 'Global system message that will be sent with every request.',
                'default' => 'You are a helpful assistant.',
            ]
        ];
    }

    private static function render_field(array $field): void {
        $options = get_option(self::OPTION_NAME, []);
        $value = $options[$field['id']] ?? $field['default'];
        $name = esc_attr(self::OPTION_NAME . '[' . $field['id'] . ']');

        switch ($field['type']) {
            case 'text':
            case 'url':
            case 'password':
                echo '<input type="' . $field['type'] . '" name="' . $name . '" value="' . esc_attr($value) . '" class="regular-text" />';
                break;
            case 'number':
                $step = $field['step'] ?? 1;
                $min = $field['min'] ?? '';
                $max = $field['max'] ?? '';
                echo '<input type="number" name="' . $name . '" value="' . esc_attr($value) . '" step="' . $step . '" min="' . $min . '" max="' . $max . '" />';
                break;
            case 'checkbox':
                echo '<input type="checkbox" name="' . $name . '" value="1" ' . checked(1, $value, false) . ' />';
                break;
            case 'textarea':
                printf(
                    '<textarea name="%1$s" class="large-text code" rows="5">%2$s</textarea>',
                    $name,
                    esc_textarea($value)
                );
                break;
        }

        if (!empty($field['desc'])) {
            echo '<p class="description">' . esc_html($field['desc']) . '</p>';
        }
    }

    public static function sanitize(array $input): array {
        $fields = self::get_fields();
        $sanitized = [];

        foreach ($fields as $field) {
            $id = $field['id'];
            if (!isset($input[$id])) {
                $sanitized[$id] = $field['type'] === 'checkbox' ? 0 : $field['default'];
                continue;
            }

            $raw = $input[$id];

            switch ($field['type']) {
                case 'text':
                case 'password':
                    $sanitized[$id] = sanitize_text_field($raw);
                    break;
                case 'url':
                    $sanitized[$id] = esc_url_raw($raw);
                    break;
                case 'number':
                    $sanitized[$id] = floatval($raw);
                    break;
                case 'checkbox':
                    $sanitized[$id] = (bool) $raw;
                    break;
                case 'textarea':
                    $sanitized[$id] = sanitize_textarea_field($raw);
                    break;
            }
        }

        return $sanitized;
    }

    public static function get(string $key, $default = null) {
        $options = get_option(self::OPTION_NAME, []);
        return $options[$key] ?? $default;
    }
}