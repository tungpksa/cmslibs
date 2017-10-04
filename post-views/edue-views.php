<?php
// function to display number of posts.
function edue_views( $text ='' , $postID ='' ){
	global $post;

	/*
	check theme option enable
	if( !tie_get_option( 'post_views' ) ){
		return false;
	}*/

	if( empty($postID) ){
		$postID = $post->ID ;
	}
	
    $count_key 	= 'edue_views';
    $count 		= get_post_meta($postID, $count_key, true);
	$count 		= @number_format($count);
    if( empty($count) ){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, 0 );
        $count = 0;
    }
    return '<span class="post-views"><i class="fa fa-eye"></i>'.$count.' '.$text.'</span> ';
}

// function to count views.
 /*insert edue_setPostViews() to single in while */
function edue_setPostViews() {
	global $post, $page;

	/*
	if( !tie_get_option( 'post_views' ) || $page > 1  ){
		return false;
	}*/

	if( $page > 1  ){
		return false;
	}

	$count 		= 0;
	$postID 	= $post->ID ;
    $count_key 	= 'edue_views';
    $count 		= (int)get_post_meta($postID, $count_key, true);

	if( !defined('WP_CACHE') || !WP_CACHE ){
		$count++;
		update_post_meta($postID, $count_key, (int)$count);
	}
}

### Function: Calculate Post Views With WP_CACHE Enabled
add_action('wp_enqueue_scripts', 'edue_postview_cache_count_enqueue');
function edue_postview_cache_count_enqueue() {
	global $post;

	/*if ( is_single() && ( defined('WP_CACHE') && WP_CACHE) && tie_get_option( 'post_views' ) ) */
	if ( is_single() && ( defined('WP_CACHE') && WP_CACHE)) {
		// Enqueue and localize script here
		wp_register_script( 'edue-postviews-cache', get_template_directory_uri() . '/js/postviews-cache.js', array( 'jquery' ) );
		wp_localize_script( 'edue-postviews-cache', 'edueViewsCacheL10n', array('admin_ajax_url' => admin_url('admin-ajax.php', (is_ssl() ? 'https' : 'http')), 'post_id' => intval($post->ID)));
		wp_enqueue_script ( 'edue-postviews-cache');
	}
}

### Function: Increment Post Views
add_action('wp_ajax_postviews', 'edue_increment_views');
add_action('wp_ajax_nopriv_postviews', 'edue_increment_views');
function edue_increment_views() {
	global $wpdb;
	/*if(!empty($_GET['postviews_id']) && tie_get_option( 'post_views' ))*/
	if(!empty($_GET['postviews_id']))
	{
		$post_id = intval($_GET['postviews_id']);
		if($post_id > 0 && defined('WP_CACHE') && WP_CACHE) {
			$count 		= 0;
			$count_key 	= 'edue_views';
			$count 		= (int)get_post_meta($post_id, $count_key, true);
			$count++;

			update_post_meta($post_id, $count_key, (int)$count);
			echo $count;
		}
	}
	exit();
}


// Add it to a column in WP-Admin 
add_filter('manage_posts_columns', 'edue_posts_column_views');
add_action('manage_posts_custom_column', 'edue_posts_custom_column_views',5,2);
function edue_posts_column_views($defaults){
    $defaults['edue_post_views'] = __( 'Views' , 'edue' );
    return $defaults;
}
function edue_posts_custom_column_views($column_name, $id){
	if( $column_name === 'edue_post_views' ){
        echo edue_views( '', get_the_ID() );
    }
}
?>