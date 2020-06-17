<?php

// Dev mode enabled
// Use this for uncompressed custom css codes
//if ( ! defined( 'BF_DEV_MODE' ) ) {
//	define( 'BF_DEV_MODE', TRUE );
//}

// Extensiones del Visual Composer, compatibles con zoninator
function pxe_wpb_extensions_init() {
    if (is_plugin_active('zoninator/zoninator.php')) {
        require_once(get_stylesheet_directory() . '/includes/vc-zoninator-elements.php');
    }
}
add_action('vc_before_init', 'pxe_wpb_extensions_init');

// Colgado en el Título
function pxe_custom_title($title, $id)
{
    $colgado_v = get_post_meta($id, '_COLGADO', TRUE);
    $colgado_n = get_post_meta($id, 'COLGADO', TRUE);
    $colgado = ('' != $colgado_v) ? $colgado_v : $colgado_n;
    if ('' !== $colgado) {
        $new_title = '<span class="colgado">' . $colgado . '</span>' . $title;
        return $new_title;
    } else {
        return $title;
    }
}
function pxe_colgado_on_title($query)
{
    global $wp_query;
    if ($query === $wp_query && is_single()) {
        add_filter('the_title', 'pxe_custom_title', 10, 2);
    } else {
        remove_filter('the_title', 'pxe_custom_title', 10, 2);
    }
}
add_action('loop_start', 'pxe_colgado_on_title');

function pxe_add_colgado_meta_box()
{
    add_meta_box(
        'colgado_meta_box',
        esc_html__('Colgado', 'publisher'),
        'pxe_show_colgado_meta_box',
        'post',
        'normal'
    );
}
add_action('add_meta_boxes', 'pxe_add_colgado_meta_box');

function pxe_show_colgado_meta_box()
{
    ob_start();
    include('includes/templates/template-colgado-mb.php');
    ob_end_flush();
}

function pxe_save_colgado($post_id)
{
    $is_autosave = wp_is_post_autosave($post_id);
    $is_revision = wp_is_post_revision($post_id);
    $is_valid_nonce = (isset($_POST['colgado_nonce']) && wp_verify_nonce($_POST['colgado_nonce'], basename(__FILE__))) ? 'true' : 'false';
    if ($is_autosave || $is_revision || !$is_valid_nonce) {
        return;
    }
    if (isset($_POST['colgado'])) {
        update_post_meta($post_id, '_COLGADO', sanitize_text_field($_POST['colgado']));
    }
}
add_action('save_post', 'pxe_save_colgado');

// Custom Styles
function pxe_theme_enqueue_styles()
{
    wp_enqueue_style(
        'parent-theme',
        get_template_directory_uri() . '/style.css'
    );

    wp_enqueue_style(
        'child-theme',
        get_stylesheet_directory_uri() . '/includes/css/styles.css',
        array('parent-theme'),
        filemtime(get_stylesheet_directory() . '/includes/css/styles.css')
    );
}
add_action('wp_enqueue_scripts', 'pxe_theme_enqueue_styles');

// Redactor personalizado
function pxe_add_redactor_meta_box()
{
    add_meta_box(
        'redactor_meta_box',
        esc_html__('Redactor', 'publisher'),
        'pxe_show_redactor_meta_box',
        'post',
        'normal'
    );
}
add_action('add_meta_boxes', 'pxe_add_redactor_meta_box');

function pxe_show_redactor_meta_box()
{
    ob_start();
    include('includes/templates/template-redactor-mb.php');
    ob_end_flush();
}

function pxe_save_redactor($post_id)
{
    $is_autosave = wp_is_post_autosave($post_id);
    $is_revision = wp_is_post_revision($post_id);
    $is_valid_nonce = (isset($_POST['redactor_nonce']) && wp_verify_nonce($_POST['redactor_nonce'], basename(__FILE__))) ? 'true' : 'false';
    if ($is_autosave || $is_revision || !$is_valid_nonce) {
        return;
    }
    if (isset($_POST['redactor'])) {
        update_post_meta($post_id, 'AUTOR', sanitize_text_field($_POST['redactor']));
    }
}
add_action('save_post', 'pxe_save_redactor');

// Título personalizado en home, compatible con SEO
function pxe_custom_home_title($title)
{
    if (is_home() || is_front_page()) {
        $query = new WP_Query(array('posts_per_page' => 1));
        if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();
                $last_title = get_the_title();
            endwhile;
            wp_reset_postdata();
        endif;
        $home_title = get_bloginfo();

        $title = $last_title . ' - ' . $home_title;
    }

    return $title;
}
add_filter('wpseo_title', 'pxe_custom_home_title', 10);

// Publicador del Post
function pxe_save_publisher($new, $old, $post)
{
    if (('draft' === $old || 'auto-draft' === $old) && ($new === 'publish' || $new === 'future')) {
        $publisher = get_current_user_id();
        update_post_meta($post->ID, '_publisher', $publisher);
    }
}
add_action('transition_post_status', 'pxe_save_publisher', 10, 3);

function pxe_publisher_column($column, $post_id)
{
    switch ($column) {
        case 'publisher':
            $publisher_id = get_post_meta($post_id, '_publisher', TRUE);
            if ('' != $publisher_id) {
                $publisher = get_the_author_meta('display_name', $publisher_id);
                echo $publisher;
            }
            break;
    }
}
add_action('manage_posts_custom_column', 'pxe_publisher_column', 11, 2);

function pxe_posts_table_head($columns)
{
    $columns['publisher']  = 'Publicador';
    return $columns;
}
add_filter('manage_posts_columns', 'pxe_posts_table_head');

function pxe_publisher_column_width()
{
    echo '<style type="text/css">';
    echo '.column-publisher { width:10% !important; }';
    echo '</style>';
}
add_action('admin_head', 'pxe_publisher_column_width');

// Compresión del largo de los comentarios
function pxe_theme_enqueue_scripts()
{

    if (is_singular('post')) {
        wp_enqueue_script(
            'post_comment_list',
            get_stylesheet_directory_uri() . '/includes/js/post-comment-list.js',
            array('jquery'),
            filemtime(get_stylesheet_directory() . '/includes/js/post-comment-list.js')
        );
    }
}
add_action('wp_enqueue_scripts', 'pxe_theme_enqueue_scripts');

// Agrega el Id al final de la Url
function pxe_save_post($new, $old, $post)
{
    if ('publish' === $new && 'publish' !== $old && 'post' === $post->post_type) {
        $post_id = $post->ID;
        $post_title = $post->post_title;

        $post_name = sanitize_title($post_title) . '-id' . $post_id;
        wp_update_post(array(
            'ID' => $post_id,
            'post_name' => $post_name
        ));
    } else {
        return;
    }
}
add_action('transition_post_status', 'pxe_save_post', 10, 3);