<?php
if ( ! function_exists( 'is_plugin_active' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

if ( ! function_exists( 'wp_get_current_user' ) ) {
    require_once( ABSPATH . 'wp-includes/pluggable.php' );
}

/**
* after_setup_theme add_image_size
*/ 

add_action('after_setup_theme', 'wppandora_responsive_setup');

if (!function_exists('wppandora_responsive_setup')):

    function wppandora_responsive_setup() {

        register_nav_menus( array(
            'primary' => esc_html__( 'Primary', 'wppandora' ),
        ) );

        global $content_width;
        if (!isset($content_width))
            $content_width = 550;

        load_theme_textdomain('wppandora', get_template_directory().'/languages');

        $locale = get_locale();
        $locale_file = get_template_directory().'/languages/$locale.php';
        if (is_readable( $locale_file))
            require_once( $locale_file);

        add_editor_style();
        add_theme_support('automatic-feed-links');

        add_theme_support('post-thumbnails');
        add_image_size( 'wppandora-port-thumb', 250, 200 );
        add_image_size( 'wppandora-port-full', 600, 400 );
        add_image_size( 'wppandora-post-service', 380,300,  array('center', 'center'));
        
        $options = get_option('responsive_theme_options');
        add_theme_support( 'title-tag' );
    }

endif;

/**
* social share
*/ 

function edue_social_share() {
  // Get post thumbnail
  $src = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );    
?>
        <ul class="social-share pa tc">
          <li class="social-item mgb10">
            <a class="db tc br-2 color-dark nitro-line" title="Facebook" href="http://www.facebook.com/sharer.php?u=<?php esc_url( the_permalink() ); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
              <i class="fa fa-facebook"></i>
            </a>
          </li>
          <li class="social-item mgb10">
            <a class="db tc br-2 color-dark nitro-line" title="Twitter" href="https://twitter.com/share?url=<?php esc_url( the_permalink() ); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
              <i class="fa fa-twitter"></i>
            </a>
          </li>
          <li class="social-item mgb10">
            <a class="db tc br-2 color-dark nitro-line" title="Googleplus" href="https://plus.google.com/share?url=<?php esc_url( the_permalink() ); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
              <i class="fa fa-google-plus"></i>
            </a>
          </li>
          <li class="social-item mgb10">
            <a class="db tc br-2 color-dark nitro-line" title="Pinterest" href="//pinterest.com/pin/create/button/?url=<?php esc_url( the_permalink() ); ?>&media=<?php echo esc_attr( $src[0] ); ?>&description=<?php the_title(); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
              <i class="fa fa-pinterest"></i>
            </a>
          </li>
        </ul>
<?php
}


/**
* Add prev and next links to a numbered link list
*/
add_filter('wp_link_pages_args', 'wp_link_pages_args_prevnext_add');

function wp_link_pages_args_prevnext_add($args)
    {
        global $page, $numpages, $more, $pagenow;

        if (!$args['next_or_number'] == 'next_and_number')
            return $args; # exit early

        $args['next_or_number'] = 'number'; # keep numbering for the main part       


        if (!$more)
            return $args; # exit early

        if($page-1) # there is a previous page
            $args['before'] .=  _wp_link_page($page-1)
                . $args['link_before']. $args['previouspagelink'] . $args['link_after'] . '</a>'
            ;

        if ($page<$numpages) # there is a next page
            $args['after'] =  _wp_link_page($page+1)
                . $args['link_before'] . $args['nextpagelink'] . $args['link_after'] . '</a>'
                . $args['after']
            ;


        return $args;
}

/*use single.php*/

/*
wp_link_pages(array(
    'link_before'      => '<span>',
    'link_after'       => '</span>',
    'pagelink'         => '%',                            
    'before' => '<div class="blog-pagination">',
    'after' => '</div>',
    'nextpagelink' => __('&rarr;', 'edue'),
    'previouspagelink' => __('&larr;', 'edue'),
    )
);

css:

.blog-pagination {
  margin-top: 45px;
}
.blog-pagination > span {
  margin-right: 5px;
}
.blog-pagination span {
  display: inline-block;
  border: 1px solid #0099ff;
  padding: 3px 10px;
  -webkit-border-radius: 3px;
  -moz-border-radius: 3px;
  -o-border-radius: 3px;
  border-radius: 3px;
  font-weight: bold;
  color: #fff;
  background: #0099ff;
}
.blog-pagination a {
  margin-right: 5px;
}
.blog-pagination a span {
  color: #0099ff;
  background: #fff;
  border-color: #d7d7d7;
  -webkit-transition: all 0.3s;
  -moz-transition: all 0.3s;
  -ms-transition: all 0.3s;
  -o-transition: all 0.3s;
  transition: all 0.3s;
}
.blog-pagination a:hover span {
  color: #fff;
  background: #0099ff;
  border-color: #0099ff;
}

*/ 


function wppandoraCurrentUrl($full = true)
{
    if (isset($_SERVER['REQUEST_URI'])) {
        $parse = parse_url(
            (isset($_SERVER['HTTPS']) && strcasecmp($_SERVER['HTTPS'], 'off') ? 'https://' : 'http://').
            (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : (isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '')).(($full) ? $_SERVER['REQUEST_URI'] : null)
        );
        $parse['port'] = $_SERVER['SERVER_PORT']; // Setup protocol for sure (80 is default)        
        return $parse;
    }
}

/*===================================================================================
 * Add Author Links
 * =================================================================================*/
function wppandora_add_to_author_profile( $contactmethods ) {
    $contactmethods['google_profile'] = 'Google Profile URL';
    $contactmethods['twitter_profile'] = 'Twitter Profile URL';
    $contactmethods['facebook_profile'] = 'Facebook Profile URL';
    $contactmethods['linkedin_profile'] = 'Linkedin Profile URL';
    $contactmethods['pinterest_profile'] = 'Pinterest Profile URL';
    $contactmethods['instagram_profile'] = 'Instagram Profile URL';

    return $contactmethods;
}
add_filter( 'user_contactmethods', 'wppandora_add_to_author_profile', 10, 1);

/*ilc mce buttons*/
function wppandora_mce_buttons($buttons){
    array_push($buttons,
        "backcolor",
        "sub",
        "sup",
        "fontselect",
        "fontsizeselect",
        "styleselect",
        "forecolor",
        "code",
        "cleanup"
    );
    return $buttons;
}
add_filter("mce_buttons", "wppandora_mce_buttons");

/**
 * Where the post has no post title, but must still display a link to the single-page post view.
 */
add_filter('the_title', 'wppandora_responsive_title');

function wppandora_responsive_title($title) {
    if ($title == '') {
        return __('Untitled','wppandora');
    } else {
        return $title;
    }
}

/**
 * check file file_exists
 */

function wppandora_hasJomresInstallerFiles()
{
    $files = ABSPATH.'jomres/install_jomres.php';

    if (file_exists($files)) return true;

    return false;
}


function wppandora_get_wordpress_uploads_url() {
  $nbupload_dir = wp_upload_dir(); 
  return trailingslashit( $nbupload_dir['baseurl'] );
}

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */

function wppandora_body_classes($classes ) {
  global $post;    

  if ( is_page_template( 'template-fullwidth.php' ) || is_404() ) {
    $classes[] = 'page-fullwidth';
  }
  // Boxed Layout
  if ( wppandora_get_option('site_boxed') || (isset($_REQUEST['boxed_layout']) && $_REQUEST['boxed_layout'] = 'enable' ) ) {
    $classes[] = 'layout-boxed';
  }
  
  return $classes;
}
add_filter( 'body_class', 'wppandora_body_classes' );

/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function wppandora_setup_author() {
  global $wp_query;

  if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
    $GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
  }
}
add_action( 'wp', 'wppandora_setup_author' );

