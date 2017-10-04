<?php
if ( !class_exists('wpt_widget') ) {
	class wpt_widget extends WP_Widget {
		function __construct() {	        
	        
			// ajax functions
			add_action('wp_ajax_wpt_widget_content', array(&$this, 'ajax_wpt_widget_content'));
			add_action('wp_ajax_nopriv_wpt_widget_content', array(&$this, 'ajax_wpt_widget_content'));
	        add_action('wp_enqueue_scripts', array(&$this, 'wpt_register_scripts'));	        

			$widget_ops = array('classname' => 'widget_wpt', 'description' => __('Display posts by category in tabbed format.', 'wp-tab-widget')
				);
			
			parent::__construct(
				'wpt_widget', 
				__('Tab Post or Products by Category', 'wp-tab-widget'), $widget_ops);
	    }
	    
	    function wpt_register_scripts() { 
			// JS    
			wp_register_script('wpt_widget', plugins_url('assets/js/wp-tab-widget.js', __FILE__), array('jquery'));     
			wp_localize_script( 'wpt_widget', 'wpt',         
				array( 'ajax_url' => admin_url( 'admin-ajax.php' )) 
			);
	    }  
	    	
		function form( $instance ) {
			extract($instance);
			if(!isset($posttype)){
				$posttype=1;
			}
			?>
	        <div class="wpt_options_form">  
	        	<div class="wpt_select_tabs">
					<label for="<?php echo $this->get_field_id('txtcat'); ?>">
							<?php _e('Text', 'wpnetbase'); ?>			
							<br>
					<input type="text" id="<?php echo $this->get_field_id('txtcat'); ?>" name="<?php echo $this->get_field_name('txtcat'); ?>" value="<?php echo $txtcat; ?>" />
				
				</div>     
	        
	        	<div class="wpt_select_tabs">
					<label for="<?php echo $this->get_field_id('slugcat'); ?>">
							<?php _e('Slug Category:', 'wpnetbase'); ?>		
							<?php _e('Enter the slugs separated by commas (ex: slug1,slug2,slug3) ', 'wpnetbase'); ?>
							<br>
					<input type="text" id="<?php echo $this->get_field_id('slugcat'); ?>" name="<?php echo $this->get_field_name('slugcat'); ?>" value="<?php echo $slugcat; ?>" />
				
				</div>
				<div>
					<label for="<?php echo $this->get_field_id('posttype'); ?>" ><?php _e('Select Post Type:','wpnetbase'); ?></label>
					<select name="<?php echo $this->get_field_name('posttype'); ?>" id="<?php echo $this->get_field_id('posttype'); ?>" >
						<option value='1' <?php selected( $posttype, '1'); ?> >Post</option>
						<option value='2' <?php selected( $posttype, '2'); ?> >Product</option>						
					</select>

				</div>
			</div>
			<?php 
		}	
		
		function update( $new_instance, $old_instance ) {	
			$instance = $old_instance;    
			$instance['tabs'] = $new_instance['tabs'];
			$instance['slugcat'] = $new_instance['slugcat'];
			$instance['txtcat'] = $new_instance['txtcat'];	
			$instance['posttype'] = $new_instance['posttype'];
					
			return $instance;	
		}	
		function widget( $args, $instance ) {	
			
			extract($args);     
			extract($instance); 
			$slugcat= $instance['slugcat'];

			//echo nl2br(htmlentities  ($instance['tab1content'])); 
			wp_enqueue_script('wpt_widget'); 
			wp_enqueue_style('wpt_widget');  
			if (empty($tabs)) $tabs = array('recent' => 1, 'popular' => 1);    
			$tabs_count = count($tabs);     
			if ($tabs_count <= 1) {       
				$tabs_count = 1;       
			} elseif($tabs_count > 3) {   
				$tabs_count = 4;      
			}			
	        
			$available_tabs = explode(",",$slugcat);	        
			?>	
			<?php echo $before_widget; ?>	
			<div class="wpt_widget_content" id="<?php echo $widget_id; ?>_content" data-widget-number="<?php echo esc_attr( $this->number ); ?>">
				<?php if($txtcat && $txtcat !=''): ?>	
				<div class="txtcat"><?php echo $txtcat; ?></div>
				<?php endif; ?>
				<?php 
				/*post type product*/
				if($posttype==2){ ?>
					<ul class="wpt-tabs <?php echo "has-$tabs_count-"; ?>tabs">
		                <?php foreach ($available_tabs as $key => $value) { 

		                	?>
		                        <li class="tab_title"><a href="#" id="<?php echo $key; ?>-tab"><?php 
		                        $idObj1= get_term_by('slug', $value, 'product_cat'); 
								/*$idObj1 = get_category_by_slug($value ); */
								if ( $idObj1 instanceof WP_Term ) {
								    echo $idObj1->name;
								}else{
									echo 'Slug not found';
								}							
		                        ?></a></li>		                    
		                <?php } ?> 
					</ul>
					<div class="clear"></div>  
					<div class="inside nbt-tabcontent">  
					      <?php foreach ($available_tabs as $key => $value) { ?>
					      	<div id="<?php echo $key; ?>-tab-content" class="tab-content">				
							</div>
					      <?php } ?>					
						<div class="clear"></div>
					</div> <!--end .inside -->	


				<?php
				}else {
				?>
					<ul class="wpt-tabs <?php echo "has-$tabs_count-"; ?>tabs">
		                <?php foreach ($available_tabs as $key => $value) { ?>	                   
		                        <li class="tab_title"><a href="#" id="<?php echo $key; ?>-tab"><?php 
								$idObj = get_category_by_slug($value ); 
								if ( $idObj instanceof WP_Term ) {
								    echo $idObj->name;
								}else{
									echo 'Slug not found';
								}							
		                        ?></a></li>		                    
		                <?php } ?> 
					</ul>
					<div class="clear"></div>  
					<div class="inside nbt-tabcontent">  
					      <?php foreach ($available_tabs as $key => $value) { ?>
					      	<div id="<?php echo $key; ?>-tab-content" class="tab-content row">				
							</div>
					      <?php } ?>					
						<div class="clear"></div>
					</div> <!--end .inside -->	
				<?php } ?>			
				<div class="clear"></div>
			</div><!--end #tabber -->			
			<script type="text/javascript">  
				jQuery(function($) {    
					$('#<?php echo $widget_id; ?>_content').data('args', <?php echo json_encode($instance); ?>);

				});  
			</script>  
			<?php echo $after_widget; ?>
			<?php 
		}  		
		 
		function ajax_wpt_widget_content() {     
			$tab = $_POST['tab'];
			$args = $_POST['args'];  
			$getposttype = $args['posttype'];

			$arrslugcat = explode(",",$args['slugcat']);			
	    	$number = intval( $_POST['widget_number'] );
			$page = intval($_POST['page']);    
			if ($page < 1)        
				$page = 1;

			if ( !is_array( $args ) || empty( $args ) ) { // json_encode() failed
				$wpt_widgets = new wpt_widget();
				$settings = $wpt_widgets->get_settings();

				if ( isset( $settings[ $number ] ) ) {
					$args = $settings[ $number ];
				} else {
					die( __('Unable to load tab content', 'wp-tab-widget') );
				}
			}
			/*posttype product*/
			if($getposttype==2){
				foreach ($arrslugcat as $key => $value) {

				    switch($tab){
				        case $key:			            
				        if($value){
				        	$catname1 = trim($value);
				        }else{
				        	$catname1 = '';
				        }
				        $args = array('post_type' => 'product', 'posts_per_page' => 8, 'product_cat'=>$catname1, 'status' => 'publish', 'orderby' => 'desc' );
						$loop = new WP_Query( $args );
						$showcount = $loop->post_count;	
							
						if ( $loop->have_posts() ) {
						echo '<div class="tabajax-products woocommerce">';											
							echo '<ul class="products">';
							while ( $loop->have_posts() ) : $loop->the_post();
							wc_get_template_part( 'content', 'product' );
							//$citem ++;
							endwhile;
							echo '</ul>';
						echo '</div>';
										
						}else{ 
							echo '<p>No product found</p>';
						}
				        break;
				    }
				} /*end foreach*/
			}else{
				foreach ($arrslugcat as $key => $value) {

				    switch($tab){
				        case $key:			            
				        if($value){
				        	$catname = trim($value);
				        }else{
				        	$catname = '';
				        }
				        $args = array( 'posts_per_page' => 6, 'category_name'=>$catname, 'status' => 'publish', 'orderby' => 'desc' );
						$loop = new WP_Query( $args );
						$showcount = $loop->post_count;	
							
						if ( $loop->have_posts() ) {											
							$citem=1;			
							while ( $loop->have_posts() ) : $loop->the_post();
							?>
							<div class="col-md-4 col-sm-6 col-xs-12 ca-block-recent <?php if($citem==4){ echo 'clearboth'; } ?>">
								
									<?php
									if( has_post_thumbnail( ) ) {
										echo '<div class="image-recent">';
										?>
										<a href="<?php the_permalink(); ?>">
										<?php
											the_post_thumbnail('cawptheme-pbcgroup-thumb');
										echo '</a>';
										echo '</div>';
									}
									?>								
									<div class="info-recent">
										<h3 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
										<?php
											if ( has_excerpt() ){
												echo '<div class="text-recent">';
												echo wp_trim_words( get_the_excerpt(), 26, '...' ); 
												echo '</div>';
											}
										?>
									</div>
								
							</div>
							<?php
							$citem ++;
							endwhile;
										
						}else{ 
							echo '<p>No post found</p>';
						}
				        break;
				    }
				} /*end foreach*/
			}            
			die(); // required to return a proper result  
		}
	    
	}
}
add_action( 'widgets_init', create_function( '', 'register_widget( "wpt_widget" );' ) );
?>