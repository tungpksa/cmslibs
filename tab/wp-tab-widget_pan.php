<?php
if ( !class_exists('wpt_widget') ) {
	class wpt_widget extends WP_Widget {
		function __construct() {	        
	        
			// ajax functions
			add_action('wp_ajax_wpt_widget_content', array(&$this, 'ajax_wpt_widget_content'));
			add_action('wp_ajax_nopriv_wpt_widget_content', array(&$this, 'ajax_wpt_widget_content'));
	        add_action('wp_enqueue_scripts', array(&$this, 'wpt_register_scripts'));	        

			$widget_ops = array('classname' => 'widget_wpt', 'description' => __('Display posts by category in tabbed format.', 'wptravel'),
				'panels_groups' => array('netbaseteam')
				);
			
			parent::__construct(
				'wpt_widget', 
				__('NBT Tabs Post or Jomres', 'wptravel'), $widget_ops);
	    }
	    
	    function wpt_register_scripts() { 
			// JS    
			wp_register_script('wpt_widget', plugins_url('assests/js/wp-tab-widget.js', __FILE__), array('jquery'));     
			wp_localize_script( 'wpt_widget', 'wpt',         
				array( 'ajax_url' => admin_url( 'admin-ajax.php' )) 
			);
	    }  
	    	
		function form( $instance ) {
			extract($instance);
			if ( isset( $instance[ 'styleflayout' ] ) ){
                $styleflayout = $instance[ 'styleflayout' ];
        	}else{ $styleflayout='blog' ;}

        	$postnumb = empty( $instance['postnumb'] ) ? 10 : $instance['postnumb'];
        	
			?>
	        <div class="wpt_options_form">  
	        	<div class="wpt_select_tabs">
					<label for="<?php echo $this->get_field_id('txtcat'); ?>">
                        <?php _e('Text Before Tabs', 'wptravel'); ?>
							<br />
					<input type="text" id="<?php echo $this->get_field_id('txtcat'); ?>" name="<?php echo $this->get_field_name('txtcat'); ?>" value="<?php echo $txtcat; ?>" />
				
				</div>
				<div class="wpt_select_tabs">
		            <label for="<?php echo $this->get_field_id('styleflayout'); ?>"><?php _e( 'Select Type', 'wptravel' ); ?>:</label>
		            <select class='widefat' id="<?php echo $this->get_field_id('styleflayout'); ?>" name="<?php echo $this->get_field_name('styleflayout'); ?>" type="text">
		                <option value='blog'<?php echo ($styleflayout=='blog')?'selected':''; ?>>
		                    <?php _e( 'Blog', 'wptravel' ); ?>
		                  </option>
		                <option value='services'<?php echo ($styleflayout=='services')?'selected':''; ?>>
		                    <?php _e( 'Services', 'wptravel' ); ?>
		                </option>
		                <option value='jomres'<?php echo ($styleflayout=='jomres')?'selected':''; ?>>
		                    <?php _e( 'Jomres', 'wptravel' ); ?>
		                </option>
		                
		            </select>
		        </div>
	        
	        	<div class="wpt_select_tabs">
					<label for="<?php echo $this->get_field_id('slugcat'); ?>">
							<?php _e('BLog Slug Category:', 'wptravel'); ?>
							<?php _e('Enter the slugs separated by commas (ex: slug1,slug2,slug3) ', 'wptravel'); ?>
							
							<br />
					<input type="text" style="width: 100%" id="<?php echo $this->get_field_id('slugcat'); ?>" name="<?php echo $this->get_field_name('slugcat'); ?>" value="<?php echo $slugcat; ?>" />
				
				</div>
				<div class="wpt_select_tabs">
			        <label for="<?php echo $this->get_field_id('postnumb'); ?>"><?php _e('Posts per page:', 'wpnetbase'); ?></label>
			        <input type="number" class="widefat" id="<?php echo $this->get_field_id('postnumb'); ?>"
			               name="<?php echo $this->get_field_name('postnumb'); ?>" value="<?php echo $instance['postnumb']; ?>"/>
			    </div>
				<p style="font-weight: bold;"><?php _e('Property Jomres:', 'wptravel'); ?></p>
				<div class="wpt_select_tabs">
					<label for="<?php echo $this->get_field_id('titleptype'); ?>">
							<?php _e('Title tabs Propery type corresponding with Propery type ID:', 'wptravel'); ?><br />
							<?php _e('Enter the ptype id separated by commas (ex: ptype id1,ptype id2,ptype id3) ', 'wptravel'); ?>
							
							<br />
					<input type="text" id="<?php echo $this->get_field_id('titleptype'); ?>" name="<?php echo $this->get_field_name('titleptype'); ?>" style="width: 100%" value="<?php echo $titleptype; ?>" />
				
				</div>
		        <div class="wpt_select_tabs">
					<label for="<?php echo $this->get_field_id('ptype'); ?>">
							<?php _e('Propery type ID:', 'wptravel'); ?><br />
							<?php _e('Enter the ptype id separated by commas (ex: ptype id1,ptype id2,ptype id3) ', 'wptravel'); ?>
							
							<br />
					<input type="text" id="<?php echo $this->get_field_id('ptype'); ?>" name="<?php echo $this->get_field_name('ptype'); ?>" style="width: 100%" value="<?php echo $ptype; ?>" />
				
				</div>
			</div>
			<?php 
		}	
		
		function update( $new_instance, $old_instance ) {	
			$instance = $old_instance;    
			$instance['tabs'] = $new_instance['tabs'];
			$instance['slugcat'] = $new_instance['slugcat'];
			$instance['ptype'] = $new_instance['ptype'];
			$instance['titleptype'] = $new_instance['titleptype'];
			
			
			$instance['txtcat'] = $new_instance['txtcat'];				
			$instance['postnumb'] = ! absint( $new_instance['postnumb'] ) ? 10 : $new_instance['postnumb'];			
			$instance['styleflayout'] = ( ! empty( $new_instance['styleflayout'] ) ) ? strip_tags( $new_instance['styleflayout'] ) : 'blog';
					
			return $instance;	
		}	
		function widget( $args, $instance ) {	
			
			extract($args);     
			extract($instance); 

			$slugcat= $instance['slugcat'];

			if(isset($instance['ptype'])){
				$ptype =$instance['ptype'];
			}else{
				$ptype='';
			}
			if(isset($instance['titleptype'])){
				$titleptype=$instance['titleptype'];	
			}else{
				$titleptype='';
			}
			

			if ( isset( $instance[ 'styleflayout' ] ) ){
                $styleflayout = $instance[ 'styleflayout' ];
        	}else{ $styleflayout='blog' ;}
			$postnumb = empty( $instance['postnumb'] ) ? 10 : $instance['postnumb'];

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


			$av_tabs = explode(",", $ptype);
			
			$title_jomres_tab = array();
			if(isset($titleptype) && $titleptype!=''){
				$title_jomres_tab = explode(",", $titleptype);	
			}
			

			$countav= count($av_tabs);
			$counttj = count($title_jomres_tab);

			if($counttj < $countav){
				$ac= $countav - $counttj;
				for($i=0; $i <= $ac; $i++ ){
					array_push($title_jomres_tab,"tab title");
				}
			}


			echo $before_widget; 
			?>	
			<?php
			if($styleflayout=='jomres'){
				?>
				<div class="wpt_widget_content" id="<?php echo $widget_id; ?>_content" data-widget-number="<?php echo esc_attr( $this->number ); ?>">
					<?php if($txtcat && $txtcat !=''): ?>	
					<div class="txtcat"><?php echo $txtcat; ?></div>
					<?php endif; ?>
					<ul class="wpt-tabs <?php echo "has-$tabs_count-"; ?>tabs">
		                <?php 
		                $ititle=0;
		                foreach ($title_jomres_tab as $key => $value1) { 
		                	if($ititle< $countav){
		                ?>

		                    <li class="tab_title"><a href="#" id="<?php echo $key.$widget_id; ?>-tab">
		                        <?php 
								echo $value1;							
		                        ?></a>
		                    </li>			                    
		                <?php 
		            		} 

		                $ititle++; 
		                } ?> 
					</ul>
					<div class="clear"></div>  
					<div class="inside nbt-tabcontent">  
					      <?php foreach ($av_tabs as $key => $value) { ?>
					      	<div id="<?php echo $key.$widget_id; ?>-tab-content" class="tab-content row nbt-lstprop gird">				
							</div>
					      <?php } ?>					
						<div class="clear"></div>
					</div> <!--end .inside -->				
					<div class="clear"></div>
				</div><!--end #tabber -->
			<?php

			}
			else{
			?>			
				<div class="wpt_widget_content" id="<?php echo $widget_id; ?>_content" data-widget-number="<?php echo esc_attr( $this->number ); ?>">
					<?php if($txtcat && $txtcat !=''): ?>	
					<div class="txtcat"><?php echo $txtcat; ?></div>
					<?php endif; ?>
					<ul class="wpt-tabs <?php echo "has-$tabs_count-"; ?>tabs">
		                <?php foreach ($available_tabs as $key => $value) { ?>
		                    <?php //if (!empty($tabs[$tab])): ?>
		                        <li class="tab_title"><a href="#" id="<?php echo $key.'-'.$widget_id; ?>-tab"><?php 
								$idObj = get_category_by_slug($value ); 
								if ( $idObj instanceof WP_Term ) {
								    echo $idObj->name;
								}else{
									echo 'Slug not found';
								}							
		                        ?></a></li>	
		                    <?php //endif; ?>
		                <?php } ?> 
					</ul>
					<div class="clear"></div>  
					<div class="inside nbt-tabcontent">  
					      <?php foreach ($available_tabs as $key => $value) { ?>
					      	<div id="<?php echo $key.'-'.$widget_id; ?>-tab-content" class="tab-content">				
							</div>
					      <?php } ?>					
						<div class="clear"></div>
					</div> <!--end .inside -->				
					<div class="clear"></div>
				</div><!--end #tabber -->
			<?php } ?>
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

			$styleflayout = $args['styleflayout'];
			$postnumb = $args['postnumb'];

			$arrslugcat = explode(",",$args['slugcat']);	

			$arrptype = explode(",", $args['ptype']);		

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
					die( __('Unable to load tab content', 'wptravel') );
				}
			}

			if($styleflayout=='jomres'){
				global $wpdb;
				
				foreach ($arrptype as $key => $value) {
					switch ($tab) {
						case $key:
							/*if($value){
				        		$ptype = trim($value);
					        }else{
					        	$ptype = '';
					        }*/
					          
					        $tablename= $wpdb->prefix."jomres_propertys";
					        $results = array();
					        
					        $query = "
					            SELECT 
					                propertys_uid,property_name,property_street,ptype_id,property_town,	property_country,property_description
					            FROM 
					                $tablename
					            WHERE
					            published = '1' AND ptype_id LIKE '%d'
					                
					            LIMIT 0, %d
					                ";

					        $query = ( $wpdb->prepare($query, $value, $postnumb));
					        $results = $wpdb->get_results( $query );
					        if(sizeof($results) > 0 && is_array($results) && !is_wp_error($results)){
					        	foreach($results as $result){
					        		
					        		$p1=substr(ABSPATH, 0, -1);
					        		$nbt_base_path= $p1.DIRECTORY_SEPARATOR.'jomres'.DIRECTORY_SEPARATOR.'uploadedimages'.DIRECTORY_SEPARATOR.$result->propertys_uid.DIRECTORY_SEPARATOR.'property'.DIRECTORY_SEPARATOR.'0'.DIRECTORY_SEPARATOR.'medium';
					        		if (file_exists($nbt_base_path))
									{
					        			$imgname = scandir($nbt_base_path);
					        		}else{
					        			$imgname[2]='no-img';
					        		}
					        		if($imgname[2]!='no-img'){
					        			$nbt_rel_path = home_url().'/jomres/uploadedimages/'.$result->propertys_uid.'/property/0/medium/'.$imgname[2];
					        		}else{
					        			$nbt_rel_path = plugins_url( 'netbase-wptravel-widgets/assests/images/no-image.png', dirname(__FILE__) );
					        		}

					        	?>
					        	
					        	<div class="jomres_property_list_propertywrapper photo-view col-xs-12 col-sm-6 col-md-6 col-lg-4 appear id="35" data-animated="zoomIn" data-start="0">
						        	<div class="panel  panel-default">
							        	<div class="nbpanel-body">
								        	<div class="property-pic-wrapper"> 
								        		<a href="<?php echo home_url().'/bookings/?option=com_jomres&amp;task=dobooking&amp;selectedProperty='.$result->propertys_uid; ?>">
								        		<img src="<?php echo $nbt_rel_path?>" class="responsive img-responsive" alt="property image">
								        		</a>
								        		<div class="booking-instant">
								        			<div class="wap-label">Instant booking</div>
								        		</div>
								        		<div class="average-rating"><i class="fa fa-thumbs-o-up"></i> <?php echo nbt_showRating($result->propertys_uid); ?></div>								        		
								        	</div><p class="visible-md visible-lg">&nbsp;</p>
								        		<div class="col-md-12 pd0 nbt-title-info">
									        		<h2 class="page-header">
									        		<a href="<?php echo home_url().'/bookings/?option=com_jomres&amp;task=dobooking&amp;selectedProperty='.$result->propertys_uid; ?>">
									        			<?php echo $result->property_name; ?>									        			
									        		</a>
									        		</h2>
									        		<p class="prop-address"><?php echo $result->property_street.', '. $result->property_town.', '. $result->property_country; ?></p>  
									        		<?php 
													if ( $result->property_description ){
													echo '<div class="nbt-prodes clear">';
													echo wp_trim_words( $result->property_description, 16, '...' ); 
													echo '</div>';
													}
													?>								        		

								        		</div>
							        	</div>
						        	</div>
					        	</div>
					        		

					        	<?php	


					        	} 
					        }



							break;
						
						
					}
				}

			}else{
				foreach ($arrslugcat as $key => $value) {
				    switch($tab){
				        case $key:
				            //print 'Variable $x tripped switch: '.$value.'<br>';
				        if($value){
				        	$catname = trim($value);
				        }else{
				        	$catname = '';
				        }
				        if(isset($postnumb) && !empty($postnumb)){
				        	$args = array( 'posts_per_page' => $postnumb, 'category_name'=>$catname, 'status' => 'publish', 'orderby' => 'desc' );
				        }else{
				        	$args = array( 'posts_per_page' => 10, 'category_name'=>$catname, 'status' => 'publish', 'orderby' => 'desc' );	
				        }
				        
						$loop = new WP_Query( $args );
						$showcount = $loop->post_count;
						if ( $loop->have_posts() ) {

							if(isset($styleflayout) && $styleflayout=='services'){
								
								while ( $loop->have_posts() ) : $loop->the_post();
							?>
								<div class="col-md-4 col-xs-6 col-sp-12 tab-style-services pd0"> 
									
										<?php if ( has_post_thumbnail() ) {  
											the_post_thumbnail('wppandora-post-service');  
										} ?>
										
									<div class="articles-desc"> 
										<div class="tab-services-wap">
											<div class="nb1">
												<a href="<?php the_permalink(); ?>" class="title"><?php the_title();?></a>									
											<?php 
											if ( has_excerpt() ){
													echo '<div class="articles-intro"> ';
													echo wp_trim_words( get_the_excerpt(), 25, '...' );
													echo '</div>';
												}
											?>	
											<a href="<?php the_permalink(); ?>" class="btnreadnow"><span><?php echo __('Read Now!', 'wptravel')?></span></a>
											</div>
																				
										</div>									
									</div>
									
								</div>
							<?php
								endwhile;
							}else{

								$numc = 1;										
								while ( $loop->have_posts() ) : $loop->the_post();
								
								if($numc == 1){ ?>
								<div class="col-md-6 wpt-left">									
								<?php } ?>
									<?php if($numc < 3){ ?>
									<div class="col-md-12 wpt-left-items">	
										<?php if ( has_post_thumbnail() ) { ?>
											<div class="entry-media">
												<a href="<?php the_permalink(); ?>">
												<?php the_post_thumbnail('wppandora-post-service'); ?>
												</a>
											</div>
										<?php } ?>
										<h3 class="grid-title"><a href="<?php the_permalink(); ?>"><?php the_title();?></a></h3>
										<div class="info">
											<?php 
											if(get_the_author()){								
												echo '<span class="author">';	
												echo esc_html( get_the_author() ); 
												echo '</span>';
											}
											echo '<span class="txt-date">';
												the_time('M j, Y') ;
											echo '</span>';									
											if ( has_excerpt() ){
												echo '<p class="wpt-excerpt">';
												echo wp_trim_words( get_the_excerpt(), 25, '...' );
												echo '</p>';
											}
											?>
										</div>	
									</div>
									<?php } ?>
								<?php if($numc == 2){ ?>	
								</div>
								<?php } ?>	
								<?php if($numc == 3){ ?>	
								<div class="col-md-6 col-sm-12 wpt-right">	
								<?php } ?>
									<?php if($numc > 2){ ?>	
									<div class="col-md-6 col-sm-6 col-xs-6 col-sp-12 wpt-right-items <?php if($numc==$showcount ||$numc== $showcount-1){echo 'item-last';}?> <?php if($numc %2 == 0){ echo 'item-even'; }else{echo 'item-odd';} ?>">	
											<?php if ( has_post_thumbnail() ) { ?>
												<div class="entry-media">
													<a href="<?php the_permalink(); ?>">
													<?php the_post_thumbnail('wppandora-port-thumb'); ?>
													</a>
												</div>
											<?php } ?>
											<h3 class="grid-title">
		                                        <a href="<?php the_permalink(); ?>">
		                                            <?php echo wp_trim_words( get_the_title(), 8, '...' );
		                                            ?>
		                                        </a>
		                                    </h3>
											<div class="info">
												<?php
		                                        if(get_the_author()){
		                                            echo '<span class="author">';
		                                            echo esc_html( get_the_author() );
		                                            echo '</span>';
		                                        }
		                                        echo '<span class="wpt-date">';
												the_time('M j, Y') ;
												echo '</span>';

												?>		
											</div>	
									</div>
									<?php } ?>	
								<?php if($numc == $showcount){ ?>	
								</div>	
								<?php } ?>
								<?php
								$numc ++;
								endwhile;
							}
										
						}else{ 
							echo '<p>No post found</p>';
										
						}
				        break;
				    }
				} 
			}           
			die(); // required to return a proper result  
		}
	}
}
add_action( 'widgets_init', create_function( '', 'register_widget( "wpt_widget" );' ) );
?>