/**
 * Browser detection body_class() output
 */
function wppandora_browser_body_class($classes) {
        global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
        if($is_lynx) $classes[] = 'lynx';
        elseif($is_gecko) $classes[] = 'gecko';
        elseif($is_opera) $classes[] = 'opera';
        elseif($is_NS4) $classes[] = 'ns4';
        elseif($is_safari) $classes[] = 'safari';
        elseif($is_chrome) $classes[] = 'chrome';
        elseif($is_IE) {
                $classes[] = 'ie';
                if(preg_match('/MSIE ([0-9]+)([a-zA-Z0-9.]+)/', $_SERVER['HTTP_USER_AGENT'], $browser_version))
                $classes[] = 'ie'.$browser_version[1];
        } else $classes[] = 'unknown';
        if($is_iphone) $classes[] = 'iphone';
        if ( stristr( $_SERVER['HTTP_USER_AGENT'],"mac") ) {
                 $classes[] = 'osx';
           } elseif ( stristr( $_SERVER['HTTP_USER_AGENT'],"linux") ) {
                 $classes[] = 'linux';
           } elseif ( stristr( $_SERVER['HTTP_USER_AGENT'],"windows") ) {
                 $classes[] = 'windows';
           }

        if(is_user_logged_in()):
            $user=wp_get_current_user();   
            $urole='ur-'.$user->roles[0];
            $classes[] = $urole;
        endif;
                            
                           
        return $classes;
}
add_filter('body_class','wppandora_browser_body_class');

