<div class="checkbox">
    <label><input type="checkbox" id="faq_select" name="faq[default]" value="1"<?php echo checked(1, $faq_data['default']);?>> <?php _e('Enable Custom Product FAQ', $text_domain);?></label>
</div>

<div class="faq-section<?php if(isset( $faq_data['default']) && $faq_data['default'] == 1): echo ' show'; endif;?>">
    <div class="form-group">
        <label for="title"><?php _e('Title', $text_domain);?>:</label>
        <input type="text" class="form-control" name="faq[title]" value="<?php if(isset( $faq_data['title'])): echo $faq_data['title']; endif;?>">
    </div>

    <div class="form-group">
        <label for="Lists"><?php _e('List of FAQs', $text_domain);?>:</label>
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
    </div>

</div>
