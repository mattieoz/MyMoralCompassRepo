<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 * @category Compass Theme
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
 */

/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function jvs_metaboxes(array $meta_boxes) {

    $prefix = '_compass_';

    $meta_boxes[] = array(
        'id'         => 'meta-post-fields',
        'title'      => 'Featured Post',
        'pages'      => array('post'),
        'context'    => 'normal',
        'priority'   => 'high',
        'show_names' => true,
        'fields'     => array(
            array(
                'name' => 'Featured on homepage?',
                'id'   => $prefix . 'featured_post',
                'type' => 'checkbox'
            )
        )
    );

    $meta_boxes[] = array(
        'id'         => 'meta-post-info',
        'title'      => 'Sources',
        'pages'      => array('post'),
        'context'    => 'normal',
        'priority'   => 'high',
        'show_names' => true,
        'fields'     => array(
            array(
                'name' => 'Bibliography and Links',
                'id'   => $prefix . 'sources',
                'type' => 'textarea'
            )
        )
    );

    return $meta_boxes;

}
add_filter('cmb_meta_boxes', 'jvs_metaboxes');

/**
 * Initialize the metabox class.
 */
function jvs_initialize_cmb_meta_boxes() {
    if ( ! class_exists( 'cmb_Meta_Box' ) )
        require_once 'init.php';
}
add_action('init', 'jvs_initialize_cmb_meta_boxes', 9999);
