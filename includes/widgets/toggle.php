<?php
namespace Qazana;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_Toggle extends Widget_Base {

	public function get_name() {
		return 'toggle';
	}

	public function get_title() {
		return __( 'Toggle', 'qazana' );
	}

	public function get_icon() {
		return 'eicon-toggle';
	}

	public function get_categories() {
		return [ 'general-elements' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_toggle',
			[
				'label' => __( 'Toggle', 'qazana' ),
			]
		);

		$this->add_control(
			'tabs',
			[
				'label' => __( 'Toggle Items', 'qazana' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'tab_title' => __( 'Toggle #1', 'qazana' ),
						'tab_content' => __( 'I am item content. Click the edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'qazana' ),
					],
					[
						'tab_title' => __( 'Toggle #2', 'qazana' ),
						'tab_content' => __( 'I am item content. Click the edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'qazana' ),
					],
				],
				'fields' => [
					[
						'name' => 'tab_title',
						'label' => __( 'Title & Content', 'qazana' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'default' => __( 'Toggle Title' , 'qazana' ),
					],
					[
						'name' => 'tab_content',
						'label' => __( 'Content', 'qazana' ),
						'type' => Controls_Manager::WYSIWYG,
						'default' => __( 'Toggle Content', 'qazana' ),
						'show_label' => false,
					],
				],
				'title_field' => '{{{ tab_title }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_toggle_style',
			[
				'label' => __( 'Toggle', 'qazana' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'border_width',
			[
				'label' => __( 'Border Width', 'qazana' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .qazana-toggle .qazana-toggle-title' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .qazana-toggle .qazana-toggle-content' => 'border-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'border_color',
			[
				'label' => __( 'Border Color', 'qazana' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .qazana-toggle .qazana-toggle-content' => 'border-bottom-color: {{VALUE}};',
					'{{WRAPPER}} .qazana-toggle .qazana-toggle-title' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'heading_title',
			[
				'label' => __( 'Title', 'qazana' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_background',
			[
				'label' => __( 'Background', 'qazana' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .qazana-toggle .qazana-toggle-title' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'qazana' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .qazana-toggle .qazana-toggle-title' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
			]
		);

		$this->add_control(
			'tab_active_color',
			[
				'label' => __( 'Active Color', 'qazana' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .qazana-toggle .qazana-toggle-title.active' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .qazana-toggle .qazana-toggle-title',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'heading_content',
			[
				'label' => __( 'Content', 'qazana' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_background_color',
			[
				'label' => __( 'Background', 'qazana' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .qazana-toggle .qazana-toggle-content' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'content_color',
			[
				'label' => __( 'Color', 'qazana' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .qazana-toggle .qazana-toggle-content' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .qazana-toggle .qazana-toggle-content',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->end_controls_section();
	}

	public function render() {
		$tabs = $this->get_settings( 'tabs' );
		?>
		<div class="qazana-toggle">
			<?php $counter = 1; ?>
			<?php foreach ( $tabs as $item ) : ?>
				<div class="qazana-toggle-title" data-tab="<?php echo $counter; ?>">
					<span class="qazana-toggle-icon">
						<i class="fa"></i>
					</span>
					<?php echo $item['tab_title']; ?>
				</div>
				<div class="qazana-toggle-content qazana-clearfix" data-tab="<?php echo $counter; ?>"><?php echo $this->parse_text_editor( $item['tab_content'] ); ?></div>
			<?php
				$counter++;
			endforeach;
			?>
		</div>
		<?php
	}

	protected function _content_template() {
		?>
		<div class="qazana-toggle">
			<#
			if ( settings.tabs ) {
				var counter = 1;
				_.each(settings.tabs, function( item ) { #>
					<div class="qazana-toggle-title" data-tab="{{ counter }}">
						<span class="qazana-toggle-icon">
							<i class="fa"></i>
						</span>
						{{{ item.tab_title }}}
					</div>
					<div class="qazana-toggle-content qazana-clearfix" data-tab="{{ counter }}">{{{ item.tab_content }}}</div>
				<#
					counter++;
				} );
			} #>
		</div>
		<?php
	}
}
