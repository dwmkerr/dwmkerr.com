<?php
/**
 * The textarea customize control extends the WP_Customize_Control class.  This class allows 
 * developers to create textarea settings within the WordPress theme customizer.
  */

/**
 * Textarea customize control class.
 *
 * @since 1.4.0
 */
class Omega_Customize_Control_Textarea extends WP_Customize_Control {

	/**
	 * The type of customize control being rendered.
	 *
	 * @since 1.4.0
	 */
	public $type = 'textarea';

	/**
	 * Displays the textarea on the customize screen.
	 *
	 * @since 1.4.0
	 */
	public function render_content() { ?>
		<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<div class="customize-control-content">
				<textarea class="widefat" cols="45" rows="5" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
			</div>
		</label>
	<?php }
}

?>