<?php
namespace Qazana;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

/**
 * Qazana controls manager class.
 *
 * Qazana controls manager handler class is responsible for registering and
 * initializing all the supported controls, both regular controls and the group
 * controls.
 *
 * @since 1.0.0
 */
class Controls_Manager {

	const TAB_CONTENT = 'content';
	const TAB_STYLE = 'style';
	const TAB_ADVANCED = 'advanced';
	const TAB_RESPONSIVE = 'responsive';
	const TAB_LAYOUT = 'layout';
	const TAB_SETTINGS = 'settings';

	const TEXT = 'text';
	const NUMBER = 'number';
	const TEXTAREA = 'textarea';
	const SELECT = 'select';
	const CHECKBOX = 'checkbox';
	const RADIO = 'radio';
	const SWITCHER = 'switcher';

	const HIDDEN = 'hidden';
	const HEADING = 'heading';
    const RAW_HTML = 'raw_html';
    const POPOVER_TOGGLE = 'popover_toggle';
	const SECTION = 'section';
	const TAB = 'tab';
	const TABS = 'tabs';
	const DIVIDER = 'divider';

	const COLOR = 'color';
	const MEDIA = 'media';
	const SLIDER = 'slider';
	const DIMENSIONS = 'dimensions';
	const CHOOSE = 'choose';
	const WYSIWYG = 'wysiwyg';
	const CODE = 'code';
	const FONT = 'font';
	const IMAGE_DIMENSIONS = 'image_dimensions';

	const WP_WIDGET = 'wp_widget';

	const URL = 'url';
	const REPEATER = 'repeater';
	const ICON = 'icon';
	const GALLERY = 'gallery';
	const STRUCTURE = 'structure';
	const SELECT2 = 'select2';
	const DATE_TIME = 'date_time';
	const BOX_SHADOW = 'box_shadow';
	const TEXT_SHADOW = 'text_shadow';
	const ANIMATION_IN = 'animation_in';
	const ANIMATION_OUT = 'animation_out';
	const ORDER = 'order';

	/**
	 * Controls.
	 *
	 * Holds the list of all the controls. Default is `null`.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var Base_Control[]
	 */
	private $controls = null;

	/**
	 * Control groups.
	 *
	 * Holds the list of all the control groups. Default is an empty array.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var Group_Control_Base[]
	 */
	private $control_groups = [];

	/**
	 * Control stacks.
	 *
	 * Holds the list of all the control stacks. Default is an empty array.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var array
	 */
	private $stacks = [];

	/**
	 * Tabs.
	 *
	 * Holds the list of all the tabs.
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 *
	 * @var array
	 */
	private static $tabs;

	/**
	 * Init tabs.
	 *
	 * Initialize control tabs.
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 */
	private static function init_tabs() {
		self::$tabs = [
			self::TAB_CONTENT => __( 'Content', 'qazana' ),
			self::TAB_STYLE => __( 'Style', 'qazana' ),
			self::TAB_ADVANCED => __( 'Advanced', 'qazana' ),
			self::TAB_RESPONSIVE => __( 'Responsive', 'qazana' ),
			self::TAB_LAYOUT => __( 'Layout', 'qazana' ),
			self::TAB_SETTINGS => __( 'Settings', 'qazana' ),
		];

		self::$tabs = Utils::apply_filters_deprecated( 'qazana/controls/get_available_tabs_controls', [ self::$tabs ], '1.0.0', '`' . __CLASS__ . '::add_tab( $tab_name, $tab_title )`' );
	}

	/**
	 * Get tabs.
	 *
	 * Retrieve the tabs of the current control.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return array Control tabs.
	 */
	public static function get_tabs() {
		if ( ! self::$tabs ) {
			self::init_tabs();
		}

		return self::$tabs;
	}

	/**
	 * Get tab.
	 *
	 * Retrieve the tab of the current control.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return array Control tabs.
	 */
	public static function add_tab( $tab_name, $tab_title ) {
		if ( ! self::$tabs ) {
			self::init_tabs();
		}

		if ( isset( self::$tabs[ $tab_name ] ) ) {
			return;
		}

		self::$tabs[ $tab_name ] = $tab_title;
	}

