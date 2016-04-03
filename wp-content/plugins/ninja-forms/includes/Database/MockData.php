<?php if ( ! defined( 'ABSPATH' ) ) exit;

final class NF_Database_MockData
{

    public function __construct()
    {
        $this->_migrate();
    }

    public function saved_fields()
    {
        $field = Ninja_Forms()->form()->field()->get();
        $field->update_setting( 'label', 'Foo' );
        $field->update_setting( 'key', 'foo' );
        $field->update_setting( 'type', 'textbox' );
        $field->update_setting( 'saved', 1 );
        $field->save();

        $field = Ninja_Forms()->form()->field()->get();
        $field->update_setting( 'label', 'Bar' );
        $field->update_setting( 'key', 'bar' );
        $field->update_setting( 'type', 'checkbox' );
        $field->update_setting( 'saved', 1 );
        $field->save();

        $field = Ninja_Forms()->form()->field()->get();
        $field->update_setting( 'label', 'Baz' );
        $field->update_setting( 'key', 'baz' );
        $field->update_setting( 'type', 'listselect' );
        $field->update_setting( 'saved', 1 );
        $field->save();
    }

    public function form_blank_form()
    {
        /*
         * FORM
         */

        $form = Ninja_Forms()->form()->get();
        $form->update_setting( 'title', 'Blank Forms' );
        $form->save();
    }

