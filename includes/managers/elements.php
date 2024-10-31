<?php
namespace Qazana;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Elements_Manager {
	/**
	 * @var Element_Base[]
	 */
	private $_element_types;

    /**
     * Globally acceible element instance
     * @var array
     */
    private $_element_instance;

    private $_categories;

    public function __construct() {
        add_action( 'after_setup_theme', [ $this, 'require_files' ] );
        add_action( 'wp_ajax_qazana_save_builder', [ $this, 'ajax_save_builder' ] );
    }

	/**
	 * @param array $element_data
	 *
	 * @param array $element_args
	 *
	 * @param Element_Base $element_type
	 *
	 * @return Element_Base
	 */
	public function create_element_instance( array $element_data, array $element_args = [], Element_Base $element_type = null ) {

		if ( null === $element_type ) {
			if ( 'widget' === $element_data['elType'] ) {
				$element_type = qazana()->widgets_manager->get_widget_types( $element_data['widgetType'] );
			} else {
				$element_type = $this->get_element_types( $element_data['elType'] );
			}
		}

		if ( ! $element_type ) {
			return null;
		}

		$args = array_merge( $element_type->get_default_args(), $element_args );

		$element_class = $element_type->get_class_name();

		try {
			$element = new $element_class( $element_data, $args );
		} catch ( \Exception $e ) {
			return null;
		}

		return $element;
	}

	/**
	 * Add element instance to cache
	 *
	 * @method get_element_instance
	 *
	 * @param  string 	$element_id  unique element id
	 * @return object 	element object class Element_Base
	 */
	public function add_element_instance( Element_Base $element ) {
		$this->_element_instance[ $element->get_id() ] = $element;

		return true;
	}

	/**
	 * Get element instance by id
	 *
	 * @method get_element_instance
	 *
	 * @param  string 	$element_id  unique element id
	 * @return object 	element object class Element_Base
	 */
	public function get_element_instance( string $element_id = null ) {
		if ( null !== $element_id ) {
			return $this->_element_instance[ $element_id ];
		}

		return $this->_element_instance;
	}

	public function get_categories() {
		if ( null === $this->_categories ) {
			$this->init_categories();
		}

		return $this->_categories;
	}

	public function add_category( $category_name, $category_properties, $offset = null ) {
		if ( null === $this->_categories ) {
			$this->init_categories();
		}

		if ( null === $offset ) {
			$this->_categories[ $category_name ] = $category_properties;
		}

		$this->_categories = array_slice( $this->_categories, 0, $offset, true ) + [ $category_name => $category_properties ] + array_slice( $this->_categories, $offset, null, true );
	}

	public function register_element_type( Element_Base $element ) {
		$this->_element_types[ $element->get_name() ] = $element;

		return true;
	}

	public function unregister_element_type( $name ) {
		if ( ! isset( $this->_element_types[ $name ] ) ) {
			return false;
		}

		unset( $this->_element_types[ $name ] );

		return true;
	}

	public function get_element_types( $element_name = null ) {
		if ( is_null( $this->_element_types ) ) {
			$this->_init_elements();
		}

		if ( null !== $element_name ) {
			return isset( $this->_element_types[ $element_name ] ) ? $this->_element_types[ $element_name ] : null;
		}

		return $this->_element_types;
	}

	public function get_element_types_config() {
		$config = [];

		foreach ( $this->get_element_types() as $element ) {
			$config[ $element->get_name() ] = $element->get_config();
		}

		return $config;
	}

	public function render_elements_content() {
		foreach ( $this->get_element_types() as $element_type ) {
			$element_type->print_template();
		}
	}

	public function ajax_save_builder() {
        if ( ! qazana()->editor->verify_request_nonce() ) {
			wp_send_json_error( new \WP_Error( 'token_expired' ) );
		}

		if ( empty( $_POST['post_id'] ) ) {
			wp_send_json_error( new \WP_Error( 'no_post_id' ) );
		}

		if ( ! User::is_current_user_can_edit( $_POST['post_id'] ) ) {
			wp_send_json_error( new \WP_Error( 'no_access' ) );
		}

		if ( isset( $_POST['status'] ) || in_array( $_POST['status'], [ DB::STATUS_PUBLISH, DB::STATUS_DRAFT, DB::STATUS_AUTOSAVE ] ) ) {
			$status = $_POST['status'];
		} else {
			$status = DB::STATUS_DRAFT;
		}

        if ( ! empty( $_POST['save_state'] ) && $_POST['save_state'] === 'delete' ) {
			$save_state = 'delete';
		} else {
			$save_state = 'save';
		}

		$posted = json_decode( stripslashes( html_entity_decode( $_POST['data'] ) ), true );

		qazana()->db->save_editor( $_POST['post_id'], $posted, $status, $save_state );

		$return_data = apply_filters( 'qazana/ajax_save_builder/return_data', [] );

		wp_send_json_success( $return_data );
	}

	private function _init_elements() {
		$this->_element_types = [];

		foreach ( [ 'section', 'column' ] as $element_name ) {
			$class_name = __NAMESPACE__ . '\Element_' . $element_name;

			$this->register_element_type( new $class_name() );
		}

		do_action( 'qazana/elements/elements_registered' );
	}

    private function init_categories() {
        $categories = [
            'basic' => [
                'title' => __( 'Basic', 'qazana' ),
                'icon' => 'eicon-font',
            ],
            'general-elements' => [
                'title' => __( 'General Elements', 'qazana' ),
                'icon' => 'eicon-font',
            ],
            'wordpress' => [
                'title' => __( 'WordPress', 'qazana' ),
                'icon' => 'eicon-wordpress',
            ],
        ];

        $this->_categories = apply_filters( 'qazana/elements/categories', $categories );

        return $this->_categories;

    }

    public function require_files() {

        $default_files = array(
            'base/element-base.php',
            'elements/column.php',
            'elements/section.php',
	     	'elements/repeater.php',
        );

        $files = apply_filters( 'qazana\elements\require_files', $default_files );

        if ( is_array( $files ) ) {
            foreach ( $files as $file ) {
                qazana()->widget_loader->locate_widget( $file, true );
            }
        }
    }
}
