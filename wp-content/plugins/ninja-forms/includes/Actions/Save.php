<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Action_Save
 */
final class NF_Actions_Save extends NF_Abstracts_Action
{
    /**
    * @var string
    */
    protected $_name  = 'save';

    /**
    * @var array
    */
    protected $_tags = array();

    /**
    * @var string
    */
    protected $_timing = 'normal';

    /**
    * @var int
    */
    protected $_priority = '10';

    /**
    * Constructor
    */
    public function __construct()
    {
        parent::__construct();

        $this->_nicename = __( 'Save', 'ninja-forms' );
    }

    /*
    * PUBLIC METHODS
    */

    public function save( $action_settings )
    {

    }

    public function process( $action_settings, $form_id, $data )
    {
        $data[ 'extra' ][ 'foo' ] = 'bar';

        if( isset( $data['settings']['is_preview'] ) && $data['settings']['is_preview'] ){
            return $data;
        }

        $sub = Ninja_Forms()->form( $form_id )->sub()->get();

        $hidden_field_types = apply_filters( 'nf_sub_hidden_field_types', array() );

        foreach( $data['fields'] as $field ){

            if( in_array( $field[ 'type' ], array_values( $hidden_field_types ) ) ) {
                $data['actions']['save']['hidden'][] = $field['type'];
                continue;
            }

            $sub->update_field_value( $field['id'], $field['value'] );
        }

        if( isset( $data[ 'extra' ] ) ) {
            $sub->update_extra_values( $data['extra'] );
        }

        do_action( 'nf_save_sub', $sub->get_id() );
        do_action( 'ninja_forms_save_sub', $sub->get_id() );

        $sub->save();

        $data[ 'actions' ][ 'save' ][ 'id' ] = $sub->get_id();

        return $data;
    }
}
