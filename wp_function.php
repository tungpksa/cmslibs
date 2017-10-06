WP Function

$home_path = function_exists('get_home_path') ? get_home_path() : ABSPATH;
$htaccess_file = $home_path . '.htaccess';
$mod_rewrite_enabled = function_exists('got_mod_rewrite') ? got_mod_rewrite() : false;
***********

Skip Cart Go Straight to Checkout Page in WooCommerce
WooCommerce workflow can be a little too long for simple products, here’s how to get the product to skip past the cart page and go straight to the checkout page.

First uncheck the cart options in WooCommerce Settings -> Products.

Then add in your functions.php
<?php
add_filter('add_to_cart_redirect', 'themeprefix_add_to_cart_redirect');
function themeprefix_add_to_cart_redirect() {
 global $woocommerce;
 $checkout_url = $woocommerce->cart->get_checkout_url();
 return $checkout_url;
}
?>
That’s it now when you click a product add to cart it will go to checkout.
Now since the cart is gone we should change the ‘Add to Cart’ label in the WooCommerce product to something a bit more immediate like ‘Pay Now’. WooCommerce has a filter for that too. This is also added to your functions.php

<?php
//Add New Pay Button Text
add_filter( 'woocommerce_product_single_add_to_cart_text', 'themeprefix_cart_button_text' ); 
 
function themeprefix_cart_button_text() {
 return __( 'Pay Now', 'woocommerce' );
}
?>

The above filter applies the text to the single product page, however you may have the product on another post type, there is a 2nd filter you can also use – woocommerce_product_add_to_cart_text

<?php 
//Add New Pay Button Text
add_filter( 'woocommerce_product_single_add_to_cart_text', 'themeprefix_cart_button_text' ); 
add_filter( 'woocommerce_product_add_to_cart_text', 'themeprefix_cart_button_text' ); 
 
function themeprefix_cart_button_text() {
 return __( 'Pay Now', 'woocommerce' );
}
?>

***********

public static function list_categories( $args = '' ) {
        $defaults = array(
            'echo' => 1,
        );

        $r = wp_parse_args( $args, $defaults );

        $categories = get_terms( 'course_category' );

        if ( empty( $categories ) ) {
            return '';
        }

        $output = '';
        foreach ( $categories as $category ) {
            $category_id = ! empty( $category->term_id ) ? $category->term_id : '';
            $output .= sprintf( '<li class="cat-item cat-item-%s" data-slug="%s"><a href="%s">%s</a></li>',
                $category_id,
                ! empty( $category->slug ) ? $category->slug : '',
                get_term_link( $category_id, 'course_category' ),
                ! empty( $category->name ) ? $category->name : ''
            );
        }

        if ( $r['echo'] ) {
            echo $output;
        } else {
            return $output;
        }
    }

***********

<?php

// Register the script
wp_register_script( 'some_handle', 'path/to/myscript.js' );

// Localize the script with new data
$translation_array = array(
    'some_string' => __( 'Some string to translate', 'plugin-domain' ),
    'a_value' => '10',
    'template_url' => get_stylesheet_directory_uri()
);
wp_localize_script( 'some_handle', 'object_name', $translation_array );

// Enqueued script with localized data.
wp_enqueue_script( 'some_handle' );

You can access the variables in JavaScript as follows:

<script>
// alerts 'Some string to translate'
alert( object_name.some_string);
</script> 

***********

One use case for this is to pass variable to template file when calling it with get_template_part().

// When calling a template with get_template_part()
set_query_var('my_form_id', 23);
get_template_part('my-form-template');
Now in you template you can then call it.

// Inside my-form-template.php
$my_form_id = get_query_var('my_form_id');

***********

For this purpose there is get_term_link function (documentation).

<a href="<?php echo get_term_link( 42 ,'product_cat') ?>">Fine Art ... etc.</a>
Product category is just WP taxonomy, so there is plenty of functions to work with. In this case you have to know your product category ID (taxonomy term ID, actually). When editing category, you will find it in URL: .../edit-tags.php?action=edit&taxonomy=product_cat&tag_ID=42&post_type=product

***********
For example, the format string:

