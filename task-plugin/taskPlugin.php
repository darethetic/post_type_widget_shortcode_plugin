<?php
/*
 * Plugin Name: Task Custum post type
 * Plugin URI: cerseilabs.com
 * Description: A plugin to create a custom post type for Books
 * Version: 1.0
 * Author: CerseiLabs LTD
 * Author URI: cerseilabs.com
 */
add_action('init', 'book_init');
//custom post type Book, and initialization
function book_init()
{

    $labels = array(

        'name' => 'Books',
        'singular_name' => 'Book',
        'menu_name' => 'Books',
        'name_admin_bar' => 'Book',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Book',
        'new_item' => 'New Book',
        'edit_item' => 'Edit Book',
        'view_item' => 'View Book',
        'all_items' => 'All Books',
        'search_items' => 'Search Books',
        'parent_item_colon' => 'Parent Books:',
        'not_found' => 'No books found.',
        'not_found_in_trash' => 'No books found in Trash.'

    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'book'),
        'capability_type' => 'post',
        'has_archive' => true,
        'menu_position' => null,
        'supports' => array('title', 'thumbnail')

    );

    register_post_type('book', $args);

}

include 'include/author.php';
include 'include/books_widget.php';
include 'include/slider.php';


function custom_meta_box_markup_plugin($object)
{

    wp_enqueue_style('orderdetails', get_template_directory_uri() . '/include/css/metabox_book.css');

    wp_nonce_field(basename(__FILE__), "meta-box-nonce");

    ?>
    <div class="metabox">
        <div class="metastyle">
            <label class="labela" for="pl_author"> Author </label>

            <?php $selectedAuthor = get_post_meta($object->ID, "pl_author", true); ?>

            <select class="formStyle" name="pl_author">
                <?php

                $author = array('post_type' => 'author');

                $loop = new WP_Query($author);

                while ($loop->have_posts()):$loop->the_post(); ?>

                    <option
                        value="<?php the_ID(); ?>" <?php selected($selectedAuthor, get_the_ID()); ?>><?php the_title(); ?></option>

                    <?php
                endwhile;
                wp_reset_query();
                ?>
            </select>
            <br/>
            <label class="labela" for="pl_pnumber">Number of pages</label>
            <input class="formStyle" type="text" name="pl_pnumber" size="24"
                   value="<?php echo get_post_meta($object->ID, "pl_pnumber", true); ?>"/>
            <br/>
            <label class="labela" for="pl_binding">Binding</label>
            <?php $selectedBinding = get_post_meta($object->ID, "pl_binding", true); ?>
            <select class="formStyle" name="pl_binding">
                <option value="Paperbacks" <?php selected($selectedBinding, Paperbacks); ?>>Paperbacks</option>
                <option value="Hardcovers" <?php selected($selectedBinding, Hardcovers); ?>>Hardcovers</option>
                <option value="Spirals" <?php selected($selectedBinding, Spirals); ?>>Spirals</option>
                <option value="Other" <?php selected($selectedBinding, Other); ?> >Other</option>
            </select>
            <br/>
            <label class="labela" for="pl_yearp">Year of publication </label>
            <?php $selectedYear = get_post_meta($object->ID, "pl_yearp", true); ?>
            <select class="formStyle" name="pl_yearp">
                <?php
                $y = 1800;
                while ($y > 1799) {
                    if ($y > date('Y'))
                        break;
                    else {
                        ?>
                        <option
                            value="<?php echo $y; ?>" <?php selected($selectedYear, $y); ?>><?php echo $y; ?></option>
                        <?php
                        $y++;
                    }

                }
                ?>
            </select>
            <br/>
            <label id="lab" class="labela" for="pl_cut">Excerpt from book</label><br/>
            <textarea class="formStyle" name="pl_cut" rows="4"
                      cols="30"><?php echo get_post_meta($object->ID, "pl_cut", true); ?></textarea>
        </div>
    </div>
    <?php
}


function add_meta_box_book()
{
    add_meta_box('book-meta-box', 'Book meta box', 'custom_meta_box_markup_plugin', 'book', 'advanced', 'high', null);
}

add_action('add_meta_boxes', 'add_meta_box_book');

function save_book_meta_box($post_id, $post, $update)
{
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if (!current_user_can("edit_post", $post_id))
        return $post_id;

    if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;


    $pageNum = $binding = $yearPub = $cut = $author = "";

    if (isset($_POST["pl_author"])) {
        $author = $_POST["pl_author"];
    }
    update_post_meta($post_id, "pl_author", $author);

    if (isset($_POST["pl_pnumber"])) {
        $pageNum = $_POST["pl_pnumber"];
    }
    update_post_meta($post_id, "pl_pnumber", $pageNum);

    if (isset($_POST["pl_binding"])) {
        $binding = $_POST["pl_binding"];
        update_post_meta($post_id, "pl_binding", $binding);
    }

    if (isset($_POST["pl_yearp"])) {
        $yearPub = $_POST["pl_yearp"];
    }
    update_post_meta($post_id, "pl_yearp", $yearPub);

    if (isset($_POST["pl_cut"])) {
        $cut = $_POST["pl_cut"];
    }
    update_post_meta($post_id, "pl_cut", $cut);

}

add_action("save_post", "save_book_meta_box", 10, 3);