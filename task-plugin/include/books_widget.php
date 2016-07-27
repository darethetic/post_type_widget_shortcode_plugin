<?php
if (!defined('ABSPATH'))
    die('-1');


add_action('widgets_init', function () {
    register_widget('Book_Widget');
});

class Book_Widget extends WP_Widget
{
    
    function __construct()
    {
        parent::__construct(
            'Book_Widget', // Base ID
            __('Book_Widget', 'text_domain'), // Name
            array('description' => __('Show last few posts!', 'text_domain'),) // Args
        );
        
    }

    public function widget($args, $instance)
    {
        
        $querryArgs = array('post_type' => 'book', 'orderby' => 'post_date', 'order' => 'desc', 'posts_per_page' => $instance['number_posts']);
        $querry = new WP_Query($querryArgs);
        while ($querry->have_posts()):$querry->the_post();
            {
                ?>
                    <li><a href="<?php get_permalink();?>"> <?php echo the_title();?></a></li>
                <?php
            }
        endwhile;
        wp_reset_query();
        
        echo __('Show last '.$instance['number_posts'].' posts!', 'text_domain');
        echo $args['after_widget'];
    }

    public function form($instance)
    {
        $number_posts = ! empty( $instance['number_posts'] ) ? $instance['number_posts'] : __( 'Number of posts', 'text_domain' );
        ?>
        <p>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number_posts' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number_posts' ) ); ?>" type="text" value="<?php echo esc_attr( $number_posts ); ?>">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['number_posts'] = ( ! empty( $new_instance['number_posts'] ) ) ? strip_tags( $new_instance['number_posts'] ) : '';

        return $instance;
    }
}