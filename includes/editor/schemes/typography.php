<?php
namespace Qazana;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Scheme_Typography extends Scheme_Base {

    const TYPOGRAPHY_1 = '1';
    const TYPOGRAPHY_2 = '2';
    const TYPOGRAPHY_3 = '3';
    const TYPOGRAPHY_4 = '4';

    public static function get_type() {
        return 'typography';
    }

    public function get_title() {
        return __( 'Typography', 'qazana' );
    }

    public function get_disabled_title() {
        return __( 'Default Fonts', 'qazana' );
    }

    public function get_scheme_titles() {
        return [
            self::TYPOGRAPHY_1 => __( 'Primary Headline', 'qazana' ),
            self::TYPOGRAPHY_2 => __( 'Secondary Headline', 'qazana' ),
            self::TYPOGRAPHY_3 => __( 'Body Text', 'qazana' ),
            self::TYPOGRAPHY_4 => __( 'Accent Text', 'qazana' ),
        ];
    }

    public function get_default_scheme() {
        $fonts = array(
            self::TYPOGRAPHY_1 => array(
                'font_family' => 'Roboto',
                'font_weight' => '600',
            ),
            self::TYPOGRAPHY_2 => array(
                'font_family' => 'Roboto Slab',
                'font_weight' => '400',
            ),
            self::TYPOGRAPHY_3 => array(
                'font_family' => 'Roboto',
                'font_weight' => '400',
            ),
            self::TYPOGRAPHY_4 => array(
                'font_family' => 'Roboto',
                'font_weight' => '500',
            ),
        );

        return apply_filters( 'qazana/schemes/default_fonts', $fonts );

    }

    protected function _init_system_schemes() {
        return [];
    }

    public function print_template_content() {

        ?><div class="qazana-panel-scheme-items"></div><?php

    }
}
