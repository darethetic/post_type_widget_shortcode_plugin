<?php
add_action('init', 'slider_init');
//creating new post type slider
function slider_init()
{

    $labels = array(
        'name' => 'Slider',
        'singular_name' => 'Slider',
        'menu_name' => 'Slide',
        'name_admin_bar' => 'Slider',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Slide',
        'new_item' => 'New Slide',
        'edit_item' => 'Edit Slide',
        'view_item' => 'View Slide',
        'all_items' => 'All Slides',
        'search_items' => 'Search Slides',
        'parent_item_colon' => 'Parent Slides:',
        'not_found' => 'No slides found.',
        'not_found_in_trash' => 'No slides found in Trash.'
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'slider'),
        'capability_type' => 'post',
        'has_archive' => true,
        'menu_position' => null,
        'supports' => array('title', 'thumbnail')
    );

    register_post_type('slider', $args);

}

add_action('wp_print_scripts', 'np_register_scripts');
add_action('wp_print_styles', 'np_register_styles');

function np_register_scripts()
{
    if (!is_admin()) {
        // register
        wp_register_script('np_nivo-script', plugins_url('/nivo-slider/jquery.nivo.slider.js', __FILE__), array('jquery'));
        wp_register_script('np_script', plugins_url('/js/slide.js', __FILE__));

        // enqueue
        wp_enqueue_script('np_nivo-script');
        wp_enqueue_script('np_script');
    }
}

function np_register_styles()
{
    if (!is_admin()) {
        //register
        wp_register_style('np_styles', plugins_url('/nivo-slider/nivo-slider.css', __FILE__));

        //enqueue
        wp_enqueue_style('np_styles');
    }
}

// shortcode hook
add_shortcode('slider', 'np_function');

// changing image size. Broken?
add_image_size('np_function', 180, 180, true);

//fucntion for call shortcode with loop
function np_function($atts, $type = 'np_function')
{

    $atts = shortcode_atts(
        array(
            'num' => 4,
            'width' => 300,
            'height' => 300,
        ), $atts);


    $args = array(
        'post_type' => 'slider',
        'posts_per_page' => $atts['num']

    );

    $result = '<div class="slider-wrapper theme-default">';
    $result .= '<div id="slider" class="nivoSlider">';

    //the loop
    $loop = new WP_Query($args);
    while ($loop->have_posts()) {
        $loop->the_post();

        $the_url = wp_get_attachment_image_src(get_post_thumbnail_id($loop->post->ID), $type);
        $result .= '<img title="' . get_the_title() . '" src="' . $the_url[0] . '" data-thumb="' . $the_url[0] . '" alt=""/>';
    }
    $result .= '</div>';
    $result .= '<div id = "htmlcaption" class = "nivo-html-caption">';
    $result .= '<strong>This</strong> is an example of a <em>HTML</em> caption with <a href = "#">a link</a>.';
    $result .= '</div>';
    $result .= '</div>';
    return $result;
}