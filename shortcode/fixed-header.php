<div class="hainam-cart-fixed">
	<p class="product woocommerce add_to_cart_inline ">
		<del><span class="woocommerce-Price-amount amount">1,700,000<span class="woocommerce-Price-currencySymbol">₫</span></span></del> 
		<a rel="nofollow" href="#" class="button product_type_simple add_to_cart_button ajax_add_to_cart">Đặt mua</a>
	</p>
</div>
<style type="text/css">
	
.hainam-cart-fixed{background:#fff;text-align:right;border:1px solid #ccc!important;}
.hainam-cart-fixed .woocommerce-Price-amount{font-size:18px;color:#ed3f46;}
.hainam-cart-fixed del .woocommerce-Price-amount{color:#999;margin-right:10px;font-size:16px;}
.hainam-cart-fixed p{margin-bottom:0;border:none!important;}
.hainam-cart-fixed a.button{background:#da251c;color:#fff;margin-left:20px;border-radius:3px;}
.hainam-cart-fixed a.button:hover{background:#19abe0;color:#fff;}
.hainam-cart-fixed a.button:before{font-family:"FontAwesome";content:"\f07a";margin-right:10px;}
.hainam-cart-fixed.hainam-fixed{border-right:none;border-left:none;border-bottom:none;}
.hainam-fixed{position:fixed;width:100%;z-index:999;bottom:-1px;left:50%;transform:translateX(-50%);}
</style>
<script type="text/javascript">
	jQuery(document).ready(function(){
	/**
      * Fixed Header + Navigation.
      */
      ( function() {        
        jQuery('.hainam-cart-fixed').each(function(){ 
             var header_fixed = jQuery('.hainam-cart-fixed');
             var p_to_top     = header_fixed.position().top;

             jQuery(window).scroll(function(){
                 if(jQuery(document).scrollTop() > p_to_top) {
                     header_fixed.addClass('hainam-fixed');
                     jQuery('.hainam-cart-fixed > p').addClass('container');
                     header_fixed.stop().animate({},300);
                     /*if ( jQuery("body").hasClass('header-transparent') ) {
                         
                     } else {
                         jQuery('.site-content').css('padding-top', header_fixed.height());
                     }*/
                    
                 } else {
                     header_fixed.removeClass('hainam-fixed');
                     jQuery('.hainam-cart-fixed > p').removeClass('container');
                     header_fixed.stop().animate({},300);
                     /*if (jQuery("body").hasClass('header-transparent') ) {
                         
                     } else {
                         jQuery('.site-content').css('padding-top', '0');
                     }*/
                 }
             });     
             });    

     })();
    })
</script>