// make tags into a bootstrap button
function wppandora_add_class_the_tags($html){
    $postid = get_the_ID();
    $html = str_replace('<a','<a class="btn btn-xs btn-default"',$html);
    return $html;
}
add_filter('the_tags','wppandora_add_class_the_tags',10,1);

/**
 * Display navigation to next/previous set of posts when applicable.
 */
function wppandora_paging_nav() {
  // Don't print empty markup if there's only one page.
  if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
    return;
  }

  $paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
  $pagenum_link = html_entity_decode( get_pagenum_link() );
  $query_args   = array();
  $url_parts    = explode( '?', $pagenum_link );

  if ( isset ( $url_parts[1] ) ) {
    wp_parse_str( $url_parts[1], $query_args );
  }

  $pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
  $pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

  $format  = $GLOBALS['wp_rewrite']->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
  $format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit( 'page/%#%', 'paged' ) : '?paged=%#%';

  // Set up paginated links.
  $links = paginate_links( array(
    'wppandora'     => $pagenum_link,
    'format'    => $format,
    'total'     => $GLOBALS['wp_query']->max_num_pages,
    'current'   => $paged,
    'mid_size'  => 1,
    'add_args'  => array_map( 'urlencode', $query_args ),
    'prev_text' =>  wp_kses(__('<i class=\'fa fa-chevron-left\'></i>', 'wppandora' ), array('i' => array('class' => array()))),
    'next_text' => wp_kses(__( '<i class=\'fa fa-chevron-right\'></i>', 'wppandora' ), array('i' => array('class' => array()))),
  ) );

  if ( $links ) :
  ?>
  <nav class="navigation paging-navigation" role="navigation">
    <h1 class="screen-reader-text"><?php esc_html_e( 'Posts navigation', 'wppandora' ); ?></h1>
    <div class="pagination loop-pagination">
      <?php echo wp_kses($links, array(
        'a' => array(
          'href' => array(),
          'class' => array()
        ),
        'i' => array(
          'class' => array()
        ),
        'span' => array(
          'class' => array()
        )
      )); ?>
    </div><!--/ .pagination -->
  </nav><!--/ .navigation -->
  <?php
  endif;
}



/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
if ( ! function_exists('printshop_posted_on') ) :
function printshop_posted_on() {
  $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
  

  $time_string = sprintf( $time_string,
    esc_attr( get_the_date( 'c' ) ),
    esc_html( get_the_date() ),
    esc_attr( get_the_modified_date( 'c' ) ),
    esc_html( get_the_modified_date() )
  );

  $posted_on = sprintf(
    _x( ' on %s', 'post date', 'wpnetbase' ),
    '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
  );

  if ( is_sticky( ) ) {
    echo wp_kses(__('<span class="nbt-sticky-post">Sticky</span>', 'wpnetbase'), array('span' => array('class' => array())));
  }
  if(get_the_author()){
    $byline = sprintf(
      _x( '<i class="fa fa-user"></i> %s', 'post author', 'wpnetbase' ),
      '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
    );
    echo '<span class="byline">'. $byline . '</span>';
  }
  echo '<span class="posted-on"> <i class="fa fa-calendar-o"></i>' . $posted_on . '</span>';

  $categories_list = get_the_category_list( esc_html__( ', ', 'wpnetbase' ) );
  if ( $categories_list && printshop_categorized_blog() ) {
    printf( '<span class="categories-links">' . esc_html__( '%1$s', 'wpnetbase' ) . '</span>', $categories_list );
  }

  if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
    echo '<span class="comments-link">'. wp_kses(__(' <i class="fa fa-comment-o"></i> ', 'wpnetbase'), array('i' => array('class' => array())));
    comments_popup_link( esc_html__( '0', 'wpnetbase' ), esc_html__( '1', 'wpnetbase' ), esc_html__( '%', 'wpnetbase' ) );
    echo '</span>';
  }
  if ( has_tag() ){ 
      echo '<span class="tags-links">';
      printf( esc_html__('%1$s', 'wpnetbase'), get_the_tag_list( '', ', ' ) );
      echo '</span>';
  }

}
endif;

