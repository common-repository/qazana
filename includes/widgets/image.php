<?php
namespace Qazana;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Image Widget
 */
class Widget_Image extends Widget_Base {

	/**
	 * Retrieve image widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'image';
	}

	/**
	 * Retrieve image widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Image', 'qazana' );
	}

	/**
	 * Retrieve image widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-insert-image';
	}

    /**
	 * Retrieve widget categories.
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
	 * Retrieve widget keywords.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'photo', 'image', 'thumbnail', 'media' ];
    }

	/**
	 * Register image widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_image',
			[
				'label' => __( 'Image', 'qazana' ),
			]
		);

		$this->add_control(
			'image',
			[
				'label' => __( 'Choose Image', 'qazana' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image', // Actually its `image_size`
				'label' => __( 'Image Size', 'qazana' ),
				'default' => 'large',
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'qazana' ),
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
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'caption',
			[
				'label' => __( 'Caption', 'qazana' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Enter your caption about the image', 'qazana' ),
				'title' => __( 'Input image caption here', 'qazana' ),
			]
		);

		$this->add_control(
			'link_to',
			[
				'label' => __( 'Link to', 'qazana' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => __( 'None', 'qazana' ),
					'file' => __( 'Media File', 'qazana' ),
					'custom' => __( 'Custom URL', 'qazana' ),
				],
			]
		);

		$this->add_control(
			'link',
			[
				'label' => __( 'Link to', 'qazana' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'http://your-link.com', 'qazana' ),
				'condition' => [
					'link_to' => 'custom',
				],
				'show_label' => false,
			]
		);

		$this->add_control(
			'open_lightbox',
			[
				'label' => __( 'Lightbox', 'qazana' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => __( 'Default', 'qazana' ),
					'yes' => __( 'Yes', 'qazana' ),
					'no' => __( 'No', 'qazana' ),
				],
				'condition' => [
					'link_to' => 'file',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_image',
			[
				'label' => __( 'Image', 'qazana' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'space',
			[
				'label' => __( 'Size (%)', 'qazana' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 100,
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .qazana-image img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'opacity',
			[
				'label' => __( 'Opacity (%)', 'qazana' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .qazana-image img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Hover_Animations::get_type(),
			[
				'name' => 'hover_animation',
				'fields_options' => [
					'type' => [
						'default' => '',
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'label' => __( 'Image Border', 'qazana' ),
				'selector' => '{{WRAPPER}} .qazana-image img',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'qazana' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .qazana-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_box_shadow',
				'exclude' => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .qazana-image img',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_caption',
			[
				'label' => __( 'Caption', 'qazana' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'caption_align',
			[
				'label' => __( 'Alignment', 'qazana' ),
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
					'justify' => [
						'title' => __( 'Justified', 'qazana' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .widget-image-caption' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'qazana' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .widget-image-caption' => 'color: {{VALUE}};',
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
				'name' => 'caption_typography',
				'selector' => '{{WRAPPER}} .widget-image-caption',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render image widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	public function render() {
		$settings = $this->get_settings();

		if ( empty( $settings['image']['url'] ) ) {
			return;
		}

		$has_caption = ! empty( $settings['caption'] );

		$this->add_render_attribute( 'wrapper', 'class', 'qazana-image' );

		if ( ! empty( $settings['shape'] ) ) {
			$this->add_render_attribute( 'wrapper', 'class', 'qazana-image-shape-' . $settings['shape'] );
		}

		$link = $this->get_link_url( $settings );

		if ( $link ) {
			$this->add_render_attribute( 'link', [
				'href' => $link['url'],
				'class' => 'qazana-clickable',
				'data-qazana-open-lightbox' => $settings['open_lightbox'],
			] );

			if ( ! empty( $link['is_external'] ) ) {
				$this->add_render_attribute( 'link', 'target', '_blank' );
			}

			if ( ! empty( $link['nofollow'] ) ) {
				$this->add_render_attribute( 'link', 'rel', 'nofollow' );
			}
		} ?>
		<div <?php $this->render_attribute_string( 'wrapper' ); ?>>
		<?php if ( $has_caption ) : ?>
			<figure class="wp-caption">
		<?php endif;

		if ( $link ) : ?>
			<a <?php $this->render_attribute_string( 'link' ); ?>>
		<?php endif;

		echo Group_Control_Image_Size::get_attachment_image_html( $settings );

		if ( $link ) : ?>
				</a>
		<?php endif;

		if ( $has_caption ) : ?>
			<figcaption class="widget-image-caption wp-caption-text"><?php echo $settings['caption']; ?></figcaption>
		<?php endif;

		if ( $has_caption ) : ?>
			</figure>
		<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render image widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _content_template() {
		?>
		<# if ( '' !== settings.image.url ) {
			var image = {
				id: settings.image.id,
				url: settings.image.url,
				size: settings.image_size,
				dimension: settings.image_custom_dimension,
				model: view.getEditModel()
			};

			var image_url = qazana.imagesManager.getImageUrl( image );

			if ( ! image_url ) {
				return;
			}

			var link_url;

			if ( 'custom' === settings.link_to ) {
				link_url = settings.link.url;
			}

			if ( 'file' === settings.link_to ) {
				link_url = settings.image.url;
			}

			#><div class="qazana-image{{ settings.shape ? ' qazana-image-shape-' + settings.shape : '' }}"><#
			var imgClass = '',
				hasCaption = '' !== settings.caption;

			if ( '' !== settings.hover_animation_type ) {
				imgClass = 'qazana-hover-animation-' + settings.hover_animation_type;
			}

			if ( hasCaption ) {
				#><figure class="wp-caption"><#
			}

			if ( link_url ) {
			    #><a class="qazana-clickable" data-qazana-open-lightbox="{{ settings.open_lightbox }}" href="{{ link_url }}"><#
			}
            
                #><img src="{{ image_url }}" class="{{ imgClass }}" /><#

			if ( link_url ) {
			    #></a><#
			}

			if ( hasCaption ) {
				#><figcaption class="widget-image-caption wp-caption-text">{{{ settings.caption }}}</figcaption><#
			}

			if ( hasCaption ) {
				#></figure><#
			}

			#></div><#
		} #>
		<?php
	}

	/**
	 * Retrieve image widget link URL.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @param object $instance
	 *
	 * @return array|string|false An array/string containing the link URL, or false if no link.
	 */
	private function get_link_url( $instance ) {
		if ( 'none' === $instance['link_to'] ) {
			return false;
		}

		if ( 'custom' === $instance['link_to'] ) {
			if ( empty( $instance['link']['url'] ) ) {
				return false;
			}
			return $instance['link'];
		}

		return [
			'url' => $instance['image']['url'],
		];
	}
}