    public function form_contact_form_1()
    {
        /*
         * FORM
         */

        $form = Ninja_Forms()->form()->get();
        $form->update_setting( 'title', 'Contact Me' );
        $form->save();

        $form_id = $form->get_id();

        /*
         * FIELDS
         */

        $field = Ninja_Forms()->form( $form_id )->field()->get();
        $field->update_setting( 'type', 'textbox' )
            ->update_setting( 'label', 'Name')
            ->update_setting( 'label_pos', 'above' )
            ->update_setting( 'required', 1 )
            ->update_setting( 'order', 1 )
            ->update_setting( 'key', 'name' )
            ->update_setting( 'placeholder', '' )
            ->update_setting( 'default', '' )
            ->update_setting( 'wrapper_class', '' )
            ->update_setting( 'element_class', '' )
            ->save();

        $name_field_id = $field->get_id();

        $field = Ninja_Forms()->form( $form_id )->field()->get();
        $field->update_setting( 'type', 'email' )
            ->update_setting( 'label', 'Email')
            ->update_setting( 'label_pos', 'above' )
            ->update_setting( 'required', 1 )
            ->update_setting( 'order', 2 )
            ->update_setting( 'key', 'email' )
            ->update_setting( 'placeholder', '' )
            ->update_setting( 'default', '' )
            ->update_setting( 'wrapper_class', '' )
            ->update_setting( 'element_class', '' )
            ->save();

        $email_field_id = $field->get_id();

        // $field = Ninja_Forms()->form( $form_id )->field()->get();
        // $field->update_setting( 'type', 'textbox' )
        //     ->update_setting( 'label', 'Confirm Email')
        //     ->update_setting( 'label_pos', 'above' )
        //     ->update_setting( 'confirm_field', $email_field_id )
        //     ->update_setting( 'required', 1 )
        //     ->update_setting( 'order', 3 )
        //     ->save();

        $field = Ninja_Forms()->form( $form_id )->field()->get();
        $field->update_setting( 'type', 'textarea' )
            ->update_setting( 'label', 'Message')
            ->update_setting( 'label_pos', 'above' )
            ->update_setting( 'required', 1 )
            ->update_setting( 'order', 3 )
            ->update_setting( 'key', 'message' )
            ->update_setting( 'placeholder', '' )
            ->update_setting( 'default', '' )
            ->update_setting( 'wrapper_class', '' )
            ->update_setting( 'element_class', '' )
            ->save();

        // $field = Ninja_Forms()->form( $form_id )->field()->get();
        // $field->update_setting( 'type', 'textbox' )
        //     ->update_setting( 'label', 'Mirror Name')
        //     ->update_setting( 'label_pos', 'above' )
        //     ->update_setting( 'required', 1 )
        //     ->update_setting( 'mirror_field', $name_field_id )
        //     ->update_setting( 'order', 5 )
        //     ->save();

        $options = array(
            array(
                'label' => 'One',
                'value' => '1',
                'calc' => 1,
                'order' => 1,
                'selected' => 0,
            ),
            array(
                'label' => 'Two',
                'value' => '2',
                'calc' => 2,
                'order' => 2,
                'selected' => 1,
            ),
            array(
                'label' => 'Three',
                'value' => '3',
                'calc' => 3,
                'order' => 3,
                'selected' => 0,
            ),
        );

        $field = Ninja_Forms()->form( $form_id )->field()->get();
        $field->update_setting( 'type', 'listradio' )
            ->update_setting( 'label_pos', 'above')
            ->update_setting( 'label', "James' List")
            ->update_setting( 'required', 0)
            ->update_setting( 'options', $options)
            ->update_setting( 'order', 4 )
            ->update_setting( 'key', 'james_list' )
            ->update_setting( 'placeholder', '' )
            ->update_setting( 'default', '' )
            ->update_setting( 'wrapper_class', '' )
            ->update_setting( 'element_class', '' )
            ->save();

        $field = Ninja_Forms()->form( $form_id )->field()->get();
        $field->update_setting( 'type', 'submit' )
            ->update_setting( 'label', 'Submit')
            ->update_setting( 'processing_label', 'Processing' )
            ->update_setting( 'order', 5 )
            ->save();

        /*
         * ACTIONS
         */

        $action = Ninja_Forms()->form( $form_id )->action()->get();
        $action->update_setting( 'label',  'Mock Success Message Action' )
            ->update_setting( 'type', 'successmessage' )
            ->update_setting( 'message', 'Thank you {field:name} for filling out my form!' )
            ->update_setting( 'active', TRUE )
            ->save();

//        $action = Ninja_Forms()->form( $form_id )->action()->get();
//        $action->update_setting( 'label',  'Mock Redirect Action' )
//            ->update_setting( 'type', 'redirect' )
//            ->update_setting( 'url', 'http://kstover.codes' )
//            ->update_setting( 'active', 0 )
//            ->save();

        $action = Ninja_Forms()->form( $form_id )->action()->get();
        $action->update_setting( 'label',  'Mock Email Action' )
            ->update_setting( 'type', 'email' )
            ->update_setting( 'to', 'myformbuildingbringsallthedeveloperstotheyard@wpninjas.com' )
            ->update_setting( 'subject', 'This is an email action.' )
            ->update_setting( 'message', 'Hello, Ninja Forms!' )
            ->update_setting( 'active', FALSE )
            ->save();

//        $action = Ninja_Forms()->form( $form_id )->action()->get();
//        $action->update_setting( 'label',  'Run WordPress Action' )
//            ->update_setting( 'type', 'custom' )
//            ->update_setting( 'hook', 'action' )
//            ->update_setting( 'tag', 'blarg_action' )
//            ->save();

        $action = Ninja_Forms()->form( $form_id )->action()->get();
        $action->update_setting( 'label',  'Mock Save Action' )
            ->update_setting( 'type', 'save' )
            ->update_setting( 'active', TRUE )
            ->save();

        /*
         * SUBMISSIONS
         */

        $sub = Ninja_Forms()->form( $form_id )->sub()->get();
        $sub->update_field_value( 1, 'Kyle Johnson' )
            ->update_field_value( 2, 'kyle@wpninjas.com' )
            ->update_field_value( 3, 'This is a test' )
            ->update_field_value( 4, '2' )
            ->update_field_value( 5, 'Kyle Johnson' );
        $sub->save();

        // Delay Execution for different submission dates
        sleep(1);

        $sub = Ninja_Forms()->form( $form_id )->sub()->get();
        $sub->update_field_value( 1, 'John Doe' )
            ->update_field_value( 2, 'user@gmail.com' )
            ->update_field_value( 3, 'This is another test.' )
            ->update_field_value( 4, '3' )
            ->update_field_value( 5, 'John Doe' );
        $sub->save();
    }

