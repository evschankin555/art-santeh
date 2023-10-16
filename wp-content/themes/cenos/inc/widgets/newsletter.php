<?php

/**
 * Cenos Newsletter widget class
 *
 * @since 2.8.0
 */
class Cenos_Newsletter_Widget extends WP_Widget {

    function __construct() {
        $widget_ops = array( 'classname' => 'cenos_newsletter', 'description' => esc_html__('Displays your Mailchimp for WordPress sign-up form', 'cenos'), 'customize_selective_refresh' => true);

        $control_ops = array( 'id_base' => 'cenos_newsletter' );

        parent::__construct( 'cenos_newsletter',esc_html__('Cenos Newsletter', 'cenos'), $widget_ops, $control_ops );
    }

    function widget($args, $instance) {
        $cache = wp_cache_get('widget_newsletter', 'widget');

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
        $title = apply_filters( 'widget_title', $instance['box_title'], $instance, $this->id_base );
        cenos_esc_data($args['before_widget']);
        cenos_esc_data($args['before_title'] . $title . $args['after_title']);
        $content_text = isset($instance['content_text'])? $instance['content_text'] :'';
        ?>
        <div class="text"><?php echo esc_html($content_text);?></div>
        <?php
        mc4wp_show_form();
        cenos_esc_data( $args['after_widget']);
        $cache[$args['widget_id']] = ob_get_flush();
        wp_cache_set('widget_newsletter', $cache, 'widget');
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['box_title'] = sanitize_text_field($new_instance['box_title']);
        $instance['content_text'] = wp_kses( trim( wp_unslash( $new_instance[ 'content_text' ] ) ), wp_kses_allowed_html( 'post' ) );
        $this->flush_widget_cache();
        return $instance;
    }

    function flush_widget_cache() {
        wp_cache_delete('widget_newsletter', 'widget');
    }

    function form( $instance ) {
        $box_title     = isset( $instance['box_title'] ) ? esc_attr( $instance['box_title'] ) : '';
        $content_text  = isset( $instance['content_text'] ) ? esc_attr( $instance['content_text'] ) : '';
        ?>
        <p><label for="<?php echo esc_attr($this->get_field_id( 'box_title' )); ?>"><?php esc_html_e( 'Box Title:', 'cenos' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'box_title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'box_title' )); ?>" type="text" value="<?php echo esc_attr($box_title); ?>" /></p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'content_text' ) ); ?>"><?php esc_html_e('Content Text', 'cenos'); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?></label>
            <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'content_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'content_text' ) ); ?>" cols="20" rows="3"><?php echo esc_textarea( $content_text ); ?></textarea>
        </p>
        <?php
    }
}

?>
