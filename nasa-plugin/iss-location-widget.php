<?php
class ISS_Location_Widget extends WP_Widget
{
    public function __construct()
    {
        $widget_options = array( 
            'classname' => 'iss_location_widget',
            'description' => 'Position actuelle de l\'ISS',
        );

        parent::__construct('iss_location', 'ISS Location', $widget_options);
    }


    public function widget($args, $instance)
    {
        
        $api_result = $this->callISSAPI();
        $iss_location = $api_result->iss_position;

        echo $args['before_widget'];
        echo $args['before_title'];
        echo apply_filters('widget_title', $instance['title']);
        echo $args['after_title'];

        echo "<p>L'ISS se trouve à la latitude " . $iss_location->latitude . " et longitude " . $iss_location->longitude . "</p>";
        
    }

    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
        return $instance;
    }

    public function form($instance)
    {
        $title = isset($instance['title']) ? $instance['title'] : '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo  $title; ?>" />
        </p>
        <?php
    }

    private function callISSAPI(){
        $response = file_get_contents('http://api.open-notify.org/iss-now.json');
        $response = json_decode($response);
        return $response;
    }
}