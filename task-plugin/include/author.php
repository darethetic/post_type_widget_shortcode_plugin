<?php
add_action('init','author_init');

function author_init()
{
    $labels= array(
        'name' => 'Author',
        'singular_name' => 'Author',
        'menu_name' => 'Authors',
        'name_admin_bar' => 'Author',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Author',
        'new_item' => 'New Author',
        'edit_item' => 'Edit Author',
        'view_item' => 'View Author',
        'all_items' => 'All Authors',
        'search_items' => 'Search Authors',
        'parent_item_colon' => 'Parent Authors:',
        'not_found' => 'No authors found.',
        'not_found_in_trash' => 'No authors found in Trash.'
    );
    $args= array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'author'),
        'capability_type' => 'post',
        'has_archive' => true,
        'menu_position' => null,
        'supports' => array('title', 'thumbnail')
    );
    register_post_type('author',$args);
}