    public function form_contact_form_2()
    {
        /*
         * FORM
         */

        $form = Ninja_Forms()->form()->get();
        $form->update_setting( 'title', 'Get Help' );
        $form->save();

        $form_id = $form->get_id();

        /*
         * FIELDS
         */

        $fields = array(
            array(
                'type' 			=> 'textbox',
                'label'			=> 'Name',
                'label_pos' 	=> 'above',
                'order'         => 1,
                'key'           => 'name',
            ),
            array(
                'type'			=> 'email',
                'label'			=> 'Email',
                'label_pos'		=> 'above',
                'order'         => 2,
                'key'           => 'email',
            ),
            array(
                'type' 			=> 'textarea',
                'label'			=> 'What Can We Help You With?',
                'label_pos'		=> 'above',
                'order'         => 3,
                'key'           => 'message',
            ),
            array(
                'type' 			=> 'checkbox',
                'label'			=> 'Agree?',
                'label_pos'		=> 'right',
                'order'         => 4,
                'key'           => 'agree',
            ),
            array(
                'type' 			=> 'listradio',
                'label'			=> 'Best Contact Method?',
                'label_pos'		=> 'above',
                'options'		=> array(
                    array(
                        'label'	=> 'Phone',
                        'value'	=> 'phone',
                        'calc'  => '',
                        'order' => 1,
                        'selected' => 0,
                    ),
                    array(
                        'label'	=> 'Email',
                        'value'	=> 'email',
                        'calc'  => '',
                        'order' => 2,
                        'selected' => 0,
                    ),
                    array(
                        'label'	=> 'Snail Mail',
                        'value'	=> 'snail-mail',
                        'calc'  => '',
                        'order' => 3,
                        'selected' => 0,
                    ),
                ),
                'show_other'	=> 1,
                'required'      => 1,
                'order'         => 5,
                'key'           => 'contact_method',
            ),
            array(
                'type'			=> 'submit',
                'label'			=> 'Send',
                'order'         => 6,
            )
        );

        foreach( $fields as $settings ){

            $field = Ninja_Forms()->form( $form_id )->field()->get();
            $field->update_settings( $settings )->save();
        }

        /*
         * ACTIONS
         */

        $action = Ninja_Forms()->form( $form_id )->action()->get();
        $action->update_setting( 'label',  'Mock Save Action' )
            ->update_setting( 'type', 'save' )
            ->save();
    }

    public function form_kitchen_sink()
    {
        /*
         * FORM
         */
        $form = Ninja_Forms()->form()->get();
        $form->update_setting( 'title', 'Kitchen Sink' );
        $form->save();

        $form_id = $form->get_id();

        /*
         * FIELDS
         */

        $fields = array(
            array(
                'type' 			=> 'textbox',
                'label'			=> 'Textbox',
                'key'           => 'textbox',
            ),
            array(
                'type' 			=> 'firstname',
                'label'			=> 'First Name',
                'key'           => 'first_name',
            ),
            array(
                'type' 			=> 'lastname',
                'label'			=> 'Last Name',
                'key'           => 'last_name',
            ),
            array(
                'type' 			=> 'hidden',
                'label'			=> 'Hidden',
                'label_pos' 	=> 'hidden',
                'key'           => 'hidden',
            ),
            array(
                'type' 			=> 'textarea',
                'label'			=> 'Textarea',
                'key'           => 'textarea',
            ),
            array(
                'type' 			=> 'listselect',
                'label'			=> 'Select List',
                'options'      => array(
                    array(
                        'label' => 'Option One',
                        'value' => 1,
                        'calc'  => '',
                        'order' => 1,
                        'selected' => 0,
                    ),
                    array(
                        'label' => 'Option Two',
                        'value' => 2,
                        'calc'  => '',
                        'order' => 2,
                        'selected' => 0,
                    ),
                    array(
                        'label' => 'Option Three',
                        'value' => 3,
                        'calc'  => '',
                        'order' => 3,
                        'selected' => 0,
                    )
                ),
                'key'           => 'select_list',
            ),
            array(
                'type' 			=> 'listradio',
                'label'			=> 'Radio List',
                'options'       => array(
                    array(
                        'label' => 'Option One',
                        'value' => 1,
                        'calc'  => '',
                        'order' => 1,
                        'selected' => 0,
                    ),
                    array(
                        'label' => 'Option Two',
                        'value' => 2,
                        'calc'  => '',
                        'order' => 2,
                        'selected' => 0,
                    ),
                    array(
                        'label' => 'Option Three',
                        'value' => 3,
                        'calc'  => '',
                        'order' => 3,
                        'selected' => 0,
                    )
                ),
                'key'           => 'radio_list',
            ),
            array(
                'type' 			=> 'checkbox',
                'label'			=> 'Checkbox',
                'key'           => 'checkbox',
            ),
            // array(
            //     'type' 			=> 'button',
            //     'label'			=> 'Button',
            //     'label_pos' 	=> 'hidden',
            // ),
        );

        $order = 1;
        $i = 1;
        foreach( array( 'above', 'right', 'below', 'left', 'hidden' ) as $label_pos ) {


            foreach ($fields as $settings) {

                unset($settings['id']);

                $settings[ 'key' ] = $settings[ 'key' ] . '-' . $i;

                if ( ! isset( $settings['label_pos'] ) ) $settings['label_pos'] = $label_pos;

                if ( 'submit' != $settings['type'] ) $settings['label'] = $settings['label'] . ' - label ' . $label_pos;

                if ( 'hidden' == $settings['label_pos'] && 'submit' != $settings['type'] ) $settings['placeholder'] = $settings['label'];

                $field = Ninja_Forms()->form($form_id)->field()->get();

                $settings[ 'order' ] = $order;

                $field->update_settings($settings)->save();

                $order++;
            }
            $order++;
            $i++;
        }

        $submit = Ninja_Forms()->form($form_id)->field()->get();
        $submit->update_setting( 'label', 'Submit')
                ->update_setting( 'type', 'submit' )
                ->update_setting( 'order', $order)
                ->save();

        $action = Ninja_Forms()->form( $form_id )->action()->get();
        $action->update_setting( 'label',  'Mock Save Action' )
            ->update_setting( 'type', 'save' )
            ->save();
    }

