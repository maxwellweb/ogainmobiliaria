<?php if ( ! defined( 'ABSPATH' ) ) exit;

return apply_filters( 'ninja_forms_from_display_settings', array(

    array(
        'id'      => 'tmp-1',
        'label'   => 'Success Message',
        'type'    => 'successmessage',
        'message' => 'Your form has been successfully submitted.',
        'order'   => 1,
        'active'  => TRUE,
    ),

    array(
        'id'      => 'tmp-2',
        'label'   => 'Admin Email',
        'type'    => 'email',
        'to'      => array( get_option( 'admin_email' ) ),
        'subject' => 'Ninja Forms Submission',
        'message' => '{field:all_fields}',
        'order'   => 2,
        'active'  => TRUE,
    ),

    array(
        'id'    => 'tmp-3',
        'label' => 'Save Submission',
        'type'  => 'save',
        'order' => 3,
        'active'=> TRUE,
    ),

));