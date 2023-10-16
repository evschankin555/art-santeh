<?php

/**
 * Featured_Box widget class
 *
 * @since 2.8.0
 */
class Cenos_Featured_Box_Widget extends WP_Widget {

    function __construct() {
        $widget_ops = array( 'classname' => 'cenos_featured_box', 'description' => esc_html__('A widget that displays a custom box ', 'cenos'), 'customize_selective_refresh' => true);

        $control_ops = array( 'id_base' => 'cenos_featured_box' );

        parent::__construct( 'cenos_featured_box',esc_html__('Cenos Featured Box', 'cenos'), $widget_ops, $control_ops );
    }

    function widget($args, $instance) {

        $cache = wp_cache_get('widget_featured_box', 'widget');

        if ( !is_array($cache) )
            $cache = array();

        if ( !isset( $args['widget_id'] ) )
            $args['widget_id'] = $this->id;

        if ( isset( $cache[ $args['widget_id'] ] ) ) {
            cenos_esc_data($cache[ $args['widget_id'] ]);
            return;
        }

        ob_start();
        extract($args);
        $title = apply_filters( 'widget_title', '' );
        cenos_esc_data($before_widget);
        cenos_esc_data($before_title.$title.$after_title);
        $icon_id = isset($instance['icon_id'])? $instance['icon_id'] :'';
        $content_text = isset($instance['content_text'])? $instance['content_text'] :'';
        $box_title = isset($instance['box_title'])? $instance['box_title'] :'';
        ?>
        <div class="content-box">
            <?php if($icon_id):?>
                <div class="icon">
                    <?php cenos_svg_icon($icon_id);?>
                </div>
            <?php endif;?>
            <div class="content-text">
                <h3 class="title"><?php echo esc_html($box_title);?></h3>
                <div class="text"><?php echo esc_html($content_text);?></div>
            </div>
        </div>
        <?php
        cenos_esc_data($after_widget);
        $cache[$args['widget_id']] = ob_get_flush();
        wp_cache_set('widget_featured_box', $cache, 'widget');
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['box_title'] = sanitize_text_field($new_instance['box_title']);
        $instance['icon_id'] = sanitize_text_field($new_instance['icon_id']);
        $instance['content_text'] = wp_kses( trim( wp_unslash( $new_instance[ 'content_text' ] ) ), wp_kses_allowed_html( 'post' ) );
        $this->flush_widget_cache();
        return $instance;
    }

    function flush_widget_cache() {
        wp_cache_delete('widget_featured_box', 'widget');
    }

    function form( $instance ) {
        $box_title     = isset( $instance['box_title'] ) ? esc_attr( $instance['box_title'] ) : '';
        $icon_id    = isset( $instance['icon_id'] ) ? esc_attr( $instance['icon_id'] ) : '';
        $content_text  = isset( $instance['content_text'] ) ? esc_attr( $instance['content_text'] ) : '';

        ?>

        <p><label for="<?php echo esc_attr($this->get_field_id( 'box_title' )); ?>"><?php esc_html_e( 'Box Title:', 'cenos' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'box_title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'box_title' )); ?>" type="text" value="<?php echo esc_attr($box_title); ?>" /></p>

        <p><label for="<?php echo esc_attr($this->get_field_id( 'icon_id' )); ?>"><?php esc_html_e( 'Icon ID:', 'cenos' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'icon_id' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'icon_id' )); ?>" type="text" value="<?php echo esc_attr($icon_id); ?>" /></p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'content_text' ) ); ?>"><?php echo esc_html('Content Text'); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></label>
            <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'content_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'content_text' ) ); ?>" cols="20" rows="3"><?php echo esc_textarea( $content_text ); ?></textarea>
        </p>
        <?php
    }
}

?>