    public function form_bathroom_sink()
    {
        /*
         * FORM
         */
        $form = Ninja_Forms()->form()->get();
        $form->update_setting( 'title', 'Bathroom Sink' );
        $form->save();

        $form_id = $form->get_id();

        /*
         * FIELDS
         */

        $fields = array(
            array(
                'type' 			=> 'textbox',
                'label'			=> 'Textbox',
                'key'           => 'textbox',
            ),
            array(
                'type' 			=> 'firstname',
                'label'			=> 'First Name',
                'key'           => 'first_name',
            ),
            array(
                'type' 			=> 'lastname',
                'label'			=> 'Last Name',
                'key'           => 'last_name',
            ),
            array(
                'type' 			=> 'hidden',
                'label'			=> 'Hidden',
                'label_pos' 	=> 'hidden',
                'key'           => 'hidden',
            ),
            array(
                'type' 			=> 'textarea',
                'label'			=> 'Textarea',
                'key'           => 'textarea',
            ),
            array(
                'type' 			=> 'listselect',
                'label'			=> 'Select List',
                'options'      => array(
                    array(
                        'label' => 'Option One',
                        'value' => 1,
                        'calc'  => '',
                        'order' => 1,
                        'selected' => 0,
                    ),
                    array(
                        'label' => 'Option Two',
                        'value' => 2,
                        'calc'  => '',
                        'order' => 2,
                        'selected' => 0,
                    ),
                    array(
                        'label' => 'Option Three',
                        'value' => 3,
                        'calc'  => '',
                        'order' => 3,
                        'selected' => 0,
                    )
                ),
                'key'           => 'select_list',
            ),
            array(
                'type' 			=> 'listradio',
                'label'			=> 'Radio List',
                'options'       => array(
                    array(
                        'label' => 'Option One',
                        'value' => 1,
                        'calc'  => '',
                        'order' => 1,
                        'selected' => 0,
                    ),
                    array(
                        'label' => 'Option Two',
                        'value' => 2,
                        'calc'  => '',
                        'order' => 2,
                        'selected' => 0,
                    ),
                    array(
                        'label' => 'Option Three',
                        'value' => 3,
                        'calc'  => '',
                        'order' => 3,
                        'selected' => 0,
                    )
                ),
                'key'           => 'radio_list',
            ),
            array(
                'type' 			=> 'checkbox',
                'label'			=> 'Checkbox',
                'key'           => 'checkbox',
            ),
            // array(
            //     'type' 			=> 'button',
            //     'label'			=> 'Button',
            //     'label_pos' 	=> 'hidden',
            // ),
        );

        $order = 1;
        foreach ($fields as $settings) {

            unset($settings['id']);

            $field = Ninja_Forms()->form($form_id)->field()->get();

            $settings[ 'order' ] = $order;

            $settings[ 'label_pos' ] = 'above';

            $field->update_settings($settings)->save();

            $order++;
        }

        $submit = Ninja_Forms()->form($form_id)->field()->get();
        $submit->update_setting( 'label', 'Submit')
            ->update_setting( 'type', 'submit' )
            ->update_setting( 'order', $order)
            ->update_setting( 'process_label', 'processing' )
            ->save();

        $action = Ninja_Forms()->form( $form_id )->action()->get();
        $action->update_setting( 'label',  'Mock Save Action' )
            ->update_setting( 'type', 'save' )
            ->save();
    }

