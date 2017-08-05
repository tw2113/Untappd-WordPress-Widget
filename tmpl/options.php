<div class="wrap">
	<form method="post" action="options.php">
		<?php
		settings_fields( 'mb_untappd' );
		do_settings_sections( 'mb_untappd_do_options' );
		submit_button( esc_attr__( 'Save Changes', 'mb_untappd' ) ); ?>
	</form>
</div>
