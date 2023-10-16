<?php

/**
 * Cenos Recent Posts widget class
 *
 * @since 2.8.0
 */
class Cenos_Recent_Posts_Widget extends WP_Widget {

    function __construct() {
        $widget_ops = array( 'classname' => 'cenos_recent_posts', 'description' =>esc_html__('Your site&#8217;s most recent Posts.', 'cenos'), 'customize_selective_refresh' => true);

        $control_ops = array( 'id_base' => 'cenos_recent_posts' );

        parent::__construct( 'cenos_recent_posts',esc_html__('Cenos Recent Posts', 'cenos'), $widget_ops, $control_ops );
    }

    function widget($args, $instance) {
        if ( ! isset( $args['widget_id'] ) ) {
            $args['widget_id'] = $this->id;
        }

        $default_title =esc_html__( 'Recent Posts' ,'cenos');
        $title         = ( ! empty( $instance['title'] ) ) ? $instance['title'] : $default_title;

        /** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

        $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
        if ( ! $number ) {
            $number = 5;
        }

        $r = new WP_Query(
        /**
         * Filters the arguments for the Recent Posts widget.
         *
         * @since 3.4.0
         * @since 4.9.0 Added the `$instance` parameter.
         *
         * @see WP_Query::get_posts()
         *
         * @param array $args     An array of arguments used to retrieve the recent posts.
         * @param array $instance Array of settings for the current widget.
         */
            apply_filters(
                'widget_posts_args',
                array(
                    'posts_per_page'      => $number,
                    'no_found_rows'       => true,
                    'post_status'         => 'publish',
                    'ignore_sticky_posts' => true,
                ),
                $instance
            )
        );

        if ( ! $r->have_posts() ) {
            return;
        }
        $i = 0;
        cenos_esc_data($args['before_widget']);
        if ( $title ) {
            cenos_esc_data($args['before_title'] . $title . $args['after_title']);
        }
        ?>
        <ul class="cenos-posts-list">
            <?php foreach ( $r->posts as $recent_post ) :
                $background_style = '';
                $item_class = '';
                if ($i == 0){
                    $background_thumb = get_the_post_thumbnail_url($recent_post->ID,'large');
                    if ($background_thumb){
                        $background_style = 'style="background-image: url('.$background_thumb.');"';
                        $item_class = ' has_background';
                    }
                }
                $post_title   = get_the_title( $recent_post->ID );
                $title        = ( ! empty( $post_title ) ) ? $post_title :esc_html__( '(no title)' ,'cenos');
                $aria_current = '';
                if ( get_queried_object_id() === $recent_post->ID ) {
                    $aria_current = ' aria-current="page"';
                }
                $post_thumb_class = ' no-thumbnail';
                if (has_post_thumbnail($recent_post)){
                    $post_thumb_class = ' has-thumbnail';
                }
                $item_class .= $post_thumb_class;
                ?>
                <li class="post-item<?php echo esc_attr($item_class);?>">
                    <?php if (!empty( $background_style)):?>
                        <div class="post-item-background" <?php cenos_esc_data($background_style);?>></div>
                    <?php endif;?>
                    <div class="post-list-thumb">
                        <a href="<?php echo esc_url( get_the_permalink( $recent_post->ID ) ); ?>">
                            <?php echo get_the_post_thumbnail( $recent_post->ID, 'thumbnail' );?>
                        </a>
                    </div>
                    <div class="post-list-content">
                        <?php if ($i == 0){
                           cenos_post_taxonomy('categories', false);
                        } else {
                            $categories = get_the_category( $recent_post->ID );
                            if (!empty($categories)){
                                printf('<span class="post-list-category"><a href="%1$s" title="%2$s">%2$s</a></span>',
                                    get_category_link($categories[0]),
                                    $categories[0]->name
                                );
                            }
                        }?>
                        <h5 class="post-list-title">
                            <a href="<?php the_permalink( $recent_post->ID ); ?>" <?php cenos_esc_data($aria_current); ?>><?php echo esc_html($title); ?></a>
                        </h5>
                        <div class="post-list-meta">
                            <?php
                            if ($i == 0){
                                cenos_post_meta(['posted_on','author'], false);
                            } else {
                                printf('<span class="posted-on">%s</span>',get_the_date( "M d, Y" ,$recent_post->ID));
                            }
                            ?>
                        </div>
                    </div>
                </li>
            <?php
                $i++;
            endforeach; ?>
        </ul>
        <?php
        cenos_esc_data($args['after_widget']);
    }

    function update( $new_instance, $old_instance ) {
        $instance              = $old_instance;
        $instance['title']     = sanitize_text_field( $new_instance['title'] );
        $instance['number']    = (int) $new_instance['number'];
        return $instance;
    }

    function flush_widget_cache() {
        wp_cache_delete('widget_recent_posts', 'widget');
    }

    function form( $instance ) {
        $title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $id = $this->get_field_id( 'title' );
        ?>
        <p>
            <label for="<?php echo esc_attr($id); ?>"><?php esc_html_e( 'Title:' ,'cenos'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <p>
            <label for="<?php echo esc_attr($id); ?>"><?php esc_html_e( 'Number of posts to show:' ,'cenos'); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($this->get_field_name( 'number' )); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($number); ?>" size="3" />
        </p>
        <?php
    }
}

?>
