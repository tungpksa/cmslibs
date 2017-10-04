<div class="header-cart-search">
    <?php if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { ?>
	<?php $count = WC()->cart->cart_contents_count;?>
	<div class="cawp-minicart"><i class="fa fa-shopping-cart fa-2x"></i><span>Giỏ hàng</span></div>
	<a class="cart-contents" href="<?php echo esc_url(WC()->cart->get_cart_url()); ?>" title="<?php esc_html_e( 'View your shopping cart', 'cawptheme' ); ?>">
                                    
	<span><?php if ( $count > 0 ){echo intval($count).' sản phẩm' ;}else{ echo '0 sản phẩm';}  ?></span></a><?php } ?>
	<div class="widget_shopping_cart_content"></div>
</div>
<style type="text/css">
.header-cart-search .cart-contents{color:#ED3F46;font-weight:bold;}
.header-cart-search{float:left;position:relative;}
.header-cart-search .cart-contents span{border-radius:50%;color:#ed3f46;font-size:11px;font-family:Arial,Sans-serif;line-height:14px;min-height:15px;position:absolute;right:0px;text-align:center;top:20px;width:auto;padding-left:3px;min-width:16px;}
.header-cart-search .widget_shopping_cart_content{line-height:18px;width:250px;background:#ffffff;opacity:0;position:absolute;right:-67px;top:48px;transition:all 0.5s ease 0s;-webkit-transition:all 0.5s ease 0s;-moz-transition:all 0.5s ease 0s;-ms-transition:all 0.5s ease 0s;-o-transition:all 0.5s ease 0s;visibility:hidden;z-index:99;-webkit-transform:translate(-30%);-moz-transform:translate(-30%);-ms-transform:translate(-30%);-o-transform:translate(-30%);transform:translate(-30%);border:1px solid #e1e1e1;}
.header-cart-search .widget_shopping_cart_content p{margin:0;padding:10px;text-align:center;}
.header-cart-search .widget_shopping_cart_content p.buttons a{border-radius:2px;color:#fff;background-color:#ED3F46;display:inline-block;font-size:12px;font-weight:500;line-height:14px;margin:3px 5px 3px 0;padding:7px 12px 9px;text-align:center;text-decoration:none;text-transform:uppercase;}
.header-cart-search .widget_shopping_cart_content ul{list-style-type:none;padding-left:0;margin-left:0;}
.header-cart-search .widget_shopping_cart_content ul li{padding:10px;position:relative;border-bottom:1px solid #ececec;margin:0;clear:both;min-height:70px;}
.header-cart-search .widget_shopping_cart_content ul li a.remove{position:absolute;right:5px;top:5px;background:#f1f1f1 none repeat scroll 0 0;border-radius:50%;color:#ED3F46!important;float:right;line-height:11px;padding:3px 6px;width:18px;height:18px;font-size:14px;}
.header-cart-search .widget_shopping_cart_content ul li a img{width:50px;height:50px;margin-right:15px;float:left;}
.header-cart-search .widget_shopping_cart_content ul:after{right:10px;position:absolute;content:"";border-left:8px solid transparent;border-right:8px solid transparent;border-bottom:8px solid #fff;top:-7px}
.header-cart-search .widget_shopping_cart_content ul:before{right:10px;position:absolute;content:"";border-left:8px solid transparent;border-right:8px solid transparent;border-bottom:8px solid #ddd;top:-8px}
.header-cart-search:hover .widget_shopping_cart_content{opacity:1;transition:all 0.5s ease 0s;visibility:visible;}	
</style>