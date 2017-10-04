<?php
$classes = $attr = array();

        if ( ! empty( $slider ) ) {

            if($margin){
                
                $attr[] = '"margin": "' . ( int ) $margin . '"';
            }
            
            if ( $navigation ) {
                $attr[] = '"nav": "true"';
            }
            if ( $pagination ) {
                $attr[] = '"dots": "true"';
            }
            if ( ! empty( $columns ) ) {
                $attr[] = '"items": "' . ( int ) $columns . '"';
            }
            if ( ! empty( $columnstablet ) ) {
                $attr[] = '"ades": "' . ( int ) $columnstablet . '"';
            }

            
            if ( ! empty( $attr ) ) {
                $data_owlcarousel = 'data-owl-options=\'{' . esc_attr( implode( ', ', $attr ) ) . '}\'';
            }

            if ( $nav_position && $navigation) {
                $classes[] =' nav-'.$nav_position.' ';
            }

            $classes[] = 'edusite-carousel owl-carousel owl-loaded owl-drag';
        }
echo '<ul class="'. esc_attr( implode( ' ', $classes ) ) .'" '.$data_owlcarousel.'>';
echo '<li>item</li>';
echo '<li>item</li>';
echo '</ul>';
?>
<style type="text/css">
/* edusite-carousel */
.edusite-carousel .owl-nav .owl-prev,
.edusite-carousel .owl-nav .owl-next{ position: absolute; }
.nav-center.edusite-carousel .owl-nav .owl-prev,
.nav-center.edusite-carousel .owl-nav .owl-next{top: 33%;font-size: 23px;}

.nav-center.edusite-carousel .owl-nav .owl-prev{left: 0;}
.nav-center.edusite-carousel .owl-nav .owl-next{right: 0;}
.nav-bottom.edusite-carousel .owl-nav .owl-next{left: 30px;}

.edusite-carousel .owl-nav .fa-arrow-right, .edusite-carousel .owl-nav .fa-arrow-left{font-size: 18px;}
.edusite-carousel .owl-dots{margin-top: 15px;}
.edusite-carousel .owl-dots .owl-dot{width:8px;height:8px;background:#d7d7d7;display:inline-block;margin-right:5px;
 -webkit-border-radius: 50%; -moz-border-radius: 50%; -o-border-radius: 50%; border-radius: 50%;}
.edusite-carousel .owl-dots .owl-dot.active{background:#1BA590;}
.edusite-carousel .owl-dots{text-align:center;}
	
</style>