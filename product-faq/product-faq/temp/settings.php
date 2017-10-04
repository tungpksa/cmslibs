<div class="wrap woocommerce">
    <h2><?php _e( 'Product FAQs', $text_domain ); ?></h2>
    <nav class="nav-tab-wrapper woo-nav-tab-wrapper">
        <a href="http://192.168.9.111/wpdemo/teepro/wp-admin/admin.php?page=wc-settings&amp;tab=general" class="nav-tab nav-tab-active"><?php _e( 'General', $text_domain ); ?></a>
    </nav>
    <form action="" method="POST">
        <table class="form-table">
            <tbody>
            <tr valign="top">
                <td class="forminp">
                    <fieldset>
                        <div class="checkbox">
                            <label><input type="checkbox" id="faq_select" name="faq[default]" value="1"<?php echo checked(1, $faq_data['default']);?>> <?php _e('Enable Product FAQ', $text_domain);?></label>
                        </div>
                    </fieldset>
                </td>
            </tr>
            <tr valign="top" class="faq-section"<?php if(isset( $faq_data['default']) && $faq_data['default'] == 1): echo ' style="display: block;"'; endif;?>>
                <th scope="row" class="titledesc">
                    <label for="woocommerce_bacs_title">Title</label></th>
                <td class="forminp">
                    <fieldset>
                        <legend class="screen-reader-text"><span><?php _e( 'Title', $text_domain ); ?></span></legend>
                        <input class="input-text regular-input " name="faq[title]" id="pfaqs_settings_title" style="" placeholder="<?php _e( 'Enter a title of tab on single product page...', $text_domain ); ?>" type="text" value="<?php if(isset( $faq_data['title'])): echo $faq_data['title']; endif;?>">
                    </fieldset>
                </td>
            </tr>
            <tr valign="top" class="faq-section"<?php if(isset( $faq_data['default']) && $faq_data['default'] == 1): echo ' style="display: block;"'; endif;?>>
                <th scope="row" class="titledesc">
                    <label for="woocommerce_bacs_description">List FAQs</label></th>
                    <td class="forminp">
                        <fieldset>
                            <legend class="screen-reader-text"><span>Description</span></legend>
                            <div class="acf-repeater">
                                <table class="acf-table">
                                    <thead>
                                    <tr>
                                        <th class="acf-row-handle"></th>
                                        <th class="acf-th" data-name="text" data-type="text" data-key="field_58be6fddc3c32" style="width: 50%;"> <?php _e( 'Question', $text_domain ); ?></th>
                                        <th class="acf-th" data-name="text" data-type="textarea" data-key="field_58be6fe8c3c33" style="width: 50%;"> <?php _e( 'Answer', $text_domain ); ?></th>
                                        <th class="acf-row-handle"></th>
                                    </tr>
                                    </thead>
                                    <tbody class="ui-sortable">
                                    <?php
                                    echo $repeater_field;
                                    echo $repeater_field_clone;
                                    ?>
                                    </tbody>
                                </table>
                                <ul class="acf-actions acf-hl">
                                    <li>
                                        <a class="acf-button button button-primary" href="#" data-event="add-row">Add Row</a>
                                    </li>
                                </ul>
                            </div>
                        </fieldset>
                    </td>
            </tr>
            </tbody>
        </table>
        <p class="submit">
            <input name="save" class="button-primary pfaqs-save-button" value="Save changes" type="submit">
            <input id="_wpnonce" name="_wpnonce" value="<?php echo wp_create_nonce( 'product-faqs' );?>" type="hidden">
            <input name="_wp_http_referer" value="/wpdemo/teepro/wp-admin/admin.php?page=wc-settings&amp;tab=checkout&amp;section=bacs" type="hidden">
        </p>
    </form>

</div>