l, F j, Y
creates a date that look like this:

Friday, September 24, 2004

Here is what each format character in the string above represents:

l = Full name for day of the week (lower-case L).
F = Full name for the month.
j = The day of the month.
Y = The year in 4 digits. (lower-case y gives the year's last 2 digits)

Day of Month
d   Numeric, with leading zeros 01–31
j   Numeric, without leading zeros  1–31
S   The English suffix for the day of the month st, nd or th in the 1st, 2nd or 15th.
Weekday
l   Full name  (lowercase 'L')  Sunday – Saturday
D   Three letter name   Mon – Sun
Month
m   Numeric, with leading zeros 01–12
n   Numeric, without leading zeros  1–12
F   Textual full    January – December
M   Textual three letters   Jan - Dec
Year
Y   Numeric, 4 digits   Eg., 1999, 2003
y   Numeric, 2 digits   Eg., 99, 03
Time
a   Lowercase   am, pm
A   Uppercase   AM, PM
g   Hour, 12-hour, without leading zeros    1–12
h   Hour, 12-hour, with leading zeros   01–12
G   Hour, 24-hour, without leading zeros    0-23
H   Hour, 24-hour, with leading zeros   00-23
i   Minutes, with leading zeros 00-59
s   Seconds, with leading zeros 00-59
T   Timezone abbreviation   Eg., EST, MDT ...
Full Date/Time
c   ISO 8601    2004-02-12T15:19:21+00:00
r   RFC 2822    Thu, 21 Dec 2000 16:01:07 +0200
U   Unix timestamp (seconds since Unix Epoch)   1455880176
Examples
Here are some examples of date format and result output.

F j, Y g:i a - November 6, 2010 12:50 am
F j, Y - November 6, 2010
F, Y - November, 2010
g:i a - 12:50 am
g:i:s a - 12:50:48 am
l, F jS, Y - Saturday, November 6th, 2010
M j, Y @ G:i - Nov 6, 2010 @ 0:50
Y/m/d \a\t g:i A - 2010/11/06 at 12:50 AM
Y/m/d \a\t g:ia - 2010/11/06 at 12:50am
Y/m/d g:i:s A - 2010/11/06 12:50:48 AM
Y/m/d - 2010/11/06
Combined with the_time() template tag, the code below in the template file:

This entry was posted on <?php the_time('l, F jS, Y') ?> and is filed under <?php the_category(', ') ?>.
will be shown on your site as following:

This entry was posted on Friday, September 24th, 2004 and is filed under WordPress and WordPress Tips.


***********

function getsidebar($args,$content){
    ob_start();
    if(isset($args['sidebarid'])){
        if ( is_active_sidebar( $args['sidebarid'] )){
        dynamic_sidebar($args['sidebarid']);    
        }   
    }
    $nbsidebar = ob_get_contents();
    ob_end_clean();    
    return $nbsidebar;
    
}
add_shortcode( 'sidebar_widget', 'getsidebar' );


***********
<?php
 
$args = array(
    // Arguments for your query.
);
 
// Custom query.
$query = new WP_Query( $args );
 
// Check that we have query results.
if ( $query->have_posts() ) {
 
    // Start looping over the query results.
    while ( $query->have_posts() ) {
 
        $query->the_post();
 
        // Contents of the queried post results go here.
 
    } 
}
 
// Restore original post data.
wp_reset_postdata();
 
?>

***********

Post thumbnail sizes:
//Default WordPress
the_post_thumbnail( 'thumbnail' );     // Thumbnail (150 x 150 hard cropped)
the_post_thumbnail( 'medium' );        // Medium resolution (300 x 300 max height 300px)
the_post_thumbnail( 'medium_large' );  // Medium Large (added in WP 4.4) resolution (768 x 0 infinite height)
the_post_thumbnail( 'large' );         // Large resolution (1024 x 1024 max height 1024px)
the_post_thumbnail( 'full' );          // Full resolution (original size uploaded)
 
//With WooCommerce
the_post_thumbnail( 'shop_thumbnail' ); // Shop thumbnail (180 x 180 hard cropped)
the_post_thumbnail( 'shop_catalog' );   // Shop catalog (300 x 300 hard cropped)
the_post_thumbnail( 'shop_single' );    // Shop single (600 x 600 hard cropped)

<?php if ( has_post_thumbnail() ) : ?>
    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
        <?php the_post_thumbnail(); ?>
    </a>
<?php endif; ?>

add_action('init', 'wpnetbase_owl_pc_thumb');
function wpnetbase_owl_pc_thumb() {
    add_image_size('owl-pc-thumb', 362, 248, array('center', 'center'));
}

***********

add_action( 'woocommerce_after_add_to_cart_button', 'inoplugs_price_container', 50 );

function inoplugs_price_container()
{
echo "<div class='price_container'>";
woocommerce_template_single_price($post, $product);
echo "</div>";
}
***********
add_filter('loop_shop_columns', 'loop_columns');
if (!function_exists('loop_columns')) {
 function loop_columns() {
  return 4;
 }
}

***********
// Get the ID of a given category
$category_id = get_cat_ID( 'Category Name' );

// Get the URL of this category
$category_link = get_category_link( $category_id );

<!-- Print a link to this category -->
<a href="<?php echo esc_url( $category_link ); ?>" title="Category Name">Category Name</a>

***********
esc_url
function esc_url( $url, $protocols = null, $_context = 'display' ) {
    $original_url = $url;
 
    if ( '' == $url )
        return $url;
 
    $url = str_replace( ' ', '%20', $url );
    $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\[\]\\x80-\\xff]|i', '', $url);
 
    if ( '' === $url ) {
        return $url;

***********
Create widgets:

function wpnetbase_widgets_print_boxed() {
    register_sidebar( array(
        'name'          => __( 'Search bottom header', 'wpnetbase' ),
        'id'            => 'search-bottom-header',
        'description'   => '',
        'before_widget' => '<div class="search-bottom-header">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
        ) );
    register_sidebar( array(
        'name'          => __( 'Vertical menu', 'wpnetbase' ),
        'id'            => 'vertical-menu',
        'description'   => '',
        'before_widget' => '<div class="vertical-menu">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
        ) );
    }

add_action( 'widgets_init', 'wpnetbase_widgets_print_boxed' );

Call:

dynamic_sidebar('vertical-menu');

***********
echo wp_kses(__('<span class="nbt-sticky-post">Sticky</span>', 'wptravel'), array('span' => array('class' => array())));
'prev_text' =>  wp_kses(__('<i class=\'fa fa-chevron-left\'></i>', 'wptravel' ), array('i' => array('class' => array()))),
esc_html__('Enter your Pinterest URL.', 'wptravel'),

***********
Get current page URL in WordPress
     
global $wp;
$current_url = home_url(add_query_arg(array(),$wp->request));

***********
Validation

if ( has_excerpt() ){
    echo '<p class="excerpt">';
    echo wp_trim_words( get_the_excerpt(), 15, '...' ); 
    echo '</p>';
}
if ( has_post_thumbnail() ) {
    the_post_thumbnail('owl-pc-thumb');
}

if(get_the_author()){                               
    echo '<span class="author">';                               
    echo get_avatar( $user_id, $size='45' );                        
    echo esc_html( get_the_author() ); 
    echo '</span>';
}
if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
    echo '<span class="comments-link">'. wp_kses(__(' <i class="fa fa-comments-o"></i> ', 'wpnetbase'), array('i' => array('class' => array())));
    comments_popup_link( esc_html__( '0', 'wpnetbase' ), esc_html__( '1', 'wpnetbase' ), esc_html__( '%', 'wpnetbase' ) );
    echo '</span>';
}

if ( has_nav_menu( 'primary' ) ) {  
    wp_nav_menu( array('theme_location' => 'primary', 'container' => '', 'items_wrap' => '%3$s' ) ); 
} 

if ( is_active_sidebar( 'right-topbar' ) ) : 
    dynamic_sidebar('right-topbar'); 
    
endif;

<?php if ( shortcode_exists( 'wpnetbase_register_form' ) ) {  echo do_shortcode('[wpnetbase_register_form]');   } ?>
