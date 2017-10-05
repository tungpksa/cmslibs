<?php

add_action( 'widgets_init', 'cmsaddons_follow_google_widget_box' );
function cmsaddons_follow_google_widget_box() {
	register_widget( 'cmsaddons_follow_google_widget' );
}
class cmsaddons_follow_google_widget extends WP_Widget {

	public function __construct(){
		$widget_ops = array( 'classname' => 'follow-google-widget'  );
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'follow-google-widget' );
		parent::__construct( 'follow-google-widget','CA - Google + page', $widget_ops, $control_ops );
	}

	public function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$page_url = $instance['page_url'];

		echo wp_kses_post( $before_widget );

		if ( ! empty( $title ) ) {
			echo wp_kses_post( $before_title . $title . $after_title );
		} ?>
			<div class="google-box">
				<!-- Google +1 script -->
				<script type="text/javascript">
				  (function() {
					var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
					po.src = 'https://apis.google.com/js/plusone.js';
					var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
				  })();
				</script>
				<!-- Link blog to Google+ page -->
				<a style='display: block; height: 0;' href="<?php echo $page_url ?>" rel="publisher">&nbsp;</a>
				<!-- Google +1 Page badge -->
				<g:plus href="<?php echo $page_url ?>" height="131" width="300" theme="light"></g:plus>

			</div>
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
		$defaults = array( 'title' =>__( 'Follow us on Google+' , 'tie') );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title : </label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php if( !empty($instance['title']) ) echo $instance['title']; ?>" class="widefat" type="text" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'page_url' ); ?>">Page Url : </label>
			<input id="<?php echo $this->get_field_id( 'page_url' ); ?>" name="<?php echo $this->get_field_name( 'page_url' ); ?>" value="<?php if( !empty($instance['page_url']) ) echo $instance['page_url']; ?>" class="widefat" type="text" />
		</p>


	<?php
	}
}
?>