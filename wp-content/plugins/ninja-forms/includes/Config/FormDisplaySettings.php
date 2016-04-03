<?php if ( ! defined( 'ABSPATH' ) ) exit;

return apply_filters( 'ninja_forms_from_display_settings', array(

    /*
    * FORM TITLE
    */

    'title' => array(
        'name' => 'title',
        'type' => 'textbox',
        'label' => __( 'Form Title', 'ninja-forms' ),
        'width' => 'full',
        'group' => 'primary',
        'value' => '',

    ),

    /*
    * SHOW FORM TITLE
    */

    'show_title' => array(
        'name' => 'show_title',
        'type' => 'toggle',
        'label' => __( 'Display Form Title', 'ninja-forms' ),
        'width' => 'full',
        'group' => 'primary',
        'value' => 1,

    ),

    /*
    * CLEAR SUCCESSFULLY COMPLETED FORM
    */

    'clear_complete' => array(
        'name' => 'clear_complete',
        'type' => 'toggle',
        'label' => __( 'Clear successfully completed form?', 'ninja-forms' ),
        'width' => 'full',
        'group' => 'primary',
        'value' => 1,

        //TODO: Add following text below the element.
        //If this box is checked, Ninja Forms will clear the form values after it has been successfully submitted.
    ),

    /*
    * HIDE SUCCESSFULLY COMPLETED FORMS
    */

    'hide_complete' => array(
        'name' => 'hide_complete',
        'type' => 'toggle',
        'label' => __( 'Hide successfully completed form?', 'ninja-forms' ),
        'width' => 'full',
        'group' => 'primary',
        'value' => 1,
        'help'  => __( 'If this box is checked, Ninja Forms will hide the form after it has been successfully submitted.', 'ninja-forms' ),
    ),

    /*
     * Currency
     */

    'currency' => array(
        'name' => 'currency',
        'type' => 'select',
        'label' => __( 'Currency', 'ninja-forms' ),
        'group' => 'advanced',
        'width' => 'full',
        'options' => array(
            array(
                'label' => __( 'USD - $', 'ninja-forms' ),
                'value' => 'usd'
            ),
        ),
        'value' => 'usd',
        'use_merge_tags' => FALSE
    ),

    /*
     * Classes
     */

    'classes' => array(
        'name' => 'classes',
        'type' => 'fieldset',
        'label' => __( 'Custom Class Names', 'ninja-forms' ),
        'width' => 'full',
        'group' => 'advanced',
        'settings' => array(
            array(
                'name' => 'wrapper_class',
                'type' => 'textbox',
                'placeholder' => '',
                'label' => __( 'Wrapper', 'ninja-forms' ),
                'width' => 'one-half',
                'value' => '',
                'use_merge_tags' => FALSE,
            ),
            array(
                'name' => 'element_class',
                'type' => 'textbox',
                'label' => __( 'Element', 'ninja-forms' ),
                'placeholder' => '',
                'width' => 'one-half',
                'value' => '',
                'use_merge_tags' => FALSE,
            ),
        ),
    ),

    /*
     * KEY
     */

    'key' => array(
        'name' => 'key',
        'type' => 'textbox',
        'label' => __( 'Form Key', 'ninja-forms'),
        'width' => 'full',
        'group' => 'administration',
        'value' => '',
        'help' => __( 'Programmatic name that can be used to reference this form.', 'ninja-forms' ),
    ),

    /*
     * ADD SUBMIT CHECKBOX
     */

    'add_submit' => array(
        'name' => 'add_submit',
        'type' => 'toggle',
        'label' => __( 'Add Submit Button', 'ninja-forms'),
        'width' => 'full',
        'group' => '',
        'value' => 1,
        'help' => __( 'We\'ve noticed that don\'t have a submit button on your form. We can add one for your automatically.', 'ninja-forms' ),
    ),



));
