<?php if ( ! defined( 'ABSPATH' ) ) exit;

class NF_AJAX_Controllers_Uploads extends NF_Abstracts_Controller
{
    protected $_blacklist = array();

    public function __construct()
    {
        parent::__construct();

        $this->_blacklist = apply_filters( 'ninja_forms_uploads_extension_blacklist', Ninja_Forms::config( 'UploadsExtensionBlacklist' ) );

        add_action( 'wp_ajax_nf_async_upload', array( $this, 'upload' ) );
        add_action( 'wp_ajax_nopriv_nf_async_upload', array( $this, 'upload' ) );

        add_action( 'nf_uploads_delete_temporary_file', array( $this, 'delete_temporary_file' ), 10, 1 );
    }

    /*
     * PUBLIC METHODS
     */

    public function upload()
    {
        check_ajax_referer( 'ninja_forms_ajax_nonce', 'security' );

        $this->_data['files'] = $_FILES;

        $this->validate();

        $this->_respond();
    }

    public function delete_temporary_file( $file_path )
    {
        unlink( $file_path );
    }

    /*
     * PROTECTED METHODS
     */

    protected function validate()
    {
        foreach( $this->_data['files'] as $key => $file ){

            if( $file['error'] ){
                $this->_errors[] = $this->code_to_message( $file['error'] );
            } else {

                $upload_dir = wp_upload_dir();

                $new_tmp_name = $this->i_like_clean_slugs_and_i_cannot_lie( $file['name'] );

                $file_path = $upload_dir['basedir'] . "/" . $new_tmp_name;

                move_uploaded_file( $file['tmp_name'], $file_path );

                wp_schedule_single_event( time() + 3600, 'nf_uploads_delete_temporary_file', array( $file_path ) );

                $this->_data['files'][ $key ]['tmp_name'] = $new_tmp_name;
            }
        }
    }

    /**
     * @param $string
     * @return string
     */
    protected function i_like_clean_slugs_and_i_cannot_lie( $string )
    {
        return 'nftmp-' . strtolower( trim( str_replace(' ', '_', $string ) ) );
    }

    /*
     * PRIVATE METHODS
     */

    /**
     * Convert $_FILES Error Code to Message
     *
     * http://php.net/manual/en/features.file-upload.errors.php
     *
     * @param $code
     * @return string
     */
    private function code_to_message($code)
    {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = "The uploaded file was only partially uploaded";
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = "No file was uploaded";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = "Missing a temporary folder";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = "Failed to write file to disk";
                break;
            case UPLOAD_ERR_EXTENSION:
                $message = "File upload stopped by extension";
                break;

            default:
                $message = "Unknown upload error";
                break;
        }
        return $message;
    }


}
