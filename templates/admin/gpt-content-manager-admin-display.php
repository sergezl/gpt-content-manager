<div class="wrap <?php echo esc_attr( $plugin_slug ); ?>">

    <h2><?php echo esc_html( $plugin_name ); ?></h2>

    <form method="POST" action="options.php">
		<?php
		settings_fields( $plugin_slug );
		do_settings_sections( $plugin_slug );
		submit_button();
		?>
    </form>

</div>