	public function include_controls() {
		
		require( qazana()->includes_dir  . 'editor/controls/base.php' );
		require( qazana()->includes_dir  . 'editor/controls/base-data.php' );
		require( qazana()->includes_dir  . 'editor/controls/base-ui.php' );
		require( qazana()->includes_dir  . 'editor/controls/base-multiple.php' );
		require( qazana()->includes_dir  . 'editor/controls/base-units.php' );

		// Group Controls
		require( qazana()->includes_dir  . 'editor/interfaces/group-control.php' );
		require( qazana()->includes_dir  . 'editor/controls/groups/base.php' );

		require( qazana()->includes_dir  . 'editor/controls/groups/background.php' );
		require( qazana()->includes_dir  . 'editor/controls/groups/border.php' );
		require( qazana()->includes_dir  . 'editor/controls/groups/typography.php' );
		require( qazana()->includes_dir  . 'editor/controls/groups/image-size.php' );
		require( qazana()->includes_dir  . 'editor/controls/groups/box-shadow.php' );
		require( qazana()->includes_dir  . 'editor/controls/groups/animations.php' );
		require( qazana()->includes_dir  . 'editor/controls/groups/hover-animations.php' );
		require( qazana()->includes_dir  . 'editor/controls/groups/icon.php' );
		require( qazana()->includes_dir  . 'editor/controls/groups/text-shadow.php' );

	}

	/**
	 * Register controls.
	 *
	 * This method creates a list of all the supported controls by requiring the
	 * control files and initializing each one of them.
	 *
	 * The list of supported controls includes the regular controls and the group
	 * controls.
	 *
	 * External developers can register new controls by hooking to the
	 * `qazana/controls/controls_registered` action.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register_controls() {

		$this->controls = [];

		$available_controls = [
			self::TEXT,
			self::NUMBER,
			self::TEXTAREA,
			self::SELECT,
			self::CHECKBOX,
			self::RADIO,
			self::SWITCHER,

			self::HIDDEN,
			self::HEADING,
            self::RAW_HTML,
            self::POPOVER_TOGGLE,
			self::SECTION,
			self::TAB,
			self::TABS,
			self::DIVIDER,

			self::COLOR,
			self::MEDIA,
			self::SLIDER,
			self::DIMENSIONS,
			self::CHOOSE,
			self::WYSIWYG,
			self::CODE,
			self::FONT,
			self::IMAGE_DIMENSIONS,

			self::WP_WIDGET,

			self::URL,
			self::REPEATER,
			self::ICON,
			self::GALLERY,
			self::STRUCTURE,
			self::SELECT2,
			self::DATE_TIME,
			self::BOX_SHADOW,
			self::TEXT_SHADOW,
			self::ANIMATION_IN,
			self::ANIMATION_OUT,
			self::ORDER,
		];
	
		foreach ( $available_controls as $control_id ) {
	
			$control_filename = str_replace( '_', '-', $control_id );
			$control_filename =  qazana()->includes_dir . "editor/controls/{$control_filename}.php";
			require( $control_filename );

			$class_name = __NAMESPACE__ . '\Control_' . ucwords( $control_id );

			$this->register_control( $control_id, new $class_name() );
		}

		// Group Controls
		$this->control_groups['background'] 		= new Group_Control_Background();
		$this->control_groups['border']     		= new Group_Control_Border();
		$this->control_groups['typography'] 		= new Group_Control_Typography();
		$this->control_groups['image-size'] 		= new Group_Control_Image_Size();
		$this->control_groups['box-shadow'] 		= new Group_Control_Box_Shadow();
		$this->control_groups['text-shadow'] 		= new Group_Control_Text_Shadow();
		$this->control_groups['animations'] 		= new Group_Control_Animations();
		$this->control_groups['hover-animations'] 	= new Group_Control_Hover_Animations();
		$this->control_groups['icon'] 		 		= new Group_Control_Icon();

		do_action( 'qazana/controls/controls_registered', $this );
	}

	/**
	 * @since 1.0.0
	 *
	 * @param $control_id
	 * @param Base_Control $control_instance
	 */
	public function register_control( $control_id, Base_Control $control_instance ) {
		$this->controls[ $control_id ] = $control_instance;
	}

	/**
	 * @param $control_id
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function unregister_control( $control_id ) {
		if ( ! isset( $this->controls[ $control_id ] ) ) {
			return false;
		}

		unset( $this->controls[ $control_id ] );

		return true;
	}

	/**
	 * @since 1.0.0
	 * @return Base_Control[]
	 */
	public function get_controls() {
		if ( null === $this->controls ) {
			$this->register_controls();
		}

		return $this->controls;
	}

	/**
	 * @since 1.0.0
	 * @param $control_id
	 *
	 * @return bool|\Qazana\Base_Control
	 */
	public function get_control( $control_id ) {
		$controls = $this->get_controls();

		return isset( $controls[ $control_id ] ) ? $controls[ $control_id ] : false;
	}

