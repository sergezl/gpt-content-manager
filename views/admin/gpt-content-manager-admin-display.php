<div class="wrap">
    <h1>GPT Content Manager Settings</h1>
    <form method="post" action="options.php">
        <?php
        settings_fields('gcm_settings_group');
        do_settings_sections('gpt_content_manager');
        submit_button();
        ?>
    </form>
</div>