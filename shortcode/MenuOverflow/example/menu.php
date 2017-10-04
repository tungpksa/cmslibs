<?php 
echo '<div class="header-right-widgets col-md-5 col-xs-12 pdr-0">';
if ( has_nav_menu( 'wppandora-topbar-menu' ) ) {
    wp_nav_menu( array( 'theme_location' => 'wppandora-topbar-menu' ) );
} 
echo '</div>';
/*Menu Overflow function*/
add_filter( 'wp_nav_menu_items', 'wppandora_add_menu_overflow', 10, 2 );
function wppandora_add_menu_overflow( $items, $args ) {
    if ($args->theme_location == 'wppandora-topbar-menu') {
        $items .= '<li class="more"> <span>...</span><ul id="overflow"></ul></li>';
    }
    
    return $items;
}
/*End Menu Overflow*/
?>
<style type="text/css">
	
.header-right-widgets ul.menu li.more {  
  display: none; position: relative; float: right;
}
.header-right-widgets #overflow {
  display: none;
  position: absolute;
  text-align: left;
  -webkit-transition-duration: .3s;
  transition-duration: .3s;
  z-index: 99;
  background: #fff;
  min-width: 160px;
  right: 0;     border: 1px solid #e1e1e1; padding-right: 15px; padding-left: 15px;

}
.header-right-widgets #overflow li{ text-align: right; line-height: 32px;}
.header-right-widgets ul.menu li.more span {
  color: #fff;
  text-decoration: none;
  padding: 0px 8px 10px;
  cursor: pointer;
  -webkit-transition-duration: .3s;
  transition-duration: .3s;
  line-height: 18px;
  display: inline-block;      
  vertical-align: middle;
  -webkit-border-radius: 3px;
  -moz-border-radius: 3px;
  -o-border-radius: 3px;
  border-radius: 3px;
  margin-left: 10px; font-size: 20px;
}
 
.header-right-widgets #overflow ul{position: static; border: none;}
.header-right-widgets #overflow ul{width: 100%;}
.header-right-widgets #overflow ul li{list-style: none; clear: both;}
.header-right-widgets #overflow ul li:before{content: none !important;}
.header-right-widgets #overflow a {
  padding: 3px 0; width: 100%;
}

.header-right-widgets #overflow .menu > .menu-item:not(:first-child) > a {
  border-top: 1px #eceeef solid;
}
 .header-right-widgets #overflow .menu-item-has-children > a:after {
  float: right;
  content: '\f107';
  font-family: 'FontAwesome';
} 

.header-right-widgets #overflow .menu-item-expanded > a:after {
  content: '\f106';
} 

.header-right-widgets #overflow .menu > .menu-item > .sub-menu {
  padding-bottom: 1rem;
}

.header-right-widgets #overflow .menu .menu-item:not(.menu-item-has-children) > a:after {
  float: right;
  visibility: hidden;
  opacity: 0;
  transform: translateX(-100%);
  transition: all .2s ease;
}

.header-right-widgets #overflow .menu .menu-item:not(.menu-item-has-children) > a:hover:after {
  visibility: visible;
  opacity: 1;
  transform: translateX(0);
}

.header-right-widgets #overflow .sub-menu {
  display: none;
}

.header-right-widgets #overflow .sub-menu a {
  padding: .25rem 0;  
}

.header-right-widgets #overflow .sub-menu .sub-menu {
  padding: .5rem 0;
}

.header-right-widgets #overflow .sub-menu .sub-menu a {
  padding-left: 1rem;
}

.header-right-widgets #overflow .submenu-visible {
  display: block;
}

@media screen and (max-width: 991px){
  .header-right-wrap-top li.menu-item {
      margin: 0;      
      width: 100%;
  }  
  .header-right-wrap-top li.menu-item a{line-height: 32px;}
}

</style>
<script type="text/javascript">
	jQuery(document).ready(function () {

	    /*Menu Overflow*/
	    if(jQuery('.header-right-widgets ul.menu li.more span').length>0){
	        window.onresize = navigationResize;
	        navigationResize();

	        function navigationResize() {  
	          jQuery('.header-right-widgets ul.menu li.more').before(jQuery('#overflow > li'));
	          
	          var $navItemMore = jQuery('.header-right-widgets ul.menu > li.more'),
	                $navItems = jQuery('.header-right-widgets ul.menu > li:not(.more)'),
	              navItemMoreWidth = navItemWidth = $navItemMore.width(),
	              windowWidth = jQuery('.header-right-widgets').width(),
	              navItemMoreLeft, offset, navOverflowWidth;
	          
	          $navItems.each(function() {
	            navItemWidth += jQuery(this).width();
	          });
	          
	          navItemWidth > windowWidth ? $navItemMore.show() : $navItemMore.hide();
	            
	          while (navItemWidth > windowWidth) {
	            navItemWidth -= $navItems.last().width();
	            $navItems.last().prependTo('#overflow');
	            $navItems.splice(-1,1);
	          }
	          
	          navItemMoreLeft = jQuery('.header-right-widgets ul.menu .more').offset().left;
	          navOverflowWidth = jQuery('#overflow').width();  
	          offset = navItemMoreLeft + navItemMoreWidth - navOverflowWidth;
	            
	          /*jQuery('#overflow').css({
	            'left': offset
	          });*/
	        } 
	        jQuery(".header-right-widgets #overflow .menu-item-has-children > a").click(function(b) {
	            b.preventDefault(), jQuery(this).responsiveNav()
	        });
	        jQuery('.header-right-widgets ul.menu li.more span').click(function () {
	            jQuery('.header-right-widgets ul.menu li.more #overflow').slideToggle();
	        });
	    }
	    /*End Menu Overflow*/
	})
</script>