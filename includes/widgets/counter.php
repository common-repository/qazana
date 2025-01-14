<?php
namespace Qazana;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_Counter extends Widget_Base {

	public function get_name() {
		return 'counter';
	}

	public function get_title() {
		return __( 'Number Counter', 'qazana' );
	}

	public function get_icon() {
		return 'eicon-counter';
	}

	public function get_categories() {
		return [ 'general-elements' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_counter',
			[
				'label' => __( 'Counter', 'qazana' ),
			]
		);

		$this->add_control(
			'starting_number',
			[
				'label' => __( 'Starting Number', 'qazana' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
			]
		);

		$this->add_control(
			'ending_number',
			[
				'label' => __( 'Ending Number', 'qazana' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 100,
			]
		);

		$this->add_control(
			'prefix',
			[
				'label' => __( 'Number Prefix', 'qazana' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => 1,
			]
		);

		$this->add_control(
			'suffix',
			[
				'label' => __( 'Number Suffix', 'qazana' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Plus', 'qazana' ),
			]
		);

		$this->add_control(
			'duration',
			[
				'label' => __( 'Animation Duration', 'qazana' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 2000,
				'min' => 100,
				'step' => 100,
			]
		);

		$this->add_control(
			'thousand_separator',
			[
				'label' => __( 'Thousand Separator', 'qazana' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Show', 'qazana' ),
				'label_off' => __( 'Hide', 'qazana' ),
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'qazana' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'Cool Number', 'qazana' ),
				'placeholder' => __( 'Cool Number', 'qazana' ),
			]
		);

		$this->add_control(
			'view',
			[
				'label' => __( 'View', 'qazana' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_number',
			[
				'label' => __( 'Number', 'qazana' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'number_color',
			[
				'label' => __( 'Text Color', 'qazana' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .qazana-counter-number-wrapper' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography_number',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .qazana-counter-number-wrapper',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title',
			[
				'label' => __( 'Title', 'qazana' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Text Color', 'qazana' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				],
				'selectors' => [
					'{{WRAPPER}} .qazana-counter-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography_title',
				'scheme' => Scheme_Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .qazana-counter-title',
			]
		);

		$this->end_controls_section();
	}

	protected function _content_template() {
		?>
		<div class="qazana-counter">
			<div class="qazana-counter-number-wrapper">
				<span class="qazana-counter-number-prefix">{{{ settings.prefix }}}</span>
				<span class="qazana-counter-number" data-duration="{{ settings.duration }}" data-to-value="{{ settings.ending_number }}" data-delimiter="{{ settings.thousand_separator ? ',' : '' }}">{{{ settings.starting_number }}}</span>
				<span class="qazana-counter-number-suffix">{{{ settings.suffix }}}</span>
			</div>
			<# if ( settings.title ) {
				#><div class="qazana-counter-title">{{{ settings.title }}}</div><#
			} #>
		</div>
		<?php
	}

	public function render() {
		$settings = $this->get_settings();

		$this->add_render_attribute( 'counter', [
			'class' => 'qazana-counter-number',
			'data-duration' => $settings['duration'],
			'data-to-value' => $settings['ending_number'],
		] );

		if ( ! empty( $settings['thousand_separator'] ) ) {
			$this->add_render_attribute( 'counter', 'data-delimiter', ',' );
		}
		?>
		<div class="qazana-counter">
			<div class="qazana-counter-number-wrapper">
				<span class="qazana-counter-number-prefix"><?php echo $settings['prefix']; ?></span>
				<span <?php echo $this->get_render_attribute_string( 'counter' ); ?>><?php echo $settings['starting_number']; ?></span>
				<span class="qazana-counter-number-suffix"><?php echo $settings['suffix']; ?></span>
			</div>
			<?php if ( $settings['title'] ) : ?>
				<div class="qazana-counter-title"><?php echo $settings['title']; ?></div>
			<?php endif; ?>
		</div>
		<?php
	}
}
