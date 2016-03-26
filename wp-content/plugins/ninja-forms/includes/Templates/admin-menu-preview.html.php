<div class="wrap">
    Hello, Preview!
</div>

<script>
    var preview_field = {
        action: 'nf_preview_update',
        security: '<?php echo wp_create_nonce( 'ninja_forms_ajax_nonce' ); ?>',
        form: {
            id: 1,
            field: {
                "id" : 1,
                "settings": {
                    "type": 'textbox',
                    "label": 'Another Name',
                    "label_pos": 'inside',
                    "required": 1
                }
            }
        },
    };

    var preview_action = {
        action: 'nf_preview_update',
        security: '<?php echo wp_create_nonce( 'ninja_forms_ajax_nonce' ); ?>',
        form: {
            id: 1,
            action: {
                "id" : 1,
                "settings": {
                    "type": 'successmessage',
                    "message": 'This is another test success message',
                    "active": 1
                }
            }
        },
    };

    jQuery.post( ajaxurl, preview_field, function(response){

        console.log( JSON.parse( response ) );
    });

    setTimeout(function(){
        jQuery.post( ajaxurl, preview_action, function(response){

            console.log( JSON.parse( response ) );

        });
    }, 3000);



</script>