	/**
	 * @since 1.0.0
	 * @return array
	 */
	public function get_controls_data() {
		$controls_data = [];

		foreach ( $this->get_controls() as $name => $control ) {
			$controls_data[ $name ] = $control->get_settings();

			if ( $control instanceof Base_Data_Control ) {
				$controls_data[ $name ]['default_value'] = $control->get_default_value();
			}
		}

		return $controls_data;
	}

	/**
	 * @since 1.0.0
	 * @return void
	 */
	public function render_controls() {
		foreach ( $this->get_controls() as $control ) {
			$control->print_template();
		}
	}

	/**
	 * Get control groups.
	 *
	 * Retrieve a specific group for a given ID, or a list of all the control
	 * groups.
	 *
	 * If the given group ID is wrong, it will return `null`. When the ID valid,
	 * it will return the group control instance. When no ID was given, it will
	 * return all the control groups.
	 *
	 * @since 1.0.10
	 * @access public
	 *
	 * @param string $id Optional. Group ID. Default is null.
	 *
	 * @return null|Group_Control_Base|Group_Control_Base[]
	 */
	public function get_control_groups( $id = null ) {
		if ( $id ) {
			return isset( $this->control_groups[ $id ] ) ? $this->control_groups[ $id ] : null;
		}

		return $this->control_groups;
	}

	/**
	 * Add group control.
	 *
	 * This method adds a new group control to the control groups list. It adds
	 * any given group control to any given group control instance.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string               $id       Group control ID.
	 * @param Group_Control_Base[] $instance Group control instance, usually the
	 *                                       current instance.
	 *
	 * @return Group_Control_Base[] Group control instance.
	 */
	public function add_group_control( $id, $instance ) {
		$this->control_groups[ $id ] = $instance;

		return $instance;
	}

	/**
	 * Enqueue control scripts and styles.
	 *
	 * Used to register and enqueue custom scripts and styles used by the control.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function enqueue_control_scripts() {
		foreach ( $this->get_controls() as $control ) {
			$control->enqueue();
		}
	}

	/**
	 * Open new stack.
	 *
	 * This method adds a new stack to the control stacks list. It adds any
	 * given stack to the current control instance.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param Controls_Stack $element Element stack.
	 */
	public function open_stack( Controls_Stack $element ) {
		$stack_id = $element->get_unique_name();

		$this->stacks[ $stack_id ] = [
			'tabs' => [],
			'controls' => [],
		];
	}

	/**
	 * Add control to stack.
	 *
	 * This method adds a new control to the stack.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param Controls_Stack $element      Element stack.
	 * @param string         $control_id   Control ID.
	 * @param array          $control_data Control data.
	 * @param array          $options      Optional. Control aditional options.
	 *                                     Default is an empty array.
	 *
	 * @return bool True if control added, False otherwise.
	 */
	public function add_control_to_stack( Controls_Stack $element, $control_id, $control_data, $options = [] ) {
		
		$stack_id = $element->get_unique_name();
		
		if ( ! is_array( $options ) ) {
			_deprecated_argument( __FUNCTION__, '1.7.0', 'Use `[ \'overwrite\' => ' . var_export( $options, true ) . ' ]` instead. Added in ' . $control_id . ' in the ' . $stack_id . ' element.' );

			$options = [
				'overwrite' => $options,
			];
		}

		$default_options = [
			'overwrite' => false,
			'index' => null,
		];

		$options = array_merge( $default_options, $options );

		$default_args = [
			'type' => self::TEXT,
			'tab' => self::TAB_CONTENT,
		];

		$control_data['name'] = $control_id;

		$control_data = array_merge( $default_args, $control_data );

		$control_type_instance = $this->get_control( $control_data['type'] );

		if ( ! $control_type_instance ) {
			_doing_it_wrong( __CLASS__ . '::' . __FUNCTION__, 'Control type `' . $control_data['type'] . '` not found`. Added in `' . $stack_id . '` element.', '1.0.0' );
			return false;
		}

		if ( $control_type_instance instanceof Base_Data_Control ) {
			$control_default_value = $control_type_instance->get_default_value();

			if ( is_array( $control_default_value ) ) {
				$control_data['default'] = ! empty( $control_data['default'] ) ? array_merge( $control_default_value, $control_data['default'] ) : $control_default_value;
			} else {
				$control_data['default'] = ! empty( $control_data['default'] ) ? $control_data['default'] : $control_default_value;
			}
		}

		if ( ! $options['overwrite'] && isset( $this->stacks[ $stack_id ]['controls'][ $control_id ] ) ) {
			_doing_it_wrong( __CLASS__ . '::' . __FUNCTION__, 'Cannot redeclare control with same name. ' . $control_id . ' in the ' . $stack_id . ' element', '1.0.0' );

			return false;
		}

		$tabs = self::get_tabs();

		if ( ! isset( $tabs[ $control_data['tab'] ] ) ) {
			$control_data['tab'] = $default_args['tab'];
		}

		$this->stacks[ $stack_id ]['tabs'][ $control_data['tab'] ] = $tabs[ $control_data['tab'] ];

		$this->stacks[ $stack_id ]['controls'][ $control_id ] = $control_data;

		if ( null !== $options['index'] ) {
			$controls = $this->stacks[ $stack_id ]['controls'];

			$controls_keys = array_keys( $controls );

			array_splice( $controls_keys, $options['index'], 0, $control_id );

			$this->stacks[ $stack_id ]['controls'] = array_merge( array_flip( $controls_keys ), $controls );
		}

		return true;
	}