/**
 * Output html5 js file for ie9.
 */

function wppandora_IE_fallback() {
    wp_register_script( 'ie_html5shiv', get_template_directory_uri() . '/js/html5.min.js' );
    wp_enqueue_script( 'ie_html5shiv');
    wp_script_add_data( 'ie_html5shiv', 'conditional', 'lt IE 9' );
    
}
add_action( 'wp_enqueue_scripts', 'wppandora_IE_fallback' ); 

/**
 * Output site favicon to wp_head hook.
 */

function wppandora_favicons() {
    if ( function_exists( 'wp_site_icon' ) ) {  
      if ( function_exists( 'has_site_icon' ) ) {
        if ( ! has_site_icon() ) {
          $favicons = null;

          if ( wppandora_get_option('site_favicon', '', 'url') ) $favicons .= '
          <link rel="shortcut icon" href="'. esc_url(wppandora_get_option('site_favicon', '', 'url')) .'">';

          if ( wppandora_get_option('site_iphone_icon', '', 'url') ) $favicons .= '
          <link rel="apple-touch-icon-precomposed" href="'. esc_url(wppandora_get_option('site_iphone_icon', '', 'url')) .'">';

          if ( wppandora_get_option('site_iphone_icon_retina', '', 'url') ) $favicons .= '
          <link rel="apple-touch-icon-precomposed" sizes="114x114" href="'. esc_url(wppandora_get_option('site_iphone_icon_retina', '', 'url')) .'">';

          if ( wppandora_get_option('site_ipad_icon', '', 'url') ) $favicons .= '
          <link rel="apple-touch-icon-precomposed" sizes="72x72" href="'. esc_url(wppandora_get_option('site_ipad_icon', '', 'url')) .'">';

          if ( wppandora_get_option('site_ipad_icon_retina', '', 'url') ) $favicons .= '
          <link rel="apple-touch-icon-precomposed" sizes="114x114" href="'. esc_url(wppandora_get_option('site_ipad_icon_retina', '', 'url')) .'">';

          printf("%s", $favicons);

          return;
        }

        return;
      } 
    }
    /**
     * Support WordPress < 4.3
    */

    $favicons = null;

    if ( wppandora_get_option('site_favicon', '', 'url') ) $favicons .= '
    <link rel="shortcut icon" href="'. esc_url(wppandora_get_option('site_favicon', '', 'url')) .'">';

    if ( wppandora_get_option('site_iphone_icon', '', 'url') ) $favicons .= '
    <link rel="apple-touch-icon-precomposed" href="'. esc_url(wppandora_get_option('site_iphone_icon', '', 'url')) .'">';

    if ( wppandora_get_option('site_iphone_icon_retina', '', 'url') ) $favicons .= '
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="'. esc_url(wppandora_get_option('site_iphone_icon_retina', '', 'url')) .'">';

    if ( wppandora_get_option('site_ipad_icon', '', 'url') ) $favicons .= '
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="'. esc_url(wppandora_get_option('site_ipad_icon', '', 'url')) .'">';

    if ( wppandora_get_option('site_ipad_icon_retina', '', 'url') ) $favicons .= '
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="'. esc_url(wppandora_get_option('site_ipad_icon_retina', '', 'url')) .'">';

    printf("%s", $favicons);

      
}
add_action( 'wp_head', 'wppandora_favicons' );
