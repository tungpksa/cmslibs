<?php
/**
 * Created by PhpStorm.
 * User: Marketing1
 * Date: 07/03/2017
 * Time: 2:26 PM
 */

class Product_FAQ {
    function __construct() {
        global $post;
        list( $path, $url ) = self::get_path( dirname( dirname( __FILE__ ) ) );
        $url = $url  .'product-faq/';

        define( 'PF_URL', $url );
        define( 'PF_JS_URL', trailingslashit( PF_URL . 'temp/js' ) );
        define( 'PF_CSS_URL', trailingslashit( PF_URL . 'temp/css' ) );

        add_action( 'add_meta_boxes', array($this, 'create_metabox'));
        add_action( 'save_post', array($this, 'save_metabox'), 10, 3  );
        add_action( 'admin_head', array( $this, 'admin_scripts' ) );

        add_action( 'wp_ajax_nopriv_nb_repeater', array($this, 'nb_repeater_ajax') );
        add_action( 'wp_ajax_nb_repeater', array($this, 'nb_repeater_ajax') );
        add_filter( 'woocommerce_product_tabs', array($this, 'woo_new_product_tab'), 10, 1 );

        add_action( 'wp_enqueue_scripts', array($this, 'frontend_scripts') );

        add_action( 'admin_menu', array($this, 'settings_menu') );

    }

    /*
    * Create metabox for Product FAQ
    */
    function create_metabox(){
        if(get_option('product_faqs_enable')) {
            add_meta_box('product-faq', __('Product FAQ', 'tshirt'), array($this, 'show_metabox'), 'product');
        }
    }

    /*
     * Show metabox on product post type
     */
    function show_metabox(){
        global $post;

        $faq_data       =  @unserialize(base64_decode(get_post_meta( $post->ID, 'product_faq', true)));
        $m_themes       = wp_get_theme();
        $text_domain    = $m_themes->get( 'TextDomain' );
        $id             = md5(rand().time());
        $id_clone       = md5('acf-clone'.rand().time());
        $repeater_field = $this->repeater_clone($faq_data, '', $id);
        $repeater_field_clone = $this->repeater_clone($faq_data, ' acf-clone', $id_clone);
        require_once 'temp/index.php';
    }

    /*
     * Save metabox
     */
    function save_metabox($post_id, $post, $update){
        $data = $_POST['faq'];
        if(is_array($data)){
            $new_data = array();
            $new_data['default']    = $data['default'];
            $new_data['title']      = $data['title'];
            foreach ($data['question'] as $k => $faq):
                if($faq){
                    $new_data['question'][$k]   = $faq;
                    $new_data['answer'][$k]     = $data['answer'][$k];
                }

            endforeach;
        }
        update_post_meta($post_id, 'product_faq', base64_encode(serialize($new_data)));
        update_post_meta($post_id, 'product_faqs_enable', $new_data['default'] );
    }