	/**
	 * Remove control from stack.
	 *
	 * This method removes a control a the stack.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $stack_id   Stack ID.
	 * @param string $control_id The ID of the control to remove.
	 *
	 * @return bool True if the stack was removed, False otherwise.
	 */
	public function remove_control_from_stack( $stack_id, $control_id ) {
		if ( is_array( $control_id ) ) {
			foreach ( $control_id as $id ) {
				$this->remove_control_from_stack( $stack_id, $id );
			}

			return true;
		}

		if ( empty( $this->stacks[ $stack_id ]['controls'][ $control_id ] ) ) {
			return new \WP_Error( 'Cannot remove non-existent control.' );
		}

		unset( $this->stacks[ $stack_id ]['controls'][ $control_id ] );

		return true;
	}

	/**
	 * Get control from stack.
	 *
	 * Retrieve a specific control for a given a specific stack.
	 *
	 * If the given control does not exist in the stack, or the stack does not
	 * exist, it will return `WP_Error`. Otherwise, it will retrieve the control
	 * from the stack.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $stack_id   Stack ID.
	 * @param string $control_id Control ID.
	 *
	 * @return array|\WP_Error The control, or an error.
	 */
	public function get_control_from_stack( $stack_id, $control_id ) {
		if ( empty( $this->stacks[ $stack_id ]['controls'][ $control_id ] ) ) {
			return new \WP_Error( 'Cannot get a non-existent control.' );
		}

		return $this->stacks[ $stack_id ]['controls'][ $control_id ];
	}

	/**
	 * Update control in stack.
	 *
	 * This method updates the control data for a given stack.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param Controls_Stack $element      Element stack.
	 * @param string         $control_id   Control ID.
	 * @param array          $control_data Control data.
	 * @param array          $options      Optional. Control aditional options.
	 *                                     Default is an empty array.
	 *
	 * @return bool True if control updated, False otherwise.
	 */
	public function update_control_in_stack( Controls_Stack $element, $control_id, $control_data, array $options = [] ) {
		$old_control_data = $this->get_control_from_stack( $element->get_unique_name(), $control_id );

		if ( is_wp_error( $old_control_data ) ) {
			return false;
		}

		if ( ! empty( $options['recursive'] ) ) {
			$control_data = array_replace_recursive( $old_control_data, $control_data );
		} else {
			$control_data = array_merge( $old_control_data, $control_data );
		}

		return $this->add_control_to_stack( $element, $control_id, $control_data, [ 'overwrite' => true ] );
	}

	/**
	 * Get stacks.
	 *
	 * Retrieve a specific stack for the list of stacks.
	 *
	 * If the given stack is wrong, it will return `null`. When the stack valid,
	 * it will return the the specific stack. When no stack was given, it will
	 * return all the stacks.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @param string $stack_id Optional. stack ID. Default is null.
	 *
	 * @return null|array A list of stacks.
	 */
	public function get_stacks( $stack_id = null ) {
		if ( $stack_id ) {
			if ( isset( $this->stacks[ $stack_id ] ) ) {
				return $this->stacks[ $stack_id ];
			}

			return null;
		}

		return $this->stacks;
	}

	/**
	 * Get element stack.
	 *
	 * Retrieve a specific stack for the list of stacks from the current instance.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param Controls_Stack $controls_stack  Controls stack.
	 *
	 * @return null|array Stack data if it exist, `null` otherwise.
	 */
	public function get_element_stack( Controls_Stack $controls_stack ) {
		$stack_id = $controls_stack->get_unique_name();

		if ( ! isset( $this->stacks[ $stack_id ] ) ) {
			return null;
		}

		return $this->stacks[ $stack_id ];
	}

	/**
	 * Controls_Manager constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {		
		$this->include_controls();
		$this->register_controls();
	}
}
