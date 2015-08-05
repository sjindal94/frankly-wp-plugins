<?php
include 'formcreate.php';

/*
Plugin Name: franklymeapp
Plugin URI: http://www.frankly.me
Description: Social video selfie sharing and question answering application for android ios and web.
Author: chowmean
Version: 1.0
Author URI: www.github.com/chowmean
*/
// Block direct requests
if ( !defined('ABSPATH') )
    die('-1');
add_action( 'widgets_init', function(){
    register_widget( 'My_Widget' );
});



/**
 * Adds My_Widget widget.
 */
class My_Widget extends WP_Widget {
    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'My_Widget', // Base ID
            __('Frankly Me widget', 'text_domain'), // Name
            array( 'description' => __( 'Social video selfie sharing and question answering application for android ios and web.', 'text_domain' ), ) // Args
        );
    }



    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
        }
        echo $args['after_widget'];

        if (!isset($instance['no_elements']))
        {
            echo ($instance['no_elements']);
        }

        if (!isset($instance['elements']))
        {
            print_r ($instance['elements']);
        }

        if (! isset($instance['username']))
        {echo "Frankly Username NOT set";}
        else
        {generating_form($instance);
        }
    }










    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = __( 'New title', 'text_domain' );
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>

        <?php
        if ( isset( $instance[ 'from' ] ) ) {
        $from = $instance[ 'from' ];
        }
        else {
        $from = __( 'Franklymail', 'text_domain' );
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'from' ); ?>"><?php _e( 'Name from which to recieve mail:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'from' ); ?>" name="<?php echo $this->get_field_name( 'from' ); ?>" type="text" value="<?php echo esc_attr( $from ); ?>">
        </p>


        <?php
        if (isset( $instance[ 'key' ] )){$key=$instance[ 'key' ];}

        else {
            $key = __( 'New Key', 'text_domain' );
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'key' ); ?>"><?php _e( 'Mail where to receive Notifications:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'key' ); ?>" name="<?php echo $this->get_field_name( 'key' ); ?>" type="text" value="<?php echo esc_attr( $key ); ?>">
        </p>
        <?php
        if (isset( $instance[ 'username' ] )){$username=$instance[ 'username' ];}
        else {
            $username = __( 'New username', 'text_domain' );
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'username' ); ?>"><?php _e( 'Frankly Username:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'username' ); ?>" name="<?php echo $this->get_field_name( 'username' ); ?>" type="text" value="<?php echo esc_attr( $username ); ?>">
        </p>



        <?php
        if (isset( $instance[ 'no_elements' ] )){$no_elements=$instance[ 'no_elements' ];}
        else {
            $no_elements = __( '1', 'text_domain' );
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'no_elements' ); ?>"><?php _e( 'Total elements in form:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'no_elements' ); ?>" name="<?php echo $this->get_field_name( 'no_elements' ); ?>" type="text" value="<?php echo esc_attr( $no_elements ); ?>">
        </p>



        <?php

        $i=0;
        while($i<$no_elements){
            if (isset( $instance[ 'options'.$i ] )){$instance[ 'options'.$i ];}
            else {
                $instance[ 'options'.$i ] = __( '0', 'text_domain' );
            }
            ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'options'.$i ); ?>"><?php _e( 'Enter Option '.$i ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'options'.$i ); ?>" name="<?php echo $this->get_field_name( 'options'.$i ); ?>" type="text" value="<?php echo esc_attr( $instance['options'.$i] ); ?>">
            </p>


            <?php
            $i=$i+1;
        }

        ?>


        <p>
            <label ><?php _e( 'Copy and paste this link to embed in any post or page' ); ?></label>
            <input  type="text" value='<iframe  padding="30" frameborder="0"  src="http://embed.frankly.me/askBtnLg/template.html?user=<?php echo $username;?>> </iframe>' >
        </p>


    <?php

    }










    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['from'] = ( ! empty( $new_instance['from'] ) ) ? strip_tags( $new_instance['from'] ) : '';
        $instance['key'] = ( ! empty( $new_instance['key'] ) ) ? strip_tags( $new_instance['key'] ) : '';
        $instance['username'] = ( ! empty( $new_instance['username'] ) ) ? strip_tags( $new_instance['username'] ) : '';
        $instance['no_elements'] = ( ! empty( $new_instance['no_elements'] ) ) ? strip_tags( $new_instance['no_elements'] ) : '0';
        $i=0;
        while($i<$instance['no_elements'])
        {
            $instance['options'.$i] = ( ! empty( $new_instance['options'.$i] ) ) ? strip_tags( $new_instance['options'.$i] ) : '';
            $i=$i+1;}

        return $instance;
    }
} // class My_Widget




?>

