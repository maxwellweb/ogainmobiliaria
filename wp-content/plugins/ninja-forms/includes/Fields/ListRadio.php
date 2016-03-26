<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Fields_RadioList
 */
class NF_Fields_ListRadio extends NF_Abstracts_List
{
    protected $_name = 'listradio';

    protected $_type = 'listradio';

    protected $_section = 'common';

    protected $_templates = 'listradio';

    protected $_old_classname = 'list-radio';

    public function __construct()
    {
        parent::__construct();

        $this->_nicename = __( 'Radio List', 'ninja-forms' );
    }
}
