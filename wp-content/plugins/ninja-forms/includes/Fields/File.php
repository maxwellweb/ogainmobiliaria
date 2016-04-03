<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Field_File
 */
class NF_Fields_File extends NF_Abstracts_Field
{
    protected $_name = 'file';

    protected $_nicename = 'File';

    protected $_section = '';

    protected $_type = 'file';

    protected $_templates = array( 'file', 'textbox', 'input' );

    public function __construct()
    {
        parent::__construct();

        $this->_nicename = __( 'File', 'ninja-forms' );
    }
}
