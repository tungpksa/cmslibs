<div id="btt"><i class="fa fa-chevron-up"></i><?php echo esc_html__( 'Top', 'wppandora' ); ?></div> 

<style type="text/css">
#btt { -webkit-border-radius: 5px; -moz-border-radius: 5px; -o-border-radius: 5px; border-radius: 5px; bottom: 40px; cursor: pointer; position: fixed; right: 15px; z-index: 50; color: #fff; text-align: center; width: 40px; height: 40px; }
#btt i { font-size: 10px; display: block; margin-top: 4px; font-weight: normal; }
</style>
<script type="text/javascript">
jQuery(document).ready(function () {
	/**
     * Back To Top
     */
    ( function() {
        jQuery('#btt').fadeOut();
        jQuery(window).scroll(function() {
            if(jQuery(this).scrollTop() != 0) {
                jQuery('#btt').fadeIn();
            } else {
                jQuery('#btt').fadeOut();
            }
        });

        jQuery('#btt').click(function() {
            jQuery('body,html').animate({scrollTop:0},800);
        });
    })();
})
</script>