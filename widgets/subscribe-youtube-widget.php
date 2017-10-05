<?php
add_action( 'widgets_init', 'cmsaddons_subscribe_youtube_widget_box' );
function cmsaddons_subscribe_youtube_widget_box() {
	register_widget( 'cmsaddons_subscribe_youtube_widget' );
}
class cmsaddons_subscribe_youtube_widget extends WP_Widget {

	public function __construct(){
		$widget_ops = array( 'classname' => 'cmsaddons-youtube-widget'  );
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'cmsaddons-youtube-widget' );
		parent::__construct( 'cmsaddons-youtube-widget','CA - Subscribe Youtube Channel', $widget_ops, $control_ops );
	}

	public function widget( $args, $instance ) {
		extract( $args );
		
		$title     = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$page_url = $instance['page_url'];

		echo wp_kses_post( $before_widget );

		if ( ! empty( $title ) ) {
			echo wp_kses_post( $before_title . $title . $after_title );
		}
		?>
			<div class="youtube-box">
			<iframe id="fr" src="https://www.youtube.com/subscribe_widget?p=<?php echo $page_url ?>" style="overflow: hidden; height: 105px; border: 0; width: 100%;" scrolling="no" frameBorder="0"></iframe></div>
	<?php
		echo wp_kses_post( $after_widget );
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['page_url'] = strip_tags( $new_instance['page_url'] );
		return $instance;
	}

	public function form( $instance ) {
		$defaults = array( 'title' =>__( 'Subscribe to our Channel' , 'cmsaddons') );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title : </label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php if( !empty($instance['title']) ) echo $instance['title']; ?>" class="widefat" type="text" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'page_url' ); ?>">Channel Name : </label>
			<input id="<?php echo $this->get_field_id( 'page_url' ); ?>" name="<?php echo $this->get_field_name( 'page_url' ); ?>" value="<?php if( !empty($instance['page_url']) ) echo $instance['page_url']; ?>" class="widefat" type="text" />
		</p>

	<?php
	}
}
?>