<div class="wrap">
	<h2>
		<?php _e( 'Beans Simple Edits', $this->plugin_textdomain ); ?>
		<span style="float: right; font-size: 10px; color: #888;">
            <?php
                _e( 'Version ', $this->plugin_textdomain );
                echo esc_attr( $this->plugin_version );
            ?>
		</span>
	</h2>
    <?php
if( ! class_exists( 'LearningCurve\BeansSimpleShortcodes\Beans_Simple_Shortcodes' ) ) {
?>
        <div class="notice notice-success is-dismissible">
            <p>
	            <?php _e( 'Beans Simple Edits works well with the <a href="https://github.com/JeffCleverley/BeansSimpleShortcodes" target="_blank">Beans Simple Shortcodes Plugin</a> to easily display post and site information in the Beans Simple Edits content areas!', $this->plugin_textdomain ); ?>
            </p>
        </div>
    <?php
    }
?>
	<div><?php echo beans_options( 'beans_simple_edits_settings' ); ?></div>
</div>
