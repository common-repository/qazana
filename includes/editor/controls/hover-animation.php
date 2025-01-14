<?php
namespace Qazana;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * An Hover Animation effect select box control.
 * @see Control_Hover_Animation::get_animations() fot all available animations.
 *
 * @param string $default     The selected effect key
 *                            Default empty
 *
 * @since 1.0.0
 */
class Control_Hover_Animation extends Base_Control {

	private static $_animations;

	public function get_type() {
		return 'hover_animation';
	}

	public static function get_animations() {
		if ( is_null( self::$_animations ) ) {
			self::$_animations = [
				'grow' => 'Grow',
				'shadow-bottom' => 'Shadow Bottom',
				'no-shadow' => 'Remove Shadow',
				'shrink' => 'Shrink',
				'pulse' => 'Pulse',
				'pulse-grow' => 'Pulse Grow',
				'pulse-shrink' => 'Pulse Shrink',
				'push' => 'Push',
				'pop' => 'Pop',
				'bounce-in' => 'Bounce In',
				'bounce-out' => 'Bounce Out',
				'rotate' => 'Rotate',
				'grow-rotate' => 'Grow Rotate',
				'float' => 'Float',
				'sink' => 'Sink',
				'bob' => 'Bob',
				'hang' => 'Hang',
				'skew' => 'Skew',
				'skew-forward' => 'Skew Forward',
				'skew-backward' => 'Skew Backward',
				'wobble-vertical' => 'Wobble Vertical',
				'wobble-horizontal' => 'Wobble Horizontal',
				'wobble-to-bottom-right' => 'Wobble To Bottom Right',
				'wobble-to-top-right' => 'Wobble To Top Right',
				'wobble-top' => 'Wobble Top',
				'wobble-bottom' => 'Wobble Bottom',
				'wobble-skew' => 'Wobble Skew',
				'buzz' => 'Buzz',
				'buzz-out' => 'Buzz Out',
			];
		}

		return self::$_animations;
	}

	public function content_template() {
		?>
		<div class="qazana-control-field">
			<label class="qazana-control-title">{{{ data.label }}}</label>
			<div class="qazana-control-input-wrapper">
				<select data-setting="{{ data.name }}">
					<option value=""><?php _e( 'None', 'qazana' ); ?></option>
					<?php foreach ( self::get_animations() as $animation_name => $animation_title ) : ?>
						<option value="<?php echo $animation_name; ?>"><?php echo $animation_title; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<# if ( data.description ) { #>
		<div class="qazana-control-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}

	protected function get_default_settings() {
		return [
			'label_block' => true,
		];
	}
}
