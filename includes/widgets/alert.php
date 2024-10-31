<?php
namespace Qazana;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Alert Widget
 */
class Widget_Alert extends Widget_Base {

	/**
	 * Retrieve alert widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'alert';
	}

	/**
	 * Retrieve alert widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Alert', 'qazana' );
	}

	/**
	 * Retrieve alert widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-alert';
	}

	/**
	 * Retrieve the list of categories the alert widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'general-elements' ];
	}

	/**
	 * Register alert widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_alert',
			[
				'label' => __( 'Alert', 'qazana' ),
			]
		);

		$this->add_control(
			'alert_type',
			[
				'label' => __( 'Type', 'qazana' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'info',
				'options' => [
					'info' => __( 'Info', 'qazana' ),
					'success' => __( 'Success', 'qazana' ),
					'warning' => __( 'Warning', 'qazana' ),
					'danger' => __( 'Danger', 'qazana' ),
				],
			]
		);

		$this->add_control(
			'alert_title',
			[
				'label' => __( 'Title & Description', 'qazana' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Your Title', 'qazana' ),
				'default' => __( 'This is Alert', 'qazana' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'alert_description',
			[
				'label' => __( 'Content', 'qazana' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => __( 'Your Description', 'qazana' ),
				'default' => __( 'I am description. Click edit button to change this text.', 'qazana' ),
				'separator' => 'none',
				'show_label' => false,
			]
		);

		$this->add_control(
			'show_dismiss',
			[
				'label' => __( 'Dismiss Button', 'qazana' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'show',
				'options' => [
					'show' => __( 'Show', 'qazana' ),
					'hide' => __( 'Hide', 'qazana' ),
				],
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
			'section_type',
			[
				'label' => __( 'Alert Type', 'qazana' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'background',
			[
				'label' => __( 'Background Color', 'qazana' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .qazana-alert' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'border_color',
			[
				'label' => __( 'Border Color', 'qazana' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .qazana-alert' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'border_left-width',
			[
				'label' => __( 'Left Border Width', 'qazana' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .qazana-alert' => 'border-left-width: {{SIZE}}{{UNIT}};',
				],
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
				'selectors' => [
					'{{WRAPPER}} .qazana-alert-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'alert_title',
				'selector' => '{{WRAPPER}} .qazana-alert-title',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_description',
			[
				'label' => __( 'Description', 'qazana' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => __( 'Text Color', 'qazana' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .qazana-alert-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'alert_description',
				'selector' => '{{WRAPPER}} .qazana-alert-description',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render alert widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	public function render() {
		$settings = $this->get_settings();

		if ( empty( $settings['alert_title'] ) ) {
			return;
		}

		if ( ! empty( $settings['alert_type'] ) ) {
			$this->add_render_attribute( 'wrapper', 'class', 'qazana-alert qazana-alert-' . $settings['alert_type'] );
		}

		$this->add_render_attribute( 'wrapper', 'role', 'alert' );

		$this->add_render_attribute( 'alert_title', 'class', 'qazana-alert-title' );

		$this->add_inline_editing_attributes( 'alert_title', 'none' );
		?>
		<div <?php $this->render_attribute_string( 'wrapper' ); ?>>
			<span <?php $this->render_attribute_string( 'alert_title' ); ?>><?php echo $settings['alert_title']; ?></span>
			<?php if ( ! empty( $settings['alert_description'] ) ) {
				$this->add_render_attribute( 'alert_description', 'class', 'qazana-alert-description' );

				$this->add_inline_editing_attributes( 'alert_description' );
				?>
				<span <?php $this->render_attribute_string( 'alert_description' ); ?>><?php echo $settings['alert_description']; ?></span>
			<?php }
			if ( 'show' === $settings['show_dismiss'] ) { ?>
				<button type="button" class="qazana-alert-dismiss">X</button>
			<?php } ?>
		</div>
		<?php
	}

	/**
	 * Render alert widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _content_template() {
		?>
		<# if ( settings.alert_title ) {
			view.addRenderAttribute( {
				alert_title: { class: 'qazana-alert-title' },
				alert_description: { class: 'qazana-alert-description' }
			} );

			view.addInlineEditingAttributes( 'alert_title', 'none' );
			view.addInlineEditingAttributes( 'alert_description' );
			#>
			<div class="qazana-alert qazana-alert-{{ settings.alert_type }}" role="alert">
				<span {{{ view.getRenderAttributeString( 'alert_title' ) }}}>{{{ settings.alert_title }}}</span>
				<span {{{ view.getRenderAttributeString( 'alert_description' ) }}}>{{{ settings.alert_description }}}</span>
				<# if ( 'show' === settings.show_dismiss ) { #>
					<button type="button" class="qazana-alert-dismiss">X</button>
				<# } #>
			</div>
		<# } #>
		<?php
	}
}
