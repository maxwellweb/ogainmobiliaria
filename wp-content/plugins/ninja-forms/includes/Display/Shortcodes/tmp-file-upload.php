<?php
/*
function nf_tmp_file_upload() {

    if( $_FILES ) {
        $file = array_values($_FILES)[0];

        echo "<pre>";
        var_dump($file);
        echo "</pre>";
    }
    ?>

    <form id="theForm" action="" method="POST" enctype="multipart/form-data">
        <input type="file" name="you-only-upload-once">
        <input type="submit">
    </form>

    <script>
        jQuery(document).ready(function($) {

            // Variable to store your files
            var files;

            // Add events
            $('input[type=file]').on('change', prepareUpload);

            // Grab the files and set them to our variable
            function prepareUpload(event)
            {
                files = event.target.files;
            }

            var ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ); ?>';

            var nf_ajax_nonce = '<?php echo wp_create_nonce( "ninja_forms_ajax_nonce" ); ?>';

            $('form').on('submit', uploadFiles);

            // Catch the form submit and upload the files
            function uploadFiles(event)
            {
                event.stopPropagation(); // Stop stuff happening
                event.preventDefault(); // Totally stop stuff happening

                // START A LOADING SPINNER HERE

                // Create a formdata object and add the files
                var data = new FormData();
                $.each(files, function(key, value)
                {
                    data.append(key, value);
                });

                data.append( 'action', 'nf_async_upload' );
                data.append( 'security', nf_ajax_nonce );

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: data,
                    cache: false,
                    dataType: 'json',
                    processData: false, // Don't process the files
                    contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                    success: function(data, textStatus, jqXHR)
                    {
                        if(typeof data.error === 'undefined')
                        {
                            // Success so call function to process the form
//                            submitForm(event, data);
                        }
                        else
                        {
                            // Handle errors here
                            console.log('ERRORS: ' + data.error);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown)
                    {
                        // Handle errors here
                        console.log('ERRORS: ' + textStatus);
                        // STOP LOADING SPINNER
                    }
                });
            }


        });
    </script>



    <?php
}

add_shortcode( 'nf_tmp_file_upload', 'nf_tmp_file_upload' );
*/