<?php
namespace Qazana;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Skin Base.
 *
 * An abstract class to register new skins for Qazana widgets. Skins allows
 * you to add new templates, set custom controls and more.
 *
 * To register new skins for your widget use the `add_skin()` method inside the
 * widget's `_register_skins()` method.
 *
 * @since 1.0.0
 * @abstract
 */
abstract class Skin_Base {

	/**
	 * Parent widget.
	 *
	 * Holds the parent widget of the skin. Default value is null, no parent widget.
	 *
	 * @access protected
	 *
	 * @var Widget_Base|null
	 */
	protected $_parent = null;

	/**
	 * Skin base constructor.
	 *
	 * Initializing the skin base class by setting parent widget and registering
	 * controls actions.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param Widget_Base $parent
	 */
	public function __construct( Widget_Base $parent ) {
		$this->_parent = $parent;

		$this->_register_controls_actions();
	}

	/**
	 * Retrieve skin ID.
	 *
	 * @since 1.0.0
	 * @access public
	 * @abstract
	 */
	abstract public function get_id();

	/**
	 * Retrieve skin title.
	 *
	 * @since 1.0.0
	 * @access public
	 * @abstract
	 */
	abstract public function get_title();

	/**
	 * Render skin.
	 *
	 * Generates the final HTML on the frontend.
	 *
	 * @since 1.0.0
	 * @access public
	 * @abstract
	 */
	abstract public function render();

	/**
	 * Render skin output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @deprecated
	 */
	public function _content_template() {}

	/**
	 * Register skin controls actions.
	 *
	 * Run on init and used to register new skins to be injected to the widget.
	 * This method is used to register new actions that specify the locaion of
	 * the skin in the widget.
	 *
	 * Example usage:
	 * `add_action( 'qazana/element/{widget_id}/{section_id}/before_section_end', [ $this, 'register_controls' ] );`
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls_actions() {}

	/**
	 * Retrieve skin control ID.
	 *
	 * Used to get the skin control ID. Note that skin controls have special
	 * prefix to destiguish them from regular controls, and from controls in
	 * other skins.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @param string $control_base_id Control base ID.
	 *
	 * @return string Control ID.
	 */
	protected function get_control_id( $control_base_id ) {
		$skin_id = str_replace( '-', '_', $this->get_id() );
		return $skin_id . '_' . $control_base_id;
	}

	/**
	 * Retrieve skin settings.
	 *
	 * Get all the skin settings or, when requested, a specific setting.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @param string $control_base_id Control base ID.
	 *
	 * @return Widget_Base Widget instance.
	 */
	public function get_instance_value( $control_base_id ) {
		$control_id = $this->get_control_id( $control_base_id );
		return $this->get_parent()->get_settings( $control_id );
    	}

    	/**
	 * Retrieve resposive skin settings.
	 *
	 * Get all the skin settings or, when requested, a specific setting.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @param string $control_base_id Control base ID.
	 *
	 * @return Widget_Base Widget instance.
	 */
    	public function get_responsive_instance_value( $control_base_id ) {
		$control_id = $this->get_control_id( $control_base_id );
		return $this->get_parent()->get_responsive_settings( $control_id );
	}

	/**
	 * Start skin controls section.
	 *
	 * Used to add a new section of controls to the skin.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $id   Section ID.
	 * @param array  $args Section arguments.
	 */
	public function start_controls_section( $id, $args ) {
		$args['condition']['_skin'] = $this->get_id();
		$this->get_parent()->start_controls_section( $this->get_control_id( $id ), $args );
	}

	/**
	 * End skin controls section.
	 *
	 * Used to close an existing open skin controls section.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function end_controls_section() {
		$this->get_parent()->end_controls_section();
	}

	/**
	 * Add new skin control.
	 *
	 * Register a single control to the allow the user to set/update skin data.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $id   Control ID.
	 * @param array  $args Control arguments.
	 *
	 * @return bool True if skin added, False otherwise.
	 */
	public function add_control( $id, $args ) {
		$args['condition']['_skin'] = $this->get_id();
		return $this->get_parent()->add_control( $this->get_control_id( $id ), $args );
	}

	/**
	 * Update skin control.
	 *
	 * Change the value of an existing skin control.
	 *
	 * @since 1.2.0
	 * @since 1.0.0 New `$options` parameter added.
	 *
	 * @access public
	 *
	 * @param string $id      Control ID.
	 * @param array  $args    Control arguments. Only the new fields you want to update.
	 * @param array  $options Optional. Some additional options.
	 */
	public function update_control( $id, $args, array $options = [] ) {
		$args['condition']['_skin'] = $this->get_id();
		$this->get_parent()->update_control( $this->get_control_id( $id ), $args, $options );
	}

	/**
	 * Remove skin control.
	 *
	 * Unregister an existing skin control.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $id Control ID.
	 */
	public function remove_control( $id ) {
		$this->get_parent()->remove_control( $this->get_control_id( $id ) );
	} 

	/**
	 * Add new responsive skin control.
	 *
	 * Register a set of controls to allow editing based on user screen size.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $id   Responsive control ID.
	 * @param array  $args Responsive control arguments.
	 */
	public function add_responsive_control( $id, $args ) {
		$args['condition']['_skin'] = $this->get_id();
		$this->get_parent()->add_responsive_control( $this->get_control_id( $id ), $args );
	}

	/**
	 * Update responsive skin control.
	 *
	 * Change the value of an existing responsive skin control.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $id   Responsive control ID.
	 * @param array  $args Responsive control arguments.
	 */
	public function update_responsive_control( $id, $args ) {
		$this->get_parent()->update_responsive_control( $this->get_control_id( $id ), $args );
	}

