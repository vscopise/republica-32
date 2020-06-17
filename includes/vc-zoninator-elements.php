<?php
/*
Element Description: VC Z Classic Listing 1
*/

function pxe_z_grid_init()
{
    $settings = array(
        'name'                    => __('Z Grid Listing 1', 'publisher'),
        'base'                    => 'pxe_z_grid',
        'category'                => __('Publisher', 'publisher'),
        'description'             => __('Post Grid ordered by Zone', 'publisher'),
        'show_settings_on_create' => false,
        'weight'                  => -5,
        'html_template'           => dirname(__FILE__) . '/vc_templates/pxe-z-grid.php',
        'params'                  => array(
            array(
                "type"        => "textfield",
                "heading"     => __("Zone Name", "publisher"),
                "param_name"  => "zone_name",
                "description" => __("Zone name", "publisher")
            ),
            array(
                "type"        => "textfield",
                "heading"     => __("Title", "publisher"),
                "param_name"  => "title",
                "description" => __("Title", "publisher")
            ),
            array(
                "type"        => "checkbox",
                "heading"     => __("Ocultar este bloque", "publisher"),
                "param_name"  => "hide_block",
            ),
        )
    );
    vc_map($settings);

    if (class_exists("WPBakeryShortCode")) {
        // Class Name should be WPBakeryShortCode_Your_Short_Code
        // See more in vc_composer/includes/classes/shortcodes/shortcodes.php
        class WPBakeryShortCode_Z_Grid extends WPBakeryShortCode
        {
            public function __construct($settings)
            {
                parent::__construct($settings); // !Important to call parent constructor to active all logic for shortcode.

                add_filter('publisher-theme-core/pagination/filter-data/' . __CLASS__, array(
                    $this,
                    'filter_data'
                ));
            }

            function filter_data($atts)
            {
                $atts = array();
                return $atts;
            }

            // Register scripts and styles there (for preview and frontend editor mode).

            // Some custom helper function that can be used in content element template (vc_templates/test_element.php)
            // This function check some string if it matches "yes","true",1,"1" return TRUE if yes, false if NOT.
            public function checkBool($in)
            {
                if (strlen($in) > 0 && in_array(strtolower($in), array(
                    "yes",
                    "true",
                    "1",
                    1
                ))) {
                    return true;
                }
                return false;
            }
        }
    }
}
add_action('vc_after_init', 'pxe_z_grid_init');

/* function z_classic_listing_1_init()
{
    $settings = array(
        'name'                    => __('Z Classic Listing 1', 'publisher'),
        'base'                    => 'z_classic_listing_1',
        'category'                => __('Publisher', 'publisher'),
        'description'             => __('Post(s) ordered by Zone', 'publisher'),
        'show_settings_on_create' => false,
        'weight'                  => -5,
        'html_template'           => dirname(__FILE__) . '/vc_templates/zclassic-listing-1.php',
        'params'                  => array(
            array(
                'type'          => 'dropdown',
                'heading'       => __('Columns', 'publisher'),
                'param_name'    => 'columns',
                'admin_label'   => true,
                'value'         => array(
                    '1' => __('1 Column', 'publisher'),
                    '2' => __('2 Column', 'publisher'),
                    '3' => __('3 Column', 'publisher'),
                ),
                'group' => 'General',
            ),
            array(
                'type'          => 'dropdown',
                'heading'       => __('Show Post Excerpt?', 'publisher'),
                'param_name'    => 'show_excerpt',
                'admin_label'   => true,
                'value'         => array(
                    '1' => __('Yes', 'publisher'),
                    '0' => __('No', 'publisher'),
                ),
                'group' => 'General',
            ),
            array(
                'type'          => 'textfield',
                'heading'       => __('Title size', 'publisher'),
                'param_name'    => 'title_size',
                'admin_label'   => true,
                'group' => 'General',
            ),
            array(
                'type'          => 'textfield',
                'heading'       => __('Zone name', 'publisher'),
                'param_name'    => 'zone_name',
                'admin_label'   => true,
                'group'         => 'Posts filter',
            ),
            array(
                'type'          => 'textfield',
                'heading'       => __('Number of Posts', 'publisher'),
                'param_name'    => 'posts_number',
                'value'         => 1,
                'admin_label'   => true,
                'group'         => 'Posts filter',
            ),
            array(
                'type'          => 'textfield',
                'heading'       => __('Offset posts', 'publisher'),
                'param_name'    => 'posts_offset',
                'value'         => 0,
                'admin_label'   => true,
                'group'         => 'Posts filter',
            ),

        ),
    );
    vc_map($settings);

    if (class_exists("WPBakeryShortCode")) {
        class WPBakeryShortCode_Z_Classic_Listing_1 extends WPBakeryShortCode
        {
            public function __construct($settings)
            {
                parent::__construct($settings);
                $this->jsCssScripts();
            }
            public function jsCssScripts()
            {
                wp_enqueue_style(
                    'classic_listing_css',
                    get_stylesheet_directory_uri() . '/includes/css/classic-listing.css',
                    array(),
                    filemtime(get_stylesheet_directory() . '/includes/css/classic-listing.css')
                );
            }
        }
    }
}
add_action('vc_after_init', 'pxe_class_1_init'); */
