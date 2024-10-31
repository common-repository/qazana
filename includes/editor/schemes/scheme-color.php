<?php
namespace Qazana;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Scheme_Color extends Scheme_Base {

	const COLOR_1 = '1';
	const COLOR_2 = '2';
	const COLOR_3 = '3';
	const COLOR_4 = '4';

	public static function get_type() {
		return 'color';
	}

	public function get_title() {
		return __( 'Colors', 'qazana' );
	}

	public function get_disabled_title() {
		return __( 'Color Palettes', 'qazana' );
	}

	public function get_scheme_titles() {
		return [
			self::COLOR_1 => __( 'Primary', 'qazana' ),
			self::COLOR_2 => __( 'Secondary', 'qazana' ),
			self::COLOR_3 => __( 'Text', 'qazana' ),
			self::COLOR_4 => __( 'Accent', 'qazana' ),
		];
	}

	public function get_default_scheme() {
		$colors = [
			self::COLOR_1 => '#6ec1e4',
			self::COLOR_2 => '#54595f',
			self::COLOR_3 => '#7a7a7a',
			self::COLOR_4 => '#61ce70',
		];

		return apply_filters( 'qazana/schemes/default_color_schemes', $colors );

	}

	public function print_template_content() {
		?>
		<div class="qazana-panel-scheme-content qazana-panel-box">
			<div class="qazana-panel-heading">
				<div class="qazana-panel-heading-title"><?php echo $this->_get_current_scheme_title(); ?></div>
			</div>
			<?php
			$description = static::get_description();

			if ( $description ) { ?>
				<div class="qazana-panel-scheme-description qazana-descriptor"><?php echo $description; ?></div>
			<?php } ?>
			<div class="qazana-panel-scheme-items qazana-panel-box-content"></div>
		</div>
		<div class="qazana-panel-scheme-colors-more-palettes qazana-panel-box">
			<div class="qazana-panel-heading">
				<div class="qazana-panel-heading-title"><?php _e( 'More Palettes', 'qazana' ); ?></div>
			</div>
			<div class="qazana-panel-box-content">
				<?php foreach ( $this->_get_system_schemes_to_print() as $scheme_name => $scheme ) : ?>
					<div class="qazana-panel-scheme-color-system-scheme" data-scheme-name="<?php echo $scheme_name; ?>">
						<div class="qazana-panel-scheme-color-system-items">
							<?php
							foreach ( $scheme['items'] as $color_value ) : ?>
								<div class="qazana-panel-scheme-color-system-item" style="background-color: <?php echo esc_attr( $color_value ); ?>;"></div>
							<?php endforeach; ?>
						</div>
						<div class="qazana-title"><?php echo $scheme['title']; ?></div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}

	protected function _init_system_schemes() {
		$schemes = [
			'joker' => [
				'title' => 'Joker',
				'items' => [
					self::COLOR_1 => '#202020',
					self::COLOR_2 => '#b7b4b4',
					self::COLOR_3 => '#707070',
					self::COLOR_4 => '#f6121c',
				],
			],
			'ocean' => [
				'title' => 'Ocean',
				'items' => [
					self::COLOR_1 => '#1569ae',
					self::COLOR_2 => '#b6c9db',
					self::COLOR_3 => '#545454',
					self::COLOR_4 => '#fdd247',
				],
			],
			'royal' => [
				'title' => 'Royal',
				'items' => [
					self::COLOR_1 => '#d5ba7f',
					self::COLOR_2 => '#902729',
					self::COLOR_3 => '#ae4848',
					self::COLOR_4 => '#302a8c',
				],
			],
			'violet' => [
				'title' => 'Violet',
				'items' => [
					self::COLOR_1 => '#747476',
					self::COLOR_2 => '#ebca41',
					self::COLOR_3 => '#6f1683',
					self::COLOR_4 => '#a43cbd',
				],
			],
			'sweet' => [
				'title' => 'Sweet',
				'items' => [
					self::COLOR_1 => '#6ccdd9',
					self::COLOR_2 => '#763572',
					self::COLOR_3 => '#919ca7',
					self::COLOR_4 => '#f12184',
				],
			],
			'urban' => [
				'title' => 'Urban',
				'items' => [
					self::COLOR_1 => '#db6159',
					self::COLOR_2 => '#3b3b3b',
					self::COLOR_3 => '#7a7979',
					self::COLOR_4 => '#2abf64',
				],
			],
			'earth' => [
				'title' => 'Earth',
				'items' => [
					self::COLOR_1 => '#882021',
					self::COLOR_2 => '#c48e4c',
					self::COLOR_3 => '#825e24',
					self::COLOR_4 => '#e8c12f',
				],
			],
			'river' => [
				'title' => 'River',
				'items' => [
					self::COLOR_1 => '#8dcfc8',
					self::COLOR_2 => '#565656',
					self::COLOR_3 => '#50656e',
					self::COLOR_4 => '#dc5049',
				],
			],
			'pastel' => [
				'title' => 'Pastel',
				'items' => [
					self::COLOR_1 => '#f27f6f',
					self::COLOR_2 => '#f4cd78',
					self::COLOR_3 => '#a5b3c1',
					self::COLOR_4 => '#aac9c3',
				],
			],
		];

		return apply_filters( 'qazana/schemes/system_color_schemes', $schemes );
	}

	protected function _get_system_schemes_to_print() {
		return $this->get_system_schemes();
	}

	protected function _get_current_scheme_title() {
		return __( 'Color Palette', 'qazana' );
	}
}