	/**
	 * Remove responsive skin control.
	 *
	 * Unregister an existing skin responsive control.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $id Responsive control ID.
	 */
	public function remove_responsive_control( $id ) {
		$this->get_parent()->remove_responsive_control( $this->get_control_id( $id ) );
	}

	/**
	 * Start skin controls tab.
	 *
	 * Used to add a new tab inside a group of tabs.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $id   Control ID.
	 * @param array  $args Control arguments.
	 */
	public function start_controls_tab( $id, $args ) {
		$args['condition']['_skin'] = $this->get_id();
		$this->get_parent()->start_controls_tab( $this->get_control_id( $id ), $args );
	}

	/**
	 * End skin controls tab.
	 *
	 * Used to close an existing open controls tab.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function end_controls_tab() {
		$this->get_parent()->end_controls_tab();
	}

	/**
	 * Start skin controls tabs.
	 *
	 * Used to add a new set of tabs inside a section.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $id Control ID.
	 */
	public function start_controls_tabs( $id ) {
		$args['condition']['_skin'] = $this->get_id();
		$this->get_parent()->start_controls_tabs( $this->get_control_id( $id ) );
	}

	/**
	 * End skin controls tabs.
	 *
	 * Used to close an existing open controls tabs.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function end_controls_tabs() {
		$this->get_parent()->end_controls_tabs();
	}

	/**
	 * Add new group control.
	 *
	 * Register a set of related controls grouped together as a single unified
	 * control.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $group_name Group control name.
	 * @param array  $args       Group control arguments. Default is an empty array.
	 */
	final public function add_group_control( $group_name, $args = [] ) {
		$args['name'] = $this->get_control_id( $args['name'] );
		$args['condition']['_skin'] = $this->get_id();
		$this->get_parent()->add_group_control( $group_name, $args );
	}

    /**
	 * Add render attributes.
	 *
	 * Used to add several attributes to current widget `_wrapper` element.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	public function add_render_attributes () {
		$this->_add_render_attributes();
	}

    /**
	 * Add render attributes helper.
	 *
	 * Used to add several attributes to current widget `_wrapper` element.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _add_render_attributes () { }

	public function add_render_attribute( $element, $key = null, $value = null, $overwrite = false ) {
		return $this->get_parent()->add_render_attribute( $element, $key, $value, $overwrite );
	}

	public function get_render_attribute_string( $element ) {
		return $this->get_parent()->get_render_attribute_string( $element );
	}

    /**
	 * Before widget rendering.
	 *
	 * Used to add stuff before the widget `_wrapper` element.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function before_render() {}

    /**
	 * After widget rendering.
	 *
	 * Used to add stuff after the widget `_wrapper` element.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function after_render() {}

	public function is_bool( $value ) {
		return $this->get_parent()->is_bool( $value );
	}

    /**
	 * Add elements scripts
	 *
	 * @since 1.0.0
	 */
	public function add_element_dependencies() { }

	public function add_frontend_script( $value ) {
		return $this->get_parent()->add_frontend_script( $value );
	}

	public function add_frontend_style( $value ) {
		return $this->get_parent()->add_frontend_style( $value );
	}

	/**
	 * Set parent widget.
	 *
	 * Used to define the parent widget of the skin.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param Widget_Base $parent Parent widget.
	 */
	public function set_parent( $parent ) {
		$this->_parent = $parent;
	}

    /**
	 * Get parent widget.
	 *
	 * Used to retrieve the parent widget of the skin.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param Widget_Base $parent Parent widget.
	 */
	public function get_parent() {
		return $this->_parent;
    }

    /**
	 * Start injection.
	 *
	 * Used to inject controls and sections to a specific position in the stack.
	 *
	 * When you use this method, all the registered controls and sections will
	 * be injected to a specific position in the stack, until you stop the
	 * injection using `end_injection()` method.
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @param array $position {
	 *     The position where to start the injection.
	 *
	 *     @type string $type Injection type, either `control` or `section`.
	 *                        Default is `control`.
	 *     @type string $at   Where to inject. If `$type` is `control` accepts
	 *                        `before` and `after`. If `$type` is `section`
	 *                        accepts `start` and `end`. Dafault values based on
	 *                        the `type`.
	 *     @type string $of   Control/Section ID.
	 * }
	 */
	final public function start_injection( array $position ) {
		if ( $this->get_parent()->injection_point ) {
			wp_die( 'A controls injection is already opened. Please close current injection before starting a new one (use `end_injection`). Element name - `' . $this->get_parent()->get_name() .' Skin name - `' . $this->get_id() .'`.' );
        }

        $position['of'] = $this->get_id();

		$this->get_parent()->injection_point = $this->get_parent()->get_position_info( $position );
	}

	/**
	 * End injection.
	 *
	 * Used to close an existing open injection point. When you use this method
	 * it stops adding new controls to this point and continue to add controls
	 * to the regular position in the stack.
	 *
	 * @since 1.3.0
	 * @access public
	 */
	final public function end_injection() {
		$this->get_parent()->injection_point = null;
	}

	public function get_presets() {}

    public function register_presets() {

        $presets = $this->get_presets();

        if ( ! is_array( $presets ) || empty( $presets ) ) return;

        $controls_data = $this->get_parent()->get_controls();

        // Modify controls and add defaults
        foreach ( $this->get_parent()->get_controls() as $name => $control ) {

            if ( $control['type'] === 'section' || $name === '_skin' || empty( $presets[ $control_id ] ) ) continue;

            $controls_data[ $name ]['default'] = $presets[ $control_id ];
        }

        qazana()->controls_manager->set_element_stack_controls( $this->get_parent(), $controls_data );
    }

}
