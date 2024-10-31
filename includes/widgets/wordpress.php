<?php
namespace Qazana;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WordPress Widget
 */
class Widget_WordPress extends Widget_Base {

	/**
	 * WordPress widget name.
	 *
	 * @access private
	 *
	 * @var string
	 */
	private $_widget_name = null;

	/**
	 * WordPress widget instance.
	 *
	 * @access private
	 *
	 * @var \WP_Widget
	 */
	private $_widget_instance = null;

	/**
	 * Retrieve WordPress name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'wp-widget-' . $this->get_widget_instance()->id_base;
	}

	public function get_title() {
		return $this->get_widget_instance()->name;
	}

	public function get_categories() {
		$category = 'wordpress';
		return [ $category ];
	}

	public function get_icon() {
		return 'eicon-wordpress';
	}

	/**
	 * Whether the reload preview is required or not.
	 *
	 * Used to determine whether the reload preview is required.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return bool Whether the reload preview is required.
	 */
	public function is_reload_preview_required() {
		return true;
	}

	/**
	 * Retrieve WordPress widget form.
	 *
	 * Returns the WordPress widget form, to be used in Qazana.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget form.
	 */
	public function get_form() {
		$instance = $this->get_widget_instance();

		ob_start();
		echo '<div class="widget-inside media-widget-control"><div class="form wp-core-ui">';
		echo '<input type="hidden" class="id_base" value="' . esc_attr( $instance->id_base ) . '" />';
		echo '<input type="hidden" class="widget-id" value="widget-' . esc_attr( $this->get_id() ) . '" />';
		echo '<div class="widget-content">';
		$instance->form( $this->get_settings( 'wp' ) );
		echo '</div></div></div>';
		return ob_get_clean();
	}

	/**
	 * Retrieve WordPress widget instance.
	 *
	 * Returns an instance of WordPress widget, to be used in Qazana.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return \WP_Widget
	 */
	public function get_widget_instance() {
		if ( is_null( $this->_widget_instance ) ) {
			global $wp_widget_factory;

			if ( isset( $wp_widget_factory->widgets[ $this->_widget_name ] ) ) {
				$this->_widget_instance = $wp_widget_factory->widgets[ $this->_widget_name ];
				$this->_widget_instance->_set( 'REPLACE_TO_ID' );
			} elseif ( class_exists( $this->_widget_name ) ) {
				$this->_widget_instance = new $this->_widget_name();
				$this->_widget_instance->_set( 'REPLACE_TO_ID' );
			}
		}
		return $this->_widget_instance;
	}

	/**
	 * Retrieve WordPress widget parsed settings.
	 *
	 * Returns the WordPress widget settings, to be used in Qazana.
	 *
	 * @access protected
	 * @since 1.0.0
	 *
	 * @return \WP_Widget
	 */
	protected function _get_parsed_settings() {
		$settings = parent::_get_parsed_settings();

		if ( ! empty( $settings['wp'] ) ) {
			$settings['wp'] = $this->get_widget_instance()->update( $settings['wp'], [] );
		}

		return $settings;
	}

	/**
	 * Register WordPress widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		$this->add_control(
			'wp',
			[
				'label'   => __( 'Form', 'qazana' ),
				'type'    => Controls_Manager::WP_WIDGET,
				'widget'  => $this->get_name(),
				'id_base' => $this->get_widget_instance()->id_base,
			]
		);
	}

	/**
	 * Render WordPress widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access public
	 */

	public function render() {
		$empty_widget_args = [
			'widget_id' => $this->get_name(),
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '<h5>',
			'after_title' => '</h5>',
		];

		$empty_widget_args = apply_filters( 'qazana/widgets/wordpress/widget_args', $empty_widget_args, $this ); // WPCS: spelling ok.

		$this->get_widget_instance()->widget( $empty_widget_args, $this->get_settings( 'wp' ) );
	}

	/**
	 * Render WordPress widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function content_template() {}

	/**
	 * WordPress widget constructor.
	 *
	 * Used to run WordPress widget constructor.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $data Widget data. Default is an empty array.
	 * @param array $args Widget arguments. Default is null.
	 */
	public function __construct( $data = [], $args = null ) {
		$this->_widget_name = $args['widget_name'];
		parent::__construct( $data, $args );
	}

	/**
	 * Render WordPress widget as plain content.
	 *
	 * Override the default render behavior, don't render widget content.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $instance Widget instance. Default is empty array.
	 */
	public function render_plain_content( $instance = [] ) {}
}
