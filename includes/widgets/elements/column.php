<?php
namespace Qazana;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Element_Column extends Element_Base {

	protected static $_edit_tools;

	protected static function get_default_edit_tools() {
		$column_label = __( 'Column', 'qazana' );

		return [
			'duplicate' => [
				'title' => sprintf( __( 'Duplicate %s', 'qazana' ), $column_label ),
				'icon' => 'clone',
			],
			'add' => [
				'title' => sprintf( __( 'Add %s', 'qazana' ), $column_label ),
				'icon' => 'plus',
			],
			'remove' => [
				'title' => sprintf( __( 'Remove %s', 'qazana' ), $column_label ),
				'icon' => 'close',
			],
		];
	}

	public function get_name() {
		return 'column';
	}

	public function get_title() {
		return __( 'Column', 'qazana' );
	}

	public function get_icon() {
		return 'eicon-column';
	}

	protected function _register_controls() {
		// Section Layout.
		$this->start_controls_section(
			'layout',
			[
				'label' => __( 'Layout', 'qazana' ),
				'tab' => Controls_Manager::TAB_LAYOUT,
			]
		);

		$this->add_responsive_control(
			'_inline_size',
			[
				'label' => __( 'Column Width', 'qazana' ) . ' (%)',
				'type' => Controls_Manager::NUMBER,
				'min' => 10,
				'max' => 90,
				'device_args' => [
					Controls_Stack::RESPONSIVE_TABLET => [
						'nullable' => true,
						'max' => 100,
					],
					Controls_Stack::RESPONSIVE_MOBILE => [
						'nullable' => true,
						'max' => 100,
					],
				],
				'min_affected_device' => [
					Controls_Stack::RESPONSIVE_DESKTOP => Controls_Stack::RESPONSIVE_TABLET,
					Controls_Stack::RESPONSIVE_TABLET => Controls_Stack::RESPONSIVE_TABLET,
				],
				'selectors' => [
					'{{WRAPPER}}' => 'width: {{VALUE}}%',
				],
			]
		);

		$this->add_control(
			'content_position',
			[
				'label' => __( 'Content Position', 'qazana' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => __( 'Default', 'qazana' ),
					'top' => __( 'Top', 'qazana' ),
					'center' => __( 'Middle', 'qazana' ),
					'bottom' => __( 'Bottom', 'qazana' ),
				],
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'bottom' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}}.qazana-column .qazana-column-wrap' => 'align-items: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'space_between_widgets',
			[
				'label' => __( 'Widgets Space', 'qazana' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,
				'placeholder' => 20,
				'selectors' => [
					'{{WRAPPER}} > .qazana-column-wrap > .qazana-widget-wrap > .qazana-widget:not(:last-child)' => 'margin-bottom: {{VALUE}}px',//Need the full path for exclude the inner section
				],
			]
		);

		$possible_tags = [
			'div',
			'article',
			'aside',
			'nav',
		];

		$options = [
			'' => __( 'Default', 'qazana' ),
		] + array_combine( $possible_tags, $possible_tags );

		$this->add_control(
			'html_tag',
			[
				'label' => __( 'HTML Tag', 'qazana' ),
				'type' => Controls_Manager::SELECT,
				'options' => $options,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Background', 'qazana' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_background' );

		$this->start_controls_tab(
			'tab_background_normal',
			[
				'label' => __( 'Normal', 'qazana' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'selector' => '{{WRAPPER}} > .qazana-element-populated',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_background_hover',
			[
				'label' => __( 'Hover', 'qazana' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background_hover',
				'selector' => '{{WRAPPER}}:hover > .qazana-element-populated',
			]
		);

		$this->add_control(
			'background_hover_transition',
			[
				'label' => __( 'Transition Duration', 'qazana' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0.3,
				],
				'range' => [
					'px' => [
						'max' => 3,
						'step' => 0.1,
					],
				],
				'render_type' => 'ui',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Section Column Background Overlay.
		$this->start_controls_section(
			'section_background_overlay',
			[
				'label' => __( 'Background Overlay', 'qazana' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'background_background' => [ 'classic', 'gradient', 'video' ],
				],
			]
		);

		$this->start_controls_tabs( 'tabs_background_overlay' );

		$this->start_controls_tab(
			'tab_background_overlay_normal',
			[
				'label' => __( 'Normal', 'qazana' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background_overlay',
				'selector' => '{{WRAPPER}} > .qazana-element-populated >  .qazana-background-overlay',
				'condition' => [
					'background_background' => [ 'classic', 'gradient' ],
				],
			]
		);

		$this->add_control(
			'background_overlay_opacity',
			[
				'label' => __( 'Opacity (%)', 'qazana' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => .5,
				],
				'range' => [
					'px' => [
						'max' => 1,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} > .qazana-element-populated >  .qazana-background-overlay' => 'opacity: {{SIZE}};',
				],
				'condition' => [
					'background_overlay_background' => [ 'classic', 'gradient' ],
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_background_overlay_hover',
			[
				'label' => __( 'Hover', 'qazana' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background_overlay_hover',
				'selector' => '{{WRAPPER}}:hover > .qazana-element-populated >  .qazana-background-overlay',
			]
		);

		$this->add_control(
			'background_overlay_hover_opacity',
			[
				'label' => __( 'Opacity (%)', 'qazana' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => .5,
				],
				'range' => [
					'px' => [
						'max' => 1,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}}:hover > .qazana-element-populated >  .qazana-background-overlay' => 'opacity: {{SIZE}};',
				],
				'condition' => [
					'background_overlay_hover_background' => [ 'classic', 'gradient' ],
				],
			]
		);

		$this->add_control(
			'background_overlay_hover_transition',
			[
				'label' => __( 'Transition Duration', 'qazana' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0.3,
				],
				'range' => [
					'px' => [
						'max' => 3,
						'step' => 0.1,
					],
				],
				'render_type' => 'ui',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_border',
			[
				'label' => __( 'Border', 'qazana' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_border' );

		$this->start_controls_tab(
			'tab_border_normal',
			[
				'label' => __( 'Normal', 'qazana' ),
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'selector' => '{{WRAPPER}} > .qazana-element-populated',
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'qazana' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} > .qazana-element-populated, {{WRAPPER}} > .qazana-element-populated > .qazana-background-overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'selector' => '{{WRAPPER}} > .qazana-element-populated',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_border_hover',
			[
				'label' => __( 'Hover', 'qazana' ),
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border_hover',
				'selector' => '{{WRAPPER}}:hover > .qazana-element-populated',
			]
		);

		$this->add_control(
			'border_radius_hover',
			[
				'label' => __( 'Border Radius', 'qazana' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}}:hover > .qazana-element-populated, {{WRAPPER}}:hover > .qazana-element-populated > .qazana-background-overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow_hover',
				'selector' => '{{WRAPPER}}:hover > .qazana-element-populated',
			]
		);

		$this->add_control(
			'border_hover_transition',
			[
				'label' => __( 'Transition Duration', 'qazana' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0.3,
				],
				'range' => [
					'px' => [
						'max' => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} > .qazana-element-populated' => 'transition: background {{background_hover_transition.SIZE}}s, border {{SIZE}}s, border-radius {{SIZE}}s, box-shadow {{SIZE}}s',
					'{{WRAPPER}} > .qazana-element-populated > .qazana-background-overlay' => 'transition: background {{background_overlay_hover_transition.SIZE}}s, border-radius {{SIZE}}s, opacity {{background_overlay_hover_transition.SIZE}}s',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Section Typography.
		$this->start_controls_section(
			'section_typo',
			[
				'label' => __( 'Typography', 'qazana' ),
				'type' => Controls_Manager::SECTION,
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		if ( in_array( Scheme_Color::get_type(), Schemes_Manager::get_enabled_schemes() ) ) {
			$this->add_control(
				'colors_warning',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => __( 'Note: The following colors won\'t work if Global Colors are enabled.', 'qazana' ),
					'content_classes' => 'qazana-panel-alert qazana-panel-alert-warning',
				]
			);
		}

		$this->add_control(
			'heading_color',
			[
				'label' => __( 'Heading Color', 'qazana' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .qazana-element-populated .qazana-heading-title' => 'color: {{VALUE}};',
				],
				'separator' => 'none',
			]
		);

		$this->add_control(
			'color_text',
			[
				'label' => __( 'Text Color', 'qazana' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} > .qazana-element-populated' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'color_link',
			[
				'label' => __( 'Link Color', 'qazana' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .qazana-element-populated a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'color_link_hover',
			[
				'label' => __( 'Link Hover Color', 'qazana' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .qazana-element-populated a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'text_align',
			[
				'label' => __( 'Text Align', 'qazana' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'qazana' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'qazana' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'qazana' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} > .qazana-element-populated' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Section Advanced.
		$this->start_controls_section(
			'section_advanced',
			[
				'label' => __( 'Element Style', 'qazana' ),
				'type' => Controls_Manager::SECTION,
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		$this->add_responsive_control(
			'margin',
			[
				'label' => __( 'Margin', 'qazana' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} > .qazana-element-populated' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'padding',
			[
				'label' => __( 'Padding', 'qazana' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} > .qazana-element-populated' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Animations::get_type(),
			[
				'name' => '_animation',
			]
		);

		$this->add_control(
			'_element_id',
			[
				'label' => __( 'CSS ID', 'qazana' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'label_block' => true,
				'title' => __( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'qazana' ),
			]
		);

		$this->add_control(
			'css_classes',
			[
				'label' => __( 'CSS Classes', 'qazana' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'prefix_class' => '',
				'label_block' => true,
				'title' => __( 'Add your custom class WITHOUT the dot. e.g: my-class', 'qazana' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_responsive',
			[
				'label' => __( 'Responsive', 'qazana' ),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		$this->add_control(
			'screen_sm_width',
			[
				'label' => __( 'Column Width', 'qazana' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'10' => '10%',
					'11' => '11%',
					'12' => '12%',
					'14' => '14%',
					'16' => '16%',
					'20' => '20%',
					'25' => '25%',
					'30' => '30%',
					'33' => '33%',
					'40' => '40%',
					'50' => '50%',
					'60' => '60%',
					'66' => '66%',
					'70' => '70%',
					'75' => '75%',
					'80' => '80%',
					'83' => '83%',
					'90' => '90%',
					'100' => '100%',
				],
				'default' => '100',
				'condition' => [
					'screen_sm' => [ 'custom' ],
				],
				'prefix_class' => 'qazana-sm-',
			]
		);

		$this->end_controls_section();
	}

	protected function render_edit_tools() {
		?>
		<div class="qazana-element-overlay">
			<ul class="qazana-editor-element-settings qazana-editor-column-settings">
				<li class="qazana-editor-element-setting qazana-editor-element-trigger" title="<?php printf( __( 'Edit %s', 'qazana' ), __( 'Column', 'qazana' ) ); ?>"><i class="eicon-column"></i></li>
				<?php foreach ( self::get_edit_tools() as $edit_tool_name => $edit_tool ) : ?>
					<li class="qazana-editor-element-setting qazana-editor-element-<?php echo $edit_tool_name; ?>" title="<?php echo $edit_tool['title']; ?>">
						<span class="qazana-screen-only"><?php echo $edit_tool['title']; ?></span>
						<i class="eicon-<?php echo $edit_tool['icon']; ?>"></i>
					</li>
				<?php endforeach; ?>
			</ul>
			<div class="qazana-column-percents-tooltip"></div>
		</div>
		<?php
	}

	protected function _content_template() {
		?>
		<div class="qazana-column-wrap">
			<div class="qazana-background-overlay"></div>
			<div class="qazana-widget-wrap"></div>
		</div>
		<?php
	}

	public function before_render() {
		$settings = $this->get_settings();

		$has_background_overlay = in_array( $settings['background_overlay_background'], [ 'classic', 'gradient' ] ) ||
								  in_array( $settings['background_overlay_hover_background'], [ 'classic', 'gradient' ] );

		$column_wrap_class = 'qazana-column-wrap';
		if ( $this->get_children() ) {
			$column_wrap_class .= ' qazana-element-populated';
		}
		?>
		<<?php echo $this->get_html_tag() . ' ' . $this->get_render_attribute_string( '_wrapper' ); ?>>
			<div class="<?php echo $column_wrap_class; ?>">
			<?php if ( $has_background_overlay ) : ?>
				<div class="qazana-background-overlay"></div>
			<?php endif; ?>
		<div class="qazana-widget-wrap">
		<?php
	}

	public function after_render() {
		?>
				</div>
			</div>
		</<?php echo $this->get_html_tag(); ?>>
		<?php
	}

	public function _add_render_attributes() {
		parent::_add_render_attributes();

		$is_inner = $this->get_data( 'isInner' );

		$column_type = ! empty( $is_inner ) ? 'inner' : 'top';

		$settings = $this->get_settings();

		$this->add_render_attribute(
			'_wrapper', 'class', [
				'qazana-column',
				'qazana-col-' . $settings['_column_size'],
				'qazana-' . $column_type . '-column',
			]
		);

		$this->add_render_attribute( '_wrapper', 'data-element_type', $this->get_name() );

		foreach ( self::get_class_controls() as $control ) {
			if ( empty( $settings[ $control['name'] ] ) )
				continue;

			if ( ! $this->is_control_visible( $control ) )
				continue;

			$this->add_render_attribute( '_wrapper', 'class', $control['prefix_class'] . $settings[ $control['name'] ] );
		}

		if ( ! empty( $settings['_element_id'] ) ) {
			$this->add_render_attribute( '_wrapper', 'id', trim( $settings['_element_id'] ) );
		}

		if ( ! empty( $settings['hover_animation'] ) ) {
            $this->add_render_attribute( '_wrapper', 'class', 'qazana-hover-animation-' . $settings['hover_animation'] );
		}
	
	}

	protected function _get_default_child_type( array $element_data ) {
		if ( 'section' === $element_data['elType'] ) {
			return qazana()->elements_manager->get_element_types( 'section' );
		}

		return qazana()->widgets_manager->get_widget_types( $element_data['widgetType'] );
	}

	private function get_html_tag() {
		$html_tag = $this->get_settings( 'html_tag' );

		if ( empty( $html_tag ) ) {
			$html_tag = 'div';
		}

		return $html_tag;
	}
}
