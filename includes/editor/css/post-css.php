<?php
namespace Qazana;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Post_CSS_File extends CSS_File {

	const META_KEY = '_qazana_css';

	const FILE_PREFIX = 'post-';

	/*
	 * @var int
	 */
	private $post_id;

	/**
	 * Post_CSS_File constructor.
	 *
	 * @param int $post_id
	 */
	public function __construct( $post_id ) {
		$this->post_id = $post_id;

		parent::__construct();
	}

	public function get_name() {
		return 'post';
	}

	/**
	 * @return int
	 */
	public function get_post_id() {
		return $this->post_id;
	}

	/**
	 * @param Element_Base $element
	 *
	 * @return string
	 */
	public function get_element_unique_selector( Element_Base $element ) {
		return '.qazana-' . $this->post_id . ' .qazana-element' . $element->get_unique_selector();
	}

	/**
	 * @return array
	 */
	protected function load_meta() {
		return get_post_meta( $this->post_id, self::META_KEY, true );
	}

	/**
	 * @param string $meta
	 */
	protected function update_meta( $meta ) {
		update_post_meta( $this->post_id, '_qazana_css', $meta );
	}

	protected function render_css() {
		$data = qazana()->db->get_plain_editor( $this->post_id );

		if ( ! empty( $data ) ) {
			foreach ( $data as $element_data ) {
				$element = qazana()->elements_manager->create_element_instance( $element_data );

				if ( ! $element ) {
					continue;
				}

				$this->render_styles( $element );
			}
		}
	}

	public function enqueue() {
		if ( ! qazana()->db->is_built_with_qazana( $this->post_id ) ) {
			return;
		}

		parent::enqueue();
	}

	public function add_controls_stack_style_rules( Controls_Stack $controls_stack, array $controls, array $values, array $placeholders, array $replacements ) {
		parent::add_controls_stack_style_rules( $controls_stack, $controls, $values, $placeholders, $replacements );

		if ( $controls_stack instanceof Element_Base ) {
			foreach ( $controls_stack->get_children() as $child_element ) {
				$this->render_styles( $child_element );
			}
		}
	}

	protected function get_enqueue_dependencies() {
		return [ 'qazana-frontend' ];
	}

	protected function get_inline_dependency() {
		return 'qazana-frontend';
	}

	protected function get_file_handle_id() {
		return 'qazana-post-' . $this->post_id;
	}

	protected function get_file_name() {
		return self::FILE_PREFIX . $this->post_id;
	}

	/**
	 * @param Element_Base $element
	 */
	private function render_styles( Element_Base $element ) {

        do_action( 'qazana/element/before_parse_css', $this, $element );

		$element_settings = $element->get_settings();

		$this->add_controls_stack_style_rules( $element, $element->get_style_controls(), $element_settings,  [ '{{ID}}', '{{WRAPPER}}' ], [ $element->get_id(), $this->get_element_unique_selector( $element ) ] );

		do_action( 'qazana/element/parse_css', $this, $element );
	}
}
