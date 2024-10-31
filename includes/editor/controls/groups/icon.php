<?php
namespace Qazana;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Group_Control_Icon extends Group_Control_Base {

	protected static $fields;

	public static function get_type() {
		return 'icon';
	}

	protected function init_fields() {
		$fields = [];

        $fields['type'] = [
            'label' => __( 'Icon type', 'qazana' ),
            'type' => Controls_Manager::SELECT,
            'default' => 'icon',
            'options' => [
                'icon' => __( 'Icon', 'qazana' ),
                'image' => __( 'Image File', 'qazana' ),
            ],
        ];

        $fields['image'] = [
            'label' => __( 'Choose Image', 'qazana' ),
            'type' => Controls_Manager::MEDIA,
            'condition' => [
                'type' => 'image',
            ],
            'default' => [
                'url' => Utils::get_placeholder_image_src(),
            ],
        ];

        $fields['icon'] = [
            'label' => __( 'Icon', 'qazana' ),
            'type' => Controls_Manager::ICON,
            'label_block' => true,
            'condition' => [
                'type' => 'icon',
            ],
        ];

        return $fields;
    }
}