    public function form_long_form( $num_fields = 500 )
    {
        /*
        * FORM
        */

        $form = Ninja_Forms()->form()->get();
        $form->update_setting( 'title', 'Long Form - ' . $num_fields . ' Fields' );
        $form->save();

        $form_id = $form->get_id();

        /*
         * FIELDS
         */

        for( $i = 1; $i <= $num_fields; $i++ ) {
            $field = Ninja_Forms()->form($form_id)->field()->get();
            $field->update_setting( 'type', 'textbox' )
                ->update_setting( 'label', 'Field #' . $i )
                ->update_setting( 'label_pos', 'above' )
                ->update_setting( 'required', 0 )
                ->update_setting( 'order', $i )
                ->update_setting( 'key', 'field_' . $i )
                ->save();
        }
    }

    public function form_email_submission()
    {
        /*
         * FORM
         */

        $form = Ninja_Forms()->form()->get();
        $form->update_setting( 'title', 'Email Subscription Form' );
        $form->save();

        $form_id = $form->get_id();

        /*
         * FIELDS
         */

        $field = Ninja_Forms()->form( $form_id )->field()->get();
        $field->update_setting( 'type', 'email' )
            ->update_setting( 'label', 'Email Address')
            ->update_setting( 'label_pos', 'hidden' )
            ->update_setting( 'required', 1 )
            ->update_setting( 'order', 1 )
            ->update_setting( 'placeholder', 'Enter your email address' )
            ->update_setting( 'wrapper_class', 'three-fourths first' )
            ->update_setting( 'key', 'email' )
            ->save();

        $email_field_id = $field->get_id();

        $field = Ninja_Forms()->form( $form_id )->field()->get();
        $field->update_setting( 'type', 'submit' )
            ->update_setting( 'label', 'Subscribe')
            ->update_setting( 'order', 5 )
            ->update_setting( 'wrapper_class', 'one-fourth' )
            ->save();

    }

    public function form_product_1()
    {
        /* FORM */
        $form = Ninja_Forms()->form()->get();
        $form->update_setting( 'title', 'Product Form (with Quantity Field)' );
        $form->update_setting( 'hide_successfully_completed_form', 1 );
        $form->save();

        $form_id = $form->get_id();

        /* Fields */
        $field = Ninja_Forms()->form( $form_id )->field()->get();
        $field->update_setting( 'type', 'product' )
            ->update_setting( 'label', 'Product')
            ->update_setting( 'label_pos', 'above' )
            ->update_setting( 'product_price', 10.10 )
            ->update_setting( 'product_use_quantity', 0 )
            ->update_setting( 'order', 1 )
            ->update_setting( 'key', 'product' )
            ->save();

        $product_field_id = $field->get_id();

        $field = Ninja_Forms()->form( $form_id )->field()->get();
        $field->update_setting( 'type', 'quantity' )
            ->update_setting( 'label', 'Quantity')
            ->update_setting( 'label_pos', 'above' )
            ->update_setting( 'product_assignment', $product_field_id )
            ->update_setting( 'default', 1 )
            ->update_setting( 'num_min', 1 )
            ->update_setting( 'num_max', NULL )
            ->update_setting( 'num_step', 1 )
            ->update_setting( 'order', 2 )
            ->update_setting( 'key', 'quantity' )
            ->save();

        $quantity_field_id = $field->get_id();

        $field = Ninja_Forms()->form( $form_id )->field()->get();
        $field->update_setting( 'type', 'shipping' )
            ->update_setting( 'label', 'Shipping')
            ->update_setting( 'label_pos', 'above' )
            ->update_setting( 'shipping_cost', 2.00 )
            ->update_setting( 'order', 4 )
            ->update_setting( 'key', 'shipping' )
            ->save();

        $field = Ninja_Forms()->form( $form_id )->field()->get();
        $field->update_setting( 'type', 'total' )
            ->update_setting( 'label', 'Total')
            ->update_setting( 'label_pos', 'above' )
            ->update_setting( 'key', 'total' )
            ->update_setting( 'order', 5 )
            ->update_setting( 'key', 'total' )
            ->save();

        $field = Ninja_Forms()->form( $form_id )->field()->get();
        $field->update_setting( 'type', 'submit' )
            ->update_setting( 'label', 'Purchase')
            ->update_setting( 'order', 1000 )
            ->save();

        /*
         * ACTIONS
         */

        $action = Ninja_Forms()->form( $form_id )->action()->get();
        $action->update_setting( 'label',  'Success Message' )
            ->update_setting( 'type', 'successmessage' )
            ->update_setting( 'message', '<div style="border: 2px solid green; padding: 10px; color: green;">You purchased {field:' . $quantity_field_id . '} product(s) for ${field:total}.</div>' )
            ->save();
    }

