<?php
namespace Qazana;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Video Widget
 */
class Widget_Video extends Widget_Base {

	/**
	 * Retrieve video widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'video';
	}

	/**
	 * Retrieve video widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Video', 'qazana' );
	}

	/**
	 * Retrieve video widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-youtube';
	}

	/**
	 * Register video widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_video',
			[
				'label' => __( 'Video', 'qazana' ),
			]
		);

		$this->add_control(
			'video_type',
			[
				'label' => __( 'Video Type', 'qazana' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'youtube',
				'options' => [
					'youtube' => __( 'YouTube', 'qazana' ),
					'vimeo' => __( 'Vimeo', 'qazana' ),
				],
			]
		);

		$this->add_control(
			'link',
			[
				'label' => __( 'Link', 'qazana' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter your YouTube link', 'qazana' ),
				'default' => 'https://www.youtube.com/watch?v=1SkAqeshvBA',
				'label_block' => true,
				'condition' => [
					'video_type' => 'youtube',
				],
			]
		);

		$this->add_control(
			'vimeo_link',
			[
				'label' => __( 'Vimeo Link', 'qazana' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter your Vimeo link', 'qazana' ),
				'default' => 'https://vimeo.com/170933924',
				'label_block' => true,
				'condition' => [
					'video_type' => 'vimeo',
				],
			]
		);

		$this->add_control(
			'hosted_link',
			[
				'label' => __( 'Link', 'qazana' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter your video link', 'qazana' ),
				'default' => '',
				'label_block' => true,
				'condition' => [
					'video_type' => 'hosted',
				],
			]
		);

		$this->add_control(
			'aspect_ratio',
			[
				'label' => __( 'Aspect Ratio', 'qazana' ),
				'type' => Controls_Manager::SELECT,
				'frontend_available' => true,
				'options' => [
					'16:9' => '16:9',
					'4:3' => '4:3',
					'3:2' => '3:2',
				],
				'default' => '16:9',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'heading_youtube',
			[
				'label' => __( 'Video Options', 'qazana' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		// YouTube
		$this->add_control(
			'yt_autoplay',
			[
				'label' => __( 'Autoplay', 'qazana' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'qazana' ),
				'label_on' => __( 'Yes', 'qazana' ),
				'condition' => [
					'video_type' => 'youtube',
				],
			]
		);

		$this->add_control(
			'yt_rel',
			[
				'label' => __( 'Suggested Videos', 'qazana' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'qazana' ),
				'label_on' => __( 'Show', 'qazana' ),
				'condition' => [
					'video_type' => 'youtube',
				],
			]
		);

		$this->add_control(
			'yt_controls',
			[
				'label' => __( 'Player Control', 'qazana' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'qazana' ),
				'label_on' => __( 'Show', 'qazana' ),
				'default' => 'yes',
				'condition' => [
					'video_type' => 'youtube',
				],
			]
		);

		$this->add_control(
			'yt_showinfo',
			[
				'label' => __( 'Player Title & Actions', 'qazana' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'qazana' ),
				'label_on' => __( 'Show', 'qazana' ),
				'default' => 'yes',
				'condition' => [
					'video_type' => 'youtube',
				],
			]
		);

		$this->add_control(
			'yt_mute',
			[
				'label' => __( 'Mute', 'qazana' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'video_type' => 'youtube',
				],
			]
		);

		$this->add_control(
			'yt_privacy',
			[
				'label' => __( 'Privacy Mode', 'qazana' ),
				'type' => Controls_Manager::SWITCHER,
				'description' => __( 'When you turn on privacy mode, YouTube won\'t store information about visitors on your website unless they play the video.', 'qazana' ),
				'condition' => [
					'video_type' => 'youtube',
				],
			]
		);

		// Vimeo.
		$this->add_control(
			'vimeo_autoplay',
			[
				'label' => __( 'Autoplay', 'qazana' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'video_type' => 'vimeo',
				],
			]
		);

		$this->add_control(
			'vimeo_loop',
			[
				'label' => __( 'Loop', 'qazana' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'video_type' => 'vimeo',
				],
			]
		);

		$this->add_control(
			'vimeo_title',
			[
				'label' => __( 'Intro Title', 'qazana' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'qazana' ),
				'label_on' => __( 'Show', 'qazana' ),
				'default' => 'yes',
				'condition' => [
					'video_type' => 'vimeo',
				],
			]
		);

		$this->add_control(
			'vimeo_portrait',
			[
				'label' => __( 'Intro Portrait', 'qazana' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'qazana' ),
				'label_on' => __( 'Show', 'qazana' ),
				'default' => 'yes',
				'condition' => [
					'video_type' => 'vimeo',
				],
			]
		);

		$this->add_control(
			'vimeo_byline',
			[
				'label' => __( 'Intro Byline', 'qazana' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'qazana' ),
				'label_on' => __( 'Show', 'qazana' ),
				'default' => 'yes',
				'condition' => [
					'video_type' => 'vimeo',
				],
			]
		);

		$this->add_control(
			'vimeo_color',
			[
				'label' => __( 'Controls Color', 'qazana' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'condition' => [
					'video_type' => 'vimeo',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_image_overlay',
			[
				'label' => __( 'Image Overlay', 'qazana' ),
			]
		);

		$this->add_control(
			'show_image_overlay',
			[
				'label' => __( 'Image Overlay', 'qazana' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'qazana' ),
				'label_on' => __( 'Show', 'qazana' ),
			]
		);

		$this->add_control(
			'image_overlay',
			[
				'label' => __( 'Image', 'qazana' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'show_image_overlay' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_play_icon',
			[
				'label' => __( 'Play Icon', 'qazana' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'qazana' ),
				'label_on' => __( 'Show', 'qazana' ),
				'condition' => [
					'show_image_overlay' => 'yes',
					'image_overlay[url]!' => '',
				],
			]
		);

		$this->add_control(
			'play_icon_type',
			[
				'label' => __( 'Icon Type', 'qazana' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'image' => [
						'title' => __( 'Image', 'qazana' ),
						'icon' => 'fa fa-picture-o',
					],
					'icon' => [
						'title' => __( 'Icon', 'qazana' ),
						'icon' => 'fa fa-star',
					],
				],
				'default' => 'icon',
				'condition' => [
					'show_image_overlay' => 'yes',
					'image_overlay[url]!' => '',
				],
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => __( 'Icon', 'qazana' ),
				'type' => Controls_Manager::ICON,
				'label_block' => true,
				'default' => 'fa fa-play-circle',
				'condition' => [
					'show_image_overlay' => 'yes',
					'image_overlay[url]!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'image_background_overlay',
				'selector' => '{{WRAPPER}} .qazana-image-background-overlay',
				'condition' => [
					'show_image_overlay' => 'yes',
				],
			]
		);

		$this->add_control(
			'image_background_overlay_opacity',
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
					'{{WRAPPER}} .qazana-image-background-overlay' => 'opacity: {{SIZE}};',
				],
				'condition' => [
					'image_background_overlay_background' => [ 'classic' ],
				],
			]
		);

		$this->end_controls_section();

		$this->_register_icon_controls();

	}

	protected function _register_icon_controls() {

		$this->start_controls_section(
			'section_style_icon',
			[
				'label' => __( 'Icon', 'qazana' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Icon Color', 'qazana' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .qazana-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .qazana-icon svg path ' => 'stroke: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_size',
			[
				'label' => __( 'Icon Size', 'qazana' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 100
				],
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .qazana-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_padding',
			[
				'label' => __( 'Icon Padding', 'qazana' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .qazana-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'view!' => 'default',
				],
			]
		);

		$this->add_control(
			'icon_margin',
			[
				'label' => __( 'Icon Margin', 'qazana' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .qazana-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'view!' => 'default',
				],
			]
		);

		$this->add_control(
			'rotate',
			[
				'label' => __( 'Icon Rotate', 'qazana' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
					'unit' => 'deg',
				],
				'selectors' => [
					'{{WRAPPER}} .qazana-icon' => 'transform: rotate({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_control(
			'border_width',
			[
				'label' => __( 'Border Width', 'qazana' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .qazana-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'view' => 'framed',
				],
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'qazana' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .qazana-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'view!' => 'default',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function get_render_icon() {

		$settings = $this->get_settings();

		$icon_tag = 'span';

		$this->add_render_attribute( 'icon', 'class', [ 'qazana-icon' ] );

		$icon_attributes = $this->get_render_attribute_string( 'icon' );

		if ( ! empty( $settings['icon'] ) ) {
			$this->add_render_attribute( 'i', 'class', $settings['icon'] );
		}

		if ( $settings['icon_type'] === 'image' ) {

			$filetype = wp_check_filetype( $settings['image']['url'] );

			if ( $filetype['ext'] === 'svg' ) {
				$this->add_render_attribute( 'image', 'class', 'svg-icon-holder svg-baseline' );
				$this->add_render_attribute( 'image', 'data-animation-speed', $settings['animation_speed'] );
				$this->add_render_attribute( 'image', 'data-size', $settings['icon_size']['size'] );
				$this->add_render_attribute( 'image', 'data-animation-delay', $settings['animation_delay'] );
				$this->add_render_attribute( 'image', 'data-color', $settings['icon_color'] );
				$this->add_render_attribute( 'image', 'data-icon', qazana_maybe_ssl_url( $settings['image']['url'] ) );
			}
		}

		$output = '<'. implode( ' ', [ $icon_tag, $icon_attributes ] ) .'>';

			if ( $settings['icon_type'] === 'image' ) {
				$output .= '<span '. $this->get_render_attribute_string( 'image' ) .'><img src="'. qazana_maybe_ssl_url( $settings['image']['url'] ) .'" /></span>';
			} else {
				$output .= '<i '. $this->get_render_attribute_string( 'i' ) .'></i>';
			}

		$output .= '</'. $icon_tag .'>';

		return $output;
	}
	
	public function render() {
		$settings = $this->get_settings();

		if ( 'hosted' !== $settings['video_type'] ) {

			$video_link = 'youtube' === $settings['video_type'] ? $settings['link'] : $settings['vimeo_link'];

			if ( empty( $video_link ) )
				return;

			$embed_params = $this->get_embed_params();

			$video_html = Embed::get_embed_html( $video_link, $embed_params );

		} else {

			$video_html = wp_video_shortcode( $this->get_hosted_params() );
		}

		if ( empty( $video_html ) ) {
			echo esc_url( $video_link );
			return;
		} 

		$this->add_render_attribute( 'video-wrapper', 'class', 'qazana-video-wrapper' );
		
		if ( ! $settings['lightbox'] ) {
			$this->add_render_attribute( 'video-wrapper', 'class', 'qazana-fit-aspect-ratio' );
		}

		$this->add_render_attribute( 'video-wrapper', 'class', 'qazana-open-inline' );

		if ( empty( $settings['aspect_ratio'] ) ) {
            $settings['aspect_ratio'] = '16:9';
        }

        $this->add_render_attribute( 'video-wrapper', 'data-video-aspect-ratio', $settings['aspect_ratio'] );
        $this->add_render_attribute( 'video-wrapper', 'class', 'qazana-aspect-ratio-' . str_replace( ':', '', $settings['aspect_ratio'] ) );
        
        $this->add_render_attribute( 'video-wrapper', 'class', 'qazana-button-align-' . $this->get_responsive_settings('button_align') );
        if ( ! empty( $settings['text'] ) ) {
            $this->add_render_attribute( 'video-wrapper', 'class', 'qazana-button-has-text' );
        } 
		
		?><div <?php $this->render_attribute_string( 'video-wrapper' ); ?>><?php 

		echo $video_html;

			if ( $this->has_image_overlay() ) : ?>
				<div class="qazana-custom-embed-image-overlay" style="background-image: url(<?php echo $settings['image_overlay']['url']; ?>);">
					<?php if ( $settings['show_play_icon'] ) : ?>
						<div class="qazana-custom-embed-play">
							<?php echo $this->get_render_icon(); ?>
						</div>
					<?php endif; ?>
					<div class="qazana-image-background-overlay"></div>
				</div>
			<?php endif; 
				
		?></div><?php

	}

	/**
	 * Retrieve video widget embed parameters.
	 *
	 * @access public
	 *
	 * @return array Video embed parameters.
	 */
	public function get_embed_params() {
		$settings = $this->get_settings();

		$params = [];

		if ( 'youtube' === $settings['video_type'] ) {
			$youtube_options = [ 'autoplay', 'rel', 'controls', 'showinfo', 'mute' ];

			foreach ( $youtube_options as $option ) {
				if ( 'autoplay' === $option && $this->has_image_overlay() ) {
					continue;
				}

				$value = ( 'yes' === $settings[ 'yt_' . $option ] ) ? '1' : '0';
				$params[ $option ] = $value;
			}

			$params['wmode'] = 'opaque';
		}

		if ( 'vimeo' === $settings['video_type'] ) {
			$vimeo_options = [ 'autoplay', 'loop', 'title', 'portrait', 'byline' ];

			foreach ( $vimeo_options as $option ) {
				if ( 'autoplay' === $option && $this->has_image_overlay() ) {
					continue;
				}

				$value = ( 'yes' === $settings[ 'vimeo_' . $option ] ) ? '1' : '0';
				$params[ $option ] = $value;
			}

			$params['color'] = str_replace( '#', '', $settings['vimeo_color'] );
		}

		return $params;
	}

	/**
	 * Retrieve video widget hosted parameters.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return array Video hosted parameters.
	 */
	protected function get_hosted_params() {
		$settings = $this->get_settings();

		$params = [];

		$params['src'] = $settings['hosted_link'];

		$hosted_options = [ 'autoplay', 'loop' ];

		foreach ( $hosted_options as $key => $option ) {
			$value = ( 'yes' === $settings[ 'hosted_' . $option ] ) ? '1' : '0';
			$params[ $option ] = $value;
		}

		if ( ! empty( $settings['hosted_width'] ) ) {
			$params['width'] = $settings['hosted_width'];
		}

		if ( ! empty( $settings['hosted_height'] ) ) {
			$params['height'] = $settings['hosted_height'];
		}
		return $params;
	}

	/**
	 * Whether the video widget has an overlay image or not.
	 *
	 * Used to determine whether an overlay image was set for the video.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return bool Whether an image overlay was set for the video.
	 */
	protected function has_image_overlay() {
		$settings = $this->get_settings();

		return ! empty( $settings['image_overlay']['url'] ) && 'yes' === $settings['show_image_overlay'];
	}

	protected function _content_template() {}
}