    function nb_repeater_ajax(){
        $json = false;
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            $id             = md5(rand().time());
            $json['html']   = $this->repeater_clone('', ' acf-clone', $id);
            $json['id']     = $id;
        }
        echo json_encode($json, TRUE);
        die();
    }

    function woo_new_product_tab( $tabs ) {
        global $post;
        $check_custom   = get_post_meta($post->ID, 'product_faqs_enable', true);
        if($check_custom){
            $faq_data   = @unserialize(base64_decode(get_post_meta( $post->ID, 'product_faq', true)));
        }else{
            $faq_data = @unserialize(base64_decode(get_option( 'product_faqs' )));
        }

        if(isset($faq_data['title']) && get_option('product_faqs_enable')){
            $tabs['faq_tab'] = array(
                'title'     => __( $faq_data['title'], 'woocommerce' ),
                'priority'  => 50,
                'callback'  => array($this, 'woo_new_product_tab_content')
            );

        }



        return $tabs;
    }

    function woo_new_product_tab_content() {
        global $post;
        $check_custom   = get_post_meta($post->ID, 'product_faqs_enable', true);
        if($check_custom){
            $faq_data   = @unserialize(base64_decode(get_post_meta( $post->ID, 'product_faq', true)));
        }else{
            $faq_data = @unserialize(base64_decode(get_option( 'product_faqs' )));
        }

        require_once 'temp/frontend.php';

    }

    function settings_menu(){
        add_options_page(
            'Product FAQs',
            'Product FAQs',
            'manage_options',
            'product-faqs',
            array(
                $this,
                'settings_page'
            )
        );
    }

    function  settings_page() {
        $m_themes = wp_get_theme();
        $text_domain = $m_themes->get( 'TextDomain' );

        $faq_data = @unserialize(base64_decode(get_option( 'product_faqs' )));

        $id = md5(rand().time());
        $id_clone = md5('acf-clone'.rand().time());
        $repeater_field = $this->repeater_clone($faq_data, '', $id);
        $repeater_field_clone = $this->repeater_clone($faq_data, ' acf-clone', $id_clone);



        if(isset($_POST['save'])){
            $nonce = $_REQUEST['_wpnonce'];
            if ( ! wp_verify_nonce( $nonce, 'product-faqs' ) ) {
                die( 'Security check' );
            } else {
                $data = $_REQUEST['faq'];
                if(is_array($data)){
                    $new_data               = array();
                    $new_data['title']      = $data['title'];
                    $new_data['default']    = $data['default'];
                    foreach ($data['question'] as $k => $faq):
                        if($faq){
                            $new_data['question'][$k]   = $faq;
                            $new_data['answer'][$k]     = $data['answer'][$k];
                        }

                    endforeach;
                }
                update_option( 'product_faqs', base64_encode(serialize($new_data)) );
                update_option( 'product_faqs_enable', $new_data['default'] );

                wp_redirect( home_url().'/wp-admin/options-general.php?page=product-faqs' );
                exit;

            }
        }
        include 'temp/settings.php';
    }

    /*
     * Include JS, CSS Backend
     */
    public function admin_scripts() {
        wp_enqueue_style( 'product-faq-style', PF_CSS_URL . 'backend.css', false, null );
        wp_enqueue_script( 'product-faq', PF_JS_URL . 'main.js' );
        wp_localize_script( 'product-faq', 'product_faq_ajax', array(
            'url' => admin_url( 'admin-ajax.php' )
        ));
    }

    public function frontend_scripts(){
        if(get_option('product_faqs_enable')){
            wp_enqueue_style( 'product-faq', PF_CSS_URL . 'style.css', array(), '1.1', 'all');
        }
    }

    /**
     * Get plugin base path and URL.
     * The method is static and can be used in extensions.
     *
     * @link http://www.deluxeblogtips.com/2013/07/get-url-of-php-file-in-wordpress.html
     * @param string $path Base folder path.
     * @return array Path and URL.
     */
    public static function get_path( $path = '' ) {
        // Plugin base path.
        $path       = wp_normalize_path( untrailingslashit( $path ) );
        $themes_dir = wp_normalize_path( untrailingslashit( dirname( realpath( get_stylesheet_directory() ) ) ) );

        // Default URL.
        $url = plugins_url( '', $path . '/' . basename( $path ) . '.php' );

        // Included into themes.
        if (
            0 !== strpos( $path, wp_normalize_path( WP_PLUGIN_DIR ) )
            && 0 !== strpos( $path, wp_normalize_path( WPMU_PLUGIN_DIR ) )
            && 0 === strpos( $path, $themes_dir )
        ) {
            $themes_url = untrailingslashit( dirname( get_stylesheet_directory_uri() ) );
            $url        = str_replace( $themes_dir, $themes_url, $path );
        }

        $path = trailingslashit( $path );
        $url  = trailingslashit( $url );

        return array( $path, $url );
    }


    function repeater_clone($faqs, $class = '', $id){
        $html = '';
        $index = 1;

        $m_themes = wp_get_theme();
        $text_domain = $m_themes->get( 'TextDomain' );


        if(is_array($faqs) && isset($faqs['question']) && empty($class)){
            foreach ($faqs['question'] as $k => $question):
                if($question):
                ob_start();
                wp_editor( $faqs['answer'][$k], 'faq_content_'.$k, array(
                    'quicktags' => array('buttons' => 'em,strong,link',),
                    'quicktags' => true,
                    'tinymce' => true,
                    'textarea_name' => 'faq[answer]['.$k.']',
                    'media_buttons' => false,
                    'textarea_rows' => 8,
                    'tabindex' => 4,
                    'tinymce' => array(
                        'theme_advanced_buttons1' => 'bold, italic, ul, min_size, max_size',
                        'theme_advanced_buttons2' => '',
                        'theme_advanced_buttons3' => '',
                        'theme_advanced_buttons4' => '',
                    ),
                ) );
                $editor_contents_return = ob_get_clean();

                $html .= '<tr class="acf-row'.$class.'" data-id="'.$k.'">
                    <td class="acf-row-handle order ui-sortable-handle" title="Drag to reorder">
                        <span>'.$index.'</span>
                    </td>
    
                    <td class="acf-field acf-field-text acf-field-58be6fddc3c32" data-name="text" data-type="text" data-key="field_58be6fddc3c32">
                        <div class="acf-input">
                            <div class="acf-input-wrap">
                                <textarea rows="3" cols="40" name="faq[question]['.$k.']" placeholder="'.__('Enter a question...', $text_domain).'">'.$question.'</textarea>                        
                            </div>
                        </div>
                    </td>
    
                    <td class="acf-field acf-field-textarea acf-field-58be6fe8c3c33" data-name="text" data-type="textarea" data-key="field_58be6fe8c3c33">
                        <div class="acf-input">
                            '.$editor_contents_return.'
                        </div>
                    </td>
    
    
    
                    <td class="acf-row-handle remove">
                        <a class="acf-icon -plus small" href="#" data-event="add-row" title="Add row"></a>
                        <a class="acf-icon -minus small" href="#" data-event="remove-row" title="Remove row"></a>
                    </td>
    
                </tr>';
                endif;
                $index++;
            endforeach;
        }else{
            ob_start();
            wp_editor( '', 'faq_content_'.$id, array(
                'quicktags' => array('buttons' => 'em,strong,link',),
                'quicktags' => true,
                'tinymce' => true,
                'textarea_name' => 'faq[answer]['.$id.']',
                'media_buttons' => false,
                'textarea_rows' => 15,
                'tabindex' => 4,
                'tinymce' => array(
                    'theme_advanced_buttons1' => 'bold, italic, ul, min_size, max_size',
                    'theme_advanced_buttons2' => '',
                    'theme_advanced_buttons3' => '',
                    'theme_advanced_buttons4' => '',
                ),
            ) );
            $editor_contents = ob_get_clean();
            $html .= '<tr class="acf-row'.$class.'" data-id="'.$id.'">

                <td class="acf-row-handle order ui-sortable-handle" title="Drag to reorder">
                    <span>'.(count($faqs['question'])+1).'</span>
                </td>

                <td class="acf-field acf-field-text acf-field-58be6fddc3c32" data-name="text" data-type="text" data-key="field_58be6fddc3c32">
                    <div class="acf-input">
                        <div class="acf-input-wrap">
                            <textarea rows="3" cols="40" name="faq[question]['.$id.']" placeholder="'.__('Enter a question...', $text_domain).'"></textarea>                        
                        </div>
                    </div>
                </td>

                <td class="acf-field acf-field-textarea acf-field-58be6fe8c3c33" data-name="text" data-type="textarea" data-key="field_58be6fe8c3c33">
                    <div class="acf-input">
                        '.$editor_contents.'
                    </div>
                </td>



                <td class="acf-row-handle remove">
                    <a class="acf-icon -plus small" href="#" data-event="add-row" title="Add row"></a>
                    <a class="acf-icon -minus small" href="#" data-event="remove-row" title="Remove row"></a>
                </td>

            </tr>';
        }

        return $html;
    }

    function log_it( $message ) {
        if( WP_DEBUG === true ){
            if( is_array( $message ) || is_object( $message ) ){
                error_log( print_r( $message, true ) );
            } else {
                error_log( $message );
            }
        }
    }

}
$faq = new Product_FAQ();