( function ( $ ) {
    'use strict';
    $( document ).ready( function () {
        $(document).on('click', '.acf-icon.-minus', function() {
            $(this).closest('.acf-row').remove();
            return false;
        });

        $(document).on('click', '.acf-icon.-plus', function() {
            repeater_ajax($(this), true);
            return false;
        });

        $(document).on('click', '#faq_select', function() {
            if($(this).is(':checked')){
                $('.faq-section').show();
            }else{
                $('.faq-section').hide();
            }

        });

        $('.acf-button').on('click', function() {
            repeater_ajax($(this));

            return false;
        });

        function repeater_ajax($this, pos = false){
            var defaults =  tinyMCEPreInit.mceInit.content;
            var options = { height: 200 };
            var $my_settings = $.extend({}, defaults, options);
            var $id = $('.acf-repeater .ui-sortable .acf-clone').data('id');
            var $clone = $('.acf-repeater .ui-sortable .acf-clone').prop('outerHTML');


            if(pos){
                $this.closest('.acf-row').before($clone);
                $('.acf-repeater .ui-sortable > .acf-clone').first().removeClass('acf-clone');
            }else{
                $('.acf-repeater .ui-sortable .acf-clone').removeClass('acf-clone');
                $('.acf-repeater .ui-sortable').append($clone);
            }

            tinymce.init($my_settings);
            tinyMCE.execCommand('mceAddEditor', false, 'faq_content_' + $id);
            quicktags({id : 'faq_content_' + $id});


            $( ".acf-row:not(.acf-clone)" ).each(function(index) {
                $( this ).find('.acf-row-handle.order span').text( index + 1);
            });

            jQuery.ajax({
                url : product_faq_ajax.url,
                type : 'post',
                dataType: 'json',
                data : {
                    action : 'nb_repeater'
                },
                success : function( response ) {
                    $('.acf-repeater .ui-sortable > .acf-clone').remove();
                    $('.acf-repeater .ui-sortable').append(response.html);

                    //alert(response.id);




                }
            });
        }




    })
} ( jQuery ) )