    public function form_product_2()
    {
        /* FORM */
        $form = Ninja_Forms()->form()->get();
        $form->update_setting( 'title', 'Product Form (Inline Quantity)' );
        $form->update_setting( 'hide_successfully_completed_form', 1 );
        $form->save();

        $form_id = $form->get_id();

        /* Fields */
        $field = Ninja_Forms()->form( $form_id )->field()->get();
        $field->update_setting( 'type', 'product' )
            ->update_setting( 'label', 'Product')
            ->update_setting( 'label_pos', 'above' )
            ->update_setting( 'product_price', 10.10 )
            ->update_setting( 'product_use_quantity', 1 )
            ->update_setting( 'order', 1 )
            ->update_setting( 'key', 'product' )
            ->save();

        $product_field_id = $field->get_id();

        $field = Ninja_Forms()->form( $form_id )->field()->get();
        $field->update_setting( 'type', 'shipping' )
            ->update_setting( 'label', 'Shipping')
            ->update_setting( 'label_pos', 'above' )
            ->update_setting( 'shipping_cost', 2.00 )
            ->update_setting( 'order', 4 )
            ->update_setting( 'key', 'shipping' )
            ->save();

        $field = Ninja_Forms()->form( $form_id )->field()->get();
        $field->update_setting( 'type', 'total' )
            ->update_setting( 'label', 'Total')
            ->update_setting( 'label_pos', 'above' )
            ->update_setting( 'key', 'total' )
            ->update_setting( 'order', 5 )
            ->update_setting( 'key', 'total' )
            ->save();

        $field = Ninja_Forms()->form( $form_id )->field()->get();
        $field->update_setting( 'type', 'submit' )
            ->update_setting( 'label', 'Purchase')
            ->update_setting( 'order', 1000 )
            ->save();

        /*
         * ACTIONS
         */

        $action = Ninja_Forms()->form( $form_id )->action()->get();
        $action->update_setting( 'label',  'Success Message' )
            ->update_setting( 'type', 'successmessage' )
            ->update_setting( 'message', '<div style="border: 2px solid green; padding: 10px; color: green;">You purchased {field:' . $product_field_id . '} product(s) for ${field:total}.</div>' )
            ->save();
    }

    public function form_product_3()
    {
        /* FORM */
        $form = Ninja_Forms()->form()->get();
        $form->update_setting( 'title', 'Product Form (Multiple Products)' );
        $form->update_setting( 'hide_successfully_completed_form', 1 );
        $form->save();

        $form_id = $form->get_id();

        /* Fields */
        $field = Ninja_Forms()->form( $form_id )->field()->get();
        $field->update_setting( 'type', 'product' )
            ->update_setting( 'label', 'Product A')
            ->update_setting( 'label_pos', 'above' )
            ->update_setting( 'product_price', 10.10 )
            ->update_setting( 'product_use_quantity', 0 )
            ->update_setting( 'order', 1 )
            ->update_setting( 'key', 'product_a' )
            ->save();

        $product_field_A_id = $field->get_id();

        $field = Ninja_Forms()->form( $form_id )->field()->get();
        $field->update_setting( 'type', 'quantity' )
            ->update_setting( 'label', 'Quantity for Product A')
            ->update_setting( 'label_pos', 'above' )
            ->update_setting( 'product_assignment', $product_field_A_id )
            ->update_setting( 'default', 1 )
            ->update_setting( 'num_min', 1 )
            ->update_setting( 'num_max', NULL )
            ->update_setting( 'num_step', 1 )
            ->update_setting( 'order', 2 )
            ->update_setting( 'key', 'qauntity_for_product_a' )
            ->save();

        $quantity_field_A_id = $field->get_id();

        $field = Ninja_Forms()->form( $form_id )->field()->get();
        $field->update_setting( 'type', 'product' )
            ->update_setting( 'label', 'Product B')
            ->update_setting( 'label_pos', 'above' )
            ->update_setting( 'product_price', 9.23 )
            ->update_setting( 'product_use_quantity', 0 )
            ->update_setting( 'order', 3 )
            ->update_setting( 'key', 'product_b' )
            ->save();

        $product_field_B_id = $field->get_id();

        $field = Ninja_Forms()->form( $form_id )->field()->get();
        $field->update_setting( 'type', 'quantity' )
            ->update_setting( 'label', 'Quantity for Product B')
            ->update_setting( 'label_pos', 'above' )
            ->update_setting( 'product_assignment', $product_field_B_id )
            ->update_setting( 'default', 1 )
            ->update_setting( 'num_min', 1 )
            ->update_setting( 'num_max', NULL )
            ->update_setting( 'num_step', 1 )
            ->update_setting( 'order', 4 )
            ->update_setting( 'key', 'quantity_for_product_b' )
            ->save();

        $quantity_field_B_id = $field->get_id();

        $field = Ninja_Forms()->form( $form_id )->field()->get();
        $field->update_setting( 'type', 'shipping' )
            ->update_setting( 'label', 'Shipping')
            ->update_setting( 'label_pos', 'above' )
            ->update_setting( 'shipping_cost', 2.00 )
            ->update_setting( 'order', 998 )
            ->update_setting( 'key', 'shipping' )
            ->save();

        $field = Ninja_Forms()->form( $form_id )->field()->get();
        $field->update_setting( 'type', 'total' )
            ->update_setting( 'label', 'Total')
            ->update_setting( 'label_pos', 'above' )
            ->update_setting( 'key', 'total' )
            ->update_setting( 'order', 999 )
            ->update_setting( 'key', 'total' )
            ->save();

        $field = Ninja_Forms()->form( $form_id )->field()->get();
        $field->update_setting( 'type', 'submit' )
            ->update_setting( 'label', 'Purchase')
            ->update_setting( 'order', 1000 )
            ->save();

        /*
         * ACTIONS
         */

        $action = Ninja_Forms()->form( $form_id )->action()->get();
        $action->update_setting( 'label',  'Success Message' )
            ->update_setting( 'type', 'successmessage' )
            ->update_setting( 'message', '<div style="border: 2px solid green; padding: 10px; color: green;">You purchased {field:' . $quantity_field_A_id . '} of Product A and {field:' . $quantity_field_B_id . '} of Product B for ${field:total}.</div>' )
            ->save();
    }

    public function form_calc_form()
    {
        /*
         * FORM
         */

        $form = Ninja_Forms()->form()->get();
        $form->update_setting( 'title', 'Form with Calculations' );
        $form->update_setting( 'calculations', array(
            array(
                'name' => 'My First Calculation',
                'eq' => '2 * 3'
            ),
            array(
                'name' => 'My Second Calculation',
                'eq' => '4 + 1'
            )
        ));
        $form->save();

        $form_id = $form->get_id();

        $field = Ninja_Forms()->form( $form_id )->field()->get();
        $field->update_setting( 'type', 'submit' )
            ->update_setting( 'label', 'Purchase')
            ->update_setting( 'order', 1000 )
            ->save();

        $action = Ninja_Forms()->form( $form_id )->action()->get();
        $action->update_setting( 'label',  'Success Message' )
            ->update_setting( 'type', 'successmessage' )
            ->update_setting( 'message', 'Calculations are returned with the AJAX response ( response -> data -> calcs' )
            ->save();
    }

    private function _migrate()
    {
        $migrations = new NF_Database_Migrations();
        $migrations->nuke(TRUE, TRUE);

        $posts = get_posts('post_type=nf_sub&numberposts=-1');
        foreach ($posts as $post) {
            wp_delete_post($post->ID, TRUE);
        }

        $migrations->migrate();
    }

} // END CLASS NF_Database